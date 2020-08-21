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

class UsersController extends Controller
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
            $title = 'Users';
            $url = route('users-index');

            $results = self::search($request);

            // ajax search
            if ($request->ajax()) {                
                return view('users.partials.listing', compact('results'));
            }

            // on page load            
            return view('users.index', compact('results', 'title', 'url'));

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

        $query = User::query()->where('role', 'User');

        // ajax search
        if (!empty($request->search)) {
            $query->where(function ($subquery) use ($request) {
                $subquery->where('name', 'like', "%$request->search%");
                $subquery->orWhere('email', 'like', "%$request->search%");
                $subquery->orWhere('mobile', 'like', "%$request->search%");
            });
        }

        if (isset($request->is_verified) && $request->is_verified != '') {
            $is_verified = (!empty($request->is_verified)) ? true : false;
            $query->where('is_verified', $is_verified);
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
            $title = 'User:Add';
            $url = url('users/create');
            $rowInfo = new User;

            return view('users.create', compact('rowInfo', 'url', 'title'));
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
            
            $input['role'] = 'User';
            $input['is_verified'] = true;
            
            unset($input['image']); // remove image from fields
            //dd($input);

            $validator = validator::make($input, [
                'name' => 'required|max:100',
                'mobile' => 'required|max:14|unique:users',
                'email' => 'required|max:191|email:rfc,dns|unique:users',
                //'password' => 'required',
                //'image' => 'required|max:255',
                'address' => 'required|max:255',
                'status' => 'required',
            ]);
            
            if ($validator->fails()) {
                return back()
                ->withInput($request->input()) // Flashes inputs
                ->withErrors($validator)
                ->with('error', 'Error in save, Please resolve these error first then try again.');
            }

            // // Password and Confirm Password Validatio
            // if(trim($input['password']) !== trim($input['confirm_password'])) {
            //     return back()->with('error', 'Password and Confirm Password should be same.');
            // }

            // // Hash Password
             $input['password'] = Hash::make(trim($input['password']));

            if($record = User::create($input)) {

                $id = $record->id;

                // upload images
                if($request->hasFile('image')) {

                    // create directory to upload images in it
                    createUserImageDirectories($id);

                    $images = [];
                    foreach ($allImages as $key=>$image) {
                        
                        $image_name = '';
                        $uploadpath = public_path('uploads/users/'.$id.'/');
                        $original_name = $image->getClientOriginalName();

                        if (!$image->isValid() || empty($uploadpath)) {
                            return $image_name;
                        }

                        if ($image->isValid()) {
                            $image_prefix = 'user_' . rand(0, 999999999) . '_' . date('d_m_Y_h_i_s');
                            $ext = $image->getClientOriginalExtension();
                            $image_name = $image_prefix . '.' . $ext;
                            $image_array[] = $image_name;
                            $image_resize = Image::make($image->getRealPath());
                            $image_resize->resize(1024, 1024);
                            $image_resize->save(public_path('uploads/users/'.$id.'/' .$image_name));
                            $image_resize->resize(75, 75);
                            $image_resize->save(public_path('uploads/users/'.$id.'/thumb/' .$image_name));
                            $image_resize->resize(480,320);
                            $image_resize->save(public_path('uploads/users/'.$id.'/medium/' .$image_name));
                            $image->move($uploadpath, $image_name);

                            $images[] = $image_name;
                        }
                    }
                    
                    if(!empty($images)) {
                        $input['image'] = $images[0];
                        $record->fill($input)->save();
                    }
                }
                
                return redirect()->route('users-index')->with('success', 'Record Saved Successfully');
            } else {
                return redirect()->route('users-index')->with('error', 'Error in record saving time please try again');
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
            $title = 'User:Edit';
            $url = url('users/update');

            $rowInfo = User::where('role', 'User')->findOrFail($id);

            if (empty($rowInfo)) {
                return redirect()->route('users-index')->with('error', 'Record not found');
            }

            return view('users.create', compact('rowInfo', 'url', 'title'));

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
            unset($input['image']); // remove image from update fields
            unset($input['email']); // remove email from update fields
            
            $record = User::where('role', 'User')->findOrFail($id);
            
            if (empty($record)) {
                return redirect()->route('users-index')->with('error', 'Record not found');
            }
            
            $oldImage = $record->image;
            
            $validator = validator::make($input, [
                'name' => 'required|max:100',
                'mobile' => 'required|max:14|unique:users,mobile,'.$id,
                //'email' => 'required|max:255|email:rfc,dns|unique:users,email,'.$id,
                //'image' => 'required|max:255',
                'address' => 'required|max:255',
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
                    createUserImageDirectories($id);

                    $images = [];
                    foreach ($allImages as $key=>$image) {
                        
                        $image_name = '';
                        $uploadpath = public_path('uploads/users/'.$id.'/');
                        $original_name = $image->getClientOriginalName();

                        if (!$image->isValid() || empty($uploadpath)) {
                            return $image_name;
                        }

                        if ($image->isValid()) {
                            $image_prefix = 'user_' . rand(0, 999999999) . '_' . date('d_m_Y_h_i_s');
                            $ext = $image->getClientOriginalExtension();
                            $image_name = $image_prefix . '.' . $ext;
                            $image_array[] = $image_name;
                            $image_resize = Image::make($image->getRealPath());
                            $image_resize->resize(1024, 1024);
                            $image_resize->save(public_path('uploads/users/'.$id.'/' .$image_name));
                            $image_resize->resize(75, 75);
                            $image_resize->save(public_path('uploads/users/'.$id.'/thumb/' .$image_name));
                            $image_resize->resize(480,320);
                            $image_resize->save(public_path('uploads/users/'.$id.'/medium/' .$image_name));
                            $image->move($uploadpath, $image_name);

                            $images[] = $image_name;
                        }
                    }
                    
                    if(!empty($images)) {
                        $input['image'] = $images[0];
                        $record->fill($input)->save();

                        unlinkOldImages($oldImage, 'users/'.$id);
                    }
                }
                
                return redirect()->route('users-index')->with('success', 'Record Updated Successfully');
            } else {
                return redirect()->route('users-index')->with('error', 'Error in record update, Please try again.');
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
            $title = 'User:View';
            $sub_title = 'User View';
            
            $rowInfo = User::where('role', 'User')->findOrFail($id);

            if (empty($rowInfo)) {
                return redirect()->route('users-index')->with('error', 'Record not found');
            }

            return view('users.view', compact('rowInfo', 'title', 'sub_title'));
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

            $record = User::where('role', 'User')->findOrFail($id);

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

            $record = User::where('role', 'User')->findOrFail($id);

            if (empty($record)) {
                $result = array('status' => 'error', 'message' => 'Record not found');
            }

            $record->status = (empty($record->status)) ? true : false;

            if ($record->save()) {

                // $status = ($record->status) ? 
                // '<a href="javascript:;" class="btn btn-warning btn-sm" title="Pending">Pending</a>'
                // : '<a href="javascript:;" class="btn btn-success btn-sm" title="Verified">Verified</a>';
                
                $status = ($record->status) ? 
                '<a href="javascript:;" class="btn btn-success btn-sm" title="Verified">Verified</a>'
                : '<a href="javascript:;" class="btn btn-warning btn-sm" title="Pending">Pending</a>';


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

    /**
     * Show the form for change password.
     * @return Response
     */
    public function changePassword($id) {

        try {        
            $title = 'User:Change Password';
            $url = url('users/update-password');

            $rowInfo = User::where('role', 'User')->findOrFail($id);

            if (empty($rowInfo)) {
                return redirect()->route('users-index')->with('error', 'Record not found');
            }

            return view('users.update-password', compact('rowInfo', 'url', 'title'));
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Update Password method
     * @param Request $request
     * @return type
     */
    public function updatePassword(Request $request) {
        
        try {
            $input = $request->all();
            $id = $input['id'];
            
            if(empty(trim($input['password']))) {
                return back()->with('error', 'Password is required field');
            }
            
            if(trim($input['password']) !== trim($input['confirm_password'])) {
                return back()->with('error', 'Password and Confirm Password should be same.');
            }

            $record = User::where('role', 'User')->findOrFail($id);
            
            if (empty($record)) {
                return redirect()->route('users-index')->with('error', 'Record not found');
            }

            //Change Password            
            if ($record->update(['password'=>Hash::make(trim($request->get('password')))])) {                                 
                return redirect()->route('users-index')->with('success', 'Password Updated Successfully');
            } else {
                return redirect()->route('users-index')->with('error', 'Error in record update, Please try again.');
            }
        
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * verify the specified resource.
     * @return Response
     */
    public function verify(Request $request) {
        
        try {

            $input = $request->all();
            $id = $input['id'];

            $record = User::where('role', 'User')->findOrFail($id);

            if (empty($record)) {
                $result = array('status' => 'error', 'message' => 'Record not found');
            }

            $record->is_verified = true;

            if ($record->save()) {

                $result = array(
                    'status' => 'success',
                    'message' => 'Status updated successfully.',
                );
            } else {

                $result = array('status' => 'error', 'message' => 'Error in verification please try again.');
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $result = array('status' => 'error', 'message' => $errorMsg);
        }

        return $result;
    }
	
}
