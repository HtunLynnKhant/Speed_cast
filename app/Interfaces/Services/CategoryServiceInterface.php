<?php

namespace App\Interfaces\Services;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CategoryServiceInterface
{
    public function delete(string $id): bool;
    public function getAllActive(): Collection;
    public function getAll(Request $request): LengthAwarePaginator;
    public function findById(string $id): Category;
    public function create(Request $request): ?Category;
    public function getBannersById(string $id): Collection;
    public function getCompaniesById(string $id): Collection;
    public function update(string $id, Request $request): Category;
}
