@extends('admin.layouts.app')

@section('main')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Laporan Penjualan Kolaborasi</h1>

        <div class="mb-4">
            <a href="{{ route('penjualanKolaborasi.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Laporan
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4 dark:bg-green-700 dark:text-white">
            {{ session('success') }}
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border dark:text-white">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="p-2 text-left">Judul</th>
                        <th class="p-2 text-left">Penulis</th>
                        <th class="p-2 text-left">Bab</th>
                        <th class="p-2 text-left">Tanggal</th>
                        <th class="p-2 text-left">Status</th>
                        <th class="p-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporan as $item)
                    <tr class="border-t dark:border-gray-600">
                        <td class="p-2">{{ $item->judul }}</td>
                        <td class="p-2">{{ $item->penulis }}</td>
                        <td class="p-2">{{ $item->bab }}</td>
                        <td class="p-2">{{ $item->tanggal }}</td>
                        <td class="p-2">{{ ucfirst($item->status_pembayaran) }}</td>
                        <td class="p-2 flex items-center gap-2">
                            <a href="{{ route('penjualanKolaborasi.show', $item->id) }}" class="inline-flex items-center justify-center p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m0 0v-6m0 6v6" />
                                </svg>
                            </a>

                            <a href="{{ route('penjualanKolaborasi.edit', $item->id) }}" class="inline-flex items-center justify-center p-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-8.38 8.38H5v-3.035l8.586-8.586z" />
                                </svg>
                            </a>

                            <form action="{{ route('penjualanKolaborasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="inline-flex items-center justify-center p-2 bg-red-500 text-white rounded hover:bg-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1zM9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2h12a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 dark:text-gray-400 py-4">Belum ada laporan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
