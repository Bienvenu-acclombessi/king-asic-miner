<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CollectionGroupController;
use App\Http\Controllers\Admin\ProductTypeController;
use App\Http\Controllers\Admin\ProductOptionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\TaxClassController;
use App\Http\Controllers\Admin\TaxRateAmountController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\EmailCampaignController;
use Illuminate\Support\Facades\Route;

// Admin Routes

Route::prefix('/admin')->name('admin.')->middleware('admin')->group(function () {
    // Dashboard
    Route::get('/',[DashboardController::class,'index'])->name('dashboard');

    // Collection Groups Management
    Route::resource('collection-groups', CollectionGroupController::class)->except(['show', 'create']);

    // Categories Management
    Route::resource('categories', CategoryController::class)->except(['show', 'create']);

    // Product Types Management
    Route::resource('product-types', ProductTypeController::class)->except(['show', 'create']);

    // Product Options Management
    Route::resource('product-options', ProductOptionController::class)->except(['show', 'create']);

    // Tags Management
    Route::resource('tags', TagController::class)->except(['show', 'create']);

    // Brands Management
    Route::resource('brands', BrandController::class)->except(['show', 'create']);

    // Products Management
    Route::resource('products', ProductController::class);

    // Tax Classes Management
    Route::resource('tax-classes', TaxClassController::class)->except(['create']);

    // Tax Rate Amounts Management (nested under tax classes)
    Route::resource('tax-rate-amounts', TaxRateAmountController::class)->only(['store', 'edit', 'update', 'destroy']);

    // Orders Management
    Route::resource('orders', OrderController::class)->except(['create', 'store', 'destroy']);

    // Customers Management
    Route::resource('customers', CustomerController::class);

    // Attributes Management
    Route::resource('attributes', AttributeController::class);

    // Coupons Management
    Route::resource('coupons', CouponController::class)->except(['show']);

    // Promotions Management
    Route::resource('promotions', PromotionController::class);

    // Reviews Management
    Route::resource('reviews', ReviewController::class)->only(['index', 'edit', 'update', 'destroy']);

    // Email Campaigns Management
    Route::resource('email-campaigns', EmailCampaignController::class)->except(['show']);
});
