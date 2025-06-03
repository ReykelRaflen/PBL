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
    

{{-- Bagian Edit --}}
<style>
/* Container styling */
.max-w-6xl {
    max-width: 72rem; /* 1152px */
    margin-left: auto;
    margin-right: auto;
    background-color: #fff;
    border-radius: 1rem;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -2px rgb(0 0 0 / 0.05);
    padding: 1.5rem 2rem;
}

.dark .max-w-6xl {
    background-color: #1f2937; /* dark gray background */
    color: #d1d5db; /* lighter text */
}

/* Heading */
.max-w-6xl h2 {
    font-size: 1.5rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 1rem;
    color: #1e40af; /* blue-800 */
}

.dark .max-w-6xl h2 {
    color: #93c5fd; /* lighter blue */
}

/* Horizontal rule */
hr {
    border-color: #e5e7eb;
    margin-top: 1rem;
    margin-bottom: 1.5rem;
}

.dark hr {
    border-color: #374151;
}

/* Grid layout for form fields */
.grid.grid-cols-1.md\:grid-cols-2 {
    display: grid;
    grid-template-columns: repeat(1, minmax(0, 1fr));
    gap: 1.5rem;
}

@media (min-width: 768px) {
    .grid.grid-cols-1.md\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* Form labels */
label.block.text-sm.font-semibold {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #374151; /* gray-700 */
}

.dark label.block.text-sm.font-semibold {
    color: #d1d5db; /* lighter gray */
}

/* Input fields, textarea and select */
input.form-control,
textarea.form-control,
select.form-control {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1.5px solid #d1d5db; /* gray-300 */
    border-radius: 0.5rem;
    font-size: 1rem;
    font-weight: 400;
    color: #111827; /* gray-900 */
    background-color: #fff;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    font-family: inherit;
}

.dark input.form-control,
.dark textarea.form-control,
.dark select.form-control {
    background-color: #374151; /* gray-700 */
    color: #d1d5db;
    border-color: #4b5563; /* gray-600 */
}

input.form-control:focus,
textarea.form-control:focus,
select.form-control:focus {
    outline: none;
    border-color: #2563eb; /* blue-600 */
    box-shadow: 0 0 0 3px rgb(59 130 246 / 0.5); /* blue-500 transparent shadow */
    background-color: #fff;
}

.dark input.form-control:focus,
.dark textarea.form-control:focus,
.dark select.form-control:focus {
    background-color: #4b5563; /* slightly lighter dark bg */
}

/* Paragraph text (file info) */
p.text-sm.text-gray-500,
p.text-sm.text-gray-500 a {
    font-size: 0.875rem;
    color: #6b7280; /* gray-500 */
}

.dark p.text-sm.text-gray-500,
.dark p.text-sm.text-gray-500 a {
    color: #9ca3af;
}

p.text-sm.text-gray-500 a:hover {
    text-decoration: underline;
}

/* Buttons container */
.mt-6.flex.justify-between {
    margin-top: 1.5rem;
    display: flex;
    justify-content: space-between;
}

/* Buttons styles */
.btn {
    padding: 0.5rem 1.25rem;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 0.5rem;
    cursor: pointer;
    border-width: 1.5px;
    transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
    user-select: none;
    text-align: center;
    display: inline-block;
    line-height: 1.25;
}

.btn-primary {
    background-color: #2563eb; /* blue-600 */
    color: white;
    border-color: transparent;
}

.btn-primary:hover,
.btn-primary:focus {
    background-color: #1e40af; /* blue-800 */
    outline: none;
}

.btn-outline-secondary {
    background-color: transparent;
    color: #374151; /* gray-700 */
    border-color: #374151;
}

.btn-outline-secondary:hover,
.btn-outline-secondary:focus {
    background-color: #e5e7eb; /* gray-200 */
    border-color: #2563eb;
    color: #2563eb;
    outline: none;
}

/* Responsive tweaks for smaller devices */
@media (max-width: 768px) {
    .mt-6.flex.justify-between {
        flex-direction: column;
        gap: 0.75rem;
    }

    .btn {
        width: 100%;
    }
}

</style>

<!-- Main Content --> 
<div class="max-w-6xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold text-center mb-4">Edit Naskah</h2>
    <hr class="my-4 border-gray-200 dark:border-gray-700">

    <form action="{{ route('admin.naskah.update', $naskah->id) }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT') 

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Judul -->
            <div>
                <label for="judul" class="block text-sm font-semibold">Judul</label>
                <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul', $naskah->judul) }}" required>
            </div>

            <!-- Pengarang -->
            <div>
                <label for="pengarang" class="block text-sm font-semibold">Pengarang</label>
                <input type="text" class="form-control" id="pengarang" name="pengarang" value="{{ old('pengarang', $naskah->pengarang) }}" required>
            </div>

            <!-- Deskripsi Singkat -->
            <div class="col-span-1 md:col-span-2">
                <label for="deskripsi_singkat" class="block text-sm font-semibold">Deskripsi Singkat</label>
                <textarea class="form-control" id="deskripsi_singkat" name="deskripsi_singkat" rows="5" required>{{ old('deskripsi_singkat', $naskah->deskripsi_singkat) }}</textarea>
            </div>

            <!-- Tanggal -->
            <div>
                <label for="tanggal" class="block text-sm font-semibold">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ old('tanggal', $naskah->tanggal ? \Carbon\Carbon::parse($naskah->tanggal)->format('Y-m-d') : '') }}" required>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-semibold">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="" disabled {{ old('status', $naskah->status) ? '' : 'selected' }}>Pilih Status</option>
                    <option value="Dalam Review" {{ old('status', $naskah->status) === 'Dalam Review' ? 'selected' : '' }}>Dalam Review</option>
                    <option value="Siap Terbit" {{ old('status', $naskah->status) === 'Siap Terbit' ? 'selected' : '' }}>Siap Terbit</option>
                    <option value="Ditolak" {{ old('status', $naskah->status) === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <!-- File (opsional, jika ada upload file) -->
            <div class="col-span-1 md:col-span-2">
                <label for="file" class="block text-sm font-semibold">File</label>
                <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx,.txt">
                @if($naskah->file_naskah)
                    <p class="mt-2 text-sm text-gray-500">File saat ini: <a href="{{ asset('storage/' . $naskah->file_naskah) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a></p>
                @endif
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <a href="{{ route('admin.naskah') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>




            <script>
        lucide.createIcons();
    </script>
</body>
</html>

