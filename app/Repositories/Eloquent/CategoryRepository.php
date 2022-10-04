<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\ICategoryRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository implements ICategoryRepository
{

    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    final public function getAllCategories(): Collection|string
    {
        try {
            return $this->getAll();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    final public function handleDeleteCategory(int $id): bool|string
    {
        try {
            $existingCategory = $this->findById($id);

            if (is_null($existingCategory)) {
                return false;
            }

            $this->delete($id);
            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    final public function updateCategory(int $id, array $attributes): bool|string
    {
        try {
            $existingCategory = $this->findById($id);

            if (is_null($existingCategory)) {
                return false;
            }

            $this->update($id, $attributes);
            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
