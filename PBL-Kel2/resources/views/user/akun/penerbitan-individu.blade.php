@extends('user.akun.layouts')

@section('content')
<div class="content-area">
    <h4 class="page-title">
        <i class="fas fa-book-open me-2"></i>Penerbitan Individu
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

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $penerbitanList = \App\Models\PenerbitanIndividu::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
    @endphp

    @if($penerbitanList->count() > 0)
        <div class="row">
            @foreach($penerbitanList as $penerbitan)
                <div class="col-md-6 mb-4">
                    <div class="card h-100 
                        @if($penerbitan->status_pembayaran == 'menunggu' || $penerbitan->status_pembayaran == 'ditolak') border-warning
                        @elseif($penerbitan->status_pembayaran == 'pending') border-info
                        @elseif($penerbitan->status_pembayaran == 'lunas') border-success
                        @elseif($penerbitan->status_pembayaran == 'dibatalkan') border-secondary
                        @else border-light @endif shadow-sm">
                        
                        <div class="card-header 
                            @if($penerbitan->status_pembayaran == 'menunggu' || $penerbitan->status_pembayaran == 'ditolak') bg-warning text-dark
                            @elseif($penerbitan->status_pembayaran == 'pending') bg-info text-white
                            @elseif($penerbitan->status_pembayaran == 'lunas') bg-success text-white
                            @elseif($penerbitan->status_pembayaran == 'dibatalkan') bg-secondary text-white
                            @else bg-light @endif">
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-receipt me-2"></i>
                                    {{ $penerbitan->nomor_pesanan }}
                                </h6>
                                <span class="badge 
                                    @if($penerbitan->status_pembayaran == 'menunggu') bg-dark
                                    @elseif($penerbitan->status_pembayaran == 'pending') bg-light text-dark
                                    @elseif($penerbitan->status_pembayaran == 'lunas') bg-light text-dark
                                    @elseif($penerbitan->status_pembayaran == 'ditolak') bg-light text-dark
                                    @elseif($penerbitan->status_pembayaran == 'dibatalkan') bg-light text-dark
                                    @else bg-secondary @endif">
                                    @if($penerbitan->status_pembayaran == 'menunggu')
                                        Menunggu Pembayaran
                                    @elseif($penerbitan->status_pembayaran == 'pending')
                                        Menunggu Verifikasi
                                    @elseif($penerbitan->status_pembayaran == 'lunas')
                                        Pembayaran Lunas
                                    @elseif($penerbitan->status_pembayaran == 'ditolak')
                                        Pembayaran Ditolak
                                    @elseif($penerbitan->status_pembayaran == 'dibatalkan')
                                        Dibatalkan
                                    @else
                                        {{ ucfirst($penerbitan->status_pembayaran) }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Informasi Dasar -->
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Tanggal Pesanan</small>
                                    <strong>{{ $penerbitan->created_at->format('d M Y') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $penerbitan->created_at->format('H:i') }} WIB</small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Paket</small>
                                    <span class="badge 
                                        @if($penerbitan->paket == 'silver') bg-secondary
                                        @elseif($penerbitan->paket == 'gold') bg-warning text-dark
                                        @elseif($penerbitan->paket == 'diamond') bg-info
                                        @else bg-primary @endif px-2 py-1">
                                        <i class="fas 
                                            @if($penerbitan->paket == 'silver') fa-medal
                                            @elseif($penerbitan->paket == 'gold') fa-trophy
                                            @elseif($penerbitan->paket == 'diamond') fa-gem
                                            @else fa-box @endif me-1"></i>
                                        {{ ucfirst($penerbitan->paket) }}
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <small class="text-muted d-block">Harga Paket</small>
                                    <h5 class="text-primary mb-0">Rp {{ number_format($penerbitan->harga_paket, 0, ',', '.') }}</h5>
                                </div>
                            </div>

                            @if($penerbitan->judul_buku)
                                <div class="mb-3">
                                    <small class="text-muted d-block">Judul Buku</small>
                                    <strong>{{ $penerbitan->judul_buku }}</strong>
                                </div>
                            @endif

                            @if($penerbitan->nama_penulis)
                                <div class="mb-3">
                                    <small class="text-muted d-block">Nama Penulis</small>
                                    <strong>{{ $penerbitan->nama_penulis }}</strong>
                                </div>
                            @endif

                            <!-- Progress Bar -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">Progress Penerbitan</small>
                                    <small class="text-muted">
                                        @php
                                            $progress = 0;
                                            if($penerbitan->status_pembayaran == 'menunggu') {
                                                $progress = 20;
                                            } elseif($penerbitan->status_pembayaran == 'pending') {
                                                $progress = 40;
                                            } elseif($penerbitan->status_pembayaran == 'lunas') {
                                                if($penerbitan->status_penerbitan == 'dapat_mulai') {
                                                    $progress = 60;
                                                } elseif($penerbitan->status_penerbitan == 'sudah_kirim') {
                                                    $progress = 80;
                                                } elseif($penerbitan->status_penerbitan == 'dalam_proses') {
                                                    $progress = 90;
                                                } elseif($penerbitan->status_penerbitan == 'selesai') {
                                                    $progress = 100;
                                                }
                                            } elseif($penerbitan->status_pembayaran == 'dibatalkan') {
                                                $progress = 0;
                                            }
                                        @endphp
                                        {{ $progress }}%
                                    </small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar 
                                        @if($penerbitan->status_pembayaran == 'dibatalkan') bg-secondary
                                        @elseif($progress == 100) bg-success
                                        @else bg-primary @endif" 
                                         role="progressbar" 
                                         style="width: {{ $progress }}%" 
                                         aria-valuenow="{{ $progress }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>

                            <!-- Status Penerbitan -->
                            @if($penerbitan->status_pembayaran == 'lunas')
                                <div class="alert alert-light border-success mb-3">
                                    <small class="text-muted d-block">Status Penerbitan</small>
                                    <strong class="text-success">
                                        @if($penerbitan->status_penerbitan == 'dapat_mulai')
                                            <i class="fas fa-upload me-1"></i>Siap Upload Naskah
                                        @elseif($penerbitan->status_penerbitan == 'sudah_kirim')
                                            <i class="fas fa-check me-1"></i>Naskah Sudah Dikirim
                                        @elseif($penerbitan->status_penerbitan == 'revisi')
                                            <i class="fas fa-edit me-1"></i>Perlu Revisi
                                        @elseif($penerbitan->status_penerbitan == 'dalam_proses')
                                            <i class="fas fa-cogs me-1"></i>Dalam Proses
                                        @elseif($penerbitan->status_penerbitan == 'selesai')
                                            <i class="fas fa-check-circle me-1"></i>Selesai
                                        @else
                                            {{ ucfirst($penerbitan->status_penerbitan) }}
                                        @endif
                                    </strong>
                                </div>
                            @endif

                                                       <!-- Catatan Revisi -->
                            @if($penerbitan->status_penerbitan == 'revisi' && $penerbitan->catatan_revisi)
                                <div class="alert alert-warning">
                                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Catatan Revisi:</h6>
                                    <p class="mb-0 small">{{ $penerbitan->catatan_revisi }}</p>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 flex-wrap">
                                @if($penerbitan->status_pembayaran == 'menunggu')
                                    <a href="{{ route('penerbitan-individu.pembayaran', $penerbitan->id) }}" 
                                       class="btn btn-warning btn-sm">
                                        <i class="fas fa-credit-card me-1"></i>
                                        Bayar Sekarang
                                    </a>
                                @elseif($penerbitan->status_pembayaran == 'pending')
                                    <button class="btn btn-info btn-sm" disabled>
                                        <i class="fas fa-clock me-1"></i>
                                        Menunggu Verifikasi
                                    </button>
                                @elseif($penerbitan->status_pembayaran == 'lunas')
                                    @if($penerbitan->status_penerbitan == 'dapat_mulai')
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#uploadNaskahModal{{ $penerbitan->id }}">
                                            <i class="fas fa-upload me-1"></i>
                                            Upload Naskah
                                        </button>
                                    @elseif($penerbitan->status_penerbitan == 'sudah_kirim')
                                        <button class="btn btn-primary btn-sm" disabled>
                                            <i class="fas fa-check me-1"></i>
                                            Naskah Terkirim
                                        </button>
                                        @if($penerbitan->file_naskah)
                                            <a href="{{ route('penerbitan-individu.download-naskah', $penerbitan->id) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-download me-1"></i>
                                                Download
                                            </a>
                                        @endif
                                    @elseif($penerbitan->status_penerbitan == 'revisi')
                                        <button type="button" 
                                                class="btn btn-warning btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#uploadNaskahModal{{ $penerbitan->id }}">
                                            <i class="fas fa-edit me-1"></i>
                                            Upload Revisi
                                        </button>
                                    @elseif($penerbitan->status_penerbitan == 'dalam_proses')
                                        <button class="btn btn-info btn-sm" disabled>
                                            <i class="fas fa-cogs me-1"></i>
                                            Dalam Proses
                                        </button>
                                    @elseif($penerbitan->status_penerbitan == 'selesai')
                                        <button class="btn btn-success btn-sm" disabled>
                                            <i class="fas fa-check-circle me-1"></i>
                                            Selesai
                                        </button>
                                    @endif
                                @elseif($penerbitan->status_pembayaran == 'ditolak')
                                    <a href="{{ route('penerbitan-individu.pembayaran', $penerbitan->id) }}" 
                                       class="btn btn-danger btn-sm">
                                        <i class="fas fa-redo me-1"></i>
                                        Upload Ulang
                                    </a>
                                @endif

                                <a href="{{ route('penerbitan-individu.status', $penerbitan->id) }}" 
                                   class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-eye me-1"></i>
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Upload Naskah -->
                @if($penerbitan->status_pembayaran == 'lunas' && in_array($penerbitan->status_penerbitan, ['dapat_mulai', 'revisi']))
                    <div class="modal fade" id="uploadNaskahModal{{ $penerbitan->id }}" tabindex="-1" 
                         aria-labelledby="uploadNaskahModalLabel{{ $penerbitan->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title" id="uploadNaskahModalLabel{{ $penerbitan->id }}">
                                        <i class="fas fa-upload me-2"></i>
                                        {{ $penerbitan->status_penerbitan == 'revisi' ? 'Upload Revisi Naskah' : 'Upload Naskah' }}
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('penerbitan-individu.submit-pengajuan', $penerbitan->id) }}" 
                                      method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        @if($penerbitan->status_penerbitan == 'revisi' && $penerbitan->catatan_revisi)
                                            <div class="alert alert-warning">
                                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Catatan Revisi:</h6>
                                                <p class="mb-0">{{ $penerbitan->catatan_revisi }}</p>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="judul_buku{{ $penerbitan->id }}" class="form-label">
                                                    <i class="fas fa-book me-1 text-success"></i>
                                                    Judul Buku *
                                                </label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="judul_buku{{ $penerbitan->id }}" 
                                                       name="judul_buku" 
                                                       value="{{ old('judul_buku', $penerbitan->judul_buku) }}" 
                                                       required
                                                       placeholder="Masukkan judul buku">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="nama_penulis{{ $penerbitan->id }}" class="form-label">
                                                    <i class="fas fa-user-edit me-1 text-success"></i>
                                                    Nama Penulis *
                                                </label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="nama_penulis{{ $penerbitan->id }}" 
                                                       name="nama_penulis" 
                                                       value="{{ old('nama_penulis', $penerbitan->nama_penulis ?? auth()->user()->name) }}" 
                                                       required
                                                       placeholder="Masukkan nama penulis">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="deskripsi_singkat{{ $penerbitan->id }}" class="form-label">
                                                <i class="fas fa-align-left me-1 text-success"></i>
                                                Deskripsi Singkat Buku *
                                            </label>
                                            <textarea class="form-control" 
                                                      id="deskripsi_singkat{{ $penerbitan->id }}" 
                                                      name="deskripsi_singkat" 
                                                      rows="4" 
                                                      required
                                                      placeholder="Masukkan deskripsi singkat tentang buku Anda (maksimal 1000 karakter)">{{ old('deskripsi_singkat', $penerbitan->deskripsi_singkat) }}</textarea>
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Jelaskan secara singkat tentang isi buku, genre, dan target pembaca.
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="file_naskah{{ $penerbitan->id }}" class="form-label">
                                                <i class="fas fa-file-upload me-1 text-success"></i>
                                                Upload File Naskah *
                                            </label>
                                            <input type="file" 
                                                   class="form-control" 
                                                   id="file_naskah{{ $penerbitan->id }}" 
                                                   name="file_naskah" 
                                                   accept=".doc,.docx,.pdf" 
                                                   required>
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Format: DOC, DOCX, atau PDF. Maksimal 10MB.
                                                <strong>Pastikan naskah sudah final dan siap untuk diterbitkan!</strong>
                                            </div>
                                            @if($penerbitan->file_naskah)
                                                <div class="mt-2">
                                                    <small class="text-muted">File saat ini: </small>
                                                    <a href="{{ route('penerbitan-individu.download-naskah', $penerbitan->id) }}" 
                                                       class="text-primary">
                                                        <i class="fas fa-download me-1"></i>
                                                        {{ basename($penerbitan->file_naskah) }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="alert alert-info">
                                            <h6><i class="fas fa-info-circle me-2"></i>Informasi Penting:</h6>
                                            <ul class="mb-0 small">
                                                <li>Pastikan naskah sudah melalui proses editing dan proofreading</li>
                                                <li>Format penulisan harus konsisten dan rapi</li>
                                                <li>Sertakan daftar isi jika diperlukan</li>
                                                <li>Tim editor akan melakukan review dalam 3-5 hari kerja</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i>Batal
                                        </button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-upload me-1"></i>
                                            {{ $penerbitan->status_penerbitan == 'revisi' ? 'Upload Revisi' : 'Upload Naskah' }}
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
        @if($penerbitanList->count() > 6)
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Pagination">
                    <ul class="pagination">
                        <li class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </li>
                        <li class="page-item active">
                            <span class="page-link">1</span>
                        </li>
                        <li class="page-item disabled">
                            <span class="page-link">Next</span>
                        </li>
                    </ul>
                </nav>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-book-open text-muted" style="font-size: 4rem;"></i>
            </div>
            <h5 class="text-muted mb-3">Belum Ada Penerbitan Individu</h5>
            <p class="text-muted mb-4">
                Anda belum memiliki pesanan penerbitan individu. 
                Mulai perjalanan menerbitkan buku Anda sekarang!
            </p>
            <a href="{{ route('penerbitan-individu.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>
                Mulai Penerbitan Buku
            </a>
        </div>
    @endif
</div>

<style>
/* Custom styling untuk halaman penerbitan individu */
.content-area {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.progress {
    border-radius: 10px;
    background-color: #e9ecef;
}

.progress-bar {
    border-radius: 10px;
    transition: width 0.6s ease;
}

.badge {
    font-size: 0.75rem;
    padding: 0.5em 0.75em;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 20px;
}

.alert {
    border-radius: 10px;
    border: none;
}

.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.modal-header {
    border-radius: 15px 15px 0 0;
    border-bottom: none;
}

.modal-footer {
    border-radius: 0 0 15px 15px;
    border-top: 1px solid #dee2e6;
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

/* Button hover effects */
.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Timeline progress animation */
.progress-bar {
    background: linear-gradient(45deg, #007bff, #0056b3);
    animation: progressShine 2s infinite;
}

@keyframes progressShine {
    0% { background-position: -100% 0; }
    100% { background-position: 100% 0; }
}

.progress-bar.bg-success {
    background: linear-gradient(45deg, #28a745, #1e7e34) !important;
}

/* Card status indicators */
.border-warning {
    border-left: 5px solid #ffc107 !important;
}

.border-info {
    border-left: 5px solid #17a2b8 !important;
}

.border-success {
    border-left: 5px solid #28a745 !important;
}

.border-secondary {
    border-left: 5px solid #6c757d !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
    
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .d-flex.gap-2 .btn {
        width: 100%;
    }
}

/* Empty state styling */
.text-center.py-5 {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    margin: 2rem 0;
}

/* Status badge animations */
.badge {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* File upload styling */
.form-control[type="file"] {
    padding: 0.75rem;
    border: 2px dashed #28a745;
    background-color: #f8f9fa;
    border-radius: 10px;
}

.form-control[type="file"]:focus {
    border-color: #28a745;
    background-color: #e8f5e8;
}

/* Alert styling improvements */
.alert-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-left: 4px solid #ffc107;
}

.alert-info {
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
    border-left: 4px solid #17a2b8;
}

.alert-light {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #dee2e6;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // File upload validation
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(function(input) {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
                const allowedTypes = ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/pdf'];
                
                // Validasi tipe file
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tipe File Tidak Didukung',
                        text: 'Hanya file DOC, DOCX, atau PDF yang diperbolehkan.',
                        confirmButtonColor: '#28a745'
                    });
                    input.value = '';
                    return;
                }
                
                // Validasi ukuran file (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Terlalu Besar',
                        text: 'Ukuran file maksimal 10MB. Silakan pilih file yang lebih kecil.',
                        confirmButtonColor: '#28a745'
                    });
                    input.value = '';
                    return;
                }
                
                // Update label dengan info file
                const label = document.querySelector(`label[for="${input.id}"]`);
                if (label) {
                    label.innerHTML = `
                        <i class="fas fa-file-upload me-1 text-success"></i>
                        File Terpilih: ${fileName} (${fileSize} MB)
                    `;
                }
            }
        });
    });
    
    // Form submission confirmation
    const uploadForms = document.querySelectorAll('form[action*="submit-pengajuan"]');
    
    uploadForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Konfirmasi Upload Naskah',
                text: 'Pastikan semua data sudah benar. Naskah akan dikirim ke tim editor untuk review.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Upload Naskah',
                cancelButtonText: 'Periksa Lagi'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Mengupload Naskah...',
                        text: 'Mohon tunggu, file sedang diupload.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    form.submit();
                }
            });
        });
    });
    
    // Auto-refresh progress bars
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(function(bar) {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
    
    // Tooltip initialization
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Function to copy order number
function copyOrderNumber(orderNumber) {
    navigator.clipboard.writeText(orderNumber).then(function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Disalin!',
            text: `Nomor pesanan ${orderNumber} telah disalin ke clipboard.`,
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    });
}
</script>
@endsection

