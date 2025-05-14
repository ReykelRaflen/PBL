@extends('admin.layouts.app')

@section('main')
<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Tambah Laporan Penjualan Buku Individu</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('penjualanIndividu.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Penulis
                    </label>
                    <input 
                        type="text" 
                        name="penulis" 
                        value="{{ old('penulis') }}"
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                </div>
                

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Buku
                    </label>
                    <input 
                        type="text" 
                        name="judul_buku" 
                        value="{{ old('judul_buku') }}"
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah Terjual
                    </label>
                    <input 
                        type="number" 
                        name="jumlah_terjual" 
                        value="{{ old('jumlah_terjual') }}"
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                        required
                        min="1"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Penjualan
                    </label>
                    <input 
                        type="date" 
                        name="tanggal_penjualan" 
                        value="{{ old('tanggal_penjualan') }}"
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status Pembayaran
                    </label>
                    <select name="status_pembayaran" class="w-full p-2 border rounded" required>
                        <option value="">Pilih Status</option>
                        <option value="Valid">Valid</option>
                        <option value="Tidak Valid">Tidak Valid</option>
                    </select>
                    
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('penjualanIndividu.index') }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
