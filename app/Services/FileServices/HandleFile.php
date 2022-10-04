<?php

namespace App\Services\FileServices;

use Exception;
use File;

class HandleFile implements FileServicesContract
{

    final public function storeFile($file, string $targetDir, string $fileName): bool|string
    {
        try {
            $fileName = $file->hashName(); // Generate a unique, random name...

            // movie file to public folder
            if (!$file->move($targetDir, $fileName)) {
                return false;
            }

            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    final public function deleteFile(string $pathFile): bool|string
    {
        try {
            if (!File::delete($pathFile)) {
                return false;
            }

            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
