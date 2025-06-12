@extends('admin.layouts.app')

@section('main')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Kelola Template Penulisan</h1>
            <a href="{{ route('template.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i data-lucide="plus" class="w-4 h-4 inline mr-2"></i>Upload Template
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Judul</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">File</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ukuran</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Diupload</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($templates as $index => $template)
                    <tr>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $template->judul }}</div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                {{ $template->deskripsi ?? '-' }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i data-lucide="file-text" class="w-4 h-4 text-red-500 mr-2"></i>
                                <span class="text-sm text-gray-900 dark:text-gray-100">{{ $template->file_name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $template->file_size_formatted }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <form action="{{ route('template.toggle-status', $template->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2 py-1 text-xs rounded-full {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $template->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            <div>{{ $template->uploader->name ?? 'Unknown' }}</div>
                            <div class="text-xs">{{ $template->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('template.show', $template->id) }}" class="text-blue-600 hover:text-blue-900">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('template.download', $template->id) }}" class="text-green-600 hover:text-green-900">
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('template.edit', $template->id) }}" class="text-yellow-600 hover:text-yellow-900">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('template.destroy', $template->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus template ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <i data-lucide="file-x" class="w-12 h-12 mb-4 text-gray-300"></i>
                                <p>Belum ada template yang diupload</p>
                                <a href="{{ route('template.create') }}" class="mt-2 text-blue-600 hover:text-blue-800">
                                    Upload template pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
lucide.createIcons();
</script>
@endsection
