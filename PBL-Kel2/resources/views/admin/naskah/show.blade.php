@extends('admin.layouts.app')

@section('title', 'Detail Naskah')

@section('main')
<div class="container mx-auto px-4 py-6">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 relative" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 relative" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ session('error') }}
            </div>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4">
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900 mb-3">{{ $naskah->judul }}</h1>
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $naskah->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($naskah->status == 'sedang_direview' ? 'bg-blue-100 text-blue-800' : ($naskah->status == 'disetujui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    @if($naskah->status == 'pending')
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    @elseif($naskah->status == 'sedang_direview')
                                        <path fill-rule="evenodd" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" clip-rule="evenodd"></path>
                                    @elseif($naskah->status == 'disetujui')
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    @else
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    @endif
                                </svg>
                                {{ $naskah->getStatusText() }}
                            </span>
                            @if($naskah->isSegera())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Segera Berakhir
                                </span>
                            @endif
                            <span class="text-sm text-gray-500">ID: #{{ $naskah->id }}</span>
                        </div>
                        <div class="flex items-center text-gray-600 text-sm">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Dikirim {{ $naskah->created_at->format('d M Y H:i') }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $naskah->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if($naskah->status == 'pending' || $naskah->status == 'sedang_direview')
                            <div class="flex gap-2">
                                <button type="button" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors" onclick="openModal('setujuiModal')">
                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Setujui
                                </button>
                                <button type="button" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors" onclick="openModal('tolakModal')">
                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Tolak
                                </button>
                                <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors" onclick="updateStatus('sedang_direview')">
                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Review
                                </button>
                            </div>
                        @endif
                        <div class="flex gap-2">
                            <a href="{{ route('admin.naskah.download', $naskah) }}" class="bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Download
                            </a>
                            <a href="{{ route('admin.naskah.edit', $naskah) }}" class="bg-yellow-50 hover:bg-yellow-100 text-yellow-700 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                </svg>
                                Edit
                            </a>
                            <div class="relative inline-block text-left">
                                <button type="button" class="bg-gray-50 hover:bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition-colors" onclick="toggleDropdown('actionDropdown')">
                                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                    </svg>
                                </button>
                                <div id="actionDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                                    <div class="py-1">
                                        <button type="button" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="previewFile()">
                                            <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Preview File
                                        </button>
                                        <button type="button" class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50" onclick="openModal('hapusModal')">
                                            <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            Hapus Naskah
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informasi Naskah -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Naskah</h2>
                    <div class="space-y-4">
                        @if($naskah->deskripsi)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                                <div class="bg-gray-50 rounded-md p-4">
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $naskah->deskripsi }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama File</label>
                                <div class="flex items-center p-3 bg-gray-50 rounded-md">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-900 text-sm">{{ $naskah->nama_file_asli }}</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran File</label>
                                <div class="flex items-center p-3 bg-gray-50 rounded-md">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 01-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-900 text-sm">{{ $naskah->ukuran_file_formatted }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Batas Waktu</label>
                                <div class="flex items-center p-3 {{ $naskah->isSegera() ? 'bg-red-50 border border-red-200' : 'bg-gray-50' }} rounded-md">
                                    <svg class="w-5 h-5 {{ $naskah->isSegera() ? 'text-red-500' : 'text-gray-400' }} mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <span class="text-gray-900 text-sm font-medium">{{ $naskah->batas_waktu->format('d M Y') }}</span>
                                        @if($naskah->isSegera())
                                            <div class="text-red-600 text-xs">Segera berakhir!</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Upload</label>
                                <div class="flex items-center p-3 bg-gray-50 rounded-md">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-900 text-sm">{{ $naskah->created_at->format('d M Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan dan Review -->
            @if($naskah->catatan || $naskah->reviewer)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Review & Catatan</h2>
                        
                        @if($naskah->catatan)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                                <div class="bg-gray-50 rounded-md p-4 border-l-4 {{ $naskah->status == 'disetujui' ? 'border-green-400' : ($naskah->status == 'ditolak' ? 'border-red-400' : 'border-blue-400') }}">
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $naskah->catatan }}</p>
                                </div>
                            </div>
                        @endif

                        @if($naskah->reviewer)
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium mr-3">
                                        {{ strtoupper(substr($naskah->reviewer->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $naskah->reviewer->name }}</div>
                                        <div class="text-gray-500">Reviewer</div>
                                    </div>
                                </div>
                                @if($naskah->direview_pada)
                                    <div class="text-right">
                                        <div class="font-medium">{{ $naskah->direview_pada->format('d M Y') }}</div>
                                        <div class="text-gray-500">{{ $naskah->direview_pada->format('H:i') }}</div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Status History -->
            @if($naskah->statusHistories && $naskah->statusHistories->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Status</h2>
                        <div class="space-y-4">
                            @foreach($naskah->statusHistories as $history)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900">
                                            Status diubah dari <span class="text-gray-600">{{ $history->status_lama }}</span> 
                                            ke <span class="text-blue-600">{{ $history->status_baru }}</span>
                                        </div>
                                        @if($history->catatan)
                                            <div class="text-sm text-gray-600 mt-1">{{ $history->catatan }}</div>
                                        @endif
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $history->created_at->format('d M Y H:i') }} 
                                            @if($history->user)
                                                oleh {{ $history->user->name }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- Informasi Pengirim -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pengirim</h3>
                    <div class="text-center mb-4">
                        <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center text-white text-xl font-bold mx-auto mb-3">
                            {{ strtoupper(substr($naskah->pengirim->name, 0, 1)) }}
                        </div>
                                    <h4 class="text-lg font-medium text-gray-900">{{ $naskah->pengirim->name }}</h4>
                        <p class="text-sm text-gray-600">{{ $naskah->pengirim->email }}</p>
                    </div>
                    
                    <div class="space-y-3">
                        @if($naskah->pengirim->phone)
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                                <span class="text-gray-900">{{ $naskah->pengirim->phone }}</span>
                            </div>
                        @endif
                        
                        @if($naskah->pengirim->address)
                            <div class="flex items-start text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-900">{{ $naskah->pengirim->address }}</span>
                            </div>
                        @endif
                        
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 text-gray-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-900">Bergabung {{ $naskah->pengirim->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold text-blue-600">{{ $naskah->pengirim->total_naskah ?? 0 }}</div>
                                <div class="text-xs text-gray-600">Total Naskah</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-green-600">{{ $naskah->pengirim->naskah_disetujui ?? 0 }}</div>
                                <div class="text-xs text-gray-600">Disetujui</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="button" class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                            </svg>
                            Lihat Profile
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <button type="button" class="w-full bg-green-50 hover:bg-green-100 text-green-700 px-4 py-2 rounded-md text-sm font-medium transition-colors" onclick="openModal('setujuiModal')">
                            <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Setujui Naskah
                        </button>
                        
                        <button type="button" class="w-full bg-red-50 hover:bg-red-100 text-red-700 px-4 py-2 rounded-md text-sm font-medium transition-colors" onclick="openModal('tolakModal')">
                            <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Tolak Naskah
                        </button>
                        
                        <button type="button" class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-2 rounded-md text-sm font-medium transition-colors" onclick="previewFile()">
                            <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                            </svg>
                            Preview File
                        </button>
                        
                        <a href="{{ route('admin.naskah.download', $naskah) }}" class="w-full bg-gray-50 hover:bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition-colors inline-flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Download File
                        </a>
                    </div>
                </div>
            </div>

            <!-- File Preview -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Preview File</h3>
                    <div id="filePreview" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-gray-600 text-sm">{{ $naskah->nama_file_asli }}</p>
                        <p class="text-gray-500 text-xs mt-1">{{ $naskah->ukuran_file_formatted }}</p>
                        <button type="button" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors" onclick="previewFile()">
                            Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Naskah Terkait -->
    @if(isset($relatedNaskah) && $relatedNaskah->count() > 0)
        <div class="mt-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Naskah Terkait dari {{ $naskah->pengirim->name }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($relatedNaskah as $related)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="text-sm font-medium text-gray-900 line-clamp-2">{{ Str::limit($related->judul, 50) }}</h4>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $related->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($related->status == 'sedang_direview' ? 'bg-blue-100 text-blue-800' : ($related->status == 'disetujui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                                        {{ $related->getStatusText() }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600 mb-3">{{ $related->created_at->format('d M Y') }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">{{ $related->ukuran_file_formatted }}</span>
                                    <a href="{{ route('admin.naskah.show', $related) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Navigation -->
    <div class="mt-8 flex justify-between items-center">
        <a href="{{ route('admin.naskah.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-sm font-medium transition-colors">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
            </svg>
            Kembali ke Daftar
        </a>
        
        <div class="flex space-x-2">
                      <a href="{{ route('admin.naskah.edit', $naskah) }}" class="inline-flex items-center px-4 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-md text-sm font-medium transition-colors">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                </svg>
                Edit Naskah
            </a>
            
            <button type="button" class="inline-flex items-center px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-md text-sm font-medium transition-colors" onclick="openModal('hapusModal')">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                Hapus
            </button>
        </div>
    </div>
</div>

<!-- Modal Setujui -->
<div id="setujuiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Setujui Naskah</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal('setujuiModal')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="setujuiForm" method="POST" action="{{ route('admin.naskah.setujui', $naskah) }}">
                @csrf
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-4">Apakah Anda yakin ingin menyetujui naskah ini?</p>
                    <div>
                        <label for="setujui_catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea id="setujui_catatan" name="catatan" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="Tambahkan catatan untuk penulis..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium rounded-md transition duration-150 ease-in-out"
                        onclick="closeModal('setujuiModal')">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div id="tolakModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Tolak Naskah</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal('tolakModal')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="tolakForm" method="POST" action="{{ route('admin.naskah.tolak', $naskah) }}">
                @csrf
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-4">Apakah Anda yakin ingin menolak naskah ini?</p>
                    <div>
                        <label for="tolak_catatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="tolak_catatan" name="catatan" rows="3" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium rounded-md transition duration-150 ease-in-out"
                        onclick="closeModal('tolakModal')">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div id="hapusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Hapus Naskah</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal('hapusModal')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="hapusForm" method="POST" action="{{ route('admin.naskah.destroy', $naskah) }}">
                @csrf
                @method('DELETE')
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-4">Apakah Anda yakin ingin menghapus naskah ini?</p>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-sm text-yellow-700">
                                <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium rounded-md transition duration-150 ease-in-out"
                        onclick="closeModal('hapusModal')">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal File Preview -->
<div id="previewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Preview File: {{ $naskah->nama_file_asli }}</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal('previewModal')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="previewContent" class="border rounded-lg p-4 bg-gray-50 min-h-96">
                <div class="flex items-center justify-center h-96">
                    <div class="text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-gray-600">Klik tombol "Load Preview" untuk melihat file</p>
                        <button type="button" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors" onclick="loadPreview()">
                            Load Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Modal functions
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        // Clear form data
        const form = document.querySelector(`#${modalId} form`);
        if (form) {
            form.reset();
        }
        
        // Clear preview content
        if (modalId === 'previewModal') {
            resetPreviewContent();
        }
    }

    function resetPreviewContent() {
        document.getElementById('previewContent').innerHTML = `
            <div class="flex items-center justify-center h-96">
                <div class="text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-gray-600">Klik tombol "Load Preview" untuk melihat file</p>
                    <button type="button" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors" onclick="loadPreview()">
                        Load Preview
                    </button>
                </div>
            </div>
        `;
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modals = ['setujuiModal', 'tolakModal', 'hapusModal', 'previewModal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (event.target === modal) {
                closeModal(modalId);
            }
        });
    }

    // Preview file function
    function previewFile() {
        openModal('previewModal');
    }

    // Load preview content - PERBAIKAN UTAMA
    function loadPreview() {
        const previewContent = document.getElementById('previewContent');
        const naskahId = {{ $naskah->id }};
        
        // Show loading
        previewContent.innerHTML = `
            <div class="flex items-center justify-center h-96">
                <div class="text-center">
                    <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-600">Memuat preview...</p>
                </div>
            </div>
        `;
        
        // Gunakan route yang benar
        const previewUrl = `{{ route('admin.naskah.preview', $naskah) }}`;
        
        fetch(previewUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Preview data:', data); // Debug log
            
            if (data.success) {
                if (data.type === 'pdf') {
                    previewContent.innerHTML = `
                        <div class="h-96">
                            <iframe src="${data.url}#toolbar=0" class="w-full h-full border rounded" frameborder="0"></iframe>
                        </div>
                        <div class="mt-4 text-center">
                            <a href="${data.url}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors mr-2">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Buka di Tab Baru
                            </a>
                            <a href="{{ route('admin.naskah.download', $naskah) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Download
                            </a>
                        </div>
                    `;
                } else if (data.type === 'image') {
                    previewContent.innerHTML = `
                        <div class="text-center">
                            <img src="${data.url}" alt="Preview" class="max-w-full max-h-96 mx-auto rounded shadow" onload="this.style.opacity=1" style="opacity:0;transition:opacity 0.3s">
                            <div class="mt-4">
                                <a href="${data.url}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors mr-2">
                                    Lihat Ukuran Penuh
                                </a>
                                <a href="{{ route('admin.naskah.download', $naskah) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                    Download
                                </a>
                            </div>
                        </div>
                    `;
                } else if (data.type === 'text') {
                    previewContent.innerHTML = `
                        <div class="h-96 overflow-y-auto">
                            <pre class="whitespace-pre-wrap text-sm text-gray-800 bg-gray-50 p-4 rounded border">${data.content}</pre>
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.naskah.download', $naskah) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                Download File
                            </a>
                        </div>
                    `;
                } else {
                    previewContent.innerHTML = `
                        <div class="flex items-center justify-center h-96">
                            <div class="text-center">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-gray-600 mb-2">${data.filename}</p>
                                <p class="text-gray-500 text-sm mb-4">${data.message || 'Preview tidak tersedia untuk tipe file ini'}</p>
                                <a href="{{ route('admin.naskah.download', $naskah) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Download File
                                </a>
                            </div>
                        </div>
                    `;
                }
            } else {
                previewContent.innerHTML = `
                    <div class="flex items-center justify-center h-96">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-red-600 mb-4">${data.message}</p>
                            <a href="{{ route('admin.naskah.download', $naskah) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                Coba Download File
                            </a>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading preview:', error);
            previewContent.innerHTML = `
                <div class="flex items-center justify-center h-96">
                    <div class="text-center">
                        <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-red-600 mb-2">Terjadi kesalahan saat memuat preview</p>
                        <p class="text-gray-500 text-sm mb-4">Error: ${error.message}</p>
                        <div class="space-x-2">
                            <button type="button" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors" onclick="loadPreview()">
                                Coba Lagi
                            </button>
                            <a href="{{ route('admin.naskah.download', $naskah) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                Download File
                            </a>
                        </div>
                    </div>
                </div>
            `;
        });
    }

    // Toggle dropdown
    function toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        dropdown.classList.toggle('hidden');
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest(`#${dropdownId}`) && !event.target.closest('button[onclick*="toggleDropdown"]')) {
                dropdown.classList.add('hidden');
            }
        });
    }

    // Update status function
    function updateStatus(status) {
        const naskahId = {{ $naskah->id }};
        
        fetch(`{{ route('admin.naskah.update-status', $naskah) }}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Status berhasil diupdate', 'success');
                // Reload page after 1 second
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification('Gagal mengupdate status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan', 'error');
        });
    }

    // Load sidebar preview
    function loadSidebarPreview() {
        const previewContainer = document.getElementById('filePreview');
        if (!previewContainer) return;
        
        const previewUrl = `{{ route('admin.naskah.preview', $naskah) }}`;
        
        fetch(previewUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.type === 'image') {
                previewContainer.innerHTML = `
                    <img src="${data.url}" alt="Preview" class="w-full h-32 object-cover rounded-lg mb-3" onload="this.style.opacity=1" style="opacity:0;transition:opacity 0.3s">
                    <p class="text-gray-600 text-sm">{{ $naskah->nama_file_asli }}</p>
                    <p class="text-gray-500 text-xs mt-1">{{ $naskah->ukuran_file_formatted }}</p>
                    <button type="button" class="mt-3 w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors" onclick="previewFile()">
                        Preview Lengkap
                    </button>
                `;
            } else if (data.success && data.type === 'pdf') {
                previewContainer.innerHTML = `
                    <div class="w-full h-32 bg-red-100 rounded-lg mb-3 flex items-center justify-center">
                        <svg class="w-12 h-12 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 text-sm">{{ $naskah->nama_file_asli }}</p>
                    <p class="text-gray-500 text-xs mt-1">{{ $naskah->ukuran_file_formatted }}</p>
                    <button type="button" class="mt-3 w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors" onclick="previewFile()">
                        Preview PDF
                    </button>
                `;
            } else {
                previewContainer.innerHTML = `
                    <div class="w-full h-32 bg-gray-100 rounded-lg mb-3 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 text-sm">{{ $naskah->nama_file_asli }}</p>
                    <p class="text-gray-500 text-xs mt-1">{{ $naskah->ukuran_file_formatted }}</p>
                    <div class="mt-3 space-y-2">
                        <button type="button" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors" onclick="previewFile()">
                            Coba Preview
                        </button>
                        <a href="{{ route('admin.naskah.download', $naskah) }}" class="block w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors text-center">
                            Download
                        </a>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading sidebar preview:', error);
            previewContainer.innerHTML = `
                <div class="w-full h-32 bg-gray-100 rounded-lg mb-3 flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-gray-600 text-sm">{{ $naskah->nama_file_asli }}</p>
                <p class="text-gray-500 text-xs mt-1">{{ $naskah->ukuran_file_formatted }}</p>
                <a href="{{ route('admin.naskah.download', $naskah) }}" class="mt-3 block w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors text-center">
                    Download File
                </a>
            `;
        });
    }

    // Notification function
    function showNotification(message, type = 'info') {
        const colors = {
            success: 'bg-green-100 border-green-400 text-green-700',
            error: 'bg-red-100 border-red-400 text-red-700',
            warning: 'bg-yellow-100 border-yellow-400 text-yellow-700',
            info: 'bg-blue-100 border-blue-400 text-blue-700'
        };

        const icons = {
            success: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>',
            error: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>',
            warning: '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>',
            info: '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>'
        };

        const notification = document.createElement('div');
              notification.className = `fixed top-4 right-4 z-50 ${colors[type]} px-4 py-3 rounded border flex items-center justify-between max-w-sm shadow-lg`;
        notification.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    ${icons[type]}
                </svg>
                ${message}
            </div>
            <button type="button" class="ml-4 hover:opacity-75" onclick="this.parentElement.remove()">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        `;

        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    // Handle form submissions with loading states
    document.addEventListener('DOMContentLoaded', function() {
        const forms = ['setujuiForm', 'tolakForm', 'hapusForm'];

        forms.forEach(formId => {
            const form = document.getElementById(formId);
            if (form) {
                form.addEventListener('submit', function(e) {
                    const submitButton = form.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;

                    // Show loading state
                    submitButton.disabled = true;
                    submitButton.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    `;

                    // Reset button after 10 seconds (fallback)
                    setTimeout(() => {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    }, 10000);
                });
            }
        });

        // Auto-load file preview in sidebar
        loadSidebarPreview();
    });

    // Status check function (for real-time updates)
    function checkStatus() {
        const naskahId = {{ $naskah->id }};
        
        fetch(`{{ route('admin.naskah.status-check', $naskah) }}`)
            .then(response => response.json())
            .then(data => {
                const currentStatus = '{{ $naskah->status }}';
                if (data.status !== currentStatus) {
                    showNotification('Status naskah telah diperbarui', 'info');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            })
            .catch(error => {
                console.error('Error checking status:', error);
            });
    }

    // Check status every 30 seconds
    setInterval(checkStatus, 30000);

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Escape key to close modals
        if (e.key === 'Escape') {
            const modals = ['setujuiModal', 'tolakModal', 'hapusModal', 'previewModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal && !modal.classList.contains('hidden')) {
                    closeModal(modalId);
                }
            });
        }

        // Ctrl+S to approve (when not in input)
        if (e.ctrlKey && e.key === 's' && !['INPUT', 'TEXTAREA'].includes(e.target.tagName)) {
            e.preventDefault();
            openModal('setujuiModal');
        }

        // Ctrl+R to reject (when not in input)
        if (e.ctrlKey && e.key === 'r' && !['INPUT', 'TEXTAREA'].includes(e.target.tagName)) {
            e.preventDefault();
            openModal('tolakModal');
        }

        // Ctrl+P to preview (when not in input)
        if (e.ctrlKey && e.key === 'p' && !['INPUT', 'TEXTAREA'].includes(e.target.tagName)) {
            e.preventDefault();
            previewFile();
        }
    });

    // Print function
    function printNaskah() {
        window.print();
    }

    // Share function
    function shareNaskah() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $naskah->judul }}',
                text: 'Lihat detail naskah ini',
                url: window.location.href
            });
        } else {
            // Fallback: copy to clipboard
            navigator.clipboard.writeText(window.location.href).then(() => {
                showNotification('Link berhasil disalin ke clipboard', 'success');
            });
        }
    }

    // Add tooltips
    document.addEventListener('DOMContentLoaded', function() {
        // Simple tooltip implementation
        const tooltipElements = document.querySelectorAll('[title]');
        
        tooltipElements.forEach(element => {
            element.addEventListener('mouseenter', function(e) {
                const tooltip = document.createElement('div');
                tooltip.className = 'absolute bg-gray-800 text-white text-xs rounded py-1 px-2 z-50';
                tooltip.textContent = this.getAttribute('title');
                tooltip.style.top = (e.pageY - 30) + 'px';
                tooltip.style.left = e.pageX + 'px';
                tooltip.id = 'tooltip';
                
                document.body.appendChild(tooltip);
                this.removeAttribute('title');
                this.setAttribute('data-original-title', tooltip.textContent);
            });
            
            element.addEventListener('mouseleave', function() {
                const tooltip = document.getElementById('tooltip');
                if (tooltip) {
                    tooltip.remove();
                }
                this.setAttribute('title', this.getAttribute('data-original-title'));
            });
        });
    });
