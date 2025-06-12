<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function index()
 {
    $templates = Template::where('is_active', true)
                       ->latest()
                       ->get();
    
    return view('user.akun.templates.templates', compact('templates'));
}

    public function download($id)
    {
        $template = Template::where('is_active', true)->findOrFail($id);
        
        if (!Storage::disk('public')->exists($template->file_path)) {
            return back()->with('error', 'File tidak ditemukan!');
        }

        return Storage::disk('public')->download($template->file_path, $template->file_name);
    }

    public function show($id)
    {
        $template = Template::where('is_active', true)->findOrFail($id);
        return view('user.akun.templates.show', compact('template'));
    }
}
