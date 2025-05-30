<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Modules\CMS\Http\Controllers\BrandController;
use Modules\CMS\Http\Controllers\CategoryController;
use Modules\CMS\Http\Controllers\OrderController;
use Modules\CMS\Http\Controllers\ProductController;

//Route::middleware(['auth', 'verified'])->group(function () {
//    Route::resource('cms', CMSController::class)->names('cms');
//});

Route::group(['prefix' => 'cms'], function () {
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
    });

    Route::group(['prefix' => 'brands'], function () {
        Route::get('/index', [BrandController::class, 'index'])->name('brands.index');
        Route::get('/create', [BrandController::class, 'create'])->name('brands.create');
        Route::post('/store', [BrandController::class, 'store'])->name('brands.store');
    });

    Route::group(['prefix' => 'orders'], function () {
        Route::get('/index', [OrderController::class, 'index'])->name('orders.index');
    });
});
