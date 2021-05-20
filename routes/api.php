<?php

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
//['middleware' => ['assign.guard']
Route::group(['prefix' => 'v1'], function () {
    Route::group(['namespace' => 'Api\V1'], function () {
        Route::post('user/login', 'ApiLoginController@login');

        // Route::post('register', 'UserController@register');
        // Route::post('login', 'UserController@login');
        // Route::post('reset-password', 'PasswordResetController@resetPassword');
    });

    Route::group(['middleware' => ['auth:sanctum', 'assign.guard:api'], 'namespace' => 'Api\V1'], function () {
        Route::post('profile', 'User\UserController@profile');
        Route::post('user/logout', 'User\UserController@logout');
    });

    //customer routes
    Route::group(['middleware' => ['auth:sanctum', 'assign.guard:api'], 'prefix' => 'user', 'namespace' => 'Customer'], function () {

        Route::post('add-card', 'PaymentController@addCard');
        Route::post('get-cards', 'PaymentController@getPaymentMethods');
        Route::post('set-default-card', 'PaymentController@setDefaultCard');
        Route::post('disable-card', 'PaymentController@disableCard');

        Route::get('address', 'CustomerController@listUserAddress');
        Route::post('address', 'CustomerController@storeUserAddress');
        Route::patch('address/{address}', 'CustomerController@storeUserAddress');
        Route::delete('address/{address}', 'CustomerController@deleteUserAddress');

        Route::resource('cart', 'CartController')->only(['store', 'index', 'destroy']);
        Route::post('order-again', 'CartController@orderAgain');
        // Route::post('add-items', 'CartController@addItems');
        Route::post('clear-cart', 'CartController@removeCart');

        //order related api
        Route::any('order-now', 'OrderController@createOrder');
        Route::any('order/{order}/update', 'OrderController@updateOrder');
        Route::any('order-details', 'OrderController@orderDetails');
        Route::any('my-orders', 'OrderController@myorderHistory');
        Route::any('address-check', 'OrderController@addressCheck');
        Route::any('order-cancel', 'OrderController@orderCancel');
        Route::any('slot-check', 'OrderController@slotCheck');
        Route::any('favourite-stores', 'CustomerController@favouriteStores');
        Route::any('make-favourite', 'CustomerController@updateFavouriteStore');

        Route::post('update-deviceid', 'CustomerController@updateDeviceId');
        Route::any('notifications', 'CustomerController@userNotifications');
        Route::any('notifications/{notification}', 'CustomerController@notificationDetails');

        //classes
        Route::any('classes/slots', 'GeneralClassController@getSlots');
        Route::any('classes/slot-check', 'GeneralClassController@slotCheck');
        Route::any('classes/order-details', 'GeneralClassController@orderDetails');
        Route::any('classes/order', 'GeneralClassController@createOrder');
        Route::any('classes/my-orders', 'GeneralClassController@myorderHistory');
        Route::any('classes/cancel-order', 'GeneralClassController@cancelOrder');
        Route::any('classes/report-problem', 'GeneralClassController@reportProblem');
        Route::any('classes', 'GeneralClassController@index');
    });

    Route::group(['middleware' => ['auth:sanctum', 'assign.guard:api'], 'prefix' => 'user', 'namespace' => 'Admin'], function () {
        Route::any('fetch-home-banner', 'BannerController@fetchHomeBanners');

        Route::any('get-store-by-location', 'StoreController@getStoreByLocation');
        Route::any('slots', 'StoreController@getSlots');
        Route::any('drop-off', 'StoreController@getDropOff');
        Route::any('fetch-businesstypes', 'BusinessTypeCategoryController@fetchBusinesstypes');
        Route::any('fetch-categories', 'CategoryController@fetchCategories')->name('fetch.categories');
        Route::any('fetch-store-categories', 'CategoryController@fetchStoreCategories');
        Route::any('fetch-store-sub-categories', 'CategoryController@fetchStoreSubCategories');
        Route::any('fetch-store-products', 'ProductController@fetchStoreProducts');
        Route::post('suggested-products', 'ProductController@fetchSuggestedProducts');
        Route::any('alternate-products', 'ProductController@fetchSuggestedProducts');
        Route::any('search-products', 'ProductController@searchProducts');

        //vlog-blog
        Route::any('vlog-blogs', 'BlogController@vlogBlog');
        Route::any('view-all-vlog-blogs', 'BlogController@viewAllvlogBlog');
        Route::any('search-vlog-blogs', 'BlogController@searchVlogBlog');
        Route::any('author-following', 'BlogController@authorFollowing');
        Route::any('author-detail', 'BlogController@authorDetail');


    });

    Route::group(['middleware' => ['auth:sanctum', 'assign.guard:api'], 'prefix' => 'user/services', 'namespace' => 'Services'], function () {
        Route::any('fetch-categories', 'CategoryController@fetchCategories')->name('fetch.categories');
        Route::any('fetch-store-categories', 'CategoryController@fetchStoreCategories');

        //service products
        Route::any('fetch-store-products', 'ProductController@fetchStoreProducts');
        // Route::post('suggested-products', 'ProductController@fetchSuggestedProducts');
        // Route::any('alternate-products', 'ProductController@fetchSuggestedProducts');

        Route::resource('cart', 'CartController')->only(['store', 'index', 'destroy']);
        Route::post('order-again', 'CartController@orderAgain');
        // Route::post('add-items', 'CartController@addItems');
        Route::post('clear-cart', 'CartController@removeCart');
        //order related api
        Route::post('order', 'OrderController@store');
        Route::post('order/type-2', 'OrderController@serviceType2Order');
        Route::any('order/{order}/update', 'OrderController@updateOrder');
        Route::any('order/type-2/{order}/update', 'OrderController@updateServiceType2Order');
        Route::delete('order/{order}/delete', 'OrderController@destroy');
        Route::any('order-details', 'OrderController@orderDetails');
        Route::any('my-orders', 'OrderController@myOrderHistory');
        Route::any('address-check', 'OrderController@addressCheck');
        Route::any('order-cancel', 'OrderController@orderCancel');
        Route::any('slot-check', 'OrderController@slotCheck');
        // report
        Route::any('report-problem', 'OrderController@reportProblem');
        Route::any('rate-order', 'OrderController@rateOrder');

        //service type 3
        Route::post('order/type-3', 'OrderController@serviceType3Order');
        Route::post('get-payment-info', 'OrderController@getPaymentInfo');
    });

    Route::post('get-subcategories', 'Admin\CategoryController@getCategoriesOnBusinessTypeCategoryId')->name('get-sub-categories');
    Route::post('get-typecategories', 'Services\CategoryController@getServiceCategories')->name('get-typecategories');
    Route::post('getServiceSubCategories', 'Services\CategoryController@getServiceSubCategories')->name('getServiceSubCategories');
});
