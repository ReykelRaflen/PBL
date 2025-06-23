@extends('admin.layouts.app')

@section('title', 'Edit Naskah')

@section('main')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-semibold text-gray-900">Edit Naskah</h1>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.naskah.show', $naskah) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                            </svg>
                            Lihat Detail
                        </a>
                        <a href="{{ route('admin.naskah.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm text-green-700">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm text-red-700">{{ session('error') }}</div>
                </div>
            </div>
        @endif

        <!-- Main Form -->
        <form action="{{ route('admin.naskah.update', $naskah) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Informasi Naskah</h2>
                    <p class="text-sm text-gray-500 mt-1">Edit informasi naskah yang diperlukan</p>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left Column - Main Information -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Judul Naskah -->
                            <div>
                                <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                                    Judul Naskah <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="judul" 
                                       name="judul" 
                                       value="{{ old('judul', $naskah->judul) }}" 
                                       placeholder="Masukkan judul naskah..."
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('judul') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('judul')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Deskripsi
                                </label>
                                <textarea id="deskripsi" 
                                          name="deskripsi" 
                                          rows="4" 
                                          placeholder="Masukkan deskripsi naskah..."
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('deskripsi') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('deskripsi', $naskah->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- File Upload -->
                            <div>
                                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                                    File Naskah
                                </label>
                                
                                <!-- Current File Display -->
                                @if($naskah->file_path)
                                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="w-8 h-8 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-blue-900">File saat ini:</p>
                                                <p class="text-sm text-blue-700">{{ $naskah->nama_file_asli }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.naskah.download', $naskah) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                </div>
                                @endif

                                <!-- New File Upload -->
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors duration-200">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span>Upload file baru</span>
                                                <input id="file" 
                                                       name="file" 
                                                       type="file" 
                                                       accept=".pdf,.doc,.docx"
                                                       class="sr-only">
                                            </label>
                                            <p class="pl-1">atau drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PDF, DOC, DOCX hingga 10MB</p>
                                        <p class="text-xs text-yellow-600">Kosongkan jika tidak ingin mengubah file</p>
                                    </div>
                                </div>
                                
                                <div id="new-file-info" class="mt-2 hidden">
                                    <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-md">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <p id="new-file-name" class="text-sm font-medium text-green-900"></p>
                                            <p id="new-file-size" class="text-xs text-green-700"></p>
                                        </div>
                                        <button type="button" id="remove-new-file" class="text-green-500 hover:text-green-700">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                @error('file')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column - Additional Information -->
                        <div class="space-y-6">
                            <!-- Pengirim -->
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pengirim <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select id="user_id" 
                                            name="user_id" 
                                            required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('user_id') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                        <option value="">Pilih pengirim...</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" 
                                                {{ old('user_id', $naskah->user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('user_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status
                                </label>
                                <select id="status" 
                                        name="status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="pending" {{ old('status', $naskah->status) == 'pending' ? 'selected' : '' }}>
                                        Menunggu
                                    </option>
                                    <option value="sedang_direview" {{ old('status', $naskah->status) == 'sedang_direview' ? 'selected' : '' }}>
                                        Sedang Direview
                                    </option>
                                    <option value="disetujui" {{ old('status', $naskah->status) == 'disetujui' ? 'selected' : '' }}>
                                        Disetujui
                                    </option>
                                    <option value="ditolak" {{ old('status', $naskah->status) == 'ditolak' ? 'selected' : '' }}>
                                        Ditolak
                                    </option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Batas Waktu -->
                            <div>
                                <label for="batas_waktu" class="block text-sm font-medium text-gray-700 mb-2">
                                    Batas Waktu <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       id="batas_waktu" 
                                       name="batas_waktu" 
                                       value="{{ old('batas_waktu', $naskah->batas_waktu->format('Y-m-d')) }}" 
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('batas_waktu') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('batas_waktu')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Catatan -->
                            <div>
                                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Catatan
                                </label>
                                <textarea id="catatan" 
                                          name="catatan" 
                                          rows="3" 
                                          placeholder="Tambahkan catatan..."
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('catatan') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('catatan', $naskah->catatan) }}</textarea>
                                @error('catatan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Information Card -->
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-800 mb-2">Informasi Naskah</h3>
                                        <ul class="text-sm text-gray-600 space-y-1">
                                            <li class="flex items-start">
                                                <svg class="w-3 h-3 text-gray-500 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                </svg>
                                                Dibuat: {{ $naskah->created_at->format('d M Y H:i') }}
                                            </li>
                                            <li class="flex items-start">
                                                <svg class="w-3 h-3 text-gray-500 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                                </svg>
                                                Diupdate: {{ $naskah->updated_at->format('d M Y H:i') }}
                                            </li>
                                            @if($naskah->direview_pada)
                                            <li class="flex items-start">
                                                <svg class="w-3 h-3 text-green-500 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Direview: {{ $naskah->direview_pada->format('d M Y H:i') }}
                                            </li>
                                            @endif
                                            @if($naskah->reviewer)
                                            <li class="flex items-start">
                                                <svg class="w-3 h-3 text-blue-500 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                </svg>
                                                Reviewer: {{ $naskah->reviewer->name }}
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-800 mb-2">Status Saat Ini</h3>
                                <div class="flex items-center">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'sedang_direview' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'disetujui' => 'bg-green-100 text-green-800 border-green-200',
                                            'ditolak' => 'bg-red-100 text-red-800 border-red-200'
                                        ];
                                        $statusIcons = [
                                            'pending' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>',
                                            'sedang_direview' => '<path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>',
                                            'disetujui' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>',
                                            'ditolak' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>'
                                        ];
                                        $statusTexts = [
                                            'pending' => 'Menunggu Review',
                                            'sedang_direview' => 'Sedang Direview',
                                            'disetujui' => 'Disetujui',
                                            'ditolak' => 'Ditolak'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $statusColors[$naskah->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            {!! $statusIcons[$naskah->status] ?? '' !!}
                                        </svg>
                                        {{ $statusTexts[$naskah->status] ?? ucfirst($naskah->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <button type="button" id="preview-changes" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                </svg>
                                Preview Perubahan
                            </button>
                            <div id="unsaved-changes" class="hidden text-sm text-amber-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Ada perubahan yang belum disimpan
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.naskah.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span id="submit-text">Update Naskah</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Change History -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Riwayat Perubahan</h2>
                <p class="text-sm text-gray-500 mt-1">Timeline aktivitas naskah ini</p>
            </div>
            <div class="p-6">
                <div class="flow-root">
                    <ul class="-mb-8">
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Naskah dibuat oleh <span class="font-medium text-gray-900">{{ $naskah->pengirim->name }}</span></p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time datetime="{{ $naskah->created_at->toISOString() }}">{{ $naskah->created_at->format('d M Y H:i') }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        @if($naskah->direview_pada)
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full {{ $naskah->status == 'disetujui' ? 'bg-green-500' : 'bg-red-500' }} flex items-center justify-center ring-8 ring-white">
                                            @if($naskah->status == 'disetujui')
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">
                                                Naskah {{ $naskah->status == 'disetujui' ? 'disetujui' : 'ditolak' }} oleh 
                                                <span class="font-medium text-gray-900">{{ $naskah->reviewer->name ?? 'Admin' }}</span>
                                            </p>
                                            @if($naskah->catatan)
                                                <div class="mt-2 text-sm text-gray-700">
                                                    <div class="bg-gray-50 rounded-md p-3 border-l-4 {{ $naskah->status == 'disetujui' ? 'border-green-400' : 'border-red-400' }}">
                                                        <p class="text-sm">{{ $naskah->catatan }}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time datetime="{{ $naskah->direview_pada->toISOString() }}">{{ $naskah->direview_pada->format('d M Y H:i') }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endif

                        <li>
                            <div class="relative">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Terakhir diupdate</p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time datetime="{{ $naskah->updated_at->toISOString() }}">{{ $naskah->updated_at->format('d M Y H:i') }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                    <svg class="animate-spin h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Memproses...</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Sedang menyimpan perubahan naskah</p>
                </div>
            </div>
        </div>
    </div>

       <!-- Preview Modal -->
    <div id="preview-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-4 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
            <div class="flex items-center justify-between border-b border-gray-200 pb-4 mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Preview Perubahan</h3>
                <button type="button" id="close-preview" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Before -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                        <span class="inline-block w-3 h-3 bg-red-400 rounded-full mr-2"></span>
                        Sebelum
                    </h4>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Judul</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $naskah->judul }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Deskripsi</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $naskah->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</label>
                            <p class="text-sm text-gray-900 mt-1">{{ ucfirst($naskah->status) }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Batas Waktu</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $naskah->batas_waktu->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- After -->
                <div>
                    <h4 class="text-md font-medium text-gray-900 mb-3 flex items-center">
                        <span class="inline-block w-3 h-3 bg-green-400 rounded-full mr-2"></span>
                        Sesudah
                    </h4>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 space-y-3">
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Judul</label>
                            <p id="preview-judul" class="text-sm text-gray-900 mt-1"></p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Deskripsi</label>
                            <p id="preview-deskripsi" class="text-sm text-gray-900 mt-1"></p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</label>
                            <p id="preview-status" class="text-sm text-gray-900 mt-1"></p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Batas Waktu</label>
                            <p id="preview-batas-waktu" class="text-sm text-gray-900 mt-1"></p>
                        </div>
                        <div id="preview-file-section" class="hidden">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">File Baru</label>
                            <p id="preview-file" class="text-sm text-gray-900 mt-1"></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" id="cancel-preview" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Tutup
                </button>
                <button type="button" id="confirm-changes" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let originalValues = {};
    let hasUnsavedChanges = false;

    // Store original values
    function storeOriginalValues() {
        originalValues = {
            judul: $('#judul').val(),
            deskripsi: $('#deskripsi').val(),
            user_id: $('#user_id').val(),
            status: $('#status').val(),
            batas_waktu: $('#batas_waktu').val(),
            catatan: $('#catatan').val()
        };
    }

    // Check for changes
    function checkForChanges() {
        const currentValues = {
            judul: $('#judul').val(),
            deskripsi: $('#deskripsi').val(),
            user_id: $('#user_id').val(),
            status: $('#status').val(),
            batas_waktu: $('#batas_waktu').val(),
            catatan: $('#catatan').val()
        };

        const fileChanged = $('#file')[0].files.length > 0;
        
        hasUnsavedChanges = fileChanged || Object.keys(originalValues).some(key => 
            originalValues[key] !== currentValues[key]
        );

        if (hasUnsavedChanges) {
            $('#unsaved-changes').removeClass('hidden');
        } else {
            $('#unsaved-changes').addClass('hidden');
        }

        return hasUnsavedChanges;
    }

    // Initialize
    storeOriginalValues();

    // Monitor form changes
    $('input, select, textarea').on('input change', function() {
        checkForChanges();
    });

    // Enhanced file upload with drag & drop
    const fileInput = $('#file')[0];
    const dropZone = fileInput.closest('.border-dashed')[0];

    // Drag & drop events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-blue-400', 'bg-blue-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    }

    // File selection handler
    $('#file').change(function(e) {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });

    function handleFileSelect(file) {
        // Validate file
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        const maxSize = 10 * 1024 * 1024; // 10MB

        if (!allowedTypes.includes(file.type)) {
            showNotification('Format file tidak didukung. Gunakan PDF, DOC, atau DOCX.', 'error');
            $('#file').val('');
            return;
        }

        if (file.size > maxSize) {
            showNotification('Ukuran file terlalu besar. Maksimal 10MB.', 'error');
            $('#file').val('');
            return;
        }

        // Show file info
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        $('#new-file-name').text(file.name);
        $('#new-file-size').text(`${fileSize} MB`);
        $('#new-file-info').removeClass('hidden');

        checkForChanges();
    }

    // Remove new file
    $('#remove-new-file').click(function() {
        $('#file').val('');
        $('#new-file-info').addClass('hidden');
        checkForChanges();
    });

    // Preview changes
    $('#preview-changes').click(function() {
        if (!checkForChanges()) {
            showNotification('Tidak ada perubahan untuk ditampilkan.', 'info');
            return;
        }

        // Update preview content
        $('#preview-judul').text($('#judul').val());
        $('#preview-deskripsi').text($('#deskripsi').val() || 'Tidak ada deskripsi');
        $('#preview-status').text($('#status option:selected').text());
        
        const batasWaktu = $('#batas_waktu').val();
        if (batasWaktu) {
            const date = new Date(batasWaktu);
            $('#preview-batas-waktu').text(date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            }));
        }

        // Show file changes
        const fileInput = $('#file')[0];
        if (fileInput.files.length > 0) {
            $('#preview-file').text(fileInput.files[0].name);
            $('#preview-file-section').removeClass('hidden');
        } else {
            $('#preview-file-section').addClass('hidden');
        }

        $('#preview-modal').removeClass('hidden');
    });

    // Close preview modal
    $('#close-preview, #cancel-preview').click(function() {
        $('#preview-modal').addClass('hidden');
    });

    // Confirm changes from preview
    $('#confirm-changes').click(function() {
        $('#preview-modal').addClass('hidden');
        $('form').submit();
    });

    // Form submission with loading
    $('form').submit(function(e) {
        $('#loading-overlay').removeClass('hidden');
        $('#submit-text').text('Menyimpan...');
        
        // Disable form elements
        $('input, select, textarea, button').prop('disabled', true);
    });

    // Prevent leaving page with unsaved changes
    window.addEventListener('beforeunload', function(e) {
        if (hasUnsavedChanges) {
            e.preventDefault();
            e.returnValue = '';
            return 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?';
        }
    });

    // Auto-save draft functionality
    let autoSaveTimer;
    function autoSaveDraft() {
        if (!hasUnsavedChanges) return;

        const formData = {
            judul: $('#judul').val(),
            deskripsi: $('#deskripsi').val(),
            user_id: $('#user_id').val(),
            status: $('#status').val(),
            batas_waktu: $('#batas_waktu').val(),
            catatan: $('#catatan').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        // Save to localStorage as backup
        localStorage.setItem('naskah_edit_draft_{{ $naskah->id }}', JSON.stringify({
            ...formData,
            timestamp: Date.now()
        }));

        console.log('Draft auto-saved');
    }

    // Auto-save every 30 seconds
    $('input, select, textarea').on('input change', function() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(autoSaveDraft, 30000);
    });

    // Load draft on page load
    function loadDraft() {
        const draft = localStorage.getItem('naskah_edit_draft_{{ $naskah->id }}');
        if (draft) {
            const draftData = JSON.parse(draft);
            const draftAge = Date.now() - draftData.timestamp;
            
            // Only load draft if it's less than 1 hour old
            if (draftAge < 3600000) {
                const shouldLoadDraft = confirm('Ditemukan draft yang belum disimpan. Muat draft tersebut?');
                if (shouldLoadDraft) {
                    $('#judul').val(draftData.judul);
                    $('#deskripsi').val(draftData.deskripsi);
                    $('#user_id').val(draftData.user_id);
                    $('#status').val(draftData.status);
                    $('#batas_waktu').val(draftData.batas_waktu);
                    $('#catatan').val(draftData.catatan);
                    
                    checkForChanges();
                    showNotification('Draft berhasil dimuat', 'success');
                }
            } else {
                // Remove old draft
                localStorage.removeItem('naskah_edit_draft_{{ $naskah->id }}');
            }
        }
    }

    // Load draft on page load
    loadDraft();

      // Clear draft on successful form submission
    $('form').on('submit', function() {
        localStorage.removeItem('naskah_edit_draft_{{ $naskah->id }}');
    });

    // Notification function
    function showNotification(message, type = 'info') {
        const colors = {
            success: 'bg-green-50 border-green-200 text-green-800',
            error: 'bg-red-50 border-red-200 text-red-800',
            warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
            info: 'bg-blue-50 border-blue-200 text-blue-800'
        };

        const icons = {
            success: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>',
            error: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>',
            warning: '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>',
            info: '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>'
        };

        const notification = $(`
            <div class="fixed top-4 right-4 max-w-sm w-full ${colors[type]} border rounded-lg shadow-lg z-50 notification-toast">
                <div class="p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                ${icons[type]}
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">${message}</p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button type="button" class="inline-flex rounded-md p-1.5 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600 close-notification">
                                    <span class="sr-only">Dismiss</span>
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `);

        $('body').append(notification);

        // Auto dismiss after 5 seconds
        setTimeout(() => {
            notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);

        // Manual dismiss
        notification.find('.close-notification').click(function() {
            notification.fadeOut(300, function() {
                $(this).remove();
            });
        });
    }

    // Keyboard shortcuts
    $(document).keydown(function(e) {
        // Ctrl+S to save
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            if (hasUnsavedChanges) {
                $('form').submit();
            } else {
                showNotification('Tidak ada perubahan untuk disimpan', 'info');
            }
        }
        
        // Ctrl+P to preview
        if (e.ctrlKey && e.key === 'p') {
            e.preventDefault();
            $('#preview-changes').click();
        }
        
        // Escape to close modals
        if (e.key === 'Escape') {
            $('#preview-modal').addClass('hidden');
            $('#loading-overlay').addClass('hidden');
        }
    });

    // Form validation enhancements
    function validateForm() {
        let isValid = true;
        const errors = [];

        // Validate required fields
        const requiredFields = {
            'judul': 'Judul naskah',
            'user_id': 'Pengirim',
            'batas_waktu': 'Batas waktu'
        };

        Object.keys(requiredFields).forEach(field => {
            const value = $(`#${field}`).val();
            if (!value || value.trim() === '') {
                errors.push(`${requiredFields[field]} harus diisi`);
                $(`#${field}`).addClass('border-red-300 focus:ring-red-500 focus:border-red-500');
                isValid = false;
            } else {
                $(`#${field}`).removeClass('border-red-300 focus:ring-red-500 focus:border-red-500');
            }
        });

        // Validate date
        const batasWaktu = $('#batas_waktu').val();
        if (batasWaktu) {
            const selectedDate = new Date(batasWaktu);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate <= today) {
                errors.push('Batas waktu harus lebih dari hari ini');
                $('#batas_waktu').addClass('border-red-300 focus:ring-red-500 focus:border-red-500');
                isValid = false;
            } else {
                $('#batas_waktu').removeClass('border-red-300 focus:ring-red-500 focus:border-red-500');
            }
        }

        // Show validation errors
        if (errors.length > 0) {
            errors.forEach(error => showNotification(error, 'error'));
        }

        return isValid;
    }

    // Enhanced form submission
    $('form').submit(function(e) {
        if (!validateForm()) {
            e.preventDefault();
            $('#loading-overlay').addClass('hidden');
            $('#submit-text').text('Update Naskah');
            $('input, select, textarea, button').prop('disabled', false);
            return false;
        }
    });

    // Real-time validation
    $('input, select, textarea').on('blur', function() {
        const field = $(this).attr('id');
        const value = $(this).val();
        
        if (['judul', 'user_id', 'batas_waktu'].includes(field)) {
            if (!value || value.trim() === '') {
                $(this).addClass('border-red-300 focus:ring-red-500 focus:border-red-500');
            } else {
                $(this).removeClass('border-red-300 focus:ring-red-500 focus:border-red-500');
            }
        }
        
        if (field === 'batas_waktu' && value) {
            const selectedDate = new Date(value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate <= today) {
                $(this).addClass('border-red-300 focus:ring-red-500 focus:border-red-500');
            } else {
                $(this).removeClass('border-red-300 focus:ring-red-500 focus:border-red-500');
            }
        }
    });

    // Character counter for textarea fields
    function addCharacterCounter(selector, maxLength) {
        const textarea = $(selector);
        const counter = $(`<div class="text-xs text-gray-500 mt-1 text-right"><span class="current">0</span>/${maxLength}</div>`);
        textarea.after(counter);
        
        textarea.on('input', function() {
            const current = $(this).val().length;
            counter.find('.current').text(current);
            
            if (current > maxLength) {
                counter.addClass('text-red-500');
                textarea.addClass('border-red-300 focus:ring-red-500 focus:border-red-500');
            } else {
                counter.removeClass('text-red-500');
                textarea.removeClass('border-red-300 focus:ring-red-500 focus:border-red-500');
            }
        });
        
        // Initialize counter
        textarea.trigger('input');
    }

    // Add character counters
    addCharacterCounter('#deskripsi', 1000);
    addCharacterCounter('#catatan', 500);

    // Initialize tooltips for better UX
    $('[title]').each(function() {
        $(this).tooltip();
    });

    // Success message on page load
    @if(session('success'))
        showNotification('{{ session('success') }}', 'success');
    @endif

    @if(session('error'))
        showNotification('{{ session('error') }}', 'error');
    @endif

    // Focus on first input
    $('#judul').focus();
});
</script>
@endpush

@push('styles')
<style>
    .notification-toast {
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
    
    .drag-over {
        border-color: #3B82F6 !important;
        background-color: #EFF6FF !important;
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .character-counter.warning {
        color: #F59E0B;
    }
    
    .character-counter.danger {
        color: #EF4444;
    }
    
    /* Loading animation */
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    /* Custom scrollbar for modal */
    .modal-content {
        scrollbar-width: thin;
        scrollbar-color: #CBD5E0 #F7FAFC;
    }
    
    .modal-content::-webkit-scrollbar {
        width: 6px;
    }
    
    .modal-content::-webkit-scrollbar-track {
        background: #F7FAFC;
    }
    
    .modal-content::-webkit-scrollbar-thumb {
        background-color: #CBD5E0;
        border-radius: 3px;
    }
</style>
@endpush
