<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekLogin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Silakan login terlebih dahulu'], 401);
            }
            
            return redirect()->route('login')->with('message', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
