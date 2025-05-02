<?php

namespace App\Interfaces\Services;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

interface CompanyServiceInterface
{
    public function getAll(): Collection;
    public function create(Request $request): ?Company;
}
