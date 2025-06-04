<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AkunController extends Controller
{
    public function index()
    {
        return view('akun.profil');
    }

    public function kolaborasi()
    {
        return view('akun.kolaborasi');
    }

    public function pembelian()
    {
        return view('akun.pembelian');
    }

    public function download()
    {
        return view('akun.download');
    }
    public function updateFoto(Request $request)
        {
            $request->validate([
                'foto' => 'image|mimes:jpeg,png,jpg|max:2048'
            ]);

        $user = \App\Models\User::find(1); // Asumsikan user ID 1


            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $namaFile = time() . '.' . $foto->getClientOriginalExtension();
                $foto->storeAs('public/foto_profil', $namaFile);

                // Simpan ke database
                $user->foto = $namaFile;
                $user->save();
            }

            return redirect()->back()->with('success', 'Foto profil berhasil diperbarui.');
        }
            
}
