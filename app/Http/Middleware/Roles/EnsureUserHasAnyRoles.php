<?php

namespace App\Http\Middleware\Roles;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnsureUserHasAnyRoles
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!$request->user()->hasAnyRole(...$roles)) {

            return response()->json(['message' => "You don't have permission"], 403);
        }

        return $next($request);
    }
}
