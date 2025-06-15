<!DOCTYPE html>
<html lang="en" x-data="app" x-init="init()" :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8">
    <title>Daftar Naskah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    
    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs" defer></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    
    <!-- Dark Mode Config -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('app', () => ({
                openMenu: '',
                dark: false,
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    this.dark = savedTheme === 'dark';
                    this.applyTheme();

                    this.$watch('dark', value => {
                        localStorage.setItem('theme', value ? 'dark' : 'light');
                        this.applyTheme();
                    });
                },
                applyTheme() {
                    document.documentElement.classList.toggle('dark', this.dark);
                }
            }));
        });
    </script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-black dark:text-white flex">

    <!-- Dark Mode Toggle -->
    <div class="absolute top-4 right-4 z-50">
        <button @click="dark = !dark" class="bg-gray-300 dark:bg-gray-700 text-black dark:text-white p-2 rounded-full shadow">
            <template x-if="dark">
                <span>☀️</span>
            </template>
            <template x-if="!dark">
                <span>🌙</span>
            </template>
        </button>
    </div>

    <!-- Sidebar -->
    <aside class="w-64 bg-white dark:bg-gray-800 h-screen shadow-md fixed">
        <div class="p-4 flex items-center gap-2 border-b dark:border-gray-700">
            <img src="/images/admin/logo-fanya1.png" alt="Logo" class="h-8">
        </div>
        <nav class="px-4 py-2 overflow-y-auto h-full text-sm">
            <ul class="space-y-2">
                <!-- Dashboard -->
                <li>
                    <a href="#" class="flex items-center gap-2 py-2 px-2 rounded hover:bg-blue-100 dark:hover:bg-gray-700 transition">
                        <i data-lucide="home" class="w-4 h-4"></i> Dashboard
                    </a>
                </li>
  
                <!-- Master Data -->
                <li class="mt-4 font-semibold text-gray-600 dark:text-gray-300">Master Data</li>
                <li>
                    <button @click="openMenu === 'master' ? openMenu = '' : openMenu = 'master'" class="w-full flex items-center gap-2 text-left py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                        <i data-lucide="database" class="w-4 h-4"></i> Master Data
                    </button>
                    <ul x-show="openMenu === 'master'" x-transition class="pl-4 space-y-1">
                        <li>
                            <a href="#" class="flex items-center gap-2 py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                                <i data-lucide="folder" class="w-4 h-4"></i> Kategori Buku
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-2 py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                                <i data-lucide="credit-card" class="w-4 h-4"></i> Rekening
                            </a>
                        </li>
                    </ul>
                </li>
  
                <!-- Promo -->
                <li class="mt-4 font-semibold text-gray-600 dark:text-gray-300">Promo</li>
                <li>
                    <a href="#" class="flex items-center gap-2 py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                        <i data-lucide="percent" class="w-4 h-4"></i> Kode Diskon
                    </a>
                </li>
  
                <!-- Buku, Member, Editor -->
                <li class="mt-4 font-semibold text-gray-600 dark:text-gray-300">Buku, Member, Editor</li>
                <li>
                    <a href="/admin/publish_buku" class="flex items-center gap-2 py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                        <i data-lucide="book-open" class="w-4 h-4"></i> Buku
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-2 py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                        <i data-lucide="users" class="w-4 h-4"></i> Member
                    </a>
                </li>
                <li>
                    <button @click="openMenu === 'editor' ? openMenu = '' : openMenu = 'editor'" class="w-full flex items-center gap-2 text-left py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                        <i data-lucide="edit" class="w-4 h-4"></i> Editor
                    </button>
                    <ul x-show="openMenu === 'editor'" x-transition class="pl-4 space-y-1">
                        <li>
                            <a href="/admin/naskah" class="flex items-center gap-2 py-1 px-6 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                                <i data-lucide="file-text" class="w-4 h-4"></i> Naskah
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-2 py-1 px-6 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                                <i data-lucide="brush" class="w-4 h-4"></i> Design
                            </a>
                        </li>
                    </ul>
                </li>
  
                <!-- Transaksi -->
                <li class="mt-4 font-semibold text-gray-600 dark:text-gray-300">Transaksi</li>
  
                <!-- Laporan Penjualan -->
                <li>
                    <button @click="openMenu === 'penjualan' ? openMenu = '' : openMenu = 'penjualan'" class="w-full flex items-center gap-2 text-left py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                        <i data-lucide="bar-chart-2" class="w-4 h-4"></i> Laporan Penjualan
                    </button>
                    <ul x-show="openMenu === 'penjualan'" x-transition class="pl-4 space-y-1">
                        <li>
                            <a href="#" class="flex items-center gap-2 py-1 px-6 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                                <i data-lucide="file" class="w-4 h-4"></i> Buku Individu
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-2 py-1 px-6 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                                <i data-lucide="layers" class="w-4 h-4"></i> Buku Kolaborasi
                            </a>
                        </li>
                    </ul>
                </li>
  
                <!-- Laporan Penerbitan -->
                <li>
                    <button @click="openMenu === 'penerbitan' ? openMenu = '' : openMenu = 'penerbitan'" class="w-full flex items-center gap-2 text-left py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                        <i data-lucide="file-stack" class="w-4 h-4"></i> Laporan Penerbitan
                    </button>
                    <ul x-show="openMenu === 'penerbitan'" x-transition class="pl-4 space-y-1">
                        <li>
                            <a href="#" class="flex items-center gap-2 py-1 px-6 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                                <i data-lucide="file-text" class="w-4 h-4"></i> Buku Individu
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-2 py-1 px-6 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                                <i data-lucide="file-plus" class="w-4 h-4"></i> Buku Kolaborasi
                            </a>
                        </li>
                    </ul>
                </li>
  
                <!-- Bukti Pembayaran -->
                <li>
                    <a href="#" class="flex items-center gap-2 py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                        <i data-lucide="check-circle" class="w-4 h-4"></i> Bukti Pembayaran
                    </a>
                </li>

                <!-- Katalog Buku -->
                <li>
                    <a href="#" class="flex items-center gap-2 py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                        <i data-lucide="book" class="w-4 h-4"></i> Katalog Buku
                    </a>
                </li>
  
                <!-- Download -->
                <li class="mt-4">
                    <a href="#" class="flex items-center gap-2 py-2 px-2 text-red-500 font-semibold rounded hover:bg-red-100 dark:hover:bg-red-800">
                        <i data-lucide="download" class="w-4 h-4"></i> Download
                    </a>
                </li>

                <!-- Setting Admin -->
                <li class="mt-4 font-semibold text-gray-600 dark:text-gray-300">Setting</li>
                <li>
                    <button @click="openMenu === 'settingAdmin' ? openMenu = '' : openMenu = 'settingAdmin'" class="w-full flex items-center gap-2 text-left py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                        <i data-lucide="settings" class="w-4 h-4"></i> Pengaturan Admin
                    </button>
                    <ul x-show="openMenu === 'settingAdmin'" x-transition class="pl-4 space-y-1">
                        <li>
                            <a href="#" class="flex items-center gap-2 py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                                <i data-lucide="user-cog" class="w-4 h-4"></i> Manajemen Admin
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-2 py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                                <i data-lucide="key-round" class="w-4 h-4"></i> Role & Permission
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-2 py-1 px-4 text-red-500 rounded hover:bg-red-100 dark:hover:bg-red-800">
                                <i data-lucide="power" class="w-4 h-4"></i> Keluar
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </aside>
  
    <!-- Main Content -->
    <div class="ml-64 w-full">
        <!-- Header for User Profile -->
