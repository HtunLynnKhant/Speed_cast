<?php

namespace App\Interfaces\Services;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProjectServiceInterface
{
    public function delete(string $id): bool;
    public function getAllActive(): Collection;
    public function findById(string $id): Project;
    public function getAll(Request $request): LengthAwarePaginator;
    public function create(Request $request): ?Project;
    public function update(string $id, Request $request): Project;
}
