<?php

use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProductsController;
use App\Http\Controllers\Dashboard\ProfileController;
use Illuminate\Support\Facades\Route;


Route::group([
    'as' => 'dashboard.',
    'prefix' => 'admin/dashboard',
    'middleware' => ['auth:admin'],
    //'middleware' => ['auth', 'auth.type:admin,user,super-admin'],
], function(){
    Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

    Route::get('/categories/trash', [CategoriesController::class, 'trash'])
    ->name('categories.trash');

    Route::put('/categories/{category}/restore', [CategoriesController::class, 'restore'])
    ->name('categories.restore');

    Route::delete('/categories/{category}/force-delete', [CategoriesController::class, 'forceDelete'])
    ->name('categories.force-delete');

    Route::resource('/categories', CategoriesController::class);

    Route::resource('/products', ProductsController::class)->except('show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

