<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\IPermissionRepository;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends BaseRepository implements IPermissionRepository
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }
}
