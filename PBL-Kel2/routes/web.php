<?php
use App\Http\Controllers\Admin\DesignController;
use App\Http\Controllers\Admin\ManajemenAkunController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\NaskahController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\User\BukuKolaboratifController;
use App\Http\Controllers\User\PesananController;
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


use App\Http\Controllers\Admin\RekeningController;

// User Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);



// API Routes untuk promo
Route::post('/api/check-promo', [\App\Http\Controllers\Api\PromoController::class, 'checkPromo'])->name('api.check-promo');
Route::get('/api/active-promos', [\App\Http\Controllers\Api\PromoController::class, 'getActivePromos'])->name('api.active-promos');

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

// User Pesanan Routes
Route::middleware(['auth'])->group(function () {
    // GET route untuk menampilkan form pemesanan
    Route::get('/user/pesanan/create', [App\Http\Controllers\User\PesananController::class, 'showCreateForm'])->name('user.pesanan.create');

    // POST route untuk memproses pemesanan
    Route::post('/user/pesanan/store', [App\Http\Controllers\User\PesananController::class, 'store'])->name('user.pesanan.store');

    Route::get('/user/pesanan', [App\Http\Controllers\User\PesananController::class, 'index'])->name('user.pesanan.index');
    Route::get('/user/pesanan/{pesanan}', [App\Http\Controllers\User\PesananController::class, 'show'])->name('user.pesanan.show');
    Route::get('/user/pesanan/{pesanan}/payment', [App\Http\Controllers\User\PesananController::class, 'payment'])->name('user.pesanan.payment');
    Route::post('/user/pesanan/{pesanan}/upload-payment', [App\Http\Controllers\User\PesananController::class, 'uploadPayment'])->name('user.pesanan.uploadPayment');
    Route::get('/user/pesanan/{pesanan}/download-ebook', [App\Http\Controllers\User\PesananController::class, 'downloadEbook'])->name('user.pesanan.downloadEbook');
    Route::post('/user/pesanan/{pesanan}/cancel', [App\Http\Controllers\User\PesananController::class, 'cancel'])->name('user.pesanan.cancel');
});


