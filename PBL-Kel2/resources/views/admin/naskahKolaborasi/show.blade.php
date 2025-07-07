@extends('admin.layouts.app')

@section('main')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Detail Naskah Kolaborasi</h1>
                <div class="flex gap-2">
                    <a href="{{ route('naskahKolaborasi.edit', $naskah->id) }}"
                        class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <a href="{{ route('naskahKolaborasi.index') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                </div>
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
                            <p class="text-blue-600 font-semibold">{{ $naskah->nomor_pesanan }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Tanggal Pesanan:</label>
                            <p>{{ $naskah->tanggal_pesanan ? $naskah->tanggal_pesanan->format('d M Y H:i') : ($naskah->created_at ? $naskah->created_at->format('d M Y H:i') : 'Tidak tersedia') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Status Pembayaran:</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $naskah->status_pembayaran_badge ?? 'bg-green-100 text-green-800' }}">
                                {{ $naskah->status_pembayaran_text ?? 'Lunas' }}
                            </span>
                        </div>
                        @if($naskah->catatan)
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Catatan Admin:</label>
                                <p class="text-sm bg-white dark:bg-gray-600 p-2 rounded">{{ $naskah->catatan }}</p>
                            </div>
                        @endif
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
                            <p>{{ $naskah->user->name ?? 'Tidak Ditemukan' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Email:</label>
                            <p>{{ $naskah->user->email ?? 'Tidak Ditemukan' }}</p>
                        </div>
                        @if($naskah->user && $naskah->user->phone)
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Telepon:</label>
                                <p>{{ $naskah->user->phone }}</p>
                            </div>
                        @endif
                        @if($naskah->user && $naskah->user->alamat)
                            <div>
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Alamat:</label>
                                <p class="text-sm">{{ $naskah->user->alamat }}</p>
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
                        <p class="font-semibold">{{ $naskah->bukuKolaboratif->judul ?? 'Tidak Ditemukan' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Bab:</label>
                                            <p>Bab {{ $naskah->babBuku->nomor_bab ?? '-' }}:
                            {{ $naskah->babBuku->judul_bab ?? 'Tidak Ditemukan' }}</p>
                    </div>
                    @if($naskah->babBuku && $naskah->babBuku->deskripsi)
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Deskripsi Bab:</label>
                            <p class="text-sm bg-white dark:bg-gray-600 p-2 rounded">{{ $naskah->babBuku->deskripsi }}</p>
                        </div>
                    @endif
                    @if($naskah->babBuku && $naskah->babBuku->tingkat_kesulitan)
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Tingkat Kesulitan:</label>
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                {{ $naskah->babBuku->tingkat_kesulitan === 'mudah' ? 'bg-green-100 text-green-800' :
                                   ($naskah->babBuku->tingkat_kesulitan === 'sedang' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($naskah->babBuku->tingkat_kesulitan) }}
                            </span>
                        </div>
                    @endif
                    @if($naskah->tanggal_deadline)
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Deadline:</label>
                            <p class="text-red-600 font-semibold">{{ $naskah->tanggal_deadline->format('d M Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Info Naskah -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">
                    <i class="fas fa-file-alt mr-2"></i>Informasi Naskah
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Judul Naskah:</label>
                        <p class="font-semibold">{{ $naskah->judul_naskah ?? 'Tidak Ada Judul' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Status Penulisan:</label>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $naskah->status_penulisan_badge }}">
                            {{ $naskah->status_penulisan_text }}
                        </span>
                    </div>
                    @if($naskah->tanggal_upload_naskah)
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Tanggal Upload:</label>
                            <p>{{ $naskah->tanggal_upload_naskah->format('d M Y H:i') }}</p>
                        </div>
                    @endif
                    @if($naskah->jumlah_kata)
                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Jumlah Kata:</label>
                            <p>{{ number_format($naskah->jumlah_kata, 0, ',', '.') }} kata</p>
                        </div>
                    @endif
                    @if($naskah->deskripsi_naskah)
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Deskripsi Naskah:</label>
                            <p class="text-sm bg-white dark:bg-gray-600 p-2 rounded">{{ $naskah->deskripsi_naskah }}</p>
                        </div>
                    @endif
                    @if($naskah->catatan_penulis)
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Catatan Penulis:</label>
                            <p class="text-sm bg-white dark:bg-gray-600 p-2 rounded">{{ $naskah->catatan_penulis }}</p>
                        </div>
                    @endif
                </div>

                <!-- Download Button -->
                @if($naskah->file_naskah)
                    <div class="mt-4">
                        <a href="{{ route('naskahKolaborasi.download', $naskah->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                            <i class="fas fa-download mr-2"></i>Download Naskah
                        </a>
                    </div>
                @else
                    <div class="mt-4 p-3 bg-yellow-100 dark:bg-yellow-800 rounded">
                        <p class="text-yellow-800 dark:text-yellow-200">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Naskah belum diupload
                        </p>
                    </div>
                @endif
            </div>

            <!-- Feedback History -->
            @if($naskah->feedback_editor || $naskah->catatan_persetujuan)
                <div class="mt-6 bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">
                        <i class="fas fa-comments mr-2"></i>Riwayat Feedback
                    </h3>

                    @if($naskah->feedback_editor)
                        <div class="mb-4 p-3 bg-white dark:bg-gray-600 rounded border-l-4 border-yellow-400">
                            <div class="flex justify-between items-start mb-2">
                                <strong class="text-yellow-700 dark:text-yellow-300">
                                    @if($naskah->status_penulisan === 'revisi')
                                        <i class="fas fa-edit mr-1"></i>Feedback Revisi
                                    @elseif($naskah->status_penulisan === 'ditolak')
                                        <i class="fas fa-times-circle mr-1"></i>Alasan Penolakan
                                    @else
                                        <i class="fas fa-comment mr-1"></i>Feedback Editor
                                    @endif
                                </strong>
                                @if($naskah->tanggal_feedback)
                                    <small class="text-gray-500">{{ $naskah->tanggal_feedback->format('d M Y H:i') }}</small>
                                @endif
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">{{ $naskah->feedback_editor }}</p>
                        </div>
                    @endif

                    @if($naskah->catatan_persetujuan)
                        <div class="p-3 bg-white dark:bg-gray-600 rounded border-l-4 border-green-400">
                            <div class="flex justify-between items-start mb-2">
                                <strong class="text-green-700 dark:text-green-300">
                                    <i class="fas fa-check-circle mr-1"></i>Catatan Persetujuan
                                </strong>
                                @if($naskah->tanggal_disetujui)
                                    <small class="text-gray-500">{{ $naskah->tanggal_disetujui->format('d M Y H:i') }}</small>
                                @endif
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">{{ $naskah->catatan_persetujuan }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Timeline Status -->
            <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">
                    <i class="fas fa-history mr-2"></i>Timeline Status
                </h3>
                <div class="space-y-3">
                    @if($naskah->created_at)
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="font-medium">Naskah Dibuat</p>
                                <p class="text-sm text-gray-500">{{ $naskah->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($naskah->tanggal_upload_naskah)
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                            <div>
                                <p class="font-medium">Naskah Diupload</p>
                                <p class="text-sm text-gray-500">{{ $naskah->tanggal_upload_naskah->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($naskah->tanggal_feedback)
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                            <div>
                                <p class="font-medium">Feedback Diberikan</p>
                                <p class="text-sm text-gray-500">{{ $naskah->tanggal_feedback->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($naskah->tanggal_disetujui)
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <div>
                                <p class="font-medium">Naskah Disetujui</p>
                                <p class="text-sm text-gray-500">{{ $naskah->tanggal_disetujui->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($naskah->updated_at && $naskah->updated_at != $naskah->created_at)
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gray-500 rounded-full mr-3"></div>
                            <div>
                                <p class="font-medium">Terakhir Diupdate</p>
                                <p class="text-sm text-gray-500">{{ $naskah->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            @if($naskah->status_penulisan === 'sudah_kirim' && $naskah->file_naskah)
                <div class="mt-6 bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">
                        <i class="fas fa-tasks mr-2"></i>Aksi Review
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        <!-- Terima Naskah -->
                        <button type="button" onclick="openAcceptModal({{ $naskah->id }})"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            <i class="fas fa-check mr-2"></i>Terima Naskah
                        </button>

                        <!-- Minta Revisi -->
                        <button type="button" onclick="openRevisionModal({{ $naskah->id }})"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                            <i class="fas fa-edit mr-2"></i>Minta Revisi
                        </button>

                        <!-- Tolak Naskah -->
                        <button type="button" onclick="openRejectModal({{ $naskah->id }})"
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            <i class="fas fa-times mr-2"></i>Tolak Naskah
                        </button>
                    </div>
                </div>
            @endif

            <!-- Delete Button (Admin Only) -->
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">Zona Berbahaya</h4>
                        <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                    <button type="button" onclick="confirmDelete({{ $naskah->id }})"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        <i class="fas fa-trash mr-1"></i>Hapus Naskah
                    </button>
                </div>
            </div>
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

    <!-- Modal Konfirmasi Hapus -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Konfirmasi Hapus</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-4">
                    Apakah Anda yakin ingin menghapus naskah ini? Tindakan ini tidak dapat dibatalkan.
                </p>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeDeleteModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                            <i class="fas fa-trash mr-1"></i>Hapus
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

        function confirmDelete(id) {
            document.getElementById('deleteForm').action = `/admin/admin/naskah-kolaborasi/${id}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        document.addEventListener('click', function (e) {
            if (e.target.id === 'acceptModal') closeAcceptModal();
            if (e.target.id === 'revisionModal') closeRevisionModal();
            if (e.target.id === 'rejectModal') closeRejectModal();
            if (e.target.id === 'deleteModal') closeDeleteModal();
        });

        // ESC key to close modals
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeAcceptModal();
                closeRevisionModal();
                closeRejectModal();
                closeDeleteModal();
            }
        });
    </script>
@endsection
