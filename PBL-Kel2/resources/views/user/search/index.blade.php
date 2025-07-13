@extends('user.layouts.app')

@section('title', 'Search Results' . ($query ? ' - ' . $query : ''))

@section('content')
    <div class="container mt-4">
        <!-- Search Header -->
        <div class="mb-4">
            <h2 class="fw-bold mb-2">
                @if($query)
                    Hasil Pencarian untuk "{{ $query }}"
                @else
                    Semua Buku
                @endif
            </h2>
            <p class="text-muted">
                Ditemukan {{ $books->total() }} {{ Str::plural('buku', $books->total()) }}
            </p>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('user.search') }}" class="row g-3">
                    <input type="hidden" name="q" value="{{ $query }}">

                    <!-- Category Filter -->
                    <div class="col-md-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $kategori == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Promo Filter -->
                    <div class="col-md-3">
                        <label class="form-label">Promo</label>
                        <select name="promo" class="form-select">
                            <option value="">Semua Promo</option>
                            @foreach($promos as $p)
                                <option value="{{ $p->id }}" {{ $promo == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- E-book Filter -->
                    <div class="col-md-2">
                        <label class="form-label">E-book</label>
                        <select name="has_ebook" class="form-select">
                            <option value="">Semua</option>
                            <option value="1" {{ $has_ebook == '1' ? 'selected' : '' }}>Ada E-book</option>
                        </select>
                    </div>

                    <!-- Stock Filter -->
                    <div class="col-md-2">
                        <label class="form-label">Stok</label>
                        <select name="stock_status" class="form-select">
                            <option value="">Semua</option>
                            <option value="available" {{ $stock_status == 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="low_stock" {{ $stock_status == 'low_stock' ? 'selected' : '' }}>Stok Terbatas
                            </option>
                            <option value="out_of_stock" {{ $stock_status == 'out_of_stock' ? 'selected' : '' }}>Habis
                            </option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="col-md-2">
                        <label class="form-label">Urutkan</label>
                        <select name="sort" class="form-select">
                            <option value="terbaru" {{ $sort == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="terlama" {{ $sort == 'terlama' ? 'selected' : '' }}>Terlama</option>
                            <option value="harga_terendah" {{ $sort == 'harga_terendah' ? 'selected' : '' }}>Harga Terendah
                            </option>
                            <option value="harga_tertinggi" {{ $sort == 'harga_tertinggi' ? 'selected' : '' }}>Harga Tertinggi
                            </option>
                            <option value="judul_az" {{ $sort == 'judul_az' ? 'selected' : '' }}>Judul A-Z</option>
                            <option value="judul_za" {{ $sort == 'judul_za' ? 'selected' : '' }}>Judul Z-A</option>
                            <option value="penulis_az" {{ $sort == 'penulis_az' ? 'selected' : '' }}>Penulis A-Z</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-1"></i>Terapkan Filter
                        </button>
                        <a href="{{ route('user.search') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results -->
        @if($books->count() > 0)
            <div class="row">
                @foreach ($books as $book)
                    <div class="col-6 col-lg-2 col-md-3 mb-3">
                        <div class="card h-100 shadow-sm position-relative" style="cursor: pointer; transition: transform 0.2s;"
                            onclick="window.location.href='{{ route('books.show', $book->id) }}'"
                            onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">

                            <!-- Badge Diskon -->
                            @if($book->promo && $book->promo->isActive() && $book->harga_promo && $book->harga_promo < $book->harga)
                                @php
                                    $diskonPersen = round((($book->harga - $book->harga_promo) / $book->harga) * 100);
                                @endphp
                                <div class="position-absolute top-0 end-0 m-1">
                                    <span class="badge bg-danger" style="font-size: 0.65em;">-{{ $diskonPersen }}%</span>
                                </div>
                            @endif

                            <!-- Cover Buku -->
                            @if($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}" class="card-img-top" alt="{{ $book->judul_buku }}"
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
                                    {{ Str::limit($book->judul_buku, 25) }}
                                </h6>

                                <!-- Penulis -->
                                <p class="text-muted mb-1" style="font-size: 0.7em;">
                                    <i class="fas fa-user-edit me-1"></i>{{ Str::limit($book->penulis, 15) }}
                                </p>

                                <!-- Kategori -->
                                @if($book->kategori)
                                    <p class="text-muted mb-1" style="font-size: 0.65em;">
                                        <i class="fas fa-tag me-1"></i>{{ $book->kategori->nama }}
                                    </p>
                                @endif

                                <!-- Deskripsi Singkat -->
                                @if($book->deskripsi)
                                    <p class="text-muted mb-2" style="font-size: 0.65em; line-height: 1.2;">
                                        {{ Str::limit($book->deskripsi, 40) }}
                                    </p>
                                @endif

                                <!-- Harga Section -->
                                <div class="mt-auto">
                                    <!-- Harga Buku Fisik -->
                                    @if($book->harga)
                                        <div class="mb-1 p-1 bg-light rounded">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="fas fa-book text-primary me-1" style="font-size: 0.6em;"></i>
                                                <span style="font-size: 0.65em; font-weight: 600;">Fisik</span>
                                            </div>

                                            <!-- Tampilkan harga asli dan promo jika ada -->
                                            @if($book->harga_promo && $book->harga_promo < $book->harga)
                                                <div class="text-muted text-decoration-line-through" style="font-size: 0.6em;">
                                                    Rp {{ number_format($book->harga, 0, ',', '.') }}
                                                </div>
                                                <div class="text-danger fw-bold" style="font-size: 0.75em;">
                                                    Rp {{ number_format($book->harga_promo, 0, ',', '.') }}
                                                </div>
                                            @else
                                                <div class="text-danger fw-bold" style="font-size: 0.75em;">
                                                    Rp {{ number_format($book->harga, 0, ',', '.') }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- E-book (jika ada file_buku) -->
                                    @if($book->file_buku)
                                        <div class="mb-2 p-1 bg-success bg-opacity-10 rounded">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="fas fa-tablet-alt text-success me-1" style="font-size: 0.6em;"></i>
                                                <span style="font-size: 0.65em; font-weight: 600;">E-book</span>
                                            </div>
                                            <div class="text-success fw-bold" style="font-size: 0.75em;">
                                                @if($book->harga_ebook)
                                                    Rp {{ number_format($book->harga_ebook, 0, ',', '.') }}
                                                @else
                                                    Tersedia
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Stok Info -->
                                    @if($book->stok <= 0)
                                        <div class="mb-2">
                                            <span class="badge bg-danger" style="font-size: 0.6em;">Stok Habis</span>
                                        </div>
                                    @elseif($book->stok <= 5)
                                        <div class="mb-2">
                                            <span class="badge bg-warning" style="font-size: 0.6em;">Stok Terbatas</span>
                                        </div>
                                    @endif

                                    <!-- Button -->
                                    <button class="btn btn-primary btn-sm w-100"
                                        style="font-size: 0.65em; padding: 0.25rem 0.5rem;">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $books->links() }}
            </div>
        @else
            <!-- No Results -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-search" style="font-size: 4rem; color: #6c757d;"></i>
                </div>
                <h3 class="fw-bold text-muted mb-3">Tidak ada buku ditemukan</h3>
                <p class="text-muted mb-4">
                    @if($query)
                        Coba gunakan kata kunci yang berbeda atau sesuaikan filter pencarian.
                    @else
                        Belum ada buku yang tersedia saat ini.
                    @endif
                </p>
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i>Kembali ke Beranda
                </a>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
    </style>
@endpush