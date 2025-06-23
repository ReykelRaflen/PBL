@extends('admin.layouts.app')

@section('title', 'Manajemen Naskah - Admin')

@section('main')
    <div class="container mx-auto px-4 py-6">
        <!-- Alert Messages -->
        @if(session('success'))
            <div
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
                <button type="button" class="text-green-700 hover:text-green-900" onclick="this.parentElement.remove()">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    {{ session('error') }}
                </div>
                <button type="button" class="text-red-700 hover:text-red-900" onclick="this.parentElement.remove()">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
            <div class="bg-blue-500 text-white rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold">{{ $stats['total'] }}</h3>
                        <p class="text-blue-100">Total Naskah</p>
                    </div>
                    <div class="text-blue-200">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-500 text-white rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold">{{ $stats['pending'] }}</h3>
                        <p class="text-yellow-100">Menunggu</p>
                    </div>
                    <div class="text-yellow-200">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-cyan-500 text-white rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold">{{ $stats['sedang_direview'] }}</h3>
                        <p class="text-cyan-100">Direview</p>
                    </div>
                    <div class="text-cyan-200">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-green-500 text-white rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold">{{ $stats['disetujui'] }}</h3>
                        <p class="text-green-100">Disetujui</p>
                    </div>
                    <div class="text-green-200">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-red-500 text-white rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold">{{ $stats['ditolak'] }}</h3>
                        <p class="text-red-100">Ditolak</p>
                    </div>
                    <div class="text-red-200">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 text-white rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold">{{ $stats['segera_berakhir'] }}</h3>
                        <p class="text-gray-300">Segera Berakhir</p>
                    </div>
                    <div class="text-gray-400">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 sm:mb-0">Daftar Naskah</h3>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <a href="{{ route('admin.naskah.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Tambah Naskah
                        </a>
                        <button type="button"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out"
                            onclick="openBulkActionModal()">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Aksi Massal
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Filter Form -->
                <form method="GET" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu
                                </option>
                                <option value="sedang_direview" {{ request('status') == 'sedang_direview' ? 'selected' : '' }}>Sedang Direview</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui
                                </option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cari Naskah</label>
                            <div class="flex">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari naskah..."
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <button type="submit"
                                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-r-md transition duration-150 ease-in-out">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="selectAll"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Judul Naskah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pengirim</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Batas Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Upload</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                                                       @forelse($naskah as $item)
                                <tr class="hover:bg-gray-50 {{ $item->isSegera() ? 'bg-yellow-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="naskah_ids[]" value="{{ $item->id }}"
                                            class="naskah-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $item->id }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs">
                                            <div class="text-sm font-medium text-gray-900 truncate">
                                                {{ Str::limit($item->judul, 50) }}</div>
                                            <div class="text-sm text-gray-500 flex items-center mt-1">
                                                <i class="{{ $item->file_type_icon }} mr-2"></i>
                                                {{ $item->nama_file_asli }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div
                                                    class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-medium">
                                                    {{ strtoupper(substr($item->pengirim->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->pengirim->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $item->pengirim->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <select
                                            class="status-select text-sm rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                            data-naskah-id="{{ $item->id }}">
                                            <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Menunggu
                                            </option>
                                            <option value="sedang_direview" {{ $item->status == 'sedang_direview' ? 'selected' : '' }}>Sedang Direview</option>
                                            <option value="disetujui" {{ $item->status == 'disetujui' ? 'selected' : '' }}>
                                                Disetujui</option>
                                            <option value="ditolak" {{ $item->status == 'ditolak' ? 'selected' : '' }}>Ditolak
                                            </option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div
                                            class="text-sm {{ $item->isSegera() ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                                            {{ $item->batas_waktu->format('d M Y') }}
                                        </div>
                                        @if($item->isSegera())
                                            <div class="text-xs text-red-500 flex items-center mt-1">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Segera berakhir!
                                            </div>
                                        @endif
                                        @if($item->isTerlambat())
                                            <div class="text-xs text-red-600 flex items-center mt-1">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Terlambat!
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->created_at->format('d M Y H:i') }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-1">
                                            <a href="{{ route('admin.naskah.show', $item) }}"
                                                class="text-blue-600 hover:text-blue-900 p-1 rounded" title="Lihat Detail">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                    <path fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.naskah.download', $item) }}"
                                                class="text-gray-600 hover:text-gray-900 p-1 rounded" title="Download">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.naskah.edit', $item) }}"
                                                class="text-yellow-600 hover:text-yellow-900 p-1 rounded" title="Edit">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <button type="button"
                                                class="setujui-btn text-green-600 hover:text-green-900 p-1 rounded"
                                                data-naskah-id="{{ $item->id }}" title="Setujui">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                            <button type="button" class="tolak-btn text-red-600 hover:text-red-900 p-1 rounded"
                                                data-naskah-id="{{ $item->id }}" title="Tolak">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                            <button type="button" class="hapus-btn text-red-500 hover:text-red-700 p-1 rounded"
                                                data-naskah-id="{{ $item->id }}" title="Hapus">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8l-4 4m0 0l-4-4m4 4V3">
                                                </path>
                                            </svg>
                                            <p class="text-lg font-medium">Belum ada naskah yang ditemukan</p>
                                            <p class="text-sm">Coba ubah filter pencarian atau tambah naskah baru</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($naskah->hasPages())
                    <div class="mt-6 flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if($naskah->onFirstPage())
                                <span
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-500 bg-white cursor-default">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $naskah->previousPageUrl() }}"
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                            @endif

                            @if($naskah->hasMorePages())
                                <a href="{{ $naskah->nextPageUrl() }}"
                                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Next
                                </a>
                            @else
                                <span
                                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-500 bg-white cursor-default">
                                    Next
                                </span>
                            @endif
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{ $naskah->firstItem() }}</span>
                                    to
                                    <span class="font-medium">{{ $naskah->lastItem() }}</span>
                                    of
                                    <span class="font-medium">{{ $naskah->total() }}</span>
                                    results
                                </p>
                            </div>
                            <div>
                                {{ $naskah->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

        <!-- Modal Setujui -->
    <div id="setujuiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Setujui Naskah</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal('setujuiModal')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
                <form id="setujuiForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-4">Apakah Anda yakin ingin menyetujui naskah ini?</p>
                        <div>
                            <label for="setujui_catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan
                                (Opsional)</label>
                            <textarea id="setujui_catatan" name="catatan" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Tambahkan catatan untuk penulis..."></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium rounded-md transition duration-150 ease-in-out"
                            onclick="closeModal('setujuiModal')">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tolak -->
    <div id="tolakModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Tolak Naskah</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal('tolakModal')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
                <form id="tolakForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-4">Apakah Anda yakin ingin menolak naskah ini?</p>
                        <div>
                            <label for="tolak_catatan" class="block text-sm font-medium text-gray-700 mb-2">
                                Alasan Penolakan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="tolak_catatan" name="catatan" rows="3" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                placeholder="Jelaskan alasan penolakan..."></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium rounded-md transition duration-150 ease-in-out"
                            onclick="closeModal('tolakModal')">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div id="hapusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Hapus Naskah</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal('hapusModal')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
                <form id="hapusForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-4">Apakah Anda yakin ingin menghapus naskah ini?</p>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <div class="text-sm text-yellow-700">
                                    <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium rounded-md transition duration-150 ease-in-out"
                            onclick="closeModal('hapusModal')">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Bulk Action -->
    <div id="bulkActionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Aksi Massal</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal('bulkActionModal')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
                <form id="bulkActionForm" method="POST" action="{{ route('admin.naskah.bulk-action') }}">
                    @csrf
                    <div class="mb-4">
                        <div class="mb-4">
                            <label for="bulk_action" class="block text-sm font-medium text-gray-700 mb-2">Pilih Aksi</label>
                            <select id="bulk_action" name="action" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih aksi...</option>
                                <option value="setujui">Setujui Semua</option>
                                <option value="tolak">Tolak Semua</option>
                                <option value="hapus">Hapus Semua</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="bulk_catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea id="bulk_catatan" name="catatan" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Tambahkan catatan..."></textarea>
                            <p class="text-xs text-gray-500 mt-1">Wajib diisi untuk aksi tolak</p>
                        </div>
                        <div id="selected-items" class="mb-4"></div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium rounded-md transition duration-150 ease-in-out"
                            onclick="closeModal('bulkActionModal')">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                                                       d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Jalankan Aksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection

@push('scripts')
    <script>
        // Modal functions
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent background scroll
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto'; // Restore scroll
                
                // Clear form data
                const form = modal.querySelector('form');
                if (form) {
                    form.reset();
                    // Remove hidden inputs for bulk action
                    if (modalId === 'bulkActionModal') {
                        const hiddenInputs = form.querySelectorAll('input[name="naskah_ids[]"]');
                        hiddenInputs.forEach(input => input.remove());
                        const selectedItems = document.getElementById('selected-items');
                        if (selectedItems) {
                            selectedItems.innerHTML = '';
                        }
                    }
                }
            }
        }

        // Close modal when clicking outside
        window.onclick = function (event) {
            const modals = ['setujuiModal', 'tolakModal', 'hapusModal', 'bulkActionModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (event.target === modal) {
                    closeModal(modalId);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Select All Checkbox
            const selectAllCheckbox = document.getElementById('selectAll');
            const naskahCheckboxes = document.querySelectorAll('.naskah-checkbox');

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function () {
                    naskahCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
            }

            // Update Select All when individual checkboxes change
            naskahCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const checkedCount = document.querySelectorAll('.naskah-checkbox:checked').length;
                    const totalCount = naskahCheckboxes.length;

                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = checkedCount === totalCount;
                        selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
                    }
                });
            });

            // Status Select Change
            document.querySelectorAll('.status-select').forEach(select => {
                select.addEventListener('change', function () {
                    const naskahId = this.dataset.naskahId;
                    const status = this.value;

                    fetch(`{{ url('admin/dashboard/naskah') }}/${naskahId}/update-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ status: status })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showNotification('Status berhasil diupdate', 'success');
                            } else {
                                showNotification('Gagal mengupdate status', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('Terjadi kesalahan', 'error');
                        });
                });
            });

            // Setujui Button - Menggunakan Modal
            document.querySelectorAll('.setujui-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const naskahId = this.dataset.naskahId;
                    const form = document.getElementById('setujuiForm');
                    if (form) {
                        form.action = `{{ url('admin/dashboard/naskah') }}/${naskahId}/setujui`;
                        openModal('setujuiModal');
                    }
                });
            });

            // Tolak Button - Menggunakan Modal
            document.querySelectorAll('.tolak-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const naskahId = this.dataset.naskahId;
                    const form = document.getElementById('tolakForm');
                    if (form) {
                        form.action = `{{ url('admin/dashboard/naskah') }}/${naskahId}/tolak`;
                        openModal('tolakModal');
                    }
                });
            });

            // Hapus Button - Menggunakan Modal
            document.querySelectorAll('.hapus-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const naskahId = this.dataset.naskahId;
                    const form = document.getElementById('hapusForm');
                    if (form) {
                        form.action = `{{ url('admin/dashboard/naskah') }}/${naskahId}`;
                        openModal('hapusModal');
                    }
                });
            });

            // Handle form submissions with loading states
            const forms = ['setujuiForm', 'tolakForm', 'hapusForm', 'bulkActionForm'];

            forms.forEach(formId => {
                const form = document.getElementById(formId);
                if (form) {
                    form.addEventListener('submit', function (e) {
                        const submitButton = form.querySelector('button[type="submit"]');
                        if (submitButton) {
                            const originalText = submitButton.innerHTML;

                            // Show loading state
                            submitButton.disabled = true;
                            submitButton.innerHTML = `
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            `;

                            // Reset button after 10 seconds (fallback)
                            setTimeout(() => {
                                submitButton.disabled = false;
                                submitButton.innerHTML = originalText;
                            }, 10000);
                        }
                    });
                }
            });

            // Enhanced bulk action form submission
            const bulkActionForm = document.getElementById('bulkActionForm');
            if (bulkActionForm) {
                bulkActionForm.addEventListener('submit', function (e) {
                    const actionSelect = document.getElementById('bulk_action');
                    const catatanInput = document.getElementById('bulk_catatan');
                    
                    if (actionSelect && catatanInput) {
                        const action = actionSelect.value;
                        const catatan = catatanInput.value;

                        // Validate required fields
                        if (action === 'tolak' && !catatan.trim()) {
                            e.preventDefault();
                            showNotification('Alasan penolakan harus diisi untuk aksi tolak', 'warning');
                            return;
                        }
                    }

                    // Show loading overlay
                    showLoadingOverlay();
                });
            }
        });

        // Bulk Action Modal
        function openBulkActionModal() {
            const selectedItems = document.querySelectorAll('.naskah-checkbox:checked');
            if (selectedItems.length === 0) {
                showNotification('Pilih minimal satu naskah terlebih dahulu', 'warning');
                return;
            }

            let itemsHtml = '<div class="bg-gray-50 rounded-md p-3"><p class="text-sm font-medium text-gray-700 mb-2">Naskah yang dipilih:</p><ul class="text-sm text-gray-600 space-y-1">';
            const form = document.getElementById('bulkActionForm');

            selectedItems.forEach(checkbox => {
                const row = checkbox.closest('tr');
                const judulElement = row.querySelector('td:nth-child(3) .text-sm.font-medium');
                const judul = judulElement ? judulElement.textContent.trim() : 'Unknown';
                itemsHtml += `<li class="flex items-center"><svg class="w-3 h-3 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>${judul}</li>`;

                // Add hidden input
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'naskah_ids[]';
                hiddenInput.value = checkbox.value;
                form.appendChild(hiddenInput);
            });

            itemsHtml += '</ul></div>';
            const selectedItemsElement = document.getElementById('selected-items');
            if (selectedItemsElement) {
                selectedItemsElement.innerHTML = itemsHtml;
            }

            openModal('bulkActionModal');
        }

        // Notification function
        function showNotification(message, type = 'info') {
            const colors = {
                success: 'bg-green-100 border-green-400 text-green-700',
                error: 'bg-red-100 border-red-400 text-red-700',
                warning: 'bg-yellow-100 border-yellow-400 text-yellow-700',
                info: 'bg-blue-100 border-blue-400 text-blue-700'
            };

            const icons = {
                success: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>',
                error: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>',
                warning: '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>',
                info: '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>'
            };

            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 ${colors[type]} px-4 py-3 rounded border flex items-center justify-between max-w-sm shadow-lg`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        ${icons[type]}
                    </svg>
                    ${message}
                </div>
                <button type="button" class="ml-4 hover:opacity-75" onclick="this.parentElement.remove()">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            `;

            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }

        // Add loading overlay for bulk actions
            function showLoadingOverlay() {
            const overlay = document.createElement('div');
            overlay.id = 'loadingOverlay';
            overlay.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50';
            overlay.innerHTML = `
                <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
                    <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-gray-700">Memproses aksi massal...</span>
                </div>
            `;
            document.body.appendChild(overlay);
        }

        function hideLoadingOverlay() {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.remove();
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function (e) {
            // Escape key to close modals
            if (e.key === 'Escape') {
                const modals = ['setujuiModal', 'tolakModal', 'hapusModal', 'bulkActionModal'];
                modals.forEach(modalId => {
                    const modal = document.getElementById(modalId);
                    if (modal && !modal.classList.contains('hidden')) {
                        closeModal(modalId);
                    }
                });
            }

            // Ctrl+A to select all (when not in input)
            if (e.ctrlKey && e.key === 'a' && !['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName)) {
                e.preventDefault();
                const selectAllCheckbox = document.getElementById('selectAll');
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = !selectAllCheckbox.checked;
                    selectAllCheckbox.dispatchEvent(new Event('change'));
                }
            }
        });

        // Enhanced table interactions
        document.addEventListener('DOMContentLoaded', function () {
            // Add hover effects and click handlers for table rows
            const tableRows = document.querySelectorAll('tbody tr');

            tableRows.forEach(row => {
                // Skip empty state row
                if (row.querySelector('td[colspan]')) return;

                // Add click handler to row (excluding action buttons)
                row.addEventListener('click', function (e) {
                    // Don't trigger if clicking on buttons, checkboxes, selects, or links
                    if (['BUTTON', 'INPUT', 'SELECT', 'A', 'SVG', 'PATH'].includes(e.target.tagName)) {
                        return;
                    }

                    // Don't trigger if clicking inside action buttons area
                    if (e.target.closest('.setujui-btn, .tolak-btn, .hapus-btn, .status-select')) {
                        return;
                    }

                    // Toggle checkbox
                    const checkbox = row.querySelector('.naskah-checkbox');
                    if (checkbox) {
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                });

                // Add visual feedback for clickable rows
                row.style.cursor = 'pointer';
            });

            // Enhanced status select with visual feedback
            document.querySelectorAll('.status-select').forEach(select => {
                const originalValue = select.value;

                select.addEventListener('change', function () {
                    // Add loading state
                    this.style.opacity = '0.6';
                    this.disabled = true;

                    const naskahId = this.dataset.naskahId;
                    const status = this.value;

                    fetch(`{{ url('admin/dashboard/naskah') }}/${naskahId}/update-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ status: status })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showNotification('Status berhasil diupdate', 'success');
                                // Update row styling based on new status
                                updateRowStyling(this.closest('tr'), status);
                            } else {
                                showNotification('Gagal mengupdate status', 'error');
                                // Revert to original value
                                this.value = originalValue;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('Terjadi kesalahan', 'error');
                            // Revert to original value
                            this.value = originalValue;
                        })
                        .finally(() => {
                            // Remove loading state
                            this.style.opacity = '1';
                            this.disabled = false;
                        });
                });
            });
        });

        // Function to update row styling based on status
        function updateRowStyling(row, status) {
            // Remove existing status classes
            row.classList.remove('bg-yellow-50', 'bg-blue-50', 'bg-green-50', 'bg-red-50');

            // Add new status class
            switch (status) {
                case 'pending':
                    row.classList.add('bg-yellow-50');
                    break;
                case 'sedang_direview':
                    row.classList.add('bg-blue-50');
                    break;
                case 'disetujui':
                    row.classList.add('bg-green-50');
                    break;
                case 'ditolak':
                    row.classList.add('bg-red-50');
                    break;
            }
        }

        // Auto-refresh functionality (optional)
        let autoRefreshInterval;

        function startAutoRefresh() {
            autoRefreshInterval = setInterval(() => {
                // Only refresh if no modals are open
                const modals = ['setujuiModal', 'tolakModal', 'hapusModal', 'bulkActionModal'];
                const isModalOpen = modals.some(modalId => {
                    const modal = document.getElementById(modalId);
                    return modal && !modal.classList.contains('hidden');
                });

                if (!isModalOpen) {
                    // Refresh page while maintaining current filters
                    window.location.reload();
                }
            }, 300000); // Refresh every 5 minutes
        }

        function stopAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }
        }

        // Stop auto-refresh when page is hidden
        document.addEventListener('visibilitychange', function () {
            if (document.hidden) {
                stopAutoRefresh();
            } else {
                // Uncomment to enable auto-refresh
                // startAutoRefresh();
            }
        });

        // Debug function to check if modals exist
        function checkModals() {
            const modals = ['setujuiModal', 'tolakModal', 'hapusModal', 'bulkActionModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                console.log(`${modalId}:`, modal ? 'Found' : 'Not found');
            });
        }

        // Call debug function on page load (remove in production)
        document.addEventListener('DOMContentLoaded', function() {
            // checkModals(); // Uncomment for debugging
        });

        // Handle Laravel flash messages
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('{{ session('success') }}', 'success');
            });
        @endif

        @if(session('error'))
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('{{ session('error') }}', 'error');
            });
        @endif

        @if(session('warning'))
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('{{ session('warning') }}', 'warning');
            });
        @endif

        @if(session('info'))
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('{{ session('info') }}', 'info');
            });
        @endif

        // Prevent double form submission
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                let isSubmitting = false;
                
                form.addEventListener('submit', function(e) {
                    if (isSubmitting) {
                        e.preventDefault();
                        return false;
                    }
                    
                    isSubmitting = true;
                    
                    // Reset after 5 seconds as fallback
                    setTimeout(() => {
                        isSubmitting = false;
                    }, 5000);
                });
            });
        });

        // Add confirmation for dangerous actions
        document.addEventListener('DOMContentLoaded', function() {
            // Confirm before bulk delete
            const bulkActionForm = document.getElementById('bulkActionForm');
            if (bulkActionForm) {
                bulkActionForm.addEventListener('submit', function(e) {
                    const action = document.getElementById('bulk_action').value;
                    
                    if (action === 'hapus') {
                        if (!confirm('Apakah Anda yakin ingin menghapus semua naskah yang dipilih? Tindakan ini tidak dapat dibatalkan!')) {
                            e.preventDefault();
                            hideLoadingOverlay();
                            return false;
                        }
                    }
                });
            }
        });
    </script>
@endpush
