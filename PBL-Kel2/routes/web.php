<?php
    use App\Http\Controllers\Auth\LoginController;
    use App\Http\Controllers\Auth\RegisterController;
    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\Admin\AuthController as AdminAuth;
    use App\Http\Controllers\Admin\DashboardController;
    use Illuminate\Support\Facades\Route ;
    use App\Http\Controllers\Admin\LaporanPenjualanIndividuController;



    use App\Http\Controllers\LaporanPenjualanController;



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
            
            // Laporan Penjualan Routes
            Route::prefix('dashboard/laporan-penjualan')->group(function() {
                Route::get('/', [LaporanPenjualanIndividuController::class, 'index'])->name('penjualanIndividu.index');
                Route::get('/create', [LaporanPenjualanIndividuController::class, 'create'])->name('penjualanIndividu.create');
                Route::post('/', [LaporanPenjualanIndividuController::class, 'store'])->name('penjualanIndividu.store');
                Route::get('/edit/{id}', [LaporanPenjualanIndividuController::class, 'edit'])->name('penjualanIndividu.edit');
                Route::put('/edit/{id}', [LaporanPenjualanIndividuController::class, 'update'])->name('penjualanIndividu.update');
                Route::delete('/{id}', [LaporanPenjualanIndividuController::class, 'destroy'])->name('penjualanIndividu.destroy');
            });
        });
    });