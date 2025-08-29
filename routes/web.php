<?php

use Illuminate\Support\Facades\Route;
use Idoneo\HumanoCore\Http\Controllers\DashboardController;
use Idoneo\HumanoCore\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Humano Core Web Routes
|--------------------------------------------------------------------------
|
| Here are the core routes for the Humano system, including dashboard
| and category management routes.
|
*/

Route::middleware(['auth', 'verified'])->group(function ()
{
	// Dashboard Routes
	Route::get('/dashboard/analytics', [DashboardController::class, 'index'])
		->name('dashboard.analytics');

	// Categories Management Routes
	Route::prefix('categories')->name('categories.')->group(function ()
	{
		Route::get('/', [CategoryController::class, 'index'])->name('index');
		Route::get('/create', [CategoryController::class, 'create'])->name('create');
		Route::post('/', [CategoryController::class, 'store'])->name('store');
		Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
		Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
		Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
		Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
		Route::post('/order', [CategoryController::class, 'updateOrder'])->name('order');
	});
});
