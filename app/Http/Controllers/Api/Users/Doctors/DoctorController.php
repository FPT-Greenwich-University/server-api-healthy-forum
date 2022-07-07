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
     * @param $userID
     * @return JsonResponse
     */
    public function getPublishedPostsByUser($userID): JsonResponse
    {
        $user = $this->userRepository->findById($userID);

        if (is_null($user)) return response()->json("User not found", 404);

        $posts = $this->postRepository->getPostsByUser($userID);
        return response()->json($posts);
    }

    public function doctorGetOwnPosts(int $userId, Request $request): JsonResponse
    {
        if ($userId != $request->user()->id) return response()->json("Not found", 404);

        $itemPerPage = 5;
        $posts = $this->postRepository->doctorGetOwnPosts($userId, $itemPerPage);
        return response()->json($posts);
    }

    /**
     * Detail post by user id
     *
     * @param $userID
     * @param $postID
     * @return JsonResponse
     */
    public function getDetailPost($userID, $postID): JsonResponse
    {
        $post = $this->postRepository->doctorGetDetailPost($postID);

        if (is_null($post) || $post->user_id != $userID) return response()->json("Post not found", 404);

        return response()->json($post);
    }


    public function update(UpdatePostRequest $request, $userID, $postID)
    {

        $post = $this->postRepository->findById($postID);

        if (is_null($post)) return response()->json("Post Not found", 404);

        if ($post->user_id != $userID) return response()->json("Bad request user not found", 404);

        if (!$this->postService->updatePost($postID, $request)) return response()->json("Bad request update post information", 400);

        // If user want update a thumbnail of the post
        if ($request->hasFile('thumbnail')) {
            $imagePath = public_path($post->image->path);
            // Delete image file
            if (!$this->fileServices->deleteFile($imagePath)) return response()->json("Error to update post image", 500);

            $file = $request->file('thumbnail'); // retrieve a file
            $fileName = $file->hashName(); // Generate a unique, random name...
            $targetDir = "posts/thumbnails/"; // set default path

            $this->fileServices->storeFile($file, $targetDir, $fileName); // movie file to public folder

            $filePath = $targetDir . $fileName; // set file path

            $this->postRepository->updatePostImage($postID, $filePath); // update current path image
        }

        return response()->json("", 204);
    }
}
