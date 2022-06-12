<?php

namespace App\Repositories\Interfaces;

interface ICategoryRepository
{
    /**
     * @param int $id category's id
     * @return bool true if success
     * otherwise false
     */
    public function handleDeleteCategory(int $id): bool;
}
