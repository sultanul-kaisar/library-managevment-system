<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\Base;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        $roles = explode("|", $roles);

        if (!auth()->check()) {
            return Base::error("You are not logged in.", [], 'not_auth');
        }

        if (!auth()->user()->is_active) {
            return Base::error("Please contact admin to activate your account!", [], "not_activated");
        }

        if (in_array(auth()->user()->role, $roles)) {
            return $next($request);
        }

        return Base::error("Unauthorized!");
    }
}
