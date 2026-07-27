<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GrowthLogController;
use App\Http\Controllers\Admin\HydroponicsController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductSupplierController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\SiteController;
use App\Http\Controllers\Admin\StaffMemberController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VisitorController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'create'])->name('login');
        Route::post('login', [LoginController::class, 'store'])->name('login.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::resource('users', UserController::class);
        Route::resource('sites', SiteController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('products', ProductController::class);
        Route::resource('product-suppliers', ProductSupplierController::class);
        Route::resource('hydroponics', HydroponicsController::class);
        Route::resource('growth-logs', GrowthLogController::class);
        Route::resource('clients', ClientController::class);
        Route::resource('visitors', VisitorController::class)->except(['show']);
        Route::resource('staff-members', StaffMemberController::class)->except(['show']);
        Route::resource('sales', SaleController::class);
    });
});
