@extends('admin.layouts.app')

@section('title', 'Kelola Pembayaran')

@section('main')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Kelola Pembayaran
                    </h1>
                    <p class="text-gray-600 mt-1">Kelola dan verifikasi pembayaran dari customer</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="refreshStats()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh
                    </button>
                    <a href="{{ route('pembayaran.export', request()->query()) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export
                    </a>

                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                        <div class="ml-auto pl-3">
                            <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-green-400 hover:text-green-600">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                        <div class="ml-auto pl-3">
                            <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-red-400 hover:text-red-600">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

                        <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Pembayaran -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pembayaran</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white" id="total-count">{{ number_format($stats['total']) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Menunggu Verifikasi -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Menunggu Verifikasi</p>
                            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400" id="pending-count">{{ number_format($stats['menunggu']) }}</p>
                        </div>


                        
                    </div>
                </div>

                <!-- Terverifikasi -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Terverifikasi</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400" id="verified-count">{{ number_format($stats['terverifikasi']) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Ditolak -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Ditolak</p>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400" id="rejected-count">{{ number_format($stats['ditolak']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

                       <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filter & Pencarian</h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('pembayaran.index') }}" id="filterForm">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">Semua Status</option>
                                    <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>
                                        Menunggu Verifikasi
                                    </option>
                                    <option value="terverifikasi" {{ request('status') == 'terverifikasi' ? 'selected' : '' }}>
                                        Terverifikasi
                                    </option>
                                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>
                                        Ditolak
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pencarian</label>
                                <input type="text" name="search" id="search" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                    placeholder="Cari berdasarkan invoice, nama, email, atau nomor pesanan..."
                                    value="{{ request('search') }}">
                            </div>
                            <div class="flex items-end space-x-3">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Cari
                                </button>
                                <a href="{{ route('pembayaran.index') }}" class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Bulk Actions -->
            <div id="bulkActions" class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 hidden">
                <div class="p-6">
                    <form id="bulkActionForm">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                            <div>
                                <label for="bulkAction" class="block text-sm font-medium text-gray-700 mb-2">Aksi Massal</label>
                                <select name="action" id="bulkAction" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="">Pilih Aksi</option>
                                    <option value="terverifikasi">Verifikasi Terpilih</option>
                                    <option value="ditolak">Tolak Terpilih</option>
                                </select>
                            </div>
                            <div>
                                <label for="bulkNote" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                                <input type="text" name="catatan_admin" id="bulkNote" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Catatan untuk aksi massal...">
                            </div>
                            <div class="flex space-x-3">
                                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Proses
                                </button>
                                <button type="button" onclick="clearSelection()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Batal
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

                      <!-- Data Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Pembayaran</h3>
                    <div>
                        <span id="selectedCount" class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-medium hidden">0 dipilih</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    @if($pembayarans->count() > 0)
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left">
                                        <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 bg-gray-100 dark:bg-gray-600 border-gray-300 dark:border-gray-500 rounded focus:ring-blue-500 dark:focus:ring-blue-400">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Invoice</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pesanan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($pembayarans as $pembayaran)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                                        <td class="px-6 py-4">
                                            <input type="checkbox" class="select-item w-4 h-4 text-blue-600 bg-gray-100 dark:bg-gray-600 border-gray-300 dark:border-gray-500 rounded focus:ring-blue-500 dark:focus:ring-blue-400" value="{{ $pembayaran->id }}">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $pembayaran->invoice_number }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $pembayaran->metode_pembayaran }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $pembayaran->pesanan->order_number }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($pembayaran->pesanan->buku->judul_buku, 30) }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $pembayaran->pesanan->user->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $pembayaran->pesanan->user->email }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">Rp {{ number_format($pembayaran->jumlah_transfer, 0, ',', '.') }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $pembayaran->bank_pengirim }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $pembayaran->nama_pengirim }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                <div><strong>Upload:</strong></div>
                                                <div>{{ $pembayaran->tanggal_pembayaran->format('d/m/Y H:i') }}</div>
                                            </div>
                                            @if($pembayaran->tanggal_verifikasi)
                                                <div class="text-sm text-green-600 dark:text-green-400 mt-1">
                                                    <div><strong>Verifikasi:</strong></div>
                                                    <div>{{ $pembayaran->tanggal_verifikasi->format('d/m/Y H:i') }}</div>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div>
                                                @if($pembayaran->status === 'menunggu_verifikasi')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Menunggu
                                                    </span>
                                                @elseif($pembayaran->status === 'terverifikasi')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Terverifikasi
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Ditolak
                                                    </span>
                                                @endif
                                            </div>
                                            @if($pembayaran->verifiedBy)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    oleh {{ $pembayaran->verifiedBy->name }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col space-y-2">
                                                <a href="{{ route('pembayaran.show', $pembayaran) }}" 
                                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition duration-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    Detail
                                                </a>

                                                @if($pembayaran->status === 'menunggu_verifikasi')
                                                    <button onclick="quickApprove({{ $pembayaran->id }})" 
                                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 transition duration-200">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Setujui
                                                    </button>
                                                    <button onclick="quickReject({{ $pembayaran->id }} )" 
                                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 transition duration-200">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Tolak
                                                    </button>
                                                @endif

                                                @if($pembayaran->bukti_pembayaran)
                                                    <a href="{{ route('pembayaran.downloadBukti', $pembayaran) }}" 
                                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 dark:bg-gray-500 dark:hover:bg-gray-600 transition duration-200">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        Bukti
                                                    </a>
                                                @endif

                                                <a href="{{ route('pembayaran.generateInvoice', $pembayaran) }}" target="_blank"
                                                    class="inline-flex items-center px-3 py-1 border border-gray-300 dark:border-gray-600 text-xs font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition duration-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    Invoice
                                                </a>
                                                                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada data pembayaran</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Belum ada pembayaran yang masuk atau sesuai dengan filter yang dipilih.</p>
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                @if($pembayarans->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $pembayarans->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>



    <!-- Quick Reject Modal -->
    <div id="quickRejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tolak Pembayaran
                    </h3>
                    <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="quickRejectForm">
                    @csrf
                    <div class="mb-4">
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Perhatian!</strong> Anda akan menolak pembayaran ini. Pastikan alasan penolakan jelas dan dapat dipahami oleh customer.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <label for="rejectReason" class="block text-sm font-medium text-gray-700 mb-2">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="catatan_admin" id="rejectReason" rows="4" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Contoh: Bukti transfer tidak jelas, nominal tidak sesuai, dll..."></textarea>
                        <p class="mt-1 text-sm text-gray-500">Alasan ini akan dikirimkan kepada customer.</p>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeRejectModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition duration-200">
                            Batal
                        </button>
                        <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Tolak Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Confirmation Modal -->
    <div id="bulkConfirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        Konfirmasi Aksi Massal
                    </h3>
                    <button onclick="closeBulkModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="mb-4">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Anda akan memproses <strong id="bulkCount">0</strong> pembayaran dengan aksi <strong id="bulkActionText"></strong>.
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-gray-600">Apakah Anda yakin ingin melanjutkan?</p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeBulkModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition duration-200">
                        Batal
                    </button>
                    <button type="button" id="confirmBulkAction"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Ya, Proses
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let selectedItems = [];
        let currentRejectId = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('[class*="border-l-4"]');
                alerts.forEach(alert => {
                    if (alert.querySelector('button')) {
                        alert.style.display = 'none';
                    }
                });
            }, 5000);
        });

        // Select All functionality
        document.getElementById('selectAll').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('.select-item');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelection();
        });

        // Individual checkbox functionality
        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('select-item')) {
                updateSelection();
            }
        });

        function updateSelection() {
            const checkboxes = document.querySelectorAll('.select-item:checked');
            selectedItems = Array.from(checkboxes).map(cb => cb.value);

            const count = selectedItems.length;
            const countBadge = document.getElementById('selectedCount');
            const bulkActions = document.getElementById('bulkActions');

            if (count > 0) {
                countBadge.textContent = count + ' dipilih';
                countBadge.classList.remove('hidden');
                bulkActions.classList.remove('hidden');
            } else {
                countBadge.classList.add('hidden');
                bulkActions.classList.add('hidden');
            }

            // Update select all checkbox
            const allCheckboxes = document.querySelectorAll('.select-item');
            const selectAllCheckbox = document.getElementById('selectAll');
            selectAllCheckbox.checked = allCheckboxes.length > 0 && selectedItems.length === allCheckboxes.length;
            selectAllCheckbox.indeterminate = selectedItems.length > 0 && selectedItems.length < allCheckboxes.length;
        }

        function clearSelection() {
            document.querySelectorAll('.select-item').forEach(cb => cb.checked = false);
            document.getElementById('selectAll').checked = false;
            updateSelection();
        }

        // Bulk Action Form
        document.getElementById('bulkActionForm').addEventListener('submit', function (e) {
            e.preventDefault();

            if (selectedItems.length === 0) {
                showAlert('warning', 'Pilih minimal satu pembayaran');
                return;
            }

            const action = document.getElementById('bulkAction').value;
            if (!action) {
                showAlert('warning', 'Pilih aksi yang akan dilakukan');
                return;
            }

            // Show confirmation modal
            document.getElementById('bulkCount').textContent = selectedItems.length;
            document.getElementById('bulkActionText').textContent = action === 'terverifikasi' ? 'VERIFIKASI' : 'TOLAK';
            document.getElementById('bulkConfirmModal').classList.remove('hidden');
        });

        // Confirm bulk action
        document.getElementById('confirmBulkAction').addEventListener('click', function() {
            const action = document.getElementById('bulkAction').value;
            const catatan = document.getElementById('bulkNote').value;
            
            // Show loading state
            this.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            `;
            this.disabled = true;

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('action', action);
            formData.append('catatan_admin', catatan);
            selectedItems.forEach(id => formData.append('pembayaran_ids[]', id));

            fetch('{{ route("pembayaran.bulkAction") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                closeBulkModal();
                
                if (data.success) {
                    showAlert('success', data.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Terjadi kesalahan sistem');
                closeBulkModal();
            });
        });

        function closeBulkModal() {
            document.getElementById('bulkConfirmModal').classList.add('hidden');
            const confirmBtn = document.getElementById('confirmBulkAction');
            confirmBtn.innerHTML = `
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Ya, Proses
            `;
            confirmBtn.disabled = false;
        }

        // Quick Approve
window.quickApprove = function(id) {
    console.log('Quick approve function called with ID:', id);
    
    if (confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?')) {
        const btn = event.target.closest('button');
        const originalContent = btn.innerHTML;
        
        btn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
        `;
        btn.disabled = true;

        // Gunakan route yang benar sesuai dengan route list
        const url = `{{ url('admin/pembayaran') }}/${id}/update-status`;
        console.log('Calling URL:', url);

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                status: 'terverifikasi',
                catatan_admin: 'Disetujui melalui quick approve'
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Server tidak mengembalikan JSON response');
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert('Pembayaran berhasil disetujui');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Terjadi kesalahan'));
                btn.innerHTML = originalContent;
                btn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Terjadi kesalahan: ' + error.message);
            btn.innerHTML = originalContent;
            btn.disabled = false;
        });
    }
};

