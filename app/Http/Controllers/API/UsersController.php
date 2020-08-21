<?php 

namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Validator;
use Illuminate\Support\Str;
use URL;
use Image;
use DB;
use Mail;
use Config;
use File;
use App\User;
use App\Models\Recording;
use App\Models\PasswordReset;

class UsersController extends Controller
{
	public function register(Request $request) {
		try {
			$input = $request->all(); 

			$validator = Validator::make($input, [ 
				'name' => 'required', 
				'mobile' => 'required|max:14|unique:users,mobile', 
				'email' => 'required|email|unique:users,email', 
				'password' => 'required', 
				'c_password' => 'required|same:password', 
			]);
			
			// Validate the arguments.
			if ($validator->fails()) {
				$errors = getValidationErrors($validator->messages()->getMessages());
				return ['status' => false, 'message' => __('messages.common.validation-error', ['error' => $errors])];
			}
					
			$input['role'] = 'User';
			$input['password'] = bcrypt($input['password']); 
			
			if($user = User::create($input)) {
				//$success['token'] =  $user->createToken('MyApp')-> accessToken; 
				
				return ['status'=>true, 'message' => 'You have been successfully registered', 'data'=>$user]; 
			} else {
				
				return ['status'=>false, 'message' => 'Error in user registration, Please try again.']; 
			}
		} catch (\Exception $e) {
			$errorMsg = $e->getMessage();
			return ['status' => false, 'message' => __('messages.common.server-error', ['error' => $errorMsg])];
		}
	}
	
	public function login() {
		
		try {

			$user = User::where('email', request('email'))->orWhere('mobile', request('email'))->first();

			if(!$user) {

				return ['status'=>false,'message'=>'We haven\'t found any account with provided information.','error'=>'Unauthorised'];
			}

			if(Auth::attempt(['email' => $user->email, 'password' => request('password'), 'role' => request('role','User')])){ 
			// if(Auth::attempt(['email' => request('email'), 'password' => request('password'), 'role' => request('role','User')])){ 
				$user = Auth::user();
				if($user->is_deleted==true){
					return ['status'=>false,'message'=>'Your Account has been blocked.','error'=>'Unauthorised'];
					
				}
				
				if($user->status==false){
					return ['status'=>false, 'message'=>'Your Account Not Active.', 'error'=>'Unauthorised']; 
				}
				
				//$user['profile_pic'] = userProfile($user->id);
				$user->image = getImage($user->image, 'users/'.$user->id.'/thumb');
				// $success['token'] =  $user->createToken('MyApp')-> accessToken; 
				return ['status'=>true,'message'=>'You are successfully logged in.', 'user'=>$user]; 
			} 
			else{ 
				return ['status'=>false,'message'=>'Invalid username or password','error'=>'Unauthorised']; 
			} 
		} catch (\Exception $e) {
			$errorMsg = $e->getMessage();
			return ['status' => false, 'message' => __('messages.common.server-error', ['error' => $errorMsg])];
		}
	}
	
	public function isVerified($user_id) {
		
		try {
			$user = User::find($user_id);
			
			if($user && $user->is_verified==true){
				return ['status'=>true, 'message'=>'You account is verified.'];
			}
			
			return ['status'=>false, 'message'=>'Your account not verified.', 'error'=>'Unauthorised']; 
		} catch (\Exception $e) {
			$errorMsg = $e->getMessage();
			return ['status' => false, 'message' => __('messages.common.server-error', ['error' => $errorMsg])];
		}
	}

	public function sendOtp(Request $request) {

		try {
			$input = $request->all();

			$validator = Validator::make($request->all(), [
				'mobile' => 'required',
			]);
			
			// Validate the arguments.
			if ($validator->fails()) {
				$errors = getValidationErrors($validator->messages()->getMessages());
				return ['status' => false, 'message' => __('messages.common.validation-error', ['error' => $errors])];
			}

			$user = User::where('mobile', $input['mobile'])->first();
			
			if(!$user) {
				$input['role'] = 'User';
				$user = User::create($input);
			}
			elseif($user->role != 'User') {
				return ['status' => false, 'message' => 'Mobile number is already registed, Please try with other one.'];
			}

			$user->image = getImage($user->image, 'users/'.$user->id);

			$authkey = config('siteconstants.AUTH_KEY');
			$sender = config('siteconstants.SENDER');
			$otp = 123456;//rand(100000,999999);
			$message = "";
			$mobile = "+91".$user['mobile'];

			$curl_url = "http://control.msg91.com/api/sendotp.php?authkey=$authkey&message=$message&sender=$sender&mobile=$mobile&otp=$otp";
			
			$response = executeCurlAndGetResponse($curl_url);
			//dd($response);
			
			if($response->type == "error"){
				return ['status' => false, 'message' => $response->message];
			} else {
				return ['status' => true, 'message' => 'OTP sent.', 'data' => $user, 'response' => $response];
			}
		} catch (\Exception $e) {
			$errorMsg = $e->getMessage();
			return ['status' => false, 'message' => __('messages.common.server-error', ['error' => $errorMsg])];
        }
	}

