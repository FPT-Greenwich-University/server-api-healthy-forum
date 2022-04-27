<?php

namespace App\Http\Controllers\Api\Public\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Get all the categories from resources
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            return response()->json(Category::all());
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }
}
