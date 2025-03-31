<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\AuthAdmin;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
});

Route::middleware(['auth'],AuthAdmin::class)->group(function () {
    // Admin Dashboard Brand Routes
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/brands/add', [AdminController::class, 'add_brand'])->name('admin.add_brand');
    Route::post('/admin/brands/store', [AdminController::class, 'brand_store'])->name('admin.brand_store');
    Route::get('/admin/brands/edit/{id}', [AdminController::class, 'brand_edit'])->name('admin.brand_edit');
    Route::put('/admin/brands/update', [AdminController::class, 'brand_update'])->name('admin.brand_update');
    Route::delete('/admin/brands/delete/{id}', [AdminController::class, 'brand_delete'])->name('admin.brand_delete');

    // Admin Dashboard Category Routes
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/categories/add', [AdminController::class, 'add_category'])->name('admin.add_category');
    Route::post('/admin/categories/store', [AdminController::class, 'category_store'])->name('admin.category_store');
    Route::get('/admin/categories/edit/{id}', [AdminController::class, 'category_edit'])->name('admin.category_edit');
    Route::put('/admin/categories/update', [AdminController::class, 'category_update'])->name('admin.category_update');
    Route::delete('/admin/categories/delete/{id}', [AdminController::class, 'category_delete'])->name('admin.category_delete');

    // Admin Dashboard Product Routes
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/products/add', [AdminController::class, 'add_product'])->name('admin.add_product');
    Route::post('/admin/products/store', [AdminController::class, 'product_store'])->name('admin.product_store');
    // Route::get('/admin/products/edit/{id}', [AdminController::class, 'product_edit'])->name('admin.product_edit');
    // Route::put('/admin/products/update', [AdminController::class, 'product_update'])->name('admin.product_update');
    Route::delete('/admin/products/delete/{id}', [AdminController::class, 'product_delete'])->name('admin.product_delete');
});
