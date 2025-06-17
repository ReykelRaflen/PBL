@extends('user.layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Buku - {{ $buku->judul_buku }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Pesan Buku</h1>
                <p class="text-gray-600">Lengkapi form di bawah untuk memesan buku</p>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Detail Buku -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Detail Buku</h2>
                    
                    <div class="flex gap-4 mb-4">
                        @if($buku->cover)
                            <img src="{{ asset('storage/' . $buku->cover) }}" alt="{{ $buku->judul_buku }}" class="w-24 h-32 object-cover rounded">
                        @else
                            <div class="w-24 h-32 bg-gray-200 rounded flex items-center justify-center">
                                <span class="text-gray-500 text-xs">No Cover</span>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg">{{ $buku->judul_buku }}</h3>
                            <p class="text-gray-600">{{ $buku->penulis }}</p>
                            <p class="text-sm text-gray-500">{{ $buku->penerbit }} ({{ $buku->tahun_terbit }})</p>
                            @if($buku->kategori)
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mt-2">
                                    {{ $buku->kategori->nama }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Informasi Harga Berdasarkan Tipe -->
                    <div class="border-t pt-4">
                        @if($tipeBuku === 'fisik')
                            <!-- Info Buku Fisik -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-book text-blue-600 mr-2"></i>
                                    <h4 class="font-semibold text-blue-800">Buku Fisik</h4>
                                </div>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium">Harga:</span>
                                        <p class="text-lg font-bold text-blue-600">
                                            @if($buku->harga_promo && $buku->harga_promo < $buku->harga)
                                                <span class="text-gray-500 line-through text-sm">Rp {{ number_format($buku->harga, 0, ',', '.') }}</span><br>
                                                Rp {{ number_format($buku->harga_promo, 0, ',', '.') }}
                                                <span class="bg-red-500 text-white text-xs px-1 py-0.5 rounded ml-1">
                                                    {{ round((($buku->harga - $buku->harga_promo) / $buku->harga) * 100) }}% OFF
                                                </span>
                                            @else
                                                Rp {{ number_format($buku->harga, 0, ',', '.') }}
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <span class="font-medium">Stok:</span>
                                        <p class="text-lg font-bold {{ $buku->stok > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $buku->stok }} tersedia
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2 text-xs text-gray-600">
                                    <i class="fas fa-truck mr-1"></i>
                                    Akan dikirim ke alamat yang Anda berikan
                                </div>
                            </div>
                        @else
                            <!-- Info E-book -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-tablet-alt text-green-600 mr-2"></i>
                                    <h4 class="font-semibold text-green-800">E-book</h4>
                                </div>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium">Harga:</span>
                                        <p class="text-lg font-bold text-green-600">
                                            @if($buku->harga_ebook)
                                                @if($buku->harga_ebook_promo && $buku->harga_ebook_promo < $buku->harga_ebook)
                                                    <span class="text-gray-500 line-through text-sm">Rp {{ number_format($buku->harga_ebook, 0, ',', '.') }}</span><br>
                                                    Rp {{ number_format($buku->harga_ebook_promo, 0, ',', '.') }}
                                                    <span class="bg-red-500 text-white text-xs px-1 py-0.5 rounded ml-1">
                                                        {{ round((($buku->harga_ebook - $buku->harga_ebook_promo) / $buku->harga_ebook) * 100) }}% OFF
                                                    </span>
                                                @else
                                                    Rp {{ number_format($buku->harga_ebook, 0, ',', '.') }}
                                                @endif
                                            @else
                                                @php
                                                    $hargaEbook = ($buku->harga_promo ?? $buku->harga) * 0.6;
                                                @endphp
                                                Rp {{ number_format($hargaEbook, 0, ',', '.') }}
                                                <span class="bg-green-500 text-white text-xs px-1 py-0.5 rounded ml-1">40% OFF</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <span class="font-medium">Format:</span>
                                        <p class="text-lg font-bold text-green-600">Digital PDF</p>
                                    </div>
                                </div>
                                <div class="mt-2 text-xs text-gray-600">
                                    <i class="fas fa-download mr-1"></i>
                                    Download langsung setelah pembayaran terverifikasi
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Form Pemesanan -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Form Pemesanan</h2>
                    
                    <form action="{{ route('user.pesanan.store') }}" method="POST" x-data="pesananForm()">
                        @csrf
                        <input type="hidden" name="buku_id" value="{{ $buku->id }}">
                        <input type="hidden" name="tipe_buku" value="{{ $tipeBuku }}">

                        <!-- Tipe Buku (Read-only display) -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Buku</label>
                            <div class="p-3 border rounded-lg {{ $tipeBuku === 'fisik' ? 'border-blue-500 bg-blue-50' : 'border-green-500 bg-green-50' }}">
                                <div class="flex items-center">
                                    @if($tipeBuku === 'fisik')
                                        <i class="fas fa-book text-blue-600 mr-2"></i>
                                        <div>
                                            <div class="font-medium text-blue-800">Buku Fisik</div>
                                            <div class="text-sm text-blue-600">
                                                @if($buku->harga_promo && $buku->harga_promo < $buku->harga)
                                                    Rp {{ number_format($buku->harga_promo, 0, ',', '.') }}
                                                @else
                                                    Rp {{ number_format($buku->harga, 0, ',', '.') }}
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <i class="fas fa-tablet-alt text-green-600 mr-2"></i>
                                        <div>
                                            <div class="font-medium text-green-800">E-book</div>
                                            <div class="text-sm text-green-600">
                                                @if($buku->harga_ebook)
                                                    @if($buku->harga_ebook_promo && $buku->harga_ebook_promo < $buku->harga_ebook)
                                                        Rp {{ number_format($buku->harga_ebook_promo, 0, ',', '.') }}
                                                    @else
                                                        Rp {{ number_format($buku->harga_ebook, 0, ',', '.') }}
                                                    @endif
                                                @else
                                                    @php
                                                        $hargaEbook = ($buku->harga_promo ?? $buku->harga) * 0.6;
                                                    @endphp
                                                    Rp {{ number_format($hargaEbook, 0, ',', '.') }}
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Link untuk ganti tipe -->
                            <div class="mt-2 text-center">
                                <a href="{{ route('books.show', $buku->id) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-arrow-left mr-1"></i>Pilih tipe buku lain
                                </a>
                            </div>
                        </div>

                        <!-- Quantity (hanya untuk buku fisik) -->
                        @if($tipeBuku === 'fisik')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="decreaseQuantity()" class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center hover:bg-gray-50 disabled:opacity-50" :disabled="quantity <= 1">-</button>
                                <input type="number" name="quantity" 
                                       x-model="quantity" 
                                       value="{{ $quantity }}"
                                       min="1" max="{{ $buku->stok }}" 
                                       class="w-20 text-center border border-gray-300 rounded px-2 py-1">
                                <button type="button" @click="increaseQuantity()" class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center hover:bg-gray-50 disabled:opacity-50" :disabled="quantity >= {{ $buku->stok }}">+</button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Maksimal: {{ $buku->stok }} buku</p>
                            @error('quantity')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        @else
                        <input type="hidden" name="quantity" value="1">
                        @endif

                        <!-- Alamat Pengiriman (hanya untuk buku fisik) -->
                        @if($tipeBuku === 'fisik')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Pengiriman <span class="text-red-500">*</span></label>
                            <textarea name="alamat_pengiriman" rows="3" 
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                      placeholder="Masukkan alamat lengkap untuk pengiriman"
                                      required>{{ old('alamat_pengiriman') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Pastikan alamat lengkap dan benar untuk pengiriman</p>
                            @error('alamat_pengiriman')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif

                        <!-- No Telepon -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon <span class="text-red-500">*</span></label>
                            <input type="text" name="no_telepon" 
                                   value="{{ old('no_telepon', Auth::user()->phone ?? '') }}" 
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="08xxxxxxxxxx" required>
                            <p class="text-xs text-gray-500 mt-1">Nomor telepon untuk konfirmasi pesanan</p>
                            @error('no_telepon')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email (Auto-filled) -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" 
                                   value="{{ Auth::user()->email }}" 
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-50" 
                                   readonly>
                            <p class="text-xs text-gray-500 mt-1">Email untuk notifikasi pesanan</p>
                        </div>

                        <!-- Kode Promo -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kode Promo (Opsional)</label>
                            <div class="flex gap-2">
                                <input type="text" name="kode_promo" 
                                       x-model="kodePromo"
                                       value="{{ old('kode_promo') }}" 
                                       class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                       placeholder="Masukkan kode promo">
                                <button type="button" @click="checkPromo()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Cek
                                </button>
                            </div>
                            <div x-show="promoMessage" x-text="promoMessage" :class="promoValid ? 'text-green-600' : 'text-red-600'" class="text-xs mt-1"></div>
                            @error('kode_promo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea name="catatan" rows="2" 
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                      placeholder="Catatan tambahan untuk pesanan">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ringkasan Harga -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <h3 class="font-medium mb-3">Ringkasan Pesanan</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span>Tipe Buku:</span>
                                    <span class="font-medium">
                                        @if($tipeBuku === 'fisik')
                                            <i class="fas fa-book text-blue-600 mr-1"></i>Buku Fisik
                                        @else
                                            <i class="fas fa-tablet-alt text-green-600 mr-1"></i>E-book
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Harga Satuan:</span>
                                    <span x-text="formatRupiah(hargaSatuan)"></span>
                                </div>
                                @if($tipeBuku === 'fisik')
                                <div class="flex justify-between">
                                    <span>Jumlah:</span>
                                    <span x-text="quantity + ' item'"></span>
                                </div>
                                @endif
                                <div x-show="diskonPromo > 0" class="flex justify-between text-green-600">
                                    <span>Diskon Promo:</span>
                                    <span x-text="'-' + formatRupiah(diskonPromo)"></span>
                                </div>
                                <div class="flex justify-between font-medium text-lg border-t pt-2">
                                    <span>Total:</span>
                                    <span x-text="formatRupiah(totalHarga)" class="text-green-600"></span>
                                </div>
                            </div>
                            
                            <!-- Informasi Pengiriman/Download -->
                            <div class="mt-3 p-3 bg-blue-50 rounded border border-blue-200">
                                @if($tipeBuku === 'fisik')
                                    <div class="flex items-start">
                                        <i class="fas fa-truck text-blue-600 mt-1 mr-2"></i>
                                        <div class="text-xs text-blue-800">
                                            <strong>Pengiriman:</strong><br>
                                            • Estimasi 3-5 hari kerja<br>
                                            • Gratis ongkir untuk pembelian di atas Rp 100.000<br>
                                            • Barang akan dikemas dengan aman
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-start">
                                        <i class="fas fa-download text-green-600 mt-1 mr-2"></i>
                                        <div class="text-xs text-green-800">
                                            <strong>E-book:</strong><br>
                                            • Download langsung setelah pembayaran terverifikasi<br>
                                            • Format PDF berkualitas tinggi<br>
                                            • Dapat dibaca di semua device
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex gap-3">
                            <a href="{{ route('books.show', $buku->id) }}" 
                               class="flex-1 bg-gray-500 text-white py-3 px-4 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 font-medium text-center transition duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            <button type="submit" 
                                    class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium transition duration-200"
                                    :disabled="isSubmitting"
                                    x-text="isSubmitting ? 'Memproses...' : 'Pesan Sekarang'">
                            </button>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mt-4 text-xs text-gray-500 text-center">
                            Dengan memesan, Anda menyetujui 
                            <a href="#" class="text-blue-600 hover:text-blue-800">Syarat & Ketentuan</a> 
                            dan 
                            <a href="#" class="text-blue-600 hover:text-blue-800">Kebijakan Privasi</a> kami.
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
function pesananForm() {
    return {
        tipeBuku: '{{ $tipeBuku }}',
        quantity: {{ $quantity }},
        kodePromo: '',
        promoMessage: '',
        promoValid: false,
        diskonPromo: 0,
        isSubmitting: false,
        activePromos: [],
        isLoadingPromo: false,
        
        // Harga berdasarkan tipe buku
        @if($tipeBuku === 'fisik')
            hargaFisik: {{ $buku->harga_promo ?? $buku->harga }},
            hargaEbook: 0,
        @else
            hargaFisik: 0,
            @if($buku->harga_ebook)
                hargaEbook: {{ $buku->harga_ebook_promo ?? $buku->harga_ebook }},
            @else
                hargaEbook: {{ ($buku->harga_promo ?? $buku->harga) * 0.6 }},
            @endif
        @endif
        
        stokFisik: {{ $buku->stok }},
        
        init() {
            this.loadActivePromos();
        },

        get hargaSatuan() {
            return this.tipeBuku === 'ebook' ? this.hargaEbook : this.hargaFisik;
        },

        get subtotal() {
            return this.hargaSatuan * (this.tipeBuku === 'fisik' ? this.quantity : 1);
        },

        get totalHarga() {
            return Math.max(0, this.subtotal - this.diskonPromo);
        },

        increaseQuantity() {
            if (this.quantity < this.stokFisik) {
                this.quantity++;
                // Reset promo jika quantity berubah
                if (this.promoValid) {
                    this.checkPromo();
                }
            }
        },

        decreaseQuantity() {
            if (this.quantity > 1) {
                this.quantity--;
                // Reset promo jika quantity berubah
                if (this.promoValid) {
                    this.checkPromo();
                }
            }
        },

        async loadActivePromos() {
            try {
                const response = await fetch('/api/active-promos');
                const data = await response.json();
                
                if (data.success) {
                    this.activePromos = data.promos;
                }
            } catch (error) {
                console.error('Error loading promos:', error);
            }
        },

        async checkPromo() {
            if (!this.kodePromo.trim()) {
                this.promoMessage = 'Masukkan kode promo terlebih dahulu';
                this.promoValid = false;
                this.diskonPromo = 0;
                return;
            }

            this.isLoadingPromo = true;
            this.promoMessage = 'Memeriksa kode promo...';
            this.promoValid = false;
            this.diskonPromo = 0;

            try {
                const response = await fetch('/api/check-promo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        kode_promo: this.kodePromo.trim(),
                        buku_id: {{ $buku->id }},
                        tipe_buku: this.tipeBuku,
                        subtotal: this.subtotal
                    })
                });

                const data = await response.json();

                if (response.ok && data.valid) {
                    this.promoValid = true;
                    this.diskonPromo = data.diskon;
                    this.promoMessage = `${data.promo.keterangan} - Hemat ${this.formatRupiah(data.diskon)}`;
                    
                    // Show success animation
                    this.animateSuccess();
                } else {
                    this.promoValid = false;
                    this.diskonPromo = 0;
                    this.promoMessage = data.message || 'Kode promo tidak valid';
                }

            } catch (error) {
                console.error('Error checking promo:', error);
                this.promoValid = false;
                this.diskonPromo = 0;
                this.promoMessage = 'Terjadi kesalahan saat memeriksa promo. Silakan coba lagi.';
            } finally {
                this.isLoadingPromo = false;
            }
        },

        applyPromo(kodePromo) {
            this.kodePromo = kodePromo;
            this.checkPromo();
        },

        clearPromo() {
            this.kodePromo = '';
            this.promoMessage = '';
            this.promoValid = false;
            this.diskonPromo = 0;
        },

        animateSuccess() {
            // Animate the total price
            const totalElement = document.querySelector('.total-price');
            if (totalElement) {
                totalElement.classList.add('animate-pulse');
                setTimeout(() => {
                    totalElement.classList.remove('animate-pulse');
                }, 1000);
            }
        },

        formatRupiah(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(amount));
        },

        formatPromoDescription(promo) {
            if (promo.tipe === 'Persentase') {
                return `Diskon ${promo.besaran}%`;
            } else {
                return `Potongan ${this.formatRupiah(promo.besaran)}`;
            }
        }
    }
}
</script>


    <!-- Add meta tag for CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Add some custom styles for better UX -->
    <style>
        .validation-feedback {
            transition: all 0.3s ease;
        }
        
        button:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .quantity-button:hover:not(:disabled) {
            background-color: #f3f4f6;
            transform: scale(1.05);
        }
        
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .price-highlight {
            animation: pulse 0.5s ease-in-out;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .notification-enter {
            animation: slideInRight 0.3s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Loading spinner */
        .spinner {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .grid {
                grid-template-columns: 1fr;
            }
            
            .flex {
                flex-direction: column;
            }
            
            .flex button,
            .flex a {
                margin-bottom: 0.5rem;
            }
        }
    </style>
</body>
</html>
@endsection