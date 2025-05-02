<?php

namespace App\Services;

use App\Interfaces\Services\ContentServiceInterface;

class ContentService implements ContentServiceInterface
{
    public function __construct(
        private readonly StorageService $storage
    ) {
    }

    public function create()
    {
    }
}
