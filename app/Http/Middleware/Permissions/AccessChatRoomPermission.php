<?php

namespace App\Http\Middleware\Permissions;

use App\Repositories\Interfaces\IPermissionRepository;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccessChatRoomPermission
{
    private readonly IPermissionRepository $permissionRepository;

    public function __construct(IPermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return JsonResponse|RedirectResponse|Response
     */
    public function handle(Request $request, Closure $next)
    {

        $permissionName = 'chat-room.' . $request->route()->parameter('chatRoomId');

        if (is_null($this->permissionRepository->findByName($permissionName))) {
            return response()->json("Permission not found", 404);
        }

        if (!$request->user()->hasPermissionTo($permissionName, 'web')) {
            return response()->json("You don't have permission to access the room", 403);
        }
        return $next($request);
    }
}
