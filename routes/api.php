<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// auth
Route::post('register', 'API\UsersController@register');
Route::post('login', 'API\UsersController@login');
Route::get('is-verified/{user_id}', 'API\UsersController@isVerified');

// home 
Route::get('pages', 'API\HomeController@pages');

// user
Route::post('users/update-profile', 'API\UsersController@updateProfile');
Route::post('users/upload-profile-image', 'API\UsersController@uploadProfileImage');

//password
Route::post('users/change-password', 'API\UsersController@changepassword');

//forgetpassword
Route::post('forgot-password', 'API\UsersController@forgetPassword');
Route::post('reset-password', 'API\UsersController@resetPassword');

