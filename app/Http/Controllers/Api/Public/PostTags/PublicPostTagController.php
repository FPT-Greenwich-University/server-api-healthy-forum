<?php

namespace App\Http\Controllers\Api\Public\PostTags;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Repositories\Interfaces\IPostRepository;
use App\Repositories\Interfaces\ITagRepository;
use Illuminate\Http\JsonResponse;

class PublicPostTagController extends Controller
{
    private readonly ITagRepository $tagRepos;
    private readonly IPostRepository $postRepos;

    public function __construct(ITagRepository $tagRepository, IPostRepository $postRepository)
    {
        $this->tagRepos = $tagRepository;
        $this->postRepos = $postRepository;
    }

    public function index()
    {
        return response()->json(TagResource::collection($this->tagRepos->getAll()));
    }

    /**
     * Get the tags belong to the posts by post ID
     *
     * @param $postId
     * @return JsonResponse
     */
    public function getPostTags(int $postId): JsonResponse
    {
        $post = $this->postRepos->findById($postId);

        if (is_null($post)) return response()->json("Post not found", 404);

        $tags = $this->tagRepos->getPostTags($postId);
        return response()->json($tags);
    }
}
