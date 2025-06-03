<?php
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanPenjualanIndividuController;
use App\Http\Controllers\Admin\LaporanPenjualanKolaborasiController;
use App\Http\Controllers\Admin\LaporanPenerbitanIndividuController;


// User Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Route untuk detail buku
Route::get('/buku/detail/{id}', [BookController::class, 'show'])->name('books.show');



// Route untuk pemesanan
Route::post('/order/create', [OrderController::class, 'create'])->name('order.create');
Route::get('/order/confirm/{id}', [OrderController::class, 'confirm'])->name('order.confirm');

// Verification routes
Route::get('/email/verify', [App\Http\Controllers\Auth\RegisterController::class, 'showVerificationForm'])->name('verification.notice');
Route::post('/email/verify', [App\Http\Controllers\Auth\RegisterController::class, 'verifyOtp'])->name('verification.verify');
Route::post('/email/resend', [App\Http\Controllers\Auth\RegisterController::class, 'resendOtp'])->name('verification.resend');
// Password Reset Routes
Route::get('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{email}', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'reset'])->name('password.update');
Route::post('/password/resend-otp', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'resendOtp'])->name('password.resend');

    
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

        // Laporan Penjualan Kolaborasi Routes
        Route::prefix('dashboard/laporan-penjualan-kolaborasi')->group(function() {
            Route::get('/', [LaporanPenjualanKolaborasiController::class, 'index'])->name('penjualanKolaborasi.index');
            Route::get('/create', [LaporanPenjualanKolaborasiController::class, 'create'])->name('penjualanKolaborasi.create');
            Route::post('/', [LaporanPenjualanKolaborasiController::class, 'store'])->name('penjualanKolaborasi.store');
            Route::get('/{id}/edit', [LaporanPenjualanKolaborasiController::class, 'edit'])->name('penjualanKolaborasi.edit');
            Route::put('/{id}', [LaporanPenjualanKolaborasiController::class, 'update'])->name('penjualanKolaborasi.update');
            Route::delete('/{id}', [LaporanPenjualanKolaborasiController::class, 'destroy'])->name('penjualanKolaborasi.destroy');
        });
        
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
