<?php

namespace App\Interfaces\Services;

use Illuminate\Database\Eloquent\Collection;

interface SubscriptionServiceInterface
{
    public function getAllActive(): Collection;
}
