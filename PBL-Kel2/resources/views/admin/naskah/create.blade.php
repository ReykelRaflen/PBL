@extends('admin.layouts.app')

@section('title', 'Tambah Naskah Baru')

@section('main')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-semibold text-gray-900">Tambah Naskah Baru</h1>
                    <a href="{{ route('admin.naskah.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                        </svg>
                        Kembali
                    </a>
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
        <form action="{{ route('admin.naskah.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Informasi Naskah</h2>
                    <p class="text-sm text-gray-500 mt-1">Lengkapi informasi dasar naskah yang akan ditambahkan</p>
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
                                       value="{{ old('judul') }}" 
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
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('deskripsi') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- File Upload -->
                            <div>
                                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                                    File Naskah <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors duration-200">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span>Upload file</span>
                                                <input id="file" 
                                                       name="file" 
                                                       type="file" 
                                                       accept=".pdf,.doc,.docx" 
                                                       required
                                                       class="sr-only">
                                            </label>
                                            <p class="pl-1">atau drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PDF, DOC, DOCX hingga 10MB</p>
                                    </div>
                                </div>
                                <div id="file-info" class="mt-2 hidden">
                                    <div class="flex items-center p-3 bg-blue-50 border border-blue-200 rounded-md">
                                        <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <p id="file-name" class="text-sm font-medium text-blue-900"></p>
                                            <p id="file-size" class="text-xs text-blue-700"></p>
                                        </div>
                                        <button type="button" id="remove-file" class="text-blue-500 hover:text-blue-700">
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
                                <select id="user_id" 
                                        name="user_id" 
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('user_id') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Pilih pengirim...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
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
                                    <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>
                                        Menunggu
                                    </option>
                                    <option value="sedang_direview" {{ old('status') == 'sedang_direview' ? 'selected' : '' }}>
                                        Sedang Direview
                                    </option>
                                    <option value="disetujui" {{ old('status') == 'disetujui' ? 'selected' : '' }}>
                                        Disetujui
                                    </option>
                                    <option value="ditolak" {{ old('status') == 'ditolak' ? 'selected' : '' }}>
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
                                       value="{{ old('batas_waktu') }}" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
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
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('catatan') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Information Card -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-blue-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <h3 class="text-sm font-medium text-blue-800 mb-2">Informasi Penting</h3>
                                        <ul class="text-sm text-blue-700 space-y-1">
                                            <li class="flex items-start">
                                                <svg class="w-3 h-3 text-blue-500 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                File akan disimpan dengan aman
                                            </li>
                                            <li class="flex items-start">
                                                <svg class="w-3 h-3 text-blue-500 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                Batas waktu minimal H+1
                                            </li>
                                            <li class="flex items-start">
                                                <svg class="w-3 h-3 text-blue-500 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                </svg>
                                                Pengirim akan mendapat notifikasi
                                            </li>
                                            <li class="flex items-start">
                                                <svg class="w-3 h-3 text-blue-500 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                                Format: PDF, DOC, DOCX (Max 10MB)
                                            </li>
                                        </ul>
                                    </div>
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
                        <div class="text-sm text-gray-500">
                            <span class="text-red-500">*</span> Field wajib diisi
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.naskah.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Batal
                            </a>
                            <button type="submit" 
                                    id="submit-btn"
                                    class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span id="submit-text">Simpan Naskah</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 flex items-center space-x-3 shadow-xl">
        <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-gray-700 font-medium">Menyimpan naskah...</span>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // File upload handling
    const fileInput = document.getElementById('file');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const removeFileBtn = document.getElementById('remove-file');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const loadingOverlay = document.getElementById('loading-overlay');

    // File input change handler
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            handleFileSelection(file);
        }
    });

    // Drag and drop functionality
    const dropZone = fileInput.closest('.border-dashed');
    
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
            const file = files[0];
            fileInput.files = files;
            handleFileSelection(file);
        }
    }

    function handleFileSelection(file) {
        // Validate file type
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!allowedTypes.includes(file.type)) {
            showNotification('Format file tidak didukung. Gunakan PDF, DOC, atau DOCX.', 'error');
            fileInput.value = '';
            return;
        }

        // Validate file size (10MB)
        const maxSize = 10 * 1024 * 1024; // 10MB in bytes
        if (file.size > maxSize) {
            showNotification('Ukuran file terlalu besar! Maksimal 10MB.', 'error');
            fileInput.value = '';
            return;
        }

        // Display file info
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        fileInfo.classList.remove('hidden');
        
        // Hide drop zone
        dropZone.classList.add('hidden');
    }

    // Remove file handler
    removeFileBtn.addEventListener('click', function() {
        fileInput.value = '';
        fileInfo.classList.add('hidden');
        dropZone.classList.remove('hidden');
    });

    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Set minimum date for batas_waktu
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const minDate = tomorrow.toISOString().split('T')[0];
    document.getElementById('batas_waktu').setAttribute('min', minDate);

    // Form validation
    const form = document.querySelector('form');
    const requiredFields = form.querySelectorAll('[required]');

    // Real-time validation
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            validateField(this);
        });

        field.addEventListener('input', function() {
            if (this.classList.contains('border-red-300')) {
                validateField(this);
            }
        });
    });

    function validateField(field) {
        const isValid = field.checkValidity();
        const errorElement = field.parentNode.querySelector('.text-red-600');

        if (isValid) {
            field.classList.remove('border-red-300', 'focus:ring-red-500', 'focus:border-red-500');
                      field.classList.add('border-green-300', 'focus:ring-green-500', 'focus:border-green-500');
            if (errorElement && !errorElement.textContent.includes('{{')) {
                errorElement.remove();
            }
        } else {
            field.classList.remove('border-green-300', 'focus:ring-green-500', 'focus:border-green-500');
            field.classList.add('border-red-300', 'focus:ring-red-500', 'focus:border-red-500');
        }
    }

    // Form submission handler
    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Validate all required fields
        requiredFields.forEach(field => {
            if (!field.checkValidity()) {
                validateField(field);
                isValid = false;
            }
        });

        // Additional custom validations
        const batasWaktu = document.getElementById('batas_waktu').value;
        if (batasWaktu) {
            const selectedDate = new Date(batasWaktu);
            const minDate = new Date();
            minDate.setDate(minDate.getDate() + 1);
            
            if (selectedDate < minDate) {
                showNotification('Batas waktu harus minimal H+1 dari hari ini', 'error');
                isValid = false;
            }
        }

        if (!isValid) {
            e.preventDefault();
            showNotification('Mohon lengkapi semua field yang wajib diisi', 'error');
            return;
        }

        // Show loading state
        showLoadingState();
    });

    function showLoadingState() {
        submitBtn.disabled = true;
        submitText.textContent = 'Menyimpan...';
        submitBtn.querySelector('svg').classList.add('animate-spin');
        loadingOverlay.classList.remove('hidden');
    }

    // Auto-save draft functionality (optional)
    let autoSaveTimeout;
    const draftKey = 'naskah_draft_' + Date.now();

    function autoSave() {
        const formData = new FormData(form);
        const draftData = {};
        
        for (let [key, value] of formData.entries()) {
            if (key !== 'file' && key !== '_token') {
                draftData[key] = value;
            }
        }

        localStorage.setItem(draftKey, JSON.stringify(draftData));
        showNotification('Draft tersimpan otomatis', 'info', 2000);
    }

    // Auto-save on input change (debounced)
    form.addEventListener('input', function(e) {
        if (e.target.type !== 'file') {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(autoSave, 3000); // Save after 3 seconds of inactivity
        }
    });

    // Load draft on page load
    function loadDraft() {
        const savedDraft = localStorage.getItem(draftKey);
        if (savedDraft) {
            try {
                const draftData = JSON.parse(savedDraft);
                Object.keys(draftData).forEach(key => {
                    const field = form.querySelector(`[name="${key}"]`);
                    if (field && !field.value) {
                        field.value = draftData[key];
                    }
                });
                showNotification('Draft dimuat dari penyimpanan lokal', 'info', 3000);
            } catch (e) {
                console.error('Error loading draft:', e);
            }
        }
    }

    // Clear draft on successful submission
    window.addEventListener('beforeunload', function() {
        // Only clear if form was submitted successfully
        if (form.dataset.submitted === 'true') {
            localStorage.removeItem(draftKey);
        }
    });

    // Load draft if available
    // loadDraft();

    // Character counter for textarea fields
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        const maxLength = textarea.getAttribute('maxlength');
        if (maxLength) {
            addCharacterCounter(textarea, maxLength);
        }
    });

    function addCharacterCounter(textarea, maxLength) {
        const counter = document.createElement('div');
        counter.className = 'text-xs text-gray-500 mt-1 text-right';
        counter.textContent = `0/${maxLength}`;
        textarea.parentNode.appendChild(counter);

        textarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            counter.textContent = `${currentLength}/${maxLength}`;
            
            if (currentLength > maxLength * 0.9) {
                counter.classList.add('text-yellow-600');
            } else {
                counter.classList.remove('text-yellow-600');
            }
            
            if (currentLength >= maxLength) {
                counter.classList.add('text-red-600');
            } else {
                counter.classList.remove('text-red-600');
            }
        });
    }

    // Enhanced user selection with search
    const userSelect = document.getElementById('user_id');
    enhanceSelect(userSelect);

    function enhanceSelect(selectElement) {
        // Add search functionality for user select
        const wrapper = document.createElement('div');
        wrapper.className = 'relative';
        
        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.placeholder = 'Cari pengirim...';
        searchInput.className = 'w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500';
        
        const dropdown = document.createElement('div');
        dropdown.className = 'absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-60 overflow-auto hidden';
        
        // Store original options
        const originalOptions = Array.from(selectElement.options);
        
        selectElement.parentNode.insertBefore(wrapper, selectElement);
        wrapper.appendChild(searchInput);
        wrapper.appendChild(dropdown);
        wrapper.appendChild(selectElement);
        selectElement.classList.add('hidden');
        
        // Populate dropdown
        function populateDropdown(options) {
            dropdown.innerHTML = '';
            options.forEach(option => {
                if (option.value === '') return; // Skip empty option
                
                const item = document.createElement('div');
                item.className = 'px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm';
                item.textContent = option.textContent;
                item.dataset.value = option.value;
                
                item.addEventListener('click', function() {
                    selectElement.value = this.dataset.value;
                    searchInput.value = this.textContent;
                    dropdown.classList.add('hidden');
                    validateField(selectElement);
                });
                
                dropdown.appendChild(item);
            });
        }
        
        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const filteredOptions = originalOptions.filter(option => 
                option.textContent.toLowerCase().includes(searchTerm)
            );
            populateDropdown(filteredOptions);
            dropdown.classList.remove('hidden');
        });
        
        searchInput.addEventListener('focus', function() {
            populateDropdown(originalOptions.slice(1)); // Exclude empty option
            dropdown.classList.remove('hidden');
        });
        
        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!wrapper.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
        
        // Set initial value if exists
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        if (selectedOption && selectedOption.value) {
            searchInput.value = selectedOption.textContent;
        }
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+S to save
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            form.dispatchEvent(new Event('submit'));
        }
        
        // Escape to cancel
        if (e.key === 'Escape') {
            const cancelBtn = document.querySelector('a[href*="index"]');
            if (cancelBtn) {
                window.location.href = cancelBtn.href;
            }
        }
    });

    // Form change detection for unsaved changes warning
    let formChanged = false;
    const formElements = form.querySelectorAll('input, textarea, select');
    
    formElements.forEach(element => {
        element.addEventListener('change', function() {
            formChanged = true;
        });
    });

    window.addEventListener('beforeunload', function(e) {
        if (formChanged && !form.dataset.submitted) {
            e.preventDefault();
            e.returnValue = 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?';
        }
    });

    // Mark form as submitted to prevent warning
    form.addEventListener('submit', function() {
        form.dataset.submitted = 'true';
        formChanged = false;
    });
});

