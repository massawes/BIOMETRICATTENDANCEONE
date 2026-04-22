<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! auth()->guard('web')->check()) {
            abort(403,'unathored aceess');
        }

        $currentRole = strtolower(auth()->guard('web')->user()->role->name ?? '');
        $allowedRoles = array_map(static fn ($role) => strtolower(trim((string) $role)), $roles);

        if (! in_array($currentRole, $allowedRoles, true)) {
            abort(403,'unathored aceess');
        }

        return $next($request);
    }
}
