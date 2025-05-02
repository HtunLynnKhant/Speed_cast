<?php

namespace App\Interfaces\Services;

use Illuminate\Database\Eloquent\Collection;

interface CurrenciesServiceInterface
{
    public function getAllActive(): Collection;
}
