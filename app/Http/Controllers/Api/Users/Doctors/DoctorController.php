<?php

namespace App\Http\Controllers\Api\Users\Doctors;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\CreatePostRequest;
use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    /**
     * Get posts of doctor
     * @param $userID
     * @return JsonResponse
     */
    public function getPosts($userID): JsonResponse
    {
        try {
            User::findOrFail($userID);// return 404 error if the user not found
            $posts = Post::with(['image'])
                ->where('user_id', $userID)
                ->orderBy('id', 'desc')
                ->take(3)
                ->get();
            return response()->json($posts);
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile()],
                500);
        }
    }

    /**
     * Display the specified the post.
     *
     * @param $postID
     * @return JsonResponse
     */
    public function getDetailPost($userID, $postID): JsonResponse
    {
        try {
            return response()->json(Post::with(['image', 'category', 'user'])
                ->where('user_id', $userID)
                ->findOrFail($postID));
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            return response()->json(['Message' => $exception->getMessage(), 'Line' => $exception->getLine(), 'File' => $exception->getFile()], 500);
        }
    }


    public function update(CreatePostRequest $request, $userID, $postID)
    {
        DB::beginTransaction();
        try {

            // Store a post
            $post = Post::findOrFail($postID);

            if ($post->user_id == $userID) {
                $post->update([
                    'title' => $request->input('title'),
                    'body' => $request->input('body'),
                    'category_id' => $request->input('category_id'),
                    'description' => $request->input('description'),
                ]);

                // Assign tag to post
                $post->tags()->sync($request->input('tags'));

                // Store a thumbnail
                \File::delete(public_path($post->image->path)); // delete image file
                $post->image()->delete(); // Delete image thumbnail first

                $file = $request->file('thumbnail'); // retrieve a file
                $fileName = $file->hashName(); // Generate a unique, random name...
                $targetDir = 'posts/thumbnails/'; // set default path
                $file->move($targetDir, $fileName); // movie file to public folder
                $filePath = $targetDir . $fileName;
                $post->image()->create(['path' => $filePath]);

                DB::commit(); // all OK
                return response()->json('Update post success');
            }
            return response()->json("You don't have own this post", 403);

        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json(['Message' => $exception->getMessage(), 'Line' => $exception->getLine(), 'Code' => $exception->getCode(), 'File' => $exception->getFile(), 'Trace' => $exception->getTrace()], 500);
        }
    }
}
