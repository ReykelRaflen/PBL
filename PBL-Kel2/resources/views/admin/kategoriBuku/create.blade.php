@extends('admin.layouts.app')

@section('main')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Tambah Kategori Buku</h1>

        <form action="{{ route('kategori-buku.store') }}" method="POST">
            @csrf

            <!-- Nama -->
            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                @error('nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="status" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol -->
            <div class="flex items-center justify-end mt-6">
                <a href="{{ route('kategori-buku.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-white uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition mr-3">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
