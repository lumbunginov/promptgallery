<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalleryController;

Route::middleware('api')->group(function () {
    Route::get('/image-styles/{id}', [GalleryController::class, 'show']);
    Route::get('/image-styles', [\App\Http\Controllers\GalleryController::class, 'apiList']);
}); 