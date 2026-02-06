<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // Ensure user is authenticated before checking role
        if (!auth()->check()) {
            \Log::warning('CheckRole: unauthenticated access attempt', ['path' => $request->path()]);
            abort(401, 'Unauthenticated');
        }

        $user = auth()->user();
        $userRole = $user->role ?? null;

        if (!in_array($userRole, $roles)) {
            \Log::warning('CheckRole: access denied', [
                'user_id' => $user->id ?? null,
                'user_role' => $userRole,
                'required_roles' => $roles,
                'path' => $request->path(),
            ]);

            abort(403, 'Forbidden: insufficient role');
        }

        return $next($request);
    }
}
