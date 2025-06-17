@extends('admin.layouts.app')

@section('main')
<div class="container">
    <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200">Dashboard Admin</h1>

    <!-- Kartu Statistik Utama -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Buku -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Buku</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalBooks ?? 0 }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        <span class="text-green-600">{{ $booksThisMonth ?? 0 }}</span> bulan ini
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalUsers ?? 0 }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        <span class="text-green-600">{{ $activeUsers ?? 0 }}</span> aktif
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Pembayaran -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600 dark:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pembayaran</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalPayments ?? 0 }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        <span class="text-yellow-600">{{ $pendingPayments ?? 0 }}</span> menunggu verifikasi
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600 dark:text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        <span class="text-green-600">Rp {{ number_format($revenueThisMonth ?? 0, 0, ',', '.') }}</span> bulan ini
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Pembayaran -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Pembayaran Terverifikasi -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Terverifikasi</p>
                    <p class="text-xl font-bold text-green-600 dark:text-green-400">{{ $verifiedPayments ?? 0 }}</p>
                </div>
                <div class="p-2 rounded-full bg-green-100 dark:bg-green-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pembayaran Ditolak -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ditolak</p>
                    <p class="text-xl font-bold text-red-600 dark:text-red-400">{{ $rejectedPayments ?? 0 }}</p>
                </div>
                <div class="p-2 rounded-full bg-red-100 dark:bg-red-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Stok Menipis -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stok Menipis</p>
                    <p class="text-xl font-bold text-red-600 dark:text-red-400">{{ $lowStockBooks ?? 0 }}</p>
                </div>
                <div class="p-2 rounded-full bg-red-100 dark:bg-red-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Kategori -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</p>
                    <p class="text-xl font-bold text-teal-600 dark:text-teal-400">{{ $totalCategories ?? 0 }}</p>
                </div>
                <div class="p-2 rounded-full bg-teal-100 dark:bg-teal-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-600 dark:text-teal-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert untuk Pembayaran Pending -->
    @if(($pendingPayments ?? 0) > 0)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    <strong>Perhatian!</strong> Ada {{ $pendingPayments }} pembayaran yang menunggu verifikasi.
                    <a href="{{ route('pembayaran.index', ['status' => 'menunggu_verifikasi']) }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">
                        Lihat sekarang →
                    </a>
                </p>
            </div>
        </div>
    </div>
    @endif

       <!-- Grafik dan Tabel -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Pembayaran Terbaru -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pembayaran Terbaru</h3>
                <a href="{{ route('pembayaran.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lihat Semua →
                </a>
            </div>
            <div class="space-y-3">
                @forelse($recentPayments ?? [] as $payment)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            @if($payment->status === 'menunggu_verifikasi')
                                <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                            @elseif($payment->status === 'terverifikasi')
                                <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                            @else
                                <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $payment->invoice_number }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $payment->nama_pengirim }} • {{ $payment->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            Rp {{ number_format($payment->jumlah_transfer, 0, ',', '.') }}
                        </p>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            @if($payment->status === 'menunggu_verifikasi') bg-yellow-100 text-yellow-800
                            @elseif($payment->status === 'terverifikasi') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Belum ada pembayaran</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Buku Terpopuler -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Buku Terpopuler</h3>
                <a href="{{ route('admin.books.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lihat Semua →
                </a>
            </div>
            <div class="space-y-3">
                @forelse($popularBooks ?? [] as $book)
                <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex-shrink-0">
                        @if($book->cover_image)
                            <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->judul_buku }}" class="w-12 h-16 object-cover rounded">
                        @else
                            <div class="w-12 h-16 bg-gray-300 dark:bg-gray-600 rounded flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            {{ $book->judul_buku }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $book->penulis }} • {{ $book->orders_count ?? 0 }} pesanan
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            Rp {{ number_format($book->harga, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Stok: {{ $book->stok }}
                        </p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Belum ada data buku</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Grafik Pendapatan -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Grafik Pendapatan (7 Hari Terakhir)</h3>
            <div class="flex space-x-2">
                <button class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">7 Hari</button>
                <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-full hover:bg-blue-100 hover:text-blue-800">30 Hari</button>
            </div>
        </div>
        <div class="h-64 flex items-end justify-between space-x-2">
            @forelse($revenueChart ?? [] as $day => $revenue)
            <div class="flex flex-col items-center flex-1">
                <div class="w-full bg-blue-200 rounded-t" style="height: {{ $revenue > 0 ? ($revenue / max($revenueChart) * 200) : 5 }}px;">
                    <div class="w-full bg-blue-600 rounded-t h-full"></div>
                </div>
                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ $day }}</div>
                <div class="text-xs font-medium text-gray-900 dark:text-white">
                    {{ $revenue > 0 ? 'Rp ' . number_format($revenue / 1000, 0) . 'K' : '0' }}
                </div>
            </div>
            @empty
            <div class="w-full text-center py-8">
                <p class="text-gray-500 dark:text-gray-400">Data grafik tidak tersedia</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="{{ route('pembayaran.index', ['status' => 'menunggu_verifikasi']) }}" 
               class="flex flex-col items-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors">
                <svg class="w-8 h-8 text-yellow-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-medium text-yellow-800">Verifikasi Pembayaran</span>
                @if(($pendingPayments ?? 0) > 0)
                    <span class="mt-1 px-2 py-1 bg-yellow-200 text-yellow-800 text-xs rounded-full">{{ $pendingPayments }}</span>
                @endif
            </a>

            <a href="{{ route('admin.books.create') }}" 
               class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="text-sm font-medium text-blue-800">Tambah Buku</span>
            </a>

            <a href="{{ route('pembayaran.export') }}" 
               class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-sm font-medium text-green-800">Export Data</span>
            </a>

            <a href="{{ route('admin.books.index', ['stok' => 'low']) }}" 
               class="flex flex-col items-center p-4 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                <svg class="w-8 h-8 text-red-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <span class="text-sm font-medium text-red-800">Stok Menipis</span>
                @if(($lowStockBooks ?? 0) > 0)
                    <span class="mt-1 px-2 py-1 bg-red-200 text-red-800 text-xs rounded-full">{{ $lowStockBooks }}</span>
                @endif
            </a>
                    </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="mt-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aktivitas Terbaru</h3>
        <div class="flow-root">
            <ul class="-mb-8">
                @forelse($recentActivities ?? [] as $index => $activity)
                <li>
                    <div class="relative pb-8">
                        @if(!$loop->last)
                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                        @endif
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                    @if($activity['type'] === 'payment') bg-yellow-500
                                    @elseif($activity['type'] === 'order') bg-blue-500
                                    @elseif($activity['type'] === 'user') bg-green-500
                                    @else bg-gray-500 @endif">
                                    @if($activity['type'] === 'payment')
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                    @elseif($activity['type'] === 'order')
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    @elseif($activity['type'] === 'user')
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                </span>
                            </div>
                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {!! $activity['description'] !!}
                                    </p>
                                </div>
                                <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                    <time datetime="{{ $activity['time'] }}">{{ $activity['time_human'] }}</time>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @empty
                <li class="text-center py-4">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Belum ada aktivitas</p>
                </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection

