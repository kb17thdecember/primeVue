<?php

use Illuminate\Support\Facades\Route;
use Modules\CMS\Http\Controllers\CMSController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//    Route::apiResource('cms', CMSController::class)->names('cms');
});
