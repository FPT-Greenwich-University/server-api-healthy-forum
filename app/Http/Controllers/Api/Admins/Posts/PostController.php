<?php

namespace App\Http\Controllers\Api\Admins\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * Get the posts not published
     *
     * @return JsonResponse
     */
    public function getPostsIsNotPublished(): JsonResponse
    {
        try {
            $posts = Post::with(['user', 'category'])
                ->isNotPublished()
                ->orderBy('id', 'desc')
                ->paginate(2);
            return response()->json($posts);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }

    /**
     * Detail post by post id
     * @param $postID
     * @return JsonResponse
     */
    public function show($postID): JsonResponse
    {
        try {
            return response()->json(Post::with(['image', 'category', 'user'])->findOrFail($postID));
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }

    /**
     * Admin accept post of doctor, update status published to true
     *
     * @param $postID
     * @return JsonResponse
     */
    public function acceptPublishPost($postID): JsonResponse
    {
        try {
            $post = Post::findOrFail($postID); // return 404 error if not found
            $post->update(['is_published' => true]); // update status published
            return response()->json('Update status published post successful');
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }
}
