<?php
use Illuminate\Support\Facades\Route;


Auth::routes();

Route::get('/', function () {
    // Accessing and calling another route
    $url = route('client.dashboard');
    return redirect($url);
});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
