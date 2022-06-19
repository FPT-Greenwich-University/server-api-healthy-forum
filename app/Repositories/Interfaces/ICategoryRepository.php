<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\Common\IEloquentRepository;

interface ICategoryRepository extends IEloquentRepository
{
    public function handleDeleteCategory(int $id);
    public function updateCategory(int $id, array $attributes);
    public function getAllCategories();
}
