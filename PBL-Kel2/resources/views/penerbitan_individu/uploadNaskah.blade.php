@extends('user.layouts.app')

@section('content')
<div class="container mt-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <h3>Formulir Pengajuan Buku</h3>
    
    <form action="{{ route('penerbitan_individu.submit_uploadNaskah') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="penerbitan_id" value="{{ $penerbitan->id }}">
        
        <div class="mb-3">
            <label for="title" class="form-label">Judul</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="author" class="form-label">Nama Penulis</label>
            <input type="text" name="author" id="author" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="synopsis" class="form-label">Deskripsi Singkat</label>
            <textarea name="synopsis" id="synopsis" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="naskah" class="form-label">Upload Naskah (PDF/Doc)</label>
            <input type="file" name="file_naskah" class="form-control" id="naskah" accept=".pdf, .doc,.docx" required>
        </div>

        <button type="submit" class="btn btn-primary">Kirim</button>
    </form>
</div>
@endsection