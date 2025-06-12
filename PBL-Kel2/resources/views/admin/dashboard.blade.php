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

        <!-- Total Transaksi -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600 dark:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTransactions ?? 0 }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        <span class="text-green-600">{{ $transactionsToday ?? 0 }}</span> hari ini
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

    <!-- Statistik Tambahan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
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

        <!-- E-books -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">E-books</p>
                    <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400">{{ $totalEbooks ?? 0 }}</p>
                </div>
                <div class="p-2 rounded-full bg-indigo-100 dark:bg-indigo-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Promo Aktif -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Promo Aktif</p>
                    <p class="text-xl font-bold text-orange-600 dark:text-orange-400">{{ $activePromos ?? 0 }}</p>
                </div>
                <div class="p-2 rounded-full bg-orange-100 dark:bg-orange-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-600 dark:text-orange-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
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

    <!-- Grafik dan Tabel -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Grafik Penjualan Bulanan -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Penjualan 6 Bulan Terakhir</h3>
                <div class="flex space-x-2">
                    <button class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Fisik</button>
                    <button class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">E-book</button>
                </div>
            </div>
            <div class="h-64 bg-gray-50 dark:bg-gray-700 rounded flex items-center justify-center">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 012-2V9a2 2 0 00-2-2H9a2 2 0 00-2 2v10z" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Grafik Penjualan</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Chart.js akan diintegrasikan di sini</p>
                </div>
            </div>
        </div>

        <!-- Top Kategori -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Kategori Terpopuler</h3>
                <a href="{{ route('admin.books.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                @if(isset($topCategories) && $topCategories->count() > 0)
                    @foreach($topCategories as $category)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $category->nama }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $category->books_count ?? 0 }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">buku</span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Belum ada data kategori</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabel Data Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Buku Terbaru -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Buku Terbaru</h3>
                    <a href="{{ route('admin.books.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Lihat Semua</a>
                </div>
            </div>
            <div class="p-6">
                @if(isset($recentBooks) && $recentBooks->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentBooks as $book)
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                @if($book->cover)
                                    <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->judul_buku }}" class="w-12 h-16 object-cover rounded">
                                @else
                                    <div class="w-12 h-16 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $book->judul_buku }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $book->penulis }}</p>
                                <div class="flex items-center mt-1">
                                    <span class="text-xs font-medium text-green-600 dark:text-green-400">Rp {{ number_format($book->harga, 0, ',', '.') }}</span>
                                    <span class="mx-2 text-gray-300">•</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Stok: {{ $book->stok }}</span>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $book->stok > 0 ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                    {{ $book->stok > 0 ? 'Tersedia' : 'Habis' }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Belum ada buku</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- User Terbaru -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">User Terbaru</h3>
                    <a href="{{ route('akun.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Lihat Semua</a>
                </div>
            </div>
            <div class="p-6">
                @if(isset($recentUsers) && $recentUsers->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentUsers as $user)
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                @if($user->foto_profil)
                                    <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="{{ $user->name }}" class="w-10 h-10 object-cover rounded-full">
                                @else
                                    <div class="w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' : 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Belum ada user</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Aktivitas Terbaru</h3>
        </div>
        <div class="p-6">
            @if(isset($recentActivities) && $recentActivities->count() > 0)
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($recentActivities as $index => $activity)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-600" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full {{ $activity['color'] ?? 'bg-gray-400' }} flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                            {!! $activity['icon'] ?? '<svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>' !!}
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                                                                     <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {!! $activity['message'] ?? 'Aktivitas tidak diketahui' !!}
                                            </p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                            <time datetime="{{ $activity['time'] ?? now() }}">{{ $activity['time_human'] ?? 'Baru saja' }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="text-center py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Belum ada aktivitas</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.books.create') }}" class="flex flex-col items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 dark:text-blue-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Tambah Buku</span>
            </a>

            <a href="{{ route('account.create') }}" class="flex flex-col items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 dark:text-green-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                <span class="text-sm font-medium text-green-700 dark:text-green-300">Tambah User</span>
            </a>

            <a href="{{ route('promos.create') }}" class="flex flex-col items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600 dark:text-purple-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <span class="text-sm font-medium text-purple-700 dark:text-purple-300">Buat Promo</span>
            </a>

            <a href="{{ route('kategori-buku.create') }}" class="flex flex-col items-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/30 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-600 dark:text-orange-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span class="text-sm font-medium text-orange-700 dark:text-orange-300">Tambah Kategori</span>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto refresh data setiap 5 menit
setInterval(function() {
    // Refresh statistik via AJAX jika diperlukan
    console.log('Auto refresh dashboard data...');
}, 300000); // 5 menit

// Animasi counter untuk angka statistik
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.text-2xl.font-bold');
    
    counters.forEach(counter => {
        const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
        if (target && target > 0) {
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    counter.textContent = counter.textContent.replace(/[\d,]+/, target.toLocaleString('id-ID'));
                    clearInterval(timer);
                } else {
                    counter.textContent = counter.textContent.replace(/[\d,]+/, Math.floor(current).toLocaleString('id-ID'));
                }
            }, 20);
        }
    });
});

// Tooltip untuk quick actions
document.querySelectorAll('[data-tooltip]').forEach(element => {
    element.addEventListener('mouseenter', function() {
        // Implementasi tooltip jika diperlukan
    });
});
</script>
@endpush

@push('styles')
<style>
/* Custom animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.5s ease-out;
}

/* Hover effects untuk cards */
.hover-scale:hover {
    transform: scale(1.02);
    transition: transform 0.2s ease-in-out;
}

/* Custom scrollbar untuk aktivitas */
.activity-scroll::-webkit-scrollbar {
    width: 4px;
}

.activity-scroll::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 2px;
}

.activity-scroll::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 2px;
}

.activity-scroll::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Dark mode scrollbar */
.dark .activity-scroll::-webkit-scrollbar-track {
    background: #374151;
}

.dark .activity-scroll::-webkit-scrollbar-thumb {
    background: #6b7280;
}

.dark .activity-scroll::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Responsive grid adjustments */
@media (max-width: 640px) {
    .grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-4 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
    
    .grid-cols-2.md\\:grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* Loading skeleton animation */
@keyframes skeleton-loading {
    0% {
        background-position: -200px 0;
    }
    100% {
        background-position: calc(200px + 100%) 0;
    }
}

.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200px 100%;
    animation: skeleton-loading 1.5s infinite;
}

.dark .skeleton {
    background: linear-gradient(90deg, #374151 25%, #4b5563 50%, #374151 75%);
    background-size: 200px 100%;
}

/* Status indicators */
.status-indicator {
    position: relative;
}

.status-indicator::before {
    content: '';
    position: absolute;
    top: -2px;
    right: -2px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #10b981;
    border: 2px solid white;
}

.status-indicator.offline::before {
    background-color: #ef4444;
}

/* Chart placeholder styling */
.chart-placeholder {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    position: relative;
    overflow: hidden;
}

.chart-placeholder::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}
</style>
@endpush
@endsection


