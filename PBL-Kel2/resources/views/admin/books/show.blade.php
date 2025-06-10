@extends('admin.layouts.app')

@section('title', 'Detail Buku')

@section('main')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detail Buku</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.books.edit', $book->id) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg shadow transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Edit Buku
            </a>
            <a href="{{ route('admin.books.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
        <div class="flex">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <p>{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Cover dan Info Dasar -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <!-- Cover Buku -->
                <div class="text-center mb-6">
                    @if($book->cover)
                        <div class="relative inline-block">
                            <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->judul_buku }}" 
                                 class="w-full max-w-xs mx-auto rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 transition-all duration-300 rounded-lg flex items-center justify-center opacity-0 hover:opacity-100">
                                <button onclick="openImageModal('{{ asset('storage/' . $book->cover) }}')" class="bg-white bg-opacity-90 text-gray-800 px-3 py-2 rounded-lg text-sm font-medium">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                    </svg>
                                    Perbesar
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="w-full max-w-xs mx-auto h-96 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-400 dark:text-gray-500 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada cover</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Status dan Badge -->
                <div class="space-y-4">
                    <!-- Status Stok -->
                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Status Stok:</span>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $book->stok > 0 ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                            {{ $book->stok > 0 ? 'Tersedia' : 'Habis' }} ({{ $book->stok }})
                        </span>
                    </div>

                    <!-- Kategori -->
                    @if($book->kategori)
                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Kategori:</span>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                            {{ $book->kategori->nama }}
                        </span>
                    </div>
                    @endif

                    <!-- Promo -->
                    @if($book->promo && $book->promo->isActive())
                    <div class="flex justify-between items-center p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Promo Aktif:</span>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                            {{ $book->promo->kode_promo }}
                        </span>
                    </div>
                    @endif

                    <!-- File E-book -->
                    @if($book->file_buku)
                    <div class="flex justify-between items-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">E-book:</span>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                            </svg>
                            Tersedia
                        </span>
                    </div>
                    @endif

                    <!-- Harga E-book jika ada -->
                    @if($book->harga_ebook)
                    <div class="flex justify-between items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Harga E-book:</span>
                        <span class="text-sm font-bold text-green-700 dark:text-green-300">
                            Rp {{ number_format($book->harga_ebook, 0, ',', '.') }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Detail Informasi -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                <!-- Header -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $book->judul_buku }}</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        oleh {{ $book->penulis }}
                    </p>
                </div>

                <!-- Informasi Detail -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kolom Kiri -->
                        <div class="space-y-4">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Penerbit</label>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $book->penerbit ?: 'Tidak disebutkan' }}</p>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tahun Terbit</label>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $book->tahun_terbit ?: 'Tidak disebutkan' }}</p>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ISBN</label>
                                <p class="text-gray-900 dark:text-white font-mono text-sm">{{ $book->isbn ?: 'Tidak ada' }}</p>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stok Tersedia</label>
                                <p class="text-gray-900 dark:text-white font-medium">
                                    <span class="text-2xl">{{ $book->stok }}</span> unit
                                </p>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="space-y-4">
                            <!-- Harga Buku Fisik -->
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Harga Buku Fisik</label>
                                @if($book->harga)
                                    <div class="space-y-2">
                                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                                            Rp {{ number_format($book->harga, 0, ',', '.') }}
                                        </p>
                                        @if($book->harga_promo < $book->harga)
                                            <div class="bg-green-100 dark:bg-green-800/30 p-3 rounded-lg border border-green-300 dark:border-green-700">
                                                <p class="text-lg font-semibold text-green-700 dark:text-green-300">
                                                    <svg class="w-5 h-5 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                    Harga Promo: Rp {{ number_format($book->harga_promo, 0, ',', '.') }}
                                                </p>
                                                <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                                    Hemat: Rp {{ number_format($book->harga - $book->harga_promo, 0, ',', '.') }}
                                                    ({{ round((($book->harga - $book->harga_promo) / $book->harga) * 100) }}%)
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-gray-500 dark:text-gray-400">Belum ditentukan</p>
                                @endif
                            </div>

                            <!-- Harga E-book -->
                            @if($book->harga_ebook)
                            <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 p-4 rounded-lg border border-purple-200 dark:border-purple-800">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Harga E-book</label>
                                <p class="text-2xl font-bold text-purple-700 dark:text-purple-300">
                                    Rp {{ number_format($book->harga_ebook, 0, ',', '.') }}
                                </p>
                                @if($book->harga && $book->harga_ebook < $book->harga)
                                <p class="text-sm text-purple-600 dark:text-purple-400 mt-1">
                                    Lebih murah Rp {{ number_format($book->harga - $book->harga_ebook, 0, ',', '.') }} dari buku fisik
                                </p>
                                @endif
                            </div>
                            @endif

                            <!-- Detail Promo -->
                            @if($book->promo && $book->promo->isActive())
                            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 p-4 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Detail Promo</label>
                                <div class="space-y-2">
                                    <p class="font-bold text-yellow-800 dark:text-yellow-200 text-lg">{{ $book->promo->kode_promo }}</p>
                                    <p class="text-sm text-yellow-700 dark:text-yellow-300">{{ $book->promo->keterangan }}</p>
                                    <div class="flex items-center text-xs text-yellow-600 dark:text-yellow-400">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $book->promo->tanggal_mulai->format('d/m/Y') }} - {{ $book->promo->tanggal_selesai->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs bg-yellow-100 dark:bg-yellow-800/50 px-2 py-1 rounded">
                                        Diskon: {{ $book->promo->tipe === 'Persentase' ? $book->promo->besaran.'%' : 'Rp '.number_format($book->promo->besaran) }}
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Informasi Waktu -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Ditambahkan</label>
                                    <p class="text-gray-900 dark:text-white text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $book->created_at->format('d F Y, H:i') }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Terakhir Diupdate</label>
                                    <p class="text-gray-900 dark:text-white text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $book->updated_at->format('d F Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    @if($book->deskripsi)
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <label class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Deskripsi Buku
                        </label>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="prose dark:prose-invert max-w-none">
                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $book->deskripsi }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Footer Actions -->
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600 rounded-b-lg">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.books.edit', $book->id) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg shadow text-sm font-medium transition-colors flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Edit Buku
                            </a>
                            
                            @if($book->file_buku)
                            <a href="{{ asset('storage/' . $book->file_buku) }}" target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow text-sm font-medium transition-colors flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm6.293-13.707a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 6.414V13a1 1 0 11-2 0V6.414L7.707 7.707a1 1 0 01-1.414-1.414l3-3z" clip-rule="evenodd" />
                                </svg>
                                Download E-book
                            </a>
                            @endif


                            <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" class="inline" onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow text-sm font-medium transition-colors flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Hapus Buku
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
    <div class="mt-8 grid grid-cols-1 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Total Terjual</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">0 unit</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Buku fisik & e-book</p>
                </div>
            </div>
        </div>
    </div>


<!-- Modal untuk memperbesar gambar cover -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImageModal()" class="absolute -top-4 -right-4 bg-white text-black rounded-full w-8 h-8 flex items-center justify-center hover:bg-gray-200 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img id="modalImage" src="" alt="Cover Buku" class="max-w-full max-h-full rounded-lg shadow-2xl">
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide success message
    const successAlert = document.querySelector('.bg-green-100');
    if (successAlert) {
        setTimeout(() => {
            successAlert.style.transition = 'opacity 0.5s ease-out';
            successAlert.style.opacity = '0';
            setTimeout(() => {
                successAlert.remove();
            }, 500);
        }, 5000);
    }
});

