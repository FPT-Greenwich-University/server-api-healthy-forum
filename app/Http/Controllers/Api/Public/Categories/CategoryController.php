<?php

namespace App\Http\Controllers\Api\Public\Categories;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ICategoryRepository;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    private ICategoryRepository  $categoryRepository;
    public function __construct(ICategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    public function getAllCategories(): JsonResponse
    {
        $categories = $this->categoryRepository->getAllCategories();
        return response()->json($categories);
    }
}
