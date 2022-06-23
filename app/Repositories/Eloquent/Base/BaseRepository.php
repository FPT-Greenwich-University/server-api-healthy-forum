<?php

namespace App\Repositories\Eloquent\Base;

use App\Repositories\Interfaces\Common\IEloquentRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BaseRepository implements IEloquentRepository
{

    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function update(int $id, array $attributes)
    {
        return $this->model->where('id', '=', $id)->update($attributes);
    }

    public function delete(int $id)
    {
        return $this->model->destroy($id);
    }
}
