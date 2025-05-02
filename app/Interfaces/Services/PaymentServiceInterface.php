<?php

namespace App\Interfaces\Services;

use App\Models\Payment;

interface PaymentServiceInterface
{
    /**
     * Find a payment by its ID
     *
     * @param string $id
     * @return Payment|null
     */
    public function findById(string $id): ?Payment;
}
