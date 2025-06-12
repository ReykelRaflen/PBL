@extends('user.akun.layouts')

@section('content')
<div class="content-area">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title">
            <i class="fas fa-file-pdf text-danger me-2"></i>Detail Template
        </h4>
        <a href="{{ route('akun.templates.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>
    
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

    <div class="row">
        <!-- Detail Template -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #a5b4fc 0%, #667eea 100%);">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-info-circle me-2"></i>Informasi Template
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h2 class="template-title mb-3">{{ $template->judul }}</h2>
                        
                        @if($template->deskripsi)
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-align-left me-1" style="color: #a5b4fc;"></i>Deskripsi Template
                                </label>
                                <div class="template-description">
                                    <p class="text-justify">{{ $template->deskripsi }}</p>
                                </div>
                            </div>
                        @else
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-align-left me-1" style="color: #a5b4fc;"></i>Deskripsi Template
                                </label>
                                <div class="template-description">
                                    <p class="text-muted fst-italic">Tidak ada deskripsi untuk template ini.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informasi File -->
            <div class="card mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-file-alt me-2"></i>Detail File
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-file me-1 text-info"></i>Nama File
                            </label>
                            <div class="file-info">
                                <span class="text-muted">{{ $template->file_name }}</span>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-weight me-1 text-info"></i>Ukuran File
                            </label>
                            <div class="file-info">
                                <span class="badge" style="background: linear-gradient(45deg, #17a2b8, #20c997); color: white; font-size: 0.9rem;">
                                    {{ $template->file_size_formatted }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-calendar-alt me-1 text-info"></i>Tanggal Upload
                            </label>
                            <div class="file-info">
                                <span class="text-muted">{{ $template->created_at->format('d F Y') }}</span>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-clock me-1 text-info"></i>Waktu Upload
                            </label>
                            <div class="file-info">
                                <span class="text-muted">{{ $template->created_at->format('H:i') }} WIB</span>
                            </div>
                        </div>
                    </div>

                    @if($template->uploader)
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                <i class="fas fa-user me-1 text-info"></i>Diupload Oleh
                            </label>
                            <div class="file-info">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-circle me-2" style="color: #a5b4fc; font-size: 1.2rem;"></i>
                                    <span class="text-muted">{{ $template->uploader->name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Download Section -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-download me-2"></i>Download Template
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="download-preview mb-4">
                        <i class="fas fa-file-pdf text-danger mb-3" style="font-size: 5rem;"></i>
                        <h6 class="template-file-name">{{ $template->file_name }}</h6>
                    </div>
                    
                    <div class="download-info mb-4">
                        <div class="info-item mb-2">
                            <small class="text-muted">Format:</small>
                            <span class="badge bg-danger ms-2">PDF</span>
                        </div>
                        <div class="info-item mb-2">
                            <small class="text-muted">Ukuran:</small>
                            <span class="badge bg-info ms-2">{{ $template->file_size_formatted }}</span>
                        </div>
                        <div class="info-item">
                            <small class="text-muted">Status:</small>
                            <span class="badge bg-success ms-2">Tersedia</span>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <a href="{{ route('akun.templates.download', $template->id) }}" 
                           class="btn btn-success btn-lg download-main-btn">
                            <i class="fas fa-download me-2"></i>Download Template
                        </a>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            File akan didownload dalam format PDF
                        </small>
                    </div>
                </div>
            </div>

            <!-- Tips Penggunaan -->
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-lightbulb me-2"></i>Tips Penggunaan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="tips-list">
                        <div class="tip-item mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small>Baca template dengan teliti sebelum mulai menulis</small>
                        </div>
                        <div class="tip-item mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small>Ikuti format dan struktur yang telah disediakan</small>
                        </div>
                        <div class="tip-item mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small>Sesuaikan konten dengan panduan yang ada</small>
                        </div>
                        <div class="tip-item">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small>Hubungi admin jika ada pertanyaan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styling untuk template show - konsisten dengan profil */
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

.template-title {
    color: #2c3e50;
    font-weight: 600;
}

.template-description {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 10px;
    border-left: 4px solid #a5b4fc;
}

.file-info {
    background: #f8f9fa;
    padding: 10px 15px;
    border-radius: 8px;
    border-left: 3px solid #17a2b8;
}

.download-preview {
    transition: transform 0.3s ease;
}

.download-preview:hover {
    transform: scale(1.05);
}

.template-file-name {
    color: #2c3e50;
    font-weight: 600;
    word-break: break-word;
}

.download-main-btn {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    padding: 12px 24px;
    font-size: 1.1rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.download-main-btn:hover {
    background: linear-gradient(135deg, #218838 0%, #1ea080 100%);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    transform: translateY(-2px);
    animation: buttonPulse 0.6s ease;
}

@keyframes buttonPulse {
    0% { transform: scale(1) translateY(-2px); }
    50% { transform: scale(1.05) translateY(-2px); }
    100% { transform: scale(1) translateY(-2px); }
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #5a6268 0%, #343a40 100%);
    transform: translateY(-1px);
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
    border-radius: 8px;
}

.tips-list .tip-item {
    display: flex;
    align-items: flex-start;
    padding: 5px 0;
}

.tips-list .tip-item i {
    margin-top: 2px;
    flex-shrink: 0;
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

.text-justify {
    text-align: justify;
    line-height: 1.6;
}

/* Form label styling konsisten */
.form-label {
    font-weight: 600;
    margin-bottom: 8px;
    color: #2c3e50;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .download-main-btn {
        padding: 10px 20px;
        font-size: 1rem;
    }
    
    .download-preview i {
        font-size: 3.5rem !important;
    }
    
    .template-title {
        font-size: 1.5rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}

/* Loading state untuk download button */
.download-main-btn:active {
    transform: translateY(0);
}

.download-main-btn.loading {
    pointer-events: none;
    opacity: 0.7;
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click tracking for main download button
    const downloadButton = document.querySelector('.download-main-btn');
    if (downloadButton) {
        downloadButton.addEventListener('click', function(e) {
            // Show loading state
            const originalHtml = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses Download...';
            this.classList.add('loading');
            
            // Get template name for notification
            const templateName = document.querySelector('.template-title').textContent.trim();
            
            // Show success message after short delay
            setTimeout(() => {
                const toast = document.createElement('div');
                toast.className = 'toast-notification';
                toast.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show position-fixed" 
                         style="top: 20px; right: 20px; z-index: 9999; min-width: 350px; border-radius: 10px;">
                        <i class="fas fa-download me-2"></i>
                        <strong>Download Berhasil!</strong><br>
                        <small>Template "${templateName}" sedang didownload...</small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                document.body.appendChild(toast);
                
                // Reset button
                this.innerHTML = originalHtml;
                this.classList.remove('loading');
                
                // Auto remove toast after 4 seconds
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 4000);
            }, 800);
        });
    }

    // Add smooth scroll animation for cards
    const cards = document.querySelectorAll('.card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 150);
            }
        });
    });

    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // Add hover effect for download preview
    const downloadPreview = document.querySelector('.download-preview');
    if (downloadPreview) {
        downloadPreview.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05) rotate(2deg)';
        });
        
        downloadPreview.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0deg)';
        });
    }

    // Add click effect for tips items
    const tipItems = document.querySelectorAll('.tip-item');
    tipItems.forEach(item => {
        item.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });

    // Add copy filename functionality
    const fileName = document.querySelector('.template-file-name');
    if (fileName) {
        fileName.style.cursor = 'pointer';
        fileName.title = 'Klik untuk menyalin nama file';
        
        fileName.addEventListener('click', function() {
            navigator.clipboard.writeText(this.textContent).then(() => {
                // Show copy notification
                const copyToast = document.createElement('div');
                copyToast.className = 'copy-notification';
                copyToast.innerHTML = `
                    <div class="alert alert-info alert-dismissible fade show position-fixed" 
                         style="top: 20px; right: 20px; z-index: 9999; min-width: 250px; border-radius: 10px;">
                        <i class="fas fa-copy me-2"></i>
                        Nama file disalin!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                document.body.appendChild(copyToast);
                
                setTimeout(() => {
                    if (copyToast.parentNode) {
                        copyToast.remove();
                    }
                }, 2000);
            });
        });
    }

    // Add back button confirmation
    const backButton = document.querySelector('a[href*="templates.index"]');
    if (backButton) {
        backButton.addEventListener('click', function(e) {
            // Add smooth transition effect
            document.body.style.opacity = '0.8';
            document.body.style.transform = 'scale(0.98)';
            document.body.style.transition = 'all 0.3s ease';
        });
    }

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + D untuk download
        if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
            e.preventDefault();
            if (downloadButton) {
                downloadButton.click();
            }
        }
        
        // Escape untuk kembali
        if (e.key === 'Escape') {
            if (backButton) {
                backButton.click();
            }
        }
    });

    // Show keyboard shortcuts info
    const keyboardInfo = document.createElement('div');
    keyboardInfo.className = 'keyboard-shortcuts';
    keyboardInfo.innerHTML = `
        <div class="position-fixed bottom-0 end-0 m-3" style="z-index: 1000;">
            <div class="card shadow-sm" style="max-width: 250px;">
                <div class="card-body p-2">
                    <small class="text-muted">
                        <strong>Shortcut:</strong><br>
                        <kbd>Ctrl+D</kbd> Download<br>
                        <kbd>Esc</kbd> Kembali
                    </small>
                </div>
            </div>
        </div>
    `;
    
    // Show shortcuts after 2 seconds, hide after 5 seconds
    setTimeout(() => {
        document.body.appendChild(keyboardInfo);
        keyboardInfo.style.opacity = '0';
        keyboardInfo.style.transition = 'opacity 0.5s ease';
        
        setTimeout(() => {
            keyboardInfo.style.opacity = '1';
        }, 100);
        
        setTimeout(() => {
            keyboardInfo.style.opacity = '0';
            setTimeout(() => {
                if (keyboardInfo.parentNode) {
                    keyboardInfo.remove();
                }
            }, 500);
        }, 5000);
    }, 2000);
});
</script>
@endpush
