<?php

namespace App\Services\FileServices;

use Exception;
use File;

class HandleFile implements FileServicesContract
{

    public function storeFile($file, string $targetDir, string $fileName)
    {
        try {
            $fileName = $file->hashName(); // Generate a unique, random name...
            $targetDir = 'posts/thumbnails/'; // set default path

            // movie file to public folder
            if (!$file->move($targetDir, $fileName)) return false;

            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function deleteFile(string $pathFile)
    {
        try {
            if (!File::delete($pathFile)) return false;

            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
