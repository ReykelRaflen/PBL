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
          <a href="{{route('admin.dashboard')}}" class="flex items-center gap-2 py-2 px-2 rounded hover:bg-blue-100 dark:hover:bg-gray-700 transition">
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
          <a href="#" class="flex items-center gap-2 py-1 px-4 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
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
              <a href="#" class="flex items-center gap-2 py-1 px-6 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
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
            <a href="{{ route('penerbitanIndividu.index') }}" class="flex items-center gap-2 py-1 px-6 rounded hover:bg-blue-100 dark:hover:bg-gray-700">
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
    <div class="ml-64 flex-1 p-8">
      <main class="bg-gray-100 dark:bg-gray-900 min-h-screen">
        @yield('main')
      </main>
    </div>
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

  <script>
    lucide.createIcons();
  </script>
</body>
</html>
