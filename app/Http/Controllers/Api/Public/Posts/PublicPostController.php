<?php

namespace App\Http\Controllers\Api\Public\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PublicPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $posts = Post::join('images', function ($join) {
                $join->on('posts.id', '=', 'images.imageable_id')
                    ->where('images.imageable_type', '=', 'App\Models\Post');
            })
                ->select('posts.title', 'images.path')
                ->paginate(10);
            return response()->json($posts);
        } catch (Exception $exception) {
            return response()->json($exception, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param $postID
     * @return JsonResponse
     */
    public function show($postID): JsonResponse
    {
        try {
            $post = Post::with(['image'])->findOrFail($postID);
            return response()->json($post);
        } catch (Exception $exception) {
            return response()->json($exception, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
