@extends('admin.layouts.app')

@section('main')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Tambah Laporan Kolaborasi</h1>
        
        <form action="{{ route('penerbitanKolaborasi.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="kode_buku" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kode Buku</label>
                <input type="text" name="kode_buku" id="kode_buku" value="{{ old('kode_buku') }}" class="form-input dark:bg-gray-700 dark:text-white" required>
                @error('kode_buku')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul') }}" class="form-input dark:bg-gray-700 dark:text-white" required>
                @error('judul')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="bab_buku" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bab Buku</label>
                <input type="text" name="bab_buku" id="bab_buku" value="{{ old('bab_buku') }}" class="form-input dark:bg-gray-700 dark:text-white" required>
                @error('bab_buku')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="penulis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Penulis</label>
                <input type="text" name="penulis" id="penulis" value="{{ old('penulis') }}" class="form-input dark:bg-gray-700 dark:text-white" required>
                @error('penulis')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="tanggal_terbit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Terbit</label>
                <input type="date" name="tanggal_terbit" id="tanggal_terbit" value="{{ old('tanggal_terbit') }}" class="form-input dark:bg-gray-700 dark:text-white" required>
                @error('tanggal_terbit')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="jumlah_terjual" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Terjual</label>
                <input type="number" name="jumlah_terjual" id="jumlah_terjual" value="{{ old('jumlah_terjual') }}" class="form-input dark:bg-gray-700 dark:text-white" required>
                @error('jumlah_terjual')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="status" class="form-select dark:bg-gray-700 dark:text-white" required>
                    <option value="">Pilih Status</option>
                    <option value="proses" {{ old('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="terbit" {{ old('status') == 'terbit' ? 'selected' : '' }}>Terbit</option>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end mt-6">
                <a href="{{ route('penerbitanKolaborasi.index') }}" class="btn bg-gray-300 hover:bg-gray-400 text-gray-700 mr-3">Batal</a>
                <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
