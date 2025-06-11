<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Modules\CMS\Http\Controllers\AuthController;
use Modules\CMS\Http\Controllers\BrandController;
use Modules\CMS\Http\Controllers\CategoryController;
use Modules\CMS\Http\Controllers\OrderController;
use Modules\CMS\Http\Controllers\ProductController;
use Modules\CMS\Http\Controllers\ShopController;

// Public routes
Route::get('/cms/login', [AuthController::class, 'formLogin'])->name('login.form')->middleware('guest:admin');
Route::post('/cms/login', [AuthController::class, 'login'])->name('cms.login');
Route::post('/cms/logout', [AuthController::class, 'logout'])->name('cms.logout');

// Protected routes
Route::group(['prefix' => 'cms', 'middleware' => ['admin.auth']], function () {
    // Dashboard - Tất cả admin đều có thể truy cập
    Route::get('/home', function () {
        return Inertia::render('dashboard/Home');
    })->name('dashboard');

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
        Route::get('/analysis', [OrderController::class, 'analysis'])->name('orders.analysis');
    });

    Route::group(['prefix' => 'shops'], function () {
        Route::get('/index', [ShopController::class, 'index'])->name('shops.index');
        Route::get('/create', [ShopController::class, 'create'])->name('shops.create');
        Route::post('/store', [ShopController::class, 'store'])->name('shops.store');
    });
});
