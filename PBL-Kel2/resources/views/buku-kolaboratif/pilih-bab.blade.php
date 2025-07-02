@extends('user.layouts.app')

@section('title', 'Pilih Bab - ' . $bukuKolaboratif->judul)

@section('content')
<div class="container mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('buku-kolaboratif.index') }}">Buku Kolaboratif</a></li>
            <li class="breadcrumb-item"><a href="{{ route('buku-kolaboratif.tampilkan', $bukuKolaboratif) }}">{{ $bukuKolaboratif->judul }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pilih Bab</li>
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

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-check-circle me-2"></i>Konfirmasi Pemilihan Bab
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Info Buku -->
                    <div class="alert alert-info border-left">
                        <h6><i class="fas fa-book me-2"></i>{{ $bukuKolaboratif->judul }}</h6>
                        <p class="mb-0 small">{{ Str::limit($bukuKolaboratif->deskripsi, 150) }}</p>
                    </div>

                    <!-- Detail Bab -->
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-primary">Bab {{ $babBuku->nomor_bab }}: {{ $babBuku->judul_bab }}</h5>
                            @if($babBuku->deskripsi_bab)
                                <p class="text-muted">{{ $babBuku->deskripsi_bab }}</p>
                            @endif

                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <div class="border rounded p-3 bg-light text-center">
                                        <small class="text-muted d-block">Nomor Bab</small>
                                        <div class="fw-bold">Bab {{ $babBuku->nomor_bab }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="border rounded p-3 bg-light text-center">
                                        <small class="text-muted d-block">Status</small>
                                        <span class="badge bg-success">{{ ucfirst($babBuku->status) }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($babBuku->estimasi_kata)
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <div class="border rounded p-3 bg-light text-center">
                                        <small class="text-muted d-block">Estimasi Kata</small>
                                        <div class="fw-bold">{{ number_format($babBuku->estimasi_kata) }} kata</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="border rounded p-3 bg-light text-center">
                                        <small class="text-muted d-block">Tingkat Kesulitan</small>
                                        @if($babBuku->tingkat_kesulitan === 'mudah')
                                            <span class="badge bg-success">Mudah</span>
                                        @elseif($babBuku->tingkat_kesulitan === 'sedang')
                                            <span class="badge bg-warning">Sedang</span>
                                        @elseif($babBuku->tingkat_kesulitan === 'sulit')
                                            <span class="badge bg-danger">Sulit</span>
                                        @else
                                            <span class="badge bg-secondary">Belum Ditentukan</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h6 class="text-white-50">Harga Bab</h6>
                                    <h3 class="mb-0">
                                        @if(isset($babBuku->harga))
                                            Rp {{ number_format($babBuku->harga, 0, ',', '.') }}
                                        @else
                                            Rp {{ number_format($bukuKolaboratif->harga_per_bab, 0, ',', '.') }}
                                        @endif
                                    </h3>
                                    <small class="text-white-50">Pembayaran sekali bayar</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-info">
                                <div class="card-body">
                                    <h6 class="card-title text-info">
                                        <i class="fas fa-info-circle me-2"></i>Informasi Buku
                                    </h6>
                                    <ul class="list-unstyled mb-0 small">
                                        <li><strong>Kategori:</strong> {{ $bukuKolaboratif->kategoriBuku->nama ?? 'Umum' }}</li>
                                        <li><strong>Total Bab:</strong> {{ $bukuKolaboratif->total_bab }} bab</li>
                                        <li><strong>Target Pembaca:</strong> {{ $bukuKolaboratif->target_pembaca ?? 'Umum' }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h6 class="card-title text-warning">
                                        <i class="fas fa-clock me-2"></i>Timeline
                                    </h6>
                                    <ul class="list-unstyled mb-0 small">
                                        <li><strong>Pembayaran:</strong> Maksimal 24 jam</li>
                                        <li><strong>Penulisan:</strong> Sesuai deadline</li>
                                        <li><strong>Revisi:</strong> Maksimal 2 kali</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Persyaratan -->
                    <div class="alert alert-warning border-left">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Persyaratan & Ketentuan:</h6>
                        <ul class="mb-0 small">
                            <li>Pembayaran harus dilakukan dalam 24 jam setelah pemesanan</li>
                            <li>Naskah harus diselesaikan sesuai deadline yang ditentukan</li>
                            <li>Naskah harus original dan bebas plagiarisme</li>
                            <li>Mengikuti panduan penulisan yang telah ditentukan</li>
                            <li>Revisi dapat diminta maksimal 2 kali</li>
                            <li>Pembayaran akan dikembalikan jika tidak memenuhi standar kualitas</li>
                        </ul>
                    </div>

                    <!-- Form Pemesanan -->
                    <form action="{{ route('buku-kolaboratif.proses-pesanan', [$bukuKolaboratif, $babBuku]) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="catatan" class="form-label">
                                <i class="fas fa-sticky-note me-2"></i>Catatan Tambahan (Opsional)
                            </label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                      id="catatan" name="catatan" rows="3" maxlength="1000"
                                      placeholder="Tuliskan pengalaman atau keahlian Anda terkait topik ini, gaya penulisan yang diinginkan, atau informasi lain yang relevan...">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Catatan ini akan membantu kami memahami ekspektasi Anda terhadap penulisan bab ini.
                            </div>
                        </div>

                        {{-- <div class="mb-3">
                            <label for="tanggal_deadline" class="form-label">
                                <i class="fas fa-calendar-alt me-2"></i>Deadline Pengerjaan
                            </label>
                            <input type="date" class="form-control @error('tanggal_deadline') is-invalid @enderror" 
                                   id="tanggal_deadline" name="tanggal_deadline" 
                                   min="{{ date('Y-m-d', strtotime('+7 days')) }}" 
                                   value="{{ old('tanggal_deadline') }}" required>
                            @error('tanggal_deadline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Minimal 7 hari dari sekarang</div>
                        </div> --}}

                        <!-- Ringkasan Pesanan -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-receipt me-2"></i>Ringkasan Pesanan
                                </h6>
                                <div class="row">
                                    <div class="col-md-8">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr>
                                                <td><strong>Buku:</strong></td>
                                                <td>{{ $bukuKolaboratif->judul }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Bab:</strong></td>
                                                <td>Bab {{ $babBuku->nomor_bab }} - {{ $babBuku->judul_bab }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td><span class="badge bg-success">{{ ucfirst($babBuku->status) }}</span></td>
                                            </tr>
                                            @if($babBuku->estimasi_kata)
                                            <tr>
                                                <td><strong>Estimasi:</strong></td>
                                                <td>{{ number_format($babBuku->estimasi_kata) }} kata</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <div class="h4 text-success mb-0">
                                            @if(isset($babBuku->harga))
                                                Rp {{ number_format($babBuku->harga, 0, ',', '.') }}
                                            @else
                                                Rp {{ number_format($bukuKolaboratif->harga_per_bab, 0, ',', '.') }}
                                            @endif
                                        </div>
                                        <small class="text-muted">Total Pembayaran</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input @error('setuju') is-invalid @enderror" 
                                   type="checkbox" id="setuju" name="setuju" value="1" required>
                            <label class="form-check-label" for="setuju">
                                Saya setuju dengan <a href="#" data-bs-toggle="modal" data-bs-target="#syaratKetentuanModal">persyaratan dan ketentuan</a> yang berlaku
                            </label>
                            @error('setuju')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('buku-kolaboratif.tampilkan', $bukuKolaboratif->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Pesan Bab Ini - 
                                @if(isset($babBuku->harga))
                                    Rp {{ number_format($babBuku->harga, 0, ',', '.') }}
                                @else
                                    Rp {{ number_format($bukuKolaboratif->harga_per_bab, 0, ',', '.') }}
                                @endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Syarat dan Ketentuan -->
<div class="modal fade" id="syaratKetentuanModal" tabindex="-1" aria-labelledby="syaratKetentuanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="syaratKetentuanModalLabel">
                    <i class="fas fa-file-contract me-2"></i>Syarat dan Ketentuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>1. Pembayaran</h6>
                <ul>
                    <li>Pembayaran harus dilakukan dalam waktu 24 jam setelah pemesanan</li>
                    <li>Pembayaran yang terlambat akan mengakibatkan pembatalan otomatis</li>
                    <li>Metode pembayaran yang tersedia: Transfer Bank, Kartu Kredit, E-Wallet</li>
                </ul>

                <h6>2. Penulisan</h6>
                <ul>
                    <li>Naskah harus diselesaikan sesuai deadline yang telah ditentukan</li>
                    <li>Naskah harus original dan bebas dari plagiarisme</li>
                    <li>Mengikuti panduan penulisan dan gaya bahasa yang telah ditentukan</li>
                    <li>Minimal kata sesuai dengan estimasi yang telah ditetapkan</li>
                </ul>

                <h6>3. Revisi</h6>
                <ul>
                    <li>Revisi dapat diminta maksimal 2 kali</li>
                    <li>Revisi harus berdasarkan feedback yang konstruktif</li>
                    <li>Waktu revisi maksimal 7 hari per revisi</li>
                </ul>

                <h6>4. Hak Cipta</h6>
                <ul>
                    <li>Hak cipta naskah akan menjadi milik pemesan setelah pembayaran lunas</li>
                    <li>Penulis tidak diperkenankan mempublikasikan ulang naskah yang sama</li>
                </ul>

                <h6>5. Pembatalan</h6>
                <ul>
                    <li>Pembatalan hanya dapat dilakukan sebelum pembayaran diverifikasi</li>
                    <li>Pembayaran akan dikembalikan 100% jika naskah tidak memenuhi standar kualitas</li>
                    <li>Pengembalian dana akan diproses dalam 3-7 hari kerja</li>
                </ul>
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
    .alert {
        border-left: 4px solid;
        border-radius: 8px;
    }

    .alert-info {
        border-left-color: #17a2b8;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }

    .alert-warning {
        border-left-color: #ffc107;
        background-color: #fff3cd;
        border-color: #ffeaa7;
    }

    .alert-success {
        border-left-color: #28a745;
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-danger {
        border-left-color: #dc3545;
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    .btn {
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .card {
        border-radius: 10px;
        transition: all 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        border-radius: 10px 10px 0 0 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .breadcrumb {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 0.75rem 1rem;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        content: "›";
        font-weight: bold;
        color: #6c757d;
    }

    .breadcrumb-item a {
        color: #007bff;
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    .breadcrumb-item.active {
        color: #6c757d;
        font-weight: 500;
    }

    /* Form styling */
    .form-control {
        border-radius: 6px;
        border: 1px solid #ced4da;
        transition: all 0.2s ease-in-out;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .form-check-input {
        border-radius: 4px;
    }

    .form-check-input:checked {
        background-color: #007bff;
        border-color: #007bff;
    }

    /* Badge styling */
    .badge {
        font-size: 0.75em;
        font-weight: 500;
        padding: 0.35em 0.65em;
        border-radius: 4px;
    }

    .badge.bg-success {
        background-color: #28a745 !important;
    }

    .badge.bg-warning {
        background-color: #ffc107 !important;
        color: #212529 !important;
    }

    .badge.bg-danger {
        background-color: #dc3545 !important;
    }

    .badge.bg-secondary {
        background-color: #6c757d !important;
    }

    /* Table styling */
    .table-borderless td {
        border: none;
        padding: 0.25rem 0.5rem;
    }

    .table-borderless td:first-child {
        padding-left: 0;
        font-weight: 500;
        color: #6c757d;
        width: 30%;
    }

    /* Card border colors */
    .card.border-info {
        border-color: #17a2b8 !important;
    }

    .card.border-warning {
        border-color: #ffc107 !important;
    }

    /* Price card styling */
    .card.bg-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        border: none;
    }

    .card.bg-success .card-body {
        padding: 1.5rem;
    }

    /* Summary card styling */
    .card.bg-light {
        background-color: #f8f9fa !important;
        border: 1px solid #e9ecef;
    }

    /* Modal styling */
    .modal-content {
        border-radius: 10px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        border-bottom: 1px solid #e9ecef;
        background-color: #f8f9fa;
        border-radius: 10px 10px 0 0;
    }

    .modal-body h6 {
        color: #007bff;
        font-weight: 600;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
    }

    .modal-body h6:first-child {
        margin-top: 0;
    }

    .modal-body ul {
        margin-bottom: 1rem;
    }

    .modal-body li {
        margin-bottom: 0.25rem;
        color: #495057;
    }

    /* Invalid feedback styling */
    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;
    }

    .form-control.is-invalid,
    .form-check-input.is-invalid {
        border-color: #dc3545;
    }

    .form-control.is-invalid:focus,
    .form-check-input.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    /* Character counter */
    .char-counter {
        font-size: 0.875em;
        text-align: right;
        margin-top: 0.25rem;
    }

    .char-counter.text-warning {
        color: #ffc107 !important;
    }

    .char-counter.text-danger {
        color: #dc3545 !important;
    }

    /* Loading state */
    .btn.loading {
        position: relative;
        color: transparent !important;
        pointer-events: none;
    }

    .btn.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 16px;
        height: 16px;
        margin: -8px 0 0 -8px;
        border: 2px solid #ffffff;
        border-top: 2px solid transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .card-body {
            padding: 1rem;
        }

        .btn-lg {
            font-size: 1rem;
            padding: 0.75rem 1rem;
        }

        .h3 {
            font-size: 1.5rem;
        }

        .row.mb-3 .col-sm-6 {
            margin-bottom: 0.75rem;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }

        .d-flex.justify-content-between .btn {
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 576px) {
        .breadcrumb {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }

        .card-header h4 {
            font-size: 1.1rem;
        }

        .alert h6 {
            font-size: 1rem;
        }

        .modal-dialog {
            margin: 0.5rem;
        }
    }

    /* Focus states for accessibility */
    .btn:focus,
    .form-control:focus,
    .form-check-input:focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Link styling */
    a {
        color: #007bff;
        text-decoration: none;
        transition: color 0.2s ease-in-out;
    }

    a:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    /* Icon spacing */
    .fas, .far, .fab {
        margin-right: 0.25rem;
    }

    /* Animation for form submission */
    .btn[type="submit"]:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Fade in animation */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form submission with loading state
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;

    form.addEventListener('submit', function(e) {
        // Validate form before submission
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            form.classList.add('was-validated');
            return;
        }

        // Check if checkbox is checked
        const checkbox = form.querySelector('#setuju');
        if (!checkbox.checked) {
            e.preventDefault();
            alert('Anda harus menyetujui syarat dan ketentuan terlebih dahulu.');
            checkbox.focus();
            return;
        }

        // Add loading state
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;

        // Optional: Add timeout to prevent infinite loading
        setTimeout(function() {
            if (submitBtn.classList.contains('loading')) {
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        }, 10000); // 10 seconds timeout
    });

    // Character counter for textarea
    const textarea = document.getElementById('catatan');
    const maxLength = 1000;
    
    if (textarea) {
        // Create character counter element
        const counter = document.createElement('div');
        counter.className = 'form-text text-end char-counter';
        counter.innerHTML = `<span id="char-count">0</span>/${maxLength} karakter`;
        textarea.parentNode.appendChild(counter);

        const charCount = document.getElementById('char-count');

                textarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCount.textContent = currentLength;
            
            // Update counter color based on usage
            counter.classList.remove('text-muted', 'text-warning', 'text-danger');
            
            if (currentLength > maxLength * 0.9) {
                counter.classList.add('text-warning');
            } else if (currentLength >= maxLength) {
                counter.classList.add('text-danger');
            } else {
                counter.classList.add('text-muted');
            }
        });

        // Initialize counter
        textarea.dispatchEvent(new Event('input'));
    }

    // Date input validation
    const dateInput = document.getElementById('tanggal_deadline');
    if (dateInput) {
        // Set minimum date to 7 days from now
        const minDate = new Date();
        minDate.setDate(minDate.getDate() + 7);
        dateInput.min = minDate.toISOString().split('T')[0];

        // Validate date on change
        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            const minAllowedDate = new Date();
            minAllowedDate.setDate(today.getDate() + 7);

            if (selectedDate < minAllowedDate) {
                this.setCustomValidity('Deadline minimal 7 hari dari sekarang');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
        });
    }

    // Smooth scroll to error messages
    const errorElements = document.querySelectorAll('.is-invalid');
    if (errorElements.length > 0) {
        errorElements[0].scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }

    // Auto-hide success/error alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-success, .alert-danger');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert && alert.parentNode) {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(function() {
                    if (alert && alert.parentNode) {
                        alert.remove();
                    }
                }, 500);
            }
        }, 5000);
    });

    // Form validation styling
    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach(function(input) {
        input.addEventListener('blur', function() {
            if (this.checkValidity()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });

        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid') && this.checkValidity()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });

    // Checkbox validation
    const checkbox = document.getElementById('setuju');
    if (checkbox) {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    }

    // Modal enhancement
    const modal = document.getElementById('syaratKetentuanModal');
    if (modal) {
        modal.addEventListener('shown.bs.modal', function() {
            // Focus on close button for accessibility
            const closeBtn = modal.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.focus();
            }
        });
    }

    // Prevent double submission
    let isSubmitting = false;
    form.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }
        isSubmitting = true;
        
        // Reset flag after timeout
        setTimeout(function() {
            isSubmitting = false;
        }, 3000);
    });

    // Add fade-in animation to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(function(card, index) {
        card.style.animationDelay = (index * 0.1) + 's';
        card.classList.add('fade-in');
    });

    // Price formatting (if needed for dynamic updates)
    function formatPrice(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount).replace('IDR', 'Rp');
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + Enter to submit form
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            if (form.checkValidity()) {
                form.submit();
            }
        }
        
        // Escape to close modal
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal.show');
            if (openModal) {
                const modal = bootstrap.Modal.getInstance(openModal);
                if (modal) {
                    modal.hide();
                }
            }
        }
    });

    // Tooltip initialization (if Bootstrap tooltips are used)
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Enhanced form validation messages
    const customValidationMessages = {
        'catatan': {
            'tooLong': 'Catatan terlalu panjang. Maksimal 1000 karakter.'
        },
        'tanggal_deadline': {
            'valueMissing': 'Tanggal deadline harus diisi.',
            'rangeUnderflow': 'Deadline minimal 7 hari dari sekarang.'
        },
        'setuju': {
            'valueMissing': 'Anda harus menyetujui syarat dan ketentuan.'
        }
    };

    // Apply custom validation messages
    Object.keys(customValidationMessages).forEach(function(fieldName) {
        const field = document.getElementById(fieldName);
        if (field) {
            field.addEventListener('invalid', function(e) {
                const validity = e.target.validity;
                const messages = customValidationMessages[fieldName];
                
                if (validity.valueMissing && messages.valueMissing) {
                    e.target.setCustomValidity(messages.valueMissing);
                } else if (validity.tooLong && messages.tooLong) {
                    e.target.setCustomValidity(messages.tooLong);
                } else if (validity.rangeUnderflow && messages.rangeUnderflow) {
                    e.target.setCustomValidity(messages.rangeUnderflow);
                }
            });
            
            field.addEventListener('input', function(e) {
                e.target.setCustomValidity('');
            });
        }
    });

    // Performance optimization: Debounce textarea input
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    if (textarea) {
        const debouncedInput = debounce(function(e) {
            // Any expensive operations on textarea input can go here
            console.log('Textarea updated:', e.target.value.length, 'characters');
        }, 300);

        textarea.addEventListener('input', debouncedInput);
    }

    // Accessibility improvements
    const requiredFields = document.querySelectorAll('[required]');
    requiredFields.forEach(function(field) {
        const label = document.querySelector(`label[for="${field.id}"]`);
        if (label && !label.querySelector('.required-indicator')) {
            const indicator = document.createElement('span');
            indicator.className = 'required-indicator text-danger';
            indicator.innerHTML = ' *';
            indicator.setAttribute('aria-label', 'required');
            label.appendChild(indicator);
        }
    });

    // Add loading overlay for better UX
    function showLoadingOverlay() {
        const overlay = document.createElement('div');
        overlay.id = 'loading-overlay';
        overlay.innerHTML = `
            <div class="d-flex justify-content-center align-items-center h-100">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-2">Memproses pesanan...</div>
                </div>
            </div>
        `;
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            z-index: 9999;
            display: flex;
        `;
        document.body.appendChild(overlay);
    }

    // Show loading overlay on form submit
    form.addEventListener('submit', function(e) {
        if (form.checkValidity()) {
            setTimeout(showLoadingOverlay, 100);
        }
    });
});
</script>
@endpush
