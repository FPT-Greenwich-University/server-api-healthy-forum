<?php

namespace App\Http\Controllers\Api\Public\Posts;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ICategoryRepository;
use App\Repositories\Interfaces\IPostRepository;
use App\Repositories\Interfaces\ITagRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicPostController extends Controller
{
    private readonly IPostRepository $postRepos;
    private readonly ITagRepository $tagRepos;
    private readonly ICategoryRepository $categoryRepos;

    public function __construct(IPostRepository $IPostRepository, ITagRepository $ITagRepository, ICategoryRepository $categoryRepository)
    {
        $this->postRepos = $IPostRepository;
        $this->tagRepos = $ITagRepository;
        $this->categoryRepos = $categoryRepository;
    }

    /**
     * Display all posts of the resources.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
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
}
