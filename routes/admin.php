<?php
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->middleware(['web'])->group(function () {
    
    Route::get('/', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('/', 'LoginController@validateLogin')->name('admin.login.submit');
    
    Route::middleware(['auth:admin'])->group(function () {
        
        Route::post('/logout', 'LoginController@logout')->name('admin.logout');
        
        Route::prefix('dashboard')->middleware(['auth:admin'])->group(function () {

            //page route
            Route::get('/', 'Dashboard\HomeController@dashboard')->name('admin.dashboard');
            Route::get('/', 'Dashboard\HomeController@item')->name('admin.item');

            //items
            Route::post('/createItem', 'Dashboard\ItemController@create');
            Route::get('/readItem', 'Dashboard\ItemController@read');
            Route::get('/readOneItem/{id}', 'Dashboard\ItemController@readOne');
            Route::get('/updateQuantity/{id}', 'Dashboard\ItemController@updateQuantity');
            Route::post('/updateItem', 'Dashboard\ItemController@update');
            Route::get('/searchItem/{search}', 'Dashboard\ItemController@search');

            //purchase requests
            Route::get('/readPurchaseRequest', 'Dashboard\PurchaseRequestController@read');
            Route::get('/readOnePurchaseRequest/{id}', 'Dashboard\PurchaseRequestController@readOne');
            Route::post('/updatePurchaseRequestStatus', 'Dashboard\PurchaseRequestController@updateStatus');
            Route::get('/searchPurchaseRequest/{search}', 'Dashboard\PurchaseRequestController@search');

            //purchase
            Route::get('/readPurchase', 'Dashboard\PurchaseController@read');
            Route::get('/searchPurchase/{search}', 'Dashboard\PurchaseController@search');
        });
    });
});