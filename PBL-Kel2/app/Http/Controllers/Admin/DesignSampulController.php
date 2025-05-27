<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DesignSampul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DesignSampulController extends Controller
{
    /* ---------- INDEX ---------- */
    public function index()
    {
        $items = DesignSampul::latest()->get();
        return view('admin.DesignSampul.index', ['DesignSampul' => $items]);
    }

    /* ---------- CREATE ---------- */
    public function create()
    {
        return view('admin.DesignSampul.create');
    }

    /* ---------- STORE ---------- */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_proyek'   => 'required|string|max:100',
            'jenis_design'  => 'required|string|max:255',
            'editor'        => 'required|string|max:100',
            'tanggal_kirim' => 'required|date',
            'sampul'        => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // simpan berkas
        $path = $request->file('sampul')->store('design_sampul', 'public');
        $data['sampul'] = $path;

        DesignSampul::create($data);

        return redirect()->route('DesignSampul.index')
                         ->with('success', 'Data berhasil ditambahkan.');
    }

    /* ---------- EDIT ---------- */
    public function edit($id)
    {
        $DesignSampul = DesignSampul::findOrFail($id);
        return view('admin.DesignSampul.edit', compact('DesignSampul'));
    }

    /* ---------- UPDATE ---------- */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_proyek'   => 'required|string|max:100',
            'jenis_design'  => 'required|string|max:255',
            'editor'        => 'required|string|max:100',
            'tanggal_kirim' => 'required|date',
            'sampul'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $item = DesignSampul::findOrFail($id);

        // bila user ganti foto
        if ($request->hasFile('sampul')) {
            // hapus file lama
            if ($item->sampul && Storage::disk('public')->exists($item->sampul)) {
                Storage::disk('public')->delete($item->sampul);
            }
            $validated['sampul'] = $request->file('sampul')
                                           ->store('design_sampul', 'public');
        }

        $item->update($validated);

        return redirect()->route('DesignSampul.index')
                         ->with('success', 'Data berhasil diperbarui.');
    }

    /* ---------- DESTROY ---------- */
    public function destroy($id)
    {
        $item = DesignSampul::findOrFail($id);

        // hapus file fisik
        if ($item->sampul && Storage::disk('public')->exists($item->sampul)) {
            Storage::disk('public')->delete($item->sampul);
        }

        $item->delete();

        return redirect()->route('DesignSampul.index')
                         ->with('success', 'Data berhasil dihapus.');
    }
}
