<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\PesananKolaborasi;

class AkunController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('user.akun.profil', compact('user'));
    }

    public function profil()
    {
        $user = Auth::user();
        return view('user.akun.profil', compact('user'));
    }

    public function kolaborasi()
    {
        $pesananKolaborasi = PesananKolaborasi::with(['bukuKolaboratif', 'babBuku'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.akun.kolaborasi', compact('pesananKolaborasi'));
    }

    public function pembelian()
    {
        return view('user.akun.pembelian');
    }

    public function download()
    {
        return view('user.akun.download');
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        // Validasi input - agama, gender, birthdate menjadi optional
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]*$/',
            'address' => 'nullable|string|max:500',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'agama' => 'nullable|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'birthdate' => 'nullable|date|before:today|after:1900-01-01',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',
            'phone.regex' => 'Format nomor telepon tidak valid.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'address.max' => 'Alamat maksimal 500 karakter.',
            'gender.in' => 'Jenis kelamin tidak valid.',
            'agama.in' => 'Pilihan agama tidak valid.',
            'birthdate.date' => 'Format tanggal lahir tidak valid.',
            'birthdate.before' => 'Tanggal lahir harus sebelum hari ini.',
            'birthdate.after' => 'Tanggal lahir tidak valid.',
        ]);

        try {
            // Format nomor telepon
            $phone = $validatedData['phone'] ?? null;
            if ($phone) {
                $phone = preg_replace('/[^0-9]/', '', $phone);
                
                if (str_starts_with($phone, '0')) {
                    $phone = '62' . substr($phone, 1);
                } elseif (!str_starts_with($phone, '62')) {
                    $phone = '62' . $phone;
                }
            }

            // Update data user
            $user->update([
                'name' => $validatedData['name'],
                'phone' => $phone,
                'address' => $validatedData['address'] ?? null,
                'gender' => $validatedData['gender'] ?? null,
                'agama' => $validatedData['agama'] ?? null,
                'birthdate' => $validatedData['birthdate'] ?? null,
            ]);

            return redirect()->back()->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Error updating profile: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui profil. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function updateFoto(Request $request)
    {
        // Validasi file
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'foto.required' => 'Silakan pilih foto terlebih dahulu.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpeg, png, jpg, atau gif.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        try {
            $user = Auth::user();
            
            Log::info('=== FOTO UPLOAD START ===');
            Log::info('User ID: ' . $user->id);
            Log::info('User Name: ' . $user->name);

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                
                Log::info('File Info:', [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                    'is_valid' => $file->isValid()
                ]);

                if (!$file->isValid()) {
                    Log::error('File is not valid');
                    return redirect()->back()->with('error', 'File tidak valid.');
                }

                // Hapus foto lama jika ada
                if ($user->foto) {
                    $oldPhotoPath = public_path('uploads/foto_profil/' . $user->foto);
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                        Log::info('Old photo deleted: ' . $oldPhotoPath);
                    }
                }

                // Buat nama file baru
                $fileName = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                Log::info('New filename: ' . $fileName);

                // Buat direktori jika belum ada
                $uploadPath = public_path('uploads/foto_profil');
                if (!file_exists($uploadPath)) {
                    if (!mkdir($uploadPath, 0755, true)) {
                        Log::error('Failed to create directory: ' . $uploadPath);
                        return redirect()->back()->with('error', 'Gagal membuat direktori upload.');
                    }
                    Log::info('Directory created: ' . $uploadPath);
                }

                // Upload file
                $fullPath = $uploadPath . '/' . $fileName;
                Log::info('Attempting to move file to: ' . $fullPath);

                if ($file->move($uploadPath, $fileName)) {
                    Log::info('File moved successfully');
                    
                    // Verifikasi file benar-benar ada
                    if (file_exists($fullPath)) {
                        Log::info('File verified exists at: ' . $fullPath);
                        
                        // Update database
                        $user->foto = $fileName;
                        if ($user->save()) {
                            Log::info('Database updated successfully');
                            return redirect()->back()->with('success', 'Foto profil berhasil diperbarui!');
                        } else {
                            Log::error('Failed to update database');
                            // Hapus file jika gagal update database
                            unlink($fullPath);
                            return redirect()->back()->with('error', 'Gagal menyimpan ke database.');
                        }
                    } else {
                        Log::error('File does not exist after move');
                        return redirect()->back()->with('error', 'File tidak ditemukan setelah upload.');
                    }
                } else {
                    Log::error('Failed to move file');
                    return redirect()->back()->with('error', 'Gagal memindahkan file.');
                }
            } else {
                Log::error('No file in request');
                return redirect()->back()->with('error', 'Tidak ada file yang dipilih.');
            }

        } catch (\Exception $e) {
            Log::error('Exception in updateFoto: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteFoto()
    {
        try {
            $user = Auth::user();

            if ($user->foto) {
                $photoPath = public_path('uploads/foto_profil/' . $user->foto);
                
                // Hapus file foto
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                    Log::info('Photo deleted: ' . $photoPath);
                }

                // Update database
                $user->foto = null;
                $user->save();

                return redirect()->back()->with('success', 'Foto profil berhasil dihapus!');
            }

            return redirect()->back()->with('error', 'Tidak ada foto untuk dihapus.');

        } catch (\Exception $e) {
            Log::error('Error deleting photo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus foto.');
        }
    }

    public function changePassword(Request $request)
    {
        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        try {
            $user = Auth::user();

            // Cek password saat ini
            if (!Hash::check($validatedData['current_password'], $user->password)) {
                return redirect()->back()
                    ->with('error', 'Password saat ini tidak benar.')
                    ->withInput();
            }

            // Update password
            $user->update([
                'password' => Hash::make($validatedData['new_password'])
            ]);

            return redirect()->back()->with('success', 'Password berhasil diubah!');

        } catch (\Exception $e) {
            Log::error('Error changing password: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah password.');
        }
    }
}
