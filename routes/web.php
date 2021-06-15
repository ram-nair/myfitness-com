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
Route::get('error-logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::group(['middleware' => ['web']], function () {

    Route::get('/', function () {
        return redirect('admin/dashboard');
    });
    Route::get('/admin', function () {
        return redirect('admin/dashboard');
    });
    Route::get('/store', function () {
        return redirect('store/dashboard');
    });
    Route::get('/vendor', function () {
        return redirect('vendor/dashboard');
    });

    Route::get('testPush', 'Web\TestController@index');
    Route::get('testCharge', 'Web\TestController@charge');

    Route::get('testSoap', 'Web\TestController@testSoap');

    //PaymentHandler
    Route::any('get-rsa', 'Web\PaymentHandlerController@index')->name('getrsa');
    Route::any('ccav-response-handler', 'Web\PaymentHandlerController@ccavResponseHandler')->name('ccav-response-handler');
// Login Routes...
    // Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
    // Route::post('login', ['as' => 'login.post', 'uses' => 'Auth\LoginController@login']);
    // Route::post('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

    // Registration Routes...
    //Route::get('register', ['as' => 'register', 'uses' => 'Auth\RegisterController@showRegistrationForm']);
    //Route::post('register', ['as' => 'register.post', 'uses' => 'Auth\RegisterController@register']);
    // Password Reset Routes...
    Route::get('password/request', ['as' => 'password.request', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
    Route::post('password/email', ['as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);
    Route::get('password/reset/{token}', ['as' => 'password.reset', 'uses' => 'Auth\ResetPasswordController@showResetForm']);
    Route::post('password/reset', ['as' => 'password.reset.post', 'uses' => 'Auth\ResetPasswordController@reset']);

    Route::any('fetch-categories', 'Admin\CategoryController@fetchCategories')->name('fetch.categories');
    Route::any('product-in-stores', 'Admin\ProductController@fetchInStores')->name('product-in-stores');
    Route::any('service-product-in-stores', 'Services\ProductController@fetchInStores')->name('service-product-in-stores');

});

Auth::routes();


// ADDED ON 11-06-2021
