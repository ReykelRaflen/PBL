@extends('user.layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Pendaftaran Penerbitan Buku Individu</h3>
    
    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <form action="{{ route('penerbitan_individu.submit_pembayaran') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <div class="card-package ">
                        <img src="{{ asset('img/Paket-Silver.png') }}" class="img-fluid rounded w-100" alt="Silver">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <div class="card-package">
                        <img src="{{ asset('img/Paket-Gold.jpg') }}" class="img-fluid rounded w-100" alt="Gold">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <div class="card-package text-center">
                        <img src="{{ asset('img/Paket-Diamond.png') }}" class="img-fluid rounded w-100" alt="Diamond">
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="package" class="form-label">Pilih Paket Penerbitan</label>
            <select name="package" id="package" class="form-control" required>
                <option value="">Select a package</option>
                <option value="Silver">Silver</option>
                <option value="Gold">Gold</option>
                <option value="Diamond">Diamond</option>
            </select>
        </div>

        <div class="mb-3">
            <h4>Informasi Pembayaran</h4>
            @if(isset($rekening))
                <p><strong>Bank:</strong> {{ $rekening->bank }}</p>
                <p><strong>No. Rekening:</strong> {{ $rekening->nomor_rekening }}</p>
                <p><strong>Nama Pemilik:</strong> {{ $rekening->nama_pemilik }}</p>
            @else
                <p>Informasi rekening tidak tersedia.</p>
            @endif
        </div>

        <div class="mb-3">
            <label for="payment_receipt" class="form-label">Upload Bukti Pembayaran</label>
            <input type="file" name="payment_receipt" id="payment_receipt" class="form-control" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-primary">Kirim</button>
    </form>
</div>
@endsection