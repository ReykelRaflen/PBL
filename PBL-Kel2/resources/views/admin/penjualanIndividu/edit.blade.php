@extends('admin.layouts.app')

@section('main')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Edit Laporan Penjualan</h1>

        <form action="{{ route('penjualanIndividu.update', $laporan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul', $laporan->judul) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('judul')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="penulis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Penulis</label>
                <input type="text" name="penulis" id="penulis" value="{{ old('penulis', $laporan->penulis) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('penulis')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="paket" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Paket</label>
                <select name="paket" id="paket" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih Paket</option>
                    <option value="silver" {{ old('paket', $laporan->paket) == 'silver' ? 'selected' : '' }}>Silver</option>
                    <option value="gold" {{ old('paket', $laporan->paket) == 'gold' ? 'selected' : '' }}>Gold</option>
                    <option value="diamond" {{ old('paket', $laporan->paket) == 'diamond' ? 'selected' : '' }}>Diamond</option>
                </select>
                @error('paket')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bukti Pembayaran (gambar)</label>
                <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" accept="image/*" class="mt-1 block w-full text-sm text-gray-700 border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @if ($laporan->bukti_pembayaran)
                    <div class="mt-2">
                        <img src="{{ asset('storage/bukti/' . $laporan->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="w-32 rounded border">
                    </div>
                @endif
                @error('bukti_pembayaran')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status_pembayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Pembayaran</label>
                <select name="status_pembayaran" id="status_pembayaran" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih Status</option>
                    <option value="sukses" {{ old('status_pembayaran', $laporan->status_pembayaran) == 'sukses' ? 'selected' : '' }}>Sukses</option>
                    <option value="tidak sesuai" {{ old('status_pembayaran', $laporan->status_pembayaran) == 'tidak sesuai' ? 'selected' : '' }}>Tidak Sesuai</option>
                </select>
                @error('status_pembayaran')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end mt-6">
                <a href="{{ route('penjualanIndividu.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition mr-3">
                    Kembali
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring ring-green-300 disabled:opacity-25 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection