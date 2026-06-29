<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Penggunaan: ->middleware('role:pentadbir,penyelaras')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_active) {
            abort(403, 'Akaun tidak aktif atau tidak dibenarkan.');
        }

        if (! empty($roles) && ! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak mempunyai kebenaran untuk tindakan ini.');
        }

        return $next($request);
    }
}
