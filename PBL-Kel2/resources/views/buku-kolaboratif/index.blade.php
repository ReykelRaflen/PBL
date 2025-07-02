@extends('user.layouts.app')
@section('title', 'Buku Kolaboratif')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Buku Kolaboratif</h2>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    @auth
                        <a href="{{ route('user.pesanan.index') }}" class="btn btn-outline-success">
                            <i class="fas fa-list me-2"></i>Pesanan Saya
                        </a>
                    @endauth
                </div>
            </div>
            <!-- Filter Form -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('buku-kolaboratif.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Cari Buku</label>
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Judul buku...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Kategori</label>
                                <select class="form-select" name="kategori">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategoriBuku as $kategori)
                                    <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Harga Min</label>
                                <input type="number" class="form-control" name="harga_min" value="{{ request('harga_min') }}" placeholder="0">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Harga Max</label>
                                <input type="number" class="form-control" name="harga_max" value="{{ request('harga_max') }}" placeholder="1000000">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>Cari
                                    </button>
                                    <a href="{{ route('buku-kolaboratif.index') }}" class="btn btn-outline-secondary">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Statistik Ringkas -->
            @if($bukuKolaboratif->count() > 0)
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-book fa-2x mb-2"></i>
                            <h4>{{ $bukuKolaboratif->total() }}</h4>
                            <small>Total Buku</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-file-alt fa-2x mb-2"></i>
                            <h4>{{ $bukuKolaboratif->sum('bab_tersedia') }}</h4>
                            <small>Bab Tersedia</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                                                      <i class="fas fa-tags fa-2x mb-2"></i>
                            <h4>{{ $kategoriBuku->count() }}</h4>
                            <small>Kategori</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                            <h4>Rp {{ number_format($bukuKolaboratif->sum('total_nilai_proyek'), 0, ',', '.') }}</h4>
                            <small>Total Nilai Proyek</small>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <!-- Daftar Buku -->
            <div class="row">
                @forelse($bukuKolaboratif as $buku)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 card-hover shadow-sm">
                        @if($buku->gambar_sampul)
                        <img src="{{ asset('storage/' . $buku->gambar_sampul) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                        @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-book fa-3x text-muted"></i>
                        </div>
                        @endif
                                               
                        <!-- Badge Status -->
                        <div class="position-absolute top-0 end-0 m-2">
                            @if($buku->bab_tersedia > 0)
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Tersedia
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-clock me-1"></i>Penuh
                                </span>
                            @endif
                        </div>
                        <!-- Badge Kategori -->
                        @if($buku->kategoriBuku)
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-primary">{{ $buku->kategoriBuku->nama }}</span>
                        </div>
                        @endif
                                               
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $buku->judul }}</h5>
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($buku->deskripsi, 100) }}
                            </p>
                                                       
                            <!-- Statistik Bab -->
                            <div class="mb-3">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <small class="text-muted">Total Bab</small>
                                        <div class="fw-bold">{{ $buku->total_bab }}</div>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted">Tersedia</small>
                                        <div class="fw-bold text-success">{{ $buku->bab_tersedia }}</div>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted">Target</small>
                                        <div class="fw-bold">{{ ucfirst($buku->target_pembaca ?? 'Umum') }}</div>
                                    </div>
                                </div>
                            </div>
                            <!-- Progress Bar -->
                            @php
                                $progress = $buku->progress;
                                $persenSelesai = $progress['persen_selesai'];
                                $persenDipesan = $progress['persen_dipesan'];
                                $persenTersedia = $progress['persen_tersedia'];
                            @endphp
                            <div class="mb-3">
                                <small class="text-muted">Progress Penulisan</small>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: {{ $persenSelesai }}%" title="Selesai: {{ $progress['selesai'] }} bab"></div>
                                    <div class="progress-bar bg-warning" style="width: {{ $persenDipesan }}%" title="Dalam Proses: {{ $progress['dipesan'] }} bab"></div>
                                    <div class="progress-bar bg-info" style="width: {{ $persenTersedia }}%" title="Tersedia: {{ $progress['tersedia'] }} bab"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-success">{{ $progress['selesai'] }} selesai</small>
                                    <small class="text-warning">{{ $progress['dipesan'] }} proses</small>
                                    <small class="text-info">{{ $progress['tersedia'] }} tersedia</small>
                                </div>
                            </div>
                            <!-- Informasi Harga -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Rentang Harga:</span>
                                    <span class="fw-bold text-primary">{{ $buku->rentang_harga }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Total Nilai Proyek:</span>
                                    <span class="fw-bold text-success">Rp {{ number_format($buku->total_nilai_proyek, 0, ',', '.') }}</span>
                                </div>
                                {{-- @if($buku->getTotalEstimasiKata() > 0)
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Estimasi Kata:</span>
                                    <span class="fw-bold text-info">~{{ number_format($buku->getTotalEstimasiKata()) }} kata</span>
                                </div>
                                @endif --}}
                            </div>
                            
                            <!-- Action Button -->
                            <div class="mt-auto">
                                @if($buku->isAvailable())
                                    <a href="{{ route('buku-kolaboratif.tampilkan', $buku->id) }}" class="btn btn-primary w-100">
                                        <i class="fas fa-eye me-2"></i>Lihat Detail & Pilih Bab
                                    </a>
                                @else
                                    <a href="{{ route('buku-kolaboratif.tampilkan', $buku->id) }}" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-eye me-2"></i>Lihat Detail
                                    </a>
                                @endif
                            </div>
                        </div>
                        <!-- Card Footer dengan Info Tambahan -->
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $buku->created_at->diffForHumans() }}
                                </small>
                                @if($buku->bab_tersedia > 0)
                                    <small class="text-success fw-bold">
                                        <i class="fas fa-pen me-1"></i>
                                        {{ $buku->bab_tersedia }} bab siap ditulis
                                    </small>
                                @else
                                    <small class="text-muted">
                                        <i class="fas fa-users me-1"></i>
                                        Semua bab sudah dipesan
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada buku kolaboratif tersedia</h5>
                        <p class="text-muted">Silakan coba lagi nanti atau ubah filter pencarian Anda.</p>
                                               
                        @if(request()->hasAny(['search', 'kategori', 'harga_min', 'harga_max']))
                        <div class="mt-3">
                            <a href="{{ route('buku-kolaboratif.index') }}" class="btn btn-primary">
                                <i class="fas fa-refresh me-2"></i>Lihat Semua Buku
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endforelse
            </div>
            <!-- Pagination -->
            @if($bukuKolaboratif->hasPages())
            <div class="d-flex justify-content-center">
                {{ $bukuKolaboratif->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Filter Advanced -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">
                    <i class="fas fa-filter me-2"></i>Filter Lanjutan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('buku-kolaboratif.index') }}">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Pencarian</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Judul, deskripsi, atau kategori...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" name="kategori">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoriBuku as $kategori)
                                <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga Minimum</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" name="harga_min" value="{{ request('harga_min') }}" placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga Maksimum</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" name="harga_max" value="{{ request('harga_max') }}" placeholder="1000000">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status Ketersediaan</label>
                            <select class="form-select" name="ketersediaan">
                                <option value="">Semua Status</option>
                                <option value="tersedia" {{ request('ketersediaan') == 'tersedia' ? 'selected' : '' }}>Ada Bab Tersedia</option>
                                <option value="penuh" {{ request('ketersediaan') == 'penuh' ? 'selected' : '' }}>Semua Bab Dipesan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Urutkan Berdasarkan</label>
                            <select class="form-select" name="sort">
                                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                                <option value="harga_terendah" {{ request('sort') == 'harga_terendah' ? 'selected' : '' }}>Harga Terendah</option>
                                <option value="harga_tertinggi" {{ request('sort') == 'harga_tertinggi' ? 'selected' : '' }}>Harga Tertinggi</option>
                                <option value="bab_terbanyak" {{ request('sort') == 'bab_terbanyak' ? 'selected' : '' }}>Bab Terbanyak</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('buku-kolaboratif.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-refresh me-2"></i>Reset Filter
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Notification -->
@if(session('success') || session('error') || session('info'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            @if(session('success'))
                <i class="fas fa-check-circle text-success me-2"></i>
                <strong class="me-auto">Berhasil</strong>
            @elseif(session('error'))
                <i class="fas fa-exclamation-circle text-danger me-2"></i>
                <strong class="me-auto">Error</strong>
            @else
                           <i class="fas fa-info-circle text-info me-2"></i>
                <strong class="me-auto">Info</strong>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ session('success') ?? session('error') ?? session('info') }}
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    .card-hover {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    .progress {
        background-color: #e9ecef;
        border-radius: 4px;
    }
    .progress-bar {
        transition: width 0.6s ease;
    }
    .badge {
        font-size: 0.75em;
    }
    .card-img-top {
        transition: transform 0.3s ease;
    }
    .card-hover:hover .card-img-top {
        transform: scale(1.05);
    }
    .position-absolute {
        z-index: 10;
    }
    .card-footer {
        border-top: 1px solid rgba(0,0,0,.125);
        background-color: rgba(0,0,0,.03);
    }
    .btn {
        transition: all 0.2s ease-in-out;
    }
    .btn:hover {
        transform: translateY(-1px);
    }
    .toast {
        min-width: 300px;
    }
    .modal-content {
        border-radius: 10px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .modal-header {
        border-bottom: 1px solid #dee2e6;
        background-color: #f8f9fa;
        border-radius: 10px 10px 0 0;
    }
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .bg-gradient-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
    }
    .bg-gradient-success {
        background: linear-gradient(45deg, #28a745, #1e7e34);
    }
    .bg-gradient-info {
        background: linear-gradient(45deg, #17a2b8, #117a8b);
    }
    .bg-gradient-warning {
        background: linear-gradient(45deg, #ffc107, #e0a800);
    }
    @media (max-width: 768px) {
        .card-hover:hover {
            transform: none;
        }
               
        .card-hover:hover .card-img-top {
            transform: none;
        }
               
        .btn:hover {
            transform: none;
        }
    }
    .pagination {
        margin-bottom: 0;
    }
    .page-link {
        color: #007bff;
        border-color: #dee2e6;
    }
    .page-link:hover {
        color: #0056b3;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
    .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }
    .empty-state {
        padding: 3rem 1rem;
    }
    .empty-state i {
        opacity: 0.5;
    }
    .filter-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto hide toast after 5 seconds
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(toast => {
        setTimeout(() => {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.hide();
        }, 5000);
    });

    // Filter badge counter
    const filterBtn = document.querySelector('[data-bs-target="#filterModal"]');
    if (filterBtn) {
        const urlParams = new URLSearchParams(window.location.search);
        const filterCount = Array.from(urlParams.keys()).filter(key =>
            ['search', 'kategori', 'harga_min', 'harga_max', 'ketersediaan', 'sort'].includes(key) &&
            urlParams.get(key) !== ''
        ).length;
               
        if (filterCount > 0) {
            filterBtn.style.position = 'relative';
            const badge = document.createElement('span');
            badge.className = 'filter-badge';
            badge.textContent = filterCount;
            filterBtn.appendChild(badge);
        }
    }

    // Smooth scroll for pagination
    const paginationLinks = document.querySelectorAll('.pagination a');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Let the default action happen, but add smooth scroll
            setTimeout(() => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }, 100);
        });
    });

    // Progress bar animation on scroll
    const progressBars = document.querySelectorAll('.progress-bar');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const progressBar = entry.target;
                const width = progressBar.style.width;
                progressBar.style.width = '0%';
                setTimeout(() => {
                    progressBar.style.width = width;
                }, 100);
            }
        });
    }, { threshold: 0.5 });

    progressBars.forEach(bar => {
        observer.observe(bar);
    });

    // Card hover effect enhancement
    const cards = document.querySelectorAll('.card-hover');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.zIndex = '10';
        });
               
        card.addEventListener('mouseleave', function() {
            this.style.zIndex = '1';
        });
    });

    // Search input enhancement
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const form = this.closest('form');
                       
            // Add loading state
            this.style.backgroundImage = 'url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'20\' height=\'20\' viewBox=\'0 0 24 24\'%3E%3Cpath fill=\'%23999\' d=\'M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z\' opacity=\'.25\'/%3E%3Cpath fill=\'%23999\' d=\'M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z\'%3E%3CanimateTransform attributeName=\'transform\' dur=\'0.75s\' repeatCount=\'indefinite\' type=\'rotate\' values=\'0 12 12;360 12 12\'/%3E%3C/path%3E%3C/svg%3E")';
            this.style.backgroundRepeat = 'no-repeat';
            this.style.backgroundPosition = 'right 10px center';
            this.style.backgroundSize = '20px';
                       
            // Auto submit after 1 second of no typing
            searchTimeout = setTimeout(() => {
                this.style.backgroundImage = '';
                // Uncomment below line if you want auto-submit
                // form.submit();
            }, 1000);
        });
    }
        
    // Price range validation
    const hargaMin = document.querySelector('input[name="harga_min"]');
    const hargaMax = document.querySelector('input[name="harga_max"]');
       
    if (hargaMin && hargaMax) {
        function validatePriceRange() {
            const min = parseInt(hargaMin.value) || 0;
            const max = parseInt(hargaMax.value) || 0;
                       
            if (min > 0 && max > 0 && min > max) {
                hargaMax.setCustomValidity('Harga maksimum harus lebih besar dari harga minimum');
                hargaMin.setCustomValidity('Harga minimum harus lebih kecil dari harga maksimum');
            } else {
                hargaMax.setCustomValidity('');
                hargaMin.setCustomValidity('');
            }
        }
               
        hargaMin.addEventListener('input', validatePriceRange);
        hargaMax.addEventListener('input', validatePriceRange);
    }

    // Format number inputs
    const numberInputs = document.querySelectorAll('input[type="number"]');
    numberInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value) {
                const value = parseInt(this.value);
                if (!isNaN(value)) {
                    // Add thousand separators for display
                    const formatted = value.toLocaleString('id-ID');
                    this.setAttribute('data-formatted', formatted);
                }
            }
        });
    });

    // Lazy loading for images
    const images = document.querySelectorAll('img[src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.classList.add('fade-in');
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => {
        imageObserver.observe(img);
    });

    // Enhanced card interactions
    const bookCards = document.querySelectorAll('.card-hover');
    bookCards.forEach(card => {
        const button = card.querySelector('.btn');
               
        card.addEventListener('click', function(e) {
            // If click is not on button, trigger button click
            if (!button.contains(e.target) && e.target !== button) {
                button.click();
            }
        });
               
        // Add keyboard navigation
        card.setAttribute('tabindex', '0');
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                button.click();
            }
        });
    });

    // Statistics counter animation
    const counters = document.querySelectorAll('.card h4');
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        counter.textContent = counter.textContent.replace(/\d+/, target.toLocaleString('id-ID'));
                        clearInterval(timer);
                    } else {
                        counter.textContent = counter.textContent.replace(/\d+/, Math.floor(current).toLocaleString('id-ID'));
                    }
                }, 20);
                counterObserver.unobserve(counter);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => {
        if (counter.textContent.match(/\d/)) {
            counterObserver.observe(counter);
        }
    });

    // Filter form enhancement
    const filterForm = document.querySelector('#filterModal form');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mencari...';
                       
            // Re-enable button after 3 seconds as fallback
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-search me-2"></i>Terapkan Filter';
            }, 3000);
        });
    }

    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], [title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Progress bar click to show details
    const progressContainers = document.querySelectorAll('.progress');
    progressContainers.forEach(container => {
        container.style.cursor = 'pointer';
        container.addEventListener('click', function() {
            const details = this.nextElementSibling;
            if (details && details.classList.contains('d-flex')) {
                details.style.display = details.style.display === 'none' ? 'flex' : 'none';
            }
        });
    });
});

