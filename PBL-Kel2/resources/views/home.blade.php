@extends('user.layouts.app')
@section('content')

    <div class="container mt-4">
        <div class="row">
            <!-- Gambar Kiri (gambar besar) -->
            <div class="col-md-8 mb-3">
                <img src="{{ asset('img/banner1.png') }}" class="img-fluid rounded w-100" alt="Banner 1">
            </div>

            <!-- Dua gambar kanan (gambar kecil) -->
            <div class="col-md-4">
                <div class="mb-3">
                    <img src="{{ asset('img/banner2.png') }}" class="img-fluid rounded w-100" alt="Banner 2">
                </div>
                <div>
                    <img src="{{ asset('img/banner3.png') }}" class="img-fluid rounded w-100" alt="Banner 3">
                </div>
            </div>
        </div>
    </div>


    <!-- Fitur Navigasi -->
  <div class="container mt-5">
    <div class="row g-4 justify-content-center">
        <div class="col-md-5">
            <div class="card h-100 shadow-sm border-0 text-center p-4">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <img src="{{ asset('img/Buku_Individu.png') }}" 
                             class="img-fluid" 
                             width="60" 
                             alt="Buku Individu">
                    </div>
                    <h5 class="card-title mb-3">Buku Individu</h5>
                    <div class="mt-auto">
                        <a href="{{ route('penerbitan-individu.index') }}" 
                           class="btn btn-primary px-4 py-2">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card h-100 shadow-sm border-0 text-center p-4">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <img src="{{ asset('img/Buku_kolaborasi.png') }}" 
                             class="img-fluid" 
                             width="60" 
                             alt="Buku Kolaboratif">
                    </div>
                    <h5 class="card-title mb-3">Buku Kolaboratif</h5>
                    <div class="mt-auto">
                        <a href="{{ route('buku-kolaboratif.index') }}" 
                           class="btn btn-primary px-4 py-2">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    <div class="container mt-5">
        <h4 class="mb-4 fw-bold">Buku Terbaru Fanya Untukmu</h4>
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
                                style="width: 100%; object-fit: contain;">

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
    </div>


    {{-- <!-- Tampilkan lebih banyak buku -->
    <div class="text-center mt-4">
        <a href="{{ route('books.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-books me-2"></i>Lihat Semua Buku
        </a>
    </div>
    </div> --}}


@endsection


{{-- @extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Beranda Berhasil Diakses ✅</h1>
</div>
@endsection --}}