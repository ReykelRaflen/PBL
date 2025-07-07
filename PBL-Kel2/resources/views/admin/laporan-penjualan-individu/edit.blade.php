@extends('admin.layouts.app')

@section('main')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Edit Laporan Penjualan Individu</h1>
            <a href="{{ route('admin.laporan-penjualan-individu.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 dark:bg-red-800 dark:text-red-100">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('admin.laporan-penjualan-individu.update', $laporan->id) }}" method="POST">
            @csrf
            @method('PUT')
            
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="penerbitan_individu_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Penerbitan Individu <span class="text-red-500">*</span>
                    </label>
                    <select name="penerbitan_individu_id" id="penerbitan_individu_id" required onchange="fillFromPenerbitan()"
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('penerbitan_individu_id') border-red-500 @enderror">
                        <option value="">Pilih Penerbitan</option>
                        @foreach($penerbitanList as $penerbitan)
                            <option value="{{ $penerbitan->id }}" 
                                    data-judul="{{ $penerbitan->judul_buku ?? 'Belum ada judul' }}"
                                    data-penulis="{{ $penerbitan->nama_penulis ?? $penerbitan->user->name }}"
                                    data-paket="{{ $penerbitan->paket }}"
                                    data-bukti="{{ $penerbitan->bukti_pembayaran }}"
                                    {{ old('penerbitan_individu_id', $laporan->penerbitan_individu_id) == $penerbitan->id ? 'selected' : '' }}>
                                {{ $penerbitan->nomor_pesanan }} - {{ $penerbitan->user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('penerbitan_individu_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Judul Buku <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul', $laporan->judul) }}" required
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('judul') border-red-500 @enderror">
                    @error('judul')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="penulis" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Penulis <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="penulis" id="penulis" value="{{ old('penulis', $laporan->penulis) }}" required
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('penulis') border-red-500 @enderror">
                    @error('penulis')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="paket" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Paket <span class="text-red-500">*</span>
                    </label>
                    <select name="paket" id="paket" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('paket') border-red-500 @enderror">
                        <option value="">Pilih Paket</option>
                        <option value="silver" {{ old('paket', $laporan->paket) == 'silver' ? 'selected' : '' }}>Silver</option>
                        <option value="gold" {{ old('paket', $laporan->paket) == 'gold' ? 'selected' : '' }}>Gold</option>
                        <option value="diamond" {{ old('paket', $laporan->paket) == 'diamond' ? 'selected' : '' }}>Diamond</option>
                    </select>
                    @error('paket')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status_pembayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <select name="status_pembayaran" id="status_pembayaran" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('status_pembayaran') border-red-500 @enderror">
                        <option value="">Pilih Status</option>
                        <option value="menunggu_verifikasi" {{ old('status_pembayaran', $laporan->status_pembayaran) == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="sukses" {{ old('status_pembayaran', $laporan->status_pembayaran) == 'sukses' ? 'selected' : '' }}>Sukses</option>
                        <option value="tidak_sesuai" {{ old('status_pembayaran', $laporan->status_pembayaran) == 'tidak_sesuai' ? 'selected' : '' }}>Tidak Sesuai</option>
                    </select>
                    @error('status_pembayaran')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $laporan->tanggal->format('Y-m-d')) }}" required
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('tanggal') border-red-500 @enderror">
                    @error('tanggal')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Bukti Pembayaran (Path File)
                </label>
                <input type="text" name="bukti_pembayaran" id="bukti_pembayaran" value="{{ old('bukti_pembayaran', $laporan->bukti_pembayaran) }}"
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white @error('bukti_pembayaran') border-red-500 @enderror"
                       placeholder="Contoh: bukti_pembayaran/file.jpg">
                @error('bukti_pembayaran')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada bukti pembayaran</p>
            </div>

            <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg">
                <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200 mb-2">Informasi:</h4>
                <ul class="text-xs text-yellow-700 dark:text-yellow-300 space-y-1">
                    <li>• Invoice: <strong>{{ $laporan->invoice }}</strong> (tidak dapat diubah)</li>
                    <li>• Data dapat diperbarui sesuai kebutuhan</li>
                    <li>• Perubahan status akan mempengaruhi status penerbitan terkait</li>
                </ul>
            </div>

            <div class="flex items-center justify-end mt-6 space-x-3">
                <a href="{{ route('admin.laporan-penjualan-individu.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function fillFromPenerbitan() {
    const select = document.getElementById('penerbitan_individu_id');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        document.getElementById('judul').value = selectedOption.dataset.judul;
        document.getElementById('penulis').value = selectedOption.dataset.penulis;
        document.getElementById('paket').value = selectedOption.dataset.paket;
        document.getElementById('bukti_pembayaran').value = selectedOption.dataset.bukti || '';
    }
}
</script>
@endsection
