@extends('user.layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.pesanan.index') }}">Pesanan Saya</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $pesanan->order_number }}</li>
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
        <!-- Detail Pesanan -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>Detail Pesanan
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Info Pesanan -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted" style="width: 40%;">Nomor Pesanan</td>
                                    <td><strong>{{ $pesanan->order_number }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tanggal Pesanan</td>
                                    <td>{{ $pesanan->tanggal_pesanan->format('d F Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status</td>
                                    <td>
                                        <span class="badge {{ $pesanan->status_badge }}">
                                            {{ $pesanan->status_text }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted" style="width: 40%;">Tipe Buku</td>
                                    <td>
                                        @if($pesanan->tipe_buku === 'fisik')
                                            <span class="badge bg-info">
                                                <i class="fas fa-book me-1"></i>Buku Fisik
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="fas fa-tablet-alt me-1"></i>E-book
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jumlah</td>
                                    <td>{{ $pesanan->quantity }} item</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Total</td>
                                    <td><strong class="text-primary">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Rincian Harga -->
                    <div class="border-top pt-4 mb-4">
                        <h6 class="fw-bold mb-3">Rincian Harga</h6>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Harga Satuan:</span>
                                    <span>Rp {{ number_format($pesanan->harga_satuan, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Jumlah:</span>
                                    <span>{{ $pesanan->quantity }} item</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal:</span>
                                    <span>Rp {{ number_format($pesanan->subtotal ?? ($pesanan->harga_satuan * $pesanan->quantity), 0, ',', '.') }}</span>
                                </div>
                                
                                @if(isset($pesanan->diskon) && $pesanan->diskon > 0)
                                    <div class="d-flex justify-content-between mb-2 text-success">
                                        <span>
                                            <i class="fas fa-tag me-1"></i>
                                            Diskon
                                            @if($pesanan->kode_promo)
                                                <small class="badge bg-success ms-1">{{ $pesanan->kode_promo }}</small>
                                            @endif
                                            :
                                        </span>
                                        <span>-Rp {{ number_format($pesanan->diskon, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                
                                @if($pesanan->tipe_buku === 'fisik' && isset($pesanan->ongkir) && $pesanan->ongkir > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Ongkos Kirim:</span>
                                        <span>Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-primary">Total Pembayaran:</strong>
                                    <strong class="text-primary h5">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Buku -->
                    <div class="border-top pt-4">
                        <h6 class="fw-bold mb-3">Detail Buku</h6>
                        <div class="row">
                            <div class="col-md-3">
                                @if($pesanan->buku->cover)
                                    <img src="{{ asset('storage/' . $pesanan->buku->cover) }}" 
                                         class="img-fluid rounded shadow-sm" 
                                         alt="{{ $pesanan->buku->judul_buku }}"
                                         style="max-height: 200px; cursor: pointer;"
                                         onclick="showImageModal(this.src)">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="fas fa-book fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <h5 class="text-primary">{{ $pesanan->buku->judul_buku }}</h5>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-user-edit me-2"></i>{{ $pesanan->buku->penulis }}
                                </p>
                                
                                @if($pesanan->buku->penerbit)
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-building me-2"></i>{{ $pesanan->buku->penerbit }}
                                        @if($pesanan->buku->tahun_terbit)
                                            ({{ $pesanan->buku->tahun_terbit }})
                                        @endif
                                    </p>
                                @endif
                                
                                @if($pesanan->buku->kategori)
                                    <p class="mb-2">
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-tag me-1"></i>{{ $pesanan->buku->kategori->nama }}
                                        </span>
                                    </p>
                                @endif
                                
                                @if($pesanan->buku->isbn)
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-barcode me-2"></i>ISBN: {{ $pesanan->buku->isbn }}
                                    </p>
                                @endif
                                
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <small class="text-muted">Harga Satuan</small>
                                        <div class="fw-bold">Rp {{ number_format($pesanan->harga_satuan, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Subtotal</small>
                                        <div class="fw-bold">Rp {{ number_format($pesanan->subtotal ?? ($pesanan->harga_satuan * $pesanan->quantity), 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    @if($pesanan->tipe_buku === 'fisik' && $pesanan->alamat_pengiriman)
                        <div class="border-top pt-4 mt-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>Informasi Pengiriman
                            </h6>
                            <div class="bg-light p-3 rounded">
                                <div class="row">
                                    <div class="col-md-8">
                                        <strong>Alamat Pengiriman:</strong>
                                        <p class="mb-1">{{ $pesanan->alamat_pengiriman }}</p>
                                    </div>
                                    @if($pesanan->no_telepon)
                                        <div class="col-md-4">
                                            <strong>No. Telepon:</strong>
                                            <p class="mb-1">{{ $pesanan->no_telepon }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($pesanan->catatan)
                        <div class="border-top pt-4 mt-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-sticky-note me-2"></i>Catatan
                            </h6>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0">{{ $pesanan->catatan }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Panel -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>Aksi Pesanan
                    </h6>
                </div>
                <div class="card-body">
                    @if($pesanan->status === 'menunggu_pembayaran')
                        <!-- Belum Bayar -->
                        <div class="text-center mb-3">
                            <i class="fas fa-clock fa-3x text-warning mb-2"></i>
                            <h6 class="text-warning">Menunggu Pembayaran</h6>
                            <p class="text-muted small">Silakan lakukan pembayaran untuk melanjutkan pesanan</p>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('user.pesanan.payment', $pesanan) }}" 
                               class="btn btn-primary">
                                <i class="fas fa-credit-card me-2"></i>Bayar Sekarang
                            </a>
                            
                            <form method="POST" action="{{ route('user.pesanan.cancel', $pesanan) }}" 
                                  onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-times me-2"></i>Batalkan Pesanan
                                </button>
                            </form>
                        </div>

                        <!-- Countdown Timer -->
                        <div class="alert alert-warning mt-3">
                            <small>
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <strong>Perhatian:</strong> Pesanan akan otomatis dibatalkan jika tidak dibayar dalam 24 jam.
                            </small>
                        </div>

                    @elseif($pesanan->status === 'menunggu_verifikasi')
                        <!-- Menunggu Verifikasi -->
                        <div class="text-center mb-3">
                            <i class="fas fa-hourglass-half fa-3x text-info mb-2"></i>
                            <h6 class="text-info">Menunggu Verifikasi</h6>
                            <p class="text-muted small">Pembayaran sedang diverifikasi oleh admin</p>
                        </div>
                        
                        @if($pesanan->pembayaran)
                            <div class="alert alert-info">
                                <small>
                                    <strong>Bukti pembayaran telah diupload</strong><br>
                                    <i class="fas fa-calendar me-1"></i>{{ $pesanan->pembayaran->tanggal_pembayaran->format('d F Y H:i') }}<br>
                                    <i class="fas fa-university me-1"></i>{{ $pesanan->pembayaran->bank_pengirim }}<br>
                                    <i class="fas fa-user me-1"></i>{{ $pesanan->pembayaran->nama_pengirim }}
                                </small>
                                                            </div>
                        @endif

                        <div class="d-grid">
                            <a href="{{ route('user.pesanan.payment', $pesanan) }}" 
                               class="btn btn-outline-info">
                                <i class="fas fa-eye me-2"></i>Lihat Detail Pembayaran
                            </a>
                        </div>

                    @elseif($pesanan->status === 'selesai' && $pesanan->tipe_buku === 'ebook')
                        <!-- E-book Ready -->
                        <div class="text-center mb-3">
                            <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                            <h6 class="text-success">Pesanan Selesai</h6>
                            <p class="text-muted small">E-book siap untuk didownload</p>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('user.pesanan.downloadEbook', $pesanan) }}" 
                               class="btn btn-success">
                                <i class="fas fa-download me-2"></i>Download E-book
                            </a>
                            
                            <button class="btn btn-outline-primary" onclick="shareEbook()">
                                <i class="fas fa-share-alt me-2"></i>Bagikan
                            </button>
                        </div>

                        <div class="alert alert-success mt-3">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                E-book dapat didownload kapan saja melalui halaman pesanan.
                            </small>
                        </div>

                    @elseif($pesanan->status === 'selesai')
                        <!-- Pesanan Selesai -->
                        <div class="text-center mb-3">
                            <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                            <h6 class="text-success">Pesanan Selesai</h6>
                            <p class="text-muted small">Buku telah dikirim ke alamat tujuan</p>
                        </div>

                        <div class="alert alert-success">
                            <small>
                                <i class="fas fa-truck me-1"></i>
                                Buku sedang dalam perjalanan ke alamat pengiriman.
                            </small>
                        </div>

                    @elseif($pesanan->status === 'dibatalkan')
                        <!-- Dibatalkan -->
                        <div class="text-center mb-3">
                            <i class="fas fa-times-circle fa-3x text-danger mb-2"></i>
                            <h6 class="text-danger">Pesanan Dibatalkan</h6>
                            <p class="text-muted small">Pesanan telah dibatalkan</p>
                        </div>

                        @if($pesanan->tipe_buku === 'fisik')
                            <div class="alert alert-info">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    Stok buku telah dikembalikan.
                                </small>
                            </div>
                        @endif

                    @else
                        <!-- Status Lainnya -->
                        <div class="text-center mb-3">
                            <i class="fas fa-cog fa-3x text-secondary mb-2"></i>
                            <h6 class="text-secondary">{{ $pesanan->status_text }}</h6>
                            <p class="text-muted small">Status pesanan sedang diproses</p>
                        </div>
                    @endif

                    <!-- Info Pembayaran -->
                    @if($pesanan->pembayaran)
                        <div class="border-top pt-3 mt-3">
                            <h6 class="fw-bold mb-2">
                                <i class="fas fa-receipt me-2"></i>Info Pembayaran
                            </h6>
                            <div class="bg-light p-2 rounded">
                                <small class="text-muted">
                                    <strong>Invoice:</strong> {{ $pesanan->pembayaran->invoice_number }}<br>
                                    <strong>Metode:</strong> {{ $pesanan->pembayaran->metode_pembayaran }}<br>
                                    <strong>Jumlah:</strong> Rp {{ number_format($pesanan->pembayaran->jumlah_transfer, 0, ',', '.') }}<br>
                                    <strong>Status:</strong> 
                                    <span class="badge bg-{{ $pesanan->pembayaran->status === 'terverifikasi' ? 'success' : ($pesanan->pembayaran->status === 'ditolak' ? 'danger' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $pesanan->pembayaran->status)) }}
                                    </span>
                                </small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timeline Status -->
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>Timeline Pesanan
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
                                <small class="text-muted">{{ $pesanan->tanggal_pesanan->format('d M Y, H:i') }}</small>
                            </div>
                        </div>

                        <!-- Menunggu Pembayaran -->
                        <div class="timeline-item {{ in_array($pesanan->status, ['menunggu_pembayaran']) ? 'active' : (in_array($pesanan->status, ['menunggu_verifikasi', 'selesai']) ? 'completed' : ($pesanan->status === 'dibatalkan' ? 'cancelled' : '')) }}">
                            <div class="timeline-marker {{ in_array($pesanan->status, ['menunggu_pembayaran']) ? 'bg-warning' : (in_array($pesanan->status, ['menunggu_verifikasi', 'selesai']) ? 'bg-success' : ($pesanan->status === 'dibatalkan' ? 'bg-danger' : 'bg-light')) }}">
                                <i class="fas fa-{{ in_array($pesanan->status, ['menunggu_pembayaran']) ? 'clock' : (in_array($pesanan->status, ['menunggu_verifikasi', 'selesai']) ? 'check' : ($pesanan->status === 'dibatalkan' ? 'times' : 'circle')) }} text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">
                                    @if($pesanan->status === 'dibatalkan')
                                        Pesanan Dibatalkan
                                    @else
                                        Menunggu Pembayaran
                                    @endif
                                </h6>
                                <small class="text-muted">
                                    @if($pesanan->status === 'menunggu_pembayaran')
                                        Silakan lakukan pembayaran
                                    @elseif($pesanan->status === 'dibatalkan')
                                        Dibatalkan pada {{ $pesanan->updated_at->format('d M Y, H:i') }}
                                    @else
                                        Pembayaran telah diupload
                                    @endif
                                </small>
                            </div>
                        </div>

                        @if($pesanan->status !== 'dibatalkan')
                            <!-- Verifikasi Pembayaran -->
                            <div class="timeline-item {{ $pesanan->status === 'menunggu_verifikasi' ? 'active' : ($pesanan->status === 'selesai' ? 'completed' : '') }}">
                                <div class="timeline-marker {{ $pesanan->status === 'menunggu_verifikasi' ? 'bg-info' : ($pesanan->status === 'selesai' ? 'bg-success' : 'bg-light') }}">
                                    <i class="fas fa-{{ $pesanan->status === 'menunggu_verifikasi' ? 'hourglass-half' : ($pesanan->status === 'selesai' ? 'check' : 'circle') }} text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Verifikasi Pembayaran</h6>
                                    <small class="text-muted">
                                        @if($pesanan->status === 'menunggu_verifikasi')
                                            Sedang diverifikasi admin
                                        @elseif($pesanan->status === 'selesai')
                                            Pembayaran terverifikasi pada {{ $pesanan->pembayaran ? $pesanan->pembayaran->updated_at->format('d M Y, H:i') : '-' }}
                                        @else
                                            Menunggu upload bukti
                                        @endif
                                    </small>
                                </div>
                            </div>

                            <!-- Selesai -->
                            <div class="timeline-item {{ $pesanan->status === 'selesai' ? 'completed' : '' }}">
                                <div class="timeline-marker {{ $pesanan->status === 'selesai' ? 'bg-success' : 'bg-light' }}">
                                    <i class="fas fa-{{ $pesanan->status === 'selesai' ? 'trophy' : 'circle' }} text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">
                                        @if($pesanan->tipe_buku === 'ebook')
                                            E-book Siap Didownload
                                        @else
                                            Buku Dikirim
                                        @endif
                                    </h6>
                                    <small class="text-muted">
                                        @if($pesanan->status === 'selesai')
                                            @if($pesanan->tipe_buku === 'ebook')
                                                E-book dapat didownload
                                            @else
                                                Buku sedang dalam pengiriman
                                            @endif
                                        @else
                                            Menunggu verifikasi pembayaran
                                        @endif
                                    </small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bantuan -->
            <div class="card shadow-sm mt-3">
                <div class="card-body text-center">
                    <h6 class="fw-bold">
                        <i class="fas fa-question-circle me-2"></i>Butuh Bantuan?
                    </h6>
                    <p class="text-muted small">Hubungi customer service kami</p>
                    <div class="d-grid gap-2">
                        <a href="https://wa.me/6281234567890?text=Halo, saya butuh bantuan untuk pesanan {{ $pesanan->order_number }}" 
                           class="btn btn-success btn-sm" target="_blank">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>
                        <a href="mailto:support@fanya.com?subject=Bantuan Pesanan {{ $pesanan->order_number }}" 
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

    <!-- Kembali -->
    <div class="row mt-4">
        <div class="col-12">
            <a href="{{ route('user.pesanan.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Pesanan
            </a>
        </div>
    </div>

    <!-- Modal untuk memperbesar gambar -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">
                        <i class="fas fa-image me-2"></i>Cover Buku
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid rounded" alt="Cover Buku">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
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

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }

    .timeline-marker {
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

    .timeline-item.completed .timeline-marker {
        box-shadow: 0 0 0 3px #28a745;
    }

    .timeline-item.active .timeline-marker {
        box-shadow: 0 0 0 3px #ffc107;
        animation: pulse 2s infinite;
    }

    .timeline-item.cancelled .timeline-marker {
        box-shadow: 0 0 0 3px #dc3545;
    }

    @keyframes pulse {
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

    .timeline-content {
        padding-left: 15px;
    }

    .timeline-content h6 {
        margin-bottom: 5px;
        font-size: 14px;
    }

    .timeline-content small {
        font-size: 12px;
    }

    .card-header h5, .card-header h6 {
        margin-bottom: 0;
    }

    .img-fluid:hover {
        transform: scale(1.02);
        transition: transform 0.2s ease-in-out;
    }

    .btn:hover {
        transform: translateY(-1px);
        transition: transform 0.2s ease-in-out;
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

    .alert-danger {
        border-left-color: #dc3545;
    }

    .badge {
        font-size: 0.75em;
    }

    .table-borderless td {
        padding: 0.5rem 0;
        border: none;
    }

    .animate-pulse {
        animation: pulse-price 1s ease-in-out;
    }

    @keyframes pulse-price {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
            color: #28a745;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Function untuk menampilkan modal gambar
    function showImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }

    // Function untuk share e-book
    function shareEbook() {
        if (navigator.share) {
            navigator.share({
                title: 'E-book: {{ $pesanan->buku->judul_buku }}',
                text: 'Saya baru saja membeli e-book "{{ $pesanan->buku->judul_buku }}" dari Fanya Bookstore!',
                url: window.location.href
            }).then(() => {
                console.log('Berhasil dibagikan');
            }).catch((error) => {
                console.log('Error sharing:', error);
                fallbackShare();
            });
        } else {
            fallbackShare();
        }
    }

    function fallbackShare() {
        const shareText = `Saya baru saja membeli e-book "${document.querySelector('h5.text-primary').textContent}" dari Fanya Bookstore!`;
        const shareUrl = window.location.href;
        
        // Copy to clipboard
        navigator.clipboard.writeText(`${shareText} ${shareUrl}`).then(() => {
            alert('Link telah disalin ke clipboard!');
        }).catch(() => {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = `${shareText} ${shareUrl}`;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('Link telah disalin ke clipboard!');
        });
    }

    // Auto refresh untuk status menunggu verifikasi
    @if($pesanan->status === 'menunggu_verifikasi')
        let refreshInterval = setInterval(function() {
            // Check if page is still visible
            if (!document.hidden) {
                fetch(window.location.href, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Parse the response to check if status has changed
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newStatusBadge = doc.querySelector('.badge');
                    const currentStatusBadge = document.querySelector('.badge');
                    
                    // If status changed, reload the page
                    if (newStatusBadge && currentStatusBadge && 
                        newStatusBadge.textContent.trim() !== currentStatusBadge.textContent.trim()) {
                        clearInterval(refreshInterval);
                        location.reload();
                    }
                })
                .catch(error => {
                    console.log('Auto refresh error:', error);
                });
            }
        }, 30000); // Check every 30 seconds

        // Clear interval when page is hidden or user leaves
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                clearInterval(refreshInterval);
            }
        });

        window.addEventListener('beforeunload', function() {
            clearInterval(refreshInterval);
        });
    @endif

    // Smooth scroll for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn && !alert.classList.contains('alert-warning')) {
                closeBtn.click();
            }
        });
    }, 5000);

    // Add loading state to buttons
    document.querySelectorAll('form button[type="submit"]').forEach(button => {
        button.addEventListener('click', function() {
            const originalText = this.innerHTML;
            
            // Disable button dan show loading
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
            
            // Re-enable setelah 3 detik jika form tidak submit
            setTimeout(() => {
                if (this.disabled) {
                    this.disabled = false;
                    this.innerHTML = originalText;
                }
            }, 3000);
        });
    });

    // Confirmation for cancel order
    document.querySelectorAll('form[action*="cancel"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Tindakan ini tidak dapat dibatalkan.')) {
                e.preventDefault();
                return false;
            }
        });
    });

    // Add click effect to cards
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'transform 0.2s ease-in-out';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Format currency on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation to price elements
        const priceElements = document.querySelectorAll('.text-primary:contains("Rp")');
        priceElements.forEach(element => {
            if (element.textContent.includes('Rp')) {
                element.classList.add('animate-pulse');
            }
        });

        // Remove animation after 2 seconds
        setTimeout(() => {
            priceElements.forEach(element => {
                element.classList.remove('animate-pulse');
            });
        }, 2000);
    });

    // Print functionality (optional)
    function printOrder() {
        window.print();
    }

    // Add print button if needed
    document.addEventListener('DOMContentLoaded', function() {
        const actionCard = document.querySelector('.col-md-4 .card .card-body');
        if (actionCard && !document.querySelector('.btn-print')) {
            const printBtn = document.createElement('button');
            printBtn.className = 'btn btn-outline-secondary btn-sm mt-2 w-100 btn-print';
            printBtn.innerHTML = '<i class="fas fa-print me-2"></i>Cetak Pesanan';
            printBtn.onclick = printOrder;
            actionCard.appendChild(printBtn);
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + P for print
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            printOrder();
        }
        
        // Escape to close modal
        if (e.key === 'Escape') {
            const modal = document.querySelector('.modal.show');
            if (modal) {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        }
    });

    // Add tooltips to buttons
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips if available
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    });
</script>
@endpush


