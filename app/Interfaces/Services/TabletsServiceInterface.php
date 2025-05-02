<?php

namespace App\Interfaces\Services;

use App\Models\Tablet;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TabletsServiceInterface
{
    public function getAll(Request $request): LengthAwarePaginator;
    public function create(Request $request): ?Tablet;
    public function delete(string $id): bool;
    public function getAllActive(): Collection;
    public function findById(string $id): Tablet;
    public function update(string $id, Request $request): Tablet;

}
