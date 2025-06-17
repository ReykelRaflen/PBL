@extends('admin.layouts.app')

@section('title', 'Detail Pembayaran')

@section('main')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Detail Pembayaran
                    </h1>
                    <p class="text-gray-600 mt-2">{{ $pembayaran->invoice_number }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('pembayaran.index') }}" 
                        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    @if($pembayaran->bukti_pembayaran)
                        <a href="{{ route('pembayaran.downloadBukti', $pembayaran) }}" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Bukti
                        </a>
                    @endif
                    <a href="{{ route('pembayaran.generateInvoice', $pembayaran) }}" target="_blank"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H9.5a2 2 0 01-2-2V5a2 2 0 012-2H14"></path>
                        </svg>
                        Generate Invoice
                    </a>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Payment Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Informasi Pembayaran
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Invoice Number</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ $pembayaran->invoice_number }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                                    <div>
                                        @if($pembayaran->status === 'menunggu_verifikasi')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Menunggu Verifikasi
                                            </span>
                                        @elseif($pembayaran->status === 'terverifikasi')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Terverifikasi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Ditolak
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Metode Pembayaran</label>
                                    <p class="text-gray-900">{{ $pembayaran->metode_pembayaran }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Jumlah Transfer</label>
                                    <p class="text-lg font-semibold text-green-600">Rp {{ number_format($pembayaran->jumlah_transfer, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Bank Pengirim</label>
                                    <p class="text-gray-900">{{ $pembayaran->bank_pengirim }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Nama Pengirim</label>
                                    <p class="text-gray-900">{{ $pembayaran->nama_pengirim }}</p>
                                </div>
                                @if($pembayaran->nomor_rekening_pengirim)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">Nomor Rekening Pengirim</label>
                                        <p class="text-gray-900">{{ $pembayaran->nomor_rekening_pengirim }}</p>
                                    </div>
                                @endif
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Upload</label>
                                    <p class="text-gray-900">{{ $pembayaran->tanggal_pembayaran->format('d F Y, H:i') }} WIB</p>
                                </div>
                                @if($pembayaran->tanggal_verifikasi)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Verifikasi</label>
                                        <p class="text-gray-900">{{ $pembayaran->tanggal_verifikasi->format('d F Y, H:i') }} WIB</p>
                                    </div>
                                @endif
                                @if($pembayaran->verifiedBy)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">Diverifikasi Oleh</label>
                                        <p class="text-gray-900">{{ $pembayaran->verifiedBy->name }}</p>
                                    </div>
                                @endif
                            </div>

                            @if($pembayaran->keterangan)
                                <div class="mt-6">
                                    <label class="block text-sm font-medium text-gray-500 mb-2">Keterangan dari Customer</label>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-gray-900">{{ $pembayaran->keterangan }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($pembayaran->catatan_admin)
                                <div class="mt-6">
                                    <label class="block text-sm font-medium text-gray-500 mb-2">Catatan Admin</label>
                                    <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-400">
                                        <p class="text-blue-900">{{ $pembayaran->catatan_admin }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                Informasi Pesanan
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start space-x-4">
                                @if($pembayaran->pesanan->buku->cover)
                                    <img src="{{ asset('storage/' . $pembayaran->pesanan->buku->cover) }}" 
                                         alt="{{ $pembayaran->pesanan->buku->judul_buku }}"
                                         class="w-20 h-28 object-cover rounded-lg shadow-sm">
                                @else
                                    <div class="w-20 h-28 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $pembayaran->pesanan->buku->judul_buku }}</h3>
                                    <p class="text-gray-600 mb-2">{{ $pembayaran->pesanan->buku->penulis }}</p>
                                    
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-500">Order Number:</span>
                                            <span class="font-medium text-gray-900">{{ $pembayaran->pesanan->order_number }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Tipe:</span>
                                            @if($pembayaran->pesanan->tipe_buku === 'fisik')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                    </svg>
                                                    Buku Fisik
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                    </svg>
                                                    E-book
                                                </span>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Quantity:</span>
                                            <span class="font-medium text-gray-900">{{ $pembayaran->pesanan->quantity }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Total:</span>
                                            <span class="font-semibold text-green-600">Rp {{ number_format($pembayaran->pesanan->total, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    @if($pembayaran->pesanan->alamat_pengiriman)
                                        <div class="mt-4">
                                            <span class="text-gray-500 text-sm">Alamat Pengiriman:</span>
                                            <p class="text-gray-900 text-sm mt-1">{{ $pembayaran->pesanan->alamat_pengiriman }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Informasi Customer
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Nama</label>
                                    <p class="text-gray-900">{{ $pembayaran->pesanan->user->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                                    <p class="text-gray-900">{{ $pembayaran->pesanan->user->email }}</p>
                                </div>
                                @if($pembayaran->pesanan->no_telepon)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">No. Telepon</label>
                                        <p class="text-gray-900">{{ $pembayaran->pesanan->no_telepon }}</p>
                                    </div>
                                @endif
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Bergabung</label>
                                    <p class="text-gray-900">{{ $pembayaran->pesanan->user->created_at->format('d F Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bukti Pembayaran -->
                    @if($pembayaran->bukti_pembayaran)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Bukti Pembayaran
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" 
                                         alt="Bukti Pembayaran"
                                         class="max-w-full h-auto rounded-lg shadow-lg cursor-pointer hover:shadow-xl transition-shadow duration-200"
                                         onclick="openImageModal(this.src)">
                                    <p class="text-sm text-gray-500 mt-2">Klik gambar untuk memperbesar</p>
                                </div>
                            </div>
                                                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    @if($pembayaran->status === 'menunggu_verifikasi')
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <button onclick="quickApprove()" 
                                        class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg flex items-center justify-center transition duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Setujui Pembayaran
                                </button>
                                <button onclick="showRejectModal()" 
                                        class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg flex items-center justify-center transition duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Tolak Pembayaran
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Status Update Form -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Update Status</h3>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('pembayaran.updateStatus', $pembayaran) }}" method="POST" id="statusForm">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                        <select name="status" id="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="menunggu_verifikasi" {{ $pembayaran->status === 'menunggu_verifikasi' ? 'selected' : '' }}>
                                                Menunggu Verifikasi
                                            </option>
                                            <option value="terverifikasi" {{ $pembayaran->status === 'terverifikasi' ? 'selected' : '' }}>
                                                Terverifikasi
                                            </option>
                                            <option value="ditolak" {{ $pembayaran->status === 'ditolak' ? 'selected' : '' }}>
                                                Ditolak
                                            </option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="catatan_admin" class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin</label>
                                        <textarea name="catatan_admin" id="catatan_admin" rows="3" 
                                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                  placeholder="Tambahkan catatan (opsional)">{{ $pembayaran->catatan_admin }}</textarea>
                                    </div>
                                    <button type="submit" 
                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                        </svg>
                                        Update Status
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Payment Timeline -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Timeline</h3>
                        </div>
                        <div class="p-6">
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    <!-- Order Created -->
                                    <li>
                                        <div class="relative pb-8">
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-900">Pesanan dibuat</p>
                                                        <p class="text-xs text-gray-500">{{ $pembayaran->pesanan->order_number }}</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        {{ $pembayaran->pesanan->tanggal_pesanan->format('d M Y, H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <!-- Payment Uploaded -->
                                    <li>
                                        <div class="relative pb-8">
                                            @if($pembayaran->status !== 'menunggu_verifikasi')
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-900">Bukti pembayaran diupload</p>
                                                        <p class="text-xs text-gray-500">{{ $pembayaran->nama_pengirim }} - {{ $pembayaran->bank_pengirim }}</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        {{ $pembayaran->tanggal_pembayaran->format('d M Y, H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <!-- Payment Verification -->
                                    @if($pembayaran->tanggal_verifikasi)
                                        <li>
                                            <div class="relative">
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        @if($pembayaran->status === 'terverifikasi')
                                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                            </span>
                                                        @else
                                                            <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-900">
                                                                Pembayaran {{ $pembayaran->status === 'terverifikasi' ? 'disetujui' : 'ditolak' }}
                                                            </p>
                                                            @if($pembayaran->verifiedBy)
                                                                <p class="text-xs text-gray-500">oleh {{ $pembayaran->verifiedBy->name }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                            {{ $pembayaran->tanggal_verifikasi->format('d M Y, H:i') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @else
                                        <li>
                                            <div class="relative">
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white animate-pulse">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5">
                                                        <p class="text-sm text-gray-900">Menunggu verifikasi admin</p>
                                                        <p class="text-xs text-gray-500">Silakan verifikasi pembayaran</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Related Orders -->
                    @php
                        $relatedOrders = \App\Models\Pesanan::where('user_id', $pembayaran->pesanan->user_id)
                                                           ->where('id', '!=', $pembayaran->pesanan->id)
                                                           ->with('buku')
                                                           ->orderBy('created_at', 'desc')
                                                           ->limit(5)
                                                           ->get();
                    @endphp

                    @if($relatedOrders->count() > 0)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Pesanan Lainnya</h3>
                                <p class="text-sm text-gray-500">dari customer yang sama</p>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3">
                                    @foreach($relatedOrders as $order)
                                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                            @if($order->buku->cover)
                                                                                            <img src="{{ asset('storage/' . $order->buku->cover) }}" 
                                                     alt="{{ $order->buku->judul_buku }}"
                                                     class="w-10 h-12 object-cover rounded">
                                            @else
                                                <div class="w-10 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $order->order_number }}</p>
                                                <p class="text-xs text-gray-500 truncate">{{ Str::limit($order->buku->judul_buku, 25) }}</p>
                                                <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y') }}</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                    {{ $order->status === 'selesai' ? 'bg-green-100 text-green-800' : 
                                                       ($order->status === 'menunggu_pembayaran' ? 'bg-yellow-100 text-yellow-800' : 
                                                        'bg-blue-100 text-blue-800') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Tolak Pembayaran</h3>
                    <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-800">
                                Anda akan menolak pembayaran ini. Pastikan alasan penolakan jelas untuk customer.
                            </p>
                        </div>
                    </div>
                </div>
                <form id="rejectForm">
                    @csrf
                    <div class="mb-4">
                        <label for="rejectReason" class="block text-sm font-medium text-gray-700 mb-2">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="rejectReason" name="catatan_admin" rows="4" required
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                  placeholder="Jelaskan alasan penolakan pembayaran..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeRejectModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200">
                            Tolak Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative max-w-4xl max-h-full">
                <button onclick="closeImageModal()" 
                        class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <img id="modalImage" src="" alt="Bukti Pembayaran" class="max-w-full max-h-full rounded-lg">
            </div>
        </div>
    </div>


@push('styles')
    <style>
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }

        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        .hover\:shadow-xl:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .ring-8 {
            --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
            --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(8px + var(--tw-ring-offset-width)) var(--tw-ring-color);
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        }

        .ring-white {
            --tw-ring-color: #fff;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Quick Approve Function
        function quickApprove() {
            if (confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("pembayaran.updateStatus", $pembayaran) }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'status';
                statusInput.value = 'terverifikasi';
                
                const noteInput = document.createElement('input');
                noteInput.type = 'hidden';
                noteInput.name = 'catatan_admin';
                noteInput.value = 'Pembayaran disetujui melalui quick action';
                
                form.appendChild(csrfToken);
                form.appendChild(statusInput);
                form.appendChild(noteInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Show Reject Modal
        function showRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectReason').focus();
        }

        // Close Reject Modal
        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectForm').reset();
        }

        // Handle Reject Form
        document.getElementById('rejectForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const reason = document.getElementById('rejectReason').value.trim();
            if (!reason) {
                alert('Alasan penolakan harus diisi');
                return;
            }

            if (confirm('Apakah Anda yakin ingin menolak pembayaran ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("pembayaran.updateStatus", $pembayaran) }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'status';
                statusInput.value = 'ditolak';
                
                const noteInput = document.createElement('input');
                noteInput.type = 'hidden';
                noteInput.name = 'catatan_admin';
                noteInput.value = reason;
                
                form.appendChild(csrfToken);
                form.appendChild(statusInput);
                form.appendChild(noteInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        });

        // Image Modal Functions
        function openImageModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modals when clicking outside
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });

        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Escape key to close modals
            if (e.key === 'Escape') {
                closeRejectModal();
                closeImageModal();
            }
            
            // Quick actions with keyboard shortcuts
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case 'Enter':
                        @if($pembayaran->status === 'menunggu_verifikasi')
                            e.preventDefault();
                            quickApprove();
                        @endif
                        break;
                    case 'Delete':
                        @if($pembayaran->status === 'menunggu_verifikasi')
                            e.preventDefault();
                            showRejectModal();
                        @endif
                        break;
                }
            }
        });

        // Form validation
        document.getElementById('statusForm').addEventListener('submit', function(e) {
            const status = document.getElementById('status').value;
            const note = document.getElementById('catatan_admin').value.trim();
            
            if (status === 'ditolak' && !note) {
                e.preventDefault();
                alert('Catatan admin wajib diisi ketika menolak pembayaran');
                document.getElementById('catatan_admin').focus();
                return false;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c 0 4.418 10 8 8 8 8-3.582 8-8-8zm8-2A7.962 7.962 0 0120 12h4c0 6.627-5.373 12-12 12v-4c3.582 0 6.418-2.686 7.291-6z"></path>
                </svg>
                Memproses...
            `;
            
            // Reset button after timeout (fallback)
            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            }, 10000);
        });

        // Auto-refresh for pending payments
        @if($pembayaran->status === 'menunggu_verifikasi')
            let autoRefreshInterval = setInterval(() => {
                if (!document.hidden) {
                    fetch(window.location.href, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        // Check if status has changed
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const currentStatus = doc.querySelector('[name="status"]').value;
                        
                        if (currentStatus !== 'menunggu_verifikasi') {
                            clearInterval(autoRefreshInterval);
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.log('Auto refresh error:', error);
                    });
                }
            }, 30000); // Check every 30 seconds

            // Clear interval when page becomes hidden
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    clearInterval(autoRefreshInterval);
                }
            });

            // Clear interval before page unload
            window.addEventListener('beforeunload', () => {
                clearInterval(autoRefreshInterval);
            });
        @endif

        // Smooth scroll for anchor links
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

        // Copy to clipboard functionality
        function copyToClipboard(text, element) {
            navigator.clipboard.writeText(text).then(() => {
                // Show success feedback
                const originalText = element.textContent;
                element.textContent = 'Tersalin!';
                element.classList.add('text-green-600');
                
                setTimeout(() => {
                    element.textContent = originalText;
                    element.classList.remove('text-green-600');
                }, 2000);
            }).catch(err => {
                console.error('Could not copy text: ', err);
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                const originalText = element.textContent;
                element.textContent = 'Tersalin!';
                setTimeout(() => {
                    element.textContent = originalText;
                }, 2000);
            });
        }

        // Add click to copy for invoice number
        document.addEventListener('DOMContentLoaded', function() {
            const invoiceElements = document.querySelectorAll('p:contains("{{ $pembayaran->invoice_number }}")');
            invoiceElements.forEach(element => {
                if (element.textContent.includes('{{ $pembayaran->invoice_number }}')) {
                    element.style.cursor = 'pointer';
                    element.title = 'Klik untuk menyalin';
                    element.addEventListener('click', function() {
                        copyToClipboard('{{ $pembayaran->invoice_number }}', this);
                    });
                }
            });
        });

        // Print functionality
        function printPage() {
            window.print();
        }

        // Add print styles
        const printStyles = `
            @media print {
                .no-print {
                    display: none !important;
                }
                
                .print-break {
                    page-break-before: always;
                }
                
                body {
                    font-size: 12pt;
                    line-height: 1.4;
                }
                
                .bg-white {
                    background: white !important;
                }
                
                .shadow-sm, .shadow-lg {
                    box-shadow: none !important;
                }
                
                .border {
                    border: 1px solid #000 !important;
                }
            }
        `;

        const styleSheet = document.createElement('style');
        styleSheet.textContent = printStyles;
        document.head.appendChild(styleSheet);

        // Notification system
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
            
            const bgColor = {
                'success': 'bg-green-500',
                'error': 'bg-red-500',
                'warning': 'bg-yellow-500',
                'info': 'bg-blue-500'
            }[type] || 'bg-blue-500';
            
            notification.classList.add(bgColor, 'text-white');
            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="mr-2">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }

        // Show success message if exists
        @if(session('success'))
            showNotification('{{ session('success') }}', 'success');
        @endif

        @if(session('error'))
            showNotification('{{ session('error') }}', 'error');
        @endif
    </script>
@endpush
@endsection



