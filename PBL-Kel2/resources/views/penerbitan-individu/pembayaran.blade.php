@extends('user.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-credit-card me-2"></i>
                            Pembayaran - {{ $penerbitan->nomor_pesanan }}
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

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row">
                            <!-- Detail Pesanan -->
                            <div class="col-md-6 mb-4">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-shopping-cart me-2"></i>
                                            Detail Pesanan
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless mb-0">
                                            <tr>
                                                <td class="fw-bold">No. Pesanan</td>
                                                <td>:</td>
                                                <td>
                                                    <code class="bg-light text-dark px-2 py-1 rounded">
                                                        {{ $penerbitan->nomor_pesanan }}
                                                    </code>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Paket</td>
                                                <td>:</td>
                                                <td>
                                                    @php
                                                        $paketIcon = match ($penerbitan->paket) {
                                                            'silver' => 'fas fa-medal text-secondary',
                                                            'gold' => 'fas fa-trophy text-warning',
                                                            'diamond' => 'fas fa-gem text-info',
                                                            default => 'fas fa-box'
                                                        };
                                                        $paketBadge = match ($penerbitan->paket) {
                                                            'silver' => 'bg-secondary',
                                                            'gold' => 'bg-warning text-dark',
                                                            'diamond' => 'bg-info',
                                                            default => 'bg-secondary'
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $paketBadge }} px-3 py-2">
                                                        <i class="{{ $paketIcon }} me-1"></i>
                                                        {{ ucfirst($penerbitan->paket) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Total Bayar</td>
                                                <td>:</td>
                                                <td>
                                                    <h5 class="text-primary mb-0">
                                                        <strong>Rp
                                                            {{ number_format($penerbitan->harga_paket, 0, ',', '.') }}</strong>
                                                    </h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Metode Pembayaran</td>
                                                <td>:</td>
                                                <td>
                                                    <span class="badge bg-primary px-3 py-2">
                                                        <i class="fas fa-university me-1"></i>
                                                        Transfer Bank
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Pembayaran -->
                            <div class="col-md-6 mb-4">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-university me-2"></i>
                                            Informasi Transfer Bank
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @if($rekenings && $rekenings->count() > 0)
                                            <div class="row">
                                                @foreach($rekenings as $rekening)
                                                    <div class="col-12 mb-3">
                                                        <div class="p-3 border rounded bg-light text-center">
                                                            @php
                                                                $bankColor = match (strtolower($rekening->bank)) {
                                                                    'bca' => 'text-primary',
                                                                    'mandiri' => 'text-warning',
                                                                    'bni' => 'text-success',
                                                                    'bri' => 'text-info',
                                                                    'btn' => 'text-secondary',
                                                                    default => 'text-dark'
                                                                };
                                                            @endphp
                                                            <i class="fas fa-university {{ $bankColor }} mb-2"
                                                                style="font-size: 1.5rem;"></i>
                                                            <h6 class="fw-bold mb-1">Bank {{ strtoupper($rekening->bank) }}</h6>
                                                            <p class="mb-1 fs-5">
                                                                <strong>{{ $rekening->nomor_rekening }}</strong>
                                                                <button class="btn btn-sm btn-outline-secondary ms-2"
                                                                    onclick="copyToClipboard('{{ $rekening->nomor_rekening }}')"
                                                                    title="Copy nomor rekening">
                                                                    <i class="fas fa-copy"></i>
                                                                </button>
                                                            </p>
                                                            <small class="text-muted">a.n. {{ $rekening->nama_pemilik }}</small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-warning text-center">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Belum ada rekening yang tersedia. Silakan hubungi admin.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Petunjuk Pembayaran -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <div class="row align-items-center">
                                        <div class="col-md-1 text-center">
                                            <i class="fas fa-info-circle text-info" style="font-size: 2rem;"></i>
                                        </div>
                                        <div class="col-md-11">
                                            <h6 class="mb-2"><strong>Petunjuk Pembayaran:</strong></h6>
                                            <ol class="mb-0">
                                                <li>Transfer <strong>tepat</strong> sesuai nominal: <strong
                                                        class="text-primary">Rp
                                                        {{ number_format($penerbitan->harga_paket, 0, ',', '.') }}</strong>
                                                </li>
                                                <li>Pilih salah satu rekening bank di atas</li>
                                                <li>Simpan bukti transfer (screenshot/foto struk)</li>
                                                <li>Upload bukti pembayaran pada form di bawah</li>
                                                <li>Tunggu verifikasi admin maksimal 1x24 jam</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Upload Bukti -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning text-dark">
                                        <h5 class="mb-0">
                                            <i class="fas fa-upload me-2"></i>
                                            Upload Bukti Pembayaran
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('penerbitan-individu.proses-pembayaran', $penerbitan->id) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf

                                            <!-- Hidden input untuk metode pembayaran -->
                                            <input type="hidden" name="metode_pembayaran" value="transfer_bank">

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="bank_pengirim" class="form-label fw-bold">
                                                        <i class="fas fa-university me-1"></i>
                                                        Bank Pengirim
                                                    </label>
                                                    <select class="form-select @error('bank_pengirim') is-invalid @enderror"
                                                        name="bank_pengirim" id="bank_pengirim" required>
                                                        <option value="">Pilih Bank Pengirim</option>
                                                        <option value="BCA" {{ old('bank_pengirim') == 'BCA' ? 'selected' : '' }}>BCA</option>
                                                        <option value="Mandiri" {{ old('bank_pengirim') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                                        <option value="BNI" {{ old('bank_pengirim') == 'BNI' ? 'selected' : '' }}>BNI</option>
                                                        <option value="BRI" {{ old('bank_pengirim') == 'BRI' ? 'selected' : '' }}>BRI</option>
                                                        <option value="BTN" {{ old('bank_pengirim') == 'BTN' ? 'selected' : '' }}>BTN</option>
                                                        <option value="CIMB" {{ old('bank_pengirim') == 'CIMB' ? 'selected' : '' }}>CIMB Niaga</option>
                                                        <option value="Danamon" {{ old('bank_pengirim') == 'Danamon' ? 'selected' : '' }}>Danamon</option>
                                                        <option value="Lainnya" {{ old('bank_pengirim') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                                    </select>
                                                    @error('bank_pengirim')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="bank_tujuan" class="form-label fw-bold">
                                                        <i class="fas fa-bullseye me-1"></i>
                                                        Bank Tujuan Transfer
                                                    </label>
                                                    <select class="form-select @error('bank_tujuan') is-invalid @enderror"
                                                        name="bank_tujuan" id="bank_tujuan" required>
                                                        <option value="">Pilih Bank Tujuan</option>
                                                        @if($rekenings && $rekenings->count() > 0)
                                                            @foreach($rekenings as $rekening)
                                                                <option
                                                                    value="{{ $rekening->bank }} - {{ $rekening->nomor_rekening }}"
                                                                    {{ old('bank_tujuan') == $rekening->bank . ' - ' . $rekening->nomor_rekening ? 'selected' : '' }}>
                                                                    {{ strtoupper($rekening->bank) }} -
                                                                    {{ $rekening->nomor_rekening }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @error('bank_tujuan')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="bukti_pembayaran" class="form-label fw-bold">
                                                    <i class="fas fa-file-image me-1"></i>
                                                    Upload Bukti Pembayaran
                                                </label>
                                                <input type="file"
                                                    class="form-control @error('bukti_pembayaran') is-invalid @enderror"
                                                    name="bukti_pembayaran" id="bukti_pembayaran"
                                                    accept=".jpg,.jpeg,.png,.pdf" required>
                                                <div class="form-text">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Format: JPG, JPEG, PNG, PDF. Maksimal 2MB.
                                                    <strong>Pastikan bukti transfer terlihat jelas!</strong>
                                                </div>
                                                @error('bukti_pembayaran')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="catatan_pembayaran" class="form-label fw-bold">
                                                    <i class="fas fa-sticky-note me-1"></i>
                                                    Catatan Tambahan (Opsional)
                                                </label>
                                                <textarea
                                                    class="form-control @error('catatan_pembayaran') is-invalid @enderror"
                                                    name="catatan_pembayaran" id="catatan_pembayaran" rows="3"
                                                    placeholder="Contoh: Transfer dari rekening atas nama John Doe, tanggal 15 Januari 2024">{{ old('catatan_pembayaran') }}</textarea>
                                                @error('catatan_pembayaran')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('penerbitan-individu.index') }}"
                                                        class="btn btn-secondary btn-lg">
                                                        <i class="fas fa-arrow-left me-2"></i>
                                                        Kembali
                                                    </a>

                                                    <!-- Button Batalkan Pesanan -->
                                                    <button type="button" class="btn btn-danger btn-lg"
                                                        data-bs-toggle="modal" data-bs-target="#batalkanPesananModal">
                                                        <i class="fas fa-times me-2"></i>
                                                        Batalkan Pesanan
                                                    </button>
                                                </div>

                                                <button type="submit" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-upload me-2"></i>
                                                    Upload Bukti Pembayaran
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline Status -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-secondary">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-timeline me-2"></i>
                                            Timeline Proses Pembayaran
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 text-center mb-3">
                                                <div
                                                    class="timeline-step {{ $penerbitan->status_pembayaran == 'menunggu' ? 'active' : 'completed' }}">
                                                    <div class="timeline-icon">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </div>
                                                    <h6 class="mt-2">Pesanan Dibuat</h6>
                                                    <small
                                                        class="text-muted">{{ $penerbitan->created_at->format('d/m/Y H:i') }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-center mb-3">
                                                <div
                                                    class="timeline-step {{ $penerbitan->status_pembayaran == 'menunggu' ? 'active' : ($penerbitan->status_pembayaran == 'pending' ? 'active' : 'pending') }}">
                                                    <div class="timeline-icon">
                                                        <i class="fas fa-upload"></i>
                                                    </div>
                                                    <h6 class="mt-2">Upload Bukti</h6>
                                                    <small class="text-muted">
                                                        @if($penerbitan->tanggal_bayar)
                                                            {{ $penerbitan->tanggal_bayar->format('d/m/Y H:i') }}
                                                        @else
                                                            Menunggu upload
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-center mb-3">
                                                <div
                                                    class="timeline-step {{ $penerbitan->status_pembayaran == 'pending' ? 'active' : ($penerbitan->status_pembayaran == 'lunas' ? 'completed' : 'pending') }}">
                                                    <div class="timeline-icon">
                                                        <i class="fas fa-search"></i>
                                                    </div>
                                                    <h6 class="mt-2">Verifikasi Admin</h6>
                                                    <small class="text-muted">
                                                        @if($penerbitan->status_pembayaran == 'pending')
                                                            Sedang diverifikasi
                                                        @elseif($penerbitan->tanggal_verifikasi)
                                                            {{ $penerbitan->tanggal_verifikasi->format('d/m/Y H:i') }}
                                                        @else
                                                            Menunggu verifikasi
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-center mb-3">
                                                <div
                                                    class="timeline-step {{ $penerbitan->status_pembayaran == 'lunas' ? 'completed' : 'pending' }}">
                                                    <div class="timeline-icon">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                    <h6 class="mt-2">Pembayaran Lunas</h6>
                                                    <small class="text-muted">
                                                        @if($penerbitan->status_pembayaran == 'lunas')
                                                            Pembayaran berhasil
                                                        @else
                                                            Menunggu konfirmasi
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Batalkan Pesanan -->
    <div class="modal fade" id="batalkanPesananModal" tabindex="-1" aria-labelledby="batalkanPesananModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="batalkanPesananModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Konfirmasi Pembatalan Pesanan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="text-center mb-3">Apakah Anda yakin ingin membatalkan pesanan ini?</h6>
                    <div class="alert alert-warning">
                        <strong>Perhatian:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Pesanan dengan nomor <strong>{{ $penerbitan->nomor_pesanan }}</strong> akan dibatalkan</li>
                            <li>Data yang sudah diinput akan hilang</li>
                            <li>Jika sudah melakukan pembayaran, silakan hubungi admin untuk pengembalian dana</li>
                            <li>Tindakan ini tidak dapat dibatalkan</li>
                        </ul>
                    </div>

                    <form id="batalkanPesananForm" method="POST"
                        action="{{ route('penerbitan-individu.batalkan', $penerbitan->id) }}">
                        @csrf
                        @method('DELETE')

                        <div class="mb-3">
                            <label for="alasan_pembatalan" class="form-label fw-bold">
                                <i class="fas fa-comment me-1"></i>
                                Alasan Pembatalan (Opsional)
                            </label>
                            <textarea class="form-control" name="alasan_pembatalan" id="alasan_pembatalan" rows="3"
                                placeholder="Masukkan alasan pembatalan pesanan..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Batal
                    </button>
                    <button type="button" class="btn btn-danger" onclick="submitBatalkanPesanan()">
                        <i class="fas fa-trash me-2"></i>
                        Ya, Batalkan Pesanan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Timeline Styles */
        .timeline-step {
            position: relative;
        }

        .timeline-step.active .timeline-icon {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .timeline-step.completed .timeline-icon {
            background-color: #28a745;
            color: white;
            border-color: #28a745;
        }

        .timeline-step.pending .timeline-icon {
            background-color: #f8f9fa;
            color: #6c757d;
            border-color: #dee2e6;
        }

        .timeline-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 3px solid;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .timeline-step h6 {
            font-weight: 600;
            margin-top: 10px;
        }

        /* Card hover effects */
        .card {
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        /* File input styling */
        .form-control[type="file"] {
            padding: 10px;
            border: 2px dashed #dee2e6;
            background-color: #f8f9fa;
        }

        .form-control[type="file"]:focus {
            border-color: #007bff;
            background-color: #e3f2fd;
        }

        /* Bank selection styling */
        .form-select {
            padding: 10px 15px;
        }

        /* Button styling */
        .btn-lg {
            padding: 12px 30px;
            font-weight: 600;
        }

        /* Copy button styling */
        .btn-outline-secondary {
            border-width: 1px;
            padding: 2px 8px;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        /* Modal styling */
        .modal-content {
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .modal-header.bg-danger {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .timeline-step {
                margin-bottom: 20px;
            }

            .timeline-icon {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .card-body {
                padding: 15px;
            }

            .btn-lg {
                padding: 10px 20px;
                font-size: 0.9rem;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 10px;
            }

            .d-flex.gap-2 {
                justify-content: center;
            }
        }

        /* Alert styling */
        .alert-info {
            border-left: 4px solid #17a2b8;
        }

        .alert-warning {
            border-left: 4px solid #ffc107;
        }

        /* Code styling */
        code {
            font-size: 0.9rem;
            padding: 4px 8px;
        }

        /* Badge styling */
        .badge {
            font-size: 0.8rem;
            padding: 6px 12px;
        }

        /* Copy notification */
        .copy-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }

        .copy-notification.show {
            transform: translateX(0);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Preview file yang diupload
            const fileInput = document.getElementById('bukti_pembayaran');
            const fileLabel = document.querySelector('label[for="bukti_pembayaran"]');

            fileInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const fileName = file.name;
                    const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB

                    // Update label dengan info file
                    fileLabel.innerHTML = `
                    <i class="fas fa-file-image me-1"></i>
                    File Terpilih: ${fileName} (${fileSize} MB)
                `;

                    // Validasi ukuran file
                    if (file.size > 2 * 1024 * 1024) { // 2MB
                        alert('Ukuran file terlalu besar! Maksimal 2MB.');
                        fileInput.value = '';
                        fileLabel.innerHTML = `
                        <i class="fas fa-file-image me-1"></i>
                        Upload Bukti Pembayaran
                    `;
                    }
                }
            });

            // Auto-select bank tujuan berdasarkan bank pengirim (untuk kemudahan)
            const bankPengirim = document.getElementById('bank_pengirim');
            const bankTujuan = document.getElementById('bank_tujuan');

            bankPengirim.addEventListener('change', function () {
                const selectedBank = this.value.toLowerCase();
                const options = bankTujuan.querySelectorAll('option');

                // Reset selection
                bankTujuan.value = '';


                // Auto select matching bank
                options.forEach(option => {
                    if (option.value.toLowerCase().includes(selectedBank) && selectedBank !== '') {
                        bankTujuan.value = option.value;
                    }
                });
            });
        });

        // Function untuk copy nomor rekening
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function () {
                showCopyNotification('Nomor rekening berhasil disalin!');
            }).catch(function (err) {
                // Fallback untuk browser yang tidak support clipboard API
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showCopyNotification('Nomor rekening berhasil disalin!');
            });
        }

        // Function untuk menampilkan notifikasi copy
        function showCopyNotification(message) {
            // Remove existing notification if any
            const existingNotification = document.querySelector('.copy-notification');
            if (existingNotification) {
                existingNotification.remove();
            }

            // Create new notification
            const notification = document.createElement('div');
            notification.className = 'copy-notification';
            notification.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            ${message}
        `;

            document.body.appendChild(notification);

            // Show notification
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);

            // Hide notification after 3 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }

        // Function untuk submit form batalkan pesanan
        function submitBatalkanPesanan() {
            const form = document.getElementById('batalkanPesananForm');
            const submitBtn = event.target;

            // Disable button dan ubah text
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Membatalkan...';

            // Submit form
            form.submit();
        }

        // Konfirmasi sebelum meninggalkan halaman jika ada perubahan
        let formChanged = false;
        const formInputs = document.querySelectorAll('input, select, textarea');

        formInputs.forEach(input => {
            input.addEventListener('change', function () {
                formChanged = true;
            });
        });

        window.addEventListener('beforeunload', function (e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Reset form changed flag ketika form disubmit
        document.querySelector('form[action*="proses-pembayaran"]').addEventListener('submit', function () {
            formChanged = false;
        });
    </script>
@endsection