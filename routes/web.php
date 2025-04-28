<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\TutorialController;

Route::get('/', [GalleryController::class, 'mobile']);

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/mobile-gallery', [GalleryController::class, 'mobile'])->name('mobile-gallery');
Route::get('/tutorial', [TutorialController::class, 'index'])->name('tutorial');
