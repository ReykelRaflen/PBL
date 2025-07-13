@extends('user.layouts.app')

@section('content')
    <div class="container mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($book->judul_buku, 30) }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Gambar Buku -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    @if($book->cover)
                                        <img src="{{ asset('storage/' . $book->cover) }}" 
                         class="card-img-top" 
                         alt="{{ $book->judul_buku }}"
                         style="width: 100%; object-fit: contain; max-height: 600px;">

                    @else
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light"
                            style="height: 400px;">
                            <div class="text-center text-muted">
                                <i class="fas fa-book fa-3x mb-3"></i>
                                <div>Tidak Ada Sampul</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>


            <!-- Detail Buku -->
            <div class="col-md-8">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <!-- Judul dan Penulis -->
                        <div class="mb-4">
                            <h1 class="card-title fw-bold text-primary mb-2">{{ $book->judul_buku }}</h1>
                            <p class="text-muted mb-0">
                                <i class="fas fa-user-edit me-2"></i>oleh <strong>{{ $book->penulis }}</strong>
                            </p>
                        </div>

                        <!-- Detail Buku -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Detail Buku</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="text-muted" style="width: 40%;"><i class="fas fa-book me-2"></i>Judul
                                            </td>
                                            <td>{{ $book->judul_buku }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="fas fa-user-edit me-2"></i>Penulis</td>
                                            <td>{{ $book->penulis }}</td>
                                        </tr>
                                        @if($book->penerbit)
                                            <tr>
                                                <td class="text-muted"><i class="fas fa-building me-2"></i>Penerbit</td>
                                                <td>{{ $book->penerbit }}</td>
                                            </tr>
                                        @endif
                                        @if($book->tahun_terbit)
                                            <tr>
                                                <td class="text-muted"><i class="fas fa-calendar me-2"></i>Tahun Terbit</td>
                                                <td>{{ $book->tahun_terbit }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        @if($book->isbn)
                                            <tr>
                                                <td class="text-muted" style="width: 40%;"><i
                                                        class="fas fa-barcode me-2"></i>ISBN</td>
                                                <td>{{ $book->isbn }}</td>
                                            </tr>
                                        @endif
                                        @if($book->kategori)
                                            <tr>
                                                <td class="text-muted"><i class="fas fa-tags me-2"></i>Kategori</td>
                                                <td><span class="badge bg-secondary">{{ $book->kategori->nama }}</span></td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td class="text-muted"><i class="fas fa-boxes me-2"></i>Stok</td>
                                            <td>
                                                @if($book->stok > 0)
                                                    <span class="badge bg-success">{{ $book->stok }} tersedia</span>
                                                @else
                                                    <span class="badge bg-danger">Stok habis</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if($book->file_buku)
                                            <tr>
                                                <td class="text-muted"><i class="fas fa-file-pdf me-2"></i>E-book</td>
                                                <td><span class="badge bg-info">Tersedia</span></td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Harga Section -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Pilihan Pembelian</h5>

                            <!-- Buku Fisik -->
                            @if($book->harga)
                                <div class="card mb-3 border-primary">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="card-title mb-1">
                                                    <i class="fas fa-book text-primary me-2"></i>Buku Fisik
                                                    @if($book->promo && $book->promo->isActive() && $book->harga_promo && $book->harga_promo < $book->harga)
                                                        @php
                                                            $diskonPersen = round((($book->harga - $book->harga_promo) / $book->harga) * 100);
                                                        @endphp
                                                        <span class="badge bg-danger ms-2">-{{ $diskonPersen }}%</span>
                                                    @endif
                                                </h6>
                                                <small class="text-muted">Buku cetak dengan kualitas premium</small>
                                                <div class="mt-1">
                                                    @if($book->stok > 0)
                                                        <small class="text-success">
                                                            <i class="fas fa-check-circle me-1"></i>Stok tersedia: {{ $book->stok }}
                                                            buku
                                                        </small>
                                                    @else
                                                        <small class="text-danger">
                                                            <i class="fas fa-times-circle me-1"></i>Stok habis
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                @if($book->harga_promo && $book->harga_promo < $book->harga)
                                                    <div class="text-muted text-decoration-line-through small">
                                                        Rp {{ number_format($book->harga, 0, ',', '.') }}
                                                    </div>
                                                    <div class="text-danger fw-bold h5 mb-0">
                                                        Rp {{ number_format($book->harga_promo, 0, ',', '.') }}
                                                    </div>
                                                @else
                                                    <div class="text-primary fw-bold h5 mb-0">
                                                        Rp {{ number_format($book->harga, 0, ',', '.') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        @if($book->stok > 0)
                                            @auth
                                                <form method="GET" action="{{ route('user.pesanan.create') }}" class="mt-3">
                                                    <input type="hidden" name="buku_id" value="{{ $book->id }}">
                                                    <input type="hidden" name="tipe_buku" value="fisik">

                                                    <!-- Quantity Selector -->
                                                    <div class="row align-items-center mb-3">
                                                        <div class="col-auto">
                                                            <label for="quantity_fisik" class="form-label mb-0">Jumlah:</label>
                                                        </div>
                                                        <div class="col-auto">
                                                            <div class="input-group" style="width: 120px;">
                                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                                    onclick="decreaseQuantity('quantity_fisik')">-</button>
                                                                <input type="number" class="form-control form-control-sm text-center"
                                                                    id="quantity_fisik" name="quantity" value="1" min="1"
                                                                    max="{{ $book->stok }}" required>
                                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                                    onclick="increaseQuantity('quantity_fisik', {{ $book->stok }})">+</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button type="submit" class="btn btn-primary w-100">
                                                        <i class="fas fa-shopping-cart me-2"></i>Pesan Buku Fisik
                                                    </button>
                                                </form>
                                            @else
                                                <div class="mt-3">
                                                    <a href="{{ route('login') }}" class="btn btn-primary w-100">
                                                        <i class="fas fa-sign-in-alt me-2"></i>Login untuk Memesan
                                                    </a>
                                                </div>
                                            @endauth

                                        @else
                                            <div class="mt-3">
                                                <button type="button" class="btn btn-secondary w-100" disabled>
                                                    <i class="fas fa-times me-2"></i>Stok Habis
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- E-book -->
                            @if($book->harga_ebook || $book->file_buku)
                                <div class="card border-success">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="card-title mb-1">
                                                    <i class="fas fa-tablet-alt text-success me-2"></i>E-book
                                                    @if($book->promo && $book->promo->isActive() && $book->harga_ebook_promo && $book->harga_ebook_promo < $book->harga_ebook)
                                                        @php
                                                            $diskonPersenEbook = round((($book->harga_ebook - $book->harga_ebook_promo) / $book->harga_ebook) * 100);
                                                        @endphp
                                                        <span class="badge bg-danger ms-2">-{{ $diskonPersenEbook }}%</span>
                                                    @endif
                                                </h6>
                                                <small class="text-muted">Download langsung setelah pembayaran</small>
                                            </div>
                                            <div class="text-end">
                                                @if($book->harga_ebook)
                                                    @if($book->harga_ebook_promo && $book->harga_ebook_promo < $book->harga_ebook)
                                                        <div class="text-muted text-decoration-line-through small">
                                                            Rp {{ number_format($book->harga_ebook, 0, ',', '.') }}
                                                        </div>
                                                        <div class="text-success fw-bold h5 mb-0">
                                                            Rp {{ number_format($book->harga_ebook_promo, 0, ',', '.') }}
                                                        </div>
                                                    @else
                                                        <div class="text-success fw-bold h5 mb-0">
                                                            Rp {{ number_format($book->harga_ebook, 0, ',', '.') }}
                                                        </div>
                                                    @endif
                                                @else
                                                    <!-- Fallback: 60% dari harga fisik -->
                                                    <div class="text-success fw-bold h5 mb-0">
                                                        @php
                                                            $hargaEbook = $book->harga_promo ? ($book->harga_promo * 0.6) : ($book->harga * 0.6);
                                                        @endphp
                                                        Rp {{ number_format($hargaEbook, 0, ',', '.') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        @auth
                                            <form method="GET" action="{{ route('user.pesanan.create') }}" class="mt-3">
                                                <input type="hidden" name="buku_id" value="{{ $book->id }}">
                                                <input type="hidden" name="tipe_buku" value="ebook">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn btn-success w-100">
                                                    <i class="fas fa-download me-2"></i>Pesan E-book
                                                </button>
                                            </form>
                                        @else
                                            <div class="mt-3">
                                                <a href="{{ route('login') }}" class="btn btn-success w-100">
                                                    <i class="fas fa-sign-in-alt me-2"></i>Login untuk Memesan
                                                </a>
                                            </div>
                                        @endauth

                                    </div>
                                </div>
                            @endif

                            <!-- Promo Info -->
                            @if($book->promo && $book->promo->isActive())
                                <div class="alert alert-warning mt-3" role="alert">
                                    <h6 class="alert-heading"><i class="fas fa-tag me-2"></i>Promo Aktif!</h6>
                                    <p class="mb-1"><strong>{{ $book->promo->kode_promo }}</strong> -
                                        {{ $book->promo->keterangan }}</p>
                                    <small class="text-muted">
                                        Berlaku hingga: {{ $book->promo->tanggal_selesai->format('d F Y') }}
                                        @if($book->promo->kuota)
                                            | Kuota tersisa: {{ $book->promo->kuota - $book->promo->kuota_terpakai }}
                                        @endif
                                    </small>
                                </div>
                            @endif
                        </div>

                        <!-- Tombol Tambahan -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button class="btn btn-outline-secondary" type="button" onclick="addToWishlist()">
                                <i class="fas fa-heart me-2"></i>Tambah ke Wishlist
                            </button>
                            <button class="btn btn-outline-info" type="button" onclick="shareBook()">
                                <i class="fas fa-share-alt me-2"></i>Bagikan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript untuk Quantity Control dan Pemesanan -->
        <script>
            // Quantity Control Functions
            function increaseQuantity(inputId, maxStock) {
                const input = document.getElementById(inputId);
                const currentValue = parseInt(input.value);

                if (currentValue < maxStock) {
                    input.value = currentValue + 1;
                }
            }

            function decreaseQuantity(inputId) {
                const input = document.getElementById(inputId);
                const currentValue = parseInt(input.value);
                const minValue = parseInt(input.getAttribute('min'));

                if (currentValue > minValue) {
                    input.value = currentValue - 1;
                }
            }

            // Loading state untuk button
            function showLoadingState(button, originalText) {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';

                // Re-enable setelah 3 detik jika masih disabled
                setTimeout(() => {
                    if (button.disabled) {
                        button.disabled = false;
                        button.innerHTML = originalText;
                    }
                }, 3000);
            }

            // Function untuk pesan buku fisik
            function pesanBukuFisik() {
                const button = event.target;
                const originalText = button.innerHTML;

                // Show loading state
                showLoadingState(button, originalText);

                const quantity = document.getElementById('quantity_fisik').value;
                const bookId = {{ $book->id }};
                const maxStock = {{ $book->stok }};

                // Validasi quantity
                if (quantity < 1 || quantity > maxStock) {
                    alert(`Jumlah tidak valid. Maksimal stok: ${maxStock}`);
                    button.disabled = false;
                    button.innerHTML = originalText;
                    return;
                }

                // Redirect ke halaman pemesanan dengan parameter
                const url = `{{ route('user.pesanan.create') }}?buku_id=${bookId}&tipe_buku=fisik&quantity=${quantity}`;
                window.location.href = url;
            }

            // Function untuk pesan e-book
            function pesanEbook() {
                const button = event.target;
                const originalText = button.innerHTML;

                // Show loading state
                showLoadingState(button, originalText);

                const bookId = {{ $book->id }};

                // Redirect ke halaman pemesanan dengan parameter
                const url = `{{ route('user.pesanan.create') }}?buku_id=${bookId}&tipe_buku=ebook&quantity=1`;
                window.location.href = url;
            }

            // Toast notification function
            function showToast(type, message) {
                // Create toast element
                const toast = document.createElement('div');
                toast.className = `alert alert-${type} position-fixed`;
                toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                toast.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                        <span>${message}</span>
                        <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
                    </div>
                `;

                document.body.appendChild(toast);

                // Auto remove after 3 seconds
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.remove();
                    }
                }, 3000);
            }

            // Add to wishlist functionality (placeholder)
            function addToWishlist() {
                const bookId = {{ $book->id }};

                // TODO: Implement wishlist functionality
                showToast('info', 'Fitur wishlist akan segera tersedia!');
            }

            // Share functionality
            function shareBook() {
                if (navigator.share) {
                    navigator.share({
                        title: '{{ $book->judul_buku }}',
                        text: 'Lihat buku menarik ini: {{ $book->judul_buku }} oleh {{ $book->penulis }}',
                        url: window.location.href
                    });
                } else {
                    // Fallback: copy to clipboard
                    navigator.clipboard.writeText(window.location.href).then(() => {
                        showToast('success', 'Link berhasil disalin ke clipboard!');
                    });
                }
            }

            // Validasi real-time untuk quantity input
            document.addEventListener('DOMContentLoaded', function () {
                const quantityInput = document.getElementById('quantity_fisik');
                if (quantityInput) {
                    quantityInput.addEventListener('input', function () {
                        const value = parseInt(this.value);
                        const max = parseInt(this.getAttribute('max'));
                        const min = parseInt(this.getAttribute('min'));

                        if (value > max) {
                            this.value = max;
                            showToast('warning', `Maksimal quantity adalah ${max}`);
                        } else if (value < min) {
                            this.value = min;
                            showToast('warning', `Minimal quantity adalah ${min}`);
                        }
                    });
                }
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function (e) {
                // Ctrl + B untuk pesan buku fisik (jika tersedia)
                if (e.ctrlKey && e.key === 'b' && document.getElementById('quantity_fisik')) {
                    e.preventDefault();
                    pesanBukuFisik();
                }

                // Ctrl + E untuk pesan e-book (jika tersedia)
                if (e.ctrlKey && e.key === 'e' && {{ ($book->harga_ebook || $book->file_buku) ? 'true' : 'false' }}) {
                    e.preventDefault();
                    pesanEbook();
                }
            });

            // Price comparison tooltip
            function showPriceComparison() {
                const fisikPrice = {{ $book->harga_promo ?? $book->harga ?? 0 }};
                @if($book->harga_ebook)
                    const ebookPrice = {{ $book->harga_ebook_promo ?? $book->harga_ebook }};
                @else
                    const ebookPrice = {{ ($book->harga_promo ?? $book->harga ?? 0) * 0.6 }};
                @endif

                if (fisikPrice > 0 && ebookPrice > 0) {
                    const savings = fisikPrice - ebookPrice;
                    const percentage = Math.round((savings / fisikPrice) * 100);

                    if (savings > 0) {
                        showToast('info', `Hemat Rp ${new Intl.NumberFormat('id-ID').format(savings)} (${percentage}%) dengan memilih e-book!`);
                    }
                }
            }

            // Auto-show price comparison after 5 seconds (only if both options available)
            @if($book->harga && ($book->harga_ebook || $book->file_buku))
                setTimeout(() => {
                    showPriceComparison();
                }, 5000);
            @endif
        </script>

        <!-- Deskripsi Buku -->
        @if($book->deskripsi)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">
                                <i class="fas fa-align-left text-primary me-2"></i>Deskripsi Buku
                            </h5>
                            <div class="text-muted" style="line-height: 1.8; text-align: justify;">
                                {{ $book->deskripsi }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Buku Terkait -->
        @if($relatedBooks->count() > 0)
            <div class="mt-5">
                <h4 class="mb-4 fw-bold">Buku Terkait</h4>
                <div class="row">
                    @foreach($relatedBooks as $relatedBook)
                        <div class="col-6 col-md-3 mb-4">
                            <div class="card h-100 shadow-sm" style="cursor: pointer; transition: transform 0.2s;"
                                onclick="window.location.href='{{ route('books.show', $relatedBook->id) }}'"
                                onmouseover="this.style.transform='translateY(-3px)'"
                                onmouseout="this.style.transform='translateY(0)'">

                                <!-- Badge Diskon -->
                                @if($relatedBook->promo && $relatedBook->promo->isActive() && $relatedBook->harga_promo && $relatedBook->harga_promo < $relatedBook->harga)
                                    @php
                                        $diskonPersen = round((($relatedBook->harga - $relatedBook->harga_promo) / $relatedBook->harga) * 100);
                                    @endphp
                                    <div class="position-absolute top-0 end-0 m-2" style="z-index: 1;">
                                        <span class="badge bg-danger">-{{ $diskonPersen }}%</span>
                                    </div>
                                @endif

                                <!-- Cover Buku -->
                                @if($relatedBook->cover)
                                    <img src="{{ asset('storage/' . $relatedBook->cover) }}" class="card-img-top"
                                        alt="{{ $relatedBook->judul_buku }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light"
                                        style="height: 200px;">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-book fa-2x mb-2"></i>
                                            <div style="font-size: 0.8em;">No Cover</div>
                                        </div>
                                    </div>
                                @endif

                                <div class="card-body d-flex flex-column">
                                    <!-- Judul Buku -->
                                    <h6 class="card-title fw-bold text-primary mb-2" style="line-height: 1.3;">
                                        {{ Str::limit($relatedBook->judul_buku, 40) }}
                                    </h6>

                                    <!-- Penulis -->
                                    <p class="text-muted mb-2" style="font-size: 0.85em;">
                                        <i class="fas fa-user-edit me-1"></i>{{ Str::limit($relatedBook->penulis, 20) }}
                                    </p>

                                    <!-- Kategori -->
                                    @if($relatedBook->kategori)
                                        <p class="text-muted mb-2" style="font-size: 0.8em;">
                                            <span class="badge bg-secondary">{{ $relatedBook->kategori->nama }}</span>
                                        </p>
                                    @endif

                                    <!-- Harga -->
                                    <div class="mt-auto">
                                        @if($relatedBook->harga)
                                            @if($relatedBook->harga_promo && $relatedBook->harga_promo < $relatedBook->harga)
                                                <div class="text-muted text-decoration-line-through" style="font-size: 0.8em;">
                                                    Rp {{ number_format($relatedBook->harga, 0, ',', '.') }}
                                                </div>
                                                <div class="text-danger fw-bold">
                                                    Rp {{ number_format($relatedBook->harga_promo, 0, ',', '.') }}
                                                </div>
                                            @else
                                                <div class="text-primary fw-bold">
                                                    Rp {{ number_format($relatedBook->harga, 0, ',', '.') }}
                                                </div>
                                            @endif
                                        @endif

                                        <!-- Stok Info -->
                                        <div class="mt-2">
                                            @if($relatedBook->stok > 0)
                                                <span class="badge bg-success" style="font-size: 0.7em;">Tersedia</span>
                                            @else
                                                <span class="badge bg-danger" style="font-size: 0.7em;">Habis</span>
                                            @endif

                                            @if($relatedBook->harga_ebook || $relatedBook->file_buku)
                                                <span class="badge bg-info ms-1" style="font-size: 0.7em;">E-book</span>
                                            @endif
                                        </div>

                                        <!-- Button -->
                                        <button class="btn btn-outline-primary btn-sm w-100 mt-2" style="font-size: 0.8em;">
                                            <i class="fas fa-eye me-1"></i>Lihat Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Link ke semua buku -->
                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="fas fa-book-open me-2"></i>Lihat Semua Buku
                    </a>
                </div>
            </div>
        @endif

        <!-- Rekomendasi Berdasarkan Kategori -->
        @if($book->kategori)
            <div class="mt-5">
                <h4 class="mb-4 fw-bold">Buku Lain dalam Kategori "{{ $book->kategori->nama }}"</h4>
                <div class="row">
                    @php
                        $categoryBooks = App\Models\Book::where('kategori_id', $book->kategori_id)
                            ->where('id', '!=', $book->id)
                            ->whereNotIn('id', $relatedBooks->pluck('id'))
                            ->limit(4)
                            ->get();
                    @endphp

                    @foreach($categoryBooks as $categoryBook)
                        <div class="col-6 col-md-3 mb-4">
                            <div class="card h-100 shadow-sm" style="cursor: pointer; transition: transform 0.2s;"
                                onclick="window.location.href='{{ route('books.show', $categoryBook->id) }}'"
                                onmouseover="this.style.transform='translateY(-3px)'"
                                onmouseout="this.style.transform='translateY(0)'">

                                <!-- Badge Diskon -->
                                @if($categoryBook->promo && $categoryBook->promo->isActive() && $categoryBook->harga_promo && $categoryBook->harga_promo < $categoryBook->harga)
                                    @php
                                        $diskonPersen = round((($categoryBook->harga - $categoryBook->harga_promo) / $categoryBook->harga) * 100);
                                    @endphp
                                    <div class="position-absolute top-0 end-0 m-2" style="z-index: 1;">
                                        <span class="badge bg-danger">-{{ $diskonPersen }}%</span>
                                    </div>
                                @endif

                                <!-- Cover Buku -->
                                @if($categoryBook->cover)
                                    <img src="{{ asset('storage/' . $categoryBook->cover) }}" class="card-img-top"
                                        alt="{{ $categoryBook->judul_buku }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light"
                                        style="height: 200px;">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-book fa-2x mb-2"></i>
                                            <div style="font-size: 0.8em;">No Cover</div>
                                        </div>
                                    </div>
                                @endif

                                <div class="card-body d-flex flex-column">
                                    <!-- Judul Buku -->
                                    <h6 class="card-title fw-bold text-primary mb-2" style="line-height: 1.3;">
                                        {{ Str::limit($categoryBook->judul_buku, 40) }}
                                    </h6>

                                    <!-- Penulis -->
                                    <p class="text-muted mb-2" style="font-size: 0.85em;">
                                        <i class="fas fa-user-edit me-1"></i>{{ Str::limit($categoryBook->penulis, 20) }}
                                    </p>

                                    <!-- Harga -->
                                    <div class="mt-auto">
                                        @if($categoryBook->harga)
                                            @if($categoryBook->harga_promo && $categoryBook->harga_promo < $categoryBook->harga)
                                                <div class="text-muted text-decoration-line-through" style="font-size: 0.8em;">
                                                    Rp {{ number_format($categoryBook->harga, 0, ',', '.') }}
                                                </div>
                                                <div class="text-danger fw-bold">
                                                    Rp {{ number_format($categoryBook->harga_promo, 0, ',', '.') }}
                                                </div>
                                            @else
                                                <div class="text-primary fw-bold">
                                                    Rp {{ number_format($categoryBook->harga, 0, ',', '.') }}
                                                </div>
                                            @endif
                                        @endif

                                        <!-- Stok Info -->
                                        <div class="mt-2">
                                            @if($categoryBook->stok > 0)
                                                <span class="badge bg-success" style="font-size: 0.7em;">Tersedia</span>
                                            @else
                                                <span class="badge bg-danger" style="font-size: 0.7em;">Habis</span>
                                            @endif

                                            @if($categoryBook->harga_ebook || $categoryBook->file_buku)
                                                <span class="badge bg-info ms-1" style="font-size: 0.7em;">E-book</span>
                                            @endif
                                        </div>

                                        <!-- Button -->
                                        <button class="btn btn-outline-primary btn-sm w-100 mt-2" style="font-size: 0.8em;">
                                            <i class="fas fa-eye me-1"></i>Lihat Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Back to Top Button -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1000;">
            <button type="button" class="btn btn-primary rounded-circle"
                onclick="window.scrollTo({top: 0, behavior: 'smooth'})" title="Kembali ke atas">
                <i class="fas fa-arrow-up"></i>
            </button>
        </div>
    </div>

    @push('scripts')
        <script>
            // Additional scripts for enhanced functionality

            // Smooth scroll untuk anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Auto-hide alert setelah 5 detik
            setTimeout(function () {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    if (alert.classList.contains('alert-warning')) {
                        alert.style.transition = 'opacity 0.5s';
                        alert.style.opacity = '0.7';
                    }
                });
            }, 5000);

            // Lazy loading untuk gambar
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            if (img.dataset.src) {
                                img.src = img.dataset.src;
                                img.classList.remove('lazy');
                                imageObserver.unobserve(img);
                            }
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            }

            // Enhanced form validation
            document.addEventListener('DOMContentLoaded', function () {
                // Real-time stock validation
                const quantityInput = document.getElementById('quantity_fisik');
                if (quantityInput) {
                    quantityInput.addEventListener('change', function () {
                        const quantity = parseInt(this.value);
                        const maxStock = parseInt(this.getAttribute('max'));

                        if (quantity > maxStock) {
                            this.value = maxStock;
                            showToast('warning', `Stok maksimal adalah ${maxStock} buku`);
                        }
                    });
                }

                // Add loading animation to images
                document.querySelectorAll('img').forEach(img => {
                    img.addEventListener('load', function () {
                        this.style.opacity = '1';
                    });

                    img.addEventListener('error', function () {
                        this.src = '/images/no-image.png'; // Fallback image
                    });
                });
            });

            // Price calculator for bulk orders
            function calculateBulkPrice(quantity, unitPrice) {
                let total = quantity * unitPrice;

                // Apply bulk discount if quantity >= 5
                if (quantity >= 5) {
                    const discount = total * 0.05; // 5% discount
                    total = total - discount;
                    showToast('success', `Diskon bulk 5% diterapkan! Hemat Rp ${new Intl.NumberFormat('id-ID').format(discount)}`);
                }

                return total;
            }

            // Enhanced quantity change with price update
            function updateQuantityAndPrice(inputId) {
                const input = document.getElementById(inputId);
                const quantity = parseInt(input.value);
                const unitPrice = {{ $book->harga_promo ?? $book->harga ?? 0 }};

                if (quantity > 0 && unitPrice > 0) {
                    const total = calculateBulkPrice(quantity, unitPrice);

                    // Update price display if exists
                    const priceDisplay = document.getElementById('total-price-fisik');
                    if (priceDisplay) {
                        priceDisplay.textContent = `Total: Rp ${new Intl.NumberFormat('id-ID').format(total)}`;
                    }
                }
            }

            // Add event listeners for quantity changes
            document.addEventListener('DOMContentLoaded', function () {
                const quantityInput = document.getElementById('quantity_fisik');
                if (quantityInput) {
                    quantityInput.addEventListener('input', function () {
                        updateQuantityAndPrice('quantity_fisik');
                    });
                }
            });

            // Enhanced error handling
            window.addEventListener('error', function (e) {
                console.error('JavaScript Error:', e.error);
                // Don't show error to user unless in development
                @if(config('app.debug'))
                    showToast('danger', 'Terjadi kesalahan JavaScript. Silakan refresh halaman.');
                @endif
        });

            // Performance monitoring
            window.addEventListener('load', function () {
                const loadTime = performance.now();
                if (loadTime > 3000) { // If page takes more than 3 seconds
                    console.warn('Page load time is slow:', loadTime + 'ms');
                }
            });

            // Add to cart animation
            function addCartAnimation(button) {
                button.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    button.style.transform = 'scale(1)';
                }, 150);
            }

            // Enhanced button click handlers
            document.addEventListener('click', function (e) {
                if (e.target.matches('.btn-primary, .btn-success')) {
                    addCartAnimation(e.target);
                }
            });

            // Keyboard accessibility
            document.addEventListener('keydown', function (e) {
                // Alt + 1 untuk fokus ke buku fisik
                if (e.altKey && e.key === '1') {
                    e.preventDefault();
                    const fisikButton = document.querySelector('button[onclick="pesanBukuFisik()"]');
                    if (fisikButton) fisikButton.focus();
                }

                // Alt + 2 untuk fokus ke e-book
                if (e.altKey && e.key === '2') {
                    e.preventDefault();
                    const ebookButton = document.querySelector('button[onclick="pesanEbook()"]');
                    if (ebookButton) ebookButton.focus();
                }
            });

            // Add accessibility hints
            document.addEventListener('DOMContentLoaded', function () {
                const fisikButton = document.querySelector('button[onclick="pesanBukuFisik()"]');
                const ebookButton = document.querySelector('button[onclick="pesanEbook()"]');

                if (fisikButton) {
                    fisikButton.setAttribute('title', 'Tekan Alt+1 untuk shortcut');
                }

                if (ebookButton) {
                    ebookButton.setAttribute('title', 'Tekan Alt+2 untuk shortcut');
                }
            });
        </script>
    @endpush
@endsection