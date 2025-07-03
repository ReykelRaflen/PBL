@extends('admin.layouts.app')

@section('main')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Detail Naskah Kolaborasi</h1>
            <a href="{{ route('penerbitanKolaborasi.index') }}" 
               class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-1"></i>Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4 dark:bg-green-700 dark:text-white">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4 dark:bg-red-700 dark:text-white">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Info Pesanan -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">
                    <i class="fas fa-shopping-cart mr-2"></i>Informasi Pesanan
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Nomor Pesanan:</label>
                        <p class="text-blue-600 font-semibold">{{ $laporan->nomor_pesanan }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Tanggal Pesanan:</label>
                        <p>{{ $laporan->tanggal_pesanan->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Jumlah Bayar:</label>
                        <p class="text-green-600 font-semibold">{{ $laporan->jumlah_bayar_formatted }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Status Pembayaran:</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $laporan->status_badge }}">
                            {{ $laporan->status_pembayaran_text }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Info Penulis -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">
                    <i class="fas fa-user mr-2"></i>Informasi Penulis
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Nama:</label>
                        <p>{{ $laporan->user->name ?? 'Tidak Ditemukan' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Email:</label>
                        <p>{{ $laporan->user->email ?? 'Tidak Ditemukan' }}</p>
                    </div>
                    @if($laporan->user && $laporan->user->phone)
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Telepon:</label>
                        <p>{{ $laporan->user->phone }}</p>
                    </div>
                    @endif
                    @if($laporan->catatan)
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Catatan Pesanan:</label>
                        <p class="text-sm bg-white dark:bg-gray-600 p-2 rounded">{{ $laporan->catatan }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Info Buku & Bab -->
        <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">
                <i class="fas fa-book mr-2"></i>Informasi Buku & Bab
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Judul Buku:</label>
                    <p class="font-semibold">{{ $laporan->bukuKolaboratif->judul ?? 'Tidak Ditemukan' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Bab:</label>
                    <p>Bab {{ $laporan->babBuku->nomor_bab ?? '-' }}: {{ $laporan->babBuku->judul_bab ?? 'Tidak Ditemukan' }}</p>
                </div>
                @if($laporan->babBuku && $laporan->babBuku->deskripsi)
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Deskripsi Bab:</label>
                    <p class="text-sm bg-white dark:bg-gray-600 p-2 rounded">{{ $laporan->babBuku->deskripsi }}</p>
                </div>
                @endif
                @if($laporan->babBuku && $laporan->babBuku->tingkat_kesulitan)
                <div>
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Tingkat Kesulitan:</label>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                        {{ $laporan->babBuku->tingkat_kesulitan === 'mudah' ? 'bg-green-100 text-green-800' : 
                           ($laporan->babBuku->tingkat_kesulitan === 'sedang' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($laporan->babBuku->tingkat_kesulitan) }}
                    </span>
                </div>
                @endif
            </div>
        </div>

        <!-- Info Naskah -->
        @if($laporan->file_naskah)
        <div class="mt-6 bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">
                <i class="fas fa-file-alt mr-2"></i>Informasi Naskah
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Judul Naskah:</label>
                    <p class="font-semibold">{{ $laporan->judul_naskah ?? 'Tidak Ada Judul' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Tanggal Upload:</label>
                    <p>{{ $laporan->tanggal_upload_naskah ? $laporan->tanggal_upload_naskah->format('d M Y H:i') : 'Belum Diupload' }}</p>
                </div>
                @if($laporan->jumlah_kata)
                <div>
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Jumlah Kata:</label>
                    <p>{{ number_format($laporan->jumlah_kata) }} kata</p>
                </div>
                @endif
                <div>
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Status Penulisan:</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $laporan->status_penulisan_badge }}">
                        {{ $laporan->status_penulisan_text }}
                    </span>
                </div>
                @if($laporan->deskripsi_naskah)
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Deskripsi Naskah:</label>
                    <p class="text-sm bg-white dark:bg-gray-600 p-2 rounded">{{ $laporan->deskripsi_naskah }}</p>
                </div>
                @endif
                @if($laporan->catatan_penulis)
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Catatan Penulis:</label>
                    <p class="text-sm bg-white dark:bg-gray-600 p-2 rounded">{{ $laporan->catatan_penulis }}</p>
                </div>
                @endif
            </div>

            <!-- Download Button -->
            <div class="mt-4">
                <a href="{{ route('penerbitanKolaborasi.download', $laporan->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                    <i class="fas fa-download mr-2"></i>Download Naskah
                </a>
            </div>
        </div>
        @endif

        <!-- Feedback History -->
        @if($laporan->feedback_editor || $laporan->catatan_persetujuan)
        <div class="mt-6 bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">
                <i class="fas fa-comments mr-2"></i>Riwayat Feedback
            </h3>
            
            @if($laporan->feedback_editor)
            <div class="mb-4 p-3 bg-white dark:bg-gray-600 rounded border-l-4 border-yellow-400">
                <div class="flex justify-between items-start mb-2">
                    <strong class="text-yellow-700 dark:text-yellow-300">
                        @if($laporan->status_penulisan === 'revisi')
                            <i class="fas fa-edit mr-1"></i>Feedback Revisi
                        @elseif($laporan->status_penulisan === 'ditolak')
                            <i class="fas fa-times-circle mr-1"></i>Alasan Penolakan
                        @else
                            <i class="fas fa-comment mr-1"></i>Feedback Editor
                        @endif
                    </strong>
                    @if($laporan->tanggal_feedback)
                        <small class="text-gray-500">{{ $laporan->tanggal_feedback->format('d M Y H:i') }}</small>
                    @endif
                </div>
                <p class="text-gray-700 dark:text-gray-300">{{ $laporan->feedback_editor }}</p>
            </div>
            @endif

            @if($laporan->catatan_persetujuan)
            <div class="p-3 bg-white dark:bg-gray-600 rounded border-l-4 border-green-400">
                <div class="flex justify-between items-start mb-2">
                    <strong class="text-green-700 dark:text-green-300">
                        <i class="fas fa-check-circle mr-1"></i>Catatan Persetujuan
                    </strong>
                    @if($laporan->tanggal_disetujui)
                        <small class="text-gray-500">{{ $laporan->tanggal_disetujui->format('d M Y H:i') }}</small>
                    @endif
                </div>
                <p class="text-gray-700 dark:text-gray-300">{{ $laporan->catatan_persetujuan }}</p>
            </div>
            @endif
        </div>
        @endif

        <!-- Action Buttons -->
        @if($laporan->status_penulisan === 'sudah_kirim' && $laporan->file_naskah)
        <div class="mt-6 bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">
                <i class="fas fa-tasks mr-2"></i>Aksi Review
            </h3>
            <div class="flex flex-wrap gap-3">
                <!-- Terima Naskah -->
                <button type="button" onclick="openAcceptModal({{ $laporan->id }})"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    <i class="fas fa-check mr-2"></i>Terima Naskah
                </button>

                <!-- Minta Revisi -->
                <button type="button" onclick="openRevisionModal({{ $laporan->id }})"
                    class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                    <i class="fas fa-edit mr-2"></i>Minta Revisi
                </button>

                <!-- Tolak Naskah -->
                <button type="button" onclick="openRejectModal({{ $laporan->id }})"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    <i class="fas fa-times mr-2"></i>Tolak Naskah
                </button>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Terima Naskah -->
<div id="acceptModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Terima Naskah</h3>
            <form id="acceptForm" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="catatan_persetujuan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Catatan Persetujuan (Opsional)
                    </label>
                    <textarea name="catatan_persetujuan" id="catatan_persetujuan" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Catatan untuk penulis (opsional)..."></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeAcceptModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        <i class="fas fa-check mr-1"></i>Terima Naskah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Revisi Naskah -->
<div id="revisionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Minta Revisi</h3>
            <form id="revisionForm" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="feedback_editor_revision" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Feedback untuk Revisi *
                    </label>
                    <textarea name="feedback_editor" id="feedback_editor_revision" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Jelaskan apa yang perlu direvisi..." required></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeRevisionModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                        <i class="fas fa-edit mr-1"></i>Kirim Revisi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tolak Naskah -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Tolak Naskah</h3>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="feedback_editor_reject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Alasan Penolakan *
                    </label>
                    <textarea name="feedback_editor" id="feedback_editor_reject" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Jelaskan alasan penolakan naskah..." required></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                        <i class="fas fa-times mr-1"></i>Tolak Naskah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAcceptModal(id) {
    document.getElementById('acceptForm').action = `/admin/penerbitan-kolaborasi/${id}/terima`;
    document.getElementById('acceptModal').classList.remove('hidden');
}

function closeAcceptModal() {
    document.getElementById('acceptModal').classList.add('hidden');
    document.getElementById('catatan_persetujuan').value = '';
}

function openRevisionModal(id) {
    document.getElementById('revisionForm').action = `/admin/penerbitan-kolaborasi/${id}/revisi`;
    document.getElementById('revisionModal').classList.remove('hidden');
}

function closeRevisionModal() {
    document.getElementById('revisionModal').classList.add('hidden');
    document.getElementById('feedback_editor_revision').value = '';
}

function openRejectModal(id) {
    document.getElementById('rejectForm').action = `/admin/penerbitan-kolaborasi/${id}/tolak`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('feedback_editor_reject').value = '';
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'acceptModal') closeAcceptModal();
    if (e.target.id === 'revisionModal') closeRevisionModal();
    if (e.target.id === 'rejectModal') closeRejectModal();
});

// ESC key to close modals
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAcceptModal();
        closeRevisionModal();
        closeRejectModal();
    }
});
</script>
@endsection
