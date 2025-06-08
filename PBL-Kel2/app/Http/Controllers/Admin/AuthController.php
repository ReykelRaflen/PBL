<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $credentials = $request->only('email', 'password');

        // Cari user berdasarkan email dan role admin
        $user = User::where('email', $credentials['email'])
                   ->where('role', 'admin')
                   ->first();

        // Cek apakah user ada dan password benar
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Cek apakah email sudah diverifikasi (jika diperlukan)
            if (!$user->is_verified) {
                return back()->withErrors([
                    'email' => 'Akun Anda belum diverifikasi. Silakan verifikasi email terlebih dahulu.'
                ]);
            }

            // Login user
            Auth::login($user);
            $request->session()->regenerate();
            
            return redirect()->intended('/admin/dashboard')->with('success', 'Selamat datang, ' . $user->name . '!');
        }

        // Jika gagal login
        throw ValidationException::withMessages([
            'email' => ['Email atau password salah, atau Anda bukan admin.'],
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/admin/login')->with('success', 'Anda berhasil logout.');
    }
}
