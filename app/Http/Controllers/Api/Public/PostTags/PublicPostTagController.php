<?php

namespace App\Http\Controllers\Api\Public\PostTags;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Repositories\Interfaces\IPostRepository;
use App\Repositories\Interfaces\ITagRepository;
use Illuminate\Http\JsonResponse;

class PublicPostTagController extends Controller
{
    private ITagRepository $tagRepos;
    private IPostRepository $postRepos;

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
     * @param $postID
     * @return JsonResponse
     */
    public function getPostTags($postID): JsonResponse
    {
        $post = $this->postRepos->findById($postID);

        if (is_null($post)) return response()->json("Post not found", 404);

        $tags = $this->tagRepos->getPostTags($postID);
        return response()->json($tags);
    }
}
