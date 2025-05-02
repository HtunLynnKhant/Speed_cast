<?php

namespace Database\Factories;

use App\Enums\RecordStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'active_icon_path' => fake()->imageUrl(),
            'default_icon_path' => fake()->imageUrl(),
            'description' => fake()->sentence(),
            'status' => RecordStatus::ACTIVE
        ];
    }
}
