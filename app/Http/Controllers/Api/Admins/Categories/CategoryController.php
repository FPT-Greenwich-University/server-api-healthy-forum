<?php

namespace App\Http\Controllers\Api\Admins\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admins\Categories\CreateOrUpdateCategoryRequest;
use App\Repositories\Interfaces\ICategoryRepository;
use App\Repositories\Interfaces\IPostRepository;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    private ICategoryRepository $categoryRepos;
    private IPostRepository $postRepository;

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
     * @param $categoryID
     * @return JsonResponse
     */
    public function update(CreateOrUpdateCategoryRequest $request, $categoryID): JsonResponse
    {
        $attributes = $request->only(['name', 'description']);

        $result = $this->categoryRepos->update($categoryID, $attributes);

        if ($result === false) return response()->json("Category not found", 404);

        return response()->json("", 204);
    }

    /**
     * Admin handle delete the category where is not used by post
     *
     * @param $categoryID
     * @return JsonResponse
     */
    public function destroy($categoryID): JsonResponse
    {
        $posts = $this->postRepository->getPostsByCategory($categoryID, 5);

        if ($posts->total() !== 0) return response()->json("Category is used by post", 400);

        $result =  $this->categoryRepos->handleDeleteCategory($categoryID);

        if ($result === false) return response()->json("Category not found", 404);

        return response()->json("", 204);
    }
}
