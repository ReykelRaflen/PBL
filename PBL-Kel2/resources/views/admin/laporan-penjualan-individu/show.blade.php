@extends('admin.layouts.app')

@section('main')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Detail Laporan - Invoice #{{ $laporan->invoice }}</h1>
            <a href="{{ route('admin.laporan-penjualan-individu.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 dark:bg-green-800 dark:text-green-100">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 dark:bg-red-800 dark:text-red-100">
            {{ session('error') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Detail Laporan -->
            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Informasi Laporan</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Invoice</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $laporan->invoice }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Judul Buku</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $laporan->judul }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Penulis</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $laporan->penulis }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Paket</dt>
                        <dd>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $laporan->paket_badge }} text-white">
                                {{ ucfirst($laporan->paket) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $laporan->harga_formatted }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $laporan->tanggal->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                                               <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran</dt>
                        <dd>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $laporan->status_badge }} text-white">
                                {{ $laporan->status_pembayaran_text }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Data Penerbitan Terkait -->
            @if($penerbitan)
            <div class="bg-blue-50 dark:bg-blue-900 p-6 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Data Penerbitan Terkait</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Pesanan</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $penerbitan->nomor_pesanan }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pemesan</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $penerbitan->user->name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $penerbitan->user->email ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran</dt>
                        <dd>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $penerbitan->status_pembayaran_badge ?? 'bg-gray-500' }} text-white">
                                {{ $penerbitan->status_pembayaran_text ?? ucfirst($penerbitan->status_pembayaran) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Penerbitan</dt>
                        <dd>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $penerbitan->status_penerbitan_badge ?? 'bg-gray-500' }} text-white">
                                {{ $penerbitan->status_penerbitan_text ?? ucfirst(str_replace('_', ' ', $penerbitan->status_penerbitan)) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Harga Paket</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-100">
                            Rp {{ number_format($penerbitan->harga_paket, 0, ',', '.') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Pesanan</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $penerbitan->tanggal_pesanan->format('d/m/Y H:i') }}</dd>
                    </div>
                    @if($penerbitan->tanggal_bayar)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Bayar</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $penerbitan->tanggal_bayar->format('d/m/Y H:i') }}</dd>
                    </div>
                    @endif
                    @if($penerbitan->tanggal_verifikasi)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Verifikasi</dt>
                        <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $penerbitan->tanggal_verifikasi->format('d/m/Y H:i') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
            @else
            <div class="bg-yellow-50 dark:bg-yellow-900 p-6 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-yellow-800 dark:text-yellow-200">Peringatan</h3>
                <p class="text-sm text-yellow-700 dark:text-yellow-300">
                    Data penerbitan terkait tidak ditemukan. Laporan ini mungkin dibuat secara manual.
                </p>
            </div>
            @endif
        </div>

        <!-- Bukti Pembayaran -->
        @if($laporan->bukti_pembayaran)
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-4">Bukti Pembayaran</h3>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">File: {{ basename($laporan->bukti_pembayaran) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Path: {{ $laporan->bukti_pembayaran }}</p>
                    </div>
                    <div class="flex space-x-2">
                        @if($laporan->bukti_pembayaran_url)
                        <a href="{{ $laporan->bukti_pembayaran_url }}" target="_blank"
                           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Lihat
                        </a>
                        @endif
                        <a href="{{ route('admin.laporan-penjualan-individu.download', $laporan->id) }}" 
                           class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Catatan Admin -->
        @if($penerbitan && $penerbitan->catatan_admin)
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-4">Catatan Admin</h3>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $penerbitan->catatan_admin }}</p>
                @if($penerbitan->admin)
                <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                    Oleh: {{ $penerbitan->admin->name }} pada {{ $penerbitan->tanggal_verifikasi->format('d/m/Y H:i') }}
                </p>
                @endif
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="mt-6 flex flex-wrap gap-3">
            @if($laporan->isMenungguVerifikasi())
            <button onclick="openSetujuiModal({{ $laporan->id }}, '{{ $laporan->invoice }}')"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Setujui Pembayaran
            </button>

            <button onclick="openTolakModal({{ $laporan->id }}, '{{ $laporan->invoice }}')"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Tolak Pembayaran
            </button>
            @endif

            <a href="{{ route('admin.laporan-penjualan-individu.edit', $laporan->id) }}" 
               class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm rounded-md hover:bg-yellow-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Laporan
            </a>
        </div>
    </div>
</div>

<!-- Modal Setujui -->
<div id="setujuiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Setujui Pembayaran</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Yakin ingin menyetujui pembayaran untuk invoice <span id="setujuiInvoice" class="font-semibold"></span>?
                </p>
                <form id="setujuiForm" method="POST" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label for="setujuiCatatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea name="catatan_admin" id="setujuiCatatan" rows="3" 
                                  class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                  placeholder="Catatan tambahan..."></textarea>
                    </div>
                    <div class="flex justify-center space-x-3">
                        <button type="button" onclick="closeSetujuiModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div id="tolakModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Tolak Pembayaran</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Yakin ingin menolak pembayaran untuk invoice <span id="tolakInvoice" class="font-semibold"></span>?
                </p>
                <form id="tolakForm" method="POST" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label for="tolakCatatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="catatan_admin" id="tolakCatatan" rows="3" required
                                  class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                  placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>
                    <div class="flex justify-center space-x-3">
                        <button type="button" onclick="closeTolakModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openSetujuiModal(id, invoice) {
    document.getElementById('setujuiInvoice').textContent = invoice;
    document.getElementById('setujuiForm').action = `/admin/laporan-penjualan-individu/${id}/setujui`;
    document.getElementById('setujuiModal').classList.remove('hidden');
}

function closeSetujuiModal() {
    document.getElementById('setujuiModal').classList.add('hidden');
    document.getElementById('setujuiCatatan').value = '';
}

function openTolakModal(id, invoice) {
    document.getElementById('tolakInvoice').textContent = invoice;
    document.getElementById('tolakForm').action = `/admin/laporan-penjualan-individu/${id}/tolak`;
    document.getElementById('tolakModal').classList.remove('hidden');
}

function closeTolakModal() {
    document.getElementById('tolakModal').classList.add('hidden');
    document.getElementById('tolakCatatan').value = '';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const setujuiModal = document.getElementById('setujuiModal');
    const tolakModal = document.getElementById('tolakModal');
    
    if (event.target == setujuiModal) {
        closeSetujuiModal();
    }
    if (event.target == tolakModal) {
        closeTolakModal();
    }
}
</script>
@endsection
