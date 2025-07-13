@extends('admin.layouts.app')

@section('main')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Manajemen Naskah Individu</h1>
                <div class="flex space-x-2">
                    <button onclick="openBulkActionModal()"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 disabled:opacity-50 transition ease-in-out duration-150"
                        id="bulkActionBtn" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Bulk Action
                    </button>
                    <button onclick="openBulkDeleteModal()"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 disabled:opacity-50 transition ease-in-out duration-150"
                        id="bulkDeleteBtn" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Bulk Delete
                    </button>
                </div>
            </div>

            <!-- Statistik -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-6">
                <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                    <div class="text-blue-600 dark:text-blue-300 text-sm font-medium">Total</div>
                    <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $statistik['total'] }}</div>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg">
                    <div class="text-yellow-600 dark:text-yellow-300 text-sm font-medium">Sudah Kirim</div>
                    <div class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ $statistik['sudah_kirim'] }}
                    </div>
                </div>
                <div class="bg-orange-50 dark:bg-orange-900 p-4 rounded-lg">
                    <div class="text-orange-600 dark:text-orange-300 text-sm font-medium">Revisi</div>
                    <div class="text-2xl font-bold text-orange-900 dark:text-orange-100">{{ $statistik['revisi'] }}</div>
                </div>
                <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                    <div class="text-green-600 dark:text-green-300 text-sm font-medium">Disetujui</div>
                    <div class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $statistik['disetujui'] }}</div>
                </div>
                <div class="bg-red-50 dark:bg-red-900 p-4 rounded-lg">
                    <div class="text-red-600 dark:text-red-300 text-sm font-medium">Ditolak</div>
                    <div class="text-2xl font-bold text-red-900 dark:text-red-100">{{ $statistik['ditolak'] }}</div>
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
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status_penerbitan"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="sudah_kirim" {{ request('status_penerbitan') == 'sudah_kirim' ? 'selected' : '' }}>
                                Sudah Kirim</option>
                            <option value="revisi" {{ request('status_penerbitan') == 'revisi' ? 'selected' : '' }}>Revisi
                            </option>
                            <option value="disetujui" {{ request('status_penerbitan') == 'disetujui' ? 'selected' : '' }}>
                                Disetujui</option>
                            <option value="ditolak" {{ request('status_penerbitan') == 'ditolak' ? 'selected' : '' }}>Ditolak
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Paket</label>
                        <select name="paket"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Paket</option>
                            <option value="silver" {{ request('paket') == 'silver' ? 'selected' : '' }}>Silver</option>
                            <option value="gold" {{ request('paket') == 'gold' ? 'selected' : '' }}>Gold</option>
                            <option value="diamond" {{ request('paket') == 'diamond' ? 'selected' : '' }}>Diamond</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Nomor, Judul, Penulis..."
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 dark:bg-green-800 dark:text-green-100"
                    role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 dark:bg-red-800 dark:text-red-100"
                    role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left">
                                <input type="checkbox" id="selectAll"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nomor Pesanan</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Judul & Penulis</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Pemesan</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Paket</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tanggal Upload</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @forelse($naskahList as $naskah)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="selected_items[]" value="{{ $naskah->id }}"
                                        class="item-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $naskah->nomor_pesanan }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $naskah->judul_buku ?? 'Belum ada judul' }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $naskah->nama_penulis ?? 'Belum ada penulis' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $naskah->user->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $naskah->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $paketBadge = match ($naskah->paket) {
                                            'silver' => 'bg-gray-500',
                                            'gold' => 'bg-yellow-500',
                                            'diamond' => 'bg-blue-500',
                                            default => 'bg-gray-500'
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paketBadge }} text-white">
                                        {{ ucfirst($naskah->paket) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusBadge = match ($naskah->status_penerbitan) {
                                            'sudah_kirim' => 'bg-blue-500',
                                            'revisi' => 'bg-orange-500',
                                            'disetujui' => 'bg-green-500',
                                            'ditolak' => 'bg-red-500',
                                            default => 'bg-gray-500'
                                        };

                                        $statusText = match ($naskah->status_penerbitan) {
                                            'sudah_kirim' => 'Sudah Kirim',
                                            'revisi' => 'Revisi',
                                            'disetujui' => 'Disetujui',
                                            'ditolak' => 'Ditolak',
                                            default => ucfirst(str_replace('_', ' ', $naskah->status_penerbitan))
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBadge }} text-white">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $naskah->tanggal_upload_naskah ? $naskah->tanggal_upload_naskah->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.naskah-individu.show', $naskah->id) }}"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd"
                                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </a>

                                        @if($naskah->file_naskah)
                                            <a href="{{ route('admin.naskah-individu.download', $naskah->id) }}"
                                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                title="Download Naskah">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @endif

                                        <!-- Delete Button -->
                                        <button onclick="confirmDelete({{ $naskah->id }}, '{{ $naskah->nomor_pesanan }}')"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                            title="Hapus Naskah">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 112 0v3a1 1 0 11-2 0V9zm4 0a1 1 0 112 0v3a1 1 0 11-2 0V9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        <!-- Quick Actions - Updated to use existing ENUM values -->
                                        @if($naskah->status_penerbitan === 'sudah_kirim')
                                            <button
                                                onclick="quickAction({{ $naskah->id }}, 'disetujui', '{{ $naskah->nomor_pesanan }}')"
                                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                title="Setujui">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>

                                            <button
                                                onclick="quickAction({{ $naskah->id }}, 'revisi', '{{ $naskah->nomor_pesanan }}')"
                                                class="text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300"
                                                title="Minta Revisi">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>

                                            <button
                                                onclick="quickAction({{ $naskah->id }}, 'ditolak', '{{ $naskah->nomor_pesanan }}')"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                title="Tolak">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        @endif

                                        @if(in_array($naskah->status_penerbitan, ['revisi']))
                                            <button
                                                onclick="quickAction({{ $naskah->id }}, 'disetujui', '{{ $naskah->nomor_pesanan }}')"
                                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                title="Setujui">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>

                                            <button
                                                onclick="quickAction({{ $naskah->id }}, 'ditolak', '{{ $naskah->nomor_pesanan }}')"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                title="Tolak">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center py-8">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Belum ada naskah
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Naskah yang sudah diupload akan
                                            muncul di sini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($naskahList->hasPages())
                <div class="mt-6">
                    {{ $naskahList->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div
                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Hapus Naskah</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400" id="deleteMessage"></p>
                    <form id="deleteForm" method="POST" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-center space-x-3">
                            <button type="button" onclick="closeDeleteModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Hapus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Modal -->
    <div id="quickActionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div
                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" id="quickActionTitle">Update Status</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400" id="quickActionMessage"></p>
                    <form id="quickActionForm" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="status_penerbitan" id="quickActionStatus">
                        <div class="mb-4">
                            <label for="quickActionCatatan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 text-left">
                                Catatan (Opsional)
                            </label>
                            <textarea name="catatan_review" id="quickActionCatatan" rows="3"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Tambahkan catatan..."></textarea>
                        </div>
                        <div class="flex justify-center space-x-3">
                            <button type="button" onclick="closeQuickActionModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Batal
                            </button>
                            <button type="submit" id="quickActionSubmit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Action Modal -->
    <div id="bulkActionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div
                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 dark:bg-purple-900 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Bulk Action</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Pilih aksi yang akan diterapkan pada item yang
                        dipilih</p>
                    <form id="bulkActionForm" method="POST" action="{{ route('admin.naskah-individu.bulk-action') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="bulkAction"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 text-left">
                                Pilih Aksi
                            </label>
                            <select name="action" id="bulkAction" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih Aksi</option>
                                <option value="sudah_kirim">Set ke Sudah Kirim</option>
                                <option value="revisi">Minta Revisi</option>
                                <option value="disetujui">Setujui</option>
                                <option value="ditolak">Tolak</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="bulkCatatan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 text-left">
                                Catatan (Opsional)
                            </label>
                            <textarea name="bulk_catatan" id="bulkCatatan" rows="3"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Catatan untuk semua item yang dipilih..."></textarea>
                        </div>
                        <div id="selectedItemsContainer"></div>
                        <div class="flex justify-center space-x-3">
                            <button type="button" onclick="closeBulkActionModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Proses
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Delete Modal -->
    <div id="bulkDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div
                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Bulk Delete</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Hapus semua item yang dipilih? Tindakan ini
                        tidak dapat dibatalkan.</p>
                    <form id="bulkDeleteForm" method="POST" action="{{ route('admin.naskah-individu.bulk-delete') }}">
                        @csrf
                        <div id="selectedDeleteItemsContainer"></div>
                        <div class="flex justify-center space-x-3">
                            <button type="button" onclick="closeBulkDeleteModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Hapus Semua
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Checkbox functionality
        document.addEventListener('DOMContentLoaded', function () {
            const selectAllCheckbox = document.getElementById('selectAll');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');

            // Select All functionality
            selectAllCheckbox.addEventListener('change', function () {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActionButtons();
            });

            // Individual checkbox functionality
            itemCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    updateBulkActionButtons();

                    // Update select all checkbox state
                    const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
                    selectAllCheckbox.checked = checkedBoxes.length === itemCheckboxes.length;
                    selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < itemCheckboxes.length;
                });
            });
        });

        function updateBulkActionButtons() {
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            const bulkActionBtn = document.getElementById('bulkActionBtn');
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

            if (checkedBoxes.length > 0) {
                bulkActionBtn.disabled = false;
                bulkActionBtn.classList.remove('opacity-50');
                bulkDeleteBtn.disabled = false;
                bulkDeleteBtn.classList.remove('opacity-50');

                bulkActionBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Bulk Action (${checkedBoxes.length})
                    `;

                bulkDeleteBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Bulk Delete (${checkedBoxes.length})
                    `;
            } else {
                bulkActionBtn.disabled = true;
                bulkActionBtn.classList.add('opacity-50');
                bulkDeleteBtn.disabled = true;
                bulkDeleteBtn.classList.add('opacity-50');

                bulkActionBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Bulk Action
                    `;

                bulkDeleteBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Bulk Delete
                    `;
            }
        }

        // Delete functionality
        function confirmDelete(id, nomorPesanan) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            const message = document.getElementById('deleteMessage');

            form.action = `/admin/naskah-individu/${id}`;
            message.textContent = `Apakah Anda yakin ingin menghapus naskah "${nomorPesanan}"? Tindakan ini tidak dapat dibatalkan dan akan menghapus semua file terkait.`;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Quick Action Modal - Updated to use existing ENUM values
        function quickAction(id, status, nomorPesanan) {
            const modal = document.getElementById('quickActionModal');
            const form = document.getElementById('quickActionForm');
            const title = document.getElementById('quickActionTitle');
            const message = document.getElementById('quickActionMessage');
            const statusInput = document.getElementById('quickActionStatus');
            const submitBtn = document.getElementById('quickActionSubmit');

            // Set form action
            form.action = `/admin/naskah-individu/${id}/update-status`;

            // Set status
            statusInput.value = status;

            // Set title and message based on status - Updated for existing ENUM values
            const statusTexts = {
                'sudah_kirim': {
                    title: 'Set ke Sudah Kirim',
                    message: `Set status naskah ${nomorPesanan} ke "Sudah Kirim"?`,
                    btnText: 'Set Status',
                    btnClass: 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500'
                },
                'revisi': {
                    title: 'Minta Revisi',
                    message: `Minta revisi untuk naskah ${nomorPesanan}?`,
                    btnText: 'Minta Revisi',
                    btnClass: 'bg-orange-600 hover:bg-orange-700 focus:ring-orange-500'
                },
                'disetujui': {
                    title: 'Setujui Naskah',
                    message: `Setujui naskah ${nomorPesanan}?`,
                    btnText: 'Setujui',
                    btnClass: 'bg-green-600 hover:bg-green-700 focus:ring-green-500'
                },
                'ditolak': {
                    title: 'Tolak Naskah',
                    message: `Tolak naskah ${nomorPesanan}?`,
                    btnText: 'Tolak',
                    btnClass: 'bg-red-600 hover:bg-red-700 focus:ring-red-500'
                }
            };

            const statusData = statusTexts[status];
            title.textContent = statusData.title;
            message.textContent = statusData.message;
            submitBtn.textContent = statusData.btnText;
            submitBtn.className = `px-4 py-2 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 ${statusData.btnClass}`;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeQuickActionModal() {
            const modal = document.getElementById('quickActionModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('quickActionCatatan').value = '';
        }

        // Bulk Action Modal
        function openBulkActionModal() {
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('Pilih minimal satu item untuk bulk action');
                return;
            }

            const container = document.getElementById('selectedItemsContainer');
            container.innerHTML = '';

            checkedBoxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_items[]';
                input.value = checkbox.value;
                container.appendChild(input);
            });

            const modal = document.getElementById('bulkActionModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeBulkActionModal() {
            const modal = document.getElementById('bulkActionModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('bulkCatatan').value = '';
            document.getElementById('bulkAction').value = '';
        }

        // Bulk Delete Modal
        function openBulkDeleteModal() {
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('Pilih minimal satu item untuk dihapus');
                return;
            }

            const container = document.getElementById('selectedDeleteItemsContainer');
            container.innerHTML = '';

            checkedBoxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_items[]';
                input.value = checkbox.value;
                container.appendChild(input);
            });

            const modal = document.getElementById('bulkDeleteModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeBulkDeleteModal() {
            const modal = document.getElementById('bulkDeleteModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modals when clicking outside
        window.onclick = function (event) {
            const quickModal = document.getElementById('quickActionModal');
            const bulkModal = document.getElementById('bulkActionModal');
            const deleteModal = document.getElementById('deleteModal');
            const bulkDeleteModal = document.getElementById('bulkDeleteModal');

            if (event.target === quickModal) {
                closeQuickActionModal();
            }
            if (event.target === bulkModal) {
                closeBulkActionModal();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
            if (event.target === bulkDeleteModal) {
                closeBulkDeleteModal();
            }
        }

        // Close modals with Escape key
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeQuickActionModal();
                closeBulkActionModal();
                closeDeleteModal();
                closeBulkDeleteModal();
            }
        });

        // Form validation for bulk action
        document.getElementById('bulkActionForm').addEventListener('submit', function (e) {
            const action = document.getElementById('bulkAction').value;
            if (!action) {
                e.preventDefault();
                alert('Pilih aksi yang akan dilakukan');
                return;
            }

            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('Pilih minimal satu item');
                return;
            }

            // Confirmation for destructive actions
            if (action === 'ditolak') {
                if (!confirm(`Yakin ingin menolak ${checkedBoxes.length} naskah?`)) {
                    e.preventDefault();
                    return;
                }
            }
        });

        // Form validation for bulk delete
        document.getElementById('bulkDeleteForm').addEventListener('submit', function (e) {
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('Pilih minimal satu item untuk dihapus');
                return;
            }

            if (!confirm(`Yakin ingin menghapus ${checkedBoxes.length} naskah? Tindakan ini tidak dapat dibatalkan!`)) {
                e.preventDefault();
                return;
            }
        });

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease-in-out';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        });
    </script>
@endsection