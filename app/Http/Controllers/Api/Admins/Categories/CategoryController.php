<?php

namespace App\Http\Controllers\Api\Admins\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admins\Categories\CreateOrUpdateCategoryRequest;
use App\Repositories\Interfaces\ICategoryRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    private ICategoryRepository $categoryRepos;

    public function __construct(ICategoryRepository $categoryRepository)
    {
        $this->categoryRepos = $categoryRepository;
    }

    /**
     * Admin store new category in resources
     *
     * @param CreateOrUpdateCategoryRequest $request
     * @return JsonResponse
     */
    public function store(CreateOrUpdateCategoryRequest $request): JsonResponse
    {
        try {

            $this->categoryRepos->create($request->only(['name', 'description']));
            return response()->json('Create new category successful');
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
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
        try {
           $category = $this->categoryRepos->findById($categoryID);

            if (!is_null($category)) {
                $this->categoryRepos->update($categoryID, $request->only(['name', 'description']));
            }

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), 404);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }

    /**
     * Admin handle delete the category where is not used by post
     *
     * @param $categoryID
     * @return JsonResponse
     */
    public function destroy($categoryID): JsonResponse
    {
        try {
            $result = $this->categoryRepos->handleDeleteCategory($categoryID);

            if($result === false) {
               return response()->json("Category has used by products, can't be not delete", Response::HTTP_BAD_REQUEST);
            }

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            return response()->json([
                'Message' => $exception->getMessage(),
                'Line' => $exception->getLine(),
                'File' => $exception->getFile(),
            ], 500);
        }
    }
}
