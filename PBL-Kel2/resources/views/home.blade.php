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
                <div class="col-6 col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset('storage/covers/' . $book->cover) }}" class="card-img-top" alt="{{ $book->title }}">
                        <div class="card-body">
                            <h6 class="card-title">{{ $book->title }}</h6>
                            <p class="text-muted small">{{ $book->author }}</p>
                            <p class="text-danger fw-bold">Rp{{ number_format($book->discount_price, 0, ',', '.') }}</p>
                            <small class="text-muted text-decoration-line-through">Rp{{ number_format($book->original_price, 0, ',', '.') }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

{{-- @extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1>Beranda Berhasil Diakses ✅</h1>
    </div>
@endsection --}}
