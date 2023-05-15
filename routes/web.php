<?php

use Illuminate\Support\Facades\Route;


Route::get('/', [Client\LoginController::class, 'showLoginForm'])->name('client.login');