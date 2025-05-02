<?php

namespace App\Services;

use App\Interfaces\Services\PaymentServiceInterface;
use App\Models\Payment;

class PaymentService implements PaymentServiceInterface
{
    /**
     * Find a payment by its ID.
     *
     * @param string $id
     * @return Payment|null
     */
    public function findById(string $id): ?Payment
    {
        return Payment::find($id);
    }
}
