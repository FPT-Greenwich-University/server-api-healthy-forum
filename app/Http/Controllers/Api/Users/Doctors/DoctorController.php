<?php

namespace App\Http\Controllers\Api\Users\Doctors;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\UpdatePostRequest;
use App\Repositories\Interfaces\IPostRepository;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\FileServices\FileServicesContract;
use App\Services\PostServices\PostServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    private readonly IUserRepository $userRepository;
    private readonly IPostRepository $postRepository;
    private readonly FileServicesContract $fileServices;
    private readonly PostServiceInterface $postService;

    public function __construct(IUserRepository $userRepository, PostServiceInterface $postService, IPostRepository $postRepository, FileServicesContract $fileServicesContract)
    {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->fileServices = $fileServicesContract;
        $this->postService = $postService;
    }

    /**
     * Get published posts of doctor
     * @param int $userId
     * @return JsonResponse
     */
    final public function getPublishedPostsByUser(int $userId): JsonResponse
    {
        if (is_null($this->userRepository->findById($userId))) {
            return response()->json("User not found", 404);
        }

        return response()->json($this->postRepository->getPostsByUser(userId: $userId, perPage: 5));
    }

    /**
     * Get the posts belong to the doctor
     *
     * @param int $userId
     * @param Request $request
     * @return JsonResponse
     */
    final public function doctorGetOwnPosts(int $userId, Request $request): JsonResponse
    {
        if (!(((int)$request->user()->id) !== $userId)) {
            return response()->json("Not found", 404);
        }

        return response()->json($this->postRepository->doctorGetOwnPosts(userId: $userId, itemPerPage: 5));
    }

    /**
     * Detail post by user id
     *
     * @param int $userId
     * @param int $postId
     * @return JsonResponse
     */
    final public function getDetailPost(int $userId, int $postId): JsonResponse
    {
        $post = $this->postRepository->doctorGetDetailPost($postId);

        if (is_null($post) || (int)$post->user_id !== $userId) {
            return response()->json("Post not found", 404);
        }

        return response()->json($post);
    }


    /**
     * Update the post
     *
     * @param UpdatePostRequest $request
     * @param int $userId
     * @param int $postId
     * @return JsonResponse
     */
    final public function update(UpdatePostRequest $request, int $userId, int $postId): JsonResponse
    {

        $post = $this->postRepository->findById($postId);

        if (is_null($post)) {
            return response()->json("Post Not found", 404);
        }

        if ($post->user_id !== $userId) {
            return response()->json("Bad request user not found", 404);
        }

        if (!$this->postService->updatePost($postId, $request)) {
            return response()->json("Bad request update post information", 400);
        }

        // If user want update a thumbnail of the post
        if ($request->hasFile('thumbnail')) {
            $imagePath = public_path($post->image->path);
            // Delete image file
            if (!$this->fileServices->deleteFile($imagePath)) {
                return response()->json("Error to update post image", 500);
            }

            $file = $request->file('thumbnail'); // retrieve a file
            $fileName = $file->hashName(); // Generate a unique, random name...
            $targetDir = "posts/thumbnails/"; // set default path

            $this->fileServices->storeFile($file, $targetDir, $fileName); // movie file to public folder

            $filePath = $targetDir . $fileName; // set file path

            $this->postRepository->updatePostImage($postId, $filePath); // update current path image
        }

        return response()->json("", 204); // Update success
    }
}
