<?php

namespace App\Http\Controllers\Api\Admins\Categories;

use App\Http\Controllers\Api\Users\Profiles\ProfileController;
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
    final public function store(CreateOrUpdateCategoryRequest $request): JsonResponse
    {
        $this->categoryRepos->create($request->only(['name', 'description']));
        return response()->json('Create new category successful', 201);
    }

    /**
     * Admin update information of category in resources
     *
     * @param CreateOrUpdateCategoryRequest $request
     * @param int $categoryId
     * @return JsonResponse
     */
    final public function update(CreateOrUpdateCategoryRequest $request, int $categoryId): JsonResponse
    {
        // Check category is existed
        if (is_null($this->categoryRepos->findById($categoryId))) {
            return response()->json('Category not found', 404);
        }

        $this->categoryRepos->updateCategory($categoryId, $request->only(['name', 'description'])); // Update category information if category is existed

        return response()->json("", 204); // Return http response with status 204
    }

    /**
     * Admin handle delete the category where is not used by post
     *
     * @param int $categoryId
     * @return JsonResponse
     */
    final public function destroy(int $categoryId): JsonResponse
    {
        // Check category is existed
        if (is_null($this->categoryRepos->findById($categoryId))) {
            return response()->json('Category not found', 404);
        }

        /**
         *  Get all the posts with category's id
         *  If exits the posts then return bad request
         */
        if ($this->postRepository->getPostsByCategory(categoryId: $categoryId, perPage: 5)->total() !== 0) {
            return response()->json("Category is used by post", 400);
        }

        $this->categoryRepos->handleDeleteCategory($categoryId); // delete category

        return response()->json("", 204);
    }
}
