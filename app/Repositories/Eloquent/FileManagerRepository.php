<?php

namespace App\Repositories\Eloquent;

use App\Models\File;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\Interfaces\IFileManagerRepository;

class FileManagerRepository extends BaseRepository implements IFileManagerRepository
{
    public function __construct(File $model)
    {
        parent::__construct($model);
    }
}