	public function resendOtp(Request $request) {
		
		try {
			$input = $request->all();

			$validator = Validator::make($request->all(), [
				'mobile' => 'required',
				'retrytype' => 'required'
			]);
			
			// Validate the arguments.
			if ($validator->fails()) {
				$errors = getValidationErrors($validator->messages()->getMessages());
				return ['status' => false, 'message' => __('messages.common.validation-error', ['error' => $errors])];
			}
			
			$authkey = config('siteconstants.AUTH_KEY');
			$mobile = "+91".$input['mobile'];
			$retrytype = $input['retrytype'];

			$curl_url = "http://control.msg91.com/api/retryotp.php?authkey=".$authkey."&mobile=".$mobile."&retrytype=".$retrytype;
			
			$response = executeCurlAndGetResponse($curl_url);
			
			if($response->type == "error"){
				return ['status' => false, 'message' => __('messages.common.server-error', ['error' => $response->message])];
			} else {
				return ['status' => true, 'message' => __('messages.login.send-otp'), 'data' => $response];
			}
		} catch (\Exception $e) {
			$errorMsg = $e->getMessage();
			return ['status' => false, 'message' => __('messages.common.server-error', ['error' => $errorMsg])];
        }
	}
	
	public function verifyOtp(Request $request) {
		
		try {
			$input = $request->all();

			$validator = Validator::make($request->all(), [
				'mobile' => 'required',
				'otp' => 'required'
			]);
			
			// Validate the arguments.
			if ($validator->fails()) {
				$errors = getValidationErrors($validator->messages()->getMessages());
				return ['status' => false, 'message' => __('messages.common.validation-error', ['error' => $errors])];
			}
			
			$authkey = config('siteconstants.AUTH_KEY');
			$otp = request('otp');
			$mobile = "+91".request('mobile');

			$curl_url = "https://control.msg91.com/api/verifyRequestOTP.php?authkey=".$authkey."&mobile=".$mobile."&otp=".$otp;
			
			$response = executeCurlAndGetResponse($curl_url);

			if($response->type == "error"){
				return ['status' => false, 'message' => __('messages.common.server-error', ['error' => $response->message])];
			} else {
				
				$user = User::where('role', 'User')->where('mobile', $input['mobile'])->first();

				if (empty($user)) {
					return ['status' => false, 'message' => __('messages.login.account-not-found')];
				}
				
				if(!empty($user->email) && !$user->status) {
					return ['status' => false, 'message' => __('messages.login.account-inactive')];
				}
				
				$user->name = (!empty($user->name)) ? $user->name : 'Guest';
				$user->email = $user->email;
				$user->image = getImage($user->image, 'users/'.$user->id.'/thumb');
				
				$data['token'] =  $user->createToken(config('siteconstants.SITE_NAME'))->accessToken;
				$data['user'] = $user;
				
				return ['status' => true, 'message' => __('messages.login.logged-in'), 'data' => $data];
			}
		} catch (\Exception $e) {
			$errorMsg = $e->getMessage();
			return ['status' => false, 'message' => __('messages.common.server-error', ['error' => $errorMsg])];
        }
	}

	public function updateProfile(Request $request ) {

        try {
			$input = $request->all();
			$id = $request['user_id'];

			$record = User::where('role', 'User')->find($id);
            
            if (empty($record)) {
				return ['status' => false, 'message' => __('messages.common.record-not-found')];
            }
            
            $validator = validator::make($input, [
                'name' => 'required|max:100',
				'mobile' => 'required|max:14|unique:users,mobile,'.$record->id,
            ]);

			// Validate the arguments.
			if ($validator->fails()) {
				$errors = getValidationErrors($validator->messages()->getMessages());
				return ['status' => false, 'message' => __('messages.common.validation-error', ['error' => $errors])];
			}

			$update = [
				'name' => $input['name'],
				'mobile' => $input['mobile']
			];

            if($record->fill($update)->save()) {
				
				$record->image = getImage($record->image, 'users/'.$record->id.'/thumb');
				
				return ['status' => true, 'message' => __('Profile updated successfully'),'user'=>$record];
            } else {
				return ['status' => false, 'message' => __('messages.user-profile.update-error')];
            }
        } catch (\Exception $e) {
			$errorMsg = $e->getMessage();
			return ['status' => false, 'message' => __('messages.common.server-error', ['error' => $errorMsg])];
        }
    }

