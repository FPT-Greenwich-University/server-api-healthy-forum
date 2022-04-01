<?php

namespace App\Http\Controllers\Api\Admins\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admins\Categories\CreateOrUpdateCategoryRequest;
use App\Models\Category;
use App\Models\Post;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Admin store new category in resources
     *
     * @param CreateOrUpdateCategoryRequest $request
     * @return JsonResponse
     */
    public function store(CreateOrUpdateCategoryRequest $request): JsonResponse
    {
        $data = $request->only(['name', 'description']);

        try {
            Category::create($data);
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
            $category = Category::findOrFail($categoryID);
            $category->update([
                'name' => $request->input('name'),
                'description' => $request->input('description')
            ]);
            return response()->json('Update category successful');
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
            $category = Category::findOrFail($categoryID);
            $posts = Post::where('category_id', $categoryID)->get();

            if ($posts->count() === 0) { // if the category not used by the post then accept delete
                $category->delete();
                return response()->json('Delete category successful');
            } else { // return message not accept delete the category
                return response()->json("The category has been used by user, can't delete", 405);
            }
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
}
