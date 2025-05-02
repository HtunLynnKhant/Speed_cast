<?php

namespace App\Interfaces\Services;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ClientServiceInterface
{
    public function getAll(Request $request): LengthAwarePaginator;
    public function create(Request $request): ?Client;
    public function findById(string $id): Client;
    public function update(array $data, string $id): bool;
    public function delete(string $id): bool;
}
