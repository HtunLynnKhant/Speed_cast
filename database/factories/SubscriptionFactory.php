<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'active_from' => Carbon::now(),
            'end_on' => Carbon::now()->addMonth(1)
        ];
    }
}
