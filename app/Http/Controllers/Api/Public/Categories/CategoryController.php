<?php

namespace App\Http\Controllers\Api\Public\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repositories\Interfaces\ICategoryRepository;
use Exception;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    private ICategoryRepository  $categoryRepository;
    public function __construct(ICategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    public function etAllCategories(): JsonResponse
    {
            $categories = $this->categoryRepository->getAllCategories();

            return response()->json($categories);
    }
}
