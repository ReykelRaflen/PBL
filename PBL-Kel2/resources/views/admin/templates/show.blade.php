@extends('admin.layouts.app')

@section('main')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detail Template</h1>
            <div class="flex space-x-2">
                <a href="{{ route('template.edit', $template->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
                    <i data-lucide="edit" class="w-4 h-4 inline mr-2"></i>Edit
                </a>
                <a href="{{ route('template.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    <i data-lucide="arrow-left" class="w-4 h-4 inline mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Judul Template</label>
                    <p class="text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">{{ $template->judul }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                    <p class="text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg min-h-[100px]">
                        {{ $template->deskripsi ?? 'Tidak ada deskripsi' }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <span class="px-3 py-1 text-sm rounded-full {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $template->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Informasi File</label>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg space-y-2">
                        <div class="flex items-center">
                            <i data-lucide="file-text" class="w-5 h-5 text-red-500 mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $template->file_name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $template->file_size_formatted }}</p>
                            </div>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('template.download', $template->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                                <i data-lucide="download" class="w-4 h-4 mr-2"></i>Download File
                            </a>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Informasi Upload</label>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Diupload oleh:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $template->uploader->name ?? 'Unknown' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Tanggal upload:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $template->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Terakhir diupdate:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $template->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-4">
            <form action="{{ route('template.toggle-status', $template->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 {{ $template->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg">
                    {{ $template->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>
            
            <form action="{{ route('template.destroy', $template->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus template ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    <i data-lucide="trash-2" class="w-4 h-4 inline mr-2"></i>Hapus Template
                </button>
            </form>
        </div>
    </div>
</div>

<script>
lucide.createIcons();
</script>
@endsection
