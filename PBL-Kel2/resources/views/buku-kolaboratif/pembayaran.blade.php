@extends('user.layouts.app')

@section('title', 'Pembayaran')

@section('content')
    <div class="container mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('buku-kolaboratif.index') }}">Buku Kolaboratif</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
            </ol>
        </nav>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Informasi Pesanan -->
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>Detail Pesanan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                @if($pesananBuku->bukuKolaboratif->gambar_sampul)
                                    <img src="{{ asset('storage/' . $pesananBuku->bukuKolaboratif->gambar_sampul) }}"
                                        class="img-fluid rounded" alt="{{ $pesananBuku->bukuKolaboratif->judul }}"
                                        style="max-height: 150px;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                        style="height: 150px;">
                                        <i class="fas fa-book fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <h5 class="text-primary">{{ $pesananBuku->bukuKolaboratif->judul }}</h5>
                                <p class="text-muted mb-2">Bab {{ $pesananBuku->babBuku->nomor_bab }}:
                                    {{ $pesananBuku->babBuku->judul_bab }}</p>

                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Nomor Pesanan:</small>
                                        <div class="fw-bold">{{ $pesananBuku->nomor_pesanan }}</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Status:</small>
                                        <div>
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Menunggu Pembayaran
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-6">
                                        <small class="text-muted">Tanggal Pesanan:</small>
                                        <div class="fw-bold">{{ $pesananBuku->tanggal_pesanan->format('d/m/Y H:i') }}</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Harga:</small>
                                        <div class="fw-bold">Rp {{ number_format($pesananBuku->jumlah_bayar, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Rincian Harga -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="border-top pt-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted">Harga Bab:</span>
                                                <span>Rp {{ number_format($pesananBuku->jumlah_bayar, 0, ',', '.') }}</span>
                                            </div>

                                            <hr class="my-2">
                                            <div class="d-flex justify-content-between">
                                                <strong class="text-primary">Total Pembayaran:</strong>
                                                <strong class="text-primary h5">Rp
                                                    {{ number_format($pesananBuku->jumlah_bayar, 0, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($pesananBuku->catatan)
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <small class="text-muted">Catatan:</small>
                                            <div class="text-sm">{{ $pesananBuku->catatan }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Pembayaran -->
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-university me-2"></i>Transfer Bank
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Informasi Transfer Bank -->
                        <div class="alert alert-info border-left mb-4">
                            <h6><i class="fas fa-info-circle me-2"></i>Informasi Transfer Bank</h6>
                            <div class="row">
                                @forelse($rekenings as $rekening)
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        @php
                                            // Tentukan logo bank berdasarkan nama bank
                                            $bankLogos = [
                                                'BCA' => 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg',
                                                'MANDIRI' => 'https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg',
                                                'BRI' => 'https://upload.wikimedia.org/wikipedia/commons/2/2e/BRI_2020.svg',
                                                'BNI' => 'https://upload.wikimedia.org/wikipedia/commons/5/55/BNI_logo.svg',
                                                'BTN' => 'https://upload.wikimedia.org/wikipedia/commons/3/3e/Logo_BTN.png',
                                                'CIMB' => 'https://upload.wikimedia.org/wikipedia/commons/8/8c/CIMB_Niaga_logo.svg',
                                                'DANAMON' => 'https://upload.wikimedia.org/wikipedia/commons/3/3e/Danamon_logo.svg',
                                                'PERMATA' => 'https://upload.wikimedia.org/wikipedia/commons/2/2e/PermataBank_logo.svg',
                                                'MEGA' => 'https://upload.wikimedia.org/wikipedia/commons/e/e3/Bank_Mega_logo.svg',
                                                'PANIN' => 'https://upload.wikimedia.org/wikipedia/commons/7/7b/Panin_Bank_logo.svg'
                                            ];
                                            
                                            $bankName = strtoupper($rekening->bank);
                                            $logoUrl = $bankLogos[$bankName] ?? 'https://via.placeholder.com/100x30/007bff/ffffff?text=' . urlencode($rekening->bank);
                                        @endphp
                                        <img src="{{ $logoUrl }}" 
                                             alt="{{ $rekening->bank }}" 
                                             style="height: 20px; max-width: 60px; object-fit: contain;" 
                                             class="me-2"
                                             onerror="this.src='https://via.placeholder.com/60x20/007bff/ffffff?text={{ urlencode($rekening->bank) }}'">
                                        <strong>Bank {{ $rekening->bank }}</strong>
                                    </div>
                                    <div class="text-muted small">
                                        <div>No. Rek: 
                                            <strong style="cursor: pointer;" 
                                                    onclick="copyToClipboard('{{ $rekening->nomor_rekening }}', this)"
                                                    title="Klik untuk menyalin nomor rekening">
                                                {{ $rekening->nomor_rekening }}
                                            </strong>
                                            <i class="fas fa-copy ms-1 text-primary" style="font-size: 12px;"></i>
                                        </div>
                                        <div>A.n: <strong>{{ $rekening->nama_pemilik }}</strong></div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Informasi rekening belum tersedia. Silakan hubungi admin untuk informasi pembayaran.
                                    </div>
                                </div>
                                @endforelse
                            </div>
                            
                            @if($rekenings->count() > 0)
                            <div class="mt-3 p-3 bg-light rounded">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>Petunjuk Transfer:</strong>
                                    <ul class="mb-0 mt-1">
                                        <li>Klik pada nomor rekening untuk menyalin</li>
                                        <li>Transfer sesuai dengan jumlah yang tertera</li>
                                        <li>Simpan bukti transfer untuk diupload</li>
                                        <li>Pastikan nama pengirim sesuai dengan nama akun Anda</li>
                                    </ul>
                                </small>
                            </div>
                            @endif
                        </div>

                        <form action="{{ route('buku-kolaboratif.proses-pembayaran', $pesananBuku->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Hidden input untuk metode pembayaran -->
                            <input type="hidden" name="metode_pembayaran" value="transfer_bank">

                            <div class="mb-3">
                                <label for="bukti_pembayaran" class="form-label">Upload Bukti Transfer</label>
                                <input type="file" class="form-control @error('bukti_pembayaran') is-invalid @enderror"
                                    id="bukti_pembayaran" name="bukti_pembayaran" accept=".jpg,.jpeg,.png,.pdf" required>
                                <div class="form-text">Format: JPG, PNG, PDF. Maksimal 2MB.</div>
                                @error('bukti_pembayaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="bank_pengirim" class="form-label">Bank Pengirim</label>
                                <select class="form-select @error('bank_pengirim') is-invalid @enderror" id="bank_pengirim"
                                    name="bank_pengirim" required>
                                    <option value="">Pilih Bank Pengirim</option>
                                    <option value="BCA">BCA</option>
                                    <option value="Mandiri">Mandiri</option>
                                    <option value="BRI">BRI</option>
                                    <option value="BNI">BNI</option>
                                    <option value="CIMB">CIMB Niaga</option>
                                    <option value="Danamon">Danamon</option>
                                    <option value="Permata">Permata</option>
                                    <option value="BTN">BTN</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                @error('bank_pengirim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nama_pengirim" class="form-label">Nama Pengirim</label>
                                <input type="text" class="form-control @error('nama_pengirim') is-invalid @enderror"
                                    id="nama_pengirim" name="nama_pengirim" placeholder="Nama sesuai rekening pengirim"
                                    required>
                                @error('nama_pengirim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="jumlah_transfer" class="form-label">Jumlah Transfer</label>
                                <input type="number" class="form-control @error('jumlah_transfer') is-invalid @enderror"
                                    id="jumlah_transfer" name="jumlah_transfer" value="{{ $pesananBuku->jumlah_bayar }}"
                                    placeholder="Jumlah yang ditransfer" required readonly>
                                <div class="form-text">Pastikan jumlah transfer sesuai dengan total pembayaran</div>
                                @error('jumlah_transfer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-warning border-left">
                                <h6><i class="fas fa-clock me-2"></i>Penting!</h6>
                                <ul class="mb-0 small">
                                    <li>Transfer ke salah satu rekening di atas sesuai dengan jumlah yang tertera</li>
                                    <li>Upload bukti transfer yang jelas dan dapat dibaca</li>
                                    <li>Pastikan nama pengirim dan jumlah transfer sudah benar</li>
                                    <li>Verifikasi pembayaran akan dilakukan dalam 1x24 jam</li>
                                    <li>Anda akan mendapat notifikasi setelah pembayaran diverifikasi</li>
                                </ul>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('buku-kolaboratif.tampilkan', $pesananBuku->bukuKolaboratif->id) }}"
                                    class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-upload me-2"></i>Upload Bukti Transfer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar Informasi -->
            <div class="col-md-4">
                <!-- Panduan Pembayaran -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Panduan Transfer
                        </h6>
                    </div>
                    <div class="card-body">
                        <ol class="small mb-0">
                            <li class="mb-2">Transfer ke salah satu rekening di atas sesuai jumlah yang tertera</li>
                            <li class="mb-2">Simpan bukti transfer (screenshot/foto struk)</li>
                            <li class="mb-2">Upload bukti transfer melalui form di samping</li>
                            <li class="mb-2">Isi data bank pengirim dan nama pengirim dengan benar</li>
                            <li class="mb-2">Tunggu verifikasi dari admin (maks. 1x24 jam)</li>
                            <li class="mb-0">Setelah terverifikasi, Anda dapat mulai menulis</li>
                        </ol>
                    </div>
                </div>

                <!-- Ringkasan Pembayaran -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-calculator me-2"></i>Ringkasan Pembayaran
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Harga Bab:</span>
                            <span>Rp {{ number_format($pesananBuku->jumlah_bayar, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Biaya Admin:</span>
                            <span class="text-success">Gratis</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <strong class="text-primary">Rp
                                {{ number_format($pesananBuku->jumlah_bayar, 0, ',', '.') }}</strong>
                        </div>

                        @if(isset($pesananBuku->batas_pembayaran))
                            <div class="mt-3 text-center">
                                <small class="text-muted">Batas Pembayaran:</small><br>
                                <strong class="text-danger">{{ $pesananBuku->batas_pembayaran->format('d/m/Y H:i') }}
                                    WIB</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Bantuan -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-question-circle me-2"></i>Butuh Bantuan?
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <p class="text-muted small mb-3">Hubungi customer service kami jika ada kendala</p>
                        <div class="d-grid gap-2">
                            <a href="https://wa.me/6281324558686?text=Halo, saya butuh bantuan untuk pesanan {{ $pesananBuku->nomor_pesanan }}"
                                class="btn btn-success btn-sm" target="_blank">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp
                            </a>
                            <a href="mailto:admin@fanyapublishing.com?subject=Bantuan Pesanan {{ $pesananBuku->nomor_pesanan }}"
                                class="btn btn-primary btn-sm">
                                <i class="fas fa-envelope me-2"></i>Email Support
                            </a>
                            <a href="tel:+62 813-2455-8686" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-phone me-2"></i>Telepon
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .alert {
            border-left: 4px solid;
        }

        .alert-info {
            border-left-color: #17a2b8;
        }

        .alert-warning {
            border-left-color: #ffc107;
        }

        .btn:hover {
            transform: translateY(-1px);
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-1px);
            transition: transform 0.2s ease-in-out;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .copy-text {
            cursor: pointer;
            transition: color 0.2s ease-in-out;
        }

        .copy-text:hover {
            color: #007bff !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Form validation
            const form = document.querySelector('form[enctype="multipart/form-data"]');
            if (form) {
                form.addEventListener('submit', function (e) {
                    const fileInput = form.querySelector('input[type="file"]');
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const bankPengirim = form.querySelector('#bank_pengirim');
                    const namaPengirim = form.querySelector('#nama_pengirim');
                    const jumlahTransfer = form.querySelector('#jumlah_transfer');

                    // Validate required fields
                    if (!bankPengirim.value) {
                        e.preventDefault();
                        alert('Silakan pilih bank pengirim.');
                        bankPengirim.focus();
                        return false;
                    }

                    if (!namaPengirim.value.trim()) {
                        e.preventDefault();
                        alert('Silakan isi nama pengirim.');
                        namaPengirim.focus();
                        return false;
                    }

                    if (!jumlahTransfer.value) {
                        e.preventDefault();
                        alert('Jumlah transfer tidak boleh kosong.');
                        jumlahTransfer.focus();
                        return false;
                    }

                    if (fileInput && fileInput.files.length > 0) {
                        const file = fileInput.files[0];
                        const maxSize = 2 * 1024 * 1024; // 2MB
                        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];

                        // Validate file size
                        if (file.size > maxSize) {
                            e.preventDefault();
                            alert('Ukuran file terlalu besar. Maksimal 2MB.');
                            return false;
                        }

                        // Validate file type
                        if (!allowedTypes.includes(file.type)) {
                            e.preventDefault();
                            alert('Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau PDF.');
                            return false;
                        }

                        // Show loading state
                        if (submitBtn) {
                            const originalText = submitBtn.innerHTML;
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengupload...';

                            // Reset button if form submission fails
                            setTimeout(() => {
                                if (submitBtn.disabled) {
                                    submitBtn.disabled = false;
                                    submitBtn.innerHTML = originalText;
                                }
                            }, 10000);
                        }
                    } else if (fileInput) {
                        e.preventDefault();
                        alert('Silakan pilih file bukti transfer terlebih dahulu.');
                        return false;
                    }
                });
            }

            // Preview image before upload
            const fileInput = document.querySelector('input[type="file"]');
            if (fileInput) {
                fileInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            // Remove existing preview
                            const existingPreview = fileInput.parentNode.querySelector('.image-preview');
                            if (existingPreview) {
                                existingPreview.remove();
                            }

                            // Create new preview
                            const preview = document.createElement('div');
                            preview.className = 'image-preview mt-2';
                            preview.innerHTML = `
                            <img src="${e.target.result}" class="img-thumbnail" style="max-height: 200px;" alt="Preview">
                            <small class="text-muted d-block mt-1">Preview gambar yang akan diupload</small>
                        `;
                            fileInput.parentNode.appendChild(preview);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Auto-fill nama pengirim dengan nama user yang login (opsional)
            const namaPengirimInput = document.querySelector('#nama_pengirim');
            if (namaPengirimInput && !namaPengirimInput.value) {
                // Bisa diisi dengan nama user dari session jika diperlukan
                // namaPengirimInput.value = '{{ Auth::user()->name ?? "" }}';
            }

            // Format number input
            const jumlahTransferInput = document.querySelector('#jumlah_transfer');
            if (jumlahTransferInput) {
                jumlahTransferInput.addEventListener('input', function (e) {
                    // Remove non-numeric characters
                    let value = e.target.value.replace(/[^0-9]/g, '');
                    e.target.value = value;
                });
            }
        });

        // Copy rekening number to clipboard
        function copyToClipboard(text, element) {
            navigator.clipboard.writeText(text).then(function () {
                // Show success feedback
                const originalText = element.innerHTML;
                element.innerHTML = '<i class="fas fa-check text-success"></i> Tersalin!';
                element.classList.add('text-success');

                setTimeout(() => {
                    element.innerHTML = originalText;
                    element.classList.remove('text-success');
                }, 2000);
            }).catch(function (err) {
                console.error('Could not copy text: ', err);
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);

                const originalText = element.innerHTML;
                element.innerHTML = '<i class="fas fa-check text-success"></i> Tersalin!';
                setTimeout(() => {
                    element.innerHTML = originalText;
                }, 2000);
            });
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function () {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                const closeBtn = alert.querySelector('.btn-close');
                if (closeBtn && !alert.classList.contains('alert-warning')) {
                    closeBtn.click();
                }
            });
        }, 5000);

        // Countdown timer untuk batas pembayaran (jika ada)
        @if(isset($pesananBuku->batas_pembayaran))
            function updateCountdown() {
                const batasPembayaran = new Date('{{ $pesananBuku->batas_pembayaran->format("Y-m-d H:i:s") }}');
                const now = new Date();
                const timeLeft = batasPembayaran - now;

                if (timeLeft > 0) {
                    const hours = Math.floor(timeLeft / (1000 * 60 * 60));
                    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                    // Update countdown display jika ada element untuk itu
                    const countdownElement = document.querySelector('#countdown');
                    if (countdownElement) {
                        countdownElement.innerHTML = `${hours}j ${minutes}m ${seconds}d`;
                    }
                } else {
                    // Waktu habis
                    const countdownElement = document.querySelector('#countdown');
                    if (countdownElement) {
                        countdownElement.innerHTML = 'Waktu Habis';
                        countdownElement.classList.add('text-danger');
                    }
                }
            }

            // Update countdown setiap detik
            setInterval(updateCountdown, 1000);
            updateCountdown(); // Initial call
        @endif
    </script>
@endpush