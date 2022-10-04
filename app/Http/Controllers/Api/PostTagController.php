<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Repositories\Interfaces\IPostRepository;
use App\Repositories\Interfaces\ITagRepository;
use Illuminate\Http\JsonResponse;

class PostTagController extends Controller
{
    private readonly ITagRepository $tagRepos;
    private readonly IPostRepository $postRepos;

    public function __construct(ITagRepository $tagRepository, IPostRepository $postRepository)
    {
        $this->tagRepos = $tagRepository;
        $this->postRepos = $postRepository;
    }

    final public function index(): JsonResponse
    {
        return response()->json(TagResource::collection($this->tagRepos->getAll()));
    }

    /**
     * Get the tags belong to the posts by post ID
     *
     * @param int $postId
     * @return JsonResponse
     */
    final public function getPostTags(int $postId): JsonResponse
    {
        if (is_null($this->postRepos->findById($postId))) {
            return response()->json("Post not found", 404);
        }

        return response()->json($this->tagRepos->getPostTags($postId));
    }
}
