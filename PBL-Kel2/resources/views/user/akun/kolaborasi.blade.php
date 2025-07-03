@extends('user.akun.layouts')

@section('content')
<div class="content-area">
    <h4 class="page-title">
        <i class="fas fa-users me-2"></i>Kolaborasi Saya
    </h4>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($pesananKolaborasi->isEmpty())
        <div class="card text-center py-5">
            <div class="card-body">
                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada Kolaborasi</h5>
                <p class="text-muted">Anda belum memiliki proyek kolaborasi buku.</p>
                <a href="{{ route('buku-kolaboratif.index') }}" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Cari Proyek Kolaborasi
                </a>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($pesananKolaborasi as $pesanan)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <small class="text-muted">{{ $pesanan->nomor_pesanan }}</small>
                        <span class="badge {{ $pesanan->status_pembayaran_badge }}">
                            {{ $pesanan->status_pembayaran_text }}
                        </span>
                    </div>
                    
                    <div class="card-body">
                        <h6 class="card-title">{{ $pesanan->bukuKolaboratif->judul ?? 'Judul Tidak Tersedia' }}</h6>
                        <p class="card-text">
                            <strong>Bab:</strong> {{ $pesanan->babBuku->judul_bab ?? 'Bab ' . ($pesanan->babBuku->nomor_bab ?? '-') }}<br>
                            <strong>Harga:</strong> {{ $pesanan->jumlah_bayar_formatted }}<br>
                            <strong>Status Penulisan:</strong> 
                            <span class="badge {{ $pesanan->status_penulisan_badge }}">
                                {{ $pesanan->status_penulisan_text }}
                            </span>
                        </p>

                        @if($pesanan->tanggal_pesanan)
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                Dipesan: {{ $pesanan->tanggal_pesanan->format('d M Y H:i') }}
                            </small>
                        @endif
                    </div>

                    <div class="card-footer bg-transparent">
                        <div class="d-flex flex-wrap gap-2">
                            <!-- Tombol Upload Naskah -->
                            @if($pesanan->canUploadNaskah())
                                <button type="button" class="btn btn-primary btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#uploadModal{{ $pesanan->id }}">
                                    <i class="fas fa-upload me-1"></i>
                                    @if($pesanan->status_penulisan === 'revisi')
                                        Upload Revisi
                                    @else
                                        Upload Naskah
                                    @endif
                                </button>
                            @endif

                            <!-- Tombol Download Naskah -->
                            @if($pesanan->hasNaskah())
                                <a href="{{ route('akun.kolaborasi.download-naskah', $pesanan->id) }}" 
                                   class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-download me-1"></i>Download
                                </a>
                            @endif

                            <!-- Tombol Detail -->
                            <a href="{{ route('buku-kolaboratif.status-pesanan', $pesanan->id) }}" 
                               class="btn btn-outline-info btn-sm">
                                <i class="fas fa-eye me-1"></i>Detail
                            </a>
                        </div>

                        <!-- Progress Bar untuk Status -->
                        <div class="mt-3">
                            @php
                                $progress = match($pesanan->status_penulisan) {
                                    'belum_mulai' => 10,
                                    'dapat_mulai' => 25,
                                    'sedang_proses' => 50,
                                    'sudah_kirim' => 75,
                                    'revisi' => 60,
                                    'selesai', 'disetujui' => 100,
                                    'ditolak' => 0,
                                    default => 0
                                };
                                $progressColor = match($pesanan->status_penulisan) {
                                    'selesai', 'disetujui' => 'success',
                                    'ditolak' => 'danger',
                                    'revisi' => 'warning',
                                    'sudah_kirim' => 'info',
                                    default => 'primary'
                                };
                            @endphp
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-{{ $progressColor }}" 
                                     role="progressbar" 
                                     style="width: {{ $progress }}%">
                                </div>
                            </div>
                        </div>

                        <!-- Feedback Editor -->
                        @if($pesanan->feedback_editor)
                            <div class="mt-3">
                                <div class="alert alert-{{ $pesanan->status_penulisan === 'revisi' ? 'warning' : 'info' }} alert-sm">
                                    <strong>
                                        @if($pesanan->status_penulisan === 'revisi')
                                            <i class="fas fa-edit me-1"></i>Feedback Revisi:
                                        @else
                                            <i class="fas fa-comment me-1"></i>Feedback Editor:
                                        @endif
                                    </strong>
                                    <p class="mb-0 mt-1">{{ $pesanan->feedback_editor }}</p>
                                    @if($pesanan->tanggal_feedback)
                                        <small class="text-muted">
                                            {{ $pesanan->tanggal_feedback->format('d M Y H:i') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Modal Upload Naskah -->
            @if($pesanan->canUploadNaskah())
            <div class="modal fade" id="uploadModal{{ $pesanan->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('akun.kolaborasi.upload-naskah', $pesanan->id) }}" 
                              method="POST" 
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    @if($pesanan->status_penulisan === 'revisi')
                                        <i class="fas fa-edit me-2"></i>Upload Revisi Naskah
                                    @else
                                        <i class="fas fa-upload me-2"></i>Upload Naskah
                                    @endif
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="judul_naskah{{ $pesanan->id }}" class="form-label">
                                        <i class="fas fa-heading me-1"></i>Judul Naskah *
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="judul_naskah{{ $pesanan->id }}" 
                                           name="judul_naskah" 
                                           value="{{ old('judul_naskah', $pesanan->judul_naskah) }}" 
                                           required
                                           placeholder="Masukkan judul naskah">
                                </div>

                                <div class="mb-3">
                                    <label for="file_naskah{{ $pesanan->id }}" class="form-label">
                                        <i class="fas fa-file me-1"></i>File Naskah *
                                    </label>
                                    <input type="file" 
                                           class="form-control" 
                                           id="file_naskah{{ $pesanan->id }}" 
                                           name="file_naskah" 
                                           accept=".doc,.docx,.pdf" 
                                           required>
                                    <div class="form-text">
                                        Format: DOC, DOCX, PDF. Maksimal 10MB.
                                        @if($pesanan->hasNaskah())
                                            <br><strong>File saat ini:</strong> {{ basename($pesanan->file_naskah) }}
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="deskripsi_naskah{{ $pesanan->id }}" class="form-label">
                                        <i class="fas fa-align-left me-1"></i>Deskripsi Naskah
                                    </label>
                                    <textarea class="form-control" 
                                              id="deskripsi_naskah{{ $pesanan->id }}" 
                                              name="deskripsi_naskah" 
                                              rows="3"
                                              placeholder="Deskripsi singkat tentang naskah">{{ old('deskripsi_naskah', $pesanan->deskripsi_naskah) }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="jumlah_kata{{ $pesanan->id }}" class="form-label">
                                                <i class="fas fa-calculator me-1"></i>Jumlah Kata
                                            </label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="jumlah_kata{{ $pesanan->id }}" 
                                                   name="jumlah_kata" 
                                                   value="{{ old('jumlah_kata', $pesanan->jumlah_kata) }}" 
                                                   min="500"
                                                   placeholder="Minimal 500 kata">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="catatan_penulis{{ $pesanan->id }}" class="form-label">
                                        <i class="fas fa-sticky-note me-1"></i>Catatan untuk Editor
                                    </label>
                                    <textarea class="form-control" 
                                              id="catatan_penulis{{ $pesanan->id }}" 
                                              name="catatan_penulis" 
                                              rows="3"
                                              placeholder="Catatan tambahan untuk editor (opsional)">{{ old('catatan_penulis', $pesanan->catatan_penulis) }}</textarea>
                                </div>

                                @if($pesanan->status_penulisan === 'revisi' && $pesanan->feedback_editor)
                                    <div class="alert alert-warning">
                                        <strong><i class="fas fa-exclamation-triangle me-1"></i>Feedback Revisi:</strong>
                                        <p class="mb-0 mt-2">{{ $pesanan->feedback_editor }}</p>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload me-1"></i>
                                    @if($pesanan->status_penulisan === 'revisi')
                                        Upload Revisi
                                    @else
                                        Upload Naskah
                                    @endif
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>

        <!-- Pagination jika diperlukan -->
        @if($pesananKolaborasi->count() > 12)
            <div class="d-flex justify-content-center mt-4">
                <!-- Add pagination if needed -->
            </div>
        @endif
    @endif
</div>

<style>
.alert-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.progress {
    border-radius: 10px;
}

.badge {
    font-size: 0.75em;
}

.btn-sm {
    font-size: 0.8rem;
}

@media (max-width: 576px) {
    .card-footer .d-flex {
        flex-direction: column;
    }
    
    .card-footer .btn {
        width: 100%;
        margin-bottom: 0.25rem;
    }
}
</style>
@endsection