// Routes Buku Kolaboratif
Route::middleware(['web'])->group(function () {
    Route::get('/buku-kolaboratif', [BukuKolaboratifController::class, 'index'])
        ->name('buku-kolaboratif.index');

    Route::get('/buku-kolaboratif/{bukuKolaboratif}', [BukuKolaboratifController::class, 'tampilkan'])
        ->name('buku-kolaboratif.tampilkan');

    Route::middleware(['auth'])->group(function () {
        Route::get(
            '/buku-kolaboratif/{bukuKolaboratif}/bab/{babBuku}/pilih',
            [BukuKolaboratifController::class, 'pilihBab']
        )
            ->name('buku-kolaboratif.pilih-bab');

        Route::post(
            '/buku-kolaboratif/{bukuKolaboratif}/bab/{babBuku}/pesan',
            [BukuKolaboratifController::class, 'prosesPesanan']
        )
            ->name('buku-kolaboratif.proses-pesanan');

        Route::get('/pesanan/{pesananBuku}/pembayaran', [BukuKolaboratifController::class, 'pembayaran'])
            ->name('buku-kolaboratif.pembayaran');

        Route::post('/pesanan/{pesananBuku}/pembayaran', [BukuKolaboratifController::class, 'prosesPembayaran'])
            ->name('buku-kolaboratif.proses-pembayaran');

        Route::get('/pesanan/{pesananBuku}/status', [BukuKolaboratifController::class, 'statusPesanan'])
            ->name('buku-kolaboratif.status-pesanan');
        Route::post('/pesanan/{id}/upload-naskah', [BukuKolaboratifController::class, 'uploadNaskah'])
            ->name('buku-kolaboratif.upload-naskah');
        Route::get('/pesanan/{id}/download-naskah', [BukuKolaboratifController::class, 'downloadNaskah'])
            ->name('buku-kolaboratif.download-naskah');
    });
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

// Admin Routes
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
            Route::patch('/{id}/verifikasi', [LaporanPenjualanKolaborasiController::class, 'verifikasiPembayaran'])->name('penjualanKolaborasi.verifikasi');
            Route::get('/{id}/download-bukti', [LaporanPenjualanKolaborasiController::class, 'downloadBukti'])->name('penjualanKolaborasi.download-bukti');
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
        Route::prefix('dashboard/akun')->name('admin.')->group(function () {
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

        // Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        //     Route::get('/', [PembayaranController::class, 'index'])->name('index');
        //     Route::get('/{pembayaran}', [PembayaranController::class, 'show'])->name('show');
        //     Route::patch('/{pembayaran}/status', [PembayaranController::class, 'updateStatus'])->name('updateStatus');
        //     Route::post('/{pembayaran}/quick-approve', [PembayaranController::class, 'quickApprove'])->name('quickApprove');
        //     Route::post('/{pembayaran}/quick-reject', [PembayaranController::class, 'quickReject'])->name('quickReject');
        //     Route::post('/bulk-action', [PembayaranController::class, 'bulkAction'])->name('bulkAction');
        //     Route::get('/{pembayaran}/download-bukti', [PembayaranController::class, 'downloadBukti'])->name('downloadBukti');
        //     Route::get('/{pembayaran}/invoice', [PembayaranController::class, 'generateInvoice'])->name('generateInvoice');
        //     Route::get('/stats/data', [PembayaranController::class, 'getStats'])->name('getStats');
        //     Route::get('/export/csv', [PembayaranController::class, 'export'])->name('export');
        // });

        Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
            Route::get('/', [PembayaranController::class, 'index'])->name('index');
            Route::get('/stats', [PembayaranController::class, 'getStats'])->name('getStats');
            Route::get('/dashboard', [PembayaranController::class, 'dashboard'])->name('dashboard');
            Route::get('/export', [PembayaranController::class, 'export'])->name('export');

            Route::get('/{pembayaran}', [PembayaranController::class, 'show'])->name('show');
            Route::post('/{pembayaran}/update-status', [PembayaranController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/{pembayaran}/quick-approve', [PembayaranController::class, 'quickApprove'])->name('quickApprove');
            Route::post('/{pembayaran}/quick-reject', [PembayaranController::class, 'quickReject'])->name('quickReject');
            Route::post('/{pembayaran}/mark-processed', [PembayaranController::class, 'markAsProcessed'])->name('markAsProcessed');
            Route::get('/{pembayaran}/validate', [PembayaranController::class, 'validatePayment'])->name('validatePayment');

            Route::get('/{pembayaran}/download-bukti', [PembayaranController::class, 'downloadBukti'])->name('downloadBukti');
            Route::get('/{pembayaran}/invoice', [PembayaranController::class, 'generateInvoice'])->name('generateInvoice');

            Route::post('/bulk-action', [PembayaranController::class, 'bulkAction'])->name('bulkAction');

            Route::get('/user/{userId}/history', [PembayaranController::class, 'getPaymentHistory'])->name('userHistory');
        });

        // Member Routes
        Route::prefix('dashboard/members')->group(function () {
            Route::get('/', [MemberController::class, 'index'])->name('members.index');
            Route::get('/create', [MemberController::class, 'create'])->name('members.create');
            Route::post('/', [MemberController::class, 'store'])->name('members.store');
            Route::get('/search', [MemberController::class, 'search'])->name('members.search');
            Route::get('/{member}', [MemberController::class, 'show'])->name('members.show');
            Route::get('/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
            Route::put('/{member}', [MemberController::class, 'update'])->name('members.update');
            Route::delete('/{member}', [MemberController::class, 'destroy'])->name('members.destroy');
            Route::post('/{member}/verify', [MemberController::class, 'verify'])->name('members.verify');
            Route::post('/{member}/unverify', [MemberController::class, 'unverify'])->name('members.unverify');
            Route::post('/{member}/toggle-verification', [MemberController::class, 'toggleVerification'])->name('members.toggle-verification');
            Route::get('/{member}/export', [MemberController::class, 'export'])->name('members.export');
        });

        // Manajemen Naskah
        // Route::prefix('dashboard/naskah')->name('naskah.')->group(function () {
        //     Route::get('/', [NaskahController::class, 'index'])->name('index');
        //     Route::get('/{naskah}', [NaskahController::class, 'show'])->name('show');
        //     Route::post('/{naskah}/setujui', [NaskahController::class, 'setujui'])->name('setujui');
        //     Route::post('/{naskah}/tolak', [NaskahController::class, 'tolak'])->name('tolak');
        //     Route::patch('/{naskah}/status', [NaskahController::class, 'updateStatus'])->name('update-status');
        //     Route::get('/{naskah}/download', [NaskahController::class, 'download'])->name('download');
        //     Route::delete('/{naskah}', [NaskahController::class, 'destroy'])->name('destroy');
        //     Route::post('/bulk-action', [NaskahController::class, 'bulkAction'])->name('bulk-action');
        // });

        // Naskah Routes
        Route::prefix('dashboard/naskah')->group(function () {
            Route::get('/', [NaskahController::class, 'index'])->name('admin.naskah.index');
            Route::get('/create', [NaskahController::class, 'create'])->name('admin.naskah.create');
            Route::post('/', [NaskahController::class, 'store'])->name('admin.naskah.store');
            Route::get('/search', [NaskahController::class, 'search'])->name('admin.naskah.search');
            Route::get('/{naskah}', [NaskahController::class, 'show'])->name('admin.naskah.show');
            Route::get('/{naskah}/edit', [NaskahController::class, 'edit'])->name('admin.naskah.edit');
            Route::put('/{naskah}', [NaskahController::class, 'update'])->name('admin.naskah.update');
            Route::delete('/{naskah}', [NaskahController::class, 'destroy'])->name('admin.naskah.destroy');

            // Action routes - letakkan sebelum route {naskah} untuk menghindari konflik
            Route::post('/{naskah}/setujui', [NaskahController::class, 'setujui'])->name('admin.naskah.setujui');
            Route::post('/{naskah}/tolak', [NaskahController::class, 'tolak'])->name('admin.naskah.tolak');
            Route::post('/{naskah}/update-status', [NaskahController::class, 'updateStatus'])->name('admin.naskah.update-status');
            Route::get('/{naskah}/download', [NaskahController::class, 'download'])->name('admin.naskah.download');
            Route::get('/{naskah}/preview', [NaskahController::class, 'preview'])->name('admin.naskah.preview');
            Route::get('/{naskah}/status', [NaskahController::class, 'statusCheck'])->name('admin.naskah.status-check');
            Route::post('/bulk-action', [NaskahController::class, 'bulkAction'])->name('admin.naskah.bulk-action');
            Route::get('/export', [NaskahController::class, 'export'])->name('admin.naskah.export');

        });



        // // Admin Pembayaran Routes
        // Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

        //     // Pembayaran Management

        // });

        // // User Pembayaran Routes (jika diperlukan)
        // Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
        //     Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        //         Route::get('/history', [UserPembayaranController::class, 'history'])->name('history');
        //         Route::get('/{pembayaran}', [UserPembayaranController::class, 'show'])->name('show');
        //     });
        // });

        // Rekening Routes
        Route::prefix('dashboard/rekening')->group(function () {
            Route::get('/', [RekeningController::class, 'index'])->name('rekening.index');
            Route::get('/create', [RekeningController::class, 'create'])->name('rekening.create');
            Route::post('/', [RekeningController::class, 'store'])->name('rekening.store');
            Route::get('/{rekening}/edit', [RekeningController::class, 'edit'])->name('rekening.edit');
            Route::put('/{rekening}', [RekeningController::class, 'update'])->name('rekening.update');
            Route::delete('/{rekening}', [RekeningController::class, 'destroy'])->name('rekening.destroy');
        });

        // Design Routes
        Route::prefix('dashboard/designs')->group(function () {
            Route::get('/', [DesignController::class, 'index'])->name('admin.designs.index');
            Route::get('/create', [DesignController::class, 'create'])->name('admin.designs.create');
            Route::post('/', [DesignController::class, 'store'])->name('admin.designs.store');
            Route::get('/search', [DesignController::class, 'search'])->name('admin.designs.search');
            Route::get('/{design}', [DesignController::class, 'show'])->name('admin.designs.show');
            Route::get('/{design}/edit', [DesignController::class, 'edit'])->name('admin.designs.edit');
            Route::put('/{design}', [DesignController::class, 'update'])->name('admin.designs.update');
            Route::delete('/{design}', [DesignController::class, 'destroy'])->name('admin.designs.destroy');

            // Action routes - letakkan sebelum route {design} untuk menghindari konflik
            Route::post('/{design}/setujui', [DesignController::class, 'setujui'])->name('admin.designs.setujui');
            Route::post('/{design}/tolak', [DesignController::class, 'tolak'])->name('admin.designs.tolak');
            Route::post('/{design}/update-status', [DesignController::class, 'updateStatus'])->name('admin.designs.update-status');
            Route::post('/{design}/assign-reviewer', [DesignController::class, 'assignReviewer'])->name('admin.designs.assign-reviewer');
            Route::get('/{design}/preview', [DesignController::class, 'preview'])->name('admin.designs.preview');
            Route::get('/{design}/status', [DesignController::class, 'statusCheck'])->name('admin.designs.status-check');

            // Bulk actions
            Route::post('/bulk-action', [DesignController::class, 'bulkAction'])->name('admin.designs.bulk-action');
            Route::post('/bulk-assign-reviewer', [DesignController::class, 'bulkAssignReviewer'])->name('admin.designs.bulk-assign-reviewer');

            // Export & Print
            Route::get('/export', [DesignController::class, 'export'])->name('admin.designs.export');
            Route::get('/print', [DesignController::class, 'print'])->name('admin.designs.print');

            // API endpoints
            Route::get('/api/data', [DesignController::class, 'getData'])->name('admin.designs.api.data');
            Route::get('/api/chart-data', [DesignController::class, 'getChartData'])->name('admin.designs.api.chart-data');
            Route::get('/api/notifications-count', [DesignController::class, 'getNotificationsCount'])->name('admin.designs.api.notifications-count');
        });

    });
});
