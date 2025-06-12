<!DOCTYPE html>
<html lang="en" x-data="app" x-init="init()" :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8">
    <title>Daftar Buku</title>
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

<b class="bg-gray-100 dark:bg-gray-900 text-black dark:text-white flex">

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
    <header class="bg-white dark:bg-gray-800 shadow-md p-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <img src="/images/admin/user-avatar.png" alt="Avatar" class="w-10 h-10 rounded-full border">
            <div class="text-right">
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Halo, Admin 👋</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Administrator</p>
            </div>
        </div>
    </header>

<!-- Main Content Area -->
<div class="mt-6"> <!-- Added margin-top here -->
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
        }

        /* Container Styles */
        .background {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 800px; /* Set a max-width for better alignment */
            margin: auto; /* Centered alignment */
        }

        /* Cover Container */
        .cover-container {
            background-color: #e0e0e0;
            border-radius: 10px;
            height: 256px;
            width: 192px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 20px;
        }

        /* Cover Image */
        .cover-image {
            height: 100%;
            width: 100%;
            border-radius: 10px;
        }

        /* Title Styles */
        .book-title {
            font-size: 28px; /* Increased font size for title */
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        /* Price Styles */
        .discounted-price {
            font-size: 28px; /* Increased font size for price */
            font-weight: bold;
            color: #5c6bc0; /* Adjusted color for the discounted price */
        }

        .original-price {
            font-size: 18px; /* Adjusted size for original price */
            color: #999; /* Lightened for the original price */
            text-decoration: line-through;
            margin-left: 10px; /* Added space for clarity */
        }

        .discount-badge {
            background-color: #ffebee;
            color: #f44336;
            font-size: 14px;
            padding: 4px 8px; /* Increased padding */
            border-radius: 4px;
            margin-left: 10px; /* Consistent spacing */
        }

        /* Additional styling for text */
        p {
            font-size: 16px;
            color: #666;
            margin: 4px 0; /* Ruled space between paragraphs */
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .cover-container {
                width: 100%; /* Full width for smaller devices */
                height: auto; /* Adjust height for responsiveness */
            }
        }
    </style>

    <!-- Detail Buku -->
    <div class="background">
        <h2 class="text-2xl font-bold text-center mb-4">Detail Buku</h2>
        <hr class="my-4 border-gray-200">

        <div class="flex flex-col lg:flex-row">
            <!-- Cover Buku -->
            <div class="flex justify-center">
                <div class="cover-container">
                    @if($publish_book->cover_buku)
                        <img src="{{ Storage::url($publish_book->cover_buku) }}" 
                             alt="Cover {{ $publish_book->judul_buku }}"
                             class="cover-image">
                    @else
                        <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    @endif
                </div>
            </div>

            <!-- Detail Informasi -->
            <div class="flex flex-col justify-center lg:pl-6">
                <div class="space-y-6">
                    <!-- Judul dan Harga -->
                    <div class="flex flex-col justify-center">
                        <h1 class="book-title dark:text-white mb-2">
                            {{ $publish_book->judul_buku }}
                        </h1>
                        <div class="flex flex-col items-start">
                            @if($publish_book->diskon && $publish_book->diskon > 0)
                                @php
                                    $hargaAsli = $publish_book->harga;
                                    $hargaDiskon = $publish_book->harga * (1 - $publish_book->diskon / 100);
                                @endphp
                                <span class="discounted-price">
                                    Rp {{ number_format($hargaDiskon, 0, ',', '.') }}   
                                </span>
                                <div>
                                    <span class="original-price">
                                        Rp {{ number_format($hargaAsli, 0, ',', '.') }} 
                                    </span>
                                    <span class="discount-badge">
                                        -{{ number_format($publish_book->diskon, 0) }}%
                                    </span>
                                </div>



                            @else
                                <span class="discounted-price">
                                    Rp {{ number_format($publish_book->harga, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>
                    </div>



                    <!-- Other details... -->
                    <div class="space-y-2">
                        <div class="flex">
                            <span class="font-medium w-32">Penulis</span>
                            <span class="mx-3"></span>
                            <span>{{ $publish_book->penulis }}</span>
                        </div>
                        
                        <div class="flex">
                            <span class="font-medium w-32">Penerbit</span>
                            <span class="mx-3"></span>
                            <span>{{ $publish_book->penerbit }}</span>
                        </div>
                        
                        <div class="flex">
                            <span class="font-medium w-32">ISBN</span>
                            <span class="mx-3"></span>
                            <span>{{ $publish_book->isbn }}</span>
                        </div>
                        
                        <div class="flex">
                            <span class="font-medium w-32">Halaman</span>
                            <span class="mx-3"></span>
                            <span>{{ $publish_book->jumlah_halaman }} Halaman</span>
                        </div>
                        
                        <div class="flex">
                            <span class="font-medium w-32">Tanggal Terbit</span>
                            <span class="mx-3"></span>
                            <span>{{ $publish_book->tanggal_terbit ? \Carbon\Carbon::parse($publish_book->tanggal_terbit)->format('d M Y') : 'Tanggal tidak tersedia' }}</span>
                        </div>
                    </div>

                        <div class="mt-6">
                            <h3 class="font-medium text-lg mb-2">Deskripsi</h3>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                {{ $publish_book->deskripsi }}
                            </p>
                        </div>

                </div>
            </div>
        </div>
    </div>
</div>








        <script>
        lucide.createIcons();
    </script>
</body>
</html>