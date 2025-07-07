@extends('admin.layouts.app')

@section('main')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Manajemen Naskah Kolaborasi</h1>

            <!-- Statistik Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-blue-500 text-white p-4 rounded-lg cursor-pointer transform transition-all duration-200 hover:scale-105 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $statistik['total'] }}</h3>
                            <p class="text-sm opacity-90">Total Naskah</p>
                        </div>
                        <i class="fas fa-file-alt text-2xl opacity-75"></i>
                    </div>
                </div>

                <div class="bg-yellow-500 text-white p-4 rounded-lg cursor-pointer transform transition-all duration-200 hover:scale-105 hover:shadow-lg" onclick="filterByStatus('sudah_kirim')">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $statistik['perlu_review'] }}</h3>
                            <p class="text-sm opacity-90">Perlu Review</p>
                        </div>
                        <i class="fas fa-clock text-2xl opacity-75"></i>
                    </div>
                </div>

                <div class="bg-orange-500 text-white p-4 rounded-lg cursor-pointer transform transition-all duration-200 hover:scale-105 hover:shadow-lg" onclick="filterByStatus('revisi')">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $statistik['revisi'] }}</h3>
                            <p class="text-sm opacity-90">Perlu Revisi</p>
                        </div>
                        <i class="fas fa-edit text-2xl opacity-75"></i>
                    </div>
                </div>

                <div class="bg-green-500 text-white p-4 rounded-lg cursor-pointer transform transition-all duration-200 hover:scale-105 hover:shadow-lg" onclick="filterByStatus('disetujui')">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $statistik['selesai'] }}</h3>
                            <p class="text-sm opacity-90">Selesai</p>
                        </div>
                        <i class="fas fa-check-circle text-2xl opacity-75"></i>
                    </div>
                </div>

                <div class="bg-red-500 text-white p-4 rounded-lg cursor-pointer transform transition-all duration-200 hover:scale-105 hover:shadow-lg" onclick="filterByStatus('ditolak')">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $statistik['ditolak'] }}</h3>
                            <p class="text-sm opacity-90">Ditolak</p>
                        </div>
                        <i class="fas fa-times-circle text-2xl opacity-75"></i>
                    </div>
                </div>
            </div>

            <!-- Filter Form -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                <form method="GET" action="{{ route('naskahKolaborasi.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Cari</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-blue-400"
                                placeholder="Judul, penulis, nomor...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Status</label>
                            <select name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-blue-400">
                                <option value="">Semua Status</option>
                                <option value="dapat_mulai" {{ request('status') == 'dapat_mulai' ? 'selected' : '' }}>
                                    Dapat Mulai
                                </option>
                                <option value="sedang_ditulis" {{ request('status') == 'sedang_ditulis' ? 'selected' : '' }}>
                                    Sedang Ditulis
                                </option>
                                <option value="sudah_kirim" {{ request('status') == 'sudah_kirim' ? 'selected' : '' }}>
                                    Perlu Review
                                </option>
                                <option value="revisi" {{ request('status') == 'revisi' ? 'selected' : '' }}>
                                    Perlu Revisi
                                </option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>
                                    Disetujui
                                </option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>
                                    Selesai
                                </option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>
                                    Ditolak
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-blue-400">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-blue-400">
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                <i class="fas fa-search mr-1"></i>Filter
                            </button>
                            <a href="{{ route('naskahKolaborasi.index') }}"
                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tombol Tambah -->
            <div class="mb-4">
                <a href="{{ route('naskahKolaborasi.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Tambah Naskah
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-4 dark:bg-green-800 dark:border-green-600 dark:text-green-200">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4 dark:bg-red-800 dark:border-red-600 dark:text-red-200">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="overflow-x-auto shadow-sm border border-gray-200 dark:border-gray-700 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pesanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Buku & Bab</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Penulis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Naskah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Upload</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($laporans as $laporan)
                            @php
                                // Function untuk mendapatkan badge status
                                $getStatusBadge = function($status) {
                                    return match($status) {
                                        'belum_mulai' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        'dapat_mulai' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'sedang_ditulis' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'sudah_kirim' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                        'revisi' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
                                        'disetujui' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'selesai' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'ditolak' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                    };
                                };

                                // Function untuk mendapatkan text status
                                $getStatusText = function($status) {
                                    return match($status) {
                                        'belum_mulai' => 'Belum Mulai',
                                        'dapat_mulai' => 'Dapat Mulai',
                                        'sedang_ditulis' => 'Sedang Ditulis',
                                        'sudah_kirim' => 'Sudah Dikirim',
                                        'revisi' => 'Perlu Revisi',
                                        'disetujui' => 'Disetujui',
                                        'selesai' => 'Selesai',
                                        'ditolak' => 'Ditolak',
                                        default => ucfirst(str_replace('_', ' ', $status))
                                    };
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150 {{ $laporan->status_penulisan === 'sudah_kirim' ? 'bg-purple-50 dark:bg-purple-900 dark:bg-opacity-20 border-l-4 border-purple-500' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                                                               <div class="text-sm font-medium text-blue-600 dark:text-blue-400">{{ $laporan->nomor_pesanan }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $laporan->id }}</div>
                                        @if($laporan->tanggal_disetujui)
                                            <div class="text-xs text-green-600 dark:text-green-400 flex items-center mt-1">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Disetujui: {{ $laporan->tanggal_disetujui->format('d/m/Y') }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col space-y-1">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $laporan->bukuKolaboratif->judul ?? 'Judul Tidak Tersedia' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Bab {{ $laporan->babBuku->nomor_bab ?? '-' }}:
                                            {{ $laporan->babBuku->judul_bab ?? 'Judul Bab Tidak Tersedia' }}
                                        </div>
                                        @if($laporan->babBuku && $laporan->babBuku->tingkat_kesulitan)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium w-fit
                                                {{ $laporan->babBuku->tingkat_kesulitan === 'mudah' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' :
                                                   ($laporan->babBuku->tingkat_kesulitan === 'sedang' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300') }}">
                                                {{ ucfirst($laporan->babBuku->tingkat_kesulitan) }}
                                            </span>
                                        @endif
                                        @if($laporan->babBuku)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Status Bab: 
                                                <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $laporan->babBuku->status)) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col space-y-1">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $laporan->user->name ?? 'Penulis Tidak Ditemukan' }}
                                        </div>
                                        @if($laporan->user)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $laporan->user->email }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Role: <span class="capitalize">{{ $laporan->user->role }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col space-y-1">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $laporan->judul_naskah ?? 'Belum Ada Judul' }}
                                        </div>
                                        @if($laporan->jumlah_kata)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ number_format($laporan->jumlah_kata, 0, ',', '.') }} kata
                                            </div>
                                        @endif
                                        @if($laporan->deskripsi_naskah)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ Str::limit($laporan->deskripsi_naskah, 50) }}
                                            </div>
                                        @endif
                                        @if($laporan->file_naskah)
                                            <div class="text-xs text-blue-600 dark:text-blue-400 flex items-center">
                                                <i class="fas fa-file mr-1"></i>File tersedia
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        @if($laporan->tanggal_upload_naskah)
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $laporan->tanggal_upload_naskah->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $laporan->tanggal_upload_naskah->format('H:i') }}
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400 dark:text-gray-500">Belum diupload</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $getStatusBadge($laporan->status_penulisan) }} transition-all duration-200 hover:scale-105">
                                            @switch($laporan->status_penulisan)
                                                @case('belum_mulai')
                                                    <i class="fas fa-pause-circle mr-1"></i>
                                                    @break
                                                @case('dapat_mulai')
                                                    <i class="fas fa-play-circle mr-1"></i>
                                                    @break
                                                @case('sedang_ditulis')
                                                    <i class="fas fa-pen mr-1"></i>
                                                    @break
                                                @case('sudah_kirim')
                                                    <i class="fas fa-paper-plane mr-1 animate-pulse"></i>
                                                    @break
                                                @case('revisi')
                                                    <i class="fas fa-edit mr-1"></i>
                                                    @break
                                                @case('disetujui')
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    @break
                                                @case('selesai')
                                                    <i class="fas fa-flag-checkered mr-1"></i>
                                                    @break
                                                @case('ditolak')
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    @break
                                                @default
                                                    <i class="fas fa-question-circle mr-1"></i>
                                            @endswitch
                                            {{ $getStatusText($laporan->status_penulisan) }}
                                        </span>
                                        @if($laporan->tanggal_feedback)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Feedback: {{ $laporan->tanggal_feedback->format('d/m/Y') }}
                                            </div>
                                        @endif
                                        @if($laporan->status_penulisan === 'disetujui' && $laporan->tanggal_disetujui)
                                            <div class="text-xs text-green-600 dark:text-green-400 flex items-center">
                                                <i class="fas fa-calendar-check mr-1"></i>
                                                {{ $laporan->tanggal_disetujui->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-1 flex-wrap gap-1">
                                        <!-- Tombol Lihat Detail -->
                                        <a href="{{ route('naskahKolaborasi.show', $laporan->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-110"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>

                                        <!-- Tombol Edit -->
                                        <a href="{{ route('naskahKolaborasi.edit', $laporan->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-110"
                                            title="Edit Naskah">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>

                                        <!-- Tombol Download Naskah -->
                                        @if($laporan->file_naskah)
                                            <a href="{{ route('naskahKolaborasi.download', $laporan->id) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-purple-500 text-white rounded-md hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-110"
                                                title="Download Naskah">
                                                <i class="fas fa-download text-sm"></i>
                                            </a>
                                        @endif

                                        <!-- Tombol Aksi Review (hanya untuk status sudah_kirim) -->
                                        @if($laporan->status_penulisan === 'sudah_kirim')
                                            <!-- Terima -->
                                            <button type="button"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-110"
                                                title="Terima Naskah" onclick="openAcceptModal({{ $laporan->id }})">
                                                <i class="fas fa-check text-sm"></i>
                                            </button>

                                            <!-- Revisi -->
                                            <button type="button"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-orange-500 text-white rounded-md hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-110"
                                                title="Minta Revisi" onclick="openRevisionModal({{ $laporan->id }})">
                                                <i class="fas fa-redo text-sm"></i>
                                            </button>

                                            <!-- Tolak -->
                                            <button type="button"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-110"
                                                title="Tolak Naskah" onclick="openRejectModal({{ $laporan->id }})">
                                                <i class="fas fa-times text-sm"></i>
                                            </button>
                                        @endif

                                        <!-- Status Badge untuk yang sudah disetujui -->
                                        @if($laporan->status_penulisan === 'disetujui')
                                            <span class="inline-flex items-center justify-center w-8 h-8 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 rounded-md border border-green-200 dark:border-green-700"
                                                title="Naskah Disetujui">
                                                <i class="fas fa-medal text-sm"></i>
                                            </span>
                                        @endif

                                        <!-- Tombol Delete -->
                                        <form action="{{ route('naskahKolaborasi.destroy', $laporan->id) }}" method="POST"
                                            class="inline" onsubmit="return confirmDelete('{{ $laporan->nomor_pesanan }}', '{{ $laporan->status_penulisan }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-110"
                                                title="Hapus Naskah">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                            <i class="fas fa-file-alt text-2xl text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <div class="text-gray-500 dark:text-gray-400">
                                            <p class="text-lg font-medium">Belum ada naskah yang diupload</p>
                                                                                  <p class="text-sm mt-1">Klik "Tambah Naskah" untuk menambahkan naskah baru</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($laporans->hasPages())
                <div class="mt-6">
                    {{ $laporans->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Terima Naskah -->
    <div id="acceptModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
        <div class="relative top-20 mx-auto p-0 border-0 w-full max-w-md">
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all duration-300 scale-95 opacity-0" id="acceptModalContent">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-green-600 dark:text-green-400"></i>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">Terima Naskah</h3>
                        <button type="button" onclick="closeAcceptModal()" class="ml-auto text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <form id="acceptForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="catatan_persetujuan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Catatan Persetujuan (Opsional)
                            </label>
                            <textarea name="catatan_persetujuan" id="catatan_persetujuan" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white resize-none transition-all duration-200"
                                placeholder="Catatan untuk penulis (opsional)..."></textarea>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeAcceptModal()"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                                <i class="fas fa-times mr-1"></i>Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200">
                                <i class="fas fa-check mr-1"></i>Terima Naskah
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Revisi Naskah -->
    <div id="revisionModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
        <div class="relative top-20 mx-auto p-0 border-0 w-full max-w-md">
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all duration-300 scale-95 opacity-0" id="revisionModalContent">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center">
                            <i class="fas fa-redo text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">Minta Revisi</h3>
                        <button type="button" onclick="closeRevisionModal()" class="ml-auto text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <form id="revisionForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="feedback_editor_revision"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Feedback untuk Revisi *
                            </label>
                            <textarea name="feedback_editor" id="feedback_editor_revision" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent dark:bg-gray-700 dark:text-white resize-none transition-all duration-200"
                                placeholder="Jelaskan apa yang perlu direvisi..." required></textarea>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeRevisionModal()"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                                <i class="fas fa-times mr-1"></i>Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-colors duration-200">
                                <i class="fas fa-redo mr-1"></i>Kirim Revisi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tolak Naskah -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
        <div class="relative top-20 mx-auto p-0 border-0 w-full max-w-md">
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl transform transition-all duration-300 scale-95 opacity-0" id="rejectModalContent">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                            <i class="fas fa-times text-red-600 dark:text-red-400"></i>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">Tolak Naskah</h3>
                        <button type="button" onclick="closeRejectModal()" class="ml-auto text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <form id="rejectForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="feedback_editor_reject"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Alasan Penolakan *
                            </label>
                            <textarea name="feedback_editor" id="feedback_editor_reject" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white resize-none transition-all duration-200"
                                placeholder="Jelaskan alasan penolakan naskah..." required></textarea>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeRejectModal()"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                                <i class="fas fa-times mr-1"></i>Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
                                <i class="fas fa-ban mr-1"></i>Tolak Naskah
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center hidden z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl">
            <div class="flex items-center space-x-3">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="text-gray-700 dark:text-gray-300 font-medium">Memproses...</span>
            </div>
        </div>
    </div>

    <!-- Floating Action Buttons -->
    <div class="fixed bottom-6 right-6 flex flex-col space-y-2 z-40">
        <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                class="w-12 h-12 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-110 flex items-center justify-center"
                title="Scroll to Top">
            <i class="fas fa-arrow-up"></i>
        </button>
        
        <button onclick="printTable()" 
                class="w-12 h-12 bg-gray-600 text-white rounded-full shadow-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-110 flex items-center justify-center"
                title="Print Table">
            <i class="fas fa-print"></i>
        </button>
        
        <button onclick="exportToCSV()" 
                class="w-12 h-12 bg-green-600 text-white rounded-full shadow-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-110 flex items-center justify-center"
                title="Export to CSV">
            <i class="fas fa-download"></i>
        </button>
    </div>

    <!-- Notification Toast -->
    <div id="notification" class="fixed top-4 right-4 max-w-sm w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden transform transition-all duration-300 translate-x-full opacity-0 z-50">
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i id="notificationIcon" class="fas fa-info-circle text-blue-500"></i>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p id="notificationTitle" class="text-sm font-medium text-gray-900 dark:text-white"></p>
                    <p id="notificationMessage" class="mt-1 text-sm text-gray-500 dark:text-gray-400"></p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button onclick="hideNotification()" class="bg-white dark:bg-gray-800 rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal Animation Functions
        function showModal(modalId) {
            const modal = document.getElementById(modalId);
            const content = document.getElementById(modalId + 'Content');
            
            modal.classList.remove('hidden');
            
            // Trigger animation
            setTimeout(() => {
                modal.classList.add('opacity-100');
                content.classList.remove('scale-95', 'opacity-0');
                              content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function hideModal(modalId) {
            const modal = document.getElementById(modalId);
            const content = document.getElementById(modalId + 'Content');
            
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            modal.classList.remove('opacity-100');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Modal Functions
        function openAcceptModal(id) {
            document.getElementById('acceptForm').action = `/admin/naskah-kolaborasi/${id}/terima`;
            showModal('acceptModal');
        }

        function closeAcceptModal() {
            hideModal('acceptModal');
            document.getElementById('catatan_persetujuan').value = '';
        }

        function openRevisionModal(id) {
            document.getElementById('revisionForm').action = `/admin/naskah-kolaborasi/${id}/revisi`;
            showModal('revisionModal');
        }

        function closeRevisionModal() {
            hideModal('revisionModal');
            document.getElementById('feedback_editor_revision').value = '';
        }

        function openRejectModal(id) {
            document.getElementById('rejectForm').action = `/admin/naskah-kolaborasi/${id}/tolak`;
            showModal('rejectModal');
        }

        function closeRejectModal() {
            hideModal('rejectModal');
            document.getElementById('feedback_editor_reject').value = '';
        }

        // Loading Functions
        function showLoading() {
            document.getElementById('loadingOverlay').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        }

        // Notification Functions
        function showNotification(title, message, type = 'info') {
            const notification = document.getElementById('notification');
            const icon = document.getElementById('notificationIcon');
            const titleEl = document.getElementById('notificationTitle');
            const messageEl = document.getElementById('notificationMessage');
            
            // Set content
            titleEl.textContent = title;
            messageEl.textContent = message;
            
            // Set icon and colors based on type
            const iconClasses = {
                success: 'fas fa-check-circle text-green-500',
                error: 'fas fa-exclamation-circle text-red-500',
                warning: 'fas fa-exclamation-triangle text-yellow-500',
                info: 'fas fa-info-circle text-blue-500'
            };
            
            icon.className = iconClasses[type] || iconClasses.info;
            
            // Show notification with animation
            notification.classList.remove('translate-x-full', 'opacity-0');
            notification.classList.add('translate-x-0', 'opacity-100');
            
            // Auto hide after 5 seconds
            setTimeout(hideNotification, 5000);
        }

        function hideNotification() {
            const notification = document.getElementById('notification');
            notification.classList.remove('translate-x-0', 'opacity-100');
            notification.classList.add('translate-x-full', 'opacity-0');
        }

        // Enhanced delete confirmation
        function confirmDelete(nomorPesanan, status) {
            let message = `Yakin ingin menghapus naskah "${nomorPesanan}"?`;
            
            if (status === 'disetujui') {
                message += '\n\n⚠️ PERHATIAN: Naskah ini sudah disetujui!\nMenghapusnya akan mengembalikan status bab ke "tersedia".';
            } else if (status === 'sudah_kirim') {
                message += '\n\n📋 INFO: Naskah ini sedang menunggu review.';
            } else if (status === 'revisi') {
                message += '\n\n🔄 INFO: Naskah ini sedang dalam proses revisi.';
            }
            
            return confirm(message);
        }

        // Filter by status function
        function filterByStatus(status) {
            const statusSelect = document.querySelector('select[name="status"]');
            statusSelect.value = status;
            statusSelect.closest('form').submit();
        }

        // Print functionality
        function printTable() {
            const printContent = document.querySelector('.overflow-x-auto').innerHTML;
            const printWindow = window.open('', '_blank');
            
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>Laporan Naskah Kolaborasi</title>
                        <style>
                            body { 
                                font-family: Arial, sans-serif; 
                                margin: 20px;
                                color: #333;
                            }
                            h1 {
                                color: #1f2937;
                                border-bottom: 2px solid #e5e7eb;
                                padding-bottom: 10px;
                            }
                            table { 
                                width: 100%; 
                                border-collapse: collapse; 
                                margin-top: 20px;
                            }
                            th, td { 
                                border: 1px solid #d1d5db; 
                                padding: 8px; 
                                text-align: left; 
                                font-size: 12px;
                            }
                            th { 
                                background-color: #f3f4f6; 
                                font-weight: bold;
                            }
                            .bg-green-100 { background-color: #dcfce7 !important; }
                            .bg-red-100 { background-color: #fee2e2 !important; }
                            .bg-yellow-100 { background-color: #fef3c7 !important; }
                            .bg-blue-100 { background-color: #dbeafe !important; }
                            .bg-purple-100 { background-color: #e9d5ff !important; }
                            .bg-orange-100 { background-color: #fed7aa !important; }
                            .text-sm { font-size: 12px; }
                            .text-xs { font-size: 10px; }
                            .font-medium { font-weight: 500; }
                            .print-info {
                                margin-bottom: 20px;
                                padding: 10px;
                                background-color: #f9fafb;
                                border-radius: 5px;
                            }
                        </style>
                    </head>
                    <body>
                        <h1>Laporan Naskah Kolaborasi</h1>
                        <div class="print-info">
                            <p><strong>Dicetak pada:</strong> ${new Date().toLocaleString('id-ID')}</p>
                            <p><strong>Total Naskah:</strong> {{ $statistik['total'] }}</p>
                        </div>
                        ${printContent}
                    </body>
                </html>
            `);
            
            printWindow.document.close();
            printWindow.print();
        }

        // Export to CSV functionality
        function exportToCSV() {
            const table = document.querySelector('table');
            const rows = table.querySelectorAll('tr');
            let csv = [];
            
            // Header row
            const headers = ['Nomor Pesanan', 'Buku', 'Bab', 'Penulis', 'Email', 'Judul Naskah', 'Jumlah Kata', 'Status', 'Tanggal Upload'];
            csv.push(headers.map(h => `"${h}"`).join(','));
            
            // Data rows
            rows.forEach((row, index) => {
                if (index === 0) return; // Skip header row
                
                const cells = row.querySelectorAll('td');
                if (cells.length === 0) return; // Skip empty rows
                
                const rowData = [];
                cells.forEach((cell, cellIndex) => {
                    let text = cell.textContent.trim();
                    text = text.replace(/\s+/g, ' '); // Replace multiple spaces
                    text = text.replace(/"/g, '""'); // Escape quotes
                    
                    // Skip action column
                    if (cellIndex < 6) {
                        rowData.push(`"${text}"`);
                    }
                });
                
                if (rowData.length > 0) {
                    csv.push(rowData.join(','));
                }
            });
            
            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            
            if (link.download !== undefined) {
                const url = URL.createObjectURL(blob);
                link.setAttribute('href', url);
                link.setAttribute('download', `naskah_kolaborasi_${new Date().toISOString().split('T')[0]}.csv`);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading to modal forms
            document.getElementById('acceptForm').addEventListener('submit', function() {
                showLoading();
                closeAcceptModal();
            });
            
            document.getElementById('revisionForm').addEventListener('submit', function() {
                showLoading();
                closeRevisionModal();
            });
            
            document.getElementById('rejectForm').addEventListener('submit', function() {
                showLoading();
                closeRejectModal();
            });

            // Auto-hide loading on page load
            hideLoading();

            // Show notification if there are session messages
            @if(session('success'))
                showNotification('Berhasil!', '{{ session('success') }}', 'success');
            @endif

            @if(session('error'))
                showNotification('Error!', '{{ session('error') }}', 'error');
            @endif
        });

        // Close modals when clicking outside
        document.addEventListener('click', function (e) {
            if (e.target.id === 'acceptModal') closeAcceptModal();
            if (e.target.id === 'revisionModal') closeRevisionModal();
            if (e.target.id === 'rejectModal') closeRejectModal();
        });

        // ESC key to close modals
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeAcceptModal();
                closeRevisionModal();
                closeRejectModal();
                hideNotification();
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + N for new naskah
            if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                e.preventDefault();
                window.location.href = '{{ route("naskahKolaborasi.create") }}';
            }
            
            // Ctrl/Cmd + F for search focus
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                document.querySelector('input[name="search"]').focus();
            }
        });

        // Auto refresh notification for pending reviews
        setInterval(function() {
            // Only check if no modals are open
            if (document.getElementById('acceptModal').classList.contains('hidden') &&
                document.getElementById('revisionModal').classList.contains('hidden') &&
                document.getElementById('rejectModal').classList.contains('hidden')) {
                
                // Count pending reviews
                const pendingReviews = document.querySelectorAll('.animate-pulse').length;
                if (pendingReviews > 0) {
                    // Update page title to show pending count
                    document.title = `(${pendingReviews}) Naskah Kolaborasi - Admin`;
                } else {
                    document.title = 'Naskah Kolaborasi - Admin';
                }
            }
        }, 30000); // Check every 30 seconds

        // Smooth scroll for floating action button
        function smoothScrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Add hover effects to table rows
        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = 'none';
                });
            });
        });

        // Add ripple effect to buttons
        function createRipple(event) {
            const button = event.currentTarget;
            const circle = document.createElement("span");
            const diameter = Math.max(button.clientWidth, button.clientHeight);
            const radius = diameter / 2;

            circle.style.width = circle.style.height = `${diameter}px`;
            circle.style.left = `${event.clientX - button.offsetLeft - radius}px`;
            circle.style.top = `${event.clientY - button.offsetTop - radius}px`;
            circle.classList.add("ripple");

            const ripple = button.getElementsByClassName("ripple")[0];
            if (ripple) {
                ripple.remove();
            }

            button.appendChild(circle);
        }

        // Add ripple effect to all buttons
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('button, .btn');
            buttons.forEach(button => {
                button.addEventListener('click', createRipple);
            });
        });
    </script>

    <!-- Custom Tailwind Styles -->
    <style>
        /* Custom scrollbar for webkit browsers */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-track {
            @apply bg-gray-100 dark:bg-gray-700 rounded-full;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb {
            @apply bg-gray-300 dark:bg-gray-600 rounded-full;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            @apply bg-gray-400 dark:bg-gray-500;
        }

        /* Ripple effect */
        .ripple {
            position: absolute;
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 600ms linear;
            background-color: rgba(255, 255, 255, 0.7);
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* Custom animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

              .animate-fadeInUp {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Pulse animation for pending reviews */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Floating animation for action buttons */
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        /* Gradient background for status cards */
        .gradient-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }

        .gradient-yellow {
            background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%);
        }

        .gradient-orange {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        }

        .gradient-green {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        }

        .gradient-red {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        /* Hover effects for table rows */
        tbody tr {
            transition: all 0.2s ease;
        }

        tbody tr:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Custom focus styles */
        .focus-ring:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 2px #3b82f6;
        }

        /* Loading spinner */
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Modal backdrop blur */
        .backdrop-blur-sm {
            backdrop-filter: blur(4px);
        }

        /* Status badge glow effect */
        .status-glow {
            box-shadow: 0 0 20px rgba(147, 51, 234, 0.3);
        }

        /* Notification slide animation */
        .notification-enter {
            transform: translateX(100%);
            opacity: 0;
        }

        .notification-enter-active {
            transform: translateX(0);
            opacity: 1;
            transition: all 0.3s ease-out;
        }

        .notification-exit {
            transform: translateX(0);
            opacity: 1;
        }

        .notification-exit-active {
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.3s ease-in;
        }

        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            
            .bg-gray-50 {
                background-color: #f9fafb !important;
            }
            
            .shadow, .shadow-lg, .shadow-xl {
                box-shadow: none !important;
            }
        }

        /* Dark mode improvements */
        @media (prefers-color-scheme: dark) {
            .dark\:scrollbar-dark::-webkit-scrollbar-track {
                background-color: #374151;
            }
            
            .dark\:scrollbar-dark::-webkit-scrollbar-thumb {
                background-color: #6b7280;
            }
            
            .dark\:scrollbar-dark::-webkit-scrollbar-thumb:hover {
                background-color: #9ca3af;
            }
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .mobile-stack {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .mobile-hide {
                display: none;
            }
            
            .mobile-full {
                width: 100%;
            }
            
            /* Adjust floating buttons for mobile */
            .fixed.bottom-6.right-6 {
                bottom: 1rem;
                right: 1rem;
            }
            
            /* Make action buttons smaller on mobile */
            .w-8.h-8 {
                width: 2rem;
                height: 2rem;
            }
            
            /* Stack action buttons vertically on very small screens */
            @media (max-width: 480px) {
                .action-buttons {
                    flex-direction: column;
                    align-items: flex-start;
                }
            }
        }

        /* Accessibility improvements */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .border {
                border-width: 2px;
            }
            
            .focus\:ring-2:focus {
                box-shadow: 0 0 0 3px currentColor;
            }
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            .animate-pulse,
            .animate-spin,
            .animate-float,
            .transition-all,
            .transition-colors,
            .transition-transform {
                animation: none;
                transition: none;
            }
        }

        /* Custom utility classes */
        .text-shadow {
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .border-gradient {
            border-image: linear-gradient(45deg, #3b82f6, #8b5cf6) 1;
        }

        /* Status-specific styles */
        .status-pending {
            position: relative;
            overflow: hidden;
        }

        .status-pending::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                left: -100%;
            }
            100% {
                left: 100%;
            }
        }

        /* Table enhancements */
        .table-hover tbody tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }

        .table-striped tbody tr:nth-child(even) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .dark .table-striped tbody tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.02);
        }

        /* Button loading state */
        .btn-loading {
            position: relative;
            color: transparent;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }

        /* Tooltip styles */
        .tooltip {
            position: relative;
        }

        .tooltip::before {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
            z-index: 1000;
        }

        .tooltip:hover::before {
            opacity: 1;
        }

        /* Custom scrollbar for modal */
        .modal-content {
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-content::-webkit-scrollbar {
            width: 6px;
        }

        .modal-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .modal-content::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .modal-content::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
@endsection
@push('styles')
<!-- FontAwesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush