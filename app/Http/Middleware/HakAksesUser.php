<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HakAksesUser
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (in_array($user->hak_akses, $roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
