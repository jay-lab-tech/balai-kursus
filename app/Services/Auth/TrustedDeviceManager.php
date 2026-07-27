<?php

namespace App\Services\Auth;

use App\Models\TrustedDevice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class TrustedDeviceManager
{
    public const COOKIE_NAME = 'trusted_login';

    public function remember(User $user, Request $request): void
    {
        if ($this->findTrustedDevice($user, $request)) {
            return;
        }

        $plainToken = Str::random(64);

        TrustedDevice::create([
            'user_id' => $user->id,
            'token_hash' => hash('sha256', $plainToken),
            'user_agent' => Str::limit((string) $request->userAgent(), 255, ''),
            'ip_address' => $request->ip(),
            'last_used_at' => now(),
        ]);

        Cookie::queue(Cookie::forever(
            self::COOKIE_NAME,
            $user->id.'|'.$plainToken,
            null,
            null,
            $request->isSecure(),
            true,
            false,
            'lax',
        ));
    }

    public function hasTrustedDevice(User $user, Request $request): bool
    {
        $trustedDevice = $this->findTrustedDevice($user, $request);

        if (! $trustedDevice) {
            return false;
        }

        $trustedDevice->forceFill([
            'last_used_at' => now(),
            'user_agent' => Str::limit((string) $request->userAgent(), 255, ''),
            'ip_address' => $request->ip(),
        ])->save();

        return true;
    }

    protected function findTrustedDevice(User $user, Request $request): ?TrustedDevice
    {
        [$cookieUserId, $plainToken] = $this->parseCookie((string) $request->cookie(self::COOKIE_NAME));

        if (! $cookieUserId || ! $plainToken || $cookieUserId !== $user->id) {
            return null;
        }

        return TrustedDevice::query()
            ->where('user_id', $user->id)
            ->where('token_hash', hash('sha256', $plainToken))
            ->first();
    }

    /**
     * @return array{0:int|null,1:string|null}
     */
    protected function parseCookie(string $value): array
    {
        if ($value === '' || ! str_contains($value, '|')) {
            return [null, null];
        }

        [$userId, $plainToken] = explode('|', $value, 2);

        if (! ctype_digit($userId) || $plainToken === '') {
            return [null, null];
        }

        return [(int) $userId, $plainToken];
    }
}
