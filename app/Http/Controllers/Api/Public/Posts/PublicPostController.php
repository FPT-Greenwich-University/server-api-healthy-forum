<?php

namespace App\Http\Controllers\Api\Public\Posts;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ICategoryRepository;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Http\JsonResponse;

class PublicPostController extends Controller
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
    public function index(): JsonResponse
    {
        $perPage = 10; // the total post in one page
        return response()->json($this->postRepos->filterPosts($perPage));
    }

    /**
     * Display the specified the post.
     *
     * @param integer $postId
     * @return JsonResponse
     */
    public function show(int $postId): JsonResponse
    {
        $post = $this->postRepos->getDetailPost($postId); // Get the detail post

        if (is_null($post)) return response()->json("Post not found", 404);

        return response()->json($post);
    }

    /**
     * Get random list related posts
     *
     * @param int $categoryId
     * @return JsonResponse
     */
    public function getRelatedPosts(int $categoryId): JsonResponse
    {
        $category = $this->categoryRepos->findById($categoryId);

        if (is_null($category)) return response()->json("Not found", 404);

        $limitItem = 6;
        return response()->json($this->postRepos->getRelatedPostsByCategory($categoryId, $limitItem)); // get random related post
    }
}
