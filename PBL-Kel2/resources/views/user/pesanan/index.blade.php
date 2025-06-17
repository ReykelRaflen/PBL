@extends('user.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Pesanan Saya</h2>
                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                    <i class="fas fa-book me-2"></i>Lanjut Belanja
                </a>
            </div>

            @if($pesanan->count() > 0)
                <div class="row">
                    @foreach($pesanan as $item)
                        <div class="col-12 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            @if($item->buku->cover)
                                                <img src="{{ asset('storage/' . $item->buku->cover) }}" 
                                                     class="img-fluid rounded" 
                                                     alt="{{ $item->buku->judul_buku }}"
                                                     style="max-height: 100px;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="height: 100px;">
                                                    <i class="fas fa-book fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="fw-bold text-primary">{{ $item->buku->judul_buku }}</h6>
                                            <p class="text-muted mb-1">{{ $item->buku->penulis }}</p>
                                            <div class="mb-2">
                                                <small class="text-muted">{{ $item->order_number }}</small>
                                                @if($item->tipe_buku === 'fisik')
                                                    <span class="badge bg-info ms-2">Buku Fisik</span>
                                                @else
                                                    <span class="badge bg-success ms-2">E-book</span>
                                                @endif
                                            </div>
                                            <small class="text-muted">
                                                {{ $item->tanggal_pesanan->format('d F Y H:i') }} • 
                                                {{ $item->quantity }} item
                                            </small>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <span class="badge {{ $item->status_badge }} mb-2">
                                                {{ $item->status_text }}
                                            </span>
                                            <div class="fw-bold text-primary">
                                                Rp {{ number_format($item->total, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <div class="d-grid gap-1">
                                                <a href="{{ route('user.pesanan.show', $item) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye me-1"></i>Detail
                                                </a>
                                                
                                                @if($item->status === 'menunggu_pembayaran')
                                                    <a href="{{ route('user.pesanan.payment', $item) }}" 
                                                       class="btn btn-primary btn-sm">
                                                        <i class="fas fa-credit-card me-1"></i>Bayar
                                                    </a>
                                                @elseif($item->canDownloadEbook())
                                                    <a href="{{ route('user.pesanan.downloadEbook', $item) }}" 
                                                       class="btn btn-success btn-sm">
                                                        <i class="fas fa-download me-1"></i>Download
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $pesanan->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-5x text-muted mb-3"></i>
                    <h4 class="text-muted">Belum Ada Pesanan</h4>
                    <p class="text-muted">Anda belum memiliki pesanan apapun</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-book me-2"></i>Mulai Belanja
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
