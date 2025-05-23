<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('user.auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email tidak ditemukan dalam sistem kami.'
        ]);

        $user = User::where('email', $request->email)->first();
        
        // Generate OTP
        $otp = rand(100000, 999999);
        
        // Save OTP to database
        $user->otp = $otp;
        $user->otp_created_at = Carbon::now();
        $user->save();

        // Send OTP via email
        $this->sendOtpEmail($user, $otp);

        return redirect()->route('password.reset', ['email' => $user->email])
            ->with('success', 'Kode reset password telah dikirim ke email Anda.');
    }

    public function showResetForm(Request $request)
    {
        return view('user.auth.passwords.reset', ['email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'otp' => 'required|numeric',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if OTP is valid and not expired (10 minutes)
        if (!$user || $user->otp != $request->otp || 
            Carbon::parse($user->otp_created_at)->addMinutes(10)->isPast()) {
            
            return back()->withErrors(['otp' => 'Kode verifikasi tidak valid atau telah kedaluwarsa.']);
        }

        // Reset password
        $user->password = bcrypt($request->password);
        $user->otp = null; // Clear OTP after successful reset
        $user->save();

        return redirect()->route('login')
            ->with('success', 'Password Anda telah berhasil direset. Silakan login dengan password baru Anda.');
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $user = User::where('email', $request->email)->first();

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

        return back()->with('success', 'Kode reset password baru telah dikirim ke email Anda.');
    }

    private function sendOtpEmail($user, $otp)
    {
        $data = [
            'user' => $user,
            'otp' => $otp
        ];

        Mail::send('emails.password-reset-otp', $data, function($message) use ($user) {
            $message->to($user->email, $user->name)
                    ->subject('Kode Reset Password');
        });
    }
}
