@extends('admin.layouts.app')

@section('main')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Detail Naskah - {{ $penerbitan->nomor_pesanan }}</h1>
                <a href="{{ route('admin.naskah-individu.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>

            @if(session('success'))
                <div
                    class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 dark:bg-green-800 dark:text-green-100">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 dark:bg-red-800 dark:text-red-100">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informasi Pesanan -->
                <div
                    class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900 dark:to-indigo-900 p-6 rounded-lg border border-blue-200 dark:border-blue-700">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-blue-900 dark:text-blue-100">Informasi Pesanan</h3>
                    </div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-blue-600 dark:text-blue-400">Nomor Pesanan</dt>
                            <dd class="text-sm text-blue-900 dark:text-blue-100 font-mono">{{ $penerbitan->nomor_pesanan }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-blue-600 dark:text-blue-400">Pemesan</dt>
                            <dd class="text-sm text-blue-900 dark:text-blue-100">{{ $penerbitan->user->name }}</dd>
                            <dd class="text-xs text-blue-700 dark:text-blue-300">{{ $penerbitan->user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-blue-600 dark:text-blue-400">Paket</dt>
                            <dd>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $penerbitan->paket_badge }} text-white">
                                    {{ ucfirst($penerbitan->paket) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-blue-600 dark:text-blue-400">Harga Paket</dt>
                            <dd class="text-sm text-blue-900 dark:text-blue-100 font-semibold">
                                {{ $penerbitan->harga_paket_formatted }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-blue-600 dark:text-blue-400">Tanggal Pesanan</dt>
                            <dd class="text-sm text-blue-900 dark:text-blue-100">
                                {{ $penerbitan->tanggal_pesanan->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-blue-600 dark:text-blue-400">Status Pembayaran</dt>
                            <dd>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $penerbitan->status_pembayaran_badge }} text-white">
                                    {{ $penerbitan->status_pembayaran_text }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Informasi Naskah -->
                <div
                    class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900 dark:to-emerald-900 p-6 rounded-lg border border-green-200 dark:border-green-700">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-green-900 dark:text-green-100">Informasi Naskah</h3>
                    </div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-green-600 dark:text-green-400">Judul Buku</dt>
                            <dd class="text-sm text-green-900 dark:text-green-100 font-medium">
                                {{ $penerbitan->judul_buku ?? 'Belum ada judul' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-green-600 dark:text-green-400">Nama Penulis</dt>
                            <dd class="text-sm text-green-900 dark:text-green-100">
                                {{ $penerbitan->nama_penulis ?? 'Belum ada penulis' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-green-600 dark:text-green-400">Status Penerbitan</dt>
                            <dd>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $penerbitan->status_penerbitan_badge }} text-white status-badge">
                                    {{ $penerbitan->status_penerbitan_text }}
                                </span>
                            </dd>
                        </div>
                        @if($penerbitan->tanggal_upload_naskah)
                            <div>
                                <dt class="text-sm font-medium text-green-600 dark:text-green-400">Tanggal Upload</dt>
                                <dd class="text-sm text-green-900 dark:text-green-100">
                                    {{ $penerbitan->tanggal_upload_naskah->format('d/m/Y H:i') }}</dd>
                            </div>
                        @endif
                        @if($penerbitan->deskripsi_singkat)
                            <div>
                                <dt class="text-sm font-medium text-green-600 dark:text-green-400">Deskripsi</dt>
                                <dd class="text-sm text-green-900 dark:text-green-100 leading-relaxed">
                                    {{ Str::limit($penerbitan->deskripsi_singkat, 150) }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- File Naskah -->
            @if($penerbitan->file_naskah)
                <div class="mt-6">
                    <div
                        class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900 dark:to-pink-900 p-6 rounded-lg border border-purple-200 dark:border-purple-700">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-600 dark:text-purple-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="ml-3 text-lg font-semibold text-purple-900 dark:text-purple-100">File Naskah</h3>
                        </div>
                        <div
                            class="flex items-center justify-between bg-white dark:bg-gray-800 p-4 rounded-lg border border-purple-200 dark:border-purple-600">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    @php
                                        $fileExtension = pathinfo($penerbitan->file_naskah, PATHINFO_EXTENSION);
                                        $iconColor = match (strtolower($fileExtension)) {
                                            'pdf' => 'text-red-500',
                                            'doc', 'docx' => 'text-blue-500',
                                            default => 'text-gray-500'
                                        };
                                    @endphp
                                    <svg class="h-10 w-10 {{ $iconColor }}" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $penerbitan->judul_buku ?? 'Naskah' }}.{{ $fileExtension }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        File: {{ basename($penerbitan->file_naskah) }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('admin.naskah-individu.download', $penerbitan->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download
                            </a>
                        </div>
                    </div>
                                    </div>
            @endif

            <!-- Catatan Review -->
            @if($penerbitan->catatan_review)
                <div class="mt-6">
                    <div
                        class="bg-gradient-to-r from-orange-50 to-yellow-50 dark:from-orange-900 dark:to-yellow-900 p-6 rounded-lg border border-orange-200 dark:border-orange-700">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-orange-600 dark:text-orange-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <h3 class="ml-3 text-lg font-semibold text-orange-900 dark:text-orange-100">Catatan Review</h3>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-orange-200 dark:border-orange-600">
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                {{ $penerbitan->catatan_review }}</p>
                            @if($penerbitan->admin && $penerbitan->tanggal_review)
                                <div class="mt-3 pt-3 border-t border-orange-200 dark:border-orange-600">
                                    <p class="text-xs text-orange-600 dark:text-orange-400">
                                        Oleh {{ $penerbitan->admin->name }} • {{ $penerbitan->tanggal_review->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Timeline Status -->
            <div class="mt-6">
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Timeline Status</h3>
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            <!-- Pesanan Dibuat -->
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-600"
                                        aria-hidden="true"></span>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span
                                                class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-700">
                                                <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Pesanan dibuat</p>
                                            </div>
                                            <div
                                                class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                {{ $penerbitan->tanggal_pesanan->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <!-- Pembayaran Diverifikasi -->
                            @if($penerbitan->tanggal_verifikasi)
                                <li>
                                    <div class="relative pb-8">
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-600"
                                            aria-hidden="true"></span>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span
                                                    class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-700">
                                                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">Pembayaran diverifikasi
                                                    </p>
                                                    @if($penerbitan->admin)
                                                        <p class="text-xs text-gray-400 dark:text-gray-500">oleh
                                                            {{ $penerbitan->admin->name }}</p>
                                                    @endif
                                                </div>
                                                <div
                                                    class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                    {{ $penerbitan->tanggal_verifikasi->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            <!-- Naskah Diupload -->
                            @if($penerbitan->tanggal_upload_naskah)
                                <li>
                                    <div class="relative pb-8">
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-600"
                                            aria-hidden="true"></span>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span
                                                    class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-700">
                                                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">Naskah diupload</p>
                                                </div>
                                                <div
                                                    class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                    {{ $penerbitan->tanggal_upload_naskah->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            <!-- Status Review/Update -->
                            @if($penerbitan->tanggal_review)
                                <li>
                                    <div class="relative">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                @php
                                                    $reviewIconColor = match ($penerbitan->status_penerbitan) {
                                                        'sudah_kirim' => 'bg-blue-500',
                                                        'revisi' => 'bg-orange-500',
                                                        'disetujui' => 'bg-green-500',
                                                        'ditolak' => 'bg-red-500',
                                                        default => 'bg-gray-500'
                                                    };
                                                @endphp
                                                <span
                                                    class="h-8 w-8 rounded-full {{ $reviewIconColor }} flex items-center justify-center ring-8 ring-white dark:ring-gray-700">
                                                    @if($penerbitan->status_penerbitan === 'disetujui')
                                                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    @elseif($penerbitan->status_penerbitan === 'ditolak')
                                                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    @elseif($penerbitan->status_penerbitan === 'revisi')
                                                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    @else
                                                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                            <path fill-rule="evenodd"
                                                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    @endif
                                                </span>
                                            </div>
                                                                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $penerbitan->status_penerbitan_text }}
                                                        @if($penerbitan->admin)
                                                            oleh {{ $penerbitan->admin->name }}
                                                        @endif
                                                    </p>
                                                </div>
                                                <div
                                                    class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                    {{ $penerbitan->tanggal_review->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Update Status -->
            <div class="mt-6">
                <div
                    class="bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900 dark:to-blue-900 p-6 rounded-lg border border-indigo-200 dark:border-indigo-700">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-indigo-900 dark:text-indigo-100">Update Status Penerbitan
                        </h3>
                    </div>

                    <form action="{{ route('admin.naskah-individu.update-status', $penerbitan->id) }}" method="POST"
                        class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="status_penerbitan"
                                    class="block text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-2">
                                    Status Penerbitan
                                </label>
                                <select name="status_penerbitan" id="status_penerbitan" required
                                    class="w-full rounded-md border-indigo-300 dark:border-indigo-600 dark:bg-indigo-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                    <option value="">Pilih Status</option>
                                    <option value="sudah_kirim" {{ $penerbitan->status_penerbitan == 'sudah_kirim' ? 'selected' : '' }}>
                                        Sudah Kirim
                                    </option>
                                    <option value="revisi" {{ $penerbitan->status_penerbitan == 'revisi' ? 'selected' : '' }}>
                                        Revisi
                                    </option>
                                    <option value="disetujui" {{ $penerbitan->status_penerbitan == 'disetujui' ? 'selected' : '' }}>
                                        Disetujui
                                    </option>
                                    <option value="ditolak" {{ $penerbitan->status_penerbitan == 'ditolak' ? 'selected' : '' }}>
                                        Ditolak
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="catatan_review"
                                class="block text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-2">
                                Catatan Review
                            </label>
                            <textarea name="catatan_review" id="catatan_review" rows="4"
                                class="w-full rounded-md border-indigo-300 dark:border-indigo-600 dark:bg-indigo-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 transition-all"
                                placeholder="Tambahkan catatan review...">{{ old('catatan_review', $penerbitan->catatan_review) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Update Status
                            </button>
                        </div>
                    </form>

                    <!-- Quick Actions -->
                    <div class="mt-6 pt-6 border-t border-indigo-200 dark:border-indigo-600">
                        <h4 class="text-sm font-medium text-indigo-700 dark:text-indigo-300 mb-3">Quick Actions</h4>
                        <div class="flex flex-wrap gap-2">
                            @if($penerbitan->status_penerbitan === 'sudah_kirim')
                                <button onclick="quickUpdateStatus('disetujui')"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                    </svg>
                                    Setujui
                                </button>

                                <button onclick="quickUpdateStatus('revisi')"
                                    class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" />
                                    </svg>
                                    Minta Revisi
                                </button>

                                <button onclick="quickUpdateStatus('ditolak')"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                                    </svg>
                                    Tolak
                                </button>
                            @endif

                            @if($penerbitan->status_penerbitan === 'revisi')
                                <button onclick="quickUpdateStatus('disetujui')"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                    </svg>
                                    Setujui
                                </button>

                                <button onclick="quickUpdateStatus('ditolak')"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                                    </svg>
                                    Tolak
                                </button>
                            @endif

                            @if($penerbitan->file_naskah)
                                <a href="{{ route('admin.naskah-individu.download', $penerbitan->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Download Naskah
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Modal -->
    <div id="quickActionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4" id="quickActionIcon">
                    <!-- Icon will be set by JavaScript -->
                </div>
                             <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" id="quickActionTitle">Update Status</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400" id="quickActionMessage"></p>
                    <form id="quickActionForm" method="POST"
                        action="{{ route('admin.naskah-individu.update-status', $penerbitan->id) }}" class="mt-4">
                        @csrf
                        <input type="hidden" name="status_penerbitan" id="quickActionStatus">
                        <div class="mb-4">
                            <label for="quickActionCatatan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 text-left">
                                Catatan (Opsional)
                            </label>
                            <textarea name="catatan_review" id="quickActionCatatan" rows="3"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Tambahkan catatan..."></textarea>
                        </div>
                        <div class="flex justify-center space-x-3">
                            <button type="button" onclick="closeQuickActionModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Batal
                            </button>
                            <button type="submit" id="quickActionSubmit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Quick Action functionality
        function quickUpdateStatus(status) {
            const modal = document.getElementById('quickActionModal');
            const title = document.getElementById('quickActionTitle');
            const message = document.getElementById('quickActionMessage');
            const statusInput = document.getElementById('quickActionStatus');
            const submitBtn = document.getElementById('quickActionSubmit');
            const icon = document.getElementById('quickActionIcon');

            // Set status
            statusInput.value = status;

            // Configure modal based on status
            const statusConfig = {
                'sudah_kirim': {
                    title: 'Set ke Sudah Kirim',
                    message: 'Set status naskah {{ $penerbitan->nomor_pesanan }} ke "Sudah Kirim"?',
                    btnText: 'Set Status',
                    btnClass: 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
                    iconClass: 'bg-blue-100 dark:bg-blue-900',
                    iconColor: 'text-blue-600 dark:text-blue-400',
                    icon: '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>'
                },
                'revisi': {
                    title: 'Minta Revisi',
                    message: 'Minta revisi untuk naskah {{ $penerbitan->nomor_pesanan }}?',
                    btnText: 'Minta Revisi',
                    btnClass: 'bg-orange-600 hover:bg-orange-700 focus:ring-orange-500',
                    iconClass: 'bg-orange-100 dark:bg-orange-900',
                    iconColor: 'text-orange-600 dark:text-orange-400',
                    icon: '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" /></svg>'
                },
                'disetujui': {
                    title: 'Setujui Naskah',
                    message: 'Setujui naskah {{ $penerbitan->nomor_pesanan }}?',
                    btnText: 'Setujui',
                    btnClass: 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
                    iconClass: 'bg-green-100 dark:bg-green-900',
                    iconColor: 'text-green-600 dark:text-green-400',
                    icon: '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" /></svg>'
                },
                'ditolak': {
                    title: 'Tolak Naskah',
                    message: 'Tolak naskah {{ $penerbitan->nomor_pesanan }}?',
                    btnText: 'Tolak',
                    btnClass: 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
                    iconClass: 'bg-red-100 dark:bg-red-900',
                    iconColor: 'text-red-600 dark:text-red-400',
                    icon: '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" /></svg>'
                }
            };

            const config = statusConfig[status];
            title.textContent = config.title;
            message.textContent = config.message;
            submitBtn.textContent = config.btnText;
            submitBtn.className = `px-4 py-2 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 ${config.btnClass}`;
            icon.className = `mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4 ${config.iconClass}`;
            icon.innerHTML = `<div class="${config.iconColor}">${config.icon}</div>`;

            modal.classList.remove('hidden');
        }

        function closeQuickActionModal() {
            document.getElementById('quickActionModal').classList.add('hidden');
            document.getElementById('quickActionCatatan').value = '';
        }

        // Close modal when clicking outside
        window.onclick = function (event) {
            const modal = document.getElementById('quickActionModal');
            if (event.target === modal) {
                closeQuickActionModal();
            }
        }

        // Auto-update status select when form is used
        document.getElementById('status_penerbitan').addEventListener('change', function () {
            const selectedStatus = this.value;
            const catatanField = document.getElementById('catatan_review');

            // Add placeholder text based on status
            const placeholders = {
                'sudah_kirim': 'Naskah sudah dikirim untuk proses selanjutnya...',
                'revisi': 'Jelaskan bagian yang perlu direvisi...',
                'disetujui': 'Naskah telah disetujui untuk tahap selanjutnya...',
                'ditolak': 'Jelaskan alasan penolakan...'
            };

            if (placeholders[selectedStatus]) {
                catatanField.placeholder = placeholders[selectedStatus];
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function (e) {
            const status = document.getElementById('status_penerbitan').value;

            if (!status) {
                e.preventDefault();
                alert('Silakan pilih status penerbitan terlebih dahulu.');
                return false;
            }

            // Confirm for critical actions
            if (status === 'ditolak') {
                if (!confirm('Apakah Anda yakin ingin menolak naskah ini? Tindakan ini tidak dapat dibatalkan.')) {
                    e.preventDefault();
                    return false;
                }
            }

            if (status === 'disetujui') {
                if (!confirm('Apakah Anda yakin ingin menyetujui naskah ini?')) {
                    e.preventDefault();
                    return false;
                }
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function (e) {
            // ESC to close modal
            if (e.key === 'Escape') {
                closeQuickActionModal();
            }

            // Ctrl/Cmd + Enter to submit form
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                const activeForm = document.querySelector('form:not(#quickActionForm)');
                if (activeForm) {
                    activeForm.submit();
                }
            }
        });

        // Auto-save draft functionality (optional)
        let autoSaveTimeout;
        document.getElementById('catatan_review').addEventListener('input', function () {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(() => {
                // Save to localStorage as draft
                localStorage.setItem('naskah_catatan_draft_{{ $penerbitan->id }}', this.value);
            }, 1000);
        });

        // Load draft on page load
        document.addEventListener('DOMContentLoaded', function () {
            const draft = localStorage.getItem('naskah_catatan_draft_{{ $penerbitan->id }}');
            if (draft && !document.getElementById('catatan_review').value) {
                document.getElementById('catatan_review').value = draft;
            }
        });

        // Clear draft when form is submitted successfully
        @if(session('success'))
            localStorage.removeItem('naskah_catatan_draft_{{ $penerbitan->id }}');
        @endif
    </script>

    <style>
        /* Custom scrollbar for textarea */
        textarea::-webkit-scrollbar {
            width: 8px;
        }

        textarea::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        textarea::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        textarea::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Dark mode scrollbar */
        .dark textarea::-webkit-scrollbar-track {
            background: #374151;
        }

        .dark textarea::-webkit-scrollbar-thumb {
            background: #6b7280;
        }

        .dark textarea::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s ease;
        }

        /* Focus states */
        select:focus,
        textarea:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Button hover effects */
        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Modal animation */
        #quickActionModal {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Status badge pulse animation */
        .status-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.8;
            }
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
@endsection
