@extends('admin.layouts.app')

@section('title', 'Tambah Akun')

@section('main')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Akun Baru</h1>
        <a href="{{ route('admin.account.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali
        </a>
    </div>

    @if($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium">Terdapat kesalahan pada form:</h3>
                <ul class="list-disc list-inside mt-2 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <form action="{{ route('admin.account.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror" 
                                   placeholder="Masukkan nama lengkap"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror" 
                                   placeholder="Masukkan alamat email"
                                   required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('password') border-red-500 @enderror" 
                                   placeholder="Masukkan password"
                                   required>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" 
                                   placeholder="Ulangi password"
                                   required>
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select id="role" 
                                    name="role"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('role') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Role</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Aktif -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Akun Aktif</span>
                            </label>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <!-- Nomor Telepon -->
                        <div>
                            <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nomor Telepon
                            </label>
                            <input type="text" 
                                   id="nomor_telepon" 
                                   name="nomor_telepon" 
                                   value="{{ old('nomor_telepon') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('nomor_telepon') border-red-500 @enderror" 
                                   placeholder="Masukkan nomor telepon">
                            @error('nomor_telepon')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Lahir
                            </label>
                            <input type="date" 
                                   id="tanggal_lahir" 
                                   name="tanggal_lahir" 
                                   value="{{ old('tanggal_lahir') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('tanggal_lahir') border-red-500 @enderror">
                                                     @error('tanggal_lahir')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Jenis Kelamin
                            </label>
                            <select id="jenis_kelamin" 
                                    name="jenis_kelamin"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('jenis_kelamin') border-red-500 @enderror">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Alamat
                            </label>
                            <textarea id="alamat" 
                                      name="alamat" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('alamat') border-red-500 @enderror"
                                      placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload Foto Profil -->
                        <div>
                            <label for="foto_profil" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Foto Profil
                                <span class="text-xs text-gray-500 dark:text-gray-400">(JPG, JPEG, PNG - Max: 2MB)</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="foto_profil" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload foto</span>
                                            <input id="foto_profil" name="foto_profil" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, JPEG hingga 2MB</p>
                                </div>
                            </div>
                            @error('foto_profil')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror

                            <!-- Preview Foto -->
                            <div id="foto-preview" class="mt-3 hidden">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Preview Foto:</p>
                                <div class="relative inline-block">
                                    <img id="foto-image" src="#" alt="Preview Foto" class="w-32 h-32 object-cover rounded-lg shadow">
                                    <button type="button" id="remove-foto" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                        ×
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer dengan tombol aksi -->
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600 rounded-b-lg">
                <div class="flex items-center justify-between">
                    <div class="flex space-x-3">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg shadow font-medium transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Simpan Akun
                        </button>
                        
                        <a href="{{ route('admin.account.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg shadow font-medium transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Batal
                        </a>
                    </div>
                    
                    <div class="text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <span class="text-red-500">*</span> Field wajib diisi
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fotoInput = document.getElementById('foto_profil');
    const previewContainer = document.getElementById('foto-preview');
    const previewImage = document.getElementById('foto-image');
    const removeFotoBtn = document.getElementById('remove-foto');

    // Handle foto upload
    fotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('File harus berupa gambar (JPG, JPEG, PNG)');
                this.value = '';
                return;
            }

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file tidak boleh lebih dari 2MB');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove foto
    removeFotoBtn.addEventListener('click', function() {
        fotoInput.value = '';
        previewContainer.classList.add('hidden');
    });

    // Drag and drop functionality
    const dropZone = document.querySelector('[class*="border-dashed"]');
    
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fotoInput.files = files;
            fotoInput.dispatchEvent(new Event('change'));
        }
    });
});
</script>
@endpush
@endsection
