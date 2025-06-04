@extends('user.layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($book->judul_buku, 30) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Gambar Buku -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                @if($book->sampul)
                    <img src="{{ asset('storage/covers/' . $book->sampul) }}" 
                         class="card-img-top" 
                         alt="{{ $book->judul_buku }}"
                         style="height: 400px; object-fit: cover;">
                @else
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" 
                         style="height: 400px;">
                        <div class="text-center text-muted">
                            <i class="fas fa-book fa-3x mb-3"></i>
                            <div>Tidak Ada Sampul</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Detail Buku -->
        <div class="col-md-8">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <!-- Judul dan Penulis -->
                    <div class="mb-4">
                        <h1 class="card-title fw-bold text-primary mb-2">{{ $book->judul_buku }}</h1>
                        <p class="text-muted mb-0">
                            <i class="fas fa-user-edit me-2"></i>oleh <strong>{{ $book->penulis }}</strong>
                        </p>
                    </div>

                    <!-- Detail Buku -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Detail Buku</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="text-muted" style="width: 40%;"><i class="fas fa-book me-2"></i>Judul</td>
                                        <td>{{ $book->judul_buku }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><i class="fas fa-user-edit me-2"></i>Penulis</td>
                                        <td>{{ $book->penulis }}</td>
                                    </tr>
                                    @if($book->penerbit)
                                    <tr>
                                        <td class="text-muted"><i class="fas fa-building me-2"></i>Penerbit</td>
                                        <td>{{ $book->penerbit }}</td>
                                    </tr>
                                    @endif
                                    @if($book->tahun_terbit)
                                    <tr>
                                        <td class="text-muted"><i class="fas fa-calendar me-2"></i>Tahun Terbit</td>
                                        <td>{{ $book->tahun_terbit }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    @if($book->isbn)
                                    <tr>
                                        <td class="text-muted" style="width: 40%;"><i class="fas fa-barcode me-2"></i>ISBN</td>
                                        <td>{{ $book->isbn }}</td>
                                    </tr>
                                    @endif
                                    @if($book->halaman)
                                    <tr>
                                        <td class="text-muted"><i class="fas fa-file-alt me-2"></i>Halaman</td>
                                        <td>{{ $book->halaman }} halaman</td>
                                    </tr>
                                    @endif
                                    @if($book->kategori)
                                    <tr>
                                        <td class="text-muted"><i class="fas fa-tags me-2"></i>Kategori</td>
                                        <td><span class="badge bg-secondary">{{ $book->kategori }}</span></td>
                                    </tr>
                                    @endif
                                    @if($book->bahasa)
                                    <tr>
                                        <td class="text-muted"><i class="fas fa-language me-2"></i>Bahasa</td>
                                        <td>{{ $book->bahasa }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>

                                                         <!-- Harga Section -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Pilihan Pembelian</h5>
                        
                        <!-- Buku Fisik -->
                        <div class="card mb-3 border-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">
                                            <i class="fas fa-book text-primary me-2"></i>Buku Fisik
                                            @if($book->persentase_diskon > 0)
                                                <span class="badge bg-danger ms-2">-{{ $book->persentase_diskon }}%</span>
                                            @endif
                                        </h6>
                                        <small class="text-muted">Buku cetak dengan kualitas premium</small>
                                        <div class="mt-1">
                                            @if($book->stok_fisik > 0)
                                                <small class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i>Stok tersedia: {{ $book->stok_fisik }} buku
                                                </small>
                                            @else
                                                <small class="text-danger">
                                                    <i class="fas fa-times-circle me-1"></i>Stok habis
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        @if($book->persentase_diskon > 0)
                                            <div class="text-muted text-decoration-line-through small">
                                                {{ $book->harga_asli_format }}
                                            </div>
                                            <div class="text-danger fw-bold h5 mb-0">
                                                {{ $book->harga_diskon_format }}
                                            </div>
                                        @else
                                            <div class="text-primary fw-bold h5 mb-0">
                                                {{ $book->harga_asli_format }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($book->stok_fisik > 0)
                                    <form method="POST" action="{{ route('order.create') }}" class="mt-3">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <input type="hidden" name="order_type" value="fisik">
                                        
                                        <!-- Quantity Selector -->
                                        <div class="row align-items-center mb-3">
                                            <div class="col-auto">
                                                <label for="quantity_fisik" class="form-label mb-0">Jumlah:</label>
                                            </div>
                                            <div class="col-auto">
                                                <div class="input-group" style="width: 120px;">
                                                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="decreaseQuantity('quantity_fisik')">-</button>
                                                    <input type="number" class="form-control form-control-sm text-center" 
                                                           id="quantity_fisik" name="quantity" value="1" min="1" max="{{ $book->stok_fisik }}" required>
                                                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="increaseQuantity('quantity_fisik', {{ $book->stok_fisik }})">+</button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-shopping-cart me-2"></i>Pesan Buku Fisik
                                        </button>
                                    </form>
                                @else
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-secondary w-100" disabled>
                                            <i class="fas fa-times me-2"></i>Stok Habis
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- E-book -->
                        <div class="card border-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">
                                            <i class="fas fa-tablet-alt text-success me-2"></i>E-book
                                        </h6>
                                        <small class="text-muted">Download langsung setelah pembelian</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-success fw-bold h5 mb-0">
                                            {{ $book->harga_ebook_format }}
                                        </div>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('order.create') }}" class="mt-3">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                    <input type="hidden" name="order_type" value="ebook">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-download me-2"></i>Pesan E-book
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Tambahan -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-heart me-2"></i>Tambah ke Wishlist
                        </button>
                        <button class="btn btn-outline-info" type="button">
                            <i class="fas fa-share-alt me-2"></i>Bagikan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function increaseQuantity(inputId, maxStock) {
            const input = document.getElementById(inputId);
            const currentValue = parseInt(input.value);
            
            if (currentValue < maxStock) {
                input.value = currentValue + 1;
            }
        }

        function decreaseQuantity(inputId) {
            const input = document.getElementById(inputId);
            const currentValue = parseInt(input.value);
            const minValue = parseInt(input.getAttribute('min'));
            
            if (currentValue > minValue) {
                input.value = currentValue - 1;
            }
        }
    </script>


    <!-- Deskripsi Buku -->
    @if($book->deskripsi)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-align-left text-primary me-2"></i>Deskripsi Buku
                    </h5>
                    <div class="text-muted" style="line-height: 1.8; text-align: justify;">
                        {{ $book->deskripsi }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Buku Terkait -->
    @if($relatedBooks->count() > 0)
    <div class="mt-5">
        <h4 class="mb-4 fw-bold">Buku Terkait</h4>
        <div class="row">
            @foreach($relatedBooks as $relatedBook)
                <div class="col-6 col-lg-2 col-md-3 mb-3">
                    <div class="card h-100 shadow-sm position-relative" style="cursor: pointer; transition: transform 0.2s;" 
                         onclick="window.location.href='{{ route('books.show', $relatedBook->id) }}'"
                         onmouseover="this.style.transform='translateY(-3px)'" 
                         onmouseout="this.style.transform='translateY(0)'">
                        
                        <!-- Badge Diskon -->
                        @if($relatedBook->persentase_diskon > 0)
                            <div class="position-absolute top-0 end-0 m-1">
                                <span class="badge bg-danger" style="font-size: 0.65em;">-{{ $relatedBook->persentase_diskon }}%</span>
                            </div>
                        @endif
                        
                        <!-- Cover Buku -->
                        @if($relatedBook->sampul)
                            <img src="{{ asset('storage/covers/' . $relatedBook->sampul) }}" 
                                 class="card-img-top" 
                                 alt="{{ $relatedBook->judul_buku }}"
                                 style="height: 150px; object-fit: cover;">
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" 
                                 style="height: 150px;">
                                <div class="text-center text-muted">
                                    <i class="fas fa-book fa-lg mb-1"></i>
                                    <div style="font-size: 0.7em;">No Cover</div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column p-2">
                            <!-- Judul Buku -->
                            <h6 class="card-title fw-bold text-primary mb-1" style="line-height: 1.2; font-size: 0.85em;">
                                {{ Str::limit($relatedBook->judul_buku, 25) }}
                            </h6>
                            
                            <!-- Penulis -->
                            <p class="text-muted mb-1" style="font-size: 0.7em;">
                                <i class="fas fa-user-edit me-1"></i>{{ Str::limit($relatedBook->penulis, 15) }}
                            </p>
                            
                            <!-- Deskripsi Singkat -->
                            @if($relatedBook->deskripsi)
                                <p class="text-muted mb-2" style="font-size: 0.65em; line-height: 1.2;">
                                    {{ Str::limit($relatedBook->deskripsi, 40) }}
                                </p>
                            @endif
                            
                            <!-- Harga Section -->
                            <div class="mt-auto">
                                <!-- Harga Buku Fisik -->
                                <div class="mb-1 p-1 bg-light rounded">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-book text-primary me-1" style="font-size: 0.6em;"></i>
                                        <span style="font-size: 0.65em; font-weight: 600;">Fisik</span>
                                    </div>
                                    @if($relatedBook->harga_asli != $relatedBook->harga_diskon)
                                        <div class="text-muted text-decoration-line-through" style="font-size: 0.6em;">
                                            {{ $relatedBook->harga_asli_format }}
                                        </div>
                                    @endif
                                    <div class="text-danger fw-bold" style="font-size: 0.75em;">
                                        {{ $relatedBook->harga_diskon_format }}
                                    </div>
                                </div>
                                
                                <!-- Harga E-book -->
                                <div class="mb-2 p-1 bg-success bg-opacity-10 rounded">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-tablet-alt text-success me-1" style="font-size: 0.6em;"></i>
                                        <span style="font-size: 0.65em; font-weight: 600;">E-book</span>
                                    </div>
                                    <div class="text-success fw-bold" style="font-size: 0.75em;">
                                        {{ $relatedBook->harga_ebook_format }}
                                    </div>
                                </div>
                                
                                <!-- Button -->
                                <button class="btn btn-primary btn-sm w-100" style="font-size: 0.65em; padding: 0.25rem 0.5rem;">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
