<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

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

Route::get('/cek-resi', 'PublicController@tracking')->name('tracking-page');
Route::post('/complain', 'PublicController@storeComplain')->name('complain-post');
Route::get('/complain', 'PublicController@complain')->name('complain-page');
Route::get('/', 'AuthController@index')->name('login-page');
Route::post('login', 'AuthController@login')->name('login');
Route::get('registration', 'AuthController@registration');
Route::post('post-registration', 'AuthController@postRegistration');

Route::get('test', 'DashboardController@test')->name('test');

Route::get('get-customer', 'CustomerController@getCustomer')->name('get-customer');

Route::middleware('auth')->group(function () {
    Route::post('reset-password', 'AuthController@reset_password')->name('reset-password');
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('dashboard/print', 'DashboardController@print')->name('dashboard.print');
    Route::get('dashboard-gudang', 'DashboardController@dashboardGudang')->name('dashboard-gudang');
    Route::get('dashboard-tracking', 'DashboardController@dashboardTracking')->name('dashboard-tracking');
    Route::get('dashboard/vendor-invoice', 'DashboardVendorController@index')->name('dashboard-vendor');
    Route::get('asset', 'AssetController@index')->name('asset');

    Route::name('office-spending.')->prefix('manage/office-spending')->group(function () {
        Route::get('/', 'OfficeSpendingController@index')->name('index');
        Route::get('list', 'OfficeSpendingController@list')->name('list');
        Route::post('store', 'OfficeSpendingController@store');
        Route::get('export', 'OfficeSpendingController@export_excel');
        Route::post('delete','OfficeSpendingController@delete')->name('delete');
    });

    Route::name('vendor-spending.')->prefix('manage/vendor-spending')->group(function () {
        Route::get('/', 'VendorSpendingController@index')->name('index');
        Route::get('list', 'VendorSpendingController@list')->name('list');
        Route::post('store', 'VendorSpendingController@store');
        Route::get('export', 'VendorSpendingController@export_excel');
        Route::get('invoice-vendor', 'VendorSpendingController@getInvoiceVendor')->name('invoice-vendor');
        Route::get('account-vendor', 'VendorSpendingController@getAccountVendor')->name('account-vendor');
    });

    Route::name('asset.')->prefix('manage/asset')->group(function () {
        Route::get('/', 'AssetController@index')->name('index');
        Route::get('list', 'AssetController@list')->name('list');
        Route::get('export', 'AssetController@export_excel');
    });

    Route::name('shipping.')->prefix('manage/shipping')->group(function () {
        Route::get('/', 'ShippingController@index')->name('index');
        Route::get('detail/{shipping_id}', 'ShippingController@show')->name('shipping-detail');
        Route::get('show/{shipping_id}', 'ShippingController@getById');
        Route::get('list', 'ShippingController@list')->name('list');
        Route::get('list-due-date', 'ShippingController@list_due_date')->name('list-due-date');
        Route::post('store', 'ShippingController@store')->name('shipping-store');
        Route::put('update/{id}', 'ShippingController@update')->name('shipping-update');
        Route::post('approve', 'ShippingController@approve');
        Route::get('print-invoice/{id}', 'ShippingController@print_invoice')->name('print-invoice');
        Route::get('shipping-export', 'ShippingController@export_excel');
        Route::get('create-invoice', 'ShippingController@create')->name('create-invoice');
        Route::post('upload-images', 'ShippingController@upload_img_invoice')->name('upload-inv-img');
        Route::post('delete-images', 'ShippingController@delete_image')->name('delete-images');
        Route::post('delete-invoice', 'ShippingController@delete_invoice')->name('delete-invoice');
        Route::post('delete-payment', 'ShippingController@deletePayment')->name('delete-payment');

        Route::post('send-notification', 'ShippingController@send_notification')->name('send-notification');
        Route::post('update-ready-packing/{id}', 'ShippingController@updateReadyPacking')->name('update-ready-packing');
    });

    Route::name('master.')->prefix('manage/master')->group(function () {
        Route::get('customers', 'CustomerController@index')->name('customers');
        Route::post('customers', 'CustomerController@store')->name('customers.store');
        Route::put('customers/{id}', 'CustomerController@update')->name('customers.update');
        Route::resource('vendors', 'VendorController');
        Route::get('vendors/{id}/invoices', 'VendorController@invoices')->name('list-invoices-vendor');
        Route::resource('bank', 'BankController');
        Route::resource('destination', 'DestinationController');
        Route::resource('products', 'ProductController');
    });
        
    Route::get('settings', 'SettingController@index')->name('settings.index');
    Route::put('settings/{id}', 'SettingController@update')->name('settings.update');


    Route::prefix('invoices')->group(function() {
        Route::get('/', 'InvoiceController@index')->name('invoices.index');
        Route::get('/create', 'InvoiceController@create')->name('invoices.create');
        Route::post('/store', 'InvoiceController@store')->name('invoices.store');
        Route::get('/invoices/{id}', 'InvoiceController@show')->name('invoices.show');
        Route::get('/edit/{id}', 'InvoiceController@edit')->name('invoices.edit');
        Route::put('/update/{id}', 'InvoiceController@update')->name('invoices.update');
        Route::delete('/destroy/{id}', 'InvoiceController@destroy')->name('invoices.destroy');

        Route::get('/list-invoice', 'InvoiceController@getDatas');
        Route::get('/invoices/{id}/print', 'InvoiceController@print')->name('invoices.print');
        Route::get('/invoices/{id}/print-surat', 'InvoiceController@printjalan')->name('invoices.jalan');
    }); 

    Route::prefix('orders')->group(function() {
        Route::get('/', 'OrderController@index')->name('orders.index');
        Route::get('/create', 'OrderController@create')->name('orders.create');
        Route::post('/store', 'OrderController@store')->name('orders.store');
        Route::get('/{id}', 'OrderController@show')->name('orders.show');
        Route::get('/edit/{id}', 'OrderController@edit')->name('orders.edit');
        Route::put('/update/{id}', 'OrderController@update')->name('orders.update');
        Route::delete('/destroy/{id}', 'OrderController@destroy')->name('orders.destroy');
    });
    
    Route::prefix('vendor')->group(function () {
        Route::resource('invoice', 'VendorInvoiceController');
        Route::apiResource('account', 'VendorAccountController');
        Route::resource('payment-history', 'PaymentHistoryController');
        Route::post('payment-history-import', 'PaymentHistoryController@import')->name('payment-history-import');
        Route::post('payment-history-export', 'PaymentHistoryController@exportReport')->name('payment-history-export');
        Route::post('update-color', 'PaymentHistoryController@updateColor')->name('update-color');
        Route::post('get-customer-invoice', 'VendorInvoiceController@getCustomerInvoice')->name('get-customer-invoice');
        Route::get('update-profit-vendor', 'VendorInvoiceController@updateProfitVendor')->name('update-profit-vendor');
    });

    Route::resource('user-management', 'UserManagementController');
    Route::resource('report-profit', 'ReportProfitController');


    Route::get('get-bank', 'BankController@getBank')->name('get-bank');
    Route::get('get-destination', 'ManifestController@getDestination')->name('get-destination');

    //gudang manifest
    Route::get('manifest-barang', 'ManifestController@index')->name('manifest-barang');
    Route::get('manifest-barang/create', 'ManifestController@create')->name('manifest-barang.create');
    Route::get('store-manifest-barang', 'ManifestController@store')->name('store-manifest-barang');

    //manifest
    Route::prefix('manifest')->group(function () {
        Route::post('store', 'ManifestController@store')->name('store-manifest');
        Route::get('{id}/edit','ManifestController@edit')->name('edit-manifest');
        Route::get('{id}/duplicate','ManifestController@duplicate')->name('duplicate-manifest');
        Route::get('{id}/detail','ManifestController@show')->name('show-manifest');
        Route::post('{id}/update', 'ManifestController@update')->name('update-manifest');
        Route::post('{id}/update-status', 'ManifestController@updateStatus')->name('update-manifest-status');
        Route::post('{id}/delete', 'ManifestController@delete')->name('delete-manifest');
        Route::get('get-vendor', 'ManifestController@getVendor')->name('get-vendor');
        Route::get('cetak-resi/{id}', 'ManifestController@cetakResi')->name('cetakResi');
    });


    // tracking
    Route::prefix('tracking')->name('tracking.')->group(function () {
        Route::get('/', 'ManifestTrackingController@index')->name('index');
        Route::get('/create', 'ManifestTrackingController@create')->name('create');
        Route::get('/get-manifest', 'ManifestTrackingController@getManifestdosntHaveTracking')->name('get-manifest');
        Route::post('/store', 'ManifestTrackingController@store')->name('store');
        Route::post('/update-selected', 'ManifestTrackingController@updateSelectedTracking')->name('update-selected-tracking');
        Route::post('/update', 'ManifestTrackingController@update')->name('update');
        Route::post('/delete', 'ManifestTrackingController@delete')->name('delete');
        Route::get('/get-status', 'ManifestTrackingController@getStatus')->name('get-status');
    });

    Route::prefix('outbond')->name('outbond.')->group(function () {
        Route::get('/', 'OutbondController@index')->name('index');
        Route::get('/create', 'OutbondController@create')->name('create');
        Route::get('/{id}/edit', 'OutbondController@edit')->name('edit');
        Route::post('/store', 'OutbondController@store')->name('store');
        Route::post('/update', 'OutbondController@update')->name('update');
        Route::post('/delete', 'OutbondController@delete')->name('delete');
        Route::get('/get-manifest', 'OutbondController@getManifest')->name('get-manifest');
        Route::get('/cetak/{id}', 'OutbondController@printOutbond')->name('cetak');
    });



    // Route::get('tracking-list', 'TrackingController@index')->name('tracking-barang');
    // Route::get('create-tracking-list', 'TrackingController@create')->name('tracking.create');

    // complain
    Route::get('user-complain', 'ComplainController@index')->name('user-complain');
    Route::put('user-complain/{id}/user-complain-update', 'ComplainController@updateStatus')->name('update-complain');
    Route::get('{id}/user-complain-show', 'ComplainController@show')->name('show-complain');

    //delivery atau tracking


});
//Route::get('logout', 'AuthController@logout')->name('logout');

Route::get('logout', function () {
    Session()->flush();
    auth()->logout();

    return \Illuminate\Support\Facades\Redirect::to('/');
})->name('logout');






Route::get('update-invoice-ph', 'VendorSpendingController@updateInvoiceIds')->name('update-invoice-ph');
