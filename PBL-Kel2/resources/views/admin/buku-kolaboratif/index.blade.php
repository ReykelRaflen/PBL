@extends('admin.layouts.app')

@section('title', 'Kelola Buku Kolaboratif')

@section('main')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Buku Kolaboratif</h1>
            <p class="text-gray-600 dark:text-gray-400">Kelola buku dan bab untuk penerbitan kolaborasi</p>
        </div>
        <a href="{{ route('admin.buku-kolaboratif.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Buku Baru
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i data-lucide="book-open" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Buku</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $bukuKolaboratif->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600 dark:text-green-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Buku Aktif</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $bukuKolaboratif->where('status', 'aktif')->count() }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                    <i data-lucide="clock" class="w-6 h-6 text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Dalam Progress</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $bukuKolaboratif->filter(function($buku) { return $buku->progress_percentage > 0 && $buku->progress_percentage < 100; })->count() }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <i data-lucide="award" class="w-6 h-6 text-purple-600 dark:text-purple-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Selesai</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $bukuKolaboratif->where('status', 'selesai')->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Buku
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Kategori
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Bab
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Harga/Bab
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Progress
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($bukuKolaboratif as $buku)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <img class="h-12 w-12 rounded-lg object-cover" 
                                             src="{{ $buku->gambar_sampul_url }}" 
                                             alt="{{ $buku->judul }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ Str::limit($buku->judul, 30) }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $buku->created_at->format('d M Y') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    {{ $buku->kategoriBuku->nama ?? 'Tidak ada' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium">{{ $buku->bab_buku_count }}</span>
                                    <span class="text-gray-500">/</span>
                                    <span class="text-gray-500">{{ $buku->total_bab }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $buku->harga_format }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700 mr-2">
                                        <div class="bg-blue-600 h-2 rounded-full" 
                                             style="width: {{ $buku->progress_percentage }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $buku->progress_percentage }}%
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $buku->status_badge_class }}">
                                    {{ $buku->status_text }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.buku-kolaboratif.show', $buku->id) }}" 
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('admin.buku-kolaboratif.edit', $buku->id) }}" 
                                       class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    @if($buku->canBeDeleted())
                                        <form action="{{ route('admin.buku-kolaboratif.destroy', $buku->id) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                Belum ada buku kolaboratif. 
                                <a href="{{ route('admin.buku-kolaboratif.create') }}" 
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    Tambah buku pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($bukuKolaboratif->hasPages())
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                              {{ $bukuKolaboratif->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
