<?php

namespace App\Interfaces\Services;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BannerServiceInterface
{
    public function delete(string $id): bool;
    public function getAllActive(): Collection;
    public function findById(string $id): ?Banner;
    public function getAll(Request $request): LengthAwarePaginator;
    public function create(Request $request): ?Banner;
    public function update(Request $request, int $id): bool;
}
