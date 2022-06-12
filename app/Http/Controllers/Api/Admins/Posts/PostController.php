<?php

namespace App\Http\Controllers\Api\Admins\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Repositories\Interfaces\IPostRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PostController extends Controller
{
    private IPostRepository $postRespos;

    public function __construct(IPostRepository $postRepository)
    {
        $this->postRespos = $postRepository;
    }

    /**
     * Get the posts not published
     *
     * @return JsonResponse
     */
    public function getPostsIsNotPublished(): JsonResponse
    {
        try {
            $posts = $this->postRespos->getPostsNotPublish(5);

            return response()->json($posts, Response::HTTP_OK);
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
     * @param $postID Post's id need publish
     * @return JsonResponse
     */
    public function acceptPublishPost($postID): JsonResponse
    {
        try {
            $post = $this->postRespos->findById($postID);
            if (!is_null($post)) {
                $this->postRespos->update($postID, ['is_published' => true]); // update status published
            }

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
