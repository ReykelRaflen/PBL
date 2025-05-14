@extends('admin.layouts.app')

@section('main')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Dashboard Admin</h1>

    <!-- Kartu Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 rounded shadow text-center">
            <p class="text-gray-500 text-sm">Total Buku</p>
            <p class="text-xl font-bold text-blue-600">2.485</p>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <p class="text-gray-500 text-sm">Member</p>
            <p class="text-xl font-bold text-blue-600">1.234</p>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <p class="text-gray-500 text-sm">Editor</p>
            <p class="text-xl font-bold text-blue-600">324</p>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <p class="text-gray-500 text-sm">Total Pendapatan</p>
            <p class="text-xl font-bold text-blue-600">Rp 45.200.000</p>
        </div>
    </div>

    <!-- Laporan Penjualan Section -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Laporan Penjualan Terbaru</h2>
            <a href="{{ route('penjualanIndividu.index') }}" class="text-blue-600 hover:text-blue-800">Lihat Semua</a>
        </div>
        @if(isset($laporan) && $laporan->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul Buku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penulis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Terjual</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($laporan as $item)
                            <tr>
                                <td class="px-6 py-4">{{ $item->judul_buku }}</td>
                                <td class="px-6 py-4">{{ $item->nama_penulis }}</td>
                                <td class="px-6 py-4">{{ $item->jumlah_terjual }}</td>
                                <td class="px-6 py-4">{{ $item->tanggal_penjualan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-4">Belum ada data laporan penjualan.</p>
        @endif
    </div>

    <!-- Grafik atau Visualisasi Data -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-4">Penjualan Bulanan</h3>
            <!-- Tambahkan grafik atau visualisasi data di sini -->
            <div class="h-64 bg-gray-100 rounded flex items-center justify-center">
                <p class="text-gray-500">Grafik Penjualan</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-4">Kategori Terpopuler</h3>
            <!-- Tambahkan grafik atau visualisasi data di sini -->
            <div class="h-64 bg-gray-100 rounded flex items-center justify-center">
                <p class="text-gray-500">Grafik Kategori</p>
            </div>
        </div>
    </div>
</div>
@endsection
