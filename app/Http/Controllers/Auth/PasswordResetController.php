<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\User;
use App\Models\PasswordReset;
use Validator;

class PasswordResetController extends Controller
{

    public $successStatus = 200;

    /**
     * forgot password page
     */
    public function forgetPassword(Request $request)
    {
        try {
            
            return view('auth/passwords/forgot-password');
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    } 

    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function generateResetPaswordLink(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [ 
                'email' => 'required|string|email',
            ]);

            if ($validator->fails()) {             
                if (!$validator->errors()->toArray()['email']='') {
                    $msg = $validator->errors()->toArray()['email']['0'];
                }

                return back()
                ->withInput($request->input()) // Flashes inputs
                // ->withErrors($validator)
                ->with('error', $msg);
            }

            // only for admin users
            $user = User::where('email', $request->email)->where('role', 'Admin')->first();
            if (!$user) {
                
                return back()
                ->withInput($request->input()) // Flashes inputs
                ->with('error', 'We can not find a user with that e-mail address.');
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

                // old code
                // $user->notify(
                //     new PasswordResetRequest($passwordReset->token)
                // );

                // new code (send mail using php mail function)
                $url = url('/verify-reset-password-token/'.$passwordReset->token);
                // die($url);

                $text = "<p>Hello $user->name</p><br>";
                $text .= "<p>You are receiving this email because we received a password reset request for your account.</p>";
                $text .= "<p>Reset Password: <a href='".$url."' title='Reset Password'>Click Here</a> </p>";
                $text .= "<p>If you did not request a password reset, no further action is required</p><br>";
                
                sendEmail($user->email, 'Reset Password', $text);
            }

            return back()->with('success', 'We have e-mailed your password reset link!');
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function verifyResetPasswordToken($token, Request $request)
    {
        try {
            $passwordReset = PasswordReset::where('token', $token)->first();

            $isError = false;

            if (!$passwordReset) {
                $isError = true;
                $error = 'This password reset token is invalid, Please try agin.';
            }
            elseif (Carbon::parse($passwordReset->updated_at)->addMinutes(180)->isPast()) {
                $isError = true;
                $passwordReset = PasswordReset::where('token', $passwordReset['token'])->delete();
                $error = 'This password reset token is invalid, Please try agin.';
            }

            if($isError) {
                return view('auth/passwords/invalid-token', compact('error'));
            } else {
                return view('auth/passwords/verify-reset-password-token', compact('passwordReset', 'token'));
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return view('auth/passwords/invalid-token', compact('error'));
        }
    }

    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function resetPassword(Request $request)
    {   
        try {
            $input = $request->all();
            
            $validator = Validator::make($request->all(), [ 
                'token' => 'required|string',
                'email' => 'required|string|email',
                'password' => 'required|string|confirmed',
            ]);
            if ($validator->fails()) {
                return back()
                ->withInput($request->input()) // Flashes inputs
                ->withErrors($validator)
                ->with('error', 'Error in password reset.');
            }

            $passwordReset = PasswordReset::where([
                ['token', $request->token],
                ['email', $request->email]
            ])->first();
            if (!$passwordReset) {
                return back()
                ->withInput($request->input()) // Flashes inputs
                ->with('error', 'This password reset token is invalid.');
            }

            $user = User::where('email', $passwordReset->email)->first();
            if (!$user) {
                return back()
                ->withInput($request->input()) // Flashes inputs
                ->with('error', 'We cant find a user with that e-mail address.');
            }
            
            $user->password = bcrypt($request->password);
            $user->save();
            $passwordReset = PasswordReset::where('token', $passwordReset['token'])->delete();

            // send notidication email of password reset
            //$user->notify(new PasswordResetSuccess($passwordReset));
            
            if($user->role == 'Admin') {
                return redirect()->route('admin-login')->with('success', 'Password reset successfully, Now you can login.');
            } else {
                return redirect()->route('password-reset-thanks')->with('success', 'Password reset successfully, Now you can login.');
            }

        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

    public function passwordResetThanks(Request $request)
    {   
        try { 
            $message = 'Your password has been reset successfully!';
            return view('auth/passwords/password-reset-thanks', compact('message'));
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            return back()->with('error', $errorMsg);
        }
    }

}
