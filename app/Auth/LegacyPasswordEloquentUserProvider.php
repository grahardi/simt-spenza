<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;

/**
 * Provider ini menjembatani password lama yang tersimpan plain text
 * (dibandingkan langsung "$password == $pass" di masuk.php) ke bcrypt,
 * TANPA memaksa reset password semua akun sekaligus.
 *
 * Alurnya per akun:
 * 1. Saat login, coba cara aman dulu: Hash::check() (untuk akun yang
 *    sudah pernah berhasil login lewat sistem baru ini).
 * 2. Kalau gagal DAN nilai di database masih terlihat seperti plain text
 *    (bukan hash bcrypt) DAN cocok persis dengan input -> login diizinkan,
 *    lalu password langsung di-hash ulang dan disimpan. Login berikutnya
 *    sudah lewat jalur hash yang aman.
 */
class LegacyPasswordEloquentUserProvider extends EloquentUserProvider
{
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        $plain = $credentials['password'];
        $stored = $user->getAuthPassword();

        if (Hash::check($plain, $stored)) {
            return true;
        }

        $terlihatSepertiHash = str_starts_with($stored, '$2y$') || str_starts_with($stored, '$2a$');

        if (!$terlihatSepertiHash && hash_equals((string) $stored, (string) $plain)) {
            $user->forceFill([
                $user->getAuthPasswordName() ?? 'password' => Hash::make($plain),
            ])->save();

            return true;
        }

        return false;
    }
}
