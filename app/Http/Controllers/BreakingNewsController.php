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
use App\Models\BreakingNews;

class BreakingNewsController extends Controller
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
            $title = 'Breaking News';
            $url = route('breaking_news-index');
            
            $results = self::search($request);

            // ajax search
            if ($request->ajax()) {                
                return view('breaking_news.partials.listing', compact('results'));
            }

            $imageTypes = getImageTypes();

            // on page load            
            return view('breaking_news.index', compact('results', 'title', 'url', 'imageTypes'));

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

        $query = BreakingNews::query();

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
            $title = 'Breaking News:Add';
            $url = url('breaking_news/create');
            $rowInfo = new BreakingNews;

            return view('breaking_news.create', compact('rowInfo', 'url', 'title'));
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
                'title' => 'required|max:255',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return back()
                ->withInput($request->input()) // Flashes inputs
                ->withErrors($validator)
                ->with('error', 'Error in save, Please resolve these error first then try again.');
            }
            
            if($record = BreakingNews::create($input)) {
                return redirect()->route('breaking_news-index')->with('success', 'Record Saved Successfully');
            } else {
                return redirect()->route('breaking_news-index')->with('error', 'Error in record saving time please try again');
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
            $title = 'Breaking News:Edit';
            $url = url('breaking_news/update');

            $rowInfo = BreakingNews::findOrFail($id);
            
            if (empty($rowInfo)) {
                return redirect()->route('breaking_news-index')->with('error', 'Record not found');
            }

            return view('breaking_news.create', compact('rowInfo', 'url', 'title'));

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
            
            $record = BreakingNews::findOrFail($id);
            
            if (empty($record)) {
                return redirect()->route('breaking_news-index')->with('error', 'Record not found');
            }

            $oldImage = $record->image;
            
            $validator = validator::make($input, [
                //'user_id' => 'required',
                'title' => 'required|max:255',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return back()
                ->withInput($request->input()) // Flashes inputs
                ->withErrors($validator)
                ->with('error', 'Error in save, Please resolve these error first then try again.');
            }

            if($record->fill($input)->save()) {
                return redirect()->route('breaking_news-index')->with('success', 'Record Updated Successfully');
            } else {
                return redirect()->route('breaking_news-index')->with('error', 'Error in record update, Please try again.');
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
            $title = 'Breaking News:View';
            $sub_title = 'Breaking News View';
            
            $rowInfo = BreakingNews::findOrFail($id);

            if (empty($rowInfo)) {
                return redirect()->route('breaking_news-index')->with('error', 'Record not found');
            }

            return view('breaking_news.view', compact('rowInfo', 'title', 'sub_title'));
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

            $record = BreakingNews::findOrFail($id);

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

            $record = BreakingNews::findOrFail($id);

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
