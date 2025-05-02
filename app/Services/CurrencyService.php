<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Services\CurrenciesServiceInterface;
use App\Interfaces\Services\StorageServiceInterface;
use App\Models\Currency;

class CurrencyService implements CurrenciesServiceInterface
{
    public function __construct(
        private readonly StorageServiceInterface $storage
    ) {
    }
    /**
         * Get all active crrency.
         *
         * @return Collection
         */
    public function getAllActive(): Collection
    {
        $adstype = Currency::active()->get();

        return $adstype;
    }

}
