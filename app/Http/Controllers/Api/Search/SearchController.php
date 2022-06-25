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

    public function searchPosts(Request $request): JsonResponse
    {
        $perPage = 5;
        $posts = $this->postRepository->searchPosts($request->query('title'), $perPage);
        return response()->json($posts);
    }
}
