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
    <div class="container mt-5 text-center">
        <div class="row">
            <div class="col">
                <img src="{{ asset('img/Buku_Individu.png') }}" class="mb-2" width="50">
                <p>Buku Individu</p>
            </div>
            <div class="col">
                <img src="{{ asset('img/Buku_kolaborasi.png') }}" class="mb-2" width="50">
                <p>Buku Kolaboratif</p>
            </div>
        </div>
    </div>

    <!-- Buku Terbaru -->
    <div class="container mt-5">
        <h4 class="mb-4 fw-bold">Buku Terbaru Fanya Untukmu</h4>
        <div class="row">
            @foreach ($books as $book)
                <div class="col-6 col-lg-2 col-md-3 mb-3">
                    <div class="card h-100 shadow-sm position-relative" style="cursor: pointer; transition: transform 0.2s;" 
                         onclick="window.location.href='{{ route('books.show', $book->id) }}'"
                         onmouseover="this.style.transform='translateY(-3px)'" 
                         onmouseout="this.style.transform='translateY(0)'">
                        
                        <!-- Badge Diskon -->
                        @if($book->discount_percentage > 0)
                            <div class="position-absolute top-0 end-0 m-1">
                                <span class="badge bg-danger" style="font-size: 0.65em;">-{{ $book->discount_percentage }}%</span>
                            </div>
                        @endif
                        
                        <!-- Cover Buku -->
                        @if($book->cover)
                            <img src="{{ asset('storage/covers/' . $book->cover) }}" 
                                 class="card-img-top" 
                                 alt="{{ $book->title }}"
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
                                {{ Str::limit($book->title, 25) }}
                            </h6>
                            
                            <!-- Penulis -->
                            <p class="text-muted mb-1" style="font-size: 0.7em;">
                                <i class="fas fa-user-edit me-1"></i>{{ Str::limit($book->author, 15) }}
                            </p>
                            
                            <!-- Deskripsi Singkat -->
                            @if($book->description)
                                <p class="text-muted mb-2" style="font-size: 0.65em; line-height: 1.2;">
                                    {{ Str::limit($book->description, 40) }}
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
                                    @if($book->original_price != $book->discount_price)
                                        <div class="text-muted text-decoration-line-through" style="font-size: 0.6em;">
                                            {{ $book->formatted_original_price }}
                                        </div>
                                    @endif
                                    <div class="text-danger fw-bold" style="font-size: 0.75em;">
                                        {{ $book->formatted_discount_price }}
                                    </div>
                                </div>
                                
                                <!-- Harga E-book -->
                                <div class="mb-2 p-1 bg-success bg-opacity-10 rounded">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-tablet-alt text-success me-1" style="font-size: 0.6em;"></i>
                                        <span style="font-size: 0.65em; font-weight: 600;">E-book</span>
                                    </div>
                                    <div class="text-success fw-bold" style="font-size: 0.75em;">
                                        {{ $book->formatted_ebook_price }}
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
        
        <!-- Tampilkan lebih banyak buku -->
        <div class="text-center mt-4">
            <a href="{{ route('books.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-books me-2"></i>Lihat Semua Buku
            </a>
        </div>
    </div>


@endsection


{{-- @extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1>Beranda Berhasil Diakses ✅</h1>
    </div>
@endsection --}}
