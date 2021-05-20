<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::group(['namespace' => 'Auth', 'as' => 'admin.'], function () {
    Route::get('/login', 'AdminLoginController@showLoginForm')->name('login');
    Route::post('/login', 'AdminLoginController@login')->name('login.submit');
    Route::any('/logout', 'AdminLoginController@logout')->name('logout');

    //admin password reset routes
    Route::post('/password/email', 'AdminForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('/password/reset', 'AdminForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('/password/reset', 'AdminResetPasswordController@reset');
    Route::get('/password/reset/{token}', 'AdminResetPasswordController@showResetForm')->name('password.reset');
});

Route::group(['middleware' => ['auth:admin', 'isAdmin', 'assign.guard:admin,admin/login'], 'namespace' => 'Admin', 'as' => 'admin.'], function () {
    Route::get('/dashboard', 'HomeController@index')->name('dashboard');
    Route::resource('adminusers', 'AdminController');
    Route::any('adminusers/dt', 'AdminController@datatable')->name('adminusers.datatable');
    /*Route::resource('users', 'AdminController');
    Route::any('users/dt', 'AdminController@datatable')->name('users.datatable');
    Route::get('user/profile', 'AdminController@profile')->name('user.profile');
    Route::post('user/profile', 'AdminController@profilePost')->name('user.profile.post');
    Route::post('user/profile/avatardelete', 'AdminController@avatarDelete')->name('user.avatardelete');
*/
    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');

    Route::any('/changepass', 'PermissionController@changepass')->name('changepass');
    Route::post('/changepassword', 'PermissionController@changepassword')->name('changepassword');
	Route::post('/Userpassword', 'PermissionController@changeUserpassword')->name('Userpassword');






    Route::resource('vendors', 'VendorController');
    Route::any('vendors/dt', 'VendorController@datatable')->name('vendors.datatable');

    // banner
    Route::resource('banners', 'BannerController');
    Route::any('banners/dt', 'BannerController@datatable')->name('banners.datatable');

    Route::resource('cat-banners', 'CategoryBannerController');
    Route::any('cat-banners/dt', 'CategoryBannerController@datatable')->name('cat-banner.datatable');

    Route::get('loadSubcat/{id}/{subId}', 'ChildCategoryController@loadSubcat');
    Route::get('loadChildcat/{id}/{subId}', 'ChildCategoryController@loadChildcat');
    //service banner
    Route::resource('service-banner', 'ServiceBannerController');
    Route::any('service-banner/create/{id}', 'ServiceBannerController@create');
    Route::any('service-banner/dt', 'ServiceBannerController@datatable')->name('service.banner.datatable');

    Route::resource('stores', 'StoreController');
    Route::any('stores/dt', 'StoreController@datatable')->name('stores.datatable');

    Route::resource('slots', 'SlotController');
    Route::any('slots/dt', 'SlotController@datatable')->name('slots.datatable');
    Route::get('subcategory/{id}/{subId}', 'StoreController@loadSubcat');

    Route::resource('business-type-categories', 'BusinessTypeCategoryController');
    Route::any('businesstypes/dt', 'BusinessTypeController@datatable')->name('businesstypes.datatable');
    Route::resource('businesstypes', 'BusinessTypeController')->only(['index', 'edit', 'update']);
    // Route::get('business-type-categories/dt', 'BusinessTypeCategoryController@datatable')->name('business-type-categories.datatable');
    Route::resource('brands', 'BrandController');
    Route::any('brands/dt', 'BrandController@datatable')->name('brands.datatable');
    Route::resource('categories', 'CategoryController');
    Route::any('categories/dt', 'CategoryController@datatable')->name('categories.datatable');
    Route::any('categories/{id}', 'CategoryController@index');

// sub cat & child cat added on 16/05/2021


Route::resource('subcategories', 'SubCategoryController');
Route::any('subcategories/dt', 'SubCategoryController@datatable')->name('admin-subcat-datatables');
Route::any('subcategories/{id}', 'SubCategoryController@index');
Route::get('/load/subcategories/{id}/', 'SubCategoryController@load')->name('admin-subcat-load'); //JSON 
Route::get('/loadsub/subcategories/{id}', 'SubCategoryController@loadsub')->name('admin-subcat-load-sub');; //JSON 


Route::resource('childcategories', 'ChildCategoryController');
Route::any('childcategories/dt', 'ChildCategoryController@datatable')->name('admin-childcat-datatables');
Route::any('childcategories/{id}', 'ChildCategoryController@index');
//Route::get('/load/childcategories/{id}', 'ChildCategoryController@load')->name('admin-childcat-load'); 
//Route::get('/load/subcategories/{id}/', 'SubCategoryController@load')->name('admin-subcat-load'); //JSON 

/*
    Route::get('/subcategory/datatables', 'SubCategoryController@datatables')->name('admin-subcat-datatables'); //JSON REQUEST
  Route::get('/subcategory', 'SubCategoryController@index')->name('admin-subcat-index');
  Route::get('/subcategory/create', 'SubCategoryController@create')->name('admin-subcat-create');
  Route::post('/subcategory/create', 'SubCategoryController@store')->name('admin-subcat-store');
  Route::get('/subcategory/edit/{id}', 'SubCategoryController@edit')->name('admin-subcat-edit');
  Route::post('/subcategory/edit/{id}', 'SubCategoryController@update')->name('admin-subcat-update');  
  Route::get('/subcategory/delete/{id}', 'SubCategoryController@destroy')->name('admin-subcat-delete'); 
  Route::get('/subcategory/status/{id1}/{id2}', 'SubCategoryController@status')->name('admin-subcat-status');
  Route::get('/load/subcategories/{id}/', 'SubCategoryController@load')->name('admin-subcat-load'); //JSON 

  Route::get('/childcategory/datatables', 'ChildCategoryController@datatables')->name('admin-childcat-datatables'); //JSON REQUEST
  Route::get('/childcategory', 'ChildCategoryController@index')->name('admin-childcat-index');
  Route::get('/childcategory/create', 'ChildCategoryController@create')->name('admin-childcat-create');
  Route::post('/childcategory/create', 'ChildCategoryController@store')->name('admin-childcat-store');
  Route::get('/childcategory/edit/{id}', 'ChildCategoryController@edit')->name('admin-childcat-edit');
  Route::post('/childcategory/edit/{id}', 'ChildCategoryController@update')->name('admin-childcat-update');  
  Route::get('/childcategory/delete/{id}', 'ChildCategoryController@destroy')->name('admin-childcat-delete'); 
  Route::get('/childcategory/status/{id1}/{id2}', 'ChildCategoryController@status')->name('admin-childcat-status');
  Route::get('/load/childcategories/{id}/', 'ChildCategoryController@load')->name('admin-childcat-load'); 
*/


// ORDER MANAGEMENT


    Route::resource('orders', 'OrderController');
    Route::any('orders/dt', 'OrderController@datatable')->name('orders.datatable');
    Route::post('orders/{order}/update-status', 'OrderController@updateStatus')->name('orders.update-status');
    Route::post('orders/{order}/update-items', 'OrderController@updateItems')->name('orders.update-items');
    Route::any('orders/{order}/dt', 'OrderController@detailsDatatable')->name('orders.datatable');
    Route::get('orders/{order}/process-payment', 'OrderController@processPayment')->name('orders.process-payment');

// INVOICES

    Route::resource('invoices', 'InvoicesController');
    Route::any('/invoices/dt', 'InvoicesController@datatables')->name('admin.invoices.datatables'); //JSON REQUEST
  

    // USER/CUSTOMER MANAGEMENT
    Route::resource('users', 'UserController');
    
    //Route::get('/users', 'UserController@index')->name('admin-user-index');
    Route::any('/users/dt', 'UserController@datatables')->name('admin-user-datatables'); //JSON REQUEST
    Route::get('user/profile', 'UserController@profile')->name('user.profile');
 
    Route::get('/users/ban/{id1}/{id2}', 'UserController@ban')->name('users.ban');
    // WITHDRAW SECTION
    Route::get('/users/withdraws/datatables', 'UserController@withdrawdatatables')->name('admin-withdraw-datatables'); //JSON REQUEST
    Route::get('/users/withdraws', 'UserController@withdraws')->name('admin-withdraw-index');
    Route::get('/user//withdraw/{id}/show', 'UserController@withdrawdetails')->name('admin-withdraw-show');
    Route::get('/users/withdraws/accept/{id}', 'UserController@accept')->name('admin-withdraw-accept');
    Route::get('/user//withdraws/reject/{id}', 'UserController@reject')->name('admin-withdraw-reject');
    






    Route::resource('products', 'ProductController');
    Route::post('products/import', 'ProductController@import')->name('products.import');
    Route::any('products/dt', 'ProductController@datatable')->name('products.datatable');
    Route::post('products/store-save', 'ProductController@productStoreSave')->name('products.store-save');
    Route::post('products/images/upload', 'ProductController@uploadImages')->name('products.images.upload');
    Route::get('products/images/{id}/delete', 'ProductController@deleteImages')->name('products.images.delete');

    Route::get('/user', function () {
        return auth()->user();
    });

    Route::get('/settings', 'SettingController@index')->name('settings.index');
    Route::post('/settings', 'SettingController@update')->name('settings.update');

    // offline classes
    Route::any('banners/dt', 'BannerController@datatable')->name('banners.datatable');

    //report
    Route::get('report/out-of-stock', 'ReportController@out_of_stock')->name('report.out-of-stock');
    Route::any('report-out-of-stock/dt', 'ReportController@datatable_out_of_stock')->name('out_of_stock.datatable');
    Route::post('download-out-of-stock-report', 'ReportController@download_out_of_stock_report')->name('download-out-of-stock-report');

    Route::get('report/user-listing', 'ReportController@user_listing')->name('report.user-listing');
    Route::any('report-user-listing/dt', 'ReportController@datatable_user_listing')->name('user_listing.datatable');
    Route::post('download-user-listing-report', 'ReportController@download_user_listing_report')->name('download-user-listing-report');

    Route::get('report/canceled-order', 'ReportController@canceled_order')->name('report.canceled-order');
    Route::any('report-canceled-order/dt', 'ReportController@datatable_canceled_order')->name('user_listing.datatable');
    Route::post('download-canceled-order-report', 'ReportController@download_canceled_order_report')->name('download-user-listing-report');

    Route::get('report/purchase-history', 'ReportController@purchase_history')->name('report.purchase-history');
    Route::any('report-purchase-history/dt', 'ReportController@datatable_purchase_history')->name('purchase_history.datatable');
    Route::post('download-purchase-history-report', 'ReportController@download_purchase_history_report')->name('purchase-history-report');
    Route::get('get-order-detail/{order_id}', 'ReportController@getOrderDetail')->name('get-order-detail');

    Route::get('report/average-price', 'ReportController@average_price')->name('report.average-price');
    Route::any('report-average-price/dt', 'ReportController@datatable_average_price')->name('average_price.datatable');
    Route::post('download-average-price-report', 'ReportController@download_average_price_report')->name('average-price-report');

    Route::get('report/customer-growth', 'ReportController@customer_growth')->name('report.customer-growth');
    Route::any('report-customer-growth/dt', 'ReportController@datatable_customer_growth')->name('customer_growth.datatable');
    Route::post('download-customer-growth-report', 'ReportController@download_customer_growth_report')->name('customer-growth-report');

    Route::get('report/funnel-for-order', 'ReportController@funnel_for_order')->name('report.funnel-for-order');
    Route::any('report-funnel-for-order/dt', 'ReportController@datatable_funnel_for_order')->name('funnel_for_order.datatable');
    Route::post('download-funnel-for-order-report', 'ReportController@download_funnel_for_order_report')->name('funnel-for-order-report');

    Route::any('user/audit/dt', 'AdminController@auditdatatable')->name('user.audit.datatable');

    Route::any('/brands/audit/list', 'BrandController@auditlist')->name('brands.brand.auditlist');

    Route::any('brands/audit/dt', 'BrandController@auditdatatable')->name('brands.brand.datatable');


    Route::resource('blog-category', 'BlogCategoryController');
    Route::any('blog-category/dt', 'BlogCategoryController@datatable')->name('blog-category.datatable');

    Route::resource('blog-author', 'BlogAuthorController');
    Route::any('blog-author/dt', 'BlogAuthorController@datatable')->name('blog-author.datatable');

    Route::resource('blog', 'BlogController');
    Route::any('blog/dt', 'BlogController@datatable')->name('blog.datatable');
    Route::get('blog/manage-images/{id}', 'BlogController@manage_images')->name('blog.manage-images');
    Route::post('blog/post-images', 'BlogController@post_images')->name('blog.post-images');

    //offer category
    Route::resource('offer-category', 'OfferCategoryController');
    Route::any('offer-category/dt', 'OfferCategoryController@datatable')->name('offer-category.datatable');

    //offer brand
    Route::resource('offer-brand', 'OfferBrandController');
    Route::any('offer-brand/dt', 'OfferBrandController@datatable')->name('offer-brand.datatable');

    //offer brand
    Route::resource('offers', 'OfferController');
    Route::any('offers/dt', 'OfferController@datatable')->name('offers.datatable');
    Route::resource('offer-products', 'OfferProductController');




    //store marker polygon
    Route::any('delete-marker', 'StoreController@deleteMarker');
    Route::any('add-marker', 'StoreController@addMarker');

    
    Route::get('audits/{type}', 'AuditController@index')->name('audits.index');
    Route::any('audits/datatable', 'AuditController@datatable')->name('audits.datatable');
    Route::get('activity-log/{type}', 'AuditController@activityLog')->name('audits.activityLog');
    Route::any('audits/activityLogDatatable', 'AuditController@activityLogDatatable')->name('audits.activityLogDatatable');

});

//store routes
Route::group(['middleware' => ['auth:admin', 'isAdmin', 'assign.guard:admin,admin/login'], 'namespace' => 'Store', 'as' => 'admin.'], function () {
    Route::resource('store-products', 'StoreProductController');
    Route::post('store-products/import', 'StoreProductController@import')->name('store-products.import');
    Route::any('store-products/reject/{id}', 'StoreProductController@reject')->name('store-products.reject');
    Route::any('store-products/dt', 'StoreProductController@datatable')->name('store-products.datatable');
    Route::resource('service-store-products', 'ServiceStoreProductController');
    Route::post('service-store-products/import', 'ServiceStoreProductController@import')->name('service-store-products.import');
    Route::any('service-store-products/reject/{id}', 'ServiceStoreProductController@reject')->name('store-products.reject');
    Route::any('service-store-products/dt', 'ServiceStoreProductController@datatable')->name('store-products.datatable');
});



