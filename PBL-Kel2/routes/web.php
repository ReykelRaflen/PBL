<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublishBukuController;
use App\Http\Controllers\NaskahController;

// User Routes
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

// Penerbitan Route
Route::get('/penerbitan_individu', function () {
    return view('user.penerbitan_individu');
});
Route::get('/penerbitan_kolaborasi', function () {
    return view('user.penerbitan_kolaborasi');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuth::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuth::class, 'login']);
    Route::post('/logout', [AdminAuth::class, 'logout'])->name('admin.logout');

Route::middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/dashboard', [DashboardController::class, 'dashboard']);

    // Route untuk mengelola buku
    Route::get('/publish_buku', [PublishBukuController::class, 'index']) -> name('admin.publish_buku');

    // Route untuk mengelola naskah
    Route::get('/naskah', [NaskahController::class, 'index']) -> name('admin.naskah');
    Route::post('/naskah', [NaskahController::class, 'store']) -> name('admin.naskah.store');
    Route::get('/naskahs/{naskah}', [NaskahController::class, 'show']) -> name('admin.naskah.show');
    Route::delete('/naskah/{naskah}', [NaskahController::class, 'destory']) -> name('admin.naskah.destory');

    });
});