// Notification function
function showNotification(message, type = 'info', duration = 5000) {
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

    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 ${colors[type]} px-4 py-3 rounded-md border flex items-center justify-between max-w-sm shadow-lg transform transition-all duration-300 translate-x-full`;
    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                ${icons[type]}
            </svg>
            <span class="text-sm font-medium">${message}</span>
        </div>
        <button type="button" class="ml-4 hover:opacity-75 flex-shrink-0" onclick="this.parentElement.remove()">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Auto remove
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 300);
    }, duration);
}

// Progress indicator for form completion
function updateFormProgress() {
    const requiredFields = document.querySelectorAll('[required]');
    const filledFields = Array.from(requiredFields).filter(field => {
        if (field.type === 'file') {
            return field.files.length > 0;
        }
        return field.value.trim() !== '';
    });
    
    const progress = (filledFields.length / requiredFields.length) * 100;
    
    // Update progress bar if exists
    const progressBar = document.getElementById('form-progress');
    if (progressBar) {
        progressBar.style.width = progress + '%';
        progressBar.setAttribute('aria-valuenow', progress);
    }
    
    return progress;
}

// Add progress bar to form (optional)
function addProgressBar() {
    const form = document.querySelector('form');
    const progressContainer = document.createElement('div');
    progressContainer.className = 'mb-6';
    progressContainer.innerHTML = `
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-700">Progress Pengisian</span>
                        <span class="text-sm text-gray-500" id="progress-text">0%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div id="form-progress" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    `;
    
    form.insertBefore(progressContainer, form.firstChild);
    
    // Update progress on field changes
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        field.addEventListener('input', updateProgress);
        field.addEventListener('change', updateProgress);
    });
    
    function updateProgress() {
        const progress = updateFormProgress();
        document.getElementById('progress-text').textContent = Math.round(progress) + '%';
        
        // Change color based on progress
        const progressBar = document.getElementById('form-progress');
        if (progress < 30) {
            progressBar.className = 'bg-red-600 h-2 rounded-full transition-all duration-300';
        } else if (progress < 70) {
            progressBar.className = 'bg-yellow-600 h-2 rounded-full transition-all duration-300';
        } else {
            progressBar.className = 'bg-green-600 h-2 rounded-full transition-all duration-300';
        }
    }
    
    // Initial progress calculation
    updateProgress();
}

// Initialize progress bar (uncomment to enable)
// document.addEventListener('DOMContentLoaded', addProgressBar);

// Form field animations
function addFieldAnimations() {
    const formGroups = document.querySelectorAll('.space-y-6 > div');
    
    formGroups.forEach((group, index) => {
        group.style.opacity = '0';
        group.style.transform = 'translateY(20px)';
        group.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        
        setTimeout(() => {
            group.style.opacity = '1';
            group.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// Initialize animations on page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(addFieldAnimations, 100);
});

// Enhanced file validation with preview
function enhanceFileUpload() {
    const fileInput = document.getElementById('file');
    const dropZone = fileInput.closest('.border-dashed');
    
    // Add file preview functionality
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type === 'application/pdf') {
            addPDFPreview(file);
        }
    });
    
    function addPDFPreview(file) {
        const previewContainer = document.createElement('div');
        previewContainer.className = 'mt-4 p-4 bg-gray-50 rounded-lg border';
        previewContainer.innerHTML = `
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-medium text-gray-900">Preview PDF</h4>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="this.closest('.mt-4').remove()">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <div class="bg-white border rounded p-2">
                <embed src="${URL.createObjectURL(file)}" type="application/pdf" width="100%" height="200px" />
            </div>
        `;
        
        const fileInfoContainer = document.getElementById('file-info');
        fileInfoContainer.appendChild(previewContainer);
    }
}

// Smart form suggestions
function addSmartSuggestions() {
    const judulInput = document.getElementById('judul');
    const deskripsiTextarea = document.getElementById('deskripsi');
    
    // Auto-generate description based on title
    judulInput.addEventListener('blur', function() {
        if (this.value && !deskripsiTextarea.value) {
            const suggestions = generateDescriptionSuggestions(this.value);
            if (suggestions.length > 0) {
                showDescriptionSuggestions(suggestions);
            }
        }
    });
    
    function generateDescriptionSuggestions(title) {
        const keywords = title.toLowerCase().split(' ');
        const suggestions = [];
        
        // Simple keyword-based suggestions
        if (keywords.some(word => ['penelitian', 'riset', 'study'].includes(word))) {
            suggestions.push('Penelitian ini bertujuan untuk menganalisis dan memberikan pemahaman mendalam tentang ' + title.toLowerCase() + '.');
        }
        
        if (keywords.some(word => ['analisis', 'analysis'].includes(word))) {
            suggestions.push('Analisis komprehensif mengenai ' + title.toLowerCase() + ' dengan metodologi yang sistematis.');
        }
        
        if (keywords.some(word => ['implementasi', 'penerapan'].includes(word))) {
            suggestions.push('Dokumen ini membahas implementasi dan penerapan ' + title.toLowerCase() + ' secara detail.');
        }
        
        return suggestions;
    }
    
    function showDescriptionSuggestions(suggestions) {
        const suggestionContainer = document.createElement('div');
        suggestionContainer.className = 'mt-2 p-3 bg-blue-50 border border-blue-200 rounded-md';
        suggestionContainer.innerHTML = `
            <div class="flex items-start">
                <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-medium text-blue-800 mb-2">Saran Deskripsi:</p>
                    ${suggestions.map((suggestion, index) => `
                        <button type="button" class="block w-full text-left text-sm text-blue-700 hover:text-blue-900 hover:bg-blue-100 p-2 rounded mb-1 transition-colors duration-150" onclick="useSuggestion('${suggestion.replace(/'/g, "\\'")}', this.closest('.mt-2'))">
                            ${suggestion}
                        </button>
                    `).join('')}
                    <button type="button" class="text-xs text-blue-600 hover:text-blue-800 mt-1" onclick="this.closest('.mt-2').remove()">
                        Tutup saran
                    </button>
                </div>
            </div>
        `;
        
        deskripsiTextarea.parentNode.appendChild(suggestionContainer);
    }
}

// Function to use suggestion
function useSuggestion(suggestion, container) {
    document.getElementById('deskripsi').value = suggestion;
    container.remove();
    showNotification('Saran deskripsi telah diterapkan', 'success', 3000);
}

// Initialize smart suggestions
document.addEventListener('DOMContentLoaded', addSmartSuggestions);

// Form validation summary
function showValidationSummary() {
    const form = document.querySelector('form');
    const requiredFields = form.querySelectorAll('[required]');
    const invalidFields = [];
    
    requiredFields.forEach(field => {
        if (!field.checkValidity()) {
            const label = field.parentNode.querySelector('label');
            invalidFields.push(label ? label.textContent.replace(' *', '') : field.name);
        }
    });
    
    if (invalidFields.length > 0) {
        const summaryContainer = document.createElement('div');
        summaryContainer.className = 'fixed top-4 left-4 bg-red-50 border border-red-200 rounded-lg p-4 shadow-lg z-50 max-w-sm';
        summaryContainer.innerHTML = `
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-400 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-red-800 mb-2">Field yang perlu dilengkapi:</h3>
                    <ul class="text-sm text-red-700 space-y-1">
                        ${invalidFields.map(field => `<li>• ${field}</li>`).join('')}
                    </ul>
                    <button type="button" class="mt-2 text-xs text-red-600 hover:text-red-800" onclick="this.closest('.fixed').remove()">
                        Tutup
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(summaryContainer);
        
        // Auto remove after 10 seconds
        setTimeout(() => {
            if (summaryContainer.parentElement) {
                summaryContainer.remove();
            }
        }, 10000);
    }
}

