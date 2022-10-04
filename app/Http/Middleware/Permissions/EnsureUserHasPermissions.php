<?php

namespace App\Http\Middleware\Permissions;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnsureUserHasPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $permissionName
     * @return JsonResponse|RedirectResponse|Response
     */
    public function handle(Request $request, Closure $next, string $permissionName)
    {
        if ($request->user()->hasDirectPermission($permissionName)) {
            return $next($request);
        }

        return response()->json("You don't have '$permissionName' permission to perform this action");
    }
}
