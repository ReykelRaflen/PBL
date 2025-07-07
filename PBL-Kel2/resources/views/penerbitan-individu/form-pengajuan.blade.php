@extends('user.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Form Pengajuan Penerbitan - {{ $penerbitan->nomor_pesanan }}</h3>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($penerbitan->status_penerbitan == 'revisi' && $penerbitan->feedback_editor)
                        <div class="alert alert-warning mb-4">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Feedback untuk Revisi:</h6>
                            <p class="mb-0">{{ $penerbitan->feedback_editor }}</p>
                        </div>
                    @endif

                    <form action="{{ route('penerbitan-individu.submit-pengajuan', $penerbitan->id) }}" 
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="judul_buku" class="form-label">Judul Buku <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('judul_buku') is-invalid @enderror" 
                                   name="judul_buku" id="judul_buku" 
                                   value="{{ old('judul_buku', $penerbitan->judul_buku) }}" 
                                   placeholder="Masukkan judul buku" required>
                            @error('judul_buku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nama_penulis" class="form-label">Nama Penulis <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_penulis') is-invalid @enderror" 
                                   name="nama_penulis" id="nama_penulis" 
                                   value="{{ old('nama_penulis', $penerbitan->nama_penulis) }}" 
                                   placeholder="Masukkan nama penulis" required>
                            @error('nama_penulis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi_singkat" class="form-label">Deskripsi Singkat <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('deskripsi_singkat') is-invalid @enderror" 
                                      name="deskripsi_singkat" id="deskripsi_singkat" rows="4" 
                                                                            placeholder="Masukkan deskripsi singkat tentang buku (maksimal 1000 karakter)" required>{{ old('deskripsi_singkat', $penerbitan->deskripsi_singkat) }}</textarea>
                            <div class="form-text">Maksimal 1000 karakter</div>
                            @error('deskripsi_singkat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="file_naskah" class="form-label">
                                File Naskah <span class="text-danger">*</span>
                                @if($penerbitan->file_naskah)
                                    <small class="text-muted">(File saat ini: {{ basename($penerbitan->file_naskah) }})</small>
                                @endif
                            </label>
                            <input type="file" class="form-control @error('file_naskah') is-invalid @enderror" 
                                   name="file_naskah" id="file_naskah" 
                                   accept=".doc,.docx,.pdf" 
                                   {{ $penerbitan->file_naskah ? '' : 'required' }}>
                            <div class="form-text">
                                Format: DOC, DOCX, PDF. Maksimal 10MB.
                                @if($penerbitan->file_naskah)
                                    <br><strong>Catatan:</strong> Kosongkan jika tidak ingin mengubah file yang sudah ada.
                                @endif
                            </div>
                            @error('file_naskah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($penerbitan->file_naskah)
                        <div class="mb-3">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                File naskah saat ini: 
                                <a href="{{ route('penerbitan-individu.download-naskah', $penerbitan->id) }}" 
                                   class="alert-link">
                                    {{ basename($penerbitan->file_naskah) }}
                                </a>
                            </div>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('penerbitan-individu.status', $penerbitan->id) }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>
                                {{ $penerbitan->status_penerbitan == 'revisi' ? 'Kirim Revisi' : 'Submit Pengajuan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
