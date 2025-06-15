<!DOCTYPE html>
<html lang="en" x-data="app" x-init="init()" :class="{ 'dark': dark }">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
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
    <button @click="dark = !dark"
            class="bg-gray-300 dark:bg-gray-700 text-black dark:text-white p-2 rounded-full shadow">
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
          <button @click="openMenu === 'master' ? openMenu = '' : openMenu = 'master'"
                  class="w-full flex items-center gap-2 text-left py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
            <i data-lucide="database" class="w-4 h-4"></i> Master Data
          </button>
          <ul x-show="openMenu === 'master'" x-transition class="pl-4 space-y-1">
            <li>
              <a href="#" class="flex items-center gap-2 py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
                <i data-lucide="folder" class="w-4 h-4"></i> Kategori Buku
              </a>
            </li>
            <li>
              <a href="/admin/rekening" class="flex items-center gap-2 py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
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
          <button @click="openMenu === 'editor' ? openMenu = '' : openMenu = 'editor'"
                  class="w-full flex items-center gap-2 text-left py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
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
          <button @click="openMenu === 'penjualan' ? openMenu = '' : openMenu = 'penjualan'"
                  class="w-full flex items-center gap-2 text-left py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
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
          <button @click="openMenu === 'penerbitan' ? openMenu = '' : openMenu = 'penerbitan'"
                  class="w-full flex items-center gap-2 text-left py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
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
          <button @click="openMenu === 'settingAdmin' ? openMenu = '' : openMenu = 'settingAdmin'"
                  class="w-full flex items-center gap-2 text-left py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
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
<div class="mb-6"></div>

<div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mb-6">
    <div class="p-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Daftar Rekening</h1>
        <a href="{{ route('admin.rekening.create') }}"
           class="bg-[#7B3FF2] hover:bg-[#6c2edb] text-white px-5 py-2 rounded-lg flex items-center gap-2 font-medium transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Rekening
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider bg-white border-b border-gray-100">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider bg-white border-b border-gray-100">Bank</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider bg-white border-b border-gray-100">Nomor Rekening</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider bg-white border-b border-gray-100">Nama Pemilik</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider bg-white border-b border-gray-100">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse($rekening as $index)
                <tr class="border-b border-gray-100">
                    <td class="px-6 py-4 text-base text-gray-800 font-medium">{{ $index->id }}</td>
                    <td class="px-6 py-4 text-base text-gray-800 font-medium">{{ $index->bank }}</td>
                    <td class="px-6 py-4 text-base text-gray-800 font-medium">{{ $index->nomor_rekening }}</td>
                    <td class="px-6 py-4 text-base text-gray-800 font-medium">{{ $index->nama_pemilik }}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-3">
                            <!-- Lihat -->
                            <a href="{{ route('admin.rekening.show', $index->id) }}" class="text-[#7B3FF2] hover:text-[#5f2bb6]" title="Lihat Detail">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <!-- Edit -->
                            <a href="{{ route('admin.rekening.edit', $index->id) }}" class="text-[#16C784] hover:text-[#0d8f5c]" title="Edit">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 21h16M4 17.5V21m16-3.5V21M16.5 3.5a2.121 2.121 0 113 3L7 19.5H4v-3L16.5 3.5z"/>
                                </svg>
                            </a>
                            <!-- Hapus -->
                            <form action="{{ route('admin.rekening.destroy', $index->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus rekening ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[#FF4D4F] hover:text-[#d9363e]" title="Hapus">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 19a2 2 0 002 2h8a2 2 0 002-2V7H6v12zm2-9v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">Tidak ada data rekening</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
   
<!-- Pagination -->
<div class="bg-[#F7F8FA] px-4 py-4 rounded-lg flex flex-col md:flex-row justify-between items-center gap-2">
    <div class="text-sm text-gray-700"> 
        Showing 1 to 25 of {{ $rekening->total() }} entries
    </div>
    <div class="flex items-center gap-1">
        <a href="{{ $rekening->previousPageUrl() }}" class="px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-200 rounded-md {{ $rekening->onFirstPage() ? 'opacity-50 cursor-not-allowed pointer-events-none' : 'hover:bg-gray-100 hover:text-gray-600' }}">
            Previous
        </a>
        @for ($i = 1; $i <= $rekening->lastPage(); $i++)
            <a href="{{ $rekening->url($i) }}" class="px-4 py-2 text-sm font-medium border border-gray-200 rounded-md
                {{ $rekening->currentPage() == $i 
                    ? 'bg-[#7B3FF2] text-white' 
                    : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                {{ $i }}
            </a>
        @endfor
        <a href="{{ $rekening->nextPageUrl() }}" class="px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-200 rounded-md {{ !$rekening->hasMorePages() ? 'opacity-50 cursor-not-allowed pointer-events-none' : 'hover:bg-gray-100 hover:text-gray-600' }}">
            Next
        </a>
    </div>
</div>




            <script>
        lucide.createIcons();
    </script>
</body>
</html>


