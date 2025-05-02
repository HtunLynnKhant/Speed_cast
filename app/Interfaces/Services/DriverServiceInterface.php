<?php

namespace App\Interfaces\Services;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DriverServiceInterface
{
    public function getAll(Request $request): LengthAwarePaginator;
    public function findById(string $id): Driver;
    public function create(Request $request): ?Driver;
    public function update(string $id, Request $request): Driver;
    public function getAllActive(): Collection;
    public function delete(string $id): bool;
}
