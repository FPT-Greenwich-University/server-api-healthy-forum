<?php

namespace App\Http\Middleware\Roles;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param string $role User's role
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!$request->user()->hasRole($role)) {
            return response()->json(['message' => "Required $role role"], 403);
        }

        return $next($request);
    }
}
