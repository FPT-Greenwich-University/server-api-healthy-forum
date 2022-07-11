<?php

namespace App\Http\Controllers\Api\Search;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private IPostRepository $postRepository;

    public function __construct(IPostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Search the posts by title
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchPosts(Request $request): JsonResponse
    {
        $perPage = 5; // The total in one page
        $title = $request->query('title'); // Retrieve query title from url

        // If request have query title and it not empty string
        if ($request->has('title') && !empty($title)) {
            $posts = $this->postRepository->searchPosts($title, $perPage);
            return response()->json($posts);
        }

        return response()->json($this->postRepository->getPosts($perPage)); // Return the posts
    }
}
