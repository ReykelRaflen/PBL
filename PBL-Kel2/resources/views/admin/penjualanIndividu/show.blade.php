@extends('admin.layouts.app')

@section('main')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg max-w-2xl mx-auto">
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Detail Laporan Penjualan</h1>

        <div class="space-y-4 text-sm text-gray-800 dark:text-gray-200">
            <div><strong>Judul:</strong> {{ $laporan->judul }}</div>
            <div><strong>Penulis:</strong> {{ $laporan->penulis }}</div>
            <div><strong>Paket:</strong> {{ ucfirst($laporan->paket) }}</div>
            <div><strong>Status Pembayaran:</strong> {{ ucfirst($laporan->status_pembayaran) }}</div>
            <div><strong>Tanggal:</strong> {{ $laporan->tanggal }}</div>
            <div><strong>Invoice:</strong> #{{ $laporan->invoice }}</div>
            <div>
                <strong>Bukti Pembayaran:</strong><br>
                @if($laporan->bukti_pembayaran)
                    <img src="{{ asset('storage/bukti/' . $laporan->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="rounded border w-64 mt-2">
                @else
                    <p class="italic text-gray-400">Gambar tidak tersedia atau belum diunggah.</p>
                @endif
            </div>
        </div>

        <div class="mt-6 text-right">
            <a href="{{ route('penjualanIndividu.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection
