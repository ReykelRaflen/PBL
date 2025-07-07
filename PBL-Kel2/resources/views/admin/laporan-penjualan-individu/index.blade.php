@extends('admin.layouts.app')

@section('main')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Laporan Penjualan Individu</h1>
            <a href="{{ route('admin.laporan-penjualan-individu.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Laporan
            </a>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
            <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                <div class="text-blue-600 dark:text-blue-300 text-sm font-medium">Total</div>
                <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $statistik['total'] }}</div>
            </div>
            <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg">
                <div class="text-yellow-600 dark:text-yellow-300 text-sm font-medium">Menunggu Verifikasi</div>
                <div class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ $statistik['menunggu_verifikasi'] }}</div>
            </div>
            <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                <div class="text-green-600 dark:text-green-300 text-sm font-medium">Sukses</div>
                <div class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $statistik['sukses'] }}</div>
            </div>
            <div class="bg-red-50 dark:bg-red-900 p-4 rounded-lg">
                <div class="text-red-600 dark:text-red-300 text-sm font-medium">Tidak Sesuai</div>
                <div class="text-2xl font-bold text-red-900 dark:text-red-100">{{ $statistik['tidak_sesuai'] }}</div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <div class="text-gray-600 dark:text-gray-300 text-sm font-medium">Silver</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $statistik['silver'] }}</div>
            </div>
            <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg">
                <div class="text-yellow-600 dark:text-yellow-300 text-sm font-medium">Gold</div>
                <div class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ $statistik['gold'] }}</div>
            </div>
        </div>

        <!-- Filter -->
        <div class="mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select name="status_pembayaran" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">Semua Status</option>
                        <option value="menunggu_verifikasi" {{ request('status_pembayaran') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="sukses" {{ request('status_pembayaran') == 'sukses' ? 'selected' : '' }}>Sukses</option>
                        <option value="tidak_sesuai" {{ request('status_pembayaran') == 'tidak_sesuai' ? 'selected' : '' }}>Tidak Sesuai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Paket</label>
                    <select name="paket" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">Semua Paket</option>
                        <option value="silver" {{ request('paket') == 'silver' ? 'selected' : '' }}>Silver</option>
                        <option value="gold" {{ request('paket') == 'gold' ? 'selected' : '' }}>Gold</option>
                        <option value="diamond" {{ request('paket') == 'diamond' ? 'selected' : '' }}>Diamond</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Invoice, Judul, Penulis..." 
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 dark:bg-green-800 dark:text-green-100" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 dark:bg-red-800 dark:text-red-100" role="alert">
            <p>{{ session('error') }}</p>
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Invoice</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Judul</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Penulis</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Paket</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                      @forelse($laporans as $laporan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $laporan->invoice }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ Str::limit($laporan->judul, 30) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $laporan->penulis }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $laporan->paket_badge }} text-white">
                                {{ ucfirst($laporan->paket) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $laporan->harga_formatted }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $laporan->tanggal->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $laporan->status_badge }} text-white">
                                {{ $laporan->status_pembayaran_text }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.laporan-penjualan-individu.show', $laporan->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" 
                                   title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>

                                @if($laporan->bukti_pembayaran)
                                <a href="{{ route('admin.laporan-penjualan-individu.download', $laporan->id) }}" 
                                   class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" 
                                   title="Download Bukti">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                @endif

                                @if($laporan->isMenungguVerifikasi())
                                <!-- Tombol Setujui -->
                                <button onclick="openSetujuiModal({{ $laporan->id }}, '{{ $laporan->invoice }}')"
                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" 
                                        title="Setujui">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Tombol Tolak -->
                                <button onclick="openTolakModal({{ $laporan->id }}, '{{ $laporan->invoice }}')"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" 
                                        title="Tolak">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                @endif

                                <a href="{{ route('admin.laporan-penjualan-individu.edit', $laporan->id) }}" 
                                   class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300" 
                                   title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>

                                <form action="{{ route('admin.laporan-penjualan-individu.destroy', $laporan->id) }}" 
                                      method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" 
                                            title="Hapus"
                                            onclick="return confirm('Yakin ingin menghapus laporan ini?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400">
                            Belum ada data laporan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($laporans->hasPages())
        <div class="mt-6">
            {{ $laporans->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Setujui -->
<div id="setujuiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Setujui Pembayaran</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Yakin ingin menyetujui pembayaran untuk invoice <span id="setujuiInvoice" class="font-semibold"></span>?
                </p>
                <form id="setujuiForm" method="POST" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label for="setujuiCatatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea name="catatan_admin" id="setujuiCatatan" rows="3" 
                                  class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                  placeholder="Catatan tambahan..."></textarea>
                    </div>
                    <div class="flex justify-center space-x-3">
                        <button type="button" onclick="closeSetujuiModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div id="tolakModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Tolak Pembayaran</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Yakin ingin menolak pembayaran untuk invoice <span id="tolakInvoice" class="font-semibold"></span>?
                </p>
                <form id="tolakForm" method="POST" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label for="tolakCatatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="catatan_admin" id="tolakCatatan" rows="3" required
                                  class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                  placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>
                    <div class="flex justify-center space-x-3">
                        <button type="button" onclick="closeTolakModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openSetujuiModal(id, invoice) {
    document.getElementById('setujuiInvoice').textContent = invoice;
    document.getElementById('setujuiForm').action = `/admin/laporan-penjualan-individu/${id}/setujui`;
    document.getElementById('setujuiModal').classList.remove('hidden');
}

function closeSetujuiModal() {
    document.getElementById('setujuiModal').classList.add('hidden');
    document.getElementById('setujuiCatatan').value = '';
}

function openTolakModal(id, invoice) {
    document.getElementById('tolakInvoice').textContent = invoice;
        document.getElementById('tolakForm').action = `/admin/laporan-penjualan-individu/${id}/tolak`;
    document.getElementById('tolakModal').classList.remove('hidden');
}

function closeTolakModal() {
    document.getElementById('tolakModal').classList.add('hidden');
    document.getElementById('tolakCatatan').value = '';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const setujuiModal = document.getElementById('setujuiModal');
    const tolakModal = document.getElementById('tolakModal');
    
    if (event.target == setujuiModal) {
        closeSetujuiModal();
    }
    if (event.target == tolakModal) {
        closeTolakModal();
    }
}
</script>
@endsection

