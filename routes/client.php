<?php
use Illuminate\Support\Facades\Route;

Route::prefix('client')->namespace('App\Http\Controllers\Client')->middleware(['web'])->group(function () {
    
    Route::get('/', 'LoginController@showLoginForm')->name('client.login');
    Route::post('/', 'LoginController@validateLogin')->name('client.login.submit');
    
    Route::middleware(['auth:client'])->group(function () {
        
        Route::post('/logout', 'LoginController@logout')->name('client.logout');
        
        Route::prefix('dashboard')->middleware(['auth:client'])->group(function () {

            //page route
            Route::get('/', 'Dashboard\HomeController@dashboard')->name('client.dashboard');

            //purchase requests
            Route::get('/readItems', 'Dashboard\PurchaseRequestController@readItems');

            Route::post('/createPurchaseRequest', 'Dashboard\PurchaseRequestController@create');
            Route::get('/readPurchaseRequest', 'Dashboard\PurchaseRequestController@read');
            Route::get('/readOnePurchaseRequest/{id}', 'Dashboard\PurchaseRequestController@readOne');
            Route::post('/updatePurchaseRequest', 'Dashboard\PurchaseRequestController@update');
            Route::get('/searchPurchaseRequest/{search}', 'Dashboard\PurchaseRequestController@search');
            
            //purchases
            Route::get('/readPurchase', 'Dashboard\PurchaseController@read');
        });
    });
});