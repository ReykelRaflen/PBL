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
use Illuminate\Support\Str;

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
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan role
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_verified', $request->status);
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

        // Validasi sesuai dengan field di view dan database
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user,editor',
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'agama' => 'nullable|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
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
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            'agama.in' => 'Agama tidak valid.',
        ]);

        try {
            DB::beginTransaction();

            // Handle upload foto profil
            $fotoPath = null;
            if ($request->hasFile('foto_profil')) {
                $file = $request->file('foto_profil');
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                
                // Pastikan folder profile_photos ada
                $uploadPath = public_path('profile_photos');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Pindahkan file ke public/profile_photos
                $file->move($uploadPath, $filename);
                $fotoPath = $filename;
            }

            // Hash password
            $hashedPassword = Hash::make($validated['password']);
            
            // Mapping field view ke database sesuai migration
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $hashedPassword,
                'role' => $validated['role'],
                'phone' => $validated['nomor_telepon'] ?? null,
                'address' => $validated['alamat'] ?? null,
                'birthdate' => $validated['tanggal_lahir'] ?? null,
                'gender' => $validated['jenis_kelamin'] ?? null,
                'agama' => $validated['agama'] ?? null,
                'foto' => $fotoPath, // Field 'foto' sesuai migration
                'is_verified' => $request->has('is_active') ? true : false,
            ];

            // Buat user baru
            $user = User::create($userData);

            DB::commit();

            return redirect()->route('admin.account.index')
                ->with('success', 'Akun berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();

            // Hapus file yang sudah diupload jika ada error
            if ($fotoPath && file_exists(public_path('profile_photos/' . $fotoPath))) {
                unlink(public_path('profile_photos/' . $fotoPath));
            }

            Log::error('Error creating account: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat akun: ' . $e->getMessage()])
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
            'role' => 'required|in:admin,user,editor',
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'agama' => 'nullable|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
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

            $oldPhoto = $account->foto; // Field 'foto' dari database

            // Handle upload foto profil
            $fotoPath = $oldPhoto; // Keep old photo by default
            if ($request->hasFile('foto_profil')) {
                $file = $request->file('foto_profil');
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                
                // Pastikan folder profile_photos ada
                $uploadPath = public_path('profile_photos');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Pindahkan file ke public/profile_photos
                $file->move($uploadPath, $filename);
                $fotoPath = $filename;
            }

            // Mapping field view ke database
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'phone' => $validated['nomor_telepon'] ?? null,
                'address' => $validated['alamat'] ?? null,
                'birthdate' => $validated['tanggal_lahir'] ?? null,
                'gender' => $validated['jenis_kelamin'] ?? null,
                'agama' => $validated['agama'] ?? null,
                'foto' => $fotoPath, // Field 'foto' sesuai migration
                'is_verified' => $request->has('is_active'),
            ];

            // Hash password jika diisi
            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            // Update user
            $account->update($updateData);

            // Hapus foto lama setelah update berhasil
            if ($request->hasFile('foto_profil') && $oldPhoto && $oldPhoto !== $fotoPath) {
                if (file_exists(public_path('profile_photos/' . $oldPhoto))) {
                    unlink(public_path('profile_photos/' . $oldPhoto));
                }
            }

            DB::commit();

            return redirect()->route('admin.account.index')
                ->with('success', 'Akun berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();

            // Hapus file baru yang sudah diupload jika ada error
            if ($request->hasFile('foto_profil') && isset($fotoPath) && $fotoPath !== $oldPhoto) {
                if (file_exists(public_path('profile_photos/' . $fotoPath))) {
                    unlink(public_path('profile_photos/' . $fotoPath));
                }
            }

            Log::error('Error updating account: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui akun: ' . $e->getMessage()])
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
            $photoPath = $account->foto;

            // Hapus user dari database
            $account->delete();

            // Hapus foto profil dari public/profile_photos
            if ($photoPath && file_exists(public_path('profile_photos/' . $photoPath))) {
                unlink(public_path('profile_photos/' . $photoPath));
            }

            DB::commit();

            return redirect()->route('admin.account.index')
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

            // Toggle status is_verified
            $account->is_verified = !$account->is_verified;
            $account->save();

            return response()->json([
                'success' => true,
                'message' => 'Status akun berhasil diubah.',
                'is_verified' => $account->is_verified
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
