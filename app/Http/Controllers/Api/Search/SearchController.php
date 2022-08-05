<?php

namespace App\Http\Controllers\Api\Search;

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
    public function searchPosts(Request $request): JsonResponse
    {
        $perPage = 5; // The total in one page
        $title = $request->query('title'); // Retrieve query title from url

        // If request have query title and it not empty string
        if ($request->has('title') && !empty($title)) {
            $posts = $this->postRepository->searchPosts($title, $perPage);
            return response()->json($posts);
        }

        return response()->json('', 204); // Return http 204 if empty title
    }

    public function searchUsers(Request $request): JsonResponse
    {
        $query = $request->query('query'); // Retrieve query from input user

        if ($request->has('query') && !empty($query)) {
            $users = $this->userRepository->searchUser(query: $query, perPage: 10);
            return response()->json($users);
        }

        return response()->json('', 204); // Return http 204 no content if empty query
    }
}
