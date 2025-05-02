<?php

namespace App\Services;

use App\Interfaces\Services\SubscriptionServiceInterface;
use App\Models\Subscription;
use App\Interfaces\Services\StorageServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class SubscriptionService implements SubscriptionServiceInterface
{
    public function __construct(
        private readonly StorageServiceInterface $storage
    ) {
    }
    /**
     * Get all active subscription.
     *
     * @return Collection
     */
    public function getAllActive(): Collection
    {
        $subscription = Subscription::active()->get();

        return $subscription;
    }
}
