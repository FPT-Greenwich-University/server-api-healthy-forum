<?php

namespace App\Http\Controllers\Api\Users\Doctors;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class DoctorController extends Controller
{
    /**
     * Get posts of doctor
     * @param $userID
     * @return JsonResponse
     */
    public function getPosts($userID): JsonResponse
    {
        try {
            User::findOrFail($userID);// return 404 error if the user not found
            $posts = Post::with(['image'])
                ->where('user_id', $userID)
                ->orderBy('id', 'desc')
                ->take(3)
                ->get();
            return response()->json($posts);
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile()],
                500);
        }
    }
}
