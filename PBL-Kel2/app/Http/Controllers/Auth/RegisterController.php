<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('user.auth.register');
    }

   public function register(Request $request)
{
    // Validasi dasar
    $request->validate([
        'name'     => 'required|string|max:255',
        'phone'    => 'required|string|max:15',
        'password' => 'required|string|min:6|confirmed',
    ]);

    // Validasi email secara manual
    $existingUser = User::where('email', $request->email)->first();
    if ($existingUser) {
        return back()
            ->withInput($request->except('password', 'password_confirmation'))
            ->withErrors(['email' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau coba login.']);
    }

    // Lanjutkan dengan pendaftaran jika email belum terdaftar
    $otp = rand(100000, 999999);
    
    $user = User::create([
        'name'     => $request->name,
        'phone'    => $request->phone,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'otp'      => $otp,
        'otp_created_at' => Carbon::now(),
        'is_verified' => false,
    ]);

    // Kirim OTP dan redirect
    $this->sendOtpEmail($user, $otp);
    
    return redirect()->route('verification.notice', ['email' => $user->email])
        ->with('success', 'Pendaftaran berhasil! Silakan periksa email Anda untuk kode verifikasi.');
}


    public function showVerificationForm(Request $request)
    {
        return view('user.auth.verify', ['email' => $request->email]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'otp'   => 'required|numeric',
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if OTP is valid and not expired (10 minutes)
        if (!$user || $user->otp != $request->otp || 
            Carbon::parse($user->otp_created_at)->addMinutes(10)->isPast()) {
            
            return back()->withErrors(['otp' => 'Kode verifikasi tidak valid atau telah kedaluwarsa.']);
        }

        // Mark user as verified
        $user->is_verified = true;
        $user->email_verified_at = Carbon::now();
        $user->otp = null; // Clear OTP after successful verification
        $user->save();

        // Log the user in
        Auth::login($user);

        return redirect('/')->with('success', 'Email Anda telah berhasil diverifikasi!');
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Pengguna tidak ditemukan.']);
        }

        // Check if the last OTP was sent less than 1 minute ago
        if ($user->otp_created_at && Carbon::parse($user->otp_created_at)->addMinute()->gt(Carbon::now())) {
            return back()->withErrors(['email' => 'Harap tunggu sebelum meminta kode verifikasi lain.']);
        }

        // Generate new OTP
        $otp = rand(100000, 999999);
        
        // Save OTP to database
        $user->otp = $otp;
        $user->otp_created_at = Carbon::now();
        $user->save();

        // Send OTP email
        $this->sendOtpEmail($user, $otp);

        return back()->with('success', 'Kode verifikasi baru telah dikirim ke email Anda.');
    }

    private function sendOtpEmail($user, $otp)
    {
        $data = [
            'user' => $user,
            'otp' => $otp
        ];

        Mail::send('emails.otp-verification', $data, function($message) use ($user) {
            $message->to($user->email, $user->name)
                    ->subject('Kode Verifikasi Email');
        });
    }
}
