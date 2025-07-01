@extends('admin.layouts.app')

@section('main')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Rekening Baru</h1>
        <a href="{{ route('rekening.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">
            Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form action="{{ route('rekening.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="bank" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bank</label>
                    <input type="text" name="bank" id="bank" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                </div>

                <div>
                    <label for="nomor_rekening" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Rekening</label>
                    <input type="text" name="nomor_rekening" id="nomor_rekening" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                </div>
            </div>

            <div class="mt-6">
                <label for="nama_pemilik" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Pemilik</label>
                <input type="text" name="nama_pemilik" id="nama_pemilik" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow">
                    Simpan Rekening
                </button>
            </div>
        </form>
    </div>
</div>    
@endsection   