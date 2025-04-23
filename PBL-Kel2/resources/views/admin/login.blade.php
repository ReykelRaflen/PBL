<!DOCTYPE html>
<html lang="en" x-data="app" x-init="init()" :class="{ 'dark': dark }">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
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

  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 flex justify-center items-center h-screen">

  <div class="w-full max-w-md p-8 bg-white dark:bg-gray-800 shadow-md rounded-xl">
    <div class="text-center mb-6">
      <img src="/images/admin/logo-fanya1.png" alt="Logo" class="mx-auto h-12">
      <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mt-4">Login Admin</h2>
      <p class="text-sm text-gray-600 dark:text-gray-400">Masukkan kredensial Anda untuk masuk</p>
    </div>

    <form action="{{ route('admin.login') }}" method="POST" class="space-y-4">
      @csrf

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
        <input type="email" id="email" name="email" class="w-full mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" required autofocus>
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
        <input type="password" id="password" name="password" class="w-full mt-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" required>
      </div>

      <!-- Login Button -->
      <div>
        <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
          Login
        </button>
      </div>

      <!-- Error Message -->
      @if (session('error'))
        <div class="mt-2 text-center text-red-500 text-sm">
          {{ session('error') }}
        </div>
      @endif
    </form>

    <div class="mt-4 text-center">
      <a href="{{ route('home') }}" class="text-sm text-blue-500 hover:underline">Kembali ke halaman utama</a>
    </div>
  </div>

</body>
</html>
