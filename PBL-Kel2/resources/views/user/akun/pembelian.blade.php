@extends('user.akun.layouts')

@section('content')
    <div class="content-area">
        <div class="page-header mb-4">
            <h2 class="page-title">
                <i class="fas fa-shopping-cart me-2 text-muted"></i>Riwayat Pembelian Buku
            </h2>
            <p class="text-muted mb-0">Kelola dan pantau status pembelian buku Anda</p>
        </div>

        <!-- Statistik Ringkas -->
        <div class="row mb-4">
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="stats-content">
                        <h4 class="stats-number">{{ $statistik['total_pesanan'] }}</h4>
                        <span class="stats-label">Total Pesanan</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon text-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-content">
                        <h4 class="stats-number">{{ $statistik['menunggu_pembayaran'] }}</h4>
                        <span class="stats-label">Menunggu Bayar</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon text-info">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="stats-content">
                        <h4 class="stats-number">{{ $statistik['diproses'] }}</h4>
                        <span class="stats-label">Diproses</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon text-primary">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="stats-content">
                        <h4 class="stats-number">{{ $statistik['dikirim'] }}</h4>
                        <span class="stats-label">Dikirim</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon text-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-content">
                        <h4 class="stats-number">{{ $statistik['selesai'] }}</h4>
                        <span class="stats-label">Selesai</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon text-secondary">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stats-content">
                        <h4 class="stats-number">{{ number_format($statistik['total_nilai'], 0, ',', '.') }}</h4>
                        <span class="stats-label">Total Nilai (Rp)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs-container mb-4">
            <ul class="nav nav-pills filter-tabs" id="pesananTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="semua-tab" data-bs-toggle="pill" data-bs-target="#semua"
                        type="button" role="tab">
                        <i class="fas fa-list me-2"></i>Semua <span class="badge">{{ $statistik['total_pesanan'] }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="menunggu-tab" data-bs-toggle="pill" data-bs-target="#menunggu"
                        type="button" role="tab">
                        <i class="fas fa-clock me-2"></i>Menunggu Pembayaran <span
                            class="badge">{{ $statistik['menunggu_pembayaran'] }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="proses-tab" data-bs-toggle="pill" data-bs-target="#proses" type="button"
                        role="tab">
                        <i class="fas fa-cog me-2"></i>Diproses <span class="badge">{{ $statistik['diproses'] }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="selesai-tab" data-bs-toggle="pill" data-bs-target="#selesai" type="button"
                        role="tab">
                        <i class="fas fa-check me-2"></i>Selesai <span class="badge">{{ $statistik['selesai'] }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="fisik-tab" data-bs-toggle="pill" data-bs-target="#fisik" type="button"
                        role="tab">
                        <i class="fas fa-book me-2"></i>Buku Fisik <span
                            class="badge">{{ $statistik['total_fisik'] }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ebook-tab" data-bs-toggle="pill" data-bs-target="#ebook" type="button"
                        role="tab">
                        <i class="fas fa-tablet-alt me-2"></i>E-book <span
                            class="badge">{{ $statistik['total_ebook'] }}</span>
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="pesananTabsContent">
            <!-- Tab Semua Pesanan -->
            <div class="tab-pane fade show active" id="semua" role="tabpanel">
                @include('user.akun.partials.pesanan-list', ['pesanan' => $pesananBuku])
            </div>

            <!-- Tab Menunggu Pembayaran -->
            <div class="tab-pane fade" id="menunggu" role="tabpanel">
                @include('user.akun.partials.pesanan-list', ['pesanan' => $pesananBuku->where('status', 'menunggu_pembayaran')])
            </div>

            <!-- Tab Diproses -->
            <div class="tab-pane fade" id="proses" role="tabpanel">
                @include('user.akun.partials.pesanan-list', ['pesanan' => $pesananBuku->whereIn('status', ['terverifikasi', 'diproses', 'dikirim'])])
            </div>

            <!-- Tab Selesai -->
            <div class="tab-pane fade" id="selesai" role="tabpanel">
                @include('user.akun.partials.pesanan-list', ['pesanan' => $pesananBuku->where('status', 'selesai')])
            </div>

            <!-- Tab Buku Fisik -->
            <div class="tab-pane fade" id="fisik" role="tabpanel">
                @include('user.akun.partials.pesanan-list', ['pesanan' => $pesananBuku->where('tipe_buku', 'fisik')])
            </div>

            <!-- Tab E-book -->
            <div class="tab-pane fade" id="ebook" role="tabpanel">
                @include('user.akun.partials.pesanan-list', ['pesanan' => $pesananBuku->where('tipe_buku', 'ebook')])
            </div>
        </div>

        <!-- Filter dan Pencarian -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="filter-card">
                    <div class="filter-header">
                        <h6 class="filter-title">
                            <i class="fas fa-filter me-2"></i>Filter & Pencarian
                        </h6>
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filterCollapse">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="collapse" id="filterCollapse">
                        <div class="filter-body">
                            <form method="GET" action="{{ route('akun.pembelian') }}" id="filterForm">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Pencarian</label>
                                        <input type="text" class="form-control" name="search"
                                            value="{{ request('search') }}"
                                            placeholder="Cari judul buku atau nomor pesanan...">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="">Semua Status</option>
                                            <option value="menunggu_pembayaran" {{ request('status') === 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                            <option value="menunggu_verifikasi" {{ request('status') === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                            <option value="terverifikasi" {{ request('status') === 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                                            <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>
                                                Diproses</option>
                                            <option value="dikirim" {{ request('status') === 'dikirim' ? 'selected' : '' }}>
                                                Dikirim</option>
                                            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>
                                                Selesai</option>
                                            <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Tipe Buku</label>
                                        <select class="form-select" name="tipe_buku">
                                            <option value="">Semua Tipe</option>
                                            <option value="fisik" {{ request('tipe_buku') === 'fisik' ? 'selected' : '' }}>
                                                Buku Fisik</option>
                                            <option value="ebook" {{ request('tipe_buku') === 'ebook' ? 'selected' : '' }}>
                                                E-book</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Tanggal Dari</label>
                                        <input type="date" class="form-control" name="tanggal_dari"
                                            value="{{ request('tanggal_dari') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Tanggal Sampai</label>
                                        <input type="date" class="form-control" name="tanggal_sampai"
                                            value="{{ request('tanggal_sampai') }}">
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <a href="{{ route('akun.pembelian') }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-times me-1"></i>Reset Filter
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            :root {
                --primary-color: #6366f1;
                --secondary-color: #64748b;
                --success-color: #10b981;
                --warning-color: #f59e0b;
                --danger-color: #ef4444;
                --info-color: #3b82f6;
                --light-bg: #f8fafc;
                --border-color: #e2e8f0;
                --text-muted: #64748b;
                --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
                --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            }

            .content-area {
                padding: 1.5rem;
                background-color: var(--light-bg);
                min-height: 100vh;
            }

            .page-header {
                background: white;
                padding: 1.5rem;
                border-radius: 0.75rem;
                box-shadow: var(--shadow-sm);
                border: 1px solid var(--border-color);
            }

            .page-title {
                color: #1e293b;
                margin-bottom: 0.5rem;
                font-weight: 600;
                font-size: 1.5rem;
            }

            /* Stats Cards */
            .stats-card {
                background: white;
                border: 1px solid var(--border-color);
                border-radius: 0.75rem;
                padding: 1.25rem;
                display: flex;
                align-items: center;
                gap: 1rem;
                transition: all 0.2s ease;
                box-shadow: var(--shadow-sm);
            }

            .stats-card:hover {
                box-shadow: var(--shadow-md);
                transform: translateY(-1px);
            }

            .stats-icon {
                width: 48px;
                height: 48px;
                border-radius: 0.75rem;
                background-color: #f1f5f9;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--secondary-color);
                font-size: 1.25rem;
                flex-shrink: 0;
            }

            .stats-icon.text-warning {
                background-color: #fef3c7;
                color: var(--warning-color);
            }

            .stats-icon.text-info {
                background-color: #dbeafe;
                color: var(--info-color);
            }

            .stats-icon.text-primary {
                background-color: #e0e7ff;
                color: var(--primary-color);
            }

            .stats-icon.text-success {
                background-color: #d1fae5;
                color: var(--success-color);
            }

            .stats-icon.text-secondary {
                background-color: #f1f5f9;
                color: var(--secondary-color);
            }

            .stats-content {
                flex: 1;
            }

            .stats-number {
                font-size: 1.5rem;
                font-weight: 700;
                color: #1e293b;
                margin-bottom: 0.25rem;
                line-height: 1;
            }

            .stats-label {
                font-size: 0.875rem;
                color: var(--text-muted);
                font-weight: 500;
            }

            /* Filter Tabs */
            .filter-tabs-container {
                background: white;
                border-radius: 0.75rem;
                padding: 1rem;
                box-shadow: var(--shadow-sm);
                border: 1px solid var(--border-color);
            }

            .filter-tabs {
                border: none;
                gap: 0.5rem;
                flex-wrap: wrap;
            }

            .filter-tabs .nav-link {
                background: #f8fafc;
                border: 1px solid var(--border-color);
                color: var(--secondary-color);
                border-radius: 0.5rem;
                padding: 0.75rem 1rem;
                font-weight: 500;
                font-size: 0.875rem;
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                white-space: nowrap;
            }

            .filter-tabs .nav-link:hover {
                background: #e2e8f0;
                color: #475569;
                transform: translateY(-1px);
            }

            .filter-tabs .nav-link.active {
                background: var(--primary-color);
                border-color: var(--primary-color);
                color: white;
            }

            .filter-tabs .nav-link .badge {
                background: rgba(255, 255, 255, 0.2);
                color: inherit;
                font-size: 0.75rem;
                margin-left: 0.5rem;
                padding: 0.25rem 0.5rem;
            }

            .filter-tabs .nav-link.active .badge {
                background: rgba(255, 255, 255, 0.3);
            }

            /* Filter Card */
            .filter-card {
                background: white;
                border: 1px solid var(--border-color);
                border-radius: 0.75rem;
                box-shadow: var(--shadow-sm);
            }

            .filter-header {
                padding: 1rem 1.25rem;
                border-bottom: 1px solid var(--border-color);
                display: flex;
                justify-content: between;
                align-items: center;
            }

            .filter-title {
                margin: 0;
                color: #374151;
                font-weight: 600;
                flex: 1;
            }

            .filter-body {
                padding: 1.25rem;
            }

            /* Form Controls */
            .form-label {
                font-weight: 500;
                color: #374151;
                margin-bottom: 0.5rem;
                font-size: 0.875rem;
            }

            .form-control,
            .form-select {
                border: 1px solid var(--border-color);
                border-radius: 0.5rem;
                padding: 0.75rem;
                font-size: 0.875rem;
                transition: all 0.2s ease;
            }

            .form-control:focus,
            .form-select:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            }

            /* Buttons */
            .btn {
                border-radius: 0.5rem;
                font-weight: 500;
                padding: 0.75rem 1rem;
                transition: all 0.2s ease;
                border: 1px solid transparent;
            }

            .btn-primary {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
                color: white;
            }

            .btn-primary:hover {
                background-color: #5855eb;
                border-color: #5855eb;
                transform: translateY(-1px);
            }

            .btn-outline-secondary {
                color: var(--secondary-color);
                border-color: var(--border-color);
                background: white;
            }

            .btn-outline-secondary:hover {
                background-color: #f8fafc;
                border-color: var(--secondary-color);
                color: #475569;
            }

            .btn-sm {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .content-area {
                    padding: 1rem;
                }

                .page-header {
                    padding: 1rem;
                }

                .stats-card {
                    padding: 1rem;
                }

                .stats-number {
                    font-size: 1.25rem;
                }

                .filter-tabs {
                    justify-content: center;
                }

                .filter-tabs .nav-link {
                    font-size: 0.8rem;
                    padding: 0.5rem 0.75rem;
                }

                .filter-body .row {
                    --bs-gutter-x: 1rem;
                }
            }

            @media (max-width: 576px) {
                .stats-card {
                    flex-direction: column;
                    text-align: center;
                    gap: 0.75rem;
                }

                .filter-tabs .nav-link {
                    flex: 1;
                    justify-content: center;
                    min-width: 0;
                }

                .filter-tabs .nav-link span {
                    display: none;
                }
            }

            /* Tab Content */
            .tab-content {
                background: white;
                border-radius: 0.75rem;
                box-shadow: var(--shadow-sm);
                border: 1px solid var(--border-color);
                min-height: 400px;
            }

            .tab-pane {
                padding: 1.5rem;
            }

            /* Empty State */
            .empty-state {
                text-align: center;
                padding: 3rem 1rem;
                color: var(--text-muted);
            }

            .empty-state i {
                font-size: 3rem;
                margin-bottom: 1rem;
                opacity: 0.5;
            }

            .empty-state h5 {
                color: #374151;
                margin-bottom: 0.5rem;
            }

            /* Loading State */
            .loading-state {
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 3rem;
                color: var(--text-muted);
            }

            .loading-spinner {
                width: 2rem;
                height: 2rem;
                border: 2px solid var(--border-color);
                border-top: 2px solid var(--primary-color);
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin-right: 1rem;
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }

            /* Smooth transitions */
            .tab-pane {
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .tab-pane.show.active {
                opacity: 1;
            }

            /* Focus states for accessibility */
            .nav-link:focus,
            .btn:focus,
            .form-control:focus,
            .form-select:focus {
                outline: 2px solid var(--primary-color);
                outline-offset: 2px;
            }

            /* Print styles */
            @media print {

                .filter-tabs-container,
                .filter-card {
                    display: none;
                }

                .content-area {
                    background: white;
                    padding: 0;
                }

                .stats-card {
                    box-shadow: none;
                    border: 1px solid #ccc;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Auto-refresh data setiap 30 detik
                setInterval(function () {
                    refreshPesananData();
                }, 30000);

                // Handle tab switching
                const tabButtons = document.querySelectorAll('[data-bs-toggle="pill"]');
                tabButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const targetTab = this.getAttribute('data-bs-target');
                        showLoadingState(targetTab);

                        setTimeout(() => {
                            hideLoadingState(targetTab);
                        }, 500);
                    });
                });

                // Handle filter form submission
                const filterForm = document.getElementById('filterForm');
                if (filterForm) {
                    filterForm.addEventListener('submit', function (e) {
                        showPageLoading();
                    });
                }
            });

            function refreshPesananData() {
                fetch('{{ route("akun.pembelian.refresh") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.updates.length > 0) {
                            updatePesananStatus(data.updates);
                            showNotification('Data pesanan telah diperbarui', 'success');
                        }
                    })
                    .catch(error => {
                        console.log('Refresh error:', error);
                    });
            }

            function updatePesananStatus(updates) {
                updates.forEach(update => {
                    const statusElement = document.querySelector(`[data-pesanan-id="${update.id}"] .status-badge`);
                    if (statusElement) {
                        statusElement.className = `badge ${update.badge_class}`;
                        statusElement.textContent = update.status_text;
                    }
                });
            }

            function showLoadingState(targetTab) {
                const tabPane = document.querySelector(targetTab);
                if (tabPane) {
                    tabPane.innerHTML = `
                    <div class="loading-state">
                        <div class="loading-spinner"></div>
                        <span>Memuat data...</span>
                    </div>
                `;
                }
            }

            function hideLoadingState(targetTab) {
                // This would normally reload the content
                // For now, we'll just hide the loading state
                const tabPane = document.querySelector(targetTab);
                if (tabPane && tabPane.querySelector('.loading-state')) {
                    location.reload(); // Simple reload for now
                }
            }

            function showPageLoading() {
                const submitButton = document.querySelector('#filterForm button[type="submit"]');
                if (submitButton) {
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    submitButton.disabled = true;
                }
            }

            function showNotification(message, type = 'info') {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
                notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

                document.body.appendChild(notification);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 5000);
            }

            // Keyboard shortcuts
            document.addEventListener('keydown', function (e) {
                // Ctrl/Cmd + F to focus search
                if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                    e.preventDefault();
                    const searchInput = document.querySelector('input[name="search"]');
                    if (searchInput) {
                        searchInput.focus();
                    }
                }

                // Escape to clear search
                if (e.key === 'Escape') {
                    const searchInput = document.querySelector('input[name="search"]');
                    if (searchInput && searchInput === document.activeElement) {
                        searchInput.value = '';
                    }
                }
            });
        </script>
    @endpush
@endsection