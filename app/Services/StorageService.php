<?php

namespace App\Services;

use Exception;
use App\Enums\ContentFolders;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\Services\StorageServiceInterface;

class StorageService implements StorageServiceInterface
{
    /**
     * Store content to s3.
     *
     * @param ContentFolders $folder
     * @param mixed $file
     * @return string|null
     */
    public function put(ContentFolders $folder, mixed $file): ?string
    {
        try {
            $uploadedPath = Storage::put($folder->value, $file);

            return $uploadedPath;
        } catch (Exception $e) {
            Log::error('Error on uploading content to storage. ErrMsg :: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Delete content from s3 by path.
     *
     * @param string $path
     * @return void
     */
    public function delete(string $path): bool
    {
        try {
            Storage::delete($path);

            return true;
        } catch (\Throwable $th) {
            Log::error('Error on deleteing from s3. ErrMsg :: ' . $th->getMessage());
        }

        return false;
    }
}
