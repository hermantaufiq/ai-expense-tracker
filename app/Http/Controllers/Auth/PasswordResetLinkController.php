<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $email = $request->email;
        $user = \App\Models\User::where('email', $email)->first();

        // Generate OTP and send email
        $otp = \App\Models\OtpCode::generate($email);
        
        \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\OtpMail($otp, $user->name));

        // Store email in session to verify OTP later
        session(['password_reset_email' => $email]);

        return redirect()->route('password.otp')
            ->with('success', 'Kode OTP telah dikirim ke email Anda untuk reset password.');
    }
    
    /**
     * Show the OTP verification page for password reset.
     */
    public function showOtp(Request $request): View|RedirectResponse
    {
        $email = session('password_reset_email');
        
        if (! $email) {
            return redirect()->route('password.request');
        }
        
        return view('auth.reset-password-otp', compact('email'));
    }
    
    /**
     * Verify the OTP and redirect to new password form.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);
        
        $email = session('password_reset_email');
        
        if (! $email) {
            return redirect()->route('password.request');
        }
        
        // Find the latest OTP record for this email
        $otpRecord = \App\Models\OtpCode::where('email', $email)
            ->latest()
            ->first();

        if (! $otpRecord || ! $otpRecord->isValid()) {
            return back()->withErrors([
                'otp' => 'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.',
            ]);
        }

        if (! \Illuminate\Support\Facades\Hash::check($request->otp, $otpRecord->otp)) {
            return back()->withErrors([
                'otp' => 'Kode OTP tidak valid. Periksa kembali kode yang dikirim ke email Anda.',
            ]);
        }

        // Mark OTP as used
        $otpRecord->update(['verified_at' => now()]);
        
        // Mark session that OTP is verified so we can show the reset form
        session(['password_reset_verified' => true]);
        
        return redirect()->route('password.reset.form')
            ->with('success', 'OTP diverifikasi, silakan buat password baru.');
    }
    
    /**
     * Resend a fresh OTP to the pending reset email.
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $email = session('password_reset_email');
        
        if (! $email) {
            return redirect()->route('password.request');
        }
        
        $user = \App\Models\User::where('email', $email)->first();
        $otp = \App\Models\OtpCode::generate($email);
        
        \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\OtpMail($otp, $user->name ?? 'Pengguna'));
        
        return redirect()->route('password.otp')
            ->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}
