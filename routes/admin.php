<?php
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->middleware(['web'])->group(function () {
    
    Route::get('/', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('/', 'LoginController@validateLogin')->name('admin.login.submit');
    
    Route::middleware(['auth:admin'])->group(function () {
        
        Route::post('/logout', 'LoginController@logout')->name('admin.logout');
        
        Route::prefix('dashboard')->middleware(['auth:admin'])->group(function () {

            //page route
            Route::get('/', 'HomeController@dashboard')->name('admin.dashboard');

            //items
            Route::post('/createItem', 'ItemController@create');
            Route::get('/readItem', 'ItemController@read');
            Route::get('/readOneItem', 'ItemController@readOne');
            Route::put('/updateItem', 'ItemController@update');
            Route::get('/searchItem', 'ItemController@search');

            //purchase requests
            Route::get('/readPurchaseRequest', 'PurchaseRequestController@read');
            Route::get('/readOnePurchaseRequest', 'PurchaseRequestController@readOne');
            Route::put('/updatePurchaseRequest', 'PurchaseRequestController@update');
            Route::get('/searchPurchaseRequest', 'PurchaseRequestController@search');

            //purchase
            Route::get('/readPurchase', 'PurchaseController@read');
            Route::get('/searchPurchase', 'PurchaseController@search');
        });
    });
});