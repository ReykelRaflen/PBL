@extends('admin.layouts.app')

@section('content')
  <h1 class="text-2xl font-bold mb-6 text-gray-800">Dashboard Admin</h1>

  <!-- Kartu Statistik -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-4 rounded shadow text-center">
      <p class="text-gray-500 text-sm">Total Buku</p>
      <p class="text-xl font-bold text-blue-600">2.485</p>
    </div>
    <div class="bg-white p-4 rounded shadow text-center">
      <p class="text-gray-500 text-sm">Member</p>
      <p class="text-xl font-bold text-blue-600">1.234</p>
    </div>
    <div class="bg-white p-4 rounded shadow text-center">
      <p class="text-gray-500 text-sm">Editor</p>
      <p class="text-xl font-bold text-blue-600">324</p>
    </div>
    <div class="bg-white p-4 rounded shadow text-center">
      <p class="text-gray-500 text-sm">Total Pendapatan</p>
      <p class="text-xl font-bold text-blue-600">Rp 45.200.000</p>
    </div>
  </div>

  <!-- Tambahkan konten lainnya sesuai keperluan -->
@endsection
