@extends('admin.layouts.app')

@section('title', 'Detail Buku Kolaboratif')

@section('main')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('admin.buku-kolaboratif.index') }}" 
               class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 mr-4">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $bukuKolaboratif->judul }}</h1>
                <p class="text-gray-600 dark:text-gray-400">Detail buku kolaboratif</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.buku-kolaboratif.edit', $bukuKolaboratif->id) }}" 
               class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i data-lucide="edit" class="w-4 h-4"></i>
                Edit
            </a>
            <a href="{{ route('admin.buku-kolaboratif.tambah-bab', $bukuKolaboratif->id) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Bab
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informasi Buku -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <img src="{{ $bukuKolaboratif->gambar_sampul_url }}" 
                             alt="{{ $bukuKolaboratif->judul }}"
                             class="w-32 h-40 object-cover rounded-lg">
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $bukuKolaboratif->judul }}</h2>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bukuKolaboratif->status_badge_class }}">
                                {{ $bukuKolaboratif->status_text }}
                            </span>
                        </div>
                        
                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center">
                                <i data-lucide="folder" class="w-4 h-4 mr-2"></i>
                                <span>{{ $bukuKolaboratif->kategoriBuku->nama ?? 'Tidak ada kategori' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i data-lucide="calendar" class="w-4 h-4 mr-2"></i>
                                <span>Dibuat {{ $bukuKolaboratif->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center">
                                <i data-lucide="dollar-sign" class="w-4 h-4 mr-2"></i>
                                <span>{{ $bukuKolaboratif->harga_format }} per bab</span>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <p class="text-gray-700 dark:text-gray-300">{{ $bukuKolaboratif->deskripsi }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Bab -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Bab</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Bab
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Judul
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Kesulitan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Harga
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($bukuKolaboratif->babBuku as $bab)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $bab->nomor_bab }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $bab->judul_bab }}
                                        </div>
                                        @if($bab->deskripsi)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ Str::limit($bab->deskripsi, 50) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $kesulitanClass = [
                                                'mudah' => 'bg-green-100 text-green-800',
                                                'sedang' => 'bg-yellow-100 text-yellow-800',
                                                'sulit' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $kesulitanClass[$bab->tingkat_kesulitan] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($bab->tingkat_kesulitan) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        Rp {{ number_format($bab->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClass = [
                                                'tersedia' => 'bg-green-100 text-green-800',
                                                'dipesan' => 'bg-yellow-100 text-yellow-800',
                                                'selesai' => 'bg-blue-100 text-blue-800'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass[$bab->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $bab->status_text }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.buku-kolaboratif.edit-bab', [$bukuKolaboratif->id, $bab->id]) }}" 
                                               class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                            @if($bab->status === 'tersedia')
                                                <form action="{{ route('admin.buku-kolaboratif.hapus-bab', [$bukuKolaboratif->id, $bab->id]) }}" 
                                                      method="POST" 
                                                      class="inline"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus bab ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Belum ada bab. 
                                        <a href="{{ route('admin.buku-kolaboratif.tambah-bab', $bukuKolaboratif->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                           Tambah bab pertama
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statistik -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistik</h3>
                
                <div class="space-y-4">
                    <!-- Progress -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Progress</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $bukuKolaboratif->progress_percentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $bukuKolaboratif->progress_percentage }}%"></div>
                        </div>
                    </div>

                    <!-- Status Bab -->
                    @php
                        $babStats = $bukuKolaboratif->getBabStatistics();
                    @endphp
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg">
                            <div class="text-lg font-bold text-green-600 dark:text-green-400">{{ $babStats['tersedia'] }}</div>
                            <div class="text-xs text-green-600 dark:text-green-400">Tersedia</div>
                        </div>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg">
                            <div class="text-lg font-bold text-yellow-600 dark:text-yellow-400">{{ $babStats['dipesan'] }}</div>
                            <div class="text-xs text-yellow-600 dark:text-yellow-400">Dipesan</div>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                            <div class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $babStats['selesai'] }}</div>
                            <div class="text-xs text-blue-600 dark:text-blue-400">Selesai</div>
                        </div>
                    </div>

                    <!-- Tingkat Kesulitan -->
                    @php
                        $kesulitanStats = $bukuKolaboratif->getKesulitanStatistics();
                    @endphp
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Tingkat Kesulitan</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Mudah</span>
                                <span class="text-sm font-medium text-green-600">{{ $kesulitanStats['mudah'] }} bab</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Sedang</span>
                                <span class="text-sm font-medium text-yellow-600">{{ $kesulitanStats['sedang'] }} bab</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Sulit</span>
                                <span class="text-sm font-medium text-red-600">{{ $kesulitanStats['sulit'] }} bab</span>
                            </div>
                        </div>
                    </div>

                    <!-- Finansial -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Finansial</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Total Nilai Proyek</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    Rp {{ number_format($bukuKolaboratif->harga_per_bab * $bukuKolaboratif->total_bab, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Total Pendapatan</span>
                                <span class="text-sm font-medium text-green-600">
                                    Rp {{ number_format($bukuKolaboratif->getTotalEarnings(), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.buku-kolaboratif.tambah-bab', $bukuKolaboratif->id) }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Tambah Bab
                    </a>
                    
                    <a href="{{ route('admin.buku-kolaboratif.edit', $bukuKolaboratif->id) }}" 
                       class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                        <i data-lucide="edit" class="w-4 h-4"></i>
                        Edit Buku
                    </a>
                    
                    <button onclick="toggleStatus()" 
                            class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                        <i data-lucide="toggle-left" class="w-4 h-4"></i>
                        Toggle Status
                    </button>
                    
                    @if($bukuKolaboratif->canBeDeleted())
                        <form action="{{ route('admin.buku-kolaboratif.destroy', $bukuKolaboratif->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                Hapus Buku
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleStatus() {
    const currentStatus = '{{ $bukuKolaboratif->status }}';
    const newStatus = currentStatus === 'aktif' ? 'nonaktif' : 'aktif';
    
    if (confirm(`Ubah status buku menjadi ${newStatus}?`)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.buku-kolaboratif.update", $bukuKolaboratif->id) }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PUT';
        
        const statusField = document.createElement('input');
        statusField.type = 'hidden';
        statusField.name = 'status';
        statusField.value = newStatus;
        
        // Copy other required fields
        const judulField = document.createElement('input');
        judulField.type = 'hidden';
        judulField.name = 'judul';
        judulField.value = '{{ $bukuKolaboratif->judul }}';
        
        const deskripsiField = document.createElement('input');
        deskripsiField.type = 'hidden';
        deskripsiField.name = 'deskripsi';
        deskripsiField.value = '{{ $bukuKolaboratif->deskripsi }}';
        
        const kategoriField = document.createElement('input');
        kategoriField.type = 'hidden';
        kategoriField.name = 'kategori_buku_id';
        kategoriField.value = '{{ $bukuKolaboratif->kategori_buku_id }}';
        
        const hargaField = document.createElement('input');
        hargaField.type = 'hidden';
        hargaField.name = 'harga_per_bab';
        hargaField.value = '{{ $bukuKolaboratif->harga_per_bab }}';
        
        const totalBabField = document.createElement('input');
        totalBabField.type = 'hidden';
        totalBabField.name = 'total_bab';
        totalBabField.value = '{{ $bukuKolaboratif->total_bab }}';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        form.appendChild(statusField);
        form.appendChild(judulField);
        form.appendChild(deskripsiField);
        form.appendChild(kategoriField);
        form.appendChild(hargaField);
        form.appendChild(totalBabField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
