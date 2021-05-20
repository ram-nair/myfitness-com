<?php

Route::group(['namespace' => 'Auth', 'as' => 'store.', 'middleware' => ['web']], function () {
    Route::get('/login', 'LoginController@showStoreLoginForm');
    Route::post('/login', 'LoginController@storeLogin');
});

Route::group(['middleware' => ['auth:store', 'assign.guard:store,store/login'], 'namespace' => 'Admin', 'as' => 'store.'], function () {

    Route::get('/dashboard', 'HomeController@index')->name('store.dashboard');

    Route::any('/logout', function () {
        auth()->logout();
        return redirect('/store/login');
    })->name('store.logout');

    Route::get('/user', function () {
        return auth()->user();
    });
});

Route::group(['middleware' => ['auth:store', 'assign.guard:store,store/login'], 'namespace' => 'Store', 'as' => 'store.'], function () {
    Route::resource('slots', 'SlotController');
    Route::any('slots/dt', 'SlotController@datatable')->name('slots.datatable');

    Route::resource('drops', 'DropsController');
    Route::any('drops/dt', 'DropsController@datatable')->name('drops.datatable');

    //

    Route::resource('report-problem', 'ReportProblemController');
    Route::any('report-problem/dt', 'ReportProblemController@datatable')->name('report-problem.datatable');

    Route::resource('store-products', 'StoreProductController');
    Route::get('list/products', 'StoreProductController@storeList')->name('store.products.list');
    Route::any('store-products/dt', 'StoreProductController@datatable')->name('products.datatable');

    Route::resource('service-store-products', 'ServiceStoreProductController');
    Route::get('list/products', 'ServiceStoreProductController@storeList')->name('service.store.products.list');
    Route::any('service-store-products/dt', 'ServiceStoreProductController@datatable')->name('service.products.datatable');

    Route::resource('orders', 'OrderController');
    Route::any('orders/dt', 'OrderController@datatable')->name('orders.datatable');
    Route::post('orders/{order}/update-status', 'OrderController@updateStatus')->name('orders.update-status');
    Route::post('orders/{order}/update-items', 'OrderController@updateItems')->name('orders.update-items');
    Route::any('orders/{order}/dt', 'OrderController@detailsDatatable')->name('orders.datatable');
    Route::get('orders/{order}/process-payment', 'OrderController@processPayment')->name('orders.process-payment');

    Route::resource('service-orders', 'ServiceOrderController');
    Route::any('service-orders/dt', 'ServiceOrderController@datatable')->name('service-orders.datatable');
    // Route::any('service-orders/show/{id}', 'ServiceOrderController@datatable')->name('service-orders.datatable');

    Route::post('service-orders/{order}/update-status', 'ServiceOrderController@updateStatus')->name('service-orders.update-status');
    Route::get('service-orders/{order}/process-payment', 'ServiceOrderController@processPayment')->name('service-orders.process-payment');
    // Route::post('service-orders/{order}/update-items', 'ServiceOrderController@updateItems')->name('service-orders.update-items');
    Route::any('service-orders/{order}/dt', 'ServiceOrderController@detailsDatatable')->name('service-orders.datatable');
});
