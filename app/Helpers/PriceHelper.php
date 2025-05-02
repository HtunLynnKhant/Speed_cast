<?php

namespace App\Helpers;

class PriceHelper
{
    public static function calculateAdFinalPrice(int $discountAmount, int $totalAmount)
    {
        $taxAmount = 0;
        $taxPercentage = config('settings.tax_percentage');
        $priceAfterDiscount = $totalAmount - $discountAmount;
        if ($taxPercentage > 0) {
            $taxAmount = $priceAfterDiscount * ($taxPercentage / 100);
        }

        return number_format((float) ($priceAfterDiscount + $taxAmount), 2, '.', '');
    }
}
