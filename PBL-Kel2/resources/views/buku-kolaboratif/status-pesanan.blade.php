@extends('user.layouts.app')

@section('title', 'Status Pesanan')

@section('content')
<div class="container mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('buku-kolaboratif.index') }}">Buku Kolaboratif</a></li>
            <li class="breadcrumb-item active" aria-current="page">Status Pesanan</li>
        </ol>
    </nav>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>Status Pesanan
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Informasi Pesanan -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-primary">Informasi Pesanan</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nomor Pesanan:</strong></td>
                                    <td>{{ $pesananBuku->nomor_pesanan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Pesanan:</strong></td>
                                    <td>{{ $pesananBuku->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status Pembayaran:</strong></td>
                                    <td>
                                        @php
                                            $statusPembayaran = $pesananBuku->status_pembayaran ?? 'menunggu';
                                            $badgeClass = match($statusPembayaran) {
                                                'lunas' => 'bg-success',
                                                'pending', 'menunggu_verifikasi' => 'bg-warning',
                                                'menunggu', 'menunggu_pembayaran' => 'bg-info',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $statusPembayaran)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status Penulisan:</strong></td>
                                    <td>
                                        @php
                                            $statusPenulisan = $pesananBuku->status_penulisan ?? 'belum_mulai';
                                            $badgeClass = match($statusPenulisan) {
                                                'selesai', 'disetujui' => 'bg-success',
                                                'sedang_proses', 'dapat_mulai' => 'bg-info',
                                                'sudah_kirim' => 'bg-warning',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ str_replace('_', ' ', ucfirst($statusPenulisan)) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Detail Buku</h6>
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $pesananBuku->bukuKolaboratif->judul }}</h6>
                                    <p class="card-text">
                                        <strong>Bab {{ $pesananBuku->babBuku->nomor_bab }}:</strong> {{ $pesananBuku->babBuku->judul_bab ?? 'Judul Bab' }}
                                    </p>
                                    <p class="card-text">
                                        <strong>Jumlah Bayar:</strong>
                                        <span class="text-primary">Rp {{ number_format($pesananBuku->jumlah_bayar ?? $pesananBuku->total_harga ?? 0, 0, ',', '.') }}</span>
                                    </p>
                                    <p class="card-text">
                                        <strong>Tanggal Pesan:</strong> {{ $pesananBuku->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Timeline -->
                    <div class="mb-4">
                        <h6 class="text-primary">Progress Pesanan</h6>
                        <div class="row">
                            <div class="col-12">
                                <div class="progress-timeline">
                                    <!-- Pesanan Dibuat -->
                                    <div class="timeline-item completed">
                                        <div class="timeline-marker bg-success">
                                            <i class="fas fa-shopping-cart text-white"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Pesanan Dibuat</h6>
                                            <small>{{ $pesananBuku->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </div>

                                    <!-- Pembayaran -->
                                    @php
                                        $statusPembayaran = $pesananBuku->status_pembayaran ?? 'menunggu';
                                        $isPaymentCompleted = in_array($statusPembayaran, ['lunas', 'verified']);
                                        $isPaymentActive = in_array($statusPembayaran, ['pending', 'menunggu_verifikasi']);
                                    @endphp
                                    <div class="timeline-item {{ $isPaymentCompleted ? 'completed' : ($isPaymentActive ? 'active' : '') }}">
                                        <div class="timeline-marker {{ $isPaymentCompleted ? 'bg-success' : ($isPaymentActive ? 'bg-warning' : 'bg-light') }}">
                                            <i class="fas fa-{{ $isPaymentCompleted ? 'check' : ($isPaymentActive ? 'clock' : 'credit-card') }} text-white"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Pembayaran</h6>
                                            @if($isPaymentCompleted)
                                                <small>Pembayaran terverifikasi</small>
                                            @elseif($isPaymentActive)
                                                <small>{{ $statusPembayaran === 'pending' ? 'Menunggu verifikasi admin' : 'Sedang diproses' }}</small>
                                            @else
                                                <small>Menunggu pembayaran</small>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Penulisan -->
                                    @php
                                        $statusPenulisan = $pesananBuku->status_penulisan ?? 'belum_mulai';
                                        $isWritingCompleted = in_array($statusPenulisan, ['selesai', 'disetujui']);
                                        $isWritingActive = in_array($statusPenulisan, ['dapat_mulai', 'sedang_proses']);
                                    @endphp
                                    <div class="timeline-item {{ $isWritingCompleted ? 'completed' : ($isWritingActive ? 'active' : '') }}">
                                        <div class="timeline-marker {{ $isWritingCompleted ? 'bg-success' : ($isWritingActive ? 'bg-info' : 'bg-light') }}">
                                            <i class="fas fa-pen text-white"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Penulisan</h6>
                                            <small>{{ str_replace('_', ' ', ucfirst($statusPenulisan)) }}</small>
                                        </div>
                                    </div>

                                    <!-- Selesai -->
                                    <div class="timeline-item {{ $statusPenulisan === 'disetujui' ? 'completed' : '' }}">
                                        <div class="timeline-marker {{ $statusPenulisan === 'disetujui' ? 'bg-success' : 'bg-light' }}">
                                            <i class="fas fa-trophy text-white"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Selesai</h6>
                                            @if($statusPenulisan === 'disetujui')
                                                <small>Bab telah disetujui</small>
                                            @else
                                                <small>Menunggu penyelesaian</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Naskah Section -->
                    @if($statusPembayaran === 'lunas' && in_array($statusPenulisan, ['dapat_mulai', 'sedang_proses']))
                        <div class="mb-4">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-upload me-2"></i>Upload Naskah Bab
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if($pesananBuku->file_naskah)
                                        <!-- Jika sudah ada naskah -->
                                        <div class="alert alert-info">
                                            <h6><i class="fas fa-file-alt me-2"></i>Naskah Sudah Diupload</h6>
                                            <p class="mb-2">File: <strong>{{ basename($pesananBuku->file_naskah) }}</strong></p>
                                            <p class="mb-2">Diupload: {{ $pesananBuku->tanggal_upload_naskah ? $pesananBuku->tanggal_upload_naskah->format('d/m/Y H:i') : '-' }}</p>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('buku-kolaboratif.download-naskah', $pesananBuku->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-download me-1"></i>Download
                                                </a>
                                                <button type="button" class="btn btn-sm btn-warning" onclick="showReplaceNaskahForm()">
                                                    <i class="fas fa-edit me-1"></i>Ganti Naskah
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Form ganti naskah (hidden by default) -->
                                        <div id="replaceNaskahForm" style="display: none;">
                                            <hr>
                                            <h6 class="text-warning">Ganti Naskah</h6>
                                            <form action="{{ route('buku-kolaboratif.upload-naskah', $pesananBuku->id) }}" 
                                                  method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="file_naskah_replace" class="form-label">File Naskah Baru</label>
                                                    <input type="file" class="form-control" id="file_naskah_replace" 
                                                           name="file_naskah" accept=".doc,.docx,.pdf" required>
                                                    <div class="form-text">Format: DOC, DOCX, PDF. Maksimal 10MB</div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="catatan_revisi" class="form-label">Catatan Revisi</label>
                                                    <textarea class="form-control" id="catatan_revisi" name="catatan_revisi" 
                                                              rows="3" placeholder="Jelaskan perubahan yang dilakukan..."></textarea>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-warning">
                                                        <i class="fas fa-upload me-1"></i>Upload Naskah Baru
                                                    </button>
                                                    <button type="button" class="btn btn-secondary" onclick="hideReplaceNaskahForm()">
                                                        Batal
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @else
                                        <!-- Form upload naskah pertama kali -->
                                        <div class="alert alert-success">
                                            <h6><i class="fas fa-info-circle me-2"></i>Siap Menulis!</h6>
                                            <p class="mb-0">Pembayaran Anda telah terverifikasi. Silakan upload naskah bab yang telah Anda tulis.</p>
                                        </div>

                                        <form action="{{ route('buku-kolaboratif.upload-naskah', $pesananBuku->id) }}" 
                                              method="POST" enctype="multipart/form-data" id="uploadNaskahForm">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="file_naskah" class="form-label">
                                                                                                       <strong>File Naskah</strong>
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="file" class="form-control" id="file_naskah" 
                                                       name="file_naskah" accept=".doc,.docx,.pdf" required>
                                                <div class="form-text">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Format yang diterima: DOC, DOCX, PDF. Maksimal ukuran file: 10MB
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="judul_naskah" class="form-label">
                                                    <strong>Judul Naskah</strong>
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" id="judul_naskah" 
                                                       name="judul_naskah" 
                                                       value="Bab {{ $pesananBuku->babBuku->nomor_bab }} - {{ $pesananBuku->babBuku->judul_bab ?? '' }}"
                                                       required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="deskripsi_naskah" class="form-label">
                                                    <strong>Deskripsi/Ringkasan</strong>
                                                </label>
                                                <textarea class="form-control" id="deskripsi_naskah" name="deskripsi_naskah" 
                                                          rows="4" placeholder="Berikan ringkasan singkat tentang isi bab yang Anda tulis..."></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="jumlah_kata" class="form-label">
                                                    <strong>Jumlah Kata (Estimasi)</strong>
                                                </label>
                                                <input type="number" class="form-control" id="jumlah_kata" 
                                                       name="jumlah_kata" min="500" placeholder="Contoh: 2500">
                                                <div class="form-text">Perkiraan jumlah kata dalam naskah</div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="catatan_penulis" class="form-label">
                                                    <strong>Catatan untuk Editor</strong>
                                                </label>
                                                <textarea class="form-control" id="catatan_penulis" name="catatan_penulis" 
                                                          rows="3" placeholder="Catatan khusus, referensi yang digunakan, atau hal lain yang perlu diketahui editor..."></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="persetujuan_upload" required>
                                                    <label class="form-check-label" for="persetujuan_upload">
                                                        Saya menyatakan bahwa naskah ini adalah karya asli saya dan tidak melanggar hak cipta pihak lain.
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-success btn-lg" id="btnUploadNaskah">
                                                    <i class="fas fa-upload me-2"></i>Upload Naskah
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Status Naskah -->
                    @if($pesananBuku->file_naskah && $statusPenulisan === 'sudah_kirim')
                        <div class="mb-4">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0">
                                        <i class="fas fa-clock me-2"></i>Status Review Naskah
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-warning">
                                        <h6><i class="fas fa-hourglass-half me-2"></i>Sedang Direview</h6>
                                        <p class="mb-0">Naskah Anda sedang dalam proses review oleh editor. Kami akan memberikan feedback dalam 3-5 hari kerja.</p>
                                    </div>
                                    
                                    @if($pesananBuku->tanggal_upload_naskah)
                                        <p><strong>Tanggal Upload:</strong> {{ $pesananBuku->tanggal_upload_naskah->format('d F Y H:i') }}</p>
                                    @endif
                                    
                                    @if($pesananBuku->catatan_penulis)
                                        <p><strong>Catatan Anda:</strong></p>
                                        <div class="bg-light p-3 rounded">{{ $pesananBuku->catatan_penulis }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Feedback Editor -->
                    @if($pesananBuku->feedback_editor && $statusPenulisan === 'revisi')
                        <div class="mb-4">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-comments me-2"></i>Feedback Editor
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-edit me-2"></i>Perlu Revisi</h6>
                                        <p class="mb-0">Editor telah memberikan feedback untuk naskah Anda. Silakan lakukan revisi sesuai saran.</p>
                                    </div>
                                    
                                    <div class="bg-light p-3 rounded mb-3">
                                        <strong>Feedback:</strong><br>
                                        {{ $pesananBuku->feedback_editor }}
                                    </div>
                                    
                                    @if($pesananBuku->tanggal_feedback)
                                        <p><strong>Tanggal Feedback:</strong> {{ $pesananBuku->tanggal_feedback->format('d F Y H:i') }}</p>
                                    @endif
                                    
                                    <button type="button" class="btn btn-primary" onclick="showRevisiForm()">
                                        <i class="fas fa-edit me-1"></i>Upload Revisi
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Naskah Disetujui -->
                    @if($statusPenulisan === 'disetujui')
                        <div class="mb-4">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-check-circle me-2"></i>Naskah Disetujui
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-success">
                                        <h6><i class="fas fa-trophy me-2"></i>Selamat!</h6>
                                        <p class="mb-0">Naskah Anda telah disetujui dan akan dimasukkan ke dalam buku kolaboratif. Terima kasih atas kontribusi Anda!</p>
                                    </div>
                                    
                                    @if($pesananBuku->tanggal_disetujui)
                                        <p><strong>Tanggal Disetujui:</strong> {{ $pesananBuku->tanggal_disetujui->format('d F Y H:i') }}</p>
                                    @endif
                                    
                                    @if($pesananBuku->catatan_persetujuan)
                                        <div class="bg-light p-3 rounded">
                                            <strong>Catatan Editor:</strong><br>
                                            {{ $pesananBuku->catatan_persetujuan }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Aksi berdasarkan status -->
                    <div class="text-center">
                        @if(in_array($statusPembayaran, ['menunggu', 'menunggu_pembayaran']))
                            <a href="{{ route('buku-kolaboratif.pembayaran', $pesananBuku->id) }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card me-2"></i>Lanjutkan Pembayaran
                            </a>
                        @elseif($statusPembayaran === 'lunas' && $statusPenulisan === 'dapat_mulai' && !$pesananBuku->file_naskah)
                            <div class="alert alert-success border-left">
                                <h6><i class="fas fa-check-circle me-2"></i>Pembayaran Berhasil!</h6>
                                <p class="mb-0">Silakan upload naskah bab yang telah Anda tulis menggunakan form di atas.</p>
                            </div>
                        @elseif(in_array($statusPembayaran, ['pending', 'menunggu_verifikasi']))
                            <div class="alert alert-warning border-left">
                                <h6><i class="fas fa-clock me-2"></i>Menunggu Verifikasi</h6>
                                <p class="mb-0">Bukti pembayaran Anda sedang diverifikasi oleh admin. Proses ini membutuhkan waktu maksimal 1x24 jam.</p>
                            </div>
                        @endif

                        <a href="{{ route('buku-kolaboratif.index') }}" class="btn btn-outline-primary ms-2">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Buku
                        </a>
                    </div>

                    @if($pesananBuku->catatan)
                        <div class="mt-4">
                            <h6>Catatan:</h6>
                            <div class="alert alert-light border-left">
                                {{ $pesananBuku->catatan }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Status Timeline -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-list-ol me-2"></i>Ringkasan Status
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <!-- Pesanan Dibuat -->
                        <div class="timeline-item completed">
                            <div class="timeline-marker bg-success">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Pesanan Dibuat</h6>
                                <small class="text-muted">{{ $pesananBuku->created_at->format('d F Y H:i') }}</small>
                            </div>
                        </div>

                        <!-- Pembayaran -->
                        <div class="timeline-item {{ $isPaymentActive ? 'active' : ($isPaymentCompleted ? 'completed' : '') }}">
                            <div class="timeline-marker {{ $isPaymentCompleted ? 'bg-success' : ($isPaymentActive ? 'bg-warning' : 'bg-light') }}">
                                <i class="fas fa-{{ $isPaymentCompleted ? 'check' : ($isPaymentActive ? 'clock' : 'circle') }} text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Pembayaran</h6>
                                <small class="text-muted">
                                    @if($isPaymentCompleted)
                                        Pembayaran terverifikasi
                                    @elseif($isPaymentActive)
                                        Sedang diverifikasi admin
                                    @else
                                        Silakan lakukan pembayaran
                                    @endif
                                </small>
                            </div>
                        </div>

                        <!-- Penulisan -->
                        <div class="timeline-item {{ $isWritingActive ? 'active' : ($isWritingCompleted ? 'completed' : '') }}">
                            <div class="timeline-marker {{ $isWritingCompleted ? 'bg-success' : ($isWritingActive ? 'bg-info' : 'bg-light') }}">
                                <i class="fas fa-{{ $isWritingCompleted ? 'check' : ($isWritingActive ? 'pen' : 'circle') }} text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Penulisan</h6>
                                <small class="text-muted">
                                    @if($statusPenulisan === 'dapat_mulai')
                                        Dapat mulai menulis
                                    @elseif($statusPenulisan === 'sedang_proses')
                                        Sedang dalam penulisan
                                    @elseif($statusPenulisan === 'sudah_kirim')
                                        Naskah sudah dikirim
                                    @elseif($statusPenulisan === 'disetujui')
                                        Naskah disetujui
                                    @else
                                        Menunggu verifikasi pembayaran
                                    @endif
                                </small>
                            </div>
                        </div>

                        <!-- Selesai -->
                        <div class="timeline-item {{ $statusPenulisan === 'disetujui' ? 'completed' : '' }}">
                            <div class="timeline-marker {{ $statusPenulisan === 'disetujui' ? 'bg-success' : 'bg-light' }}">
                                <i class="fas fa-{{ $statusPenulisan === 'disetujui' ? 'trophy' : 'circle' }} text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Selesai</h6>
                                                               <small class="text-muted">
                                    @if($statusPenulisan === 'disetujui')
                                        Proyek selesai
                                    @else
                                        Menunggu penyelesaian
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panduan Upload -->
            @if($statusPembayaran === 'lunas' && in_array($statusPenulisan, ['dapat_mulai', 'sedang_proses']))
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-lightbulb me-2"></i>Panduan Upload Naskah
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="small">
                            <h6 class="text-info">Format File:</h6>
                            <ul class="mb-3">
                                <li>Microsoft Word (.doc, .docx)</li>
                                <li>PDF (.pdf)</li>
                                <li>Maksimal 10MB</li>
                            </ul>

                            <h6 class="text-info">Ketentuan Penulisan:</h6>
                            <ul class="mb-3">
                                <li>Minimal 1500 kata</li>
                                <li>Font Times New Roman 12pt</li>
                                <li>Spasi 1.5</li>
                                <li>Margin 2.5cm semua sisi</li>
                            </ul>

                            <h6 class="text-info">Tips:</h6>
                            <ul class="mb-0">
                                <li>Periksa ejaan dan tata bahasa</li>
                                <li>Sertakan referensi jika ada</li>
                                <li>Gunakan bahasa yang mudah dipahami</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Informasi Kontak -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-headset me-2"></i>Informasi Kontak
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Admin Proyek:</strong><br>
                        <small class="text-muted">Untuk pertanyaan seputar penulisan</small><br>
                        <a href="mailto:admin@penerbitkolaborasi.com" class="text-decoration-none">
                            <i class="fas fa-envelope me-1"></i>admin@penerbitkolaborasi.com
                        </a>
                    </div>
                    <div class="mb-3">
                        <strong>Customer Service:</strong><br>
                        <small class="text-muted">Untuk bantuan pembayaran</small><br>
                        <a href="https://wa.me/6281234567890" class="text-decoration-none" target="_blank">
                            <i class="fab fa-whatsapp me-1"></i>+62 812-3456-7890
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bantuan -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-question-circle me-2"></i>Butuh Bantuan?
                    </h6>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted small mb-3">Hubungi customer service kami jika ada kendala</p>
                    <div class="d-grid gap-2">
                        <a href="https://wa.me/6281234567890?text=Halo, saya butuh bantuan untuk pesanan {{ $pesananBuku->nomor_pesanan }}"
                           class="btn btn-success btn-sm" target="_blank">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>
                        <a href="mailto:support@penerbitkolaborasi.com?subject=Bantuan Pesanan {{ $pesananBuku->nomor_pesanan }}"
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-envelope me-2"></i>Email Support
                        </a>
                        <a href="tel:+6281234567890" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-phone me-2"></i>Telepon
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Revisi -->
<div class="modal fade" id="revisiModal" tabindex="-1" aria-labelledby="revisiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="revisiModalLabel">
                    <i class="fas fa-edit me-2"></i>Upload Revisi Naskah
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('buku-kolaboratif.upload-naskah', $pesananBuku->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="is_revision" value="1">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Feedback Editor:</strong><br>
                        {{ $pesananBuku->feedback_editor ?? 'Tidak ada feedback khusus' }}
                    </div>

                    <div class="mb-3">
                        <label for="file_naskah_revisi" class="form-label">
                            <strong>File Naskah Revisi</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="file" class="form-control" id="file_naskah_revisi" 
                               name="file_naskah" accept=".doc,.docx,.pdf" required>
                        <div class="form-text">Format: DOC, DOCX, PDF. Maksimal 10MB</div>
                    </div>

                    <div class="mb-3">
                        <label for="catatan_revisi_modal" class="form-label">
                            <strong>Catatan Revisi</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="catatan_revisi_modal" name="catatan_revisi" 
                                  rows="4" placeholder="Jelaskan perubahan yang telah Anda lakukan berdasarkan feedback editor..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i>Upload Revisi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.progress-timeline {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin: 20px 0;
}

.progress-timeline::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 2px;
    background: #e9ecef;
    z-index: 1;
}

.timeline-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    flex: 1;
}

.timeline-marker {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
    color: #6c757d;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #e9ecef;
}

.timeline-item.completed .timeline-marker {
    background: #28a745;
    color: white;
    box-shadow: 0 0 0 3px #28a745;
}

.timeline-item.active .timeline-marker {
    background: #007bff;
    color: white;
    box-shadow: 0 0 0 3px #007bff;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 3px #007bff;
    }
    50% {
        box-shadow: 0 0 0 8px rgba(0, 123, 255, 0.3);
    }
    100% {
        box-shadow: 0 0 0 3px #007bff;
    }
}

.timeline-content {
    text-align: center;
}

.timeline-content h6 {
    margin: 0;
    font-size: 14px;
}

.timeline-content small {
    color: #6c757d;
    font-size: 12px;
}

/* Sidebar Timeline */
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline .timeline-item {
    position: relative;
    margin-bottom: 20px;
    display: block;
}

.timeline .timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #e9ecef;
}

.timeline .timeline-item.completed .timeline-marker {
    box-shadow: 0 0 0 3px #28a745;
}

.timeline .timeline-item.active .timeline-marker {
    box-shadow: 0 0 0 3px #ffc107;
    animation: pulse-sidebar 2s infinite;
}

@keyframes pulse-sidebar {
    0% {
        box-shadow: 0 0 0 3px #ffc107;
    }
    50% {
        box-shadow: 0 0 0 8px rgba(255, 193, 7, 0.3);
    }
    100% {
        box-shadow: 0 0 0 3px #ffc107;
    }
}

.timeline .timeline-content h6 {
    margin-bottom: 5px;
    font-size: 14px;
}

.timeline .timeline-content small {
    font-size: 12px;
}

.alert {
    border-left: 4px solid;
}

.alert-success {
    border-left-color: #28a745;
}

.alert-warning {
    border-left-color: #ffc107;
}

.alert-info {
    border-left-color: #17a2b8;
}

.alert-light {
    border-left-color: #f8f9fa;
}

.btn:hover {
    transform: translateY(-1px);
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-1px);
    transition: transform 0.2s ease-in-out;
}

.file-upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
}

.file-upload-area:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.file-upload-area.dragover {
    border-color: #28a745;
    background-color: #d4edda;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .progress-timeline {
        flex-direction: column;
        align-items: center;
    }
    
    .progress-timeline::before {
        width: 2px;
        height: 100%;
        left: 50%;
        transform: translateX(-50%);
        top: 0;
    }
    
    .timeline-item {
        margin-bottom: 30px;
        width: 100%;
    }
    
    .timeline-content {
        margin-top: 10px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // File upload validation
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // Check file size (10MB = 10 * 1024 * 1024 bytes)
                if (file.size > 10 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 10MB.');
                    this.value = '';
                    return;
                }
                
                // Check file type
                const allowedTypes = ['.doc', '.docx', '.pdf'];
                const fileName = file.name.toLowerCase();
                const isValidType = allowedTypes.some(type => fileName.endsWith(type));
                
                if (!isValidType) {
                    alert('Format file tidak didukung. Gunakan format DOC, DOCX, atau PDF.');
                    this.value = '';
                    return;
                }
                
                // Show file info
                const fileInfo = document.createElement('div');
                fileInfo.className = 'mt-2 text-success small';
                fileInfo.innerHTML = `<i class="fas fa-check-circle me-1"></i>File dipilih: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                
                // Remove existing file info
                const existingInfo = this.parentNode.querySelector('.text-success');
                if (existingInfo) {
                    existingInfo.remove();
                }
                
                this.parentNode.appendChild(fileInfo);
            }
        });
    });

       // Form submission with loading state
    const uploadForm = document.getElementById('uploadNaskahForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('btnUploadNaskah');
            const fileInput = document.getElementById('file_naskah');
            
            if (!fileInput.files[0]) {
                e.preventDefault();
                alert('Silakan pilih file naskah terlebih dahulu.');
                return;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengupload...';
            
            // Create progress bar
            const progressContainer = document.createElement('div');
            progressContainer.className = 'mt-3';
            progressContainer.innerHTML = `
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                         role="progressbar" style="width: 0%"></div>
                </div>
                <small class="text-muted">Mengupload file...</small>
            `;
            submitBtn.parentNode.appendChild(progressContainer);
            
            // Simulate progress (since we can't track real upload progress easily)
            let progress = 0;
            const progressBar = progressContainer.querySelector('.progress-bar');
            const progressInterval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 90) progress = 90;
                progressBar.style.width = progress + '%';
            }, 500);
            
            // Reset on form error (if validation fails on server)
            setTimeout(() => {
                clearInterval(progressInterval);
            }, 30000); // 30 seconds timeout
        });
    }

    // Auto refresh untuk status menunggu verifikasi
    @if(in_array($statusPembayaran, ['pending', 'menunggu_verifikasi']))
        let refreshInterval = setInterval(function() {
            if (!document.hidden) {
                fetch(window.location.href, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const statusBadges = doc.querySelectorAll('.badge');
                    
                    let statusChanged = false;
                    statusBadges.forEach(badge => {
                        if (badge.textContent.includes('Lunas') || badge.textContent.includes('Terverifikasi')) {
                            statusChanged = true;
                        }
                    });
                    
                    if (statusChanged) {
                        clearInterval(refreshInterval);
                        location.reload();
                    }
                })
                .catch(error => {
                    console.log('Auto refresh error:', error);
                    clearInterval(refreshInterval);
                });
            }
        }, 30000);

        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                clearInterval(refreshInterval);
            }
        });

        window.addEventListener('beforeunload', function() {
            clearInterval(refreshInterval);
        });
    @endif

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn && !alert.classList.contains('alert-warning') && !alert.classList.contains('alert-info')) {
                closeBtn.click();
            }
        });
    }, 5000);

    // Drag and drop functionality
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        const parent = input.parentNode;
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            parent.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            parent.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            parent.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            parent.classList.add('dragover');
        }

        function unhighlight(e) {
            parent.classList.remove('dragover');
        }

        parent.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                input.files = files;
                input.dispatchEvent(new Event('change'));
            }
        }
    });

    // Word count estimation
    const deskripsiTextarea = document.getElementById('deskripsi_naskah');
    if (deskripsiTextarea) {
        deskripsiTextarea.addEventListener('input', function() {
            const wordCount = this.value.trim().split(/\s+/).length;
            let countDisplay = this.parentNode.querySelector('.word-count');
            
            if (!countDisplay) {
                countDisplay = document.createElement('small');
                countDisplay.className = 'word-count text-muted';
                this.parentNode.appendChild(countDisplay);
            }
            
            countDisplay.textContent = `${wordCount} kata`;
        });
    }

    // Initialize Bootstrap tooltips
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});

// Functions for showing/hiding forms
function showReplaceNaskahForm() {
    document.getElementById('replaceNaskahForm').style.display = 'block';
    document.querySelector('button[onclick="showReplaceNaskahForm()"]').style.display = 'none';
}

function hideReplaceNaskahForm() {
    document.getElementById('replaceNaskahForm').style.display = 'none';
    document.querySelector('button[onclick="showReplaceNaskahForm()"]').style.display = 'inline-block';
}

function showRevisiForm() {
    const modal = new bootstrap.Modal(document.getElementById('revisiModal'));
    modal.show();
}

// Copy order number functionality
const orderNumberCell = document.querySelector('td:contains("{{ $pesananBuku->nomor_pesanan }}")');
if (orderNumberCell) {
    orderNumberCell.style.cursor = 'pointer';
    orderNumberCell.title = 'Klik untuk menyalin nomor pesanan';
    orderNumberCell.addEventListener('click', function() {
        navigator.clipboard.writeText('{{ $pesananBuku->nomor_pesanan }}').then(function() {
            const originalText = orderNumberCell.innerHTML;
            orderNumberCell.innerHTML = '<i class="fas fa-check text-success"></i> Tersalin!';
            
            setTimeout(() => {
                orderNumberCell.innerHTML = originalText;
            }, 2000);
        }).catch(function(err) {
            console.error('Could not copy text: ', err);
            const textArea = document.createElement('textarea');
            textArea.value = '{{ $pesananBuku->nomor_pesanan }}';
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            
            const originalText = orderNumberCell.innerHTML;
            orderNumberCell.innerHTML = '<i class="fas fa-check text-success"></i> Tersalin!';
            setTimeout(() => {
                orderNumberCell.innerHTML = originalText;
            }, 2000);
        });
    });
}

// Print functionality
function printPage() {
    window.print();
}

// Add print button
const printButton = document.createElement('button');
printButton.className = 'btn btn-outline-secondary btn-sm ms-2';
printButton.innerHTML = '<i class="fas fa-print me-1"></i>Cetak';
printButton.addEventListener('click', printPage);

const actionButtons = document.querySelector('.text-center');
if (actionButtons) {
    actionButtons.appendChild(printButton);
}
</script>
@endpush
