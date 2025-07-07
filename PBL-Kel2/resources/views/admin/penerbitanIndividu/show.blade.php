@extends('admin.layouts.app')

@section('main')
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Detail Laporan - {{ $laporan->kode_buku }}</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.penerbitanIndividu.edit', $laporan->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path
                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('admin.penerbitanIndividu.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div
                    class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 dark:bg-green-800 dark:text-green-100">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 dark:bg-red-800 dark:text-red-100">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informasi Buku -->
                <div
                    class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900 dark:to-indigo-900 p-6 rounded-lg border border-blue-200 dark:border-blue-700">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-blue-900 dark:text-blue-100">Informasi Buku</h3>
                    </div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-blue-600 dark:text-blue-400">Kode Buku</dt>
                            <dd class="text-sm text-blue-900 dark:text-blue-100 font-mono">{{ $laporan->kode_buku }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-blue-600 dark:text-blue-400">Judul Buku</dt>
                            <dd class="text-sm text-blue-900 dark:text-blue-100 font-medium">{{ $laporan->judul }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-blue-600 dark:text-blue-400">Penulis</dt>
                            <dd class="text-sm text-blue-900 dark:text-blue-100">{{ $laporan->penulis }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-blue-600 dark:text-blue-400">ISBN</dt>
                            <dd class="text-sm text-blue-900 dark:text-blue-100">
                                @if($laporan->isbn)
                                    <span class="font-mono">{{ $laporan->isbn }}</span>
                                @else
                                    <span class="text-gray-400 italic">Belum ada ISBN</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-blue-600 dark:text-blue-400">Status</dt>
                            <dd>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $laporan->status_badge }} text-white">
                                    {{ $laporan->status_text }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Informasi Penerbitan -->
                <div
                    class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900 dark:to-emerald-900 p-6 rounded-lg border border-green-200 dark:border-green-700">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0h6m-6 0l-2 2m8-2l2 2m-2-2v6a2 2 0 01-2 2H10a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H10a2 2 0 00-2 2v2" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-semibold text-green-900 dark:text-green-100">Informasi Penerbitan</h3>
                    </div>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-green-600 dark:text-green-400">Tanggal Terbit</dt>
                            <dd class="text-sm text-green-900 dark:text-green-100">
                                {{ $laporan->tanggal_terbit_formatted }}
                            </dd>
                        </div>
                                                <div>
                            <dt class="text-sm font-medium text-green-600 dark:text-green-400">Tanggal Dibuat</dt>
                            <dd class="text-sm text-green-900 dark:text-green-100">
                                {{ $laporan->created_at->format('d/m/Y H:i') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-green-600 dark:text-green-400">Terakhir Diupdate</dt>
                            <dd class="text-sm text-green-900 dark:text-green-100">
                                {{ $laporan->updated_at->format('d/m/Y H:i') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-green-600 dark:text-green-400">Sumber Data</dt>
                            <dd class="text-sm text-green-900 dark:text-green-100">
                                @if($laporan->penerbitanIndividu)
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-green-600 dark:text-green-400">Dari Naskah</span>
                                    </div>
                                    <div class="text-xs text-green-500 mt-1">{{ $laporan->penerbitanIndividu->nomor_pesanan }}</div>
                                @else
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span class="text-gray-500">Input Manual</span>
                                    </div>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Informasi Naskah Terkait -->
            @if($laporan->penerbitanIndividu)
                <div class="mt-6">
                    <div
                        class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900 dark:to-pink-900 p-6 rounded-lg border border-purple-200 dark:border-purple-700">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-600 dark:text-purple-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="ml-3 text-lg font-semibold text-purple-900 dark:text-purple-100">Naskah Terkait</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-purple-600 dark:text-purple-400">Nomor Pesanan</dt>
                                <dd class="text-sm text-purple-900 dark:text-purple-100 font-mono">
                                    {{ $laporan->penerbitanIndividu->nomor_pesanan }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-purple-600 dark:text-purple-400">Pemesan</dt>
                                <dd class="text-sm text-purple-900 dark:text-purple-100">
                                    {{ $laporan->penerbitanIndividu->user->name }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-purple-600 dark:text-purple-400">Paket</dt>
                                <dd>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-500 text-white">
                                        {{ ucfirst($laporan->penerbitanIndividu->paket) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-purple-600 dark:text-purple-400">Status Naskah</dt>
                                <dd>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500 text-white">
                                        {{ $laporan->penerbitanIndividu->status_penerbitan_text }}
                                    </span>
                                </dd>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-purple-200 dark:border-purple-600">
                            <a href="{{ route('admin.naskah-individu.show', $laporan->penerbitanIndividu->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd"
                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Lihat Detail Naskah
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="mt-6">
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
                    <div class="flex flex-wrap gap-3">
                        @if($laporan->status !== 'terbit')
                            <button onclick="quickUpdateStatus('terbit')"
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Tandai Terbit
                            </button>
                        @endif

                        @if($laporan->status !== 'proses')
                            <button onclick="quickUpdateStatus('proses')"
                                class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Tandai Proses
                            </button>
                        @endif

                        @if($laporan->status !== 'pending')
                            <button onclick="quickUpdateStatus('pending')"
                                class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Tandai Pending
                            </button>
                        @endif

                        <a href="{{ route('admin.penerbitanIndividu.edit', $laporan->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path
                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit Detail
                        </a>

                        <form action="{{ route('admin.penerbitanIndividu.destroy', $laporan->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Laporan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Update Modal -->
    <div id="quickUpdateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4" id="quickUpdateIcon">
                    <!-- Icon will be set by JavaScript -->
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100" id="quickUpdateTitle">Update Status</h3>
                            <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400" id="quickUpdateMessage"></p>
                    <form id="quickUpdateForm" method="POST" class="mt-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" id="quickUpdateStatus">
                        <input type="hidden" name="kode_buku" value="{{ $laporan->kode_buku }}">
                        <input type="hidden" name="judul" value="{{ $laporan->judul }}">
                        <input type="hidden" name="penulis" value="{{ $laporan->penulis }}">
                        <input type="hidden" name="isbn" value="{{ $laporan->isbn }}">
                        <input type="hidden" name="tanggal_terbit" id="quickUpdateTanggalTerbit" value="{{ $laporan->tanggal_terbit ? $laporan->tanggal_terbit->format('Y-m-d') : '' }}">
                        
                        <div class="mb-4" id="tanggalTerbitContainer" style="display: none;">
                            <label for="tanggal_terbit_input" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 text-left">
                                Tanggal Terbit
                            </label>
                            <input type="date" name="tanggal_terbit_input" id="tanggal_terbit_input"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        
                        <div class="flex justify-center space-x-3">
                            <button type="button" onclick="closeQuickUpdateModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Batal
                            </button>
                            <button type="submit" id="quickUpdateSubmit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Quick update status functionality
        function quickUpdateStatus(status) {
            const modal = document.getElementById('quickUpdateModal');
            const form = document.getElementById('quickUpdateForm');
            const title = document.getElementById('quickUpdateTitle');
            const message = document.getElementById('quickUpdateMessage');
            const statusInput = document.getElementById('quickUpdateStatus');
            const submitBtn = document.getElementById('quickUpdateSubmit');
            const icon = document.getElementById('quickUpdateIcon');
            const tanggalContainer = document.getElementById('tanggalTerbitContainer');
            const tanggalInput = document.getElementById('tanggal_terbit_input');
            const hiddenTanggalInput = document.getElementById('quickUpdateTanggalTerbit');

            // Set form action
            form.action = `{{ route('admin.penerbitanIndividu.update', $laporan->id) }}`;

            // Set status
            statusInput.value = status;

            // Configure modal based on status
            const statusConfig = {
                'terbit': {
                    title: 'Tandai Sebagai Terbit',
                    message: 'Tandai laporan "{{ $laporan->judul }}" sebagai terbit?',
                    btnText: 'Tandai Terbit',
                    btnClass: 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
                    iconClass: 'bg-green-100 dark:bg-green-900',
                    iconColor: 'text-green-600 dark:text-green-400',
                    icon: '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                    showTanggal: true
                },
                'proses': {
                    title: 'Tandai Sebagai Proses',
                    message: 'Tandai laporan "{{ $laporan->judul }}" sebagai sedang diproses?',
                    btnText: 'Tandai Proses',
                    btnClass: 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500',
                    iconClass: 'bg-yellow-100 dark:bg-yellow-900',
                    iconColor: 'text-yellow-600 dark:text-yellow-400',
                    icon: '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                    showTanggal: false
                },
                'pending': {
                    title: 'Tandai Sebagai Pending',
                    message: 'Tandai laporan "{{ $laporan->judul }}" sebagai pending?',
                    btnText: 'Tandai Pending',
                    btnClass: 'bg-orange-600 hover:bg-orange-700 focus:ring-orange-500',
                    iconClass: 'bg-orange-100 dark:bg-orange-900',
                    iconColor: 'text-orange-600 dark:text-orange-400',
                    icon: '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                    showTanggal: false
                }
            };

            const config = statusConfig[status];
            title.textContent = config.title;
            message.textContent = config.message;
            submitBtn.textContent = config.btnText;
            submitBtn.className = `px-4 py-2 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 ${config.btnClass}`;
            icon.className = `mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4 ${config.iconClass}`;
            icon.innerHTML = `<div class="${config.iconColor}">${config.icon}</div>`;

            // Show/hide tanggal terbit field
            if (config.showTanggal) {
                tanggalContainer.style.display = 'block';
                tanggalInput.value = new Date().toISOString().split('T')[0];
                tanggalInput.addEventListener('change', function() {
                    hiddenTanggalInput.value = this.value;
                });
            } else {
                tanggalContainer.style.display = 'none';
            }

            modal.classList.remove('hidden');
        }

        function closeQuickUpdateModal() {
            document.getElementById('quickUpdateModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        window.onclick = function (event) {
            const modal = document.getElementById('quickUpdateModal');
            if (event.target === modal) {
                closeQuickUpdateModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeQuickUpdateModal();
            }
        });

        // Form validation for quick update
        document.getElementById('quickUpdateForm').addEventListener('submit', function (e) {
            const status = document.getElementById('quickUpdateStatus').value;
            const tanggalInput = document.getElementById('tanggal_terbit_input');
            
            if (status === 'terbit' && tanggalInput.style.display !== 'none') {
                if (!tanggalInput.value) {
                    e.preventDefault();
                    alert('Tanggal terbit harus diisi untuk status "Terbit"');
                    tanggalInput.focus();
                    return false;
                }
                // Update hidden field
                document.getElementById('quickUpdateTanggalTerbit').value = tanggalInput.value;
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

    <style>
        /* Modal animation */
        #quickUpdateModal {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Button hover effects */
        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s ease;
        }
    </style>
@endsection
 