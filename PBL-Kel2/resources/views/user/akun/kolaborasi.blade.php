@extends('user.akun.layouts')

@section('content')
<div class="content-area">
    <h4 class="page-title">
        <i class="fas fa-book-open me-2"></i>Kolaborasi Saya
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

    <!-- Filter Status -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <label for="statusFilter" class="form-label">Filter Status:</label>
                    <select class="form-select" id="statusFilter" onchange="filterByStatus()">
                        <option value="">Semua Status</option>
                        <option value="belum_mulai">Belum Mulai</option>
                        <option value="dapat_mulai">Dapat Mulai</option>
                        <option value="sedang_proses">Sedang Proses</option>
                        <option value="sudah_kirim">Sudah Dikirim</option>
                        <option value="revisi">Perlu Revisi</option>
                        <option value="diterima">Diterima</option>
                        <option value="ditolak">Ditolak</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Total: {{ $pesananKolaborasi->count() }} pesanan
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Pesanan Kolaborasi -->
    <div class="row" id="kolaborasiContainer">
        @forelse($pesananKolaborasi as $pesanan)
        <div class="col-md-6 mb-4 pesanan-item" data-status="{{ $pesanan->status_penulisan }}">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center" 
                     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h6 class="mb-0 text-white">
                        <i class="fas fa-book me-2"></i>{{ $pesanan->bukuKolaboratif->judul ?? 'Judul Tidak Tersedia' }}
                    </h6>
                    <span class="badge bg-light text-dark">{{ $pesanan->nomor_pesanan }}</span>
                </div>
                
                <div class="card-body">
                    <!-- Info Bab -->
                    <div class="mb-3">
                        <h6 class="text-primary">
                            <i class="fas fa-bookmark me-1"></i>
                            Bab {{ $pesanan->babBuku->nomor_bab ?? 'N/A' }}: {{ $pesanan->babBuku->judul_bab ?? 'Judul Tidak Tersedia' }}
                        </h6>
                        <p class="text-muted small mb-0">{{ $pesanan->babBuku->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    </div>

                    <!-- Status Pembayaran -->
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">Status Pembayaran:</small>
                            <br>
                            @php
                                $badgeClass = match($pesanan->status_pembayaran) {
                                    'lunas' => 'bg-success',
                                    'pending', 'menunggu_verifikasi' => 'bg-warning',
                                    'menunggu' => 'bg-secondary',
                                    'dibatalkan', 'tidak_sesuai' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucwords(str_replace('_', ' ', $pesanan->status_pembayaran)) }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Jumlah Bayar:</small>
                            <br>
                            <strong class="text-success">Rp {{ number_format($pesanan->jumlah_bayar, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <!-- Status Penulisan -->
                    <div class="mb-3">
                        <small class="text-muted">Status Penulisan:</small>
                        <br>
                        @php
                            $statusClass = match($pesanan->status_penulisan) {
                                'diterima', 'selesai' => 'bg-success',
                                'sudah_kirim' => 'bg-info',
                                'sedang_proses', 'dapat_mulai' => 'bg-primary',
                                'revisi' => 'bg-warning',
                                'ditolak' => 'bg-danger',
                                'belum_mulai' => 'bg-secondary',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }} mb-2">
                            {{ ucwords(str_replace('_', ' ', $pesanan->status_penulisan)) }}
                        </span>
                        
                        @if($pesanan->status_penulisan === 'revisi' && $pesanan->feedback_editor)
                            <div class="alert alert-warning alert-sm mt-2">
                                <small><strong>Feedback Editor:</strong><br>{{ $pesanan->feedback_editor }}</small>
                            </div>
                        @endif

                        @if($pesanan->status_penulisan === 'ditolak' && $pesanan->feedback_editor)
                            <div class="alert alert-danger alert-sm mt-2">
                                <small><strong>Alasan Penolakan:</strong><br>{{ $pesanan->feedback_editor }}</small>
                            </div>
                        @endif

                        @if($pesanan->status_penulisan === 'diterima' && $pesanan->catatan_persetujuan)
                            <div class="alert alert-success alert-sm mt-2">
                                <small><strong>Catatan Persetujuan:</strong><br>{{ $pesanan->catatan_persetujuan }}</small>
                            </div>
                        @endif
                    </div>

                    <!-- Info Naskah -->
                    @if($pesanan->judul_naskah)
                    <div class="mb-3">
                        <small class="text-muted">Naskah:</small>
                        <br>
                        <strong>{{ $pesanan->judul_naskah }}</strong>
                        @if($pesanan->jumlah_kata)
                            <small class="text-muted">({{ number_format($pesanan->jumlah_kata) }} kata)</small>
                        @endif
                        @if($pesanan->tanggal_upload_naskah)
                            <br><small class="text-muted">Dikirim: {{ $pesanan->tanggal_upload_naskah->format('d/m/Y H:i') }}</small>
                        @endif
                    </div>
                    @endif

                    <!-- Tanggal Pesanan -->
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            Dipesan: {{ $pesanan->tanggal_pesanan->format('d/m/Y H:i') }}
                        </small>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Action Buttons -->
                        <div class="btn-group" role="group">
                            @if($pesanan->status_pembayaran === 'lunas' && in_array($pesanan->status_penulisan, ['dapat_mulai', 'sedang_proses', 'revisi']))
                                <button class="btn btn-primary btn-sm" onclick="uploadNaskah({{ $pesanan->id }})">
                                    <i class="fas fa-upload me-1"></i>Upload Naskah
                                </button>
                            @endif
                            
                            @if($pesanan->file_naskah)
                                <a href="{{ Storage::url($pesanan->file_naskah) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-download me-1"></i>Lihat Naskah
                                </a>
                            @endif
                        </div>

                        <!-- Status Progress -->
                        <div class="text-end">
                            @php
                                $progress = match($pesanan->status_penulisan) {
                                    'belum_mulai' => 0,
                                    'dapat_mulai' => 20,
                                    'sedang_proses' => 40,
                                    'sudah_kirim' => 60,
                                    'revisi' => 50,
                                    'diterima' => 80,
                                    'selesai' => 100,
                                    'ditolak' => 0,
                                    default => 0
                                };
                            @endphp
                            <div class="progress" style="width: 100px; height: 6px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%" 
                                     aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted">{{ $progress }}%</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum Ada Pesanan Kolaborasi</h5>
                    <p class="text-muted">Anda belum memiliki pesanan kolaborasi buku.</p>
                    <a href="{{ route('user.buku-kolaboratif.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Mulai Kolaborasi
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Upload Naskah -->
<div class="modal fade" id="uploadNaskahModal" tabindex="-1" aria-labelledby="uploadNaskahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadNaskahModalLabel">
                    <i class="fas fa-upload me-2"></i>Upload Naskah
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="uploadNaskahForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="judul_naskah" class="form-label">Judul Naskah *</label>
                        <input type="text" class="form-control" id="judul_naskah" name="judul_naskah" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="deskripsi_naskah" class="form-label">Deskripsi Naskah</label>
                        <textarea class="form-control" id="deskripsi_naskah" name="deskripsi_naskah" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="file_naskah" class="form-label">File Naskah *</label>
                        <input type="file" class="form-control" id="file_naskah" name="file_naskah" 
                               accept=".pdf,.doc,.docx" required>
                        <small class="text-muted">Format yang didukung: PDF, DOC, DOCX. Maksimal 10MB.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="jumlah_kata" class="form-label">Jumlah Kata (Opsional)</label>
                        <input type="number" class="form-control" id="jumlah_kata" name="jumlah_kata" min="0">
                    </div>
                    
                    <div class="mb-3">
                        <label for="catatan_penulis" class="form-label">Catatan untuk Editor</label>
                        <textarea class="form-control" id="catatan_penulis" name="catatan_penulis" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Upload Naskah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
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
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.alert-sm {
    padding: 0.5rem;
    font-size: 0.875rem;
}

.progress {
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
}

.btn-group .btn {
    margin-right: 0.25rem;
}

@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        margin-bottom: 0.25rem;
        margin-right: 0;
    }
}
</style>

<script>
function filterByStatus() {
    const filter = document.getElementById('statusFilter').value;
    const items = document.querySelectorAll('.pesanan-item');
    
    items.forEach(item => {
        if (filter === '' || item.dataset.status === filter) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function uploadNaskah(pesananId) {
    const form = document.getElementById('uploadNaskahForm');
    form.action = `/akun/kolaborasi/${pesananId}/upload-naskah`;
    
    const modal = new bootstrap.Modal(document.getElementById('uploadNaskahModal'));
    modal.show();
}

// File validation
document.getElementById('file_naskah').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Check file size (10MB limit)
        if (file.size > 10 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 10MB.');
            this.value = '';
            return;
        }
        
        // Check file type
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung. Gunakan PDF, DOC, atau DOCX.');
            this.value = '';
            return;
        }
    }
});
</script>
@endsection
