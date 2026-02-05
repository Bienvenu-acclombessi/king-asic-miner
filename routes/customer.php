<?php

// Authenticated User Routes

use App\Http\Controllers\Public\HomeController;
use Illuminate\Support\Facades\Route;

Route::prefix('/client')->name('client.')->group(function () {
    // User Management
    Route::get('/', [HomeController::class, 'index'])->name('home');
});
