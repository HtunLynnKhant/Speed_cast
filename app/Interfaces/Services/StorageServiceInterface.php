<?php

namespace App\Interfaces\Services;

use App\Enums\ContentFolders;

interface StorageServiceInterface
{
    public function delete(string $path): bool;
    public function put(ContentFolders $folder, mixed $file): ?string;
}
