<?php

namespace App\Repositories\Interfaces\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface IEloquentRepository
{
    /**
     * Create new resources in system
     *
     * @param array $attributes
     */
    public function create(array $attributes);

    /**
     * @param int $id
     */
    public function findById(int $id);

    /**
     * Get all resources from system
     */
    public function getAll();

    /**
     * Update resources by id
     *
     * @param int $id
     * @param array $attributes
     */
    public function update(int $id, array $attributes);


    /**
     * Delete resources by id
     *
     * @param int $id
     */
    public function delete(int $id);
}
