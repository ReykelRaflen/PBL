<?php
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanPenjualanIndividuController;
use App\Http\Controllers\Admin\LaporanPenjualanKolaborasiController;
use App\Http\Controllers\Admin\LaporanPenerbitanIndividuController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\LaporanPenerbitanKolaborasiController;

// User Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
// Verification routes
Route::get('/email/verify', [RegisterController::class, 'showVerificationForm'])->name('verification.notice');
Route::post('/email/verify', [RegisterController::class, 'verifyOtp'])->name('verification.verify');
Route::post('/email/resend', [RegisterController::class, 'resendOtp'])->name('verification.resend');
// Password Reset Routes
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{email}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update');
Route::post('/password/resend-otp', [ForgotPasswordController::class, 'resendOtp'])->name('password.resend');


Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuth::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuth::class, 'login']);
    Route::post('/logout', [AdminAuth::class, 'logout'])->name('admin.logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Laporan Penerbitan Individu
        Route::prefix('dashboard/penerbitan-individu')->name('penerbitanIndividu.')->group(function () {
            Route::get('/', [LaporanPenerbitanIndividuController::class, 'index'])->name('index');
            Route::get('/create', [LaporanPenerbitanIndividuController::class, 'create'])->name('create');
            Route::post('/', [LaporanPenerbitanIndividuController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [LaporanPenerbitanIndividuController::class, 'edit'])->name('edit');
            Route::put('/{id}', [LaporanPenerbitanIndividuController::class, 'update'])->name('update');
            Route::delete('/{id}', [LaporanPenerbitanIndividuController::class, 'destroy'])->name('destroy');
        });

        // Laporan Penerbitan Kolaborasi
        Route::prefix('dashboard/penerbitan-kolaborasi')->name('penerbitanKolaborasi.')->group(function () {
            Route::get('/', [LaporanPenerbitanKolaborasiController::class, 'index'])->name('index');
            Route::get('/create', [LaporanPenerbitanKolaborasiController::class, 'create'])->name('create');
            Route::post('/', [LaporanPenerbitanKolaborasiController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [LaporanPenerbitanKolaborasiController::class, 'edit'])->name('edit');
            Route::put('/{id}', [LaporanPenerbitanKolaborasiController::class, 'update'])->name('update');
            Route::delete('/{id}', [LaporanPenerbitanKolaborasiController::class, 'destroy'])->name('destroy');
        });

            // Laporan Penjualan Individu Routes
            Route::prefix('dashboard/laporan-penjualan')->group(function () {
                Route::get('/', [LaporanPenjualanIndividuController::class, 'index'])->name('penjualanIndividu.index');
                Route::get('/create', [LaporanPenjualanIndividuController::class, 'create'])->name('penjualanIndividu.create');
                Route::post('/', [LaporanPenjualanIndividuController::class, 'store'])->name('penjualanIndividu.store');
                Route::get('/{id}/edit', [LaporanPenjualanIndividuController::class, 'edit'])->name('penjualanIndividu.edit');
                Route::put('/{id}', [LaporanPenjualanIndividuController::class, 'update'])->name('penjualanIndividu.update');
                Route::delete('/{id}', [LaporanPenjualanIndividuController::class, 'destroy'])->name('penjualanIndividu.destroy');
            });

            // Laporan Penjualan Kolaborasi Routes
            Route::prefix('dashboard/laporan-penjualan-kolaborasi')->group(function () {
                Route::get('/', [LaporanPenjualanKolaborasiController::class, 'index'])->name('penjualanKolaborasi.index');
                Route::get('/create', [LaporanPenjualanKolaborasiController::class, 'create'])->name('penjualanKolaborasi.create');
                Route::post('/', [LaporanPenjualanKolaborasiController::class, 'store'])->name('penjualanKolaborasi.store');
                Route::get('/{id}/edit', [LaporanPenjualanKolaborasiController::class, 'edit'])->name('penjualanKolaborasi.edit');
                Route::put('/{id}', [LaporanPenjualanKolaborasiController::class, 'update'])->name('penjualanKolaborasi.update');
                Route::delete('/{id}', [LaporanPenjualanKolaborasiController::class, 'destroy'])->name('penjualanKolaborasi.destroy');
            });

            // Promo Routes
            Route::prefix('dashboard/promo')->group(function () {
                Route::get('/', [PromoController::class, 'index'])->name('promos.index');
                Route::get('/create', [PromoController::class, 'create'])->name('promos.create');
                Route::post('/', [PromoController::class, 'store'])->name('promos.store');
                Route::get('/{promo}', [PromoController::class, 'show'])->name('promos.show');
                Route::get('/{promo}/edit', [PromoController::class, 'edit'])->name('promos.edit');
                Route::put('/{promo}', [PromoController::class, 'update'])->name('promos.update');
                Route::delete('/{promo}', [PromoController::class, 'destroy'])->name('promos.destroy');
            });
        });
    });

