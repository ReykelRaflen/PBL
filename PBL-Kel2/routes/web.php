<?php
use App\Http\Controllers\Admin\ManajemenAkunController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanPenjualanIndividuController;
use App\Http\Controllers\Admin\LaporanPenjualanKolaborasiController;
use App\Http\Controllers\Admin\LaporanPenerbitanIndividuController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\LaporanPenerbitanKolaborasiController;
use App\Http\Controllers\Admin\KategoriBukuController;
use App\Http\Controllers\User\TemplateController as UserTemplateController;


// User Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);



// Semua route akun menggunakan middleware auth
Route::middleware(['auth'])->prefix('akun')->name('akun.')->group(function () {
    Route::get('/', [AkunController::class, 'index'])->name('index');
    Route::get('/profil', [AkunController::class, 'profil'])->name('profil');
    Route::put('/profil', [AkunController::class, 'updateProfil'])->name('profil.update');
    Route::post('/foto', [AkunController::class, 'updateFoto'])->name('foto.update');
    Route::delete('/foto', [AkunController::class, 'deleteFoto'])->name('foto.delete');
    Route::get('/kolaborasi', [AkunController::class, 'kolaborasi'])->name('kolaborasi');
    Route::get('/pembelian', [AkunController::class, 'pembelian'])->name('pembelian');
    Route::get('/download', [AkunController::class, 'download'])->name('download');
    Route::put('/password', [AkunController::class, 'changePassword'])->name('password.update');
    
    // Template routes
    Route::get('/templates', [UserTemplateController::class, 'index'])->name('templates.index');
    Route::get('/templates/{id}', [UserTemplateController::class, 'show'])->name('templates.show');
    Route::get('/templates/{id}/download', [UserTemplateController::class, 'download'])->name('templates.download');
});


// Route untuk detail buku
Route::get('/buku/detail/{id}', [BookController::class, 'show'])->name('books.show');



// Route untuk pemesanan
// Route::post('/order/create', [OrderController::class, 'create'])->name('order.create');
// Route::get('/order/confirm/{id}', [OrderController::class, 'confirm'])->name('order.confirm');

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

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');


        // Kategori Buku Routes
        Route::prefix('kategori-buku')->name('kategori-buku.')->group(function () {
            Route::get('/', [KategoriBukuController::class, 'index'])->name('index');
            Route::get('/create', [KategoriBukuController::class, 'create'])->name('create');
            Route::post('/', [KategoriBukuController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [KategoriBukuController::class, 'edit'])->name('edit');
            Route::put('/{id}', [KategoriBukuController::class, 'update'])->name('update');
            Route::delete('/{id}', [KategoriBukuController::class, 'destroy'])->name('destroy');
        });
        // Manajemen Buku Routes
        Route::prefix('books')->name('admin.books.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\BookManagementController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\BookManagementController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\BookManagementController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Admin\BookManagementController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\BookManagementController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\BookManagementController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\BookManagementController::class, 'destroy'])->name('destroy');
        });


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
            Route::get('/{id}', [LaporanPenjualanIndividuController::class, 'show'])->name('penjualanIndividu.show');
            Route::get('/{id}/edit', [LaporanPenjualanIndividuController::class, 'edit'])->name('penjualanIndividu.edit');
            Route::put('/{id}', [LaporanPenjualanIndividuController::class, 'update'])->name('penjualanIndividu.update');
            Route::delete('/{id}', [LaporanPenjualanIndividuController::class, 'destroy'])->name('penjualanIndividu.destroy');
        });

        // Laporan Penjualan Kolaborasi Routes
        Route::prefix('dashboard/laporan-penjualan-kolaborasi')->group(function () {
            Route::get('/', [LaporanPenjualanKolaborasiController::class, 'index'])->name('penjualanKolaborasi.index');
            Route::get('/create', [LaporanPenjualanKolaborasiController::class, 'create'])->name('penjualanKolaborasi.create');
            Route::post('/', [LaporanPenjualanKolaborasiController::class, 'store'])->name('penjualanKolaborasi.store');
            Route::get('/{id}', [LaporanPenjualanKolaborasiController::class, 'show'])->name('penjualanKolaborasi.show');
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

        // Manajemen Akun Routes
        Route::prefix('dashboard/akun')->group(function () {
            Route::get('/', [ManajemenAkunController::class, 'index'])->name('account.index');
            Route::get('/create', [ManajemenAkunController::class, 'create'])->name('account.create');
            Route::post('/', [ManajemenAkunController::class, 'store'])->name('account.store');
            Route::get('/{account}', [ManajemenAkunController::class, 'show'])->name('account.show');
            Route::get('/{account}/edit', [ManajemenAkunController::class, 'edit'])->name('account.edit');
            Route::put('/{account}', [ManajemenAkunController::class, 'update'])->name('account.update');
            Route::delete('/{account}', [ManajemenAkunController::class, 'destroy'])->name('account.destroy');
            Route::post('/{account}/toggle-status', [ManajemenAkunController::class, 'toggleStatus'])->name('account.toggle-status');
            Route::post('/{account}/reset-password', [ManajemenAkunController::class, 'resetPassword'])->name('account.reset-password');
        });

                // Template Routes
        Route::prefix('dashboard/template')->group(function () {
            Route::get('/', [TemplateController::class, 'index'])->name('template.index');
            Route::get('/create', [TemplateController::class, 'create'])->name('template.create');
            Route::post('/', [TemplateController::class, 'store'])->name('template.store');
            Route::get('/{template}', [TemplateController::class, 'show'])->name('template.show');
            Route::get('/{template}/edit', [TemplateController::class, 'edit'])->name('template.edit');
            Route::put('/{template}', [TemplateController::class, 'update'])->name('template.update');
            Route::delete('/{template}', [TemplateController::class, 'destroy'])->name('template.destroy');
            Route::post('/{template}/toggle-status', [TemplateController::class, 'toggleStatus'])->name('template.toggle-status');
            Route::get('/{template}/download', [TemplateController::class, 'download'])->name('template.download');
        });



    });
});

