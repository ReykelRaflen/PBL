@extends('admin.layouts.app')

@section('title', 'Detail Buku')

@section('main')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detail Buku</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.books.edit', $book->id) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Edit Buku
            </a>
            <a href="{{ route('admin.books.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Cover dan Info Dasar -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <!-- Cover Buku -->
                <div class="text-center mb-6">
                    @if($book->cover)
                        <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->judul_buku }}" 
                             class="w-full max-w-xs mx-auto rounded-lg shadow-lg">
                    @else
                        <div class="w-full max-w-xs mx-auto h-96 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Status dan Badge -->
                <div class="space-y-3">
                    <!-- Status Stok -->
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Status Stok:</span>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $book->stok > 0 ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                            {{ $book->stok > 0 ? 'Tersedia' : 'Habis' }} ({{ $book->stok }})
                        </span>
                    </div>

                    <!-- Kategori -->
                    @if($book->kategori)
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Kategori:</span>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                            {{ $book->kategori->nama }}
                        </span>
                    </div>
                    @endif

                    <!-- Promo -->
                    @if($book->promo && $book->promo->isActive())
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Promo:</span>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                            {{ $book->promo->kode_promo }}
                        </span>
                    </div>
                    @endif

                    <!-- File E-book -->
                    @if($book->file_buku)
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">E-book:</span>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100">
                            Tersedia
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Detail Informasi -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <!-- Header -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $book->judul_buku }}</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400">oleh {{ $book->penulis }}</p>
                </div>

                <!-- Informasi Detail -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kolom Kiri -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Penerbit</label>
                                <p class="text-gray-900 dark:text-white">{{ $book->penerbit ?: '-' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Terbit</label>
                                <p class="text-gray-900 dark:text-white">{{ $book->tahun_terbit ?: '-' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ISBN</label>
                                <p class="text-gray-900 dark:text-white font-mono">{{ $book->isbn ?: '-' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stok Tersedia</label>
                                <p class="text-gray-900 dark:text-white">{{ $book->stok }} unit</p>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga</label>
                                @if($book->harga)
                                    <div class="space-y-1">
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                            Rp {{ number_format($book->harga, 0, ',', '.') }}
                                        </p>
                                        @if($book->harga_promo && $book->harga_promo < $book->harga)
                                            <p class="text-lg font-semibold text-green-600 dark:text-green-400">
                                                Harga Promo: Rp {{ number_format($book->harga_promo, 0, ',', '.') }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Hemat: Rp {{ number_format($book->harga - $book->harga_promo, 0, ',', '.') }}
                                                ({{ round((($book->harga - $book->harga_promo) / $book->harga) * 100) }}%)
                                            </p>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-gray-500 dark:text-gray-400">Belum ditentukan</p>
                                @endif
                            </div>

                            @if($book->promo && $book->promo->isActive())
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Detail Promo</label>
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg">
                                    <p class="font-semibold text-yellow-800 dark:text-yellow-200">{{ $book->promo->kode_promo }}</p>
                                    <p class="text-sm text-yellow-700 dark:text-yellow-300">{{ $book->promo->keterangan }}</p>
                                    <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">
                                        Berlaku: {{ $book->promo->tanggal_mulai->format('d/m/Y') }} - {{ $book->promo->tanggal_selesai->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Ditambahkan</label>
                                <p class="text-gray-900 dark:text-white">{{ $book->created_at->format('d F Y, H:i') }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Terakhir Diupdate</label>
                                <p class="text-gray-900 dark:text-white">{{ $book->updated_at->format('d F Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    @if($book->deskripsi)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Deskripsi</label>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $book->deskripsi }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Footer Actions -->
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600 rounded-b-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.books.edit', $book->id) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg shadow text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Edit
                            </a>
                            
                                                       @if($book->file_buku)
                            <a href="{{ asset('storage/' . $book->file_buku) }}" target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm6.293-13.707a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 6.414V13a1 1 0 11-2 0V6.414L7.707 7.707a1 1 0 01-1.414-1.414l3-3z" clip-rule="evenodd" />
                                </svg>
                                Download E-book
                            </a>
                            @endif

                                                       <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus buku ini? Data yang dihapus tidak dapat dikembalikan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                        
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                Terakhir diupdate: {{ $book->updated_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Tambahan -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-1 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Total Terjual</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">0 unit</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Data penjualan akan tersedia setelah ada transaksi</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
