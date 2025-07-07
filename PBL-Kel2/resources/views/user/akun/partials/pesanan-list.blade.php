@if($pesanan->count() > 0)
    <div class="pesanan-list">
        @foreach($pesanan as $item)
            <div class="pesanan-item" data-pesanan-id="{{ $item->id }}">
                <div class="pesanan-header">
                    <div class="pesanan-info">
                        <h6 class="pesanan-title">
                            {{ $item->buku->judul_buku ?? 'Buku Tidak Ditemukan' }}
                        </h6>
                        <p class="pesanan-meta">
                            <span class="order-number">#{{ $item->order_number }}</span>
                            <span class="separator">•</span>
                            <span class="order-date">{{ $item->tanggal_pesanan->format('d M Y') }}</span>
                            <span class="separator">•</span>
                            <span class="book-type">
                                <i class="fas fa-{{ $item->tipe_buku === 'ebook' ? 'tablet-alt' : 'book' }} me-1"></i>
                                {{ $item->tipe_buku === 'ebook' ? 'E-book' : 'Buku Fisik' }}
                            </span>
                        </p>
                    </div>
                    <div class="pesanan-status">
                        <span class="badge status-badge {{ $item->status_badge }}">
                                                      {{ $item->status_text }}
                        </span>
                    </div>
                </div>
                
                <div class="pesanan-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            @if($item->buku && $item->buku->gambar_sampul)
                                <img src="{{ asset('storage/' . $item->buku->gambar_sampul) }}" 
                                     alt="{{ $item->buku->judul_buku }}" 
                                     class="book-cover">
                            @else
                                <div class="book-cover-placeholder">
                                    <i class="fas fa-book"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <div class="book-details">
                                <p class="book-author">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $item->buku->penulis ?? 'Penulis Tidak Diketahui' }}
                                </p>
                                <p class="book-quantity">
                                    <i class="fas fa-boxes me-1"></i>
                                    Jumlah: {{ $item->quantity }} item
                                </p>
                                @if($item->alamat_pengiriman && $item->tipe_buku === 'fisik')
                                    <p class="shipping-address">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ Str::limit($item->alamat_pengiriman, 50) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="price-info">
                                @if($item->diskon > 0)
                                    <p class="original-price">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </p>
                                    <p class="discount">
                                        -Rp {{ number_format($item->diskon, 0, ',', '.') }}
                                    </p>
                                @endif
                                <p class="final-price">
                                    Rp {{ number_format($item->total, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="pesanan-actions">
                                @if($item->canUploadPayment())
                                    <a href="{{ route('user.pesanan.payment', $item->id) }}" 
                                       class="btn btn-warning btn-sm">
                                        <i class="fas fa-credit-card me-1"></i>Bayar
                                    </a>
                                @endif
                                
                                @if($item->canDownloadEbook())
                                    <a href="{{ route('user.pesanan.download', $item->id) }}" 
                                       class="btn btn-success btn-sm">
                                        <i class="fas fa-download me-1"></i>Download
                                    </a>
                                @endif
                                
                                <a href="{{ route('user.pesanan.show', $item->id) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </a>
                                
                                @if($item->canBeCancelled())
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="cancelOrder({{ $item->id }})">
                                        <i class="fas fa-times me-1"></i>Batal
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($item->status === 'dikirim' && $item->estimated_delivery)
                    <div class="pesanan-footer">
                        <div class="delivery-info">
                            <i class="fas fa-truck me-2"></i>
                            <span>Estimasi tiba: {{ $item->estimated_delivery->format('d M Y') }}</span>
                        </div>
                    </div>
                @endif
                
                @if($item->needsAttention())
                    <div class="pesanan-footer">
                        <div class="attention-notice">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span>Pesanan ini memerlukan perhatian. Silakan hubungi customer service jika ada kendala.</span>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <i class="fas fa-shopping-cart"></i>
        <h5>Belum Ada Pesanan</h5>
        <p>Anda belum memiliki pesanan dengan kriteria yang dipilih.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">
            <i class="fas fa-book me-2"></i>Mulai Belanja
        </a>
    </div>
@endif

<style>
/* Pesanan List Styles */
.pesanan-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.pesanan-item {
    background: #fafafa;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    overflow: hidden;
    transition: all 0.2s ease;
}

.pesanan-item:hover {
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    border-color: #d1d5db;
}

.pesanan-header {
    background: white;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.pesanan-info {
    flex: 1;
}

.pesanan-title {
    font-size: 1rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.pesanan-meta {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.separator {
    color: #d1d5db;
}

.order-number {
    font-weight: 500;
    color: #4f46e5;
}

.pesanan-status {
    flex-shrink: 0;
}

.status-badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    font-weight: 500;
}

.pesanan-body {
    padding: 1.25rem;
    background: white;
}

.book-cover {
    width: 60px;
    height: 80px;
    object-fit: cover;
    border-radius: 0.375rem;
    border: 1px solid #e5e7eb;
}

.book-cover-placeholder {
    width: 60px;
    height: 80px;
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    font-size: 1.5rem;
}

.book-details {
    padding-left: 1rem;
}

.book-details p {
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    color: #6b7280;
    display: flex;
    align-items: center;
}

.book-details p:last-child {
    margin-bottom: 0;
}

.book-author {
    font-weight: 500;
    color: #374151;
}

.price-info {
    text-align: right;
}

.original-price {
    font-size: 0.875rem;
    color: #9ca3af;
    text-decoration: line-through;
    margin-bottom: 0.25rem;
}

.discount {
    font-size: 0.875rem;
    color: #dc2626;
    margin-bottom: 0.25rem;
}

.final-price {
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.pesanan-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    align-items: stretch;
}

.pesanan-actions .btn {
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
    white-space: nowrap;
}

.pesanan-footer {
    background: #f9fafb;
    padding: 0.75rem 1.25rem;
    border-top: 1px solid #e5e7eb;
}

.delivery-info {
    color: #059669;
    font-size: 0.875rem;
    font-weight: 500;
    display: flex;
    align-items: center;
}

.attention-notice {
    color: #d97706;
    font-size: 0.875rem;
    font-weight: 500;
    display: flex;
    align-items: center;
}

/* Status Badge Colors */
.bg-yellow-100 { background-color: #fef3c7; }
.text-yellow-800 { color: #92400e; }
.bg-blue-100 { background-color: #dbeafe; }
.text-blue-800 { color: #1e40af; }
.bg-green-100 { background-color: #d1fae5; }
.text-green-800 { color: #065f46; }
.bg-purple-100 { background-color: #e9d5ff; }
.text-purple-800 { color: #5b21b6; }
.bg-indigo-100 { background-color: #e0e7ff; }
.text-indigo-800 { color: #3730a3; }
.bg-red-100 { background-color: #fee2e2; }
.text-red-800 { color: #991b1b; }
.bg-gray-100 { background-color: #f3f4f6; }
.text-gray-800 { color: #1f2937; }

/* Responsive */
@media (max-width: 768px) {
    .pesanan-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .pesanan-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .separator {
        display: none;
    }
    
    .pesanan-body .row {
        --bs-gutter-x: 0.5rem;
    }
    
    .pesanan-body .col-md-2,
    .pesanan-body .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .book-details {
        padding-left: 0;
        padding-top: 1rem;
    }
    
    .price-info {
        text-align: left;
        margin-bottom: 1rem;
    }
    
    .pesanan-actions {
        flex-direction: row;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .pesanan-actions .btn {
        flex: 1;
        min-width: 0;
    }
}

@media (max-width: 576px) {
    .pesanan-item {
        margin: 0 -0.5rem;
        border-radius: 0.5rem;
    }
    
    .pesanan-header,
    .pesanan-body,
    .pesanan-footer {
        padding: 1rem;
    }
    
    .book-cover,
    .book-cover-placeholder {
        width: 50px;
        height: 70px;
    }
    
    .pesanan-title {
        font-size: 0.9rem;
    }
    
    .pesanan-actions .btn {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
    }
}

/* Print styles */
@media print {
    .pesanan-actions {
        display: none;
    }
    
    .pesanan-item {
        break-inside: avoid;
        box-shadow: none;
        border: 1px solid #ccc;
    }
    
    .pesanan-footer {
        background: white;
    }
}
</style>

<script>
function cancelOrder(orderId) {
    if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
        // Show loading state
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Membatalkan...';
        button.disabled = true;
        
        fetch(`/user/pesanan/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Pesanan berhasil dibatalkan', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showNotification(data.message || 'Gagal membatalkan pesanan', 'error');
                button.innerHTML = originalText;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat membatalkan pesanan', 'error');
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}

function showNotification(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' : 
                      type === 'error' ? 'alert-danger' : 'alert-info';
    
    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}
</script>
