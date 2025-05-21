@extends('admin.layouts.app')

@section('main')
<div class="container">
    <h2 class="text-2xl font-bold mb-4 text-center">Edit Laporan Penjualan Buku Kolaborasi</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('penjualanKolaborasi.update', $laporan->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label>Nama Penulis:</label>
            <input type="text" name="nama_penulis" class="w-full p-2 border rounded" value="{{ $laporan->nama_penulis }}" required>
        </div>

        <div>
            <label>Judul Buku:</label>
            <input type="text" name="judul_buku" class="w-full p-2 border rounded" value="{{ $laporan->judul_buku }}" required>
        </div>

        <div>
            <label>Jumlah Terjual:</label>
            <input type="number" name="jumlah_terjual" class="w-full p-2 border rounded" value="{{ $laporan->jumlah_terjual }}" required>
        </div>

        <div>
            <label>Total Harga:</label>
            <input type="number" name="total_harga" class="w-full p-2 border rounded" value="{{ $laporan->total_harga }}" required>
        </div>

        <div>
            <label>Tanggal Penjualan:</label>
            <input type="date" name="tanggal_penjualan" class="w-full p-2 border rounded" value="{{ $laporan->tanggal_penjualan }}" required>
        </div>

        <div>
            <label>Status Pembayaran:</label>
            <select name="status_pembayaran" class="w-full p-2 border rounded" required>
                <option value="">-- Pilih --</option>
                <option value="valid" {{ $laporan->status_pembayaran == 'valid' ? 'selected' : '' }}>Valid</option>
                <option value="tidak valid" {{ $laporan->status_pembayaran == 'tidak valid' ? 'selected' : '' }}>Tidak Valid</option>
            </select>
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Perbarui</button>
    </form>
</div>
@endsection
