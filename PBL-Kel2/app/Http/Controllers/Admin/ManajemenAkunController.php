<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ManajemenAkunController extends Controller
{
    public function index(Request $request)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        $query = User::query();

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('nomor_telepon', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan role
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $users = $query->latest()->paginate(10);

        return view('admin.account.index', compact('users'));
    }

    public function create()
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        return view('admin.account.create');
    }

    public function store(Request $request)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role wajib dipilih.',
            'foto_profil.image' => 'File harus berupa gambar.',
            'foto_profil.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        try {
            DB::beginTransaction();

            // Handle upload foto profil
            if ($request->hasFile('foto_profil')) {
                $validated['foto_profil'] = $request->file('foto_profil')->store('profile-photos', 'public');
            }

            // Hash password
            $validated['password'] = Hash::make($validated['password']);
            $validated['is_active'] = $request->has('is_active');

            // Buat user baru
            $user = User::create($validated);

            DB::commit();

            return redirect()->route('admin.accounts.index')
                ->with('success', 'Akun berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();

            // Hapus file yang sudah diupload jika ada error
            if (isset($validated['foto_profil'])) {
                Storage::disk('public')->delete($validated['foto_profil']);
            }

            Log::error('Error creating account: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat akun.'])
                ->withInput();
        }
    }

    public function show(User $account)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        return view('admin.account.show', ['user' => $account]);
    }

    public function edit(User $account)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        return view('admin.account.edit', ['user' => $account]);
    }

    public function update(Request $request, User $account)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($account->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role wajib dipilih.',
            'foto_profil.image' => 'File harus berupa gambar.',
            'foto_profil.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        try {
            DB::beginTransaction();

            $oldPhoto = $account->foto_profil;

            // Handle upload foto profil
            if ($request->hasFile('foto_profil')) {
                $validated['foto_profil'] = $request->file('foto_profil')->store('profile-photos', 'public');
            }

            // Hash password jika diisi
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $validated['is_active'] = $request->has('is_active');

            // Update user
            $account->update($validated);

            // Hapus foto lama setelah update berhasil
            if ($request->hasFile('foto_profil') && $oldPhoto) {
                Storage::disk('public')->delete($oldPhoto);
            }

            DB::commit();

            return redirect()->route('account.index')
                ->with('success', 'Akun berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();

            // Hapus file baru yang sudah diupload jika ada error
            if ($request->hasFile('foto_profil') && isset($validated['foto_profil'])) {
                Storage::disk('public')->delete($validated['foto_profil']);
            }

            Log::error('Error updating account: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui akun.'])
                ->withInput();
        }
    }

    public function destroy(User $account)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/admin/login')->withErrors(['access' => 'Akses ditolak.']);
        }

        try {
            // Tidak bisa menghapus diri sendiri
            if ($account->id === Auth::id()) {
                return back()->withErrors(['error' => 'Tidak dapat menghapus akun sendiri.']);
            }

            DB::beginTransaction();

            // Simpan path foto untuk dihapus
            $photoPath = $account->foto_profil;

            // Hapus user dari database
            $account->delete();

            // Hapus foto profil
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            DB::commit();

            return redirect()->route('account.index')
                ->with('success', 'Akun berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting account: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus akun.']);
        }
    }

    public function toggleStatus(User $account)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        try {
            // Tidak bisa menonaktifkan diri sendiri
            if ($account->id === Auth::id()) {
                return response()->json(['error' => 'Tidak dapat mengubah status akun sendiri.'], 400);
            }

            $account->is_active = !$account->is_active;
            $account->save();

            return response()->json([
                'success' => true,
                'message' => 'Status akun berhasil diubah.',
                'is_active' => $account->is_active
            ]);

        } catch (\Exception $e) {
            Log::error('Error toggling account status: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan.'], 500);
        }
    }

    public function resetPassword(User $account)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        try {
            // Generate password baru
            $newPassword = 'password123';
            $account->password = Hash::make($newPassword);
            $account->save();

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil direset.',
                'new_password' => $newPassword
            ]);

        } catch (\Exception $e) {
            Log::error('Error resetting password: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan.'], 500);
        }
    }
}
