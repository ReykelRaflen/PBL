@extends('user.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        Konfirmasi Paket {{ ucfirst($paket) }}
                    </h3>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Detail Paket -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-box me-2"></i>
                                        Detail Paket
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <!-- Icon dan Nama Paket -->
                                    <div class="text-center mb-3">
                                        @php
                                            $paketIcon = match($paket) {
                                                'silver' => 'fas fa-medal text-secondary',
                                                'gold' => 'fas fa-trophy text-warning',
                                                'diamond' => 'fas fa-gem text-info',
                                                default => 'fas fa-box'
                                            };
                                        @endphp
                                        <i class="{{ $paketIcon }}" style="font-size: 3rem;"></i>
                                        <h4 class="text-uppercase fw-bold mt-2">{{ $paket }}</h4>
                                    </div>

                                    <!-- Harga -->
                                    <div class="text-center mb-4">
                                        <h3 class="text-primary">
                                            <strong>Rp {{ number_format($paketData['harga'], 0, ',', '.') }}</strong>
                                        </h3>
                                    </div>

                                    <!-- Fitur Paket -->
                                    <div class="mb-3">
                                        <h6 class="fw-bold mb-3">
                                            <i class="fas fa-list me-2"></i>
                                            Fitur yang Didapat:
                                        </h6>
                                        <ul class="list-unstyled">
                                            @if(isset($paketData['fitur']) && is_array($paketData['fitur']))
                                                @foreach($paketData['fitur'] as $fitur)
                                                    <li class="mb-2">
                                                        <i class="fas fa-check-circle text-success me-2"></i>
                                                        {{ $fitur }}
                                                    </li>
                                                @endforeach
                                            @else
                                                @if($paket === 'silver')
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Maksimal 3 Penulis</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Editing</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Layout Naskah</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Desain Cover</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Sertifikat Penulis</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Royalti 20%</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Terindeks Google Schoolar</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Buku Cetak 10 Eksemplar</li>
                                                @elseif($paket === 'gold')
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Maksimal 3 Penulis</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Editing</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Layout Naskah</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Desain Cover</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Sertifikat Penulis</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Royalti 20%</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Terindeks Google Schoolar</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Buku Cetak 20 Eksemplar</li>
                                                @else
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Maksimal 3 Penulis</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Editing</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Layout Naskah</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Desain Cover</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Sertifikat Penulis</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Royalti 20%</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Terindeks Google Schoolar</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Buku Cetak 30 Eksemplar</li>
                                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Sertifikat HAKI</li>
                                                @endif
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Syarat dan Ketentuan -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Syarat dan Ketentuan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="border p-3 rounded" style="height: 300px; overflow-y: auto; background-color: #f8f9fa;">
                                        <ol class="mb-0">
                                            <li class="mb-2">Pembayaran harus dilakukan dalam 24 jam setelah pemesanan</li>
                                            <li class="mb-2">Naskah harus dalam format DOC, DOCX, atau PDF</li>
                                            <li class="mb-2">Proses review membutuhkan waktu 3-5 hari kerja</li>
                                            <li class="mb-2">Revisi dapat dilakukan maksimal 3 kali</li>
                                            <li class="mb-2">Hak cipta tetap milik penulis</li>
                                            <li class="mb-2">Penerbit berhak menolak naskah yang tidak sesuai standar</li>
                                            <li class="mb-2">Proses penerbitan membutuhkan waktu 2-4 minggu setelah naskah disetujui</li>
                                            <li class="mb-2">Biaya tambahan berlaku untuk revisi berlebihan</li>
                                            <li class="mb-2">Distribusi dilakukan sesuai paket yang dipilih</li>
                                            <li class="mb-0">Komplain dapat diajukan maksimal 7 hari setelah buku diterima</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Konfirmasi -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-clipboard-check me-2"></i>
                                        Konfirmasi Pesanan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('penerbitan-individu.proses-pesanan') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="paket" value="{{ $paket }}">
                                        
                                        <!-- Ringkasan Pesanan -->
                                        <div class="alert alert-info mb-4">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <h6 class="mb-1">
                                                        <i class="fas fa-shopping-cart me-2"></i>
                                                        <strong>Ringkasan Pesanan:</strong>
                                                    </h6>
                                                    <p class="mb-0">
                                                        Paket <strong>{{ ucfirst($paket) }}</strong> - 
                                                        <span class="text-primary fw-bold">Rp {{ number_format($paketData['harga'], 0, ',', '.') }}</span>
                                                    </p>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    <i class="{{ $paketIcon }}" style="font-size: 2rem;"></i>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Checkbox Persetujuan -->
                                        <div class="form-check mb-4">
                                            <input class="form-check-input @error('setuju') is-invalid @enderror" 
                                                   type="checkbox" 
                                                   name="setuju" 
                                                   id="setuju" 
                                                   value="1"
                                                   {{ old('setuju') ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="setuju">
                                                <i class="fas fa-hand-point-right me-2 text-primary"></i>
                                                Saya menyetujui syarat dan ketentuan yang berlaku
                                            </label>
                                            @error('setuju')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('penerbitan-individu.index') }}" 
                                               class="btn btn-secondary btn-lg">
                                                <i class="fas fa-arrow-left me-2"></i>
                                                Kembali
                                            </a>
                                            <button type="submit" 
                                                    class="btn btn-primary btn-lg px-4"
                                                    id="submitBtn">
                                                <i class="fas fa-credit-card me-2"></i>
                                                Lanjutkan ke Pembayaran
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.form-check-input:focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.alert-info {
    border-left: 4px solid #0dcaf0;
}

/* Custom scrollbar untuk syarat dan ketentuan */
.card-body div[style*="overflow-y: auto"]::-webkit-scrollbar {
    width: 6px;
}

.card-body div[style*="overflow-y: auto"]::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.card-body div[style*="overflow-y: auto"]::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.card-body div[style*="overflow-y: auto"]::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Responsive */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .d-flex.justify-content-between .btn {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert.classList.contains('show')) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });
    
    // Form submission with loading state
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');
    const checkbox = document.getElementById('setuju');
    
    // Enable/disable submit button based on checkbox
    function toggleSubmitButton() {
        if (checkbox.checked) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('btn-secondary');
            submitBtn.classList.add('btn-primary');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.remove('btn-primary');
            submitBtn.classList.add('btn-secondary');
        }
    }
    
    // Initial state
    toggleSubmitButton();
    
    // Listen for checkbox changes
    checkbox.addEventListener('change', toggleSubmitButton);
    
    // Form submission
    form.addEventListener('submit', function(e) {
        if (!checkbox.checked) {
            e.preventDefault();
            
            // Show SweetAlert if available, otherwise use alert
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Persetujuan Diperlukan',
                    text: 'Anda harus menyetujui syarat dan ketentuan terlebih dahulu.',
                    confirmButtonColor: '#0d6efd'
                });
            } else {
                alert('Anda harus menyetujui syarat dan ketentuan terlebih dahulu.');
            }
            return false;
        }
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
        submitBtn.disabled = true;
        
        // Prevent double submission
        setTimeout(function() {
            const allButtons = form.querySelectorAll('button');
            allButtons.forEach(btn => btn.disabled = true);
        }, 100);
    });
    
    // Smooth scroll to top when page loads
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    // Add animation to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 200);
    });
});

// Prevent back button issues
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        window.location.reload();
    }
});
</script>
@endsection
