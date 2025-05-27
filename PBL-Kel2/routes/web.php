<?php
use App\Http\Controllers\Admin\DesignSampulController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanPenjualanIndividuController; // Add semicolon here
use App\Http\Controllers\Admin\LaporanPenjualanKolaborasiController;

// User Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuth::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuth::class, 'login']);
    Route::post('/logout', [AdminAuth::class, 'logout'])->name('admin.logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Laporan Penjualan Individu Routes
            Route::prefix('dashboard/laporan-penjualan')->group(function() {
                Route::get('/', [LaporanPenjualanIndividuController::class, 'index'])->name('penjualanIndividu.index');
                Route::get('/create', [LaporanPenjualanIndividuController::class, 'create'])->name('penjualanIndividu.create');
                Route::post('/', [LaporanPenjualanIndividuController::class, 'store'])->name('penjualanIndividu.store');
                Route::get('/{id}/edit', [LaporanPenjualanIndividuController::class, 'edit'])->name('penjualanIndividu.edit');
                Route::put('/{id}', [LaporanPenjualanIndividuController::class, 'update'])->name('penjualanIndividu.update');
                Route::delete('/{id}', [LaporanPenjualanIndividuController::class, 'destroy'])->name('penjualanIndividu.destroy');
            });

            //Laporan Penjualan Kolaborasi Routes
            Route::prefix('dashboard/laporan-penjualan-kolaborasi')->group(function() {
                Route::get('/', [LaporanPenjualanKolaborasiController::class, 'index'])->name('penjualanKolaborasi.index');
                Route::get('/create', [LaporanPenjualanKolaborasiController::class, 'create'])->name('penjualanKolaborasi.create');
                Route::post('/', [LaporanPenjualanKolaborasiController::class, 'store'])->name('penjualanKolaborasi.store');
                Route::get('/{id}/edit', [LaporanPenjualanKolaborasiController::class, 'edit'])->name('penjualanKolaborasi.edit');
                Route::put('/{id}', [LaporanPenjualanKolaborasiController::class, 'update'])->name('penjualanKolaborasi.update');
                Route::delete('/{id}', [LaporanPenjualanKolaborasiController::class, 'destroy'])->name('penjualanKolaborasi.destroy');
            });


            //Design Sampul Routes
             Route::prefix('dashboard/DesignSampul')->group(function() {
                Route::get('/', [DesignSampulController::class, 'index'])->name('DesignSampul.index');
                Route::get('/create', [DesignSampulController::class, 'create'])->name('DesignSampul.create');
                Route::post('/', [DesignSampulController::class, 'store'])->name('DesignSampul.store');
                Route::get('/{id}/edit', [DesignSampulController::class, 'edit'])->name('DesignSampul.edit');
                Route::put('/{id}', [DesignSampulController::class, 'update'])->name('DesignSampul.update');
                Route::delete('/{id}', [DesignSampulController::class, 'destroy'])->name('DesignSampul.destroy');
        });
    });
    });
