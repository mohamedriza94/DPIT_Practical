<?php
use Illuminate\Support\Facades\Route;

Route::prefix('client')->namespace('App\Http\Controllers\Client')->middleware(['web'])->group(function () {
    
    Route::get('/', 'LoginController@showLoginForm')->name('client.login');
    Route::post('/', 'LoginController@validateLogin')->name('client.login.submit');
    
    Route::middleware(['auth:client'])->group(function () {
        
        Route::post('/logout', 'LoginController@logout')->name('client.logout');
        
        Route::prefix('dashboard')->middleware(['auth:client'])->group(function () {

            //page route
            Route::get('/', 'HomeController@dashboard')->name('client.dashboard');

            //purchase requests
            Route::get('/readItems', 'PurchaseRequestController@readItems');

            Route::post('/createPurchaseRequest', 'PurchaseRequestController@create');
            Route::get('/readPurchaseRequest', 'PurchaseRequestController@read');
            Route::get('/readOnePurchaseRequest', 'PurchaseRequestController@readOne');
            Route::put('/updatePurchaseRequest', 'PurchaseRequestController@update');
            Route::get('/searchPurchaseRequest', 'PurchaseRequestController@search');
            
            //purchases
            Route::get('/readPurchase', 'PurchaseController@read');
        });
    });
});