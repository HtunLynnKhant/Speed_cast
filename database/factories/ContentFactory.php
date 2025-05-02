<?php

namespace Database\Factories;

use App\Enums\ContentTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'path' => fake()->imageUrl(),
            'description' => fake()->sentence(),
            'type' => fake()->randomElement(ContentTypes::cases()),
        ];
    }
}