<header class="bg-white dark:bg-gray-800 shadow-md p-4 flex justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        <img src="/images/admin/user-avatar.png" alt="Avatar" class="w-10 h-10 rounded-full border">
        <div class="text-right">
            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Halo, Admin 👋</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Administrator</p>
        </div>
    </div>
</header>

<!-- Detail Naskah -->
<div class="max-w-6xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold text-center mb-4">Detail Naskah</h2>
    <hr class="my-4 border-gray-200 dark:border-gray-700">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-semibold">Judul Naskah :</label>
            <p class="text-gray-600 dark:text-gray-300">{{ $naskah->judul ?? 'Judul tidak tersedia' }}</p>
        </div>

        <div>
            <label class="block text-sm font-semibold">Pengarang :</label>
            <p class="text-gray-600 dark:text-gray-300">{{ $naskah->pengarang ?? 'Penulis tidak diketahui' }}</p>
        </div>
        <div>
            <label class="block text-sm font-semibold">Status :</label>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                @switch($naskah->status)
                    @case('Dalam Review') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100 @break
                    @case('Siap Terbit') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100 @break
                    @case('Ditolak') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100 @break
                    @default bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                @endswitch">
                {{ $naskah->status ?? 'Belum Ditentukan' }}
            </span>
        </div>

        <div>
            <label class="block text-sm font-semibold">Deskripsi Singkat :</label>
            <p class="text-gray-600 dark:text-gray-300">{{ $naskah->deskripsi_singkat ?? 'Terserah' }}</p>
        </div>
        <div>
            <label class="block text-sm font-semibold">Tanggal Dikirim :</label>
            <p class="text-gray-600 dark:text-gray-300">
                {{ $naskah->tanggal ? \Carbon\Carbon::parse($naskah->tanggal)->format('d M Y') : 'Tanggal tidak tersedia' }}
            </p>
        </div>
        <div>
    <label class="block text-sm font-semibold">File Naskah :</label>
    @if($naskah->file_naskah)
        <a href="{{ Storage::url($naskah->file_naskah) }}" 
           class="inline-flex items-center text-blue-600 hover:text-blue-900" 
           target="_blank" 
           title="Download File">
            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            {{ basename($naskah->file_naskah) }}
        </a>
    @else
        <p class="text-gray-500">File tidak tersedia</p>
    @endif
</div>

    </div>
</div>



    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('app', () => ({
                dark: localStorage.getItem('theme') === 'dark',
                openMenu: '',
                init() {
                    this.$watch('dark', value => {
                        localStorage.setItem('theme', value ? 'dark' : 'light');
                        document.documentElement.classList.toggle('dark', value);
                    });
                }
            }));
        });
        lucide.createIcons();
    </script>
</body>
</html>

