<?php

namespace App\Http\Controllers\Api\Users\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\CreatePostRequest;
use App\Repositories\Interfaces\ICategoryRepository;
use App\Repositories\Interfaces\IPostRepository;
use App\Services\FileServices\FileServicesContract;
use App\Services\PostServices\PostServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private IPostRepository $postRepository;
    private FileServicesContract $fileServices;
    private readonly PostServiceInterface $postService;
    private ICategoryRepository $categoryRepository;

    public function __construct(
        IPostRepository      $postRepository,
        FileServicesContract $fileServicesContract,
        PostServiceInterface $postService,
        ICategoryRepository  $categoryRepository
    )
    {
        $this->postRepository = $postRepository;
        $this->fileServices = $fileServicesContract;
        $this->postService = $postService;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Doctor store new post in resources
     *
     * @param CreatePostRequest $request
     * @return JsonResponse
     */
    final public function createPost(CreatePostRequest $request): JsonResponse
    {
        if (!$this->categoryRepository->findById($request->input('category_id'))) {
            return response()->json("Category not found", 404);
        }

        $file = $request->file('thumbnail'); // retrieve a file
        $fileName = $file->hashName(); // Generate a unique, random name...
        $targetDir = 'posts/thumbnails/'; // set default path

        if (!$this->fileServices->storeFile($file, $targetDir, $fileName)) {
            return response()->json("Bad request store a image", 400);
        }

        $filePath = $targetDir . $fileName;

        // Store post into database
        if (!$this->postService->createNewPost($filePath, $request)) {
            return response()->json("Bad request store post information", 400);
        }

        return response()->json('Create Success', 201); // Success
    }


    /**
     * Doctor deletes the post in resources
     *
     * @param int $userId
     * @param int $postId
     * @param Request $request
     * @return JsonResponse
     */
    final public function deletePost(int $userId, int $postId, Request $request): JsonResponse
    {
        $post = $this->postRepository->findById($postId);

        if (is_null($post)) {
            return response()->json("Post Not found", 404);
        }

        $this->fileServices->deleteFile($post->image->path); // delete file image

        if (!$this->postService->deletePost($userId, $postId, $request)) {
            return response()->json("Bad request", 400);
        }

        return response()->json("", 204);
    }
}
