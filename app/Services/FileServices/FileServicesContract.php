<?php

namespace App\Services\FileServices;

interface FileServicesContract
{
    public function storeFile($file, string $targetDir, string $fileName);

    public function deleteFile(string $pathFile);
}
