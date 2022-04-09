<?php

namespace App\Http\Controllers\Api\Public\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicPostController extends Controller
{
    /**
     * Display all posts of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            if ($request->query()) { // if request have query
                $posts = $this->filter($request);
            } else {
                $posts = Post::join('images', function ($join) {
                    $join->on('posts.id', '=', 'images.imageable_id')
                        ->where('images.imageable_type', '=', 'App\Models\Post');
                })
                    ->select('posts.title', 'images.path')
                    ->paginate(10);
            }
            return response()->json($posts);
//            $posts = Post::filter($request->all())->isPublished()->paginate(20);
//            return response()->json($posts);
        } catch (Exception $exception) {
            return response()->json(['Message' => $exception->getMessage(), 'Line' => $exception->getLine(), 'File' => $exception->getFile(),], 500);
        }
    }

    public function filter(Request $request)
    {
        if ($request->query('categoryID')) {
            return Post::join('images', function ($join) {
                $join->on('posts.id', '=', 'images.imageable_id')->where('images.imageable_type', '=', 'App\Models\Post');
            })->where('category_id', '=', $request->query('categoryID'))->select('posts.title', 'images.path')->paginate(10);
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
            $post = Post::with(['image'])->findOrFail($postID);
            return response()->json($post);
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
            $posts = Post::query()->tag($tagID)->isPublished()->paginate(20);
            return response()->json($posts);
        } catch (Exception $exception) {
            return response()->json(['Message' => $exception->getMessage(), 'Line' => $exception->getLine(), 'File' => $exception->getFile(),], 500);
        }
    }
}
