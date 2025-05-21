@extends('admin.layouts.app')

@section('main')
<div class="container">
    <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200">Dashboard Admin</h1>

    <!-- Kartu Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow text-center">
            <p class="text-gray-500 dark:text-gray-400 text-sm">Total Buku</p>
            <p class="text-xl font-bold text-blue-600 dark:text-blue-400">2.485</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow text-center">
            <p class="text-gray-500 dark:text-gray-400 text-sm">Member</p>
            <p class="text-xl font-bold text-blue-600 dark:text-blue-400">1.234</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow text-center">
            <p class="text-gray-500 dark:text-gray-400 text-sm">Editor</p>
            <p class="text-xl font-bold text-blue-600 dark:text-blue-400">324</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow text-center">
            <p class="text-gray-500 dark:text-gray-400 text-sm">Total Pendapatan</p>
            <p class="text-xl font-bold text-blue-600 dark:text-blue-400">Rp 45.200.000</p>
        </div>
    </div>

    <!-- Additional Dashboard Content -->
    <div class="bg-white dark:bg-gray-800 rounded shadow p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Recent Activities</h2>
        <!-- Add your dashboard content here -->
    </div>

    
</div>
@endsection