</script>

<style>
    @media print {
        .no-print {
            display: none !important;
        }
        
        .container {
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .shadow-sm {
            box-shadow: none !important;
            border: 1px solid #e5e7eb !important;
        }
        
        .bg-gray-50 {
            background-color: #f9fafb !important;
        }
        
        .text-blue-600 {
            color: #2563eb !important;
        }
        
        .text-green-600 {
            color: #16a34a !important;
        }
        
        .text-red-600 {
            color: #dc2626 !important;
        }
        
        .text-yellow-600 {
            color: #ca8a04 !important;
        }
    }
    
    /* Custom scrollbar for preview modal */
    #previewContent::-webkit-scrollbar {
        width: 8px;
    }
    
    #previewContent::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    #previewContent::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    #previewContent::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* Animation for modals */
    .modal-enter {
        animation: modalEnter 0.3s ease-out;
    }
    
    @keyframes modalEnter {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    /* Loading animation for images */
    img[style*="opacity:0"] {
        transition: opacity 0.3s ease-in-out;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container {
            padding-left: 16px;
            padding-right: 16px;
        }
        
        .grid-cols-1.lg\\:grid-cols-3 {
            grid-template-columns: 1fr;
        }
        
        .lg\\:col-span-2 {
            grid-column: span 1;
        }
        
        /* Modal adjustments for mobile */
        .fixed.inset-0 > div {
            margin: 10px;
            width: calc(100% - 20px);
            max-width: none;
        }
        
        #previewModal .relative {
            top: 10px;
            max-height: calc(100vh - 20px);
            overflow-y: auto;
        }
    }
    
    /* Enhanced button hover effects */
    .transition-colors {
        transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out;
    }
    
    /* Status badge animations */
    .status-badge {
        transition: all 0.2s ease-in-out;
    }
    
    .status-badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush
