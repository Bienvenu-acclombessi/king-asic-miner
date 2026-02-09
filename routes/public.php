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
use App\Http\Controllers\Public\HostingController;
use Illuminate\Support\Facades\Route;

// Home


Route::prefix('/')->name('public.')->group(function () {
    // Home
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Shop
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/category/{slug}', [ShopController::class, 'categoryView'])->name('shop.category');
    Route::get('/shop/tag/{slug}', [ShopController::class, 'filterByTag'])->name('shop.tag');
    Route::get('/shop/collection/{slug}', [ShopController::class, 'filterByCollection'])->name('shop.collection');
    Route::get('/shop/brand/{brandId}', [ShopController::class, 'filterByBrand'])->name('shop.brand');

    // Product
    Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/get', [CartController::class, 'get'])->name('cart.get');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{lineId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{lineId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/check-product/{productId}', [CartController::class, 'checkProduct'])->name('cart.check-product');
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
    Route::delete('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.remove-coupon');

    // Shipping Methods
    Route::get('/cart/shipping-methods', [CartController::class, 'getShippingMethods'])->name('cart.shipping-methods');
    Route::post('/cart/set-shipping-method', [CartController::class, 'setShippingMethod'])->name('cart.set-shipping-method');

    // Checkout (protected by auth middleware)
    Route::middleware('auth')->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout/add-address', [CheckoutController::class, 'addAddress'])->name('checkout.add-address');
        Route::post('/checkout/select-address', [CheckoutController::class, 'selectAddress'])->name('checkout.select-address');
        Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place-order');
        Route::get('/checkout/payment/{order}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    });

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

    // Hosting
    Route::get('/hosting', [HostingController::class, 'index'])->name('hosting.index');

    // Help Center
    Route::get('/help-center', [HelpCenterController::class, 'index'])->name('help-center.index');
    Route::get('/help-center/search', [HelpCenterController::class, 'search'])->name('help-center.search');
    Route::get('/help-center/category/{slug}', [HelpCenterController::class, 'category'])->name('help-center.category');
    Route::get('/help-center/article/{slug}', [HelpCenterController::class, 'article'])->name('help-center.article');

    // API Routes for AJAX
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/brands', [HomeController::class, 'getBrands'])->name('brands');
        Route::get('/products-by-brand/{brandId}', [HomeController::class, 'getProductsByBrand'])->name('products-by-brand');
    });
});
