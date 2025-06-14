<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Modules\CMS\Http\Controllers\AuthController;
use Modules\CMS\Http\Controllers\BrandController;
use Modules\CMS\Http\Controllers\CategoryController;
use Modules\CMS\Http\Controllers\DashboardController;
use Modules\CMS\Http\Controllers\OrderController;
use Modules\CMS\Http\Controllers\ProductController;
use Modules\CMS\Http\Controllers\SettingController;
use Modules\CMS\Http\Controllers\ShopController;
use Modules\CMS\Http\Controllers\SubscriberHistoryController;
use Modules\CMS\Http\Middleware\TrackShopFrequency;
use Modules\CMS\Http\Controllers\PricingController;

Route::middleware(['web', TrackShopFrequency::class,])->get('/cms/test-track', function () {
    return response()->json(['message' => 'Tracked']);
});

Route::get('/cms/', function () {
    return redirect()->route('cms.dashboard');
});

Route::get('/cms/login', [AuthController::class, 'formLogin'])->name('login.form')->middleware('guest:admin');
Route::post('/cms/login', [AuthController::class, 'login'])->name('cms.login');
Route::post('/cms/logout', [AuthController::class, 'logout'])->name('cms.logout');

Route::prefix('cms')->group(function () {
    Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// Protected routes
Route::group(['prefix' => 'cms', 'middleware' => ['admin.auth']], function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('cms.dashboard');
    Route::get('/dashboard/shop-frequency', [DashboardController::class, 'shopFrequency']);

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/index', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/store', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('/index', [ProductController::class, 'index'])->name('products.index');
        Route::get('/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/store', [ProductController::class, 'store'])->name('products.store');
        Route::get('{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    Route::group(['prefix' => 'subscriber-histories'], function () {
        Route::get('/index', [SubscriberHistoryController::class, 'index'])->name('subscriber-histories.index');
    });

    Route::group(['prefix' => 'brands'], function () {
        Route::get('/index', [BrandController::class, 'index'])->name('brands.index');
        Route::get('/create', [BrandController::class, 'create'])->name('brands.create');
        Route::post('/store', [BrandController::class, 'store'])->name('brands.store');
        Route::get('{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
        Route::put('{brand}', [BrandController::class, 'update'])->name('brands.update');
        Route::delete('{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');
    });

    Route::group(['prefix' => 'orders'], function () {
        Route::get('/index', [OrderController::class, 'index'])->name('orders.index');
        Route::post('/create', [OrderController::class, 'store'])->name('orders.store');

        Route::post('/stripe/setup-intent', [OrderController::class, 'stripeSetupIntent'])->name('orders.stripeSetupIntent');
        Route::post('/stripe/payment', [OrderController::class, 'stripePaymentIntent'])->name('orders.stripePaymentIntent');

        Route::get('/analysis', [OrderController::class, 'analysis'])->name('orders.analysis');
    });

    Route::group(['prefix' => 'pricing'], function () {
        Route::get('/index', [PricingController::class, 'index'])->name('pricing.index');
    });

    Route::group(['prefix' => 'shops'], function () {
        Route::get('/index', [ShopController::class, 'index'])->name('shops.index');
        Route::get('/create', [ShopController::class, 'create'])->name('shops.create');
        Route::post('/store', [ShopController::class, 'store'])->name('shops.store');
        Route::get('/key/edit', [ShopController::class, 'showKey'])->name('shops.key.edit');
        Route::get('/key/show',[ShopController::class, 'showKey'])->name('shops.key.show');
        Route::put('/status', [ShopController::class, 'updateStatus'])->name('shops.status');
        Route::get('{shop}/edit', [ShopController::class, 'edit'])->name('shops.edit');
        Route::put('{shop}', [ShopController::class, 'update'])->name('shops.update');
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::get('/edit', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/update', [SettingController::class, 'update'])->name('settings.update');
    });
});