// Utility functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

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

// Add fade-in animation CSS
const style = document.createElement('style');
style.textContent = 
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .loading-skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    .card-loading {
        pointer-events: none;
        opacity: 0.7;
    }
    
    .btn-loading {
        position: relative;
        color: transparent !important;
    }
    
    .btn-loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .filter-active {
        background-color: #e3f2fd !important;
        border-color: #2196f3 !important;
    }
    
    .sort-indicator {
        margin-left: 5px;
        opacity: 0.6;
    }
    
    .price-highlight {
        background: linear-gradient(45deg, #fff3cd, #ffeaa7);
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 600;
    }
    
    .availability-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    
    .availability-available {
        background-color: #28a745;
        box-shadow: 0 0 0 2px rgba(40, 167, 69, 0.3);
    }
    
    .availability-full {
        background-color: #6c757d;
        box-shadow: 0 0 0 2px rgba(108, 117, 125, 0.3);
    }
    
    .book-stats {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 8px;
        padding: 10px;
        margin: 10px 0;
    }
    
    .progress-enhanced {
        height: 6px;
        border-radius: 3px;
        overflow: hidden;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    
    .progress-enhanced .progress-bar {
        border-radius: 0;
        position: relative;
        overflow: hidden;
    }
    
    .progress-enhanced .progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 25%, rgba(255,255,255,0.2) 25%, rgba(255,255,255,0.2) 50%, transparent 50%, transparent 75%, rgba(255,255,255,0.2) 75%);
        background-size: 20px 20px;
        animation: progressStripes 1s linear infinite;
    }
    
    @keyframes progressStripes {
        0% { background-position: 0 0; }
        100% { background-position: 20px 0; }
    }
    
    .card-enhanced {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card-enhanced:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }
    
    .card-enhanced .card-body {
        padding: 1.5rem;
    }
    
    .card-enhanced .card-footer {
        background: rgba(248, 249, 250, 0.8);
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1rem 1.5rem;
    }
    
    .badge-enhanced {
        padding: 0.5em 0.8em;
        font-size: 0.7rem;
        font-weight: 600;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-enhanced {
        border-radius: 8px;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.2s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }
    
    .btn-enhanced::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-enhanced:hover::before {
        left: 100%;
    }
    
    .stats-card {
        background: linear-gradient(135deg, var(--bs-primary), var(--bs-primary-dark, #0056b3));
        border: none;
        border-radius: 12px;
        color: white;
        overflow: hidden;
        position: relative;
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30px, -30px);
    }
    
    .stats-card .card-body {
        position: relative;
        z-index: 1;
    }
    
    .empty-state-enhanced {
        padding: 4rem 2rem;
        text-align: center;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-radius: 12px;
        margin: 2rem 0;
    }
    
    .empty-state-enhanced i {
        font-size: 4rem;
        color: #6c757d;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .search-highlight {
        background-color: #fff3cd;
        padding: 1px 3px;
        border-radius: 2px;
        font-weight: 600;
    }
    
    @media (prefers-reduced-motion: reduce) {
        .card-hover:hover,
        .btn:hover,
        .progress-bar,
        .fade-in {
            transform: none !important;
            animation: none !important;
            transition: none !important;
        }
    }
    
    @media (max-width: 576px) {
        .card-enhanced .card-body {
            padding: 1rem;
        }
        
        .stats-card .card-body {
            padding: 1rem;
        }
        
        .btn-enhanced {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        .modal-lg {
            max-width: 95%;
        }
        
        .empty-state-enhanced {
            padding: 2rem 1rem;
        }
        
        .empty-state-enhanced i {
            font-size: 3rem;
        }
    }
;
document.head.appendChild(style);

// Performance optimization
if ('requestIdleCallback' in window) {
    requestIdleCallback(() => {
        // Initialize non-critical features
        console.log('Buku Kolaboratif page loaded successfully');
    });
}

// Error handling for images
document.addEventListener('error', function(e) {
    if (e.target.tagName === 'IMG') {
        e.target.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMTgiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIiBmaWxsPSIjOTk5Ij5UaWRhayBhZGEgZ2FtYmFyPC90ZXh0Pjwvc3ZnPg==';
        e.target.alt = 'Gambar tidak tersedia';
    }
}, true);

// Service Worker registration for offline support (optional)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('SW registered: ', registration);
            })
            .catch(registrationError => {
                console.log('SW registration failed: ', registrationError);
            });
    });
}
</script>
@endpush
