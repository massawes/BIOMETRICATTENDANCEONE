<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class CheckHod
{
public function handle(Request $request, Closure $next): Response
{
    // Check if the user is logged in and has the specified role
    if (!auth()->guard('web')->check() || auth()->guard('web')->user()->role_id != 3) {
        // If not, abort the request with a 403 status code
        abort(403, 'Unauthorized');
    }

    // If the user has the correct role, allow the request to proceed
    return $next($request);
}
}