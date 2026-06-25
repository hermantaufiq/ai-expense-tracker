<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class OtpCode extends Model
{
    protected $fillable = [
        'email',
        'otp',
        'expires_at',
        'verified_at',
    ];

    protected $casts = [
        'expires_at'  => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * Check whether this OTP record is still valid (not expired, not verified).
     */
    public function isValid(): bool
    {
        return is_null($this->verified_at) && $this->expires_at->isFuture();
    }

    /**
     * Generate a fresh 6-digit OTP and persist it for the given email.
     * Any previous OTP for the same email is deleted first.
     */
    public static function generate(string $email): string
    {
        // Remove old OTPs for this email
        static::where('email', $email)->delete();

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        static::create([
            'email'      => $email,
            'otp'        => bcrypt($code),
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        return $code;
    }
}