// Function untuk membuka modal gambar
function openImageModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    modalImage.src = imageSrc;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Function untuk menutup modal gambar
function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal ketika klik di luar gambar
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Close modal dengan ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Function untuk konfirmasi hapus
function confirmDelete() {
    return confirm('⚠️ PERINGATAN!\n\nAnda yakin ingin menghapus buku "{{ $book->judul_buku }}"?\n\n• Data buku akan dihapus permanen\n• File cover dan e-book akan dihapus\n• Data penjualan terkait akan terpengaruh\n• Tindakan ini TIDAK DAPAT DIBATALKAN\n\nKetik "HAPUS" untuk melanjutkan:') && 
           prompt('Ketik "HAPUS" untuk konfirmasi:') === 'HAPUS';
}

// Function untuk copy link buku
function copyBookLink() {
    const bookUrl = window.location.href;
    
    if (navigator.clipboard && window.isSecureContext) {
        // Menggunakan modern clipboard API
        navigator.clipboard.writeText(bookUrl).then(() => {
            showNotification('Link berhasil disalin ke clipboard!', 'success');
        }).catch(() => {
            fallbackCopyTextToClipboard(bookUrl);
        });
    } else {
        // Fallback untuk browser lama
        fallbackCopyTextToClipboard(bookUrl);
    }
}

