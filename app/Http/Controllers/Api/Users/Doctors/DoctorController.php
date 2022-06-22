<?php

namespace App\Http\Controllers\Api\Users\Doctors;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\CreatePostRequest;
use App\Repositories\Interfaces\IPostRepository;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\FileServices\FileServicesContract;
use Illuminate\Http\JsonResponse;

class DoctorController extends Controller
{
    private IUserRepository $userRepository;
    private IPostRepository $postRepository;
    private FileServicesContract $fileServices;

    public function __construct(IUserRepository $userRepository, IPostRepository $postRepository, FileServicesContract $fileServicesContract)
    {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->fileServices = $fileServicesContract;
    }

    /**
     * Get posts of doctor
     * @param $userID
     * @return JsonResponse
     */
    public function getPosts($userID): JsonResponse
    {
        $user = $this->userRepository->findById($userID);

        if (is_null($user)) return response()->json("User not found", 404);

        $posts = $this->postRepository->getPostsByUser($userID);
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
        $post = $this->postRepository->getDetailPostByUser($userID, $postID);

        if (is_null($post)) return response()->json("Post not found", 404);

        return response()->json($post);
    }


    public function update(CreatePostRequest $request, $userID, $postID)
    {
        $attributes = $request->only(['title', 'body', 'category_id', 'description']);
        $post = $this->postRepository->findById($postID);

        if ($post === null) return response()->json("Post not found", 404);

        if ($post->user_id !== intval($userID)) return response()->json("Required permission", 403);

        $this->postRepository->updatePost($postID, $attributes);

        // Update tag
        $this->postRepository->updatePostTags($postID, $request->input('tags'));

        $imagePath = public_path($post->image->path);
        $result = $this->fileServices->deleteFile($imagePath); // delete image file

        if ($result === false) return response()->json("Error to update post image", 500);

        $file = $request->file('thumbnail'); // retrieve a file
        $fileName = $file->hashName(); // Generate a unique, random name...
        $targetDir = "posts/thumbnails/"; // set default path

        $this->fileServices->storeFile($file, $targetDir, $fileName); // movie file to public folder

        $filePath = $targetDir . $fileName; // set file path

        $this->postRepository->updatePostImage($postID, $filePath); // update current path image
        return response()->json("", 204);
    }
}
