<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use URL;
use DB;
use Image;
use Hash;
use App\User;
use App\Models\Gallery;

class GalleriesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request) {
        
        try {
            $title = 'Galleries';
            $url = route('galleries-index');
            
            $results = self::search($request);

            // ajax search
            if ($request->ajax()) {                
                return view('galleries.partials.listing', compact('results'));
            }

            $imageTypes = getImageTypes();

            // on page load            
            return view('galleries.index', compact('results', 'title', 'url', 'imageTypes'));

        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return redirect()->route('dashboard')->with('error', $errorMsg);
        }
    }

    /**
     * find records into database
     * @param object $request
     * @return row
     */
    public static function search($request = null)
    {

        $query = Gallery::query();

        // ajax search
        if (!empty($request->search)) {
            $query->where('title', 'like', "%$request->search%");
            // $query->where(function ($subquery) use ($request) {
            //     $subquery->where('title', 'like', "%$request->search%");
            //     $subquery->orWhere('author', 'like', "%$request->search%");
            // });
        }

        if (isset($request->status) && $request->status != '') {
            $status = (!empty($request->status)) ? true : false;
            $query->where('status', $status);
        }

        if (isset($request->image_type) && $request->image_type != '') {
            $query->where('image_type', $request->image_type);
        }

        $sort  = 'id';
        $order = 'DESC';
        if ((isset($request->sort) && $request->sort != '') || (isset($request->order)
            && $request->order != '')) {
            $sort  = $request->sort;
            $order = $request->order;
        }
        $query->orderBy($sort, $order);

        // on page load
        if(isset($request->export)) {
            $results =  $query->get();
        } else {
            $results = $query->paginate(config('siteconstants.PER_PAGE_LIMIT'));
        }

        return $results;
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function add() {

        try {
            $title = 'Gallery:Add';
            $url = url('galleries/create');
            $rowInfo = new Gallery;

            $imageTypes = getImageTypes();
            
            return view('galleries.create', compact('rowInfo', 'url', 'title', 'imageTypes'));
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Create a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function create(Request $request) {
        
        try {
            $allImages[] = $request->file('image');
            $input = $request->all();
            
            $validator = validator::make($input, [
                'user_id' => 'required',
                'image_type' => 'required',
                'title' => 'required|max:255',
                //'image' => 'required',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return back()
                ->withInput($request->input()) // Flashes inputs
                ->withErrors($validator)
                ->with('error', 'Error in save, Please resolve these error first then try again.');
            }
            
            if($record = Gallery::create($input)) {

                $id = $record->id;

                // upload image
                if($request->hasFile('image')) {

                    // create directory to upload images in it
                    createGalleryImageDirectories($id);

                    $images = [];
                    foreach ($allImages as $key=>$image) {
                        
                        $image_name = '';
                        $uploadpath = public_path('uploads/galleries/'.$id.'/');
                        $original_name = $image->getClientOriginalName();

                        if (!$image->isValid() || empty($uploadpath)) {
                            return $image_name;
                        }

                        if ($image->isValid()) {
                            $image_prefix = 'gallery_' . rand(0, 999999999) . '_' . date('YmdHis');
                            $ext = $image->getClientOriginalExtension();
                            $image_name = $image_prefix . '.' . $ext;
                            $image_array[] = $image_name;
                            $image_resize = Image::make($image->getRealPath());
                            $image_resize->resize(1024, 1024);
                            $image_resize->save(public_path('uploads/galleries/'.$id.'/large/' .$image_name));
                            $image_resize->resize(75, 75);
                            $image_resize->save(public_path('uploads/galleries/'.$id.'/thumb/' .$image_name));
                            $image_resize->resize(480,320);
                            $image_resize->save(public_path('uploads/galleries/'.$id.'/medium/' .$image_name));
                            $image->move($uploadpath, $image_name);

                            $images[] = $image_name;
                        }
                    }
                    
                    if(!empty($images)) {
                        $input['image'] = $images[0];
                        $record->fill($input)->save();
                    }
                }
                
                return redirect()->route('galleries-index')->with('success', 'Record Saved Successfully');
            } else {
                return redirect()->route('galleries-index')->with('error', 'Error in record saving time please try again');
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id) {
        
        try {
            $title = 'Gallery:Edit';
            $url = url('galleries/update');

            $rowInfo = Gallery::findOrFail($id);
            
            if (empty($rowInfo)) {
                return redirect()->route('galleries-index')->with('error', 'Record not found');
            }

            $imageTypes = getImageTypes();

            return view('galleries.create', compact('rowInfo', 'url', 'title', 'imageTypes'));

        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request) {

        try {
            $allImages[] = $request->file('image');
            $input = $request->all();
            $id = $input['id'];

            unset($input['user_id']);
            
            $record = Gallery::findOrFail($id);
            
            if (empty($record)) {
                return redirect()->route('galleries-index')->with('error', 'Record not found');
            }

            $oldImage = $record->image;
            
            $validator = validator::make($input, [
                //'user_id' => 'required',
                'image_type' => 'required',
                'title' => 'required|max:255',
                //'image' => 'required',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return back()
                ->withInput($request->input()) // Flashes inputs
                ->withErrors($validator)
                ->with('error', 'Error in save, Please resolve these error first then try again.');
            }

            if($record->fill($input)->save()) {
                
                // upload images
                if($request->hasFile('image')) {

                    // create directory to upload images in it
                    createGalleryImageDirectories($id);

                    $images = [];
                    foreach ($allImages as $key=>$image) {
                        
                        $image_name = '';
                        $uploadpath = public_path('uploads/galleries/'.$id.'/');
                        $original_name = $image->getClientOriginalName();

                        if (!$image->isValid() || empty($uploadpath)) {
                            return $image_name;
                        }

                        if ($image->isValid()) {
                            $image_prefix = 'gallery_' . rand(0, 999999999) . '_' . date('YmdHis');
                            $ext = $image->getClientOriginalExtension();
                            $image_name = $image_prefix . '.' . $ext;
                            $image_array[] = $image_name;
                            $image_resize = Image::make($image->getRealPath());
                            $image_resize->resize(1024, 1024);
                            $image_resize->save(public_path('uploads/galleries/'.$id.'/large/' .$image_name));
                            $image_resize->resize(75, 75);
                            $image_resize->save(public_path('uploads/galleries/'.$id.'/thumb/' .$image_name));
                            $image_resize->resize(480,320);
                            $image_resize->save(public_path('uploads/galleries/'.$id.'/medium/' .$image_name));
                            $image->move($uploadpath, $image_name);

                            $images[] = $image_name;
                        }
                    }
                    
                    if(!empty($images)) {
                        $input['image'] = $images[0];
                        $record->fill($input)->save();

                        unlinkOldImages($oldImage, 'galleries/'.$id);
                    }
                }
                
                return redirect()->route('galleries-index')->with('success', 'Record Updated Successfully');
            } else {
                return redirect()->route('galleries-index')->with('error', 'Error in record update, Please try again.');
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }
    
    /**
     * Show the view of specified resource.
     * @return Response
     */
    public function view($id) {
        
        try {
            $title = 'Gallery:View';
            $sub_title = 'Gallery View';
            
            $rowInfo = Gallery::findOrFail($id);

            if (empty($rowInfo)) {
                return redirect()->route('galleries-index')->with('error', 'Record not found');
            }

            return view('galleries.view', compact('rowInfo', 'title', 'sub_title'));
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function delete(Request $request) {
        try {

            $input = $request->all();
            $id = $input['id'];

            $record = Gallery::findOrFail($id);

            if (empty($record)) {
                $result = array('status' => 'error', 'message' => 'Record not found');
            }
            
            if ($record->delete()) {

                $result = array(
                    'status' => 'success',
                    'message' => 'Record deleted sucessfully.',
                );
            } else {

                $result = array('status' => 'error', 'message' =>'Error at delete time please try agian.');
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $result = array('status' => 'error', 'message' => $errorMsg);
        }

        return $result;
    }

    /**
     * active/deactive the specified resource.
     * @return Response
     */
    public function status(Request $request) {
        
        try {

            $input = $request->all();
            $id = $input['id'];

            $record = Gallery::findOrFail($id);

            if (empty($record)) {
                $result = array('status' => 'error', 'message' => 'Record not found');
            }

            $record->status = (empty($record->status)) ? true : false;

            if ($record->save()) {

                $status = ($record->status) ? 
                '<a href="javascript:;" class="btn btn-success btn-circle btn-sm" title="Disable"><i class="fas fa-check"></i></a>'
                : '<a href="javascript:;" class="btn btn-warning btn-circle btn-sm" title="Enable"><i class="fas fa-times"></i></a>';

                $result = array(
                    'status' => 'success',
                    'message' => 'Status updated successfully.',
                    'text' => $status
                );
            } else {

                $result = array('status' => 'error', 'message' => 'Error in status update please try again.');
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $result = array('status' => 'error', 'message' => $errorMsg);
        }

        return $result;
    }
	
}
