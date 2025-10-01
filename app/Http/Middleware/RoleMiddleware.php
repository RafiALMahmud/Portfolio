<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $roles = func_get_args();
        // args: ($request, $next, 'admin', 'manager', ...)
        $requiredRoles = array_slice($roles, 2);
        $requiredRolesLower = array_map('strtolower', $requiredRoles);

        if (!Auth::check()) {
            return redirect()->route('login.show');
        }

        $user = Auth::user();
        if (!empty($requiredRolesLower) && !in_array(strtolower((string) $user->role), $requiredRolesLower, true)) {
            abort(403);
        }

        return $next($request);
    }
}
