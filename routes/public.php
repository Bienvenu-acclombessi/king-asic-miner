<?php

// Public Routes

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ShopController;
use App\Http\Controllers\Public\ProductController;
use App\Http\Controllers\Public\CartController;
use App\Http\Controllers\Public\CheckoutController;
use App\Http\Controllers\Public\CompanyController;
use App\Http\Controllers\Public\PolicyController;
use App\Http\Controllers\Public\BulkOrderController;
use App\Http\Controllers\Public\HelpCenterController;
use Illuminate\Support\Facades\Route;

// Home


Route::prefix('/')->name('public.')->group(function () {
    // Home
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Shop
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/category/{slug}', [ShopController::class, 'categoryView'])->name('shop.category');

    // Product
    Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

    // Company
    Route::get('/about', [CompanyController::class, 'about'])->name('company.about');
    Route::get('/blog', [CompanyController::class, 'blog'])->name('company.blog');
    Route::get('/blog/{slug}', [CompanyController::class, 'blogDetail'])->name('company.blog-detail');
    Route::get('/contact', [CompanyController::class, 'contact'])->name('company.contact');
    Route::get('/faq', [CompanyController::class, 'faq'])->name('company.faq');
    Route::get('/fraud-prevention', [CompanyController::class, 'fraudPrevention'])->name('company.fraud-prevention');
    Route::get('/staff-authentification', [CompanyController::class, 'staffAuthentification'])->name('company.staff-authentification');

    // Policies
    Route::get('/privacy-policy', [PolicyController::class, 'privacy'])->name('policy.privacy');
    Route::get('/cookie-policy', [PolicyController::class, 'cookie'])->name('policy.cookie');
    Route::get('/terms-and-conditions', [PolicyController::class, 'terms'])->name('policy.terms');

    // Bulk Order
    Route::get('/bulk-order', [BulkOrderController::class, 'index'])->name('bulk-order.index');

    // Help Center
    Route::get('/help-center', [HelpCenterController::class, 'index'])->name('help-center.index');
    Route::get('/help-center/search', [HelpCenterController::class, 'search'])->name('help-center.search');
    Route::get('/help-center/category/{slug}', [HelpCenterController::class, 'category'])->name('help-center.category');
    Route::get('/help-center/article/{slug}', [HelpCenterController::class, 'article'])->name('help-center.article');
});
