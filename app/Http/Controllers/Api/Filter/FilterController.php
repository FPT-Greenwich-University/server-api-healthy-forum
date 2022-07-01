<?php

namespace App\Http\Controllers\Api\Filter;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Http\JsonResponse;

class FilterController extends Controller
{
    private IPostRepository $postRepository;

    public function __construct(IPostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function filterPosts(): JsonResponse
    {
        $perPage = 5; // Number item in one page
        $posts = $this->postRepository->filterPosts($perPage);
        return response()->json($posts);
    }
}
