<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\KategoriBuku;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        // Statistik utama
        $totalBooks = Book::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalTransactions = 0; // Akan diisi ketika ada model transaksi
        $totalRevenue = 0; // Akan diisi ketika ada model transaksi

        // Statistik bulanan
        $booksThisMonth = Book::whereMonth('created_at', Carbon::now()->month)->count();
        $activeUsers = User::where('role', 'user')->count(); // Semua user dianggap aktif
        $transactionsToday = 0; // Akan diisi ketika ada model transaksi
        $revenueThisMonth = 0; // Akan diisi ketika ada model transaksi

        // Statistik tambahan
        $lowStockBooks = Book::where('stok', '<=', 5)->count();
        $totalEbooks = Book::whereNotNull('file_buku')->count();
        $activePromos = Promo::where('status', 'Aktif')
            ->where('tanggal_mulai', '<=', Carbon::now())
            ->where('tanggal_selesai', '>=', Carbon::now())
            ->count();
        $totalCategories = KategoriBuku::where('status', true)->count();

        // Data untuk tabel
        $topCategories = KategoriBuku::withCount('books')
            ->where('status', true)
            ->orderBy('books_count', 'desc')
            ->limit(5)
            ->get();

        $recentBooks = Book::with('kategori')
            ->latest()
            ->limit(5)
            ->get();

        $recentUsers = User::where('role', 'user')
            ->latest()
            ->limit(5)
            ->get();

        // Aktivitas terbaru (contoh data)
        $recentActivities = collect([
            [
                'message' => '<strong>Buku baru</strong> ditambahkan ke sistem',
                'time' => Carbon::now()->subMinutes(5),
                'time_human' => '5 menit yang lalu',
                'color' => 'bg-blue-500',
                'icon' => '<svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>'
            ],
            [
                'message' => '<strong>User baru</strong> mendaftar',
                'time' => Carbon::now()->subMinutes(15),
                'time_human' => '15 menit yang lalu',
                'color' => 'bg-green-500',
                'icon' => '<svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/></svg>'
            ],
            [
                'message' => '<strong>Promo</strong> berhasil diaktifkan',
                'time' => Carbon::now()->subHour(1),
                'time_human' => '1 jam yang lalu',
                'color' => 'bg-purple-500',
                'icon' => '<svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>'
            ],
            [
                'message' => '<strong>Kategori baru</strong> ditambahkan',
                'time' => Carbon::now()->subHours(2),
                'time_human' => '2 jam yang lalu',
                'color' => 'bg-orange-500',
                'icon' => '<svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>'
            ],
            [
                'message' => '<strong>Stok buku</strong> diperbarui',
                'time' => Carbon::now()->subHours(3),
                'time_human' => '3 jam yang lalu',
                'color' => 'bg-yellow-500',
                'icon' => '<svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg>'
            ]
        ]);

        return view('admin.dashboard', compact(
            'totalBooks',
            'totalUsers', 
            'totalTransactions',
            'totalRevenue',
            'booksThisMonth',
            'activeUsers',
            'transactionsToday',
            'revenueThisMonth',
            'lowStockBooks',
            'totalEbooks',
            'activePromos',
            'totalCategories',
            'topCategories',
            'recentBooks',
            'recentUsers',
            'recentActivities'
        ));
    }

    // ... method lainnya tetap sama
}
