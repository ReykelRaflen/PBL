<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Cek apakah user login dan role adalah admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak. Anda harus login sebagai admin.']);
        }

        $user = Auth::user();
        return view('admin.dashboard', compact('user'));
    }
}
