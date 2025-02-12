<?php

use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\StatisticsController as AdminStatisticsController;

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    Route::resource('products', AdminProductController::class)->except(['show'])->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);


    Route::prefix('coupons')->name('admin.coupons.')->group(function () {
        Route::get('/', [AdminCouponController::class, 'index'])->name('index');
        Route::get('/create', [AdminCouponController::class, 'create'])->name('create');
        Route::post('/store', [AdminCouponController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminCouponController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminCouponController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminCouponController::class, 'destroy'])->name('destroy');
    });


    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::put('/users/{id}/roles', [AdminUserController::class, 'updateRoles'])->name('admin.users.updateRoles');


    Route::get('/statistics', [AdminStatisticsController::class, 'index'])->name('admin.statistics');


});

Route::middleware(['auth', 'role:admin|manager'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('/orders', AdminOrderController::class)->names([
        'index' => 'admin.orders.index',
        'show' => 'admin.orders.show',
        'update' => 'admin.orders.update',
    ]);
});
