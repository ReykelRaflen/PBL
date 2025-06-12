@extends('user.akun.layouts')

@section('content')
<div class="content-area">
    <h4 class="page-title">
        <i class="fas fa-download me-2"></i>Template Penulisan Buku
    </h4>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Template Table -->
    <div class="card mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #a5b4fc 0%, #667eea 100%);">
            <h5 class="mb-0 text-white">
                <i class="fas fa-file-pdf me-2"></i>Daftar Template Tersedia
            </h5>
        </div>
        <div class="card-body">
            @if($templates->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                            <tr>
                                <th scope="col" width="5%" class="text-white">No</th>
                                <th scope="col" width="25%" class="text-white">
                                    <i class="fas fa-file-text me-1"></i>Judul Template
                                </th>
                                <th scope="col" width="30%" class="text-white">
                                    <i class="fas fa-info-circle me-1"></i>Deskripsi
                                </th>
                                <th scope="col" width="15%" class="text-white">
                                    <i class="fas fa-file me-1"></i>File
                                </th>
                                <th scope="col" width="10%" class="text-white">
                                    <i class="fas fa-weight me-1"></i>Ukuran
                                </th>
                                <th scope="col" width="10%" class="text-white">
                                    <i class="fas fa-calendar me-1"></i>Tanggal
                                </th>
                                <th scope="col" width="15%" class="text-white">
                                    <i class="fas fa-cogs me-1"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($templates as $index => $template)
                            <tr class="template-row">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-pdf me-2" style="color: #a5b4fc;"></i>
                                        <strong class="template-title">{{ $template->judul }}</strong>
                                    </div>
                                </td>
                                <td>
                                    @if($template->deskripsi)
                                        <span class="text-muted">{{ Str::limit($template->deskripsi, 80) }}</span>
                                    @else
                                        <span class="text-muted fst-italic">Tidak ada deskripsi</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fas fa-file-alt me-1 text-info"></i>
                                        {{ Str::limit($template->file_name, 20) }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge" style="background: linear-gradient(45deg, #17a2b8, #20c997); color: white;">
                                        {{ $template->file_size_formatted }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1 text-info"></i>
                                        {{ $template->created_at->format('d/m/Y') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('akun.templates.show', $template->id) }}" 
                                           class="btn btn-outline-primary btn-sm template-btn" 
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('akun.templates.download', $template->id) }}" 
                                           class="btn btn-success btn-sm download-btn template-btn" 
                                           title="Download Template"
                                           data-template-name="{{ $template->judul }}">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination jika diperlukan -->
                @if(method_exists($templates, 'links'))
                    <div class="d-flex justify-content-center mt-4">
                        {{ $templates->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-file-excel text-muted mb-3" style="font-size: 4rem; opacity: 0.5;"></i>
                        <h4 class="text-muted mb-2">Belum Ada Template</h4>
                        <p class="text-muted">Template penulisan buku belum tersedia saat ini.</p>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Silakan hubungi admin untuk informasi lebih lanjut.
                        </small>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Custom styling untuk template table - konsisten dengan profil */
.content-area {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.table {
    margin-bottom: 0;
}

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.9rem;
    vertical-align: middle;
    border-bottom: 2px solid rgba(255,255,255,0.2);
}

.table td {
    vertical-align: middle;
    font-size: 0.9rem;
    border-bottom: 1px solid rgba(165, 180, 252, 0.1);
}

.template-row:hover {
    background-color: rgba(165, 180, 252, 0.1);
    transform: scale(1.01);
    transition: all 0.3s ease;
}

.template-title {
    color: #2c3e50;
}

.template-btn {
    transition: all 0.3s ease;
    border-radius: 8px;
}

.template-btn:hover {
    transform: translateY(-2px);
    animation: buttonPulse 0.6s ease;
}

@keyframes buttonPulse {
    0% { transform: scale(1) translateY(-2px); }
    50% { transform: scale(1.05) translateY(-2px); }
    100% { transform: scale(1) translateY(-2px); }
}

.btn-outline-primary {
    border-color: #a5b4fc;
    color: #a5b4fc;
}

.btn-outline-primary:hover {
    background-color: #a5b4fc;
    border-color: #a5b4fc;
    color: white;
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #218838 0%, #1ea080 100%);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.8rem;
}

.empty-state {
    padding: 3rem 1rem;
    animation: fadeInUp 0.8s ease-out;
}

/* Badge styling konsisten dengan profil */
.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
    border-radius: 10px;
}

/* Alert styling konsisten dengan profil */
.alert {
    animation: slideInDown 0.5s ease-out;
    border: none;
    border-radius: 10px;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Loading effect for download button */
.download-btn:hover {
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
}

.download-btn:active {
    transform: translateY(0);
}

/* Icon styling konsisten */
.fas {
    width: 16px;
    text-align: center;
}

/* Responsive table */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .btn-sm {
        padding: 0.2rem 0.4rem;
        font-size: 0.7rem;
    }
    
    .btn-group {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .template-row:hover {
        transform: none;
    }
}

/* Table striped custom dengan warna konsisten */
.table-striped > tbody > tr:nth-of-type(odd) > td {
    background-color: rgba(165, 180, 252, 0.05);
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click tracking for downloads
    const downloadButtons = document.querySelectorAll('.download-btn');
    downloadButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const templateName = this.getAttribute('data-template-name');
            
            // Show loading state
            const originalHtml = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;
            
            // Show success message
            setTimeout(() => {
                const toast = document.createElement('div');
                toast.className = 'toast-notification';
                toast.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show position-fixed" 
                         style="top: 20px; right: 20px; z-index: 9999; min-width: 300px; border-radius: 10px;">
                        <i class="fas fa-download me-2"></i>
                        Download dimulai: ${templateName}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                document.body.appendChild(toast);
                
                // Reset button
                this.innerHTML = originalHtml;
                this.disabled = false;
                
                // Auto remove toast after 3 seconds
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 3000);
            }, 500);
        });
    });

    // Add table row hover effect dengan animasi smooth
    const tableRows = document.querySelectorAll('.template-row');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transition = 'all 0.3s ease';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transition = 'all 0.3s ease';
        });
    });
});
</script>
@endpush
