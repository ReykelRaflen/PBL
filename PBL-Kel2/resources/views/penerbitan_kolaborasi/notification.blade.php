@extends('user.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body text-center">
            <h4 class="card-title">Status Pembayaran</h4>
            @if(session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            
            @if($bab->status_pembayaran == 'pending')
                <div class="alert alert-warning">
                    <i class="fas fa-clock"></i> Pembayaran Anda sedang divalidasi oleh admin
                </div>
                <p>Silakan tunggu konfirmasi via email dalam 1x24 jam</p>
            @elseif($bab->status_pembayaran == 'Valid')
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Pembayaran telah diverifikasi
                </div>

                
                
                <a href="{{ route('penerbitan_kolaborasi.uploadNaskah', $bab->id) }}" class="btn btn-primary mt-2">
                    Lanjutkan Upload Naskah

                </a>
            @else($bab->status_pembayaran == 'Tidak Valid')
                <div class="alert alert-danger">
                    <i class="fas fa-times-circle"></i> Pembayaran ditolak
                </div>
                <p>Alasan: {{ $bab->catatan_admin ?? 'Tidak memenuhi persyaratan' }}</p>
                <a href="{{ route('penerbitan_kolaborasi.uploadBuktiPembayaran', ['id' => $bab->id]) }}" class="btn btn-warning mt-2">
                    Upload Ulang Bukti Pembayaran
                </a>

                
            @endif 
        </div>
    </div>
</div>
@endsection