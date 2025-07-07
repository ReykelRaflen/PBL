@extends('admin.layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('main')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Laporan Penerbitan</h1>
                <a href="{{ route('admin.laporanPenerbitanKolaborasi.index') }}" 
                   class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
            </div>

            <!-- Info Buku -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                        <i class="fas fa-book mr-2"></i>Informasi Buku
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kode Buku</label>
                            <p class="text-sm text-gray-900 dark:text-white font-mono">{{ $laporan->kode_buku }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Buku</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $laporan->judul }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">ISBN</label>
                            <p class="text-sm text-gray-900 dark:text-white">
                                @if($laporan->isbn)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                        <i class="fas fa-barcode mr-1"></i>{{ $laporan->isbn }}
                                    </span>
                                @else
                                    <span class="text-gray-400">Belum ada ISBN</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Terbit</label>
                            <p class="text-sm text-gray-900 dark:text-white">
                                @if($laporan->tanggal_terbit)
                                    {{ $laporan->tanggal_terbit->format('d F Y') }}
                                @else
                                    <span class="text-gray-400">Belum ditetapkan</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $laporan->status_badge }}">
                                @switch($laporan->status)
                                    @case('proses')
                                        <i class="fas fa-cog mr-1"></i>
                                        @break
                                    @case('terbit')
                                        <i class="fas fa-check-circle mr-1"></i>
                                        @break
                                    @case('pending')
                                        <i class="fas fa-clock mr-1"></i>
                                        @break
                                    @case('draft')
                                        <i class="fas fa-file-alt mr-1"></i>
                                        @break
                                @endswitch
                                {{ $laporan->status_text }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                        <i class="fas fa-chart-pie mr-2"></i>Progress Penulisan
                    </h3>
                    <div class="space-y-4">
                        <div class="text-center">
                            <div class="relative inline-flex items-center justify-center w-32 h-32">
                                <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-gray-300 dark:text-gray-600" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path class="text-blue-600" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" stroke-dasharray="{{ $laporan->persentase_selesai }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $laporan->persentase_selesai }}%</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $laporan->total_bab_disetujui }} dari {{ $laporan->total_bab_buku }} bab selesai
                            </p>
                        </div>
                        <div class="flex justify-center space-x-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $laporan->total_bab_disetujui }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Selesai</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-400">{{ $laporan->total_bab_buku - $laporan->total_bab_disetujui }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Belum</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            @if($laporan->catatan)
                <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">
                        <i class="fas fa-sticky-note mr-2"></i>Catatan
                    </h3>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $laporan->catatan }}</p>
                </div>
            @endif

            <!-- Daftar Naskah per Bab -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-list mr-2"></i>Naskah per Bab
                        </h3>
                        @if($laporan->total_bab_disetujui > 0)
                            <a href="{{ route('admin.laporanPenerbitanKolaborasi.downloadAll', $laporan->id) }}"
                                class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors duration-200">
                                <i class="fas fa-download mr-1"></i>Download Semua
                            </a>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Bab</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Judul Naskah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Penulis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah Kata</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Disetujui</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($laporan->naskahKolaborasi->sortBy('babBuku.nomor_bab') as $naskah)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-800 dark:text-blue-300">
                                                    {{ $naskah->babBuku->nomor_bab }}
                                                </span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    Bab {{ $naskah->babBuku->nomor_bab }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $naskah->babBuku->judul_bab }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $naskah->judul_naskah }}
                                        </div>
                                        @if($naskah->deskripsi_naskah)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ Str::limit($naskah->deskripsi_naskah, 60) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ $naskah->user->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $naskah->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            @if($naskah->jumlah_kata)
                                                {{ number_format($naskah->jumlah_kata, 0, ',', '.') }} kata
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            @if($naskah->tanggal_disetujui)
                                                {{ $naskah->tanggal_disetujui->format('d/m/Y') }}
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $naskah->tanggal_disetujui->format('H:i') }}
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            @if($naskah->file_naskah)
                                                <a href="{{ route('admin.laporanPenerbitanKolaborasi.downloadNaskah', [$laporan->id, $naskah->id]) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-110"
                                                    title="Download Naskah">
                                                    <i class="fas fa-download text-xs"></i>
                                                </a>
                                            @endif
                                            
                                            <a href="{{ route('naskahKolaborasi.show', $naskah->id) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-110"
                                                title="Lihat Detail Naskah">
                                                <i class="fas fa-eye text-xs"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-file-alt text-3xl mb-2 opacity-50"></i>
                                            <p>Belum ada naskah yang disetujui untuk buku ini</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.laporanPenerbitanKolaborasi.edit', $laporan->id) }}"
                    class="px-6 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-colors duration-200">
                    <i class="fas fa-edit mr-1"></i>Edit Laporan
                </a>
                
                @if($laporan->total_bab_disetujui > 0)
                    <a href="{{ route('admin.laporanPenerbitanKolaborasi.downloadAll', $laporan->id) }}"
                        class="px-6 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors duration-200">
                        <i class="fas fa-download mr-1"></i>Download Semua Naskah
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
