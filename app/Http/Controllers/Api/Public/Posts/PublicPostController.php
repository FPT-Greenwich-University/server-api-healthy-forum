<?php

namespace App\Http\Controllers\Api\Public\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicPostController extends Controller
{
    /**
     * Display all posts of the resources.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            return response()->json(Post::with(['image', 'category', 'user'])
                ->isPublished()
                ->orderBy('id', 'desc')
                ->paginate(5));
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }

    /**
     * Display the specified the post.
     *
     * @param $postID
     * @return JsonResponse
     */
    public function show($postID): JsonResponse
    {
        try {
            return response()->json(Post::with(['image', 'category', 'user'])->findOrFail($postID));
        } catch (Exception $exception) {
            return response()->json(['Message' => $exception->getMessage(), 'Line' => $exception->getLine(), 'File' => $exception->getFile(),], 500);
        }
    }

    /**
     * Get all posts via tag name
     *
     * @param $tagID
     * @return JsonResponse
     */
    public function getPostViaTagName($tagID): JsonResponse
    {
        try {
            return response()->json(Post::tag($tagID)->isPublished()->paginate(20));
        } catch (Exception $exception) {
            return response()->json(['Message' => $exception->getMessage(), 'Line' => $exception->getLine(), 'File' => $exception->getFile(),], 500);
        }
    }
}