	public function uploadProfileImage(Request $request) {

        try {
			$allImages[] = $request->file('image');
            $input = $request->all();
		   	$id = $request['user_id'];

            unset($input['image']); // remove image from update fields
            
            $record = User::where('role', 'User')->find($id);

            if (empty($record)) {
				return ['status' => false, 'message' => __('messages.common.record-not-found')];
            }

            $oldImage = $record->image;

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
						$image_prefix = 'user_' . rand(0, 999999999) . '_' . date('YmdHis');
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


                    if(!empty($images)) {
						$input['image'] = $images[0];
                        $record->fill($input)->save();
                    
						$record->image = getImage($input['image'], 'users/'.$record->id.'/thumb');

						unlinkOldImages($oldImage, 'users/'.$id);
                    } else {
						$record->image = getImage($record->image, 'users/'.$record->id.'/thumb');
					}
				}

                return ['status' => true, 'message' => __('profile image uploaded successfully'),'user'=>$record];
			}else {
				return ['status' => false, 'message' => __('messages.user-profile.image-upload-update-error')];
            }
        } catch (\Exception $e) {
			$errorMsg = $e->getMessage();
			return ['status' => false, 'message' => __('messages.common.server-error', ['error' => $errorMsg])];
        }
	}

	public function changepassword(Request $request) {
		try{
			$id = $request['user_id'];
			$record = User::where('role', 'User')->find($id);
			
			if(empty(trim($request['current_password']))) {
				return ['status' => false, 'message' => __('Password is required field')];
			}
			
			if(!Hash::check($request['current_password'], $record['password'])) {
				return ['status' => false, 'message' => __('current password is wrong.')];
			}

			if($request['password']!=$request['confirm_password']) {
			
				return ['status' => false, 'message' => __('new password and confirm password should be same.')];
			}
			
			
			$request['password'] = bcrypt($request['password']); 

			$update = [
				'password' => $request['password'],
			];
			
			if($record->fill($update)->save()) {
				return ['status' => true, 'message' => __('Password changed successfully')];
			} else {
				return ['status' => false, 'message' => 'Error in password change, Please try again.'];
			}
		} catch (\Exception $e) {
			$errorMsg = $e->getMessage();
			return ['status' => false, 'message' => __('messages.common.server-error', ['error' => $errorMsg])];
		}
	}

	public function forgetPassword(Request $request) {
		
		try {
            $validator = Validator::make($request->all(), [ 
                'email' => 'required|string|email',
            ]);

            if ($validator->fails()) {             
                if (!$validator->errors()->toArray()['email']='') {
                    $msg = $validator->errors()->toArray()['email']['0'];
                }
				return ['status' => false, 'message' => $msg];
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
				return ['status' => false, 'message' => 'We can not find a user with that e-mail address.'];
            }

            $existPasswordReset = PasswordReset::where('email', $request->email)->first();
            if (empty($existPasswordReset)) {
                $passwordReset = PasswordReset::create(
                    [
                        'email' => $user->email,
                        'token' => str_random(60)
                    ]
                );
            } else {
                $input['token'] = str_random(60); 
                $existPasswordReset->fill($input)->save();

                $passwordReset = PasswordReset::where('email', $request->email)->first();
            }

            if ($user && $passwordReset) {

                $url = url('/verify-reset-password-token/'.$passwordReset->token);
                // die($url);

                $text = "<p>Hello $user->name</p><br>";
                $text .= "<p>You are receiving this email because we received a password reset request for your account.</p>";
                $text .= "<p>Reset Password: <a href='".$url."' title='Reset Password'>Click Here</a> </p>";
                $text .= "<p>If you did not request a password reset, no further action is required</p><br>";
                
                sendEmail($user->email, 'Reset Password', $text);
            }

			return ['status' => true, 'message' => 'Reset password link has been sent on your registered email.'];
		
		} catch (\Exception $e) {
			$errorMsg = $e->getMessage();
			return ['status' => false, 'message' => __('messages.common.server-error', ['error' => $errorMsg])];
		}
	}

}
