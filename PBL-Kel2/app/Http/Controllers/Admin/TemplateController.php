<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::with('uploader')->latest()->get();
        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'required|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('templates', $fileName, 'public');

            Template::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'uploaded_by' => Auth::id(),
            ]);

            return redirect()->route('template.index')->with('success', 'Template berhasil diupload!');
        }

        return back()->with('error', 'Gagal mengupload file!');
    }

    public function show($id)
    {
        $template = Template::with('uploader')->findOrFail($id);
        return view('admin.templates.show', compact('template'));
    }

    public function edit($id)
    {
        $template = Template::findOrFail($id);
        return view('admin.templates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $template = Template::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        $updateData = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
        ];

        // If new file is uploaded
        if ($request->hasFile('file')) {
            // Delete old file
            if (Storage::disk('public')->exists($template->file_path)) {
                Storage::disk('public')->delete($template->file_path);
            }

            // Store new file
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('templates', $fileName, 'public');

            $updateData['file_path'] = $filePath;
            $updateData['file_name'] = $file->getClientOriginalName();
            $updateData['file_size'] = $file->getSize();
        }

        $template->update($updateData);

        return redirect()->route('template.show', $template->id)->with('success', 'Template berhasil diupdate!');
    }

    public function destroy($id)
    {
        $template = Template::findOrFail($id);
        
        // Delete file from storage
        if (Storage::disk('public')->exists($template->file_path)) {
            Storage::disk('public')->delete($template->file_path);
        }

        $template->delete();

        return redirect()->route('template.index')->with('success', 'Template berhasil dihapus!');
    }

    public function download($id)
    {
        $template = Template::findOrFail($id);
        
        if (!Storage::disk('public')->exists($template->file_path)) {
            return back()->with('error', 'File tidak ditemukan!');
        }

        return Storage::disk('public')->download($template->file_path, $template->file_name);
    }

    public function toggleStatus($id)
    {
        $template = Template::findOrFail($id);
        $template->update(['is_active' => !$template->is_active]);

        $status = $template->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Template berhasil {$status}!");
    }
}
