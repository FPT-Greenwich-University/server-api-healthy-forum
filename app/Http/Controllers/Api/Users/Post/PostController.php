<?php

namespace App\Http\Controllers\Api\Users\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\CreatePostRequest;
use App\Models\Post;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Doctor store new post in resources
     *
     * @param CreatePostRequest $request
     * @return JsonResponse
     */
    public function createPost(CreatePostRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Store a post
            $post = Post::create([
                'user_id' => $request->user()->id,
                'title' => $request->input('title'),
                'body' => $request->input('body'),
                'category_id' => $request->input('category_id'),
                'published_at' => now()
            ]);

            // Store a thumbnail
            $file = $request->file('thumbnail'); // retrieve a file
            $fileName = $file->hashName() . $file->extension(); // Generate a unique, random name...
            $targetDir = public_path('posts/thumbnails/'); // set default path
            $file->move($targetDir, $fileName); // movie file to public folder
            $filePath = $targetDir . $fileName;
            $post->image()->create([
                'path' => $filePath,
            ]);

            DB::commit(); // all OK
            return response()->json('Create post success');
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'Code' => $exception->getCode(),
                'File' => $exception->getFile(),
                'Trace' => $exception->getTrace()
            ], 500);
        }
    }
}
