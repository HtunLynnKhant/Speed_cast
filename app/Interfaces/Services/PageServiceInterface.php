<?php

namespace App\Interfaces\Services;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PageServiceInterface
{
    public function getAllActive(): Collection;
    public function findById(string $id): ?Page;
    public function getAll(): LengthAwarePaginator;
    public function create(Request $request): ?Page;
    public function update(Request $request, int $id): ?Page;
    public function delete(int $id): ?bool;
    public function find(int $id): ?Page;
    public function getPagesByCategory($categoryId);
    public function getAllByCategoryId(int $categoryId): LengthAwarePaginator;

}
