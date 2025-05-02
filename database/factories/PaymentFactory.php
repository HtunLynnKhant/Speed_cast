<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Enums\PaymentTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'date' => fake()->dateTime(),
            'ads_id' => fake()->randomDigit(),
            'amount' => fake()->randomFloat(2, 0, 99999999.99),
            'currency_id' => Currency::whereCode('MYR')->first()?->id,
            'payment_type' => fake()->randomElement(PaymentTypes::cases()),
            'is_fully_paid' => fake()->randomElement([true, false])
        ];
    }
}
