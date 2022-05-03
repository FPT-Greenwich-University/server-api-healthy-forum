<?php

namespace App\Http\Controllers\Api\Public\Posts;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
            if ($request->query('tag')) {
                return $this->getPostsByTag($request->query('tag'));
            }

            if ($request->query('category')) {
                return $this->getPostsByCategory($request->query('category'));
            }

            return response()->json(Post::with(['image', 'category', 'user'])
                ->isPublished()
                ->orderBy('id', 'desc')
                ->paginate(5));
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile()
            ], 500);
        }
    }

    /**
     * Get all posts by tag name
     *
     * @param $tagID
     * @return JsonResponse
     */
    public function getPostsByTag($tagID): JsonResponse
    {
        try {
            Tag::findOrFail($tagID); // return 404 if not found
            $listPostIDByTag = Post::tag($tagID)->pluck('posts.id');

            $posts = Post::with(['image', 'category', 'user', 'tags'])
                ->whereIn('posts.id', $listPostIDByTag)
                ->isPublished()
                ->orderBy('posts.id', 'desc')
                ->paginate(5);
            return response()->json($posts->appends(['tag' => $tagID]));
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            return response()->json(['Message' => $exception->getMessage(), 'Line' => $exception->getLine(), 'File' => $exception->getFile()], 500);
        }
    }

    public function getPostsByCategory($categoryID): JsonResponse
    {
        try {
            Category::findOrFail($categoryID); // return 404 if not found
            $listPostIDByCategory = Post::where('category_id', '=', $categoryID)->pluck('posts.id');

            $posts = Post::with(['image', 'category', 'user'])
                ->whereIn('posts.id', $listPostIDByCategory)
                ->orderBy('posts.id', 'desc')
                ->paginate(5);
            return response()->json($posts->appends(['category' => $categoryID]));
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            return response()->json(['Message' => $exception->getMessage(), 'Line' => $exception->getLine(), 'File' => $exception->getFile()], 500);
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
            return response()->json(Post::with(['image', 'category', 'user'])->isPublished()->findOrFail($postID));
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            return response()->json(['Message' => $exception->getMessage(), 'Line' => $exception->getLine(), 'File' => $exception->getFile()], 500);
        }
    }
}
