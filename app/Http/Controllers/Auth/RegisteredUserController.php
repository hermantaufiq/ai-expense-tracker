<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * Validates input, saves data to session, sends OTP, redirects to OTP page.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Save registration data in session temporarily
        $request->session()->put('register_pending', [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate OTP and send email
        $otp = OtpCode::generate($request->email);

        Mail::to($request->email)->send(new OtpMail($otp, $request->name));

        return redirect()->route('register.otp')
            ->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    /**
     * Show the OTP verification page.
     */
    public function showOtp(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('register_pending')) {
            return redirect()->route('register')
                ->withErrors(['email' => 'Sesi pendaftaran tidak valid. Silakan daftar ulang.']);
        }

        $email = $request->session()->get('register_pending.email');

        return view('auth.otp-verify', compact('email'));
    }

    /**
     * Verify the OTP and create the user account.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        if (! $request->session()->has('register_pending')) {
            return redirect()->route('register')
                ->withErrors(['email' => 'Sesi pendaftaran kedaluwarsa. Silakan daftar ulang.']);
        }

        $pending = $request->session()->get('register_pending');
        $email   = $pending['email'];

        // Find the latest OTP record for this email
        $otpRecord = OtpCode::where('email', $email)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (! $otpRecord || ! $otpRecord->isValid()) {
            return back()->withErrors([
                'otp' => 'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.',
            ]);
        }

        if (! Hash::check($request->otp, $otpRecord->otp)) {
            return back()->withErrors([
                'otp' => 'Kode OTP tidak valid. Periksa kembali kode yang dikirim ke email Anda.',
            ]);
        }

        // Mark OTP as used
        $otpRecord->update(['verified_at' => now()]);

        // Create the user
        $user = User::create([
            'name'     => $pending['name'],
            'email'    => $pending['email'],
            'password' => $pending['password'],
        ]);

        // Clear session
        $request->session()->forget('register_pending');

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('dashboard');
    }

    /**
     * Resend a fresh OTP to the pending registration email.
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        if (! $request->session()->has('register_pending')) {
            return redirect()->route('register')
                ->withErrors(['email' => 'Sesi pendaftaran tidak valid. Silakan daftar ulang.']);
        }

        $pending = $request->session()->get('register_pending');

        $otp = OtpCode::generate($pending['email']);

        Mail::to($pending['email'])->send(new OtpMail($otp, $pending['name']));

        return redirect()->route('register.otp')
            ->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}
