@extends('admin.layouts.app')

@section('main')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6 text-center">Edit Design Sampul</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('DesignSampul.update', $DesignSampul->id) }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf
        @method('PUT')

        {{-- nama proyek --}}
        <div>
            <label class="block font-medium mb-2">Nama Proyek</label>
            <input type="text" name="nama_proyek" maxlength="100"
                   value="{{ old('nama_proyek', $DesignSampul->nama_proyek) }}"
                   class="w-full p-2 border rounded" required>
        </div>

        {{-- jenis design --}}
        <div>
            <label class="block font-medium mb-2">Jenis Design</label>
            <input type="text" name="jenis_design" maxlength="255"
                   value="{{ old('jenis_design', $DesignSampul->jenis_design) }}"
                   class="w-full p-2 border rounded" required>
        </div>

        {{-- editor --}}
        <div>
            <label class="block font-medium mb-2">Editor</label>
            <input type="text" name="editor" maxlength="100"
                   value="{{ old('editor', $DesignSampul->editor) }}"
                   class="w-full p-2 border rounded" required>
        </div>

        {{-- tanggal --}}
        <div>
            <label class="block font-medium mb-2">Tanggal Kirim</label>
            <input type="date" name="tanggal_kirim"
                   value="{{ old('tanggal_kirim', $DesignSampul->tanggal_kirim->format('Y-m-d')) }}"
                   class="w-full p-2 border rounded" required>
        </div>

        {{-- ganti foto --}}
        <div>
            <label class="block font-medium mb-2">Sampul Baru (opsional)</label>
            <input type="file" name="sampul" accept="image/*" class="w-full p-2 border rounded">
            <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti.</p>

            @if ($DesignSampul->sampul_url)
                <img src="{{ $DesignSampul->sampul_url }}"
                     alt="Sampul lama"
                     class="w-32 h-32 object-cover rounded mt-4">
            @endif
        </div>

        <button type="submit"
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Perbarui
        </button>
    </form>
</div>
@endsection
