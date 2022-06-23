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
            return $file->move($targetDir, $fileName); // movie file to public folder

        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function deleteFile(string $pathFile)
    {
        try {
            $result = \File::delete($pathFile); // delete image file

            if ($result === false) return false;

            return true;

        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