// Quick Reject
window.quickReject = function(id) {
    console.log('Quick reject function called with ID:', id);
    
    const reason = prompt('Masukkan alasan penolakan:');
    if (reason && reason.trim()) {
        const btn = event.target.closest('button');
        const originalContent = btn.innerHTML;
        
        btn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
        `;
        btn.disabled = true;

        const url = `{{ url('admin/pembayaran') }}/${id}/update-status`;
        console.log('Calling URL:', url);

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                status: 'ditolak',
                catatan_admin: reason.trim()
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Server tidak mengembalikan JSON response');
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert('Pembayaran berhasil ditolak');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Terjadi kesalahan'));
                btn.innerHTML = originalContent;
                btn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Terjadi kesalahan: ' + error.message);
            btn.innerHTML = originalContent;
            btn.disabled = false;
        });
    }
};

        // Refresh Stats
        function refreshStats() {
            const btn = event.target.closest('button');
            const originalContent = btn.innerHTML;
            
            btn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memuat...
            `;
            btn.disabled = true;

            fetch('{{ route("pembayaran.getStats") }}')
                .then(response => response.json())
                .then(data => {
                    // Update stats cards
                    document.getElementById('total-count').textContent = data.total.toLocaleString();
                    document.getElementById('pending-count').textContent = data.menunggu.toLocaleString();
                    document.getElementById('verified-count').textContent = data.terverifikasi.toLocaleString();
                    document.getElementById('rejected-count').textContent = data.ditolak.toLocaleString();

                    showAlert('success', 'Statistik berhasil diperbarui');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'Gagal memperbarui statistik');
                })
                .finally(() => {
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                });
        }

        // Show Alert
        function showAlert(type, message) {
            // Remove existing alerts
            const existingAlerts = document.querySelectorAll('[class*="border-l-4"]');
            existingAlerts.forEach(alert => alert.remove());

            const colors = {
                'success': { bg: 'bg-green-50', border: 'border-green-400', text: 'text-green-700', icon: 'text-green-400' },
                'error': { bg: 'bg-red-50', border: 'border-red-400', text: 'text-red-700', icon: 'text-red-400' },
                'warning': { bg: 'bg-yellow-50', border: 'border-yellow-400', text: 'text-yellow-700', icon: 'text-yellow-400' },
                'info': { bg: 'bg-blue-50', border: 'border-blue-400', text: 'text-blue-700', icon: 'text-blue-400' }
            };

            const color = colors[type] || colors.info;

            const icons = {
                'success': `<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>`,
                'error': `<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>`,
                'warning': `<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1  0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>`,
                'info': `<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>`
            };

            const alertHtml = `
                <div class="${color.bg} border-l-4 ${color.border} p-4 mb-6 alert-auto-hide">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 ${color.icon}" fill="currentColor" viewBox="0 0 20 20">
                                ${icons[type] || icons.info}
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm ${color.text}">${message}</p>
                        </div>
                        <div class="ml-auto pl-3">
                            <button onclick="this.parentElement.parentElement.parentElement.remove()" class="${color.icon} hover:opacity-75">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            const container = document.querySelector('.max-w-7xl');
            const header = container.querySelector('.flex.justify-between.items-center');
            header.insertAdjacentHTML('afterend', alertHtml);

            // Auto hide after 5 seconds
            setTimeout(() => {
                const alert = container.querySelector('.alert-auto-hide');
                if (alert) {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    alert.style.transition = 'all 0.3s ease-out';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        }

        // Auto refresh for pending payments
        let refreshInterval;
        function startAutoRefresh() {
            if (document.querySelector('.bg-yellow-100')) {
                refreshInterval = setInterval(() => {
                    if (!document.hidden) {
                        fetch(window.location.href, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.text())
                        .then(html => {
                            // Parse the response to check if there are changes
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newPendingCount = doc.getElementById('pending-count')?.textContent;
                            const currentPendingCount = document.getElementById('pending-count')?.textContent;
                            
                            if (newPendingCount && currentPendingCount && newPendingCount !== currentPendingCount) {
                                showAlert('info', 'Data pembayaran telah diperbarui. Halaman akan dimuat ulang...');
                                setTimeout(() => location.reload(), 2000);
                            }
                        })
                        .catch(error => {
                            console.log('Auto refresh error:', error);
                        });
                    }
                }, 30000); // Check every 30 seconds
            }
        }

        // Start auto refresh on page load
        startAutoRefresh();

        // Clear interval when page is hidden or user leaves
        document.addEventListener('visibilitychange', function() {
            if (document.hidden && refreshInterval) {
                clearInterval(refreshInterval);
            } else if (!document.hidden) {
                startAutoRefresh();
            }
        });

        window.addEventListener('beforeunload', function() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
        });

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            const rejectModal = document.getElementById('quickRejectModal');
            const bulkModal = document.getElementById('bulkConfirmModal');
            
            if (e.target === rejectModal) {
                closeRejectModal();
            }
            if (e.target === bulkModal) {
                closeBulkModal();
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Escape key to close modals
            if (e.key === 'Escape') {
                closeRejectModal();
                closeBulkModal();
            }
            
            // Ctrl/Cmd + A to select all (when not in input)
            if ((e.ctrlKey || e.metaKey) && e.key === 'a' && !['INPUT', 'TEXTAREA'].includes(e.target.tagName)) {
                e.preventDefault();
                document.getElementById('selectAll').checked = true;
                document.getElementById('selectAll').dispatchEvent(new Event('change'));
            }
            
            // Ctrl/Cmd + R to refresh stats
            if ((e.ctrlKey || e.metaKey) && e.key === 'r' && !['INPUT', 'TEXTAREA'].includes(e.target.tagName)) {
                e.preventDefault();
                refreshStats();
            }
        });

        // Form auto-submit on filter change
        document.getElementById('status').addEventListener('change', function() {
            if (this.value !== '') {
                document.getElementById('filterForm').submit();
            }
        });

        // Search with debounce
        let searchTimeout;
        document.getElementById('search').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    document.getElementById('filterForm').submit();
                }
            }, 500);
        });

        // Smooth scroll to top after actions
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Add loading states to all buttons
        document.querySelectorAll('button[type="submit"], a[href*="download"]').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!this.disabled && this.type === 'submit') {
                    const originalContent = this.innerHTML;
                    setTimeout(() => {
                        if (this.innerHTML === originalContent) {
                            this.innerHTML = originalContent.replace(/^(<svg[^>]*>.*?<\/svg>\s*)?/, `
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            `);
                        }
                    }, 100);
                }
            });
        });

        // Initialize tooltips (if you're using a tooltip library)
        // You can add tooltip initialization here if needed

        console.log('Admin Pembayaran Index loaded successfully');
    </script>
@endpush




