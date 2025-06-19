<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\KategoriBuku;
use App\Models\User;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', array_merge(
            $this->getSummaryStats(),
            $this->getPaymentStats(),
            $this->getRevenueStats(),
            $this->getBookStats(),
            $this->getRevenueChart(),
            ['recentActivities' => $this->getRecentActivities()]
        ));
    }

    private function getSummaryStats()
    {
        return [
            'totalBooks' => Book::count(),
            'totalUsers' => User::where('role', 'user')->count(),
            'totalPayments' => Pembayaran::count(),
            'totalCategories' => KategoriBuku::count(),
        ];
    }

    private function getPaymentStats()
    {
        return [
            'pendingPayments' => Pembayaran::where('status', 'menunggu_verifikasi')->count(),
            'verifiedPayments' => Pembayaran::where('status', 'terverifikasi')->count(),
            'rejectedPayments' => Pembayaran::where('status', 'ditolak')->count(),
        ];
    }

    private function getRevenueStats()
    {
        return [
            'totalRevenue' => $this->verifiedPayments()->sum('jumlah_transfer'),
            'revenueThisMonth' => $this->thisMonth($this->verifiedPayments())->sum('jumlah_transfer'),
        ];
    }

    private function getBookStats()
    {
        $activeUsers = 0;
        if (class_exists('App\Models\Pesanan')) {
            try {
                $activeUsers = User::where('role', 'user')
                    ->whereHas('pesanans', function ($query) {
                        $query->where('created_at', '>=', now()->subDays(30));
                    })
                    ->count();
            } catch (\Exception $e) {
                $activeUsers = 0;
            }
        }

        $popularBooks = collect();
        try {
            $popularBooks = Book::withCount(['pesanans as orders_count'])
                ->orderBy('orders_count', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $popularBooks = Book::latest()->limit(5)->get();
        }

        return [
            'booksThisMonth' => $this->thisMonth(Book::query())->count(),
            'activeUsers' => $activeUsers,
            'lowStockBooks' => Book::where('stok', '<', 10)->count(),
            'popularBooks' => $popularBooks,
            'recentPayments' => Pembayaran::with(['pesanan.user', 'pesanan.buku'])->latest()->limit(5)->get(),
        ];
    }

    private function getRevenueChart()
    {
        $revenueChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $revenueChart[$date->format('D')] = $this->verifiedPayments()
                ->whereDate('created_at', $date)
                ->sum('jumlah_transfer');
        }

        return ['revenueChart' => $revenueChart];
    }

    private function getRecentActivities()
    {
        $activities = [];

        // Pembayaran terbaru
        try {
            $recentPayments = Pembayaran::with(['pesanan.user'])->latest()->limit(3)->get();
            foreach ($recentPayments as $payment) {
                $userName = $payment->pesanan->user->name ?? 'Unknown User';
                $activities[] = [
                    'type' => 'payment',
                    'description' => "<strong>{$userName}</strong> melakukan pembayaran <strong>{$payment->invoice_number}</strong>",
                    'time' => $payment->created_at->toISOString(),
                    'time_human' => $payment->created_at->diffForHumans(),
                ];
            }
        } catch (\Exception $e) {
        }

        // User baru
        try {
            $newUsers = User::where('role', 'user')->latest()->limit(2)->get();
            foreach ($newUsers as $user) {
                $activities[] = [
                    'type' => 'user',
                    'description' => "User baru <strong>{$user->name}</strong> mendaftar",
                    'time' => $user->created_at->toISOString(),
                    'time_human' => $user->created_at->diffForHumans(),
                ];
            }
        } catch (\Exception $e) {
        }

        // Pesanan terbaru
        if (class_exists('App\Models\Pesanan')) {
            try {
                $recentOrders = \App\Models\Pesanan::with(['user', 'buku'])->latest()->limit(2)->get();
                foreach ($recentOrders as $order) {
                    $userName = $order->user->name ?? 'Unknown User';
                    $bookTitle = $order->buku->judul_buku ?? 'Unknown Book';
                    $activities[] = [
                        'type' => 'order',
                        'description' => "<strong>{$userName}</strong> memesan buku <strong>{$bookTitle}</strong>",
                        'time' => $order->created_at->toISOString(),
                        'time_human' => $order->created_at->diffForHumans(),
                    ];
                }
            } catch (\Exception $e) {
            }
        }

        usort($activities, fn($a, $b) => strtotime($b['time']) - strtotime($a['time']));
        return array_slice($activities, 0, 8);
    }

    private function verifiedPayments()
    {
        return Pembayaran::where('status', 'terverifikasi');
    }

    private function thisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    public function getStats()
    {
        return response()->json([
            'total' => Pembayaran::count(),
            'menunggu' => Pembayaran::where('status', 'menunggu_verifikasi')->count(),
            'terverifikasi' => Pembayaran::where('status', 'terverifikasi')->count(),
            'ditolak' => Pembayaran::where('status', 'ditolak')->count(),
            'revenue_today' => $this->verifiedPayments()->whereDate('created_at', today())->sum('jumlah_transfer'),
            'revenue_month' => $this->thisMonth($this->verifiedPayments())->sum('jumlah_transfer'),
        ]);
    }
}
