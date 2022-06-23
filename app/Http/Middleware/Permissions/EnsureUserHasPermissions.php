<?php

namespace App\Http\Middleware\Permissions;

use Closure;
use Illuminate\Http\Request;

class EnsureUserHasPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next, string $permissionName)
    {
        if ($request->user()->hasDirectPermission($permissionName)) {
            return $next($request);
        }

        return response()->json("You don't have '$permissionName' permission to perform this action");
    }
}
