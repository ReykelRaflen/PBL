@extends('user.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mb-4">Penerbitan Buku Individu</h1>

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

                <!-- Paket Penerbitan -->
                <div class="row mb-5">
                    <div class="col-md-12">
                        <h2 class="mb-3">Pilih Paket Penerbitan</h2>
                        <div class="row">
                            @foreach($paketOptions as $key => $paket)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 {{ $key === 'gold' ? 'border-warning shadow-lg' : 'shadow' }}">
                                        @if($key === 'gold')
                                            <div class="card-header bg-warning text-dark text-center">
                                                <strong><i class="fas fa-crown me-2"></i>PALING POPULER</strong>
                                            </div>
                                        @endif

                                        <div class="card-body text-center p-0">
                                            <!-- Gambar Paket - UKURAN PENUH -->
                                            <div class="package-image-container">
                                                <img src="{{ asset($paket['gambar']) }}" alt="Paket {{ ucfirst($key) }}"
                                                    class="package-image-full"
                                                    onerror="this.src='{{ asset('img/default-package.png') }}'">
                                            </div>

                                            <div class="p-3">
                                                <h3 class="card-title text-uppercase fw-bold">
                                                    @if($key === 'silver')
                                                        <i class="fas fa-medal text-secondary me-2"></i>
                                                    @elseif($key === 'gold')
                                                        <i class="fas fa-trophy text-warning me-2"></i>
                                                    @else
                                                        <i class="fas fa-gem text-info me-2"></i>
                                                    @endif
                                                    {{ $key }}
                                                </h3>

                                                <h4 class="text-primary mb-3">
                                                    <strong>Rp {{ number_format($paket['harga'], 0, ',', '.') }}</strong>
                                                </h4>

                                                {{-- <ul class="list-unstyled mt-3 text-start">
                                                    @foreach($paket['fitur'] as $fitur)
                                                    <li class="mb-2">
                                                        <i class="fas fa-check-circle text-success me-2"></i>
                                                        <small>{{ $fitur }}</small>
                                                    </li>
                                                    @endforeach
                                                </ul> --}}
                                            </div>
                                        </div>

                                        <div class="card-footer bg-transparent">
                                            <form action="{{ route('penerbitan-individu.pilih-paket') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="paket" value="{{ $key }}">
                                                <button type="submit"
                                                    class="btn w-100 {{ $key === 'silver' ? 'btn-outline-secondary' : ($key === 'gold' ? 'btn-warning' : 'btn-info') }}">
                                                    <i class="fas fa-shopping-cart me-2"></i>
                                                    Pilih Paket {{ ucfirst($key) }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .card {
            transition: transform 0.2s ease-in-out;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        /* Container untuk gambar paket */
        .package-image-container {
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        /* Gambar paket ukuran penuh */
        .package-image-full {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.3s ease-in-out;
            /* Tidak ada batasan max-height, gambar akan tampil sesuai ukuran asli */
        }

        .package-image-full:hover {
            transform: scale(1.02);
        }

        /* Jika ingin membatasi tinggi maksimal, uncomment baris berikut */
        /*
    .package-image-full {
        max-height: 300px;
        object-fit: contain;
        background-color: #f8f9fa;
    }
    */

        .badge {
            font-size: 0.75em;
        }

        .table th {
            font-weight: 600;
            font-size: 0.9em;
        }

        .table td {
            vertical-align: middle;
        }

        code {
            background-color: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 0.85em;
        }

        /* Responsive untuk mobile */
        @media (max-width: 768px) {
            .package-image-full {
                /* Pada mobile, bisa dibatasi jika perlu */
                max-height: 250px;
                object-fit: contain;
            }
        }
    </style>
@endsection