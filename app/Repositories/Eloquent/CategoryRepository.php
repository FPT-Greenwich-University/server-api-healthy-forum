<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\ICategoryRepository;
use Exception;

class CategoryRepository extends BaseRepository implements ICategoryRepository
{

    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function getAllCategories()
    {
        try {
            return parent::getAll();
        } catch (Exception $exception) {
            return $exception;
        }
    }

    public function handleDeleteCategory(int $id)
    {
        try {
            $existingCategory = parent::findById($id);
            if (is_null($existingCategory)) return false;

            parent::delete($id);
            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function updateCategory(int $id, array $attributes)
    {
        try {
            $existingCategory = parent::findById($id);
            if (is_null($existingCategory)) return false;

            parent::update($id, $attributes);
            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
