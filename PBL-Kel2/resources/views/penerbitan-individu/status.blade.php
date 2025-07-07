@extends('user.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>Status Penerbitan - {{ $penerbitan->nomor_pesanan }}</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Progress Timeline -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Progress Penerbitan</h5>
                            <div class="timeline">
                                <div class="timeline-item {{ $penerbitan->status_pembayaran != 'menunggu' ? 'completed' : 'active' }}">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6>Pembayaran</h6>
                                        <p>Status: 
                                            @php
                                                $badgeClass = match($penerbitan->status_pembayaran) {
                                                    'menunggu' => 'bg-warning',
                                                    'pending' => 'bg-info',
                                                    'lunas' => 'bg-success',
                                                    'ditolak' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">
                                                {{ ucfirst(str_replace('_', ' ', $penerbitan->status_pembayaran)) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="timeline-item {{ $penerbitan->status_penerbitan == 'dapat_mulai' ? 'active' : ($penerbitan->status_pembayaran == 'lunas' ? 'completed' : '') }}">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6>Form Pengajuan</h6>
                                        <p>Status: 
                                            @php
                                                $badgeClass = match($penerbitan->status_penerbitan) {
                                                    'belum_mulai' => 'bg-secondary',
                                                    'dapat_mulai' => 'bg-info',
                                                    'sudah_kirim' => 'bg-warning',
                                                    'revisi' => 'bg-orange',
                                                    'disetujui' => 'bg-success',
                                                    'ditolak' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">
                                                {{ ucfirst(str_replace('_', ' ', $penerbitan->status_penerbitan)) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="timeline-item {{ in_array($penerbitan->status_penerbitan, ['sudah_kirim', 'revisi']) ? 'active' : (in_array($penerbitan->status_penerbitan, ['disetujui', 'ditolak']) ? 'completed' : '') }}">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6>Review Editor</h6>
                                        <p>Menunggu review dari editor</p>
                                    </div>
                                </div>

                                <div class="timeline-item {{ $penerbitan->status_penerbitan == 'disetujui' ? 'completed' : '' }}">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6>Penerbitan</h6>
                                        <p>Proses penerbitan buku</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Pesanan -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Detail Pesanan</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td>No. Pesanan</td>
                                    <td>: {{ $penerbitan->nomor_pesanan }}</td>
                                </tr>
                                <tr>
                                    <td>Paket</td>
                                    <td>: {{ ucfirst($penerbitan->paket) }}</td>
                                </tr>
                                <tr>
                                    <td>Total Bayar</td>
                                    <td>: Rp {{ number_format($penerbitan->harga_paket, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Pesanan</td>
                                    <td>: {{ $penerbitan->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @if($penerbitan->tanggal_bayar)
                                <tr>
                                    <td>Tanggal Bayar</td>
                                    <td>: {{ $penerbitan->tanggal_bayar->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            @if($penerbitan->judul_buku)
                            <h5>Detail Buku</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td>Judul Buku</td>
                                    <td>: {{ $penerbitan->judul_buku }}</td>
                                </tr>
                                <tr>
                                    <td>Nama Penulis</td>
                                    <td>: {{ $penerbitan->nama_penulis }}</td>
                                </tr>
                                @if($penerbitan->tanggal_upload_naskah)
                                <tr>
                                    <td>Tanggal Upload</td>
                                    <td>: {{ $penerbitan->tanggal_upload_naskah->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endif
                            </table>
                            @endif
                        </div>
                    </div>

                    <!-- Feedback Editor -->
                    @if($penerbitan->feedback_editor)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-comment-alt me-2"></i>Feedback Editor:</h6>
                                <p class="mb-0">{{ $penerbitan->feedback_editor }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Catatan Persetujuan -->
                    @if($penerbitan->catatan_persetujuan)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <h6><i class="fas fa-check-circle me-2"></i>Catatan Persetujuan:</h6>
                                <p class="mb-0">{{ $penerbitan->catatan_persetujuan }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex gap-2 flex-wrap">
                                @if($penerbitan->status_pembayaran == 'menunggu')
                                    <a href="{{ route('penerbitan-individu.pembayaran', $penerbitan->id) }}" 
                                       class="btn btn-primary">
                                        <i class="fas fa-credit-card me-2"></i>Lakukan Pembayaran
                                    </a>
                                @endif

                                @if($penerbitan->status_pembayaran == 'lunas' && in_array($penerbitan->status_penerbitan, ['dapat_mulai', 'revisi']))
                                    <a href="{{ route('penerbitan-individu.form-pengajuan', $penerbitan->id) }}" 
                                       class="btn btn-success">
                                        <i class="fas fa-edit me-2"></i>
                                        {{ $penerbitan->status_penerbitan == 'revisi' ? 'Revisi Form' : 'Isi Form Pengajuan' }}
                                    </a>
                                @endif

                                @if($penerbitan->file_naskah)
                                    <a href="{{ route('penerbitan-individu.download-naskah', $penerbitan->id) }}" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-download me-2"></i>Download Naskah
                                    </a>
                                @endif

                                <a href="{{ route('penerbitan-individu.index') }}" 
                                   class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 5px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #dee2e6;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-item.active .timeline-marker {
    background: #ffc107;
    box-shadow: 0 0 0 2px #ffc107;
}

.timeline-item.completed .timeline-marker {
    background: #28a745;
    box-shadow: 0 0 0 2px #28a745;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-weight: 600;
}

.timeline-content p {
    margin-bottom: 0;
    color: #6c757d;
}
</style>
@endsection
