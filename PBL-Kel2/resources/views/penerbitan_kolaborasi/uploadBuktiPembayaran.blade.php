@extends('user.layouts.app')

@section('content')
<div class="container mt-5">
    
    <h2 class="mb-4">Upload Bukti Pembayaran</h2>
    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    <form action="{{ route('penerbitan_kolaborasi.submitUploadBuktiPembayaran') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="bab_id" value="{{ $bab_id }}">

        <div class="mb-4">
            <h4>Informasi Pembayaran</h4>
                <p><strong>Bank:</strong> Nagari</p>
                <p><strong>No. Rekening:</strong> 123321</p>
                <p><strong>Nama Pemilik:</strong> CV. Fanya Bintang Sejahtera</p>
        </div>

        <div class="mb-3">
            <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran (JPG/JPEG/PNG)</label>
            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-primary">Kirim</button>
    </form>
</div>
@endsection
