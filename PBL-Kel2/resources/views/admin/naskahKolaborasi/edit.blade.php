@extends('admin.layouts.app')

@section('main')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Edit Naskah Kolaborasi</h1>
            <a href="{{ route('naskahKolaborasi.index') }}" 
               class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-1"></i>Kembali
            </a>
        </div>

        @if(session('error'))
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4 dark:bg-red-700 dark:text-white">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('naskahKolaborasi.update', $naskah->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-4">
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Penulis *
                        </label>
                        <select name="user_id" id="user_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                            <option value="">Pilih Penulis</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $naskah->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="buku_kolaboratif_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Buku Kolaboratif *
                        </label>
                        <select name="buku_kolaboratif_id" id="buku_kolaboratif_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                            <option value="">Pilih Buku</option>
                            @foreach($bukuKolaboratif as $buku)
                                <option value="{{ $buku->id }}" {{ old('buku_kolaboratif_id', $naskah->buku_kolaboratif_id) == $buku->id ? 'selected' : '' }}>
                                    {{ $buku->judul }}
                                </option>
                            @endforeach
                        </select>
                        @error('buku_kolaboratif_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bab_buku_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Bab Buku *
                        </label>
                        <select name="bab_buku_id" id="bab_buku_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                            <option value="">Pilih Bab</option>
                            @if($naskah->babBuku)
                                <option value="{{ $naskah->babBuku->id }}" selected>
                                    Bab {{ $naskah->babBuku->nomor_bab }}: {{ $naskah->babBuku->judul_bab }}
                                </option>
                            @endif
                        </select>
                        @error('bab_buku_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nomor_pesanan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nomor Pesanan *
                        </label>
                        <input type="text" name="nomor_pesanan" id="nomor_pesanan" value="{{ old('nomor_pesanan', $naskah->nomor_pesanan) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                               placeholder="Masukkan nomor pesanan">
                        @error('nomor_pesanan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status_penulisan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status Penulisan *
                        </label>
                        <select name="status_penulisan" id="status_penulisan" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                            <option value="">Pilih Status</option>
                            <option value="belum_mulai" {{ old('status_penulisan', $naskah->status_penulisan) == 'belum_mulai' ? 'selected' : '' }}>Belum Mulai</option>
                            <option value="dapat_mulai" {{ old('status_penulisan', $naskah->status_penulisan) == 'dapat_mulai' ? 'selected' : '' }}>Dapat Mulai</option>
                            <option value="sedang_ditulis" {{ old('status_penulisan', $naskah->status_penulisan) == 'sedang_ditulis' ? 'selected' : '' }}>Sedang Ditulis</option>
                            <option value="sudah_kirim" {{ old('status_penulisan', $naskah->status_penulisan) == 'sudah_kirim' ? 'selected' : '' }}>Sudah Dikirim</option>
                            <option value="revisi" {{ old('status_penulisan', $naskah->status_penulisan) == 'revisi' ? 'selected' : '' }}>Perlu Revisi</option>
                            <option value="disetujui" {{ old('status_penulisan', $naskah->status_penulisan) == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="selesai" {{ old('status_penulisan', $naskah->status_penulisan) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ old('status_penulisan', $naskah->status_penulisan) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        @error('status_penulisan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tanggal Deadline
                        </label>
                        <input type="date" name="tanggal_deadline" id="tanggal_deadline" 
                               value="{{ old('tanggal_deadline', $naskah->tanggal_deadline ? $naskah->tanggal_deadline->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        @error('tanggal_deadline')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-4">
                    <div>
                        <label for="judul_naskah" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Judul Naskah *
                        </label>
                        <input type="text" name="judul_naskah" id="judul_naskah" value="{{ old('judul_naskah', $naskah->judul_naskah) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                               placeholder="Masukkan judul naskah">
                        @error('judul_naskah')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="deskripsi_naskah" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Deskripsi Naskah
                        </label>
                        <textarea name="deskripsi_naskah" id="deskripsi_naskah" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                  placeholder="Deskripsi singkat tentang naskah">{{ old('deskripsi_naskah', $naskah->deskripsi_naskah) }}</textarea>
                        @error('deskripsi_naskah')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="file_naskah" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            File Naskah
                        </label>
                        @if($naskah->file_naskah)
                            <div class="mb-2 p-2 bg-blue-50 dark:bg-blue-900 rounded">
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    <i class="fas fa-file mr-1"></i>File saat ini: {{ basename($naskah->file_naskah) }}
                                </p>
                                <a href="{{ route('naskahKolaborasi.download', $naskah->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-download mr-1"></i>Download
                                </a>
                            </div>
                        @endif
                        <input type="file" name="file_naskah" id="file_naskah" accept=".pdf,.doc,.docx"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                                              <small class="text-gray-500">Format: PDF, DOC, DOCX. Maksimal 10MB. Kosongkan jika tidak ingin mengubah file.</small>
                        @error('file_naskah')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jumlah_kata" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Jumlah Kata
                        </label>
                        <input type="number" name="jumlah_kata" id="jumlah_kata" value="{{ old('jumlah_kata', $naskah->jumlah_kata) }}" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                               placeholder="Jumlah kata dalam naskah">
                        @error('jumlah_kata')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="catatan_penulis" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan Penulis
                        </label>
                        <textarea name="catatan_penulis" id="catatan_penulis" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                  placeholder="Catatan dari penulis">{{ old('catatan_penulis', $naskah->catatan_penulis) }}</textarea>
                        @error('catatan_penulis')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan Admin
                        </label>
                        <textarea name="catatan" id="catatan" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                  placeholder="Catatan dari admin">{{ old('catatan', $naskah->catatan) }}</textarea>
                        @error('catatan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Feedback Section (jika ada) -->
            @if($naskah->feedback_editor || $naskah->catatan_persetujuan)
            <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg">
                <h3 class="text-lg font-semibold mb-3 text-gray-800 dark:text-white">
                    <i class="fas fa-comments mr-2"></i>Riwayat Feedback
                </h3>
                
                @if($naskah->feedback_editor)
                <div class="mb-3 p-3 bg-white dark:bg-gray-600 rounded border-l-4 border-yellow-400">
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

            <div class="flex items-center justify-end mt-6 pt-6 border-t">
                <a href="{{ route('naskahKolaborasi.index') }}" 
                   class="px-6 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 mr-3">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    <i class="fas fa-save mr-1"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Load bab berdasarkan buku yang dipilih
document.getElementById('buku_kolaboratif_id').addEventListener('change', function() {
    const bukuId = this.value;
    const babSelect = document.getElementById('bab_buku_id');
    const currentBabId = '{{ $naskah->bab_buku_id }}';
    
    // Clear existing options except current one
    babSelect.innerHTML = '<option value="">Loading...</option>';
    
    if (bukuId) {
        // Fetch bab data
        fetch(`/admin/api/buku-kolaboratif/${bukuId}/bab`)
            .then(response => response.json())
            .then(data => {
                babSelect.innerHTML = '<option value="">Pilih Bab</option>';
                
                if (data.error) {
                    babSelect.innerHTML = '<option value="">Error: ' + data.error + '</option>';
                    return;
                }
                
                data.forEach(bab => {
                    const option = document.createElement('option');
                    option.value = bab.id;
                    option.textContent = `Bab ${bab.nomor_bab}: ${bab.judul_bab}`;
                    
                    if (bab.id == currentBabId) {
                        option.selected = true;
                    }
                    
                    // Disable jika status tidak tersedia (kecuali yang sedang dipilih)
                    if (bab.status !== 'tersedia' && bab.id != currentBabId) {
                        option.disabled = true;
                        option.textContent += ` (${bab.status_text || bab.status})`;
                    }
                    
                    babSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                babSelect.innerHTML = '<option value="">Error loading bab</option>';
            });
    } else {
        babSelect.innerHTML = '<option value="">Pilih Bab</option>';
    }
});

// Load initial bab options if buku is already selected
document.addEventListener('DOMContentLoaded', function() {
    const bukuId = document.getElementById('buku_kolaboratif_id').value;
    if (bukuId) {
        document.getElementById('buku_kolaboratif_id').dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
