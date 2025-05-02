<?php

namespace App\Interfaces\Services;

use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface AdstypeServiceInterface
{
    /**
     * Get all active ad types.
     *
     * @return Collection
     */
    public function getAllActive(): Collection;

    /**
     * Get all ad types with pagination and optional filters.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getAll(Request $request): LengthAwarePaginator;
}
