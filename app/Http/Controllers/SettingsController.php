<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use URL;
use Auth;
use Hash;
use Image;
use App\User;

class SettingsController extends Controller
{
    
    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function profile() {
        
        try {
            $title = 'Edit Profile';
            $url = url('settings/update-profile');

            $user = User::where('role', Auth::user()->role)->findOrFail(Auth::user()->id);
            
            if (empty($user)) {
                return redirect()->route('dashboard')->with('error', 'Record not found');
            }

            return view('settings.profile', compact('user', 'url', 'title'));

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
    public function updateProfile(Request $request) {

        try {
            $allImages[] = $request->file('image');
            $input = $request->all();
            $id = Auth::user()->id;
            unset($input['image']); // remove image from update fields
            unset($input['email']); // remove email from update fields
            
            $user = User::where('role', Auth::user()->role)->findOrFail($id);
            
            if (empty($user)) {
                return redirect()->route('dashboard')->with('error', 'Record not found');
            }
            
            $oldImage = $user->image;
            
            $validator = validator::make($input, [
                'name' => 'required|max:100',
                'mobile' => 'required|max:14|unique:users,mobile,'.$id,
            ]);
            if ($validator->fails()) {
                return back()
                ->withInput($request->input()) // Flashes inputs
                ->withErrors($validator)
                ->with('error', 'Error in save, Please resolve these error first then try again.');
            }

            if($user->fill($input)->save()) {
                
                // upload images
                if($request->hasFile('image')) {

                    // create directory to upload images in it
                    createRoleBasedImageDirectories($id);

                    $sub_directory = getRoleBasedImageDirectory();

                    $images = [];
                    foreach ($allImages as $key=>$image) {
                        
                        $image_name = '';
                        $uploadpath = public_path('uploads/'.$sub_directory.'/'.$id.'/');
                        $original_name = $image->getClientOriginalName();

                        if (!$image->isValid() || empty($uploadpath)) {
                            return $image_name;
                        }

                        if ($image->isValid()) {
                            $image_prefix = getRoleBasedImagePrefix();
                            $image_prefix = $image_prefix . rand(0, 999999999) . '_' . date('d_m_Y_h_i_s');
                            $ext = $image->getClientOriginalExtension();
                            $image_name = $image_prefix . '.' . $ext;
                            $image_array[] = $image_name;
                            $image_resize = Image::make($image->getRealPath());
                            $image_resize->resize(1024, 1024);
                            $image_resize->save(public_path('uploads/'.$sub_directory.'/'.$id.'/' .$image_name));
                            $image_resize->resize(75, 75);
                            $image_resize->save(public_path('uploads/'.$sub_directory.'/'.$id.'/thumb/' .$image_name));
                            $image_resize->resize(480,320);
                            $image_resize->save(public_path('uploads/'.$sub_directory.'/'.$id.'/medium/' .$image_name));
                            $image->move($uploadpath, $image_name);

                            $images[] = $image_name;
                        }
                    }
                    
                    if(!empty($images)) {
                        $input['image'] = $images[0];
                        $user->fill($input)->save();

                        unlinkOldImages($oldImage, $sub_directory.'/'.$id);
                    }
                }
                
                return redirect()->route('dashboard')->with('success', 'Record Updated Successfully');
            } else {
                return redirect()->route('dashboard')->with('error', 'Error in record update, Please try again.');
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Show the form for change password.
     * @return Response
     */
    public function changePassword() {

        try {
            $id = Auth::user()->id;

            $title = 'Change Password';
            $url = url('settings/update-password');

            $user = User::where('role', Auth::user()->role)->findOrFail($id);
            
            if (empty($user)) {
                return redirect()->route('dashboard')->with('error', 'Record not found');
            }

            return view('settings.update-password', compact('user', 'url', 'title'));
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
            $id = Auth::user()->id;
            
            if(empty(trim($input['password']))) {
                return back()->with('error', 'Password is required field');
            }
            
            if(!Auth::attempt(['id' => $id, 'password' => request('current_password')])) {
                return back()->with('error', 'Current Password is invalid.');
            }
            
            if(trim($input['password']) !== trim($input['confirm_password'])) {
                return back()->with('error', 'Password and Confirm Password should be same.');
            }

            $user = User::where('role', Auth::user()->role)->findOrFail($id);
            
            if (empty($user)) {
                return redirect()->route('dashboard')->with('error', 'Record not found');
            }

            //Change Password            
            if ($user->update(['password'=>Hash::make(trim($request->get('password')))])) {                                 
                return redirect()->route('dashboard')->with('success', 'Password Updated Successfully');
            } else {
                return redirect()->route('dashboard')->with('error', 'Error in record update, Please try again.');
            }
        
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }
	
}
