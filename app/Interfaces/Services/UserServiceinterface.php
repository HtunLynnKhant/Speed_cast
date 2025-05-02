<?php

namespace App\Interfaces\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function getAll(Request $request): LengthAwarePaginator;
    public function create(Request $request): ?User;
    public function update(User $user, Request $request): ?User;
    public function delete(User $user): bool;

}
