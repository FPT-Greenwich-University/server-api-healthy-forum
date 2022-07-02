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
    private IPostRepository $postRepos;
    private ITagRepository $tagRepos;
    private ICategoryRepository $categoryRepos;

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
        if ($request->query('tag')) {
            $tag = $this->tagRepos->findById($request->input('tag'));

            if (is_null($tag)) return response()->json("Post's tag not found", 404);

            return response()->json($this->postRepos->getPostsByTag($request->input('tag'), 5));
        }

        if ($request->query('category')) {
            $category = $this->categoryRepos->findById($request->input('category'));

            if (is_null($category)) return response()->json("Post's category not found");

            return response()->json($this->postRepos->getPostsByCategory($request->input('category'), 5));
        }

        return response()->json($this->postRepos->filterPosts(10)); // default
    }

    /**
     * Display the specified the post.
     *
     * @param $postID
     * @return JsonResponse
     */
    public function show($postID): JsonResponse
    {
        $result = $this->postRepos->getDetailPost($postID);

        if (is_null($result)) return response()->json("Post not found", 404);

        return response()->json($result);
    }
}
