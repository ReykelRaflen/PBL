@extends('user.layouts.app')

@section('content')
    <div class="container mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.pesanan.index') }}">Pesanan Saya</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('user.pesanan.show', $pesanan) }}">{{ $pesanan->order_number }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
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
            <!-- Informasi Pesanan -->
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>Detail Pesanan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                @if($pesanan->buku->cover)
                                    <img src="{{ asset('storage/' . $pesanan->buku->cover) }}" class="img-fluid rounded"
                                        alt="{{ $pesanan->buku->judul_buku }}" style="max-height: 150px;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                        style="height: 150px;">
                                        <i class="fas fa-book fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <h5 class="text-primary">{{ $pesanan->buku->judul_buku }}</h5>
                                <p class="text-muted mb-2">{{ $pesanan->buku->penulis }}</p>

                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Nomor Pesanan:</small>
                                        <div class="fw-bold">{{ $pesanan->order_number }}</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Tipe:</small>
                                        <div>
                                            @if($pesanan->tipe_buku === 'fisik')
                                                <span class="badge bg-info">
                                                    <i class="fas fa-book me-1"></i>Buku Fisik
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="fas fa-tablet-alt me-1"></i>E-book
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <small class="text-muted">Jumlah:</small>
                                        <div class="fw-bold">{{ $pesanan->quantity }} item</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Harga Satuan:</small>
                                        <div class="fw-bold">Rp {{ number_format($pesanan->harga_satuan, 0, ',', '.') }}</div>
                                    </div>
                                </div>

                                <!-- Rincian Harga -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="border-top pt-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted">Subtotal:</span>
                                                <span>Rp {{ number_format($pesanan->subtotal ?? ($pesanan->harga_satuan * $pesanan->quantity), 0, ',', '.') }}</span>
                                            </div>
                                            
                                            @if(isset($pesanan->diskon) && $pesanan->diskon > 0)
                                                <div class="d-flex justify-content-between mb-1 text-success">
                                                    <span>
                                                        <i class="fas fa-tag me-1"></i>
                                                        Diskon
                                                        @if($pesanan->kode_promo)
                                                            ({{ $pesanan->kode_promo }})
                                                        @endif
                                                        :
                                                    </span>
                                                    <span>-Rp {{ number_format($pesanan->diskon, 0, ',', '.') }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($pesanan->tipe_buku === 'fisik' && isset($pesanan->ongkir) && $pesanan->ongkir > 0)
                                                <div class="d-flex justify-content-between mb-1">
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

                                <!-- Informasi Tambahan -->
                                @if($pesanan->tipe_buku === 'fisik' && $pesanan->alamat_pengiriman)
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <small class="text-muted">Alamat Pengiriman:</small>
                                            <div class="text-sm">{{ $pesanan->alamat_pengiriman }}</div>
                                        </div>
                                    </div>
                                @endif

                                @if($pesanan->no_telepon)
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <small class="text-muted">No. Telepon:</small>
                                            <div class="text-sm">{{ $pesanan->no_telepon }}</div>
                                        </div>
                                    </div>
                                @endif

                                @if($pesanan->catatan)
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <small class="text-muted">Catatan:</small>
                                            <div class="text-sm">{{ $pesanan->catatan }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Pembayaran -->
                @if($pesanan->status === 'menunggu_pembayaran' || !$pesanan->pembayaran)
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-credit-card me-2"></i>Upload Bukti Pembayaran
                            </h5>
                        </div>
                        <div class="card-body">
                            @include('user.pesanan.partials.payment-form')
                        </div>
                    </div>
                @else
                    <!-- Status Pembayaran -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Status Pembayaran
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($pesanan->pembayaran)
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-receipt me-2"></i>Bukti Pembayaran Telah Diupload
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1">
                                                <strong>Invoice:</strong> {{ $pesanan->pembayaran->invoice_number }}<br>
                                                <strong>Tanggal Upload:</strong>
                                                {{ $pesanan->pembayaran->tanggal_pembayaran->format('d F Y H:i') }}<br>
                                                <strong>Bank Pengirim:</strong> {{ $pesanan->pembayaran->bank_pengirim }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1">
                                                <strong>Nama Pengirim:</strong> {{ $pesanan->pembayaran->nama_pengirim }}<br>
                                                <strong>Jumlah Transfer:</strong> Rp {{ number_format($pesanan->pembayaran->jumlah_transfer, 0, ',', '.') }}<br>
                                                <strong>Metode:</strong> {{ $pesanan->pembayaran->metode_pembayaran }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    @if($pesanan->pembayaran->keterangan)
                                        <p class="mb-1">
                                            <strong>Keterangan:</strong> {{ $pesanan->pembayaran->keterangan }}
                                        </p>
                                    @endif
                                    
                                    <hr>
                                    <p class="mb-0">
                                        <strong>Status:</strong>
                                        <span class="badge fs-6 bg-{{ $pesanan->pembayaran->status === 'terverifikasi' ? 'success' : ($pesanan->pembayaran->status === 'ditolak' ? 'danger' : 'warning') }}">
                                            <i class="fas fa-{{ $pesanan->pembayaran->status === 'terverifikasi' ? 'check-circle' : ($pesanan->pembayaran->status === 'ditolak' ? 'times-circle' : 'clock') }} me-1"></i>
                                            {{ ucfirst(str_replace('_', ' ', $pesanan->pembayaran->status)) }}
                                        </span>
                                    </p>
                                </div>

                                <!-- Bukti Pembayaran -->
                                @if($pesanan->pembayaran->bukti_pembayaran)
                                    <div class="mt-3">
                                        <h6><i class="fas fa-image me-2"></i>Bukti Pembayaran:</h6>
                                        <div class="text-center">
                                            <img src="{{ asset('storage/' . $pesanan->pembayaran->bukti_pembayaran) }}"
                                                class="img-fluid rounded border shadow-sm" alt="Bukti Pembayaran"
                                                style="max-height: 400px; cursor: pointer;" 
                                                onclick="showImageModal(this.src)">
                                            <small class="text-muted d-block mt-2">
                                                <i class="fas fa-search-plus me-1"></i>Klik gambar untuk memperbesar
                                            </small>
                                        </div>
                                    </div>
                                @endif

                                <!-- Upload Ulang jika ditolak -->
                                @if($pesanan->pembayaran->status === 'ditolak')
                                    <div class="alert alert-warning mt-3">
                                        <h6 class="alert-heading">
                                            <i class="fas fa-exclamation-triangle me-2"></i>Pembayaran Ditolak
                                        </h6>
                                        <p class="mb-2">Silakan upload ulang bukti pembayaran yang benar.</p>
                                        @if($pesanan->pembayaran->keterangan_admin)
                                            <div class="bg-light p-2 rounded">
                                                <small><strong>Alasan penolakan:</strong> {{ $pesanan->pembayaran->keterangan_admin }}</small>
                                            </div>
                                        @endif
                                    </div>

                                    <button class="btn btn-warning" onclick="showUploadForm()">
                                        <i class="fas fa-upload me-2"></i>Upload Ulang Bukti Pembayaran
                                    </button>

                                    <!-- Form Upload Ulang (Hidden) -->
                                    <div id="upload-form" class="mt-3" style="display: none;">
                                        <div class="card border-warning">
                                            <div class="card-header bg-warning text-dark">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-redo me-2"></i>Upload Ulang Bukti Pembayaran
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                @include('user.pesanan.partials.payment-form')
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Status Terverifikasi -->
                                @if($pesanan->pembayaran->status === 'terverifikasi')
                                    <div class="alert alert-success mt-3">
                                        <h6 class="alert-heading">
                                            <i class="fas fa-check-circle me-2"></i>Pembayaran Terverifikasi
                                        </h6>
                                        <p class="mb-0">
                                            Pembayaran Anda telah diverifikasi. 
                                                                                       @if($pesanan->tipe_buku === 'ebook')
                                                E-book sudah dapat didownload.
                                            @else
                                                Pesanan Anda sedang diproses untuk pengiriman.
                                            @endif
                                        </p>
                                    </div>

                                    @if($pesanan->tipe_buku === 'ebook')
                                        <div class="text-center mt-3">
                                            <a href="{{ route('user.pesanan.downloadEbook', $pesanan) }}" 
                                               class="btn btn-success btn-lg">
                                                <i class="fas fa-download me-2"></i>Download E-book
                                            </a>
                                        </div>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar Informasi -->
            <div class="col-md-4">
                  <!-- Informasi Transfer Bank -->
                        <div class="alert alert-info border-left mb-4">
                            <h6><i class="fas fa-info-circle me-2"></i>Informasi Transfer Bank</h6>
                            <div class="row">
                                @forelse($rekenings as $rekening)
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        @php
                                            // Tentukan logo bank berdasarkan nama bank
                                            $bankLogos = [
                                                'BCA' => 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg',
                                                'MANDIRI' => 'https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg',
                                                'BRI' => 'https://upload.wikimedia.org/wikipedia/commons/2/2e/BRI_2020.svg',
                                                'BNI' => 'https://upload.wikimedia.org/wikipedia/commons/5/55/BNI_logo.svg',
                                                'BTN' => 'https://upload.wikimedia.org/wikipedia/commons/3/3e/Logo_BTN.png',
                                                'CIMB' => 'https://upload.wikimedia.org/wikipedia/commons/8/8c/CIMB_Niaga_logo.svg',
                                                'DANAMON' => 'https://upload.wikimedia.org/wikipedia/commons/3/3e/Danamon_logo.svg',
                                                'PERMATA' => 'https://upload.wikimedia.org/wikipedia/commons/2/2e/PermataBank_logo.svg',
                                                'MEGA' => 'https://upload.wikimedia.org/wikipedia/commons/e/e3/Bank_Mega_logo.svg',
                                                'PANIN' => 'https://upload.wikimedia.org/wikipedia/commons/7/7b/Panin_Bank_logo.svg'
                                            ];
                                            
                                            $bankName = strtoupper($rekening->bank);
                                            $logoUrl = $bankLogos[$bankName] ?? 'https://via.placeholder.com/100x30/007bff/ffffff?text=' . urlencode($rekening->bank);
                                        @endphp
                                        <img src="{{ $logoUrl }}" 
                                             alt="{{ $rekening->bank }}" 
                                             style="height: 20px; max-width: 60px; object-fit: contain;" 
                                             class="me-2"
                                             onerror="this.src='https://via.placeholder.com/60x20/007bff/ffffff?text={{ urlencode($rekening->bank) }}'">
                                        <strong>Bank {{ $rekening->bank }}</strong>
                                    </div>
                                    <div class="text-muted small">
                                        <div>No. Rek: 
                                            <strong style="cursor: pointer;" 
                                                    onclick="copyToClipboard('{{ $rekening->nomor_rekening }}', this)"
                                                    title="Klik untuk menyalin nomor rekening">
                                                {{ $rekening->nomor_rekening }}
                                            </strong>
                                            <i class="fas fa-copy ms-1 text-primary" style="font-size: 12px;"></i>
                                        </div>
                                        <div>A.n: <strong>{{ $rekening->nama_pemilik }}</strong></div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Informasi rekening belum tersedia. Silakan hubungi admin untuk informasi pembayaran.
                                    </div>
                                </div>
                                @endforelse
                            </div>
                        </div>

                <!-- Panduan Pembayaran -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Panduan Pembayaran
                        </h6>
                    </div>
                    <div class="card-body">
                        <ol class="small mb-0">
                            <li class="mb-2">Transfer ke salah satu rekening di atas sesuai jumlah yang tertera</li>
                            <li class="mb-2">Simpan bukti transfer (screenshot/foto struk)</li>
                            <li class="mb-2">Upload bukti transfer melalui form di samping</li>
                            <li class="mb-2">Tunggu verifikasi dari admin (maks. 1x24 jam)</li>
                            <li class="mb-0">
                                @if($pesanan->tipe_buku === 'ebook')
                                    Setelah terverifikasi, E-book dapat langsung didownload
                                @else
                                    Setelah terverifikasi, buku akan dikirim ke alamat tujuan
                                @endif
                            </li>
                        </ol>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-list-ol me-2"></i>Status Pesanan
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
                                    <small class="text-muted">{{ $pesanan->tanggal_pesanan->format('d F Y H:i') }}</small>
                                </div>
                            </div>

                            <!-- Menunggu Pembayaran -->
                            <div class="timeline-item {{ in_array($pesanan->status, ['menunggu_pembayaran']) ? 'active' : (in_array($pesanan->status, ['menunggu_verifikasi', 'selesai']) ? 'completed' : '') }}">
                                <div class="timeline-marker {{ in_array($pesanan->status, ['menunggu_pembayaran']) ? 'bg-warning' : (in_array($pesanan->status, ['menunggu_verifikasi', 'selesai']) ? 'bg-success' : 'bg-light') }}">
                                    <i class="fas fa-{{ in_array($pesanan->status, ['menunggu_pembayaran']) ? 'clock' : (in_array($pesanan->status, ['menunggu_verifikasi', 'selesai']) ? 'check' : 'circle') }} text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Menunggu Pembayaran</h6>
                                    <small class="text-muted">
                                        @if($pesanan->status === 'menunggu_pembayaran')
                                            Silakan lakukan pembayaran
                                        @else
                                            Pembayaran telah diupload
                                        @endif
                                    </small>
                                </div>
                            </div>

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
                                            Pembayaran terverifikasi
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
                            <a href="https://wa.me/6281324558686?text=Halo, saya butuh bantuan untuk pesanan {{ $pesanan->order_number }}"
                                class="btn btn-success btn-sm" target="_blank">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp
                            </a>
                            <a href="mailto:admin@fanyapublishing.com?subject=Bantuan Pesanan {{ $pesanan->order_number }}"
                                class="btn btn-primary btn-sm">
                                <i class="fas fa-envelope me-2"></i>Email Support
                            </a>
                            <a href="tel:+62 813-2455-8686" class="btn btn-outline-secondary btn-sm">
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
                <a href="{{ route('user.pesanan.show', $pesanan) }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Detail Pesanan
                </a>
                <a href="{{ route('user.pesanan.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-list me-2"></i>Lihat Semua Pesanan
                </a>
            </div>
        </div>
    </div>

    <!-- Modal untuk memperbesar gambar -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">
                        <i class="fas fa-image me-2"></i>Bukti Pembayaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid rounded" alt="Bukti Pembayaran">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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

                .timeline-content h6 {
            margin-bottom: 5px;
            font-size: 14px;
        }

        .timeline-content small {
            font-size: 12px;
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

        // Function untuk menampilkan form upload ulang
        function showUploadForm() {
            const uploadForm = document.getElementById('upload-form');
            if (uploadForm.style.display === 'none') {
                uploadForm.style.display = 'block';
                uploadForm.scrollIntoView({ behavior: 'smooth' });
            } else {
                uploadForm.style.display = 'none';
            }
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
                        const newStatus = doc.querySelector('.timeline-item.active, .timeline-item.completed:last-child');
                        
                        // If status changed, reload the page
                        if (newStatus && !newStatus.textContent.includes('Verifikasi Pembayaran')) {
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

        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form[enctype="multipart/form-data"]');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const fileInput = form.querySelector('input[type="file"]');
                    const submitBtn = form.querySelector('button[type="submit"]');
                    
                    if (fileInput && fileInput.files.length > 0) {
                        const file = fileInput.files[0];
                        const maxSize = 2 * 1024 * 1024; // 2MB
                        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                        
                        // Validate file size
                        if (file.size > maxSize) {
                            e.preventDefault();
                            alert('Ukuran file terlalu besar. Maksimal 2MB.');
                            return false;
                        }
                        
                        // Validate file type
                        if (!allowedTypes.includes(file.type)) {
                            e.preventDefault();
                            alert('Format file tidak didukung. Gunakan JPG, JPEG, atau PNG.');
                            return false;
                        }
                        
                        // Show loading state
                        if (submitBtn) {
                            const originalText = submitBtn.innerHTML;
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengupload...';
                            
                            // Reset button if form submission fails
                            setTimeout(() => {
                                if (submitBtn.disabled) {
                                    submitBtn.disabled = false;
                                    submitBtn.innerHTML = originalText;
                                }
                            }, 10000);
                        }
                    } else if (fileInput) {
                        e.preventDefault();
                        alert('Silakan pilih file bukti pembayaran terlebih dahulu.');
                        return false;
                    }
                });
            });

            // Preview image before upload
            const fileInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
            fileInputs.forEach(input => {
                input.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            // Remove existing preview
                            const existingPreview = input.parentNode.querySelector('.image-preview');
                            if (existingPreview) {
                                existingPreview.remove();
                            }
                            
                            // Create new preview
                            const preview = document.createElement('div');
                            preview.className = 'image-preview mt-2';
                            preview.innerHTML = `
                                <img src="${e.target.result}" class="img-thumbnail" style="max-height: 200px;" alt="Preview">
                                <small class="text-muted d-block mt-1">Preview gambar yang akan diupload</small>
                            `;
                            input.parentNode.appendChild(preview);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });
        });

        // Copy rekening number to clipboard
        function copyToClipboard(text, element) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success feedback
                const originalText = element.innerHTML;
                element.innerHTML = '<i class="fas fa-check text-success"></i> Tersalin!';
                element.classList.add('text-success');
                
                setTimeout(() => {
                    element.innerHTML = originalText;
                    element.classList.remove('text-success');
                }, 2000);
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                const originalText = element.innerHTML;
                element.innerHTML = '<i class="fas fa-check text-success"></i> Tersalin!';
                setTimeout(() => {
                    element.innerHTML = originalText;
                }, 2000);
            });
        }

        // Add click to copy functionality to account numbers
        document.addEventListener('DOMContentLoaded', function() {
            const accountNumbers = document.querySelectorAll('.card-body strong');
            accountNumbers.forEach(element => {
                if (element.textContent.match(/^\d+$/)) { // Only numbers
                    element.style.cursor = 'pointer';
                    element.title = 'Klik untuk menyalin nomor rekening';
                    element.addEventListener('click', function() {
                        copyToClipboard(this.textContent, this);
                    });
                }
            });
        });

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
    </script>
@endpush

