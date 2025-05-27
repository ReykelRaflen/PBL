@extends('admin.layouts.app')

@section('main')
<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">
            Tambah Design Sampul
        </h2>

        {{-- error --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        {{-- enctype wajib --}}
        <form action="{{ route('DesignSampul.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- nama proyek --}}
                <div>
                    <label class="block text-sm font-medium mb-2">Nama Proyek</label>
                    <input type="text" name="nama_proyek" value="{{ old('nama_proyek') }}"
                           class="w-full p-2 border rounded-md"
                           maxlength="100" required>
                </div>

                {{-- jenis design --}}
                <div>
                    <label class="block text-sm font-medium mb-2">Jenis Design</label>
                    <input type="text" name="jenis_design" value="{{ old('jenis_design') }}"
                           class="w-full p-2 border rounded-md" maxlength="255" required>
                </div>

                {{-- editor --}}
                <div>
                    <label class="block text-sm font-medium mb-2">Editor</label>
                    <input type="text" name="editor" value="{{ old('editor') }}"
                           class="w-full p-2 border rounded-md" maxlength="100" required>
                </div>

                {{-- tanggal --}}
                <div>
                    <label class="block text-sm font-medium mb-2">Tanggal Kirim</label>
                    <input type="date" name="tanggal_kirim" value="{{ old('tanggal_kirim') }}"
                           class="w-full p-2 border rounded-md" required>
                </div>

                {{-- foto --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Sampul (JPG/PNG)</label>
                    <input type="file" name="sampul"
                           accept="image/*"
                           class="w-full p-2 border rounded-md" required>
                </div>
            </div>

            {{-- tombol --}}
            <div class="flex justify-end space-x-4">
                <a href="{{ route('DesignSampul.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Batal</a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
