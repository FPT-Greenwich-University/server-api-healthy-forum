<?php

namespace App\Http\Controllers\Api\Users\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\CreatePostRequest;
use App\Repositories\Interfaces\IPostRepository;
use App\Repositories\Interfaces\IPostTagRepository;
use App\Services\FileServices\FileServicesContract;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    private IPostRepository $postRepository;
    private IPostTagRepository $postTagRepository;
    private FileServicesContract $fileServices;
    public function __construct(IPostRepository $postRepository, FileServicesContract $fileServicesContract, IPostTagRepository $postTagRepository)
    {
        $this->postRepository = $postRepository;
        $this->postTagRepository = $postTagRepository;
        $this->fileServices = $fileServicesContract;
    }

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

            $attributes = $request->only(['title', 'body', 'category_id', 'description']);
            $attributes['user_id'] = $request->user()->id; // assign user_id to array

            // Store a post
            $post = $this->postRepository->create($attributes);

            // Assign tag to post
            $this->postRepository->assignPostTags($post->id, $request->input('tags'));

            // Store a thumbnail
            $file = $request->file('thumbnail'); // retrieve a file
            $fileName = $file->hashName(); // Generate a unique, random name...
            $targetDir = 'posts/thumbnails/'; // set default path

            $this->fileServices->storeFile($file, $targetDir, $fileName);

            $filePath = $targetDir . $fileName;
            $this->postRepository->createPostImage($post->id, $filePath);

            DB::commit(); // all OK
            return response()->json('Create Success', 201);
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

            $post = $this->postRepository->findById($postID);

            if (is_null($postID)) return response()->json("Post not found", 404);

            $user = $request->user(); // get current user

            // Ensure the user has own the post or have role admin
            if ($user->id === $post->user_id || $user->hasRole('admin')) {
                $this->fileServices->deleteFile($post->image->path); // delete file image

                $this->postTagRepository->deletePostTags($post->id);
                // DB::table('post_tag')->where('post_id', $postID)->delete();

                $this->postRepository->deletePost($postID);

                // All Good
                DB::commit();
                return response()->json('Delete the post successful', 204);
            }

            return response()->json("You don't have permission to delete this post", 403);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json(['Message' => $exception->getMessage(), 'Line' => $exception->getLine(), 'Code' => $exception->getCode(), 'File' => $exception->getFile(), 'Trace' => $exception->getTrace()], 500);
        }
    }
}
