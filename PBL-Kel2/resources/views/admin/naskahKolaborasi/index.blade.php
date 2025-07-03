@extends('admin.layouts.app')

@section('main')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Manajemen Naskah Kolaborasi</h1>

            <!-- Statistik Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-blue-500 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $statistik['total'] }}</h3>
                            <p class="text-sm opacity-90">Total Naskah</p>
                        </div>
                        <i class="fas fa-file-alt text-2xl opacity-75"></i>
                    </div>
                </div>

                <div class="bg-yellow-500 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $statistik['perlu_review'] }}</h3>
                            <p class="text-sm opacity-90">Perlu Review</p>
                        </div>
                        <i class="fas fa-clock text-2xl opacity-75"></i>
                    </div>
                </div>

                <div class="bg-orange-500 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $statistik['revisi'] }}</h3>
                            <p class="text-sm opacity-90">Perlu Revisi</p>
                        </div>
                        <i class="fas fa-edit text-2xl opacity-75"></i>
                    </div>
                </div>

                <div class="bg-green-500 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $statistik['selesai'] }}</h3>
                            <p class="text-sm opacity-90">Selesai</p>
                        </div>
                        <i class="fas fa-check-circle text-2xl opacity-75"></i>
                    </div>
                </div>

                <div class="bg-red-500 text-white p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $statistik['ditolak'] }}</h3>
                            <p class="text-sm opacity-90">Ditolak</p>
                        </div>
                        <i class="fas fa-times-circle text-2xl opacity-75"></i>
                    </div>
                </div>
            </div>

            <!-- Filter Form -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                <form method="GET" action="{{ route('naskahKolaborasi.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Cari</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="w-full px-3 py-2 border rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                placeholder="Judul, penulis, nomor...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Status</label>
                            <select name="status"
                                class="w-full px-3 py-2 border rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                <option value="">Semua Status</option>
                                <option value="sudah_kirim" {{ request('status') == 'sudah_kirim' ? 'selected' : '' }}>
                                    Perlu Review
                                </option>
                                <option value="revisi" {{ request('status') == 'revisi' ? 'selected' : '' }}>
                                    Perlu Revisi
                                </option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>
                                    Disetujui
                                </option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>
                                    Selesai
                                </option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>
                                    Ditolak
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                                class="w-full px-3 py-2 border rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                                class="w-full px-3 py-2 border rounded-md dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                <i class="fas fa-search mr-1"></i>Filter
                            </button>
                            <a href="{{ route('naskahKolaborasi.index') }}"
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tombol Tambah -->
            <div class="mb-4">
                <a href="{{ route('naskahKolaborasi.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    <i class="fas fa-plus mr-2"></i>Tambah Naskah
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

            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border dark:text-white">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="p-3 text-left">Pesanan</th>
                            <th class="p-3 text-left">Buku & Bab</th>
                            <th class="p-3 text-left">Penulis</th>
                            <th class="p-3 text-left">Naskah</th>
                            <th class="p-3 text-left">Upload</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporans as $laporan)
                            <tr class="border-t dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="p-3">
                                    <div>
                                        <strong class="text-blue-600">{{ $laporan->nomor_pesanan }}</strong>
                                        <br><small class="text-gray-500">ID: {{ $laporan->id }}</small>
                                    </div>
                                </td>
                                <td class="p-3">
                                    <div>
                                        <strong>{{ $laporan->bukuKolaboratif->judul ?? 'Judul Tidak Tersedia' }}</strong>
                                        <br><small class="text-gray-500">
                                            Bab {{ $laporan->babBuku->nomor_bab ?? '-' }}:
                                            {{ $laporan->babBuku->judul_bab ?? 'Judul Bab Tidak Tersedia' }}
                                        </small>
                                        @if($laporan->babBuku && $laporan->babBuku->tingkat_kesulitan)
                                                                <br><span
                                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                                                                                                            {{ $laporan->babBuku->tingkat_kesulitan === 'mudah' ? 'bg-green-100 text-green-800' :
                                            ($laporan->babBuku->tingkat_kesulitan === 'sedang' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                                    {{ ucfirst($laporan->babBuku->tingkat_kesulitan) }}
                                                                </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-3">
                                    <div>
                                        {{ $laporan->user->name ?? 'Penulis Tidak Ditemukan' }}
                                        @if($laporan->user)
                                            <br><small class="text-gray-500">{{ $laporan->user->email }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-3">
                                    <div>
                                        <strong>{{ $laporan->judul_naskah ?? 'Belum Ada Judul' }}</strong>
                                        @if($laporan->jumlah_kata)
                                            <br><small class="text-gray-500">{{ $laporan->jumlah_kata_formatted }} kata</small>
                                        @endif
                                        @if($laporan->deskripsi_naskah)
                                            <br><small
                                                class="text-gray-500">{{ Str::limit($laporan->deskripsi_naskah, 50) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-3">
                                    <div>
                                        @if($laporan->tanggal_upload_naskah)
                                            {{ $laporan->tanggal_upload_naskah->format('d/m/Y') }}
                                            <br><small
                                                class="text-gray-500">{{ $laporan->tanggal_upload_naskah->format('H:i') }}</small>
                                        @else
                                            <span class="text-gray-400">Belum diupload</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-3">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $laporan->status_penulisan_badge }}">
                                        {{ $laporan->status_penulisan_text }}
                                    </span>
                                    @if($laporan->tanggal_feedback)
                                        <br><small class="text-gray-500">
                                            Feedback: {{ $laporan->tanggal_feedback->format('d/m/Y') }}
                                        </small>
                                    @endif
                                </td>
                                <td class="p-3">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <!-- Tombol Lihat Detail -->
                                        <a href="{{ route('naskahKolaborasi.show', $laporan->id) }}"
                                            class="inline-flex items-center px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye text-xs mr-1"></i>
                                            <span class="hidden sm:inline">Lihat</span>
                                        </a>

                                        <!-- Tombol Edit -->
                                        <a href="{{ route('naskahKolaborasi.edit', $laporan->id) }}"
                                            class="inline-flex items-center px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600"
                                            title="Edit Naskah">
                                            <i class="fas fa-edit text-xs mr-1"></i>
                                            <span class="hidden sm:inline">Edit</span>
                                        </a>

                                        <!-- Tombol Download Naskah -->
                                        @if($laporan->file_naskah)
                                            <a href="{{ route('naskahKolaborasi.download', $laporan->id) }}"
                                                class="inline-flex items-center px-2 py-1 bg-purple-500 text-white text-xs rounded hover:bg-purple-600"
                                                title="Download Naskah">
                                                <i class="fas fa-download text-xs mr-1"></i>
                                                <span class="hidden sm:inline">Download</span>
                                            </a>
                                        @endif

                                        <!-- Tombol Aksi Review (hanya untuk status sudah_kirim) -->
                                        @if($laporan->status_penulisan === 'sudah_kirim')
                                            <div class="flex gap-1 flex-wrap">
                                                <!-- Terima -->
                                                <button type="button"
                                                    class="inline-flex items-center px-2 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600"
                                                    title="Terima Naskah" onclick="openAcceptModal({{ $laporan->id }})">
                                                    <i class="fas fa-check text-xs mr-1"></i>
                                                    <span class="hidden sm:inline">Terima</span>
                                                </button>

                                                <!-- Revisi -->
                                                <button type="button"
                                                    class="inline-flex items-center px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600"
                                                    title="Minta Revisi" onclick="openRevisionModal({{ $laporan->id }})">
                                                    <i class="fas fa-edit text-xs mr-1"></i>
                                                    <span class="hidden sm:inline">Revisi</span>
                                                </button>

                                                <!-- Tolak -->
                                                <button type="button"
                                                    class="inline-flex items-center px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600"
                                                    title="Tolak Naskah" onclick="openRejectModal({{ $laporan->id }})">
                                                    <i class="fas fa-times text-xs mr-1"></i>
                                                    <span class="hidden sm:inline">Tolak</span>
                                                </button>
                                            </div>
                                        @endif

                                        <!-- Tombol Delete -->
                                        <form action="{{ route('naskahKolaborasi.destroy', $laporan->id) }}" method="POST"
                                            class="inline" onsubmit="return confirm('Yakin ingin menghapus naskah ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600"
                                                title="Hapus Naskah">
                                                <i class="fas fa-trash text-xs mr-1"></i>
                                                <span class="hidden sm:inline">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 dark:text-gray-400 py-8">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-file-alt text-4xl mb-2 opacity-50"></i>
                                        <p>Belum ada naskah yang diupload.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($laporans->hasPages())
                <div class="mt-6">
                    {{ $laporans->appends(request()->query())->links() }}
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
                        <label for="catatan_persetujuan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
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
                        <label for="feedback_editor_revision"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
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
                        <label for="feedback_editor_reject"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
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
            document.getElementById('acceptForm').action = `/admin/naskah-kolaborasi/${id}/terima`;
            document.getElementById('acceptModal').classList.remove('hidden');
        }

        function closeAcceptModal() {
            document.getElementById('acceptModal').classList.add('hidden');
            document.getElementById('catatan_persetujuan').value = '';
        }

        function openRevisionModal(id) {
            document.getElementById('revisionForm').action = `/admin/naskah-kolaborasi/${id}/revisi`;
            document.getElementById('revisionModal').classList.remove('hidden');
        }

        function closeRevisionModal() {
            document.getElementById('revisionModal').classList.add('hidden');
            document.getElementById('feedback_editor_revision').value = '';
        }

        function openRejectModal(id) {
            document.getElementById('rejectForm').action = `/admin/naskah-kolaborasi/${id}/tolak`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('feedback_editor_reject').value = '';
        }

        // Close modals when clicking outside
        document.addEventListener('click', function (e) {
            if (e.target.id === 'acceptModal') closeAcceptModal();
            if (e.target.id === 'revisionModal') closeRevisionModal();
            if (e.target.id === 'rejectModal') closeRejectModal();
        });

        // ESC key to close modals
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeAcceptModal();
                closeRevisionModal();
                closeRejectModal();
            }
        });
    </script>
@endsection