<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\IPostRepository;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private readonly IPostRepository $postRepository;
    private readonly IUserRepository $userRepository;

    public function __construct(IPostRepository $postRepository, IUserRepository $userRepository)
    {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Search the posts by title
     *
     * @param Request $request
     * @return JsonResponse
     */
    final public function searchPosts(Request $request): JsonResponse
    {
        $title = trim($request->query('title')); // Retrieve query title from url

        // If the request have query title and it not empty string
        if (empty($title) || !$request->has('title')) {
            return response()->json('', 204); // Return http 204 if empty title
        }

        // Return the posts by title (5 item in one page)
        return response()->json($this->postRepository->searchPosts(title: $title, perPage: 5));
    }

    final public function searchUsers(Request $request): JsonResponse
    {
        $query = trim($request->query('query')); // Retrieve query from input user

        // If the request have query title and it not empty string
        if (empty($query) || !$request->has('query')) {
            return response()->json('', 204); // Return http 204 no content if empty query
        }

        // Return the posts by title (10 item in one page)
        return response()->json($this->userRepository->searchUser(query: $query, perPage: 10));
    }
}