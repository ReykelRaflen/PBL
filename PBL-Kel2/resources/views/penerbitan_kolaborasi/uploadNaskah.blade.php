@extends('user.layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Upload Naskah</h3>
    
    <form action="{{ route('penerbitan_kolaborasi.uploadNaskah') }}" method="POST" enctype="multipart/form-data">
    @csrf

        <input type="hidden" name="bab_id" value="{{ $bab_id }}">


        <div class="form-group mb-3">
            <label for="nama_penulis">Nama Penulis</label>
            <input type="text" name="nama_penulis" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="naskah" class="form-label">Upload Naskah (PDF/Doc)</label>
            <input type="file" name="file_naskah" class="form-control" accept=".pdf,.doc,.docx" required>
        </div>

        <button type="submit" class="btn btn-primary">Upload Naskah</button>
    </form>

</div>
@endsection

