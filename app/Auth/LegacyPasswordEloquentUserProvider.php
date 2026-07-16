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
 * PENTING: Hash::check() di Laravel 13 akan melempar RuntimeException kalau
 * nilai yang dicek bukan format bcrypt sama sekali (bukan cuma return false).
 * Jadi format hash-nya HARUS dicek dulu sebelum Hash::check() dipanggil -
 * tidak boleh langsung coba Hash::check() lalu fallback ke plain text.
 */
class LegacyPasswordEloquentUserProvider extends EloquentUserProvider
{
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        $plain = $credentials['password'];
        $stored = (string) $user->getAuthPassword();

        $sudahBcrypt = str_starts_with($stored, '$2y$')
            || str_starts_with($stored, '$2a$')
            || str_starts_with($stored, '$2b$');

        if ($sudahBcrypt) {
            return Hash::check($plain, $stored);
        }

        // Password lama, masih plain text: cocokkan langsung, lalu hash ulang.
        if ($stored !== '' && hash_equals($stored, (string) $plain)) {
            $user->forceFill([
                $user->getAuthPasswordName() ?? 'password' => Hash::make($plain),
            ])->save();

            return true;
        }

        return false;
    }
}
