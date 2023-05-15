<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Accessing and calling another route
    $url = route('client.dashboard');
    return redirect($url);
});

