<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\Common\IEloquentRepository;

interface IPermissionRepository extends IEloquentRepository
{
   public function findByName(string $name);

}
