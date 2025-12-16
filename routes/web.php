<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;

// redirect root ke login
Route::get('/', fn () => redirect('/login'));

// Login routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Semua route yang butuh login
Route::middleware('auth')->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Master data
    Route::resource('users', UserController::class);
    Route::resource('menus', MenuController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('shifts', ShiftController::class);

    // Transaksi (kalau memang cuma pakai 4 action ini)
    Route::resource('transactions', TransactionController::class)->only([
        'index', 'create', 'store', 'show',
    ]);

    // Laporan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
    
    Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.index');
    Route::post('/kitchen/item/{id}/toggle', [KitchenController::class, 'toggleItem'])->name('kitchen.item.toggle');
    Route::post('/kitchen/order/{id}/done', [KitchenController::class, 'doneOrder'])->name('kitchen.order.done');
});

