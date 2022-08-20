<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\UpdatePostViewCount;
use App\Repositories\Interfaces\ICategoryRepository;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    private readonly IPostRepository $postRepos;
    private readonly ICategoryRepository $categoryRepos;

    public function __construct(IPostRepository $IPostRepository, ICategoryRepository $categoryRepository)
    {
        $this->postRepos = $IPostRepository;
        $this->categoryRepos = $categoryRepository;
    }

    /**
     * Display all posts of the resources.
     *
     * @return JsonResponse
     */
    final public function index(): JsonResponse
    {
        return response()->json($this->postRepos->filterPosts(perPage: 10));
    }

    /**
     * Display the specified the post.
     *
     * @param int $postId
     * @return JsonResponse
     */
    final public function show(int $postId): JsonResponse
    {
        $post = $this->postRepos->getDetailPost($postId); // Get the detail post

        if (is_null($post)) {
            return response()->json("Post not found", 404);
        }

        // Update view count
        dispatch(new UpdatePostViewCount($post->id));
        return response()->json($post);
    }

    /**
     * Get random list related posts
     *
     * @param int $categoryId
     * @return JsonResponse
     */
    final public function getRelatedPosts(int $categoryId): JsonResponse
    {
        if (is_null($this->categoryRepos->findById($categoryId))) {
            return response()->json("Not found", 404);
        }

        return response()->json($this->postRepos->getRelatedPostsByCategory(categoryId: $categoryId, limitItem: 6)); // get random related post
    }
}
