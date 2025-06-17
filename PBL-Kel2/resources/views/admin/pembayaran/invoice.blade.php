<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $pembayaran->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background: #f8f9fa;
        }

        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .invoice-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 300;
        }

        .invoice-header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .invoice-body {
            padding: 30px;
        }

        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .invoice-info > div {
            flex: 1;
            min-width: 250px;
            margin-bottom: 20px;
        }

        .invoice-info h3 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 16px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 5px;
        }

        .invoice-info p {
            margin-bottom: 5px;
        }

        .invoice-info strong {
            color: #333;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .invoice-table th {
            background: #667eea;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .invoice-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .invoice-table tr:last-child td {
            border-bottom: none;
        }

        .invoice-table tr:nth-child(even) {
            background: #f8f9fa;
        }

        .book-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .book-cover {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .book-cover-placeholder {
            width: 50px;
            height: 70px;
            background: #e9ecef;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }

        .book-details h4 {
            margin-bottom: 5px;
            color: #333;
            font-size: 16px;
        }

        .book-details p {
            color: #666;
            font-size: 14px;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-verified {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .invoice-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
        }

        .summary-row.total {
            border-top: 2px solid #667eea;
            padding-top: 15px;
            margin-top: 15px;
            font-weight: bold;
            font-size: 18px;
            color: #667eea;
        }

        .payment-proof {
            text-align: center;
            margin: 30px 0;
        }

        .payment-proof img {
            max-width: 100%;
            max-height: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .invoice-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }

        .invoice-footer p {
            margin-bottom: 10px;
            color: #666;
        }

        .company-info {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }

        .company-info h4 {
            color: #667eea;
            margin-bottom: 10px;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }

        .print-button:hover {
            background: #5a67d8;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        @media print {
            body {
                background: white;
            }
            
            .invoice-container {
                box-shadow: none;
                margin: 0;
                border-radius: 0;
            }
            
            .print-button {
                display: none;
            }
            
            .invoice-header {
                background: #667eea !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            
            .invoice-table th {
                background: #667eea !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }

        @media (max-width: 768px) {
            .invoice-container {
                margin: 10px;
                border-radius: 0;
            }
            
            .invoice-header {
                padding: 20px;
            }
            
            .invoice-body {
                padding: 20px;
            }
            
            .invoice-info {
                flex-direction: column;
            }
            
            .invoice-info > div {
                min-width: auto;
            }
            
            .book-info {
                flex-direction: column;
                text-align: center;
            }
            
            .summary-row {
                font-size: 14px;
            }
            
            .print-button {
                position: static;
                width: 100%;
                margin: 10px;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        <svg style="width: 16px; height: 16px; margin-right: 8px; display: inline-block; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
        </svg>
        Print Invoice
    </button>

    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <h1>INVOICE</h1>
            <p>{{ $pembayaran->invoice_number }}</p>
        </div>

        <!-- Body -->
        <div class="invoice-body">
            <!-- Invoice Info -->
            <div class="invoice-info">
                <div>
                    <h3>Informasi Perusahaan</h3>
                    <p><strong>PT. Fanya Bookstore</strong></p>
                    <p>Jl. Contoh No. 123</p>
                    <p>Jakarta Selatan, 12345</p>
                    <p>Telp: (021) 1234-5678</p>
                    <p>Email: info@fanyabookstore.com</p>
                </div>
                
                <div>
                    <h3>Informasi Customer</h3>
                    <p><strong>{{ $pembayaran->pesanan->user->name }}</strong></p>
                    <p>{{ $pembayaran->pesanan->user->email }}</p>
                    @if($pembayaran->pesanan->no_telepon)
                        <p>{{ $pembayaran->pesanan->no_telepon }}</p>
                    @endif
                    @if($pembayaran->pesanan->alamat_pengiriman)
                        <p style="margin-top: 10px;">
                            <strong>Alamat Pengiriman:</strong><br>
                            {{ $pembayaran->pesanan->alamat_pengiriman }}
                        </p>
                    @endif
                </div>
                
                <div>
                    <h3>Detail Invoice</h3>
                    <p><strong>Invoice:</strong> {{ $pembayaran->invoice_number }}</p>
                    <p><strong>Order:</strong> {{ $pembayaran->pesanan->order_number }}</p>
                    <p><strong>Tanggal Pesanan:</strong> {{ $pembayaran->pesanan->tanggal_pesanan->format('d F Y') }}</p>
                    <p><strong>Tanggal Pembayaran:</strong> {{ $pembayaran->tanggal_pembayaran->format('d F Y') }}</p>
                    @if($pembayaran->tanggal_verifikasi)
                        <p><strong>Tanggal Verifikasi:</strong> {{ $pembayaran->tanggal_verifikasi->format('d F Y') }}</p>
                    @endif
                    <p style="margin-top: 10px;">
                        <strong>Status:</strong>
                        @if($pembayaran->status === 'terverifikasi')
                            <span class="status-badge status-verified">Terverifikasi</span>
                        @elseif($pembayaran->status === 'menunggu_verifikasi')
                            <span class="status-badge status-pending">Menunggu Verifikasi</span>
                        @else
                            <span class="status-badge status-rejected">Ditolak</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Items Table -->
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Tipe</th>
                        <th>Qty</th>
                        <th>Harga Satuan</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="book-info">
                                @if($pembayaran->pesanan->buku->cover)
                                    <img src="{{ asset('storage/' . $pembayaran->pesanan->buku->cover) }}" 
                                         alt="{{ $pembayaran->pesanan->buku->judul_buku }}"
                                         class="book-cover">
                                @else
                                    <div class="book-cover-placeholder">
                                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="book-details">
                                    <h4>{{ $pembayaran->pesanan->buku->judul_buku }}</h4>
                                    <p>{{ $pembayaran->pesanan->buku->penulis }}</p>
                                    @if($pembayaran->pesanan->buku->penerbit)
                                        <p>{{ $pembayaran->pesanan->buku->penerbit }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($pembayaran->pesanan->tipe_buku === 'fisik')
                                <span class="status-badge" style="background: #cce5ff; color: #0066cc;">Buku Fisik</span>
                            @else
                                <span class="status-badge" style="background: #ccf2cc; color: #006600;">E-book</span>
                            @endif
                        </td>
                        <td>{{ $pembayaran->pesanan->quantity }}</td>
                        <td>Rp {{ number_format($pembayaran->pesanan->harga_satuan, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($pembayaran->pesanan->subtotal ?? ($pembayaran->pesanan->harga_satuan * $pembayaran->pesanan->quantity), 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Summary -->
            <div class="invoice-summary">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($pembayaran->pesanan->subtotal ?? ($pembayaran->pesanan->harga_satuan * $pembayaran->pesanan->quantity), 0, ',', '.') }}</span>
                </div>
                
                @if($pembayaran->pesanan->diskon > 0)
                    <div class="summary-row" style="color: #28a745;">
                        <span>
                            Diskon
                            @if($pembayaran->pesanan->kode_promo)
                                ({{ $pembayaran->pesanan->kode_promo }})
                            @endif
                            :
                        </span>
                        <span>-Rp {{ number_format($pembayaran->pesanan->diskon, 0, ',', '.') }}</span>
                    </div>
                @endif
                
                @if($pembayaran->pesanan->tipe_buku === 'fisik' && isset($pembayaran->pesanan->ongkir) && $pembayaran->pesanan->ongkir > 0)
                    <div class="summary-row">
                        <span>Ongkos Kirim:</span>
                        <span>Rp {{ number_format($pembayaran->pesanan->ongkir, 0, ',', '.') }}</span>
                    </div>
                @endif
                
                <div class="summary-row total">
                    <span>Total Pembayaran:</span>
                    <span>Rp {{ number_format($pembayaran->pesanan->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Payment Information -->
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 30px 0;">
                <h3 style="color: #667eea; margin-bottom: 15px;">Informasi Pembayaran</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div>
                        <p><strong>Metode Pembayaran:</strong> {{ $pembayaran->metode_pembayaran }}</p>
                        <p><strong>Bank Pengirim:</strong> {{ $pembayaran->bank_pengirim }}</p>
                        <p><strong>Nama Pengirim:</strong> {{ $pembayaran->nama_pengirim }}</p>
                        @if($pembayaran->nomor_rekening_pengirim)
                            <p><strong>No. Rekening:</strong> {{ $pembayaran->nomor_rekening_pengirim }}</p>
                        @endif
                    </div>
                    <div>
                        <p><strong>Jumlah Transfer:</strong> Rp {{ number_format($pembayaran->jumlah_transfer, 0, ',', '.') }}</p>
                        <p><strong>Tanggal Transfer:</strong> {{ $pembayaran->tanggal_pembayaran->format('d F Y, H:i') }} WIB</p>
                        @if($pembayaran->tanggal_verifikasi)
                            <p><strong>Diverifikasi:</strong> {{ $pembayaran->tanggal_verifikasi->format('d F Y, H:i') }} WIB</p>
                        @endif
                        @if($pembayaran->verifiedBy)
                            <p><strong>Diverifikasi oleh:</strong> {{ $pembayaran->verifiedBy->name }}</p>
                        @endif
                    </div>
                </div>
                
                @if($pembayaran->keterangan)
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #dee2e6;">
                        <p><strong>Keterangan Customer:</strong></p>
                        <p style="font-style: italic; color: #666;">{{ $pembayaran->keterangan }}</p>
                    </div>
                @endif
                
                @if($pembayaran->catatan_admin)
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #dee2e6;">
                        <p><strong>Catatan Admin:</strong></p>
                        <p style="font-style: italic; color: #666;">{{ $pembayaran->catatan_admin }}</p>
                    </div>
                @endif
            </div>

            <!-- Payment Proof -->
            @if($pembayaran->bukti_pembayaran)
                <div class="payment-proof">
                    <h3 style="color: #667eea; margin-bottom: 15px;">Bukti Pembayaran</h3>
                    <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" 
                         alt="Bukti Pembayaran"
                         style="max-width: 100%; max-height: 400px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                </div>
            @endif

            <!-- Notes -->
            @if($pembayaran->pesanan->catatan)
                <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 8px; margin: 20px 0;">
                    <h4 style="color: #856404; margin-bottom: 10px;">Catatan Pesanan:</h4>
                    <p style="color: #856404; margin: 0;">{{ $pembayaran->pesanan->catatan }}</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="company-info">
                <h4>PT. Fanya Bookstore</h4>
                <p>Terima kasih atas kepercayaan Anda berbelanja di toko kami.</p>
                <p>Untuk pertanyaan lebih lanjut, hubungi customer service kami.</p>
                <p style="margin-top: 15px;">
                    <strong>Email:</strong> support@fanyabookstore.com | 
                    <strong>Telp:</strong> (021) 1234-5678 | 
                    <strong>WhatsApp:</strong> +62 812-3456-7890
                </p>
            </div>
            
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #dee2e6; font-size: 12px; color: #666;">
                <p>Invoice ini digenerate secara otomatis pada {{ now()->format('d F Y, H:i') }} WIB</p>
                <p>Dokumen ini sah tanpa tanda tangan dan stempel</p>
            </div>
        </div>
    </div>

    <script>
        // Auto print when opened in new window
        if (window.location.search.includes('auto_print=1')) {
            window.onload = function() {
                setTimeout(() => {
                    window.print();
                }, 1000);
            };
        }

        // Keyboard shortcut for print
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                               window.print();
            }
        });

        // Print button functionality
        function printInvoice() {
            window.print();
        }

        // Download as PDF functionality (if needed)
        function downloadPDF() {
            // This would require additional PDF generation library
            // For now, we'll use the browser's print to PDF feature
            window.print();
        }

        // Copy invoice number to clipboard
        function copyInvoiceNumber() {
            const invoiceNumber = '{{ $pembayaran->invoice_number }}';
            navigator.clipboard.writeText(invoiceNumber).then(function() {
                alert('Nomor invoice berhasil disalin: ' + invoiceNumber);
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
            });
        }

        // Add click event to invoice number for copying
        document.addEventListener('DOMContentLoaded', function() {
            const invoiceElements = document.querySelectorAll('.invoice-header p');
            invoiceElements.forEach(element => {
                if (element.textContent.includes('{{ $pembayaran->invoice_number }}')) {
                    element.style.cursor = 'pointer';
                    element.title = 'Klik untuk menyalin nomor invoice';
                    element.addEventListener('click', copyInvoiceNumber);
                }
            });
        });
    </script>
</body>
</html>

