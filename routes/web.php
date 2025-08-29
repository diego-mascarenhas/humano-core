<?php

use Illuminate\Support\Facades\Route;
use Idoneo\HumanoCore\Http\Controllers\UserController;
use Idoneo\HumanoCore\Http\Controllers\CategoryController;
use Idoneo\HumanoCore\Http\Controllers\ActivityLogController;
use Idoneo\HumanoCore\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Core Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['web', 'auth', 'verified'])->group(function () {
    
    // Users
    Route::resource('users', UserController::class);
    
    // Categories
    Route::resource('categories', CategoryController::class);
    Route::post('/categories/order', [CategoryController::class, 'updateOrder'])->name('categories.order');
    Route::get('/categories/{id}/items', [CategoryController::class, 'showItems'])->name('categories.items');
    
    // Activity Log
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('/activity-log/{id}', [ActivityLogController::class, 'show'])->name('activity-log.show');
    
    // Dashboard Analytics
    Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])->name('dashboard.analytics');
    
});
