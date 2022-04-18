<?php

namespace App\Http\Controllers\Api\Public\PostTags;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicPostTagController extends Controller
{
    /**
     * Get the tags belong to the posts by post ID
     *
     * @param $postID
     * @return JsonResponse
     */
    public function getPostTags($postID): JsonResponse
    {
        try {
            Post::findOrFail($postID);
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
