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
        // Retrieve the permission name from URL
        $permissionName = 'chat-room.' . $request->route()->parameter('chatRoomId');

        // Check the permission name is existed in database
        if (is_null($this->permissionRepository->findByName($permissionName))) {
            return response()->json("Chat room or permission not exits!", 404);
        }

        // If the current user had permission then allow user access the chat room
        if (!$request->user()->hasPermissionTo($permissionName, 'web')) {
            // Return HTTP 403 if user not had permission
            return response()->json("You don't have permission to access the room", 403);
        }

        // Continue the next request
        return $next($request);
    }
}
