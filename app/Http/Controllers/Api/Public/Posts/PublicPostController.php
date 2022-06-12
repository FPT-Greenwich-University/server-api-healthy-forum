<?php

namespace App\Http\Controllers\Api\Public\Posts;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ICategoryRepository;
use App\Repositories\Interfaces\IPostRepository;
use App\Repositories\Interfaces\ITagRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicPostController extends Controller
{
    private IPostRepository $postRepos;
    private ITagRepository $tagRepos;
    private ICategoryRepository $categoryRepos;

    public function __construct(IPostRepository $IPostRepository, ITagRepository $ITagRepository, ICategoryRepository $categoryRepository)
    {
        $this->postRepos = $IPostRepository;
        $this->tagRepos = $ITagRepository;
        $this->categoryRepos = $categoryRepository;
    }

    /**
     * Display all posts of the resources.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            if ($request->query('tag')) {
                return $this->getPostsByTag($request->query('tag'));
            }

            if ($request->query('category')) {
                return $this->getPostsByCategory($request->query('category'));
            }

            return response()->json($this->postRepos->getPosts(10));
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile()
            ], 500);
        }
    }

    /**
     * Get all posts by tag name
     *
     * @param $tagID
     * @return JsonResponse
     */
    public function getPostsByTag($tagID): JsonResponse
    {
        try {
            $this->tagRepos->findById($tagID); // if tag id not found throw exception model not found
            $posts = $this->postRepos->getPostsByTag($tagID);

            return response()->json($posts->appends(['tag' => $tagID]));
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            return response()->json(['Message' => $exception->getMessage(), 'Line' => $exception->getLine(), 'File' => $exception->getFile()], 500);
        }
    }

    public function getPostsByCategory($categoryID): JsonResponse
    {
        try {
            $this->categoryRepos->findById($categoryID);
            $posts = $this->postRepos->getPostsByCategory($categoryID);

            return response()->json($posts->appends(['category' => $categoryID]));
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            return response()->json(['Message' => $exception->getMessage(), 'Line' => $exception->getLine(), 'File' => $exception->getFile()], 500);
        }
    }

    /**
     * Display the specified the post.
     *
     * @param $postID
     * @return JsonResponse
     */
    public function show($postID): JsonResponse
    {
        try {
            return response()->json($this->postRepos->getDetailPost($postID));
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            return response()->json(['Message' => $exception->getMessage(), 'Line' => $exception->getLine(), 'File' => $exception->getFile()], 500);
        }
    }


}
