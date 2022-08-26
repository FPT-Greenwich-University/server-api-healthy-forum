<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ICategoryRepository;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    private readonly ICategoryRepository $categoryRepository;

    public function __construct(ICategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get all the categories
     *
     * @return JsonResponse
     */
    final public function getAllCategories(): JsonResponse
    {
        return response()->json($this->categoryRepository->getAllCategories());
    }
}