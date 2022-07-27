<?php

namespace App\Http\Controllers\Api\Admins\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admins\Categories\CreateOrUpdateCategoryRequest;
use App\Repositories\Interfaces\ICategoryRepository;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    private readonly ICategoryRepository $categoryRepos;
    private readonly IPostRepository $postRepository;

    public function __construct(ICategoryRepository $categoryRepository, IPostRepository $postRepository)
    {
        $this->categoryRepos = $categoryRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * Admin store new category in resources
     *
     * @param CreateOrUpdateCategoryRequest $request
     * @return JsonResponse
     */
    public function store(CreateOrUpdateCategoryRequest $request): JsonResponse
    {
        $this->categoryRepos->create($request->only(['name', 'description']));
        return response()->json('Create new category successful');
    }

    /**
     * Admin update information of category in resources
     *
     * @param CreateOrUpdateCategoryRequest $request
     * @param int $categoryId
     * @return JsonResponse
     */
    public function update(CreateOrUpdateCategoryRequest $request, int $categoryId): JsonResponse
    {
        $attributes = $request->only(['name', 'description']); // Get field from body http

        $result = $this->categoryRepos->updateCategory($categoryId, $attributes); // Update category infomation if category is exitsed

        if ($result === false) return response()->json("Category not found", 404); // Return not found if no result record

        return response()->json("", 204); // Return http response with status 204
    }

    /**
     * Admin handle delete the category where is not used by post
     *
     * @param int $categoryId
     * @return JsonResponse
     */
    public function destroy(int $categoryId): JsonResponse
    {
        $posts = $this->postRepository->getPostsByCategory($categoryId, 5); // Get all the posts with category's id

        if ($posts->total() !== 0) return response()->json("Category is used by post", 400); // If exits the posts then return bad request

        $result =  $this->categoryRepos->handleDeleteCategory($categoryId); // delete category infomation if category is exitsed

        if ($result === false) return response()->json("Category not found", 404);

        return response()->json("", 204);
    }
}
