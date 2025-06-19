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
        $credentials = $this->validateLogin($request);

        $user = $this->getAdminUser($credentials['email']);

        if ($this->isValidAdminLogin($user, $credentials['password'])) {
            if (!$this->isVerified($user)) {
                return back()->withErrors([
                    'email' => 'Akun Anda belum diverifikasi. Silakan verifikasi email terlebih dahulu.'
                ]);
            }

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended('/admin/dashboard')->with('success', 'Selamat datang, ' . $user->name . '!');
        }

        throw ValidationException::withMessages([
            'email' => ['Email atau password salah, atau Anda bukan admin.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login')->with('success', 'Anda berhasil logout.');
    }

    // === Helper Methods ===

    protected function validateLogin(Request $request)
    {
        return $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);
    }

    protected function getAdminUser($email)
    {
        return User::where('email', $email)
                   ->where('role', 'admin')
                   ->first();
    }

    protected function isValidAdminLogin($user, $password)
    {
        return $user && Hash::check($password, $user->password);
    }

    protected function isVerified($user)
    {
        return $user->is_verified;
    }
}
