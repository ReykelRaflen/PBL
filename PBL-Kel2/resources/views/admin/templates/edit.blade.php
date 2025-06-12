@extends('admin.layouts.app')

@section('main')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Template</h1>
            <div class="flex space-x-2">
                <a href="{{ route('template.show', $template->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    <i data-lucide="eye" class="w-4 h-4 inline mr-2"></i>Lihat Detail
                </a>
                <a href="{{ route('template.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    <i data-lucide="arrow-left" class="w-4 h-4 inline mr-2"></i>Kembali
                </a>
            </div>
        </div>

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

        <form action="{{ route('template.update', $template->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Judul Template <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul', $template->judul) }}" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                           placeholder="Masukkan judul template" required>
                    @error('judul')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Deskripsi
                    </label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                              placeholder="Masukkan deskripsi template (opsional)">{{ old('deskripsi', $template->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        File Saat Ini
                    </label>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i data-lucide="file-text" class="w-5 h-5 text-red-500 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $template->file_name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $template->file_size_formatted }}</p>
                                </div>
                            </div>
                            <a href="{{ route('template.download', $template->id) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                <i data-lucide="download" class="w-4 h-4 inline mr-1"></i>Download
                            </a>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Ganti File PDF (Opsional)
                    </label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center">
                        <input type="file" id="file" name="file" accept=".pdf" 
                               class="hidden" onchange="updateFileName(this)">
                        <label for="file" class="cursor-pointer">
                            <i data-lucide="upload" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
                            <p class="text-gray-600 dark:text-gray-400 mb-2">
                                Klik untuk memilih file PDF baru atau drag & drop
                            </p>
                            <p class="text-sm text-gray-500">Maksimal ukuran file: 10MB</p>
                            <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti file</p>
                            <p id="fileName" class="text-sm text-blue-600 mt-2 hidden"></p>
                        </label>
                    </div>
                    @error('file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="window.history.back()" 
                            class="px-6 py-2 border border-gray-300 text-gray-700 dark:text-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i data-lucide="save" class="w-4 h-4 inline mr-2"></i>Update Template
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function updateFileName(input) {
    const fileName = document.getElementById('fileName');
    if (input.files && input.files[0]) {
        fileName.textContent = 'File baru dipilih: ' + input.files[0].name;
        fileName.classList.remove('hidden');
    } else {
        fileName.classList.add('hidden');
    }
}

lucide.createIcons();
</script>
@endsection
