<?php

namespace Database\Factories;

use App\Enums\RecordStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'status' => RecordStatus::ACTIVE,
            'description' => fake()->sentence(),
            'client_id' => fake()->randomDigit()
        ];
    }
}
