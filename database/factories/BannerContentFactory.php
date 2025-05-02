<?php

namespace Database\Factories;

use App\Models\Banner;
use App\Enums\ContentTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

class BannerContentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'banner_id' => Banner::factory()->create()->id,
            'type' => fake()->randomElement(ContentTypes::cases()),
            'path' => fake()->imageUrl()
        ];
    }
}
