<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Routes d'authentification publiques
Route::prefix('/auth')->group(function () {
    Route::get('/login',[AuthController::class,'show_login'])->name('auth.login.view');
    Route::get('/logout',[AuthController::class,'logout'])->name('auth.logout.view');
    Route::post('/login',[AuthController::class,'login'])->name('auth.login.post');
    Route::post('/register',[AuthController::class,'register'])->name('auth.register.post');
    Route::get('/register',[AuthController::class,'show_register'])->name('auth.register.view');
    Route::get('/confirm-mail/{token}',[AuthController::class,'confirmEmail'])->name('auth.confirm.mail');
    Route::get('/forget-password',[AuthController::class,'view_password_reset'])->name('auth.forget_password.view');
    Route::post('/forget-password',[AuthController::class,'password_forget'])->name('auth.forget_password.post');
    Route::get('/reset-password',[AuthController::class,'password_reset_token'])->name('auth.reset_password.view');
    Route::post('/reset-password',[AuthController::class,'password_change'])->name('auth.reset_password.post');
});