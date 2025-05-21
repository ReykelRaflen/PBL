<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanPenerbitanIndividuController;

// User Routes
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuth::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuth::class, 'login']);
    Route::post('/logout', [AdminAuth::class, 'logout'])->name('admin.logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Laporan Penerbitan Individu Routes
        Route::prefix('dashboard/penerbitan-individu')->group(function() {
            Route::get('/', [LaporanPenerbitanIndividuController::class, 'index'])->name('penerbitanIndividu.index');
            Route::get('/create', [LaporanPenerbitanIndividuController::class, 'create'])->name('penerbitanIndividu.create');
            Route::post('/', [LaporanPenerbitanIndividuController::class, 'store'])->name('penerbitanIndividu.store');
            Route::get('/{id}/edit', [LaporanPenerbitanIndividuController::class, 'edit'])->name('penerbitanIndividu.edit');
            Route::put('/{id}', [LaporanPenerbitanIndividuController::class, 'update'])->name('penerbitanIndividu.update');
            Route::delete('/{id}', [LaporanPenerbitanIndividuController::class, 'destroy'])->name('penerbitanIndividu.destroy');
        });
    });
});
