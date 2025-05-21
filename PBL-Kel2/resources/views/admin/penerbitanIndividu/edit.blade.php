@extends('admin.layouts.app')

@section('main')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Edit Laporan Buku Individu</h1>

        <form action="{{ route('penerbitanIndividu.update', $laporan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="kode_buku" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kode Buku</label>
                <input type="text" name="kode_buku" id="kode_buku" value="{{ old('kode_buku', $laporan->kode_buku) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                @error('kode_buku')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul', $laporan->judul) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                @error('judul')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="penulis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Penulis</label>
                <input type="text" name="penulis" id="penulis" value="{{ old('penulis', $laporan->penulis) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                @error('penulis')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tanggal_terbit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Terbit</label>
                <input type="date" name="tanggal_terbit" id="tanggal_terbit" value="{{ old('tanggal_terbit', $laporan->tanggal_terbit) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                @error('tanggal_terbit')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="jumlah_terjual" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Terjual</label>
                <input type="number" name="jumlah_terjual" id="jumlah_terjual" value="{{ old('jumlah_terjual', $laporan->jumlah_terjual) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                @error('jumlah_terjual')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="status" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    <option value="proses" {{ (old('status', $laporan->status) == 'proses') ? 'selected' : '' }}>Proses</option>
                    <option value="terbit" {{ (old('status', $laporan->status) == 'terbit') ? 'selected' : '' }}>Terbit</option>
                    <option value="pending" {{ (old('status', $laporan->status) == 'pending') ? 'selected' : '' }}>Pending</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end mt-6">
                <a href="{{ route('penerbitanIndividu.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition mr-3">
                    Kembali
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
