@extends('admin.layouts.app')

@section('main')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center text-blue-700">Laporan Penjualan Buku Kolaborasi</h1>

    <div class="flex justify-end mb-4">
        <a href="{{ route('penjualanKolaborasi.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            + Tambah Data
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded shadow">
            <thead class="bg-blue-100 text-blue-800">
                <tr>
                    <th class="py-2 px-4 border">No</th>
                    <th class="py-2 px-4 border">Nama Buku</th>
                    <th class="py-2 px-4 border">Penulis</th>
                    <th class="py-2 px-4 border">Jumlah Terjual</th>
                    <th class="py-2 px-4 border">Total Harga</th>
                    <th class="py-2 px-4 border">Tanggal Penjualan</th>
                    <th class="py-2 px-4 border">Status Pembayaran</th>
                    <th class="py-2 px-4 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporan as $item)
                <tr>
                    <td class="py-2 px-4 border">{{ $loop->iteration }}</td>
                    <td class="py-2 px-4 border">{{ $item->nama_buku }}</td>
                    <td class="py-2 px-4 border">{{ $item->penulis }}</td>
                    <td class="py-2 px-4 border">{{ $item->jumlah_terjual }}</td>
                    <td class="py-2 px-4 border">Rp{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td class="py-2 px-4 border">{{ $item->tanggal_penjualan }}</td>
                    <td class="py-2 px-4 border">{{ ucfirst($item->status_pembayaran) }}</td>
                    <td class="py-2 px-4 border flex gap-2">
                        <a href="{{ route('penjualanKolaborasi.edit', $item->id) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('penjualanKolaborasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-4 px-4 text-center text-gray-500">Data belum tersedia.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
