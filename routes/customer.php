<?php

// Authenticated Customer Routes

use App\Http\Controllers\Customer\AccountController;
use App\Http\Controllers\Customer\ProfilController;
use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\PasswordController;
use Illuminate\Support\Facades\Route;

Route::prefix('/account')->name('customer.')->middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');

    // Profile Management
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::post('/profil', [ProfilController::class, 'update'])->name('profil.update');

    // Email Management
    Route::get('/email/edit', [AccountController::class, 'editEmail'])->name('email.edit');
    Route::post('/email/update', [AccountController::class, 'updateEmail'])->name('email.update');

    // Password Management
    Route::get('/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::post('/password', [PasswordController::class, 'update'])->name('password.update');

    // Address Management
    Route::get('/addresses', [AddressController::class, 'index'])->name('address.index');
    Route::post('/addresses', [AddressController::class, 'store'])->name('address.store');
    Route::put('/addresses/{id}', [AddressController::class, 'update'])->name('address.update');
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy'])->name('address.destroy');
    Route::post('/addresses/{id}/set-default', [AddressController::class, 'setDefault'])->name('address.set-default');

    // Orders Management
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{id}/reorder', [OrderController::class, 'reorder'])->name('orders.reorder');

    // Account Deletion
    Route::delete('/account', [AccountController::class, 'destroy'])->name('account.destroy');
});
