@extends('admin.layouts.app')

@section('main')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Edit Laporan Penjualan Kolaborasi</h1>

        <form action="{{ route('penjualanKolaborasi.update', $laporan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul', $laporan->judul) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="penulis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Penulis</label>
                <input type="text" name="penulis" id="penulis" value="{{ old('penulis', $laporan->penulis) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('penulis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="bab" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bab</label>
                <input type="text" name="bab" id="bab" value="{{ old('bab', $laporan->bab) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('bab') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bukti Pembayaran</label>
                @if($laporan->bukti_pembayaran)
                    <div class="mb-2">
                        <img src="{{ asset('storage/bukti/' . $laporan->bukti_pembayaran) }}" alt="Bukti" class="w-32 border rounded">
                    </div>
                @endif
                <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="block w-full text-sm text-gray-700 border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('bukti_pembayaran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="status_pembayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Pembayaran</label>
                <select name="status_pembayaran" id="status_pembayaran" class="block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="sukses" {{ old('status_pembayaran', $laporan->status_pembayaran) == 'sukses' ? 'selected' : '' }}>Sukses</option>
                    <option value="tidak sesuai" {{ old('status_pembayaran', $laporan->status_pembayaran) == 'tidak sesuai' ? 'selected' : '' }}>Tidak Sesuai</option>
                </select>
                @error('status_pembayaran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('penjualanKolaborasi.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Batal</a>
                <button type="submit" class="ml-3 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
