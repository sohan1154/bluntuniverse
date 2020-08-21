<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Website 
Route::get('/', 'WebsiteController@news')->name('news-listing');
Route::get('/detail/{slug}', 'WebsiteController@newsDetail')->name('news-detail');
Route::get('/about-us', 'WebsiteController@aboutUs')->name('about-us');
Route::get('/contact-us', 'WebsiteController@contactUs')->name('contact-us');
Route::get('/terms-of-use', 'WebsiteController@termsAndConditions')->name('terms-and-conditions');
Route::get('/privacy-policy', 'WebsiteController@privacyPolicy')->name('privacy-policy');


// Route::get('/', 'Auth\LoginController@admin_login')->name('admin-login');

Route::get('admin', 'Auth\LoginController@admin_login')->name('admin-login');

Route::get('logout', function () {
    Auth::logout();
    return redirect('/admin');
});

Auth::routes();

Route::get('dashboard', 'DashboardController@index')->name('dashboard');

// Resest Password 
Route::get('forgot-password', 'Auth\PasswordResetController@forgetPassword')->name('forgot-password');
Route::post('generate-reset-password-link', 'Auth\PasswordResetController@generateResetPaswordLink')->name('generate-reset-password-link');
Route::get('verify-reset-password-token/{token}', 'Auth\PasswordResetController@verifyResetPasswordToken')->name('verify-reset-password-token');
Route::post('reset-password', 'Auth\PasswordResetController@resetPassword')->name('reset-password');
Route::get('password-reset-thanks', 'Auth\PasswordResetController@passwordResetThanks')->name('password-reset-thanks');

// Users
Route::get('users', 'UsersController@index')->name('users-index');
Route::get('users/add', 'UsersController@add')->name('user-add');
Route::post('users/create', 'UsersController@create')->name('user-create');
Route::get('users/view/{id}', 'UsersController@view')->name('user-view');
Route::get('users/edit/{id}', 'UsersController@edit')->name('user-edit');
Route::post('users/update', 'UsersController@update')->name('user-update');
Route::post('users/status', 'UsersController@status')->name('user-status');
Route::post('users/delete', 'UsersController@delete')->name('user-delete');
Route::get('users/change-password/{id}', 'UsersController@changePassword')->name('user-change-password');
Route::post('users/update-password', 'UsersController@updatePassword')->name('user-update-password');
Route::post('users/verify', 'UsersController@verify')->name('user-verify');

// Galleries
Route::get('galleries', 'GalleriesController@index')->name('galleries-index');
Route::get('galleries/add', 'GalleriesController@add')->name('gallery-add');
Route::post('galleries/create', 'GalleriesController@create')->name('gallery-create');
Route::get('galleries/view/{id}', 'GalleriesController@view')->name('gallery-view');
Route::get('galleries/edit/{id}', 'GalleriesController@edit')->name('gallery-edit');
Route::post('galleries/update', 'GalleriesController@update')->name('gallery-update');
Route::post('galleries/status', 'GalleriesController@status')->name('gallery-status');
Route::post('galleries/delete', 'GalleriesController@delete')->name('gallery-delete');

// BreakingNews
Route::get('breaking_news', 'BreakingNewsController@index')->name('breaking_news-index');
Route::get('breaking_news/add', 'BreakingNewsController@add')->name('breaking_news-add');
Route::post('breaking_news/create', 'BreakingNewsController@create')->name('breaking_news-create');
Route::get('breaking_news/view/{id}', 'BreakingNewsController@view')->name('breaking_news-view');
Route::get('breaking_news/edit/{id}', 'BreakingNewsController@edit')->name('breaking_news-edit');
Route::post('breaking_news/update', 'BreakingNewsController@update')->name('breaking_news-update');
Route::post('breaking_news/status', 'BreakingNewsController@status')->name('breaking_news-status');
Route::post('breaking_news/delete', 'BreakingNewsController@delete')->name('breaking_news-delete');

// News
Route::get('news', 'NewsController@index')->name('news-index');
Route::get('news/add', 'NewsController@add')->name('news-add');
Route::post('news/create', 'NewsController@create')->name('news-create');
Route::get('news/view/{id}', 'NewsController@view')->name('news-view');
Route::get('news/edit/{id}', 'NewsController@edit')->name('news-edit');
Route::post('news/update', 'NewsController@update')->name('news-update');
Route::post('news/status', 'NewsController@status')->name('news-status');
Route::post('news/delete', 'NewsController@delete')->name('news-delete');
Route::post('news/verify', 'NewsController@verify')->name('news-verify');

// Categories
Route::get('categories', 'CategoriesController@index')->name('categories-index');
Route::get('categories/add', 'CategoriesController@add')->name('category-add');
Route::post('categories/create', 'CategoriesController@create')->name('category-create');
Route::get('categories/view/{id}', 'CategoriesController@view')->name('category-view');
Route::get('categories/edit/{id}', 'CategoriesController@edit')->name('category-edit');
Route::post('categories/update', 'CategoriesController@update')->name('category-update');
Route::post('categories/status', 'CategoriesController@status')->name('category-status');
Route::post('categories/delete', 'CategoriesController@delete')->name('category-delete');

// Pages 
Route::get('pages', 'PagesController@index')->name('pages-index');
Route::get('pages/add', 'PagesController@add')->name('page-add');
Route::post('pages/create', 'PagesController@create')->name('page-create');
Route::get('pages/view/{id}', 'PagesController@view')->name('page-view');
Route::get('pages/edit/{id}', 'PagesController@edit')->name('page-edit');
Route::post('pages/update', 'PagesController@update')->name('page-update');
Route::post('pages/status', 'PagesController@status')->name('page-status');
Route::post('pages/delete', 'PagesController@delete')->name('page-delete');

// Pages 
Route::get('contactus', 'ContactusController@index')->name('contactus-index');
Route::get('contactus/view/{id}', 'ContactusController@view')->name('contactus-view');

// Settings 
Route::get('settings/profile', 'SettingsController@profile')->name('setting-profile');
Route::post('settings/update-profile', 'SettingsController@updateProfile')->name('setting-update-profile');
Route::get('settings/change-password', 'SettingsController@changePassword')->name('setting-change-password');
Route::post('settings/update-password', 'SettingsController@updatePassword')->name('setting-update-password');
