<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function callback($provider)
    {
        try {
            // Karena menggunakan Laragon/Localhost (cURL error 60), matikan verifikasi SSL
            $socialUser = Socialite::driver($provider)
                ->stateless()
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->user();
            
            
            // Find existing user by provider ID or email
            $user = User::where($provider . '_id', $socialUser->getId())
                        ->orWhere('email', $socialUser->getEmail())
                        ->first();

            if ($user) {
                // Update provider ID if it was matched by email but provider ID is null
                if (! $user->{$provider . '_id'}) {
                    $user->update([
                        $provider . '_id' => $socialUser->getId(),
                    ]);
                }
                
                Auth::login($user);
                return redirect()->intended(route('dashboard', absolute: false));
            }

            // Create new user if not found
            $newUser = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                'email' => $socialUser->getEmail(),
                $provider . '_id' => $socialUser->getId(),
                'role' => 'user',
                'is_premium' => false,
                'password' => null, // No password for social logins
            ]);

            Auth::login($newUser);
            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            \Log::error('Socialite Error: ' . get_class($e) . ' - ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('login')->withErrors(['email' => 'Gagal login dengan ' . ucfirst($provider) . '. Pastikan kredensial di .env sudah diset atau coba lagi.']);
        }
    }
}
