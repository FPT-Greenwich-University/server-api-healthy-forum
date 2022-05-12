<?php

namespace App\Http\Controllers\Api\Users\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\CreatePostRequest;
use App\Models\Post;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
                'description' => $request->input('description'),
                'published_at' => now()
            ]);

            // Assign tag to post
            $post->tags()->attach($request->input('tags'));

            // Store a thumbnail
            $file = $request->file('thumbnail'); // retrieve a file
            $fileName = $file->hashName(); // Generate a unique, random name...
            $targetDir = 'posts/thumbnails/'; // set default path
            $file->move($targetDir, $fileName); // movie file to public folder
            $filePath = $targetDir . $fileName;
            $post->image()->create(['path' => $filePath]);

            DB::commit(); // all OK
            return response()->json('Create post success');
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json(['Message' => $exception->getMessage(), 'Line' => $exception->getLine(), 'Code' => $exception->getCode(), 'File' => $exception->getFile(), 'Trace' => $exception->getTrace()], 500);
        }
    }


    /**
     * Doctor deletes the post in resources
     *
     * @param Request $request
     * @param $postID
     * @return JsonResponse
     */
    public function deletePost(Request $request, $postID): JsonResponse
    {
        try {
            DB::beginTransaction();

            $post = Post::findOrFail($postID);// return 404 if not found

            $user = $request->user(); // get current user

            // Ensure the user has own the post or have role admin
            if ($user->id === $post->user_id || $user->hasRole('admin')) {
                \File::delete(public_path($post->image->path)); // delete image file
                $post->image()->delete(); // Delete image thumbnail first
                DB::table('post_tag')->where('post_id', $postID)->delete();
                $post->comments()->delete();
                $post->postRatings()->delete();
                $post->favorites()->delete();
                $post->tags()->delete();
                $post->postLikes()->delete();
                $post->delete(); // delete the post

                // All Good
                DB::commit();
                return response()->json('Delete the post successful', 200);
            }

            return response()->json("You don't have permission to delete this post", 403);
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json(['Message' => $exception->getMessage(), 'Line' => $exception->getLine(), 'Code' => $exception->getCode(), 'File' => $exception->getFile(), 'Trace' => $exception->getTrace()], 500);
        }
    }
}
