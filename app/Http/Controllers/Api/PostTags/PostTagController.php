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

    public function index(): JsonResponse
    {
        return response()->json(TagResource::collection($this->tagRepos->getAll()));
    }

    /**
     * Get the tags belong to the posts by post ID
     *
     * @param int $postId
     * @return JsonResponse
     */
    public function getPostTags(int $postId): JsonResponse
    {
        $post = $this->postRepos->findById($postId); // Find the post in resources

        if (is_null($post)) return response()->json("Post not found", 404);  // Return HTTP 404 if the post not found

        $tags = $this->tagRepos->getPostTags($postId); // Get all the tags by the post
        return response()->json($tags);
    }
}
