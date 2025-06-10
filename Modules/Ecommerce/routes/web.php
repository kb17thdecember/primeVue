<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Modules\Ecommerce\Http\Controllers\EcommerceController;
use Modules\Ecommerce\Http\Controllers\HomeController;

//Route::middleware(['auth', 'verified'])->group(function () {
//    Route::resource('ecommerces', EcommerceController::class)->names('ecommerce');
//});

Route::prefix('ecommerce')->group(function () {
    Route::group(['prefix' => 'home'], function () {
        Route::get('/index', [HomeController::class, 'index'])->name('homes.index');
    });
});
