<?php

// Public Routes

use App\Http\Controllers\Public\HomeController;
use Illuminate\Support\Facades\Route;

// Home


Route::prefix('/')->name('public.')->group(function () {
    // User Management
    Route::get('/', [HomeController::class, 'index'])->name('home');
});
