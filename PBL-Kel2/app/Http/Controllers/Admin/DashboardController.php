<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\KategoriBuku;
use App\Models\User;
use App\Models\Pembayaran;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Utama
        $totalBooks = Book::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalPayments = Pembayaran::count();
        $totalCategories = KategoriBuku::count();

        // Statistik Pembayaran
        $pendingPayments = Pembayaran::where('status', 'menunggu_verifikasi')->count();
        $verifiedPayments = Pembayaran::where('status', 'terverifikasi')->count();
        $rejectedPayments = Pembayaran::where('status', 'ditolak')->count();

        // Pendapatan
        $totalRevenue = Pembayaran::where('status', 'terverifikasi')->sum('jumlah_transfer');
        $revenueThisMonth = Pembayaran::where('status', 'terverifikasi')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('jumlah_transfer');

        // Data bulan ini
        $booksThisMonth = Book::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // User aktif - cek apakah model Pesanan ada
        $activeUsers = 0;
        if (class_exists('App\Models\Pesanan')) {
            try {
                $activeUsers = User::where('role', 'user')
                    ->whereHas('pesanans', function($query) {
                        $query->where('created_at', '>=', now()->subDays(30));
                    })
                    ->count();
            } catch (\Exception $e) {
                // Jika terjadi error, set ke 0
                $activeUsers = 0;
            }
        }

        // Stok menipis (kurang dari 10)
        $lowStockBooks = Book::where('stok', '<', 10)->count();

        // Pembayaran terbaru
        $recentPayments = Pembayaran::with(['pesanan.user', 'pesanan.buku'])
            ->latest()
            ->limit(5)
            ->get();

        // Buku terpopuler - cek apakah relasi ada
        $popularBooks = collect();
        try {
            $popularBooks = Book::withCount(['pesanans as orders_count'])
                ->orderBy('orders_count', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            // Jika error, ambil buku tanpa count
            $popularBooks = Book::latest()->limit(5)->get();
        }

        // Grafik pendapatan 7 hari terakhir
        $revenueChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $revenue = Pembayaran::where('status', 'terverifikasi')
                ->whereDate('created_at', $date)
                ->sum('jumlah_transfer');
            
            $revenueChart[$date->format('D')] = $revenue;
        }

        // Aktivitas terbaru
        $recentActivities = $this->getRecentActivities();

        return view('admin.dashboard', compact(
            'totalBooks',
            'totalUsers', 
            'totalPayments',
            'totalCategories',
            'pendingPayments',
            'verifiedPayments',
            'rejectedPayments',
            'totalRevenue',
            'revenueThisMonth',
            'booksThisMonth',
            'activeUsers',
            'lowStockBooks',
            'recentPayments',
            'popularBooks',
            'revenueChart',
            'recentActivities'
        ));
    }

    private function getRecentActivities()
    {
        $activities = [];

        try {
            // Pembayaran terbaru
            $recentPayments = Pembayaran::with(['pesanan.user'])
                ->latest()
                ->limit(3)
                ->get();

            foreach ($recentPayments as $payment) {
                $userName = $payment->pesanan->user->name ?? 'Unknown User';
                $activities[] = [
                    'type' => 'payment',
                    'description' => '<strong>' . $userName . '</strong> melakukan pembayaran <strong>' . $payment->invoice_number . '</strong>',
                    'time' => $payment->created_at->toISOString(),
                    'time_human' => $payment->created_at->diffForHumans()
                ];
            }
        } catch (\Exception $e) {
            // Skip jika error
        }

        try {
            // User baru
            $newUsers = User::where('role', 'user')
                ->latest()
                ->limit(2)
                ->get();

            foreach ($newUsers as $user) {
                $activities[] = [
                    'type' => 'user',
                    'description' => 'User baru <strong>' . $user->name . '</strong> mendaftar',
                    'time' => $user->created_at->toISOString(),
                    'time_human' => $user->created_at->diffForHumans()
                ];
            }
        } catch (\Exception $e) {
            // Skip jika error
        }

        // Cek apakah model Pesanan ada sebelum menggunakannya
        if (class_exists('App\Models\Pesanan')) {
            try {
                $pesananClass = new \App\Models\Pesanan();
                $recentOrders = $pesananClass::with(['user', 'buku'])
                    ->latest()
                    ->limit(2)
                    ->get();

                foreach ($recentOrders as $order) {
                    $userName = $order->user->name ?? 'Unknown User';
                    $bookTitle = $order->buku->judul_buku ?? 'Unknown Book';
                    $activities[] = [
                        'type' => 'order',
                        'description' => '<strong>' . $userName . '</strong> memesan buku <strong>' . $bookTitle . '</strong>',
                        'time' => $order->created_at->toISOString(),
                        'time_human' => $order->created_at->diffForHumans()
                    ];
                }
            } catch (\Exception $e) {
                // Skip jika error
            }
        }

        // Urutkan berdasarkan waktu terbaru
        if (!empty($activities)) {
            usort($activities, function($a, $b) {
                return strtotime($b['time']) - strtotime($a['time']);
            });
        }

        return array_slice($activities, 0, 8); // Ambil 8 aktivitas terbaru
    }

    public function getStats()
    {
        $stats = [
            'total' => Pembayaran::count(),
            'menunggu' => Pembayaran::where('status', 'menunggu_verifikasi')->count(),
            'terverifikasi' => Pembayaran::where('status', 'terverifikasi')->count(),
            'ditolak' => Pembayaran::where('status', 'ditolak')->count(),
            'revenue_today' => Pembayaran::where('status', 'terverifikasi')
                ->whereDate('created_at', today())
                ->sum('jumlah_transfer'),
            'revenue_month' => Pembayaran::where('status', 'terverifikasi')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('jumlah_transfer')
        ];

        return response()->json($stats);
    }
}
