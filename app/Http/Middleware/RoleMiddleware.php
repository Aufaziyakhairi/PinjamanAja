<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * @param  array<int, string>  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        $userRole = $user->role;
        $value = is_object($userRole) && property_exists($userRole, 'value') ? $userRole->value : (string) $userRole;

        if (!in_array($value, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
