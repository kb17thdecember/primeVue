<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Modules\CMS\Http\Controllers\CMSController;

//Route::middleware(['auth', 'verified'])->group(function () {
//    Route::resource('cms', CMSController::class)->names('cms');
//});

Route::group(['prefix' => 'cms'], function () {
    Route::get('/home', function () {
        return Inertia::render('dashboard/Home');
    });
    Route::get('/category/index', function () {
        return Inertia::render('categories/Index');
    });
});
