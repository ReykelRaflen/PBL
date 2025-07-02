@extends('user.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $bukuKolaboratif->judul }}</h4>
                    <div>
                        <a href="{{ route('buku-kolaboratif.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Informasi Buku -->
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h5>Deskripsi</h5>
                                <p class="text-muted">{{ $bukuKolaboratif->deskripsi }}</p>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <h5>Progress Penyelesaian</h5>
                                @php
                                    $totalBab = $bukuKolaboratif->total_bab;
                                    $babTersedia = $bukuKolaboratif->babBuku->where('status', 'tersedia')->count();
                                    $babDipesan = $bukuKolaboratif->babBuku->where('status', 'dipesan')->count();
                                    $babSelesai = $bukuKolaboratif->babBuku->where('status', 'selesai')->count();
                                    
                                    $persenTersedia = $totalBab > 0 ? ($babTersedia / $totalBab) * 100 : 0;
                                    $persenDipesan = $totalBab > 0 ? ($babDipesan / $totalBab) * 100 : 0;
                                    $persenSelesai = $totalBab > 0 ? ($babSelesai / $totalBab) * 100 : 0;
                                @endphp
                                
                                <div class="progress mb-2" style="height: 25px;">
                                    <div class="progress-bar bg-info" role="progressbar"
                                         style="width: {{ $persenSelesai }}%"
                                         aria-valuenow="{{ $persenSelesai }}" aria-valuemin="0" aria-valuemax="100">
                                        @if($persenSelesai > 10)
                                            Selesai {{ number_format($persenSelesai, 1) }}%
                                        @endif
                                    </div>
                                    <div class="progress-bar bg-warning" role="progressbar"
                                         style="width: {{ $persenDipesan }}%"
                                         aria-valuenow="{{ $persenDipesan }}" aria-valuemin="0" aria-valuemax="100">
                                        @if($persenDipesan > 10)
                                            Dipesan {{ number_format($persenDipesan, 1) }}%
                                        @endif
                                    </div>
                                    <div class="progress-bar bg-success" role="progressbar"
                                         style="width: {{ $persenTersedia }}%"
                                         aria-valuenow="{{ $persenTersedia }}" aria-valuemin="0" aria-valuemax="100">
                                        @if($persenTersedia > 10)
                                            Tersedia {{ number_format($persenTersedia, 1) }}%
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">
                                        <i class="fas fa-circle text-info"></i> Selesai: {{ $babSelesai }} bab
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-circle text-warning"></i> Dipesan: {{ $babDipesan }} bab
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-circle text-success"></i> Tersedia: {{ $babTersedia }} bab
                                    </small>
                                </div>
                            </div>
                            
                            <!-- Daftar Bab -->
                            @if($bukuKolaboratif->babBuku->count() > 0)
                            <div class="mb-4" id="daftar-bab">
                                <h5>Daftar Bab</h5>
                                <div class="row">
                                    @foreach($bukuKolaboratif->babBuku->sortBy('nomor_bab') as $bab)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card h-100 bab-card">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 text-primary">Bab {{ $bab->nomor_bab }}</h6>
                                                <!-- Status Badge -->
                                                @if($bab->status === 'tersedia')
                                                    <span class="badge bg-success">Tersedia</span>
                                                @elseif($bab->status === 'dipesan')
                                                    <span class="badge bg-warning">Dipesan</span>
                                                @elseif($bab->status === 'selesai')
                                                    <span class="badge bg-info">Selesai</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($bab->status) }}</span>
                                                @endif
                                            </div>
                                            
                                            <div class="card-body d-flex flex-column">
                                                <!-- Judul Bab -->
                                                <h6 class="card-title">{{ $bab->judul_bab }}</h6>
                                                
                                                <!-- Informasi Pembeli (jika sudah dipesan) -->
                                                @if($bab->status === 'dipesan' || $bab->status === 'selesai')
                                                    @php
                                                        $pesanan = $bab->pesananAktif;
                                                    @endphp
                                                    @if($pesanan && $pesanan->user)
                                                    <div class="mb-2">
                                                        <small class="text-muted">
                                                            @if($bab->status === 'dipesan')
                                                                Sedang dikerjakan oleh:
                                                            @else
                                                                Diselesaikan oleh:
                                                            @endif
                                                        </small><br>
                                                        <span class="badge bg-light text-dark border">
                                                            <i class="fas fa-user"></i> {{ $pesanan->user->name }}
                                                        </span>
                                                    </div>
                                                    @endif
                                                @endif

                                                <!-- Harga -->
                                                <div class="mb-3">
                                                    <small class="text-muted">Harga:</small><br>
                                                    <span class="h5 text-success mb-0">
                                                        @if(isset($bab->harga))
                                                            Rp {{ number_format($bab->harga, 0, ',', '.') }}
                                                        @else
                                                            Rp {{ number_format($bukuKolaboratif->harga_per_bab, 0, ',', '.') }}
                                                        @endif
                                                    </span>
                                                </div>

                                                <!-- Tanggal Pesanan dan Deadline (jika sudah dipesan) -->
                                                @if($bab->status === 'dipesan' || $bab->status === 'selesai')
                                                    @if($pesanan)
                                                    <div class="mb-3">
                                                        <div class="row">
                                                            <div class="col-12 mb-2">
                                                                <small class="text-muted">Tanggal Dipesan:</small><br>
                                                                <small class="text-info">
                                                                    <i class="fas fa-calendar-alt"></i>
                                                                    {{ $pesanan->created_at->format('d M Y') }}
                                                                </small>
                                                            </div>
                                                            @if($pesanan->tanggal_deadline)
                                                            <div class="col-12">
                                                                <small class="text-muted">Deadline:</small><br>
                                                                <small class="text-danger">
                                                                    <i class="fas fa-clock"></i>
                                                                    {{ $pesanan->tanggal_deadline->format('d M Y') }}
                                                                </small>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endif
                                                @endif

                                                <!-- Spacer untuk mendorong tombol ke bawah -->
                                                <div class="mt-auto">
                                                    @if($bab->status === 'tersedia')
                                                        <a href="{{ route('buku-kolaboratif.pilih-bab', [$bukuKolaboratif->id, $bab->id]) }}"
                                                           class="btn btn-primary w-100">
                                                            <i class="fas fa-shopping-cart"></i> Pesan Sekarang
                                                        </a>
                                                    @elseif($bab->status === 'dipesan')
                                                        @if($pesanan && $pesanan->user)
                                                            <div class="text-center">
                                                                <button class="btn btn-warning w-100 mb-2" disabled>
                                                                    <i class="fas fa-clock"></i> Sedang Dikerjakan
                                                                </button>
                                                                <small class="text-muted">
                                                                    oleh <strong>{{ $pesanan->user->name }}</strong>
                                                                </small>
                                                            </div>
                                                        @else
                                                            <button class="btn btn-warning w-100" disabled>
                                                                <i class="fas fa-clock"></i> Sedang Dikerjakan
                                                            </button>
                                                        @endif
                                                    @elseif($bab->status === 'selesai')
                                                        @if($pesanan && $pesanan->user)
                                                            <div class="text-center">
                                                                <button class="btn btn-info w-100 mb-2" disabled>
                                                                    <i class="fas fa-check-circle"></i> Sudah Selesai
                                                                </button>
                                                                <small class="text-muted">
                                                                    oleh <strong>{{ $pesanan->user->name }}</strong>
                                                                </small>
                                                            </div>
                                                        @else
                                                            <button class="btn btn-info w-100" disabled>
                                                                <i class="fas fa-check-circle"></i> Sudah Selesai
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button class="btn btn-secondary w-100" disabled>
                                                            <i class="fas fa-ban"></i> Tidak Tersedia
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Card Footer untuk informasi tambahan -->
                                            <div class="card-footer bg-light">
                                                <div class="row text-center">
                                                    <div class="col-6">
                                                        <small class="text-muted">
                                                            <i class="fas fa-hashtag"></i> Bab {{ $bab->nomor_bab }}
                                                        </small>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted">
                                                            @if($bab->status === 'tersedia')
                                                                <i class="fas fa-circle text-success"></i> Siap Dipesan
                                                            @elseif($bab->status === 'dipesan')
                                                                <i class="fas fa-circle text-warning"></i> Dalam Proses
                                                            @elseif($bab->status === 'selesai')
                                                                <i class="fas fa-circle text-info"></i> Sudah Selesai
                                                            @else
                                                                <i class="fas fa-circle text-secondary"></i> {{ ucfirst($bab->status) }}
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Summary Cards -->
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    <i class="fas fa-chart-bar"></i> Ringkasan Bab
                                                </h6>
                                                <div class="row text-center">
                                                    <div class="col-3">
                                                        <div class="border-end">
                                                            <h4 class="text-primary mb-1">{{ $bukuKolaboratif->total_bab }}</h4>
                                                            <small class="text-muted">Total Bab</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="border-end">
                                                            <h4 class="text-success mb-1">{{ $bukuKolaboratif->babBuku->where('status', 'tersedia')->count() }}</h4>
                                                            <small class="text-muted">Tersedia</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="border-end">
                                                            <h4 class="text-warning mb-1">{{ $bukuKolaboratif->babBuku->where('status', 'dipesan')->count() }}</h4>
                                                            <small class="text-muted">Dipesan</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                                                                     <h4 class="text-info mb-1">{{ $bukuKolaboratif->babBuku->where('status', 'selesai')->count() }}</h4>
                                                        <small class="text-muted">Selesai</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle fa-2x me-3"></i>
                                    <div>
                                        <h5 class="alert-heading mb-1">Belum Ada Bab Tersedia</h5>
                                        <p class="mb-0">Saat ini belum ada bab yang tersedia untuk buku ini. Silakan cek kembali nanti.</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Sidebar Informasi -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Informasi Buku</h6>
                                </div>
                                <div class="card-body">
                                    <!-- Gambar Sampul -->
                                    @if($bukuKolaboratif->gambar_sampul)
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('storage/' . $bukuKolaboratif->gambar_sampul) }}"
                                             alt="Sampul {{ $bukuKolaboratif->judul }}"
                                             class="img-fluid rounded"
                                             style="max-height: 200px;">
                                    </div>
                                    @endif

                                    <!-- Detail Informasi -->
                                    <div class="mb-3">
                                        <strong>Kategori:</strong><br>
                                        <span class="text-muted">{{ $bukuKolaboratif->kategoriBuku->nama ?? 'Umum' }}</span>
                                    </div>

                                    @if($bukuKolaboratif->target_pembaca)
                                    <div class="mb-3">
                                        <strong>Target Pembaca:</strong><br>
                                        <span class="text-muted">{{ $bukuKolaboratif->target_pembaca }}</span>
                                    </div>
                                    @endif

                                    <div class="mb-3">
                                        <strong>Status:</strong><br>
                                        @if($bukuKolaboratif->status === 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @elseif($bukuKolaboratif->status === 'nonaktif')
                                            <span class="badge bg-secondary">Non-aktif</span>
                                        @elseif($bukuKolaboratif->status === 'selesai')
                                            <span class="badge bg-info">Selesai</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($bukuKolaboratif->status) }}</span>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <strong>Harga per Bab:</strong><br>
                                        <span class="text-success fw-bold">Rp {{ number_format($bukuKolaboratif->harga_per_bab, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="mb-3">
                                        <strong>Total Nilai Proyek:</strong><br>
                                        <span class="text-primary fw-bold">Rp {{ number_format($bukuKolaboratif->harga_per_bab * $bukuKolaboratif->total_bab, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="mb-3">
                                        <strong>Dibuat:</strong><br>
                                        <span class="text-muted">{{ $bukuKolaboratif->created_at->format('d M Y') }}</span>
                                    </div>

                                    <div class="mb-3">
                                        <strong>Terakhir Diperbarui:</strong><br>
                                        <span class="text-muted">{{ $bukuKolaboratif->updated_at->format('d M Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistik Ringkas -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-chart-pie"></i> Statistik Ringkas
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <small class="text-muted">Tingkat Penyelesaian</small>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success"
                                                 style="width: {{ $persenSelesai }}%">
                                            </div>
                                        </div>
                                        <small>{{ number_format($persenSelesai, 1) }}% selesai</small>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Bab Tersedia</small>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-info"
                                                 style="width: {{ $persenTersedia }}%">
                                            </div>
                                        </div>
                                        <small>{{ $babTersedia }} dari {{ $totalBab }} bab</small>
                                    </div>
                                    @if($babTersedia > 0)
                                    <div class="mt-3">
                                        <a href="#daftar-bab" class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-list"></i> Lihat Bab Tersedia
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Informasi Tambahan -->
                            @if($bukuKolaboratif->babBuku->count() > 0)
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-info-circle"></i> Info Tambahan
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $babTersediaCollection = $bukuKolaboratif->babBuku->where('status', 'tersedia');
                                        $hargaMin = $babTersediaCollection->min('harga') ?? $bukuKolaboratif->harga_per_bab;
                                        $hargaMax = $babTersediaCollection->max('harga') ?? $bukuKolaboratif->harga_per_bab;
                                        $totalEstimasiKata = $bukuKolaboratif->babBuku->sum('estimasi_kata');
                                        $statistikBab = $bukuKolaboratif->getStatistikBab();
                                    @endphp
                                    @if($babTersedia > 0)
                                    <div class="mb-2">
                                        <small class="text-muted">Rentang Harga Bab Tersedia:</small><br>
                                        @if($hargaMin == $hargaMax)
                                            <strong>Rp {{ number_format($hargaMin, 0, ',', '.') }}</strong>
                                        @else
                                            <strong>Rp {{ number_format($hargaMin, 0, ',', '.') }} - Rp {{ number_format($hargaMax, 0, ',', '.') }}</strong>
                                        @endif
                                    </div>
                                    @endif
                                    @if($totalEstimasiKata > 0)
                                    <div class="mb-2">
                                        <small class="text-muted">Total Estimasi Kata:</small><br>
                                        <strong>{{ number_format($totalEstimasiKata) }} kata</strong>
                                    </div>
                                    @endif
                                    <div class="mb-0">
                                        <small class="text-muted">Tingkat Kesulitan Dominan:</small><br>
                                        @php
                                            $tingkatDominan = collect($statistikBab)->sortDesc()->keys()->first();
                                        @endphp
                                        @if($tingkatDominan === 'mudah')
                                            <span class="badge bg-success">Mudah</span>
                                        @elseif($tingkatDominan === 'sedang')
                                            <span class="badge bg-warning">Sedang</span>
                                        @elseif($tingkatDominan === 'sulit')
                                            <span class="badge bg-danger">Sulit</span>
                                        @else
                                            <span class="badge bg-secondary">Belum ditentukan</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Daftar Penulis Aktif -->
                            @php
                                $penulisAktif = $bukuKolaboratif->babBuku
                                    ->whereIn('status', ['dipesan', 'selesai'])
                                    ->load('pesananAktif.user')
                                    ->filter(function($bab) {
                                        return $bab->pesananAktif && $bab->pesananAktif->user;
                                    })
                                    ->groupBy(function($bab) {
                                        return $bab->pesananAktif->user->id;
                                    });
                            @endphp
                            @if($penulisAktif->count() > 0)
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-users"></i> Penulis Aktif
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @foreach($penulisAktif as $userId => $babPenulis)
                                        @php
                                            $penulis = $babPenulis->first()->pesananAktif->user;
                                            $jumlahBabDipesan = $babPenulis->where('status', 'dipesan')->count();
                                            $jumlahBabSelesai = $babPenulis->where('status', 'selesai')->count();
                                        @endphp
                                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                                            <div>
                                                <strong>{{ $penulis->name }}</strong><br>
                                                <small class="text-muted">
                                                    @if($jumlahBabDipesan > 0)
                                                        <span class="badge bg-warning">{{ $jumlahBabDipesan }} sedang dikerjakan</span>
                                                    @endif
                                                    @if($jumlahBabSelesai > 0)
                                                        <span class="badge bg-info">{{ $jumlahBabSelesai }} selesai</span>
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted">
                                                    Total: {{ $jumlahBabDipesan + $jumlahBabSelesai }} bab
                                                </small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Notifikasi atau Alert -->
                            @if($bukuKolaboratif->status !== 'aktif')
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Perhatian!</strong><br>
                                Buku ini sedang tidak aktif. Anda tidak dapat memesan bab saat ini.
                            </div>
                            @elseif($babTersedia == 0)
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle"></i>
                                <strong>Info:</strong><br>
                                Saat ini tidak ada bab yang tersedia untuk dipesan.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Anchor untuk scroll ke daftar bab -->
<div id="daftar-bab"></div>
@endsection

@push('styles')
<style>
.progress-sm {
    height: 8px;
}

.card-header h6 {
    color: #495057;
}

.badge {
    font-size: 0.75rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    background-color: #f8f9fa;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.progress {
    background-color: #e9ecef;
}

.border {
    border: 1px solid #dee2e6 !important;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.fw-bold {
    font-weight: 700 !important;
}

.text-success {
    color: #198754 !important;
}

.text-primary {
    color: #0d6efd !important;
}

.rounded {
    border-radius: 0.375rem !important;
}

/* Custom styling untuk progress bar bertumpuk */
.progress .progress-bar + .progress-bar {
    border-left: 1px solid rgba(255,255,255,0.2);
}

/* Hover effect untuk tombol */
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.2s ease-in-out;
}

/* Styling untuk card hover */
.card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: box-shadow 0.2s ease-in-out;
}

/* Styling untuk card bab */
.bab-card {
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.bab-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.bab-card .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.bab-card .card-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

/* Badge styling untuk nama user */
.badge.bg-light {
    color: #495057 !important;
    border: 1px solid #ced4da;
}

/* User info styling */
.user-info {
    background-color: #f8f9fa;
    border-radius: 0.375rem;
    padding: 0.5rem;
    margin-bottom: 0.5rem;
}

/* Penulis aktif card styling */
.penulis-item {
    background-color: #f8f9fa;
    border-radius: 0.375rem;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    border-left: 3px solid #0d6efd;
}

.penulis-item:last-child {
    margin-bottom: 0;
}

/* Status indicator */
.status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 0.25rem;
}

.status-tersedia {
    background-color: #198754;
}

.status-dipesan {
    background-color: #ffc107;
}

.status-selesai {
    background-color: #0dcaf0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-sm {
        padding: 0.125rem 0.25rem;
        font-size: 0.75rem;
    }
    
    .bab-card {
        margin-bottom: 1rem;
    }
    
    .progress {
        height: 20px !important;
    }
    
    .progress .progress-bar {
        font-size: 0.75rem;
    }
}

@media (max-width: 576px) {
    .col-lg-4 {
        margin-bottom: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .btn {
        font-size: 0.875rem;
    }
}

/* Animation untuk loading state */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Custom scrollbar untuk sidebar */
.card-body::-webkit-scrollbar {
    width: 4px;
}

.card-body::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.card-body::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 2px;
}

.card-body::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll untuk anchor link
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

    // Auto refresh setiap 60 detik untuk update status real-time
    let refreshInterval = setInterval(function() {
        // Hanya refresh jika halaman masih aktif
        if (!document.hidden) {
            refreshBabStatus();
        }
    }, 60000);

    // Pause refresh saat tab tidak aktif
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            clearInterval(refreshInterval);
        } else {
            refreshInterval = setInterval(refreshBabStatus, 60000);
        }
    });

    // Function untuk refresh status bab
    function refreshBabStatus() {
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.babStatus) {
                updateBabCards(data.babStatus);
            }
        })
        .catch(error => {
            console.log('Auto refresh error:', error);
        });
    }

    // Function untuk update card bab
    function updateBabCards(babStatus) {
        babStatus.forEach(bab => {
            const babCard = document.querySelector(`[data-bab-id="${bab.id}"]`);
            if (babCard) {
                // Update status badge
                const statusBadge = babCard.querySelector('.status-badge');
                if (statusBadge) {
                    statusBadge.className = `badge bg-${getStatusColor(bab.status)}`;
                    statusBadge.textContent = getStatusText(bab.status);
                }

                // Update user info jika ada
                const userInfo = babCard.querySelector('.user-info');
                if (bab.status === 'dipesan' || bab.status === 'selesai') {
                    if (bab.user && !userInfo) {
                        // Tambah info user jika belum ada
                        addUserInfo(babCard, bab);
                    } else if (bab.user && userInfo) {
                        // Update info user yang sudah ada
                        updateUserInfo(userInfo, bab);
                    }
                } else if (userInfo) {
                    // Hapus info user jika status berubah ke tersedia
                    userInfo.remove();
                }

                // Update tombol aksi
                updateActionButton(babCard, bab);
            }
        });
    }

    // Helper functions
    function getStatusColor(status) {
        switch(status) {
            case 'tersedia': return 'success';
            case 'dipesan': return 'warning';
            case 'selesai': return 'info';
            default: return 'secondary';
        }
    }

    function getStatusText(status) {
        switch(status) {
            case 'tersedia': return 'Tersedia';
            case 'dipesan': return 'Dipesan';
            case 'selesai': return 'Selesai';
            default: return ucfirst(status);
        }
    }

    function ucfirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function addUserInfo(babCard, bab) {
        const cardBody = babCard.querySelector('.card-body');
        const hargaDiv = cardBody.querySelector('.mb-3');
        
        const userInfoHtml = `
            <div class="mb-2 user-info">
                <small class="text-muted">
                    ${bab.status === 'dipesan' ? 'Sedang dikerjakan oleh:' : 'Diselesaikan oleh:'}
                </small><br>
                <span class="badge bg-light text-dark border">
                    <i class="fas fa-user"></i> ${bab.user.name}
                </span>
            </div>
        `;
        
        hargaDiv.insertAdjacentHTML('beforebegin', userInfoHtml);
    }

    function updateUserInfo(userInfo, bab) {
        const statusText = bab.status === 'dipesan' ? 'Sedang dikerjakan oleh:' : 'Diselesaikan oleh:';
        userInfo.querySelector('small').textContent = statusText;
        userInfo.querySelector('.badge').innerHTML = `<i class="fas fa-user"></i> ${bab.user.name}`;
    }

    function updateActionButton(babCard, bab) {
        const actionDiv = babCard.querySelector('.mt-auto');
        let buttonHtml = '';

        switch(bab.status) {
            case 'tersedia':
                buttonHtml = `
                    <a href="/buku-kolaboratif/${bab.buku_kolaboratif_id}/bab/${bab.id}/pilih" class="btn btn-primary w-100">
                        <i class="fas fa-shopping-cart"></i> Pesan Sekarang
                    </a>
                `;
                break;
            case 'dipesan':
                if (bab.user) {
                    buttonHtml = `
                        <div class="text-center">
                            <button class="btn btn-warning w-100 mb-2" disabled>
                                <i class="fas fa-clock"></i> Sedang Dikerjakan
                            </button>
                            <small class="text-muted">
                                oleh <strong>${bab.user.name}</strong>
                            </small>
                        </div>
                    `;
                } else {
                    buttonHtml = `
                        <button class="btn btn-warning w-100" disabled>
                            <i class="fas fa-clock"></i> Sedang Dikerjakan
                        </button>
                    `;
                }
                break;
            case 'selesai':
                if (bab.user) {
                    buttonHtml = `
                        <div class="text-center">
                            <button class="btn btn-info w-100 mb-2" disabled>
                                <i class="fas fa-check-circle"></i> Sudah Selesai
                            </button>
                            <small class="text-muted">
                                oleh <strong>${bab.user.name}</strong>
                            </small>
                        </div>
                    `;
                } else {
                    buttonHtml = `
                        <button class="btn btn-info w-100" disabled>
                            <i class="fas fa-check-circle"></i> Sudah Selesai
                        </button>
                    `;
                }
                break;
            default:
                buttonHtml = `
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="fas fa-ban"></i> Tidak Tersedia
                    </button>
                `;
        }

        actionDiv.innerHTML = buttonHtml;
    }

    // Tooltip initialization
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Loading state untuk tombol
    document.querySelectorAll('.btn-primary').forEach(button => {
        button.addEventListener('click', function() {
            if (!this.disabled) {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                this.disabled = true;
            }
        });
    });

    // Lazy loading untuk gambar
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));

    // Animasi fade in untuk card
    const cards = document.querySelectorAll('.bab-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('fade-in');
        }, index * 100);
    });
});

// Function untuk copy text (jika diperlukan)
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success notification
        showNotification('Teks berhasil disalin!', 'success');
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        showNotification('Gagal menyalin teks', 'error');
    });
}

// Function untuk show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}
</script>
@endpush
