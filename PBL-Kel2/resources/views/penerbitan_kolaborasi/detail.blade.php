@extends('user.layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Gambar Buku -->
        <div class="col-md-5">
            <img src="{{ $buku->image_url }}" alt="{{ $buku->judul }}" class="img-fluid rounded shadow">
        </div>

        <!-- Informasi Buku -->
        <div class="col-md-7">

            <h2 class="mb-4">{{ $buku->judul }}</h2>


            <ul class="list-group list-group-flush">
                @foreach($bab as $chapter)
                    <li class="list-group-item">
                        <strong>{{ $chapter->judul }}</strong>
                        @if ($chapter->penulis)
                            ({{ $chapter->penulis }})
                        @endif
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="text-muted">Rp{{ number_format($chapter->harga, 0, ',', '.') }}</span>
                            <small class="text-secondary">
                                {{ $chapter->deadline ? 'Deadline: ' . \Carbon\Carbon::parse($chapter->deadline)->format('d-m-Y') : 'Tidak ada deadline' }}
                            </small>
                        </div>
                        <form action="{{ route('penerbitan_kolaborasi.submitPesanBab') }}" method="POST" class="mt-2">
                            @csrf
                            <input type="hidden" name="bab_id" value="{{ $chapter->id }}">
                            <button type="submit" class="btn btn-primary">Pesan Bab</button>
                        </form>
                    </li>
                @endforeach

            </ul>
        </div>
    </div>
</div>
@endsection