// Enhanced form submission with validation summary
document.querySelector('form').addEventListener('submit', function(e) {
    const isValid = this.checkValidity();
    if (!isValid) {
        e.preventDefault();
        showValidationSummary();
    }
});

// Accessibility improvements
function enhanceAccessibility() {
    // Add ARIA labels and descriptions
    const requiredFields = document.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        field.setAttribute('aria-required', 'true');
        
        const label = field.parentNode.querySelector('label');
        if (label) {
            const labelId = 'label-' + field.id;
            label.id = labelId;
            field.setAttribute('aria-labelledby', labelId);
        }
    });
    
    // Add keyboard navigation for custom elements
    const customButtons = document.querySelectorAll('[role="button"]');
    customButtons.forEach(button => {
        button.setAttribute('tabindex', '0');
        button.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });
    
    // Announce form changes to screen readers
    const liveRegion = document.createElement('div');
    liveRegion.setAttribute('aria-live', 'polite');
    liveRegion.setAttribute('aria-atomic', 'true');
    liveRegion.className = 'sr-only';
    document.body.appendChild(liveRegion);
    
    // Update live region on form changes
    window.announceToScreenReader = function(message) {
        liveRegion.textContent = message;
        setTimeout(() => {
            liveRegion.textContent = '';
        }, 1000);
    };
}

// Initialize accessibility enhancements
document.addEventListener('DOMContentLoaded', enhanceAccessibility);

// Performance monitoring
function monitorPerformance() {
    const startTime = performance.now();
    
    window.addEventListener('load', function() {
        const loadTime = performance.now() - startTime;
        console.log(`Page loaded in ${loadTime.toFixed(2)}ms`);
        
        // Report slow loading times
        if (loadTime > 3000) {
            console.warn('Page loading time is slow:', loadTime + 'ms');
        }
    });
    
    // Monitor form interaction times
    const form = document.querySelector('form');
    let formStartTime;
    
    form.addEventListener('focusin', function() {
        if (!formStartTime) {
            formStartTime = performance.now();
        }
    }, { once: true });
    
    form.addEventListener('submit', function() {
        if (formStartTime) {
            const completionTime = performance.now() - formStartTime;
            console.log(`Form completed in ${(completionTime / 1000).toFixed(2)} seconds`);
        }
    });
}

// Initialize performance monitoring
monitorPerformance();
</script>
@endpush
