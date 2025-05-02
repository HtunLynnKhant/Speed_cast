<?php

namespace App\Interfaces\Services;

use App\Models\Subbanner;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

interface SubbannerServiceInterface
{
    public function getAllActive(): Collection;
    public function getAll(Request $request): LengthAwarePaginator;
    public function create(Request $request): ?Subbanner;
    public function findById(string $id): Subbanner;
    public function update(string $id, Request $request): Subbanner;
    public function delete(string $id): bool;
}