// Fallback copy function
function fallbackCopyTextToClipboard(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";
    
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showNotification('Link berhasil disalin ke clipboard!', 'success');
        } else {
            showNotification('Gagal menyalin link. Silakan salin manual.', 'error');
        }
    } catch (err) {
        showNotification('Gagal menyalin link. Silakan salin manual.', 'error');
    }
    
    document.body.removeChild(textArea);
}

// Function untuk menampilkan notifikasi
function showNotification(message, type = 'info') {
    // Hapus notifikasi yang ada
    const existingNotification = document.getElementById('notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Buat notifikasi baru
    const notification = document.createElement('div');
    notification.id = 'notification';
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium transition-all duration-300 transform translate-x-full`;
    
    // Set warna berdasarkan type
    switch(type) {
        case 'success':
            notification.className += ' bg-green-500';
            break;
        case 'error':
            notification.className += ' bg-red-500';
            break;
        case 'warning':
            notification.className += ' bg-yellow-500';
            break;
        default:
            notification.className += ' bg-blue-500';
    }
    
    notification.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl+E untuk edit
    if (e.ctrlKey && e.key === 'e') {
        e.preventDefault();
        window.location.href = '{{ route("admin.books.edit", $book->id) }}';
    }
    
    // Ctrl+B untuk kembali
    if (e.ctrlKey && e.key === 'b') {
        e.preventDefault();
        window.location.href = '{{ route("admin.books.index") }}';
    }
    
    // Ctrl+D untuk download e-book (jika ada)
    @if($book->file_buku)
    if (e.ctrlKey && e.key === 'd') {
        e.preventDefault();
        window.open('{{ asset("storage/" . $book->file_buku) }}', '_blank');
    }
    @endif
});

// Print functionality
function printBookDetails() {
    window.print();
}

// Add print button functionality
document.addEventListener('DOMContentLoaded', function() {
    // Bisa ditambahkan print button jika diperlukan
    const printBtn = document.createElement('button');
    printBtn.innerHTML = `
        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H3a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
        Print
    `;
    printBtn.className = 'bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow text-sm font-medium transition-colors flex items-center';
    printBtn.onclick = printBookDetails;
    
    // Uncomment jika ingin menambahkan print button
    // document.querySelector('.flex.flex-wrap.gap-3').appendChild(printBtn);
});
</script>
@endpush

@push('styles')
<style>
/* Print styles */
@media print {
    .no-print, 
    .bg-gray-50,
    .shadow-lg,
    button,
    .flex.flex-wrap.gap-3 {
        display: none !important;
    }
    
    body {
        background: white !important;
        color: black !important;
    }
    
    .bg-white,
    .dark\:bg-gray-800 {
        background: white !important;
        color: black !important;
    }
    
    .border {
        border-color: #ccc !important;
    }
        
    .text-gray-900,
    .dark\:text-white {
        color: black !important;
    }
    
    .text-gray-600,
    .dark\:text-gray-400 {
        color: #666 !important;
    }
    
    .rounded-lg {
        border: 1px solid #ddd;
    }
    
    /* Hide interactive elements */
    .hover\:shadow-xl,
    .transition-shadow,
    .transition-colors,
    .hover\:bg-indigo-600,
    .hover\:bg-gray-600 {
        transition: none !important;
    }
    
    /* Ensure images print well */
    img {
        max-width: 300px !important;
        height: auto !important;
        page-break-inside: avoid;
    }
    
    /* Page breaks */
    .lg\:col-span-2 {
        page-break-inside: avoid;
    }
    
    /* Compact spacing for print */
    .p-6 {
        padding: 1rem !important;
    }
    
    .space-y-4 > * + * {
        margin-top: 0.5rem !important;
    }
    
    .gap-6 {
        gap: 1rem !important;
    }
}

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

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

/* Page load animations */
.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

.animate-slide-in-right {
    animation: slideInRight 0.3s ease-out;
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Hover effects */
.hover-scale {
    transition: transform 0.2s ease-in-out;
}

.hover-scale:hover {
    transform: scale(1.02);
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.dark ::-webkit-scrollbar-track {
    background: #374151;
}

.dark ::-webkit-scrollbar-thumb {
    background: #6b7280;
}

.dark ::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Modal styles */
#imageModal {
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}

#imageModal img {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Notification styles */
#notification {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .grid-cols-1.lg\:grid-cols-3 {
        grid-template-columns: 1fr;
    }
    
    .flex.flex-wrap.gap-3 {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .flex.flex-wrap.gap-3 > * {
        width: 100%;
        justify-content: center;
    }
    
    .text-3xl {
        font-size: 1.875rem;
    }
    
    .text-2xl {
        font-size: 1.5rem;
    }
    
    .px-6 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .py-6 {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    
    /* Stack buttons vertically on mobile */
    .flex.items-center.justify-between {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .flex.items-center.justify-between > div:last-child {
        text-align: center;
    }
}

@media (max-width: 640px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .text-2xl {
        font-size: 1.25rem;
    }
    
    .text-lg {
        font-size: 1rem;
    }
    
    .p-4 {
        padding: 0.75rem;
    }
    
    .space-y-4 > * + * {
        margin-top: 0.75rem;
    }
    
    .gap-6 {
        gap: 1rem;
    }
    
    /* Adjust modal for mobile */
    #imageModal {
        padding: 1rem;
    }
    
    #imageModal .relative {
        max-width: 100%;
    }
    
    #imageModal button {
        top: -2rem;
        right: 0;
    }
}

/* Focus styles for accessibility */
button:focus,
a:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Loading states */
.loading {
    opacity: 0.6;
    pointer-events: none;
    position: relative;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Gradient backgrounds */
.gradient-bg-1 {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.gradient-bg-2 {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.gradient-bg-3 {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

/* Badge animations */
.badge-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Smooth transitions */
* {
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .bg-gray-50 {
        background-color: white;
        border: 1px solid black;
    }
    
    .text-gray-600 {
        color: black;
    }
    
    .border-gray-200 {
        border-color: black;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}

/* Dark mode improvements */
@media (prefers-color-scheme: dark) {
    .dark\:bg-gray-800 {
        background-color: #1f2937;
    }
    
    .dark\:text-white {
        color: #ffffff;
    }
    
    .dark\:text-gray-300 {
        color: #d1d5db;
    }
    
    .dark\:border-gray-700 {
        border-color: #374151;
    }
}

/* Custom utilities */
.text-shadow {
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.backdrop-blur {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

.glass-effect {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Tooltip styles */
.tooltip {
    position: relative;
}

.tooltip::before {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s;
    z-index: 1000;
}

.tooltip:hover::before {
    opacity: 1;
}

/* Selection styles */
::selection {
    background-color: #3b82f6;
    color: white;
}

::-moz-selection {
    background-color: #3b82f6;
    color: white;
}
</style>
@endpush

@section('meta')
<meta name="description" content="Detail buku {{ $book->judul_buku }} oleh {{ $book->penulis }}">
<meta name="keywords" content="{{ $book->judul_buku }}, {{ $book->penulis }}, {{ $book->kategori ? $book->kategori->nama : 'buku' }}">
<meta property="og:title" content="{{ $book->judul_buku }} - {{ config('app.name') }}">
<meta property="og:description" content="{{ $book->deskripsi ? Str::limit($book->deskripsi, 160) : 'Detail buku ' . $book->judul_buku }}">
@if($book->cover)
<meta property="og:image" content="{{ asset('storage/' . $book->cover) }}">
@endif
<meta property="og:type" content="book">
<meta property="book:author" content="{{ $book->penulis }}">
@if($book->isbn)
<meta property="book:isbn" content="{{ $book->isbn }}">
@endif
@if($book->tahun_terbit)
<meta property="book:release_date" content="{{ $book->tahun_terbit }}">
@endif
@endsection
@endsection



