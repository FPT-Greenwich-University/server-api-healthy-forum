<?php

namespace App\Http\Controllers\Api\Public\PostTags;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\IPostRepository;
use App\Repositories\Interfaces\ITagRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

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
        try {
            return response()->json($this->tagRepos->getAll());
        } catch (Exception $exception) {
            return response()->json(['Message' => $exception->getMessage(), 'Line' => $exception->getLine(), 'File' => $exception->getFile()], 500);
        }
    }

    /**
     * Get the tags belong to the posts by post ID
     *
     * @param $postID
     * @return JsonResponse
     */
    public function getPostTags($postID): JsonResponse
    {
        try {
            $this->postRepos->findById($postID);

            return response()->json(DB::table('tags')
                ->join('post_tag', 'tags.id', '=', 'post_tag.tag_id')
                ->join('posts', function ($join) use ($postID) {
                    $join->on('post_tag.post_id', '=', 'posts.id')
                        ->where('posts.id', '=', $postID);
                })
                ->select('tags.*')
                ->get());
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            return response()->json(['Message' => $exception->getMessage(), 'Line' => $exception->getLine(), 'File' => $exception->getFile()], 500);
        }
    }
}
