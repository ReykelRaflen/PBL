@extends('user.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body text-center">
            <h4 class="card-title">Status Pembayaran</h4>
            
            @if($penerbitan->status_pembayaran == 'pending')
                <div class="alert alert-warning">
                    <i class="fas fa-clock"></i> Pembayaran Anda sedang divalidasi oleh admin
                </div>
                <p>Silakan tunggu konfirmasi via email dalam 1x24 jam</p>
            @elseif($penerbitan->status_pembayaran == 'Valid')
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Pembayaran telah diverifikasi
                </div>

                <a href="{{ route('penerbitan_individu.uploadNaskah', $penerbitan->id) }}" class="btn btn-primary mt-2">
                    Lanjutkan Pengisian Formulir Buku
                </a>
            @else
                <div class="alert alert-danger">
                    <i class="fas fa-times-circle"></i> Pembayaran ditolak
                </div>
                <p>Alasan: {{ $penerbitan->catatan_admin ?? 'Tidak memenuhi persyaratan' }}</p>
                <a href="{{ route('penerbitan_individu.index') }}" class="btn btn-warning mt-2">
                    Upload Ulang Bukti Pembayaran
                </a>
            @endif
        </div>
    </div>
</div>
@endsection