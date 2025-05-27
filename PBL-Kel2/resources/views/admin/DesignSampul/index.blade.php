@extends('admin.layouts.app')

@section('main')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center text-blue-700">Design Sampul</h1>

    <div class="flex justify-end mb-4">
        <a href="{{ route('DesignSampul.create') }}"
           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            + Tambah Data
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded shadow">
            <thead class="bg-blue-100 text-blue-800">
                <tr>
                    <th class="py-2 px-4 border">No</th>
                    <th class="py-2 px-4 border">Nama Proyek</th>
                    <th class="py-2 px-4 border">Jenis Design</th>
                    <th class="py-2 px-4 border">Editor</th>
                    <th class="py-2 px-4 border">Tanggal Kirim</th>
                    <th class="py-2 px-4 border">Sampul</th>
                    <th class="py-2 px-4 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($DesignSampul as $item)
                <tr>
                    <td class="py-2 px-4 border text-center">{{ $loop->iteration }}</td>
                    <td class="py-2 px-4 border">{{ $item->nama_proyek }}</td>
                    <td class="py-2 px-4 border">{{ $item->jenis_design }}</td>
                    <td class="py-2 px-4 border">{{ $item->editor }}</td>
                    <td class="py-2 px-4 border">{{ \Carbon\Carbon::parse($item->tanggal_kirim)->format('d-m-Y') }}</td>
                    <td class="py-2 px-4 border">
                        @if ($item->sampul_url)
                            <img src="{{ $item->sampul_url }}"
                                 alt="Sampul"
                                 class="w-20 h-20 object-cover rounded">
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="py-2 px-4 border">
                        <div class="flex gap-3">
                            <a href="{{ route('DesignSampul.edit', $item->id) }}"
                               class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('DesignSampul.destroy', $item->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7"
                        class="py-4 px-4 text-center text-gray-500">
                        Data belum tersedia.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
