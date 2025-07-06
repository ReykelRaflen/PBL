@extends('user.layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Penerbitan Kolaborasi</h3>
    <div class="row">
        @foreach($buku as $item)
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="text-center" style="height: 460px; overflow: hidden;">
                    <img src="{{ $item->image_url }}" 
                        class="img-fluid h-100" 
                        style="object-fit: cover;" 
                        alt="{{ $item->judul }}">
                </div>
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title">{{ $item->judul }}</h6>
                    <a href="{{ route('penerbitan_kolaborasi.showBuku', $item->id) }}" 
                    class="btn btn-primary mt-auto">Detail Buku</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

