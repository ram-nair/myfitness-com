<?php 

Route::group(['namespace' => 'Auth', 'as' => 'vendor.', 'middleware' => ['web']], function () {
  Route::get('/login', 'LoginController@showVendorLoginForm');
  Route::post('/login', 'LoginController@vendorLogin');
});

Route::group(['middleware' => ['auth:vendor', 'assign.guard:vendor,vendor/login'], 'namespace' => 'Admin', 'as' => 'vendor.'], function () {
  Route::get('/dashboard', 'HomeController@index');
  
  Route::resource('stores', 'StoreController');
  Route::any('stores/dt', 'StoreController@datatable')->name('stores.datatable');
  
  Route::any('/logout', function(){
    auth()->logout();
    return redirect('/vendor/login');
  });
  Route::get('/user', function () {
    return auth()->user();
  });
});