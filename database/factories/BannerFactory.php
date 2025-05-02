<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Factories\Factory;

class BannerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'description' => fake()->sentence(),
            'category_id' => $this->getCategoryId()
        ];
    }

    private function getCategoryId(): mixed
    {
        $categories = Category::pluck('id');
        if ($categories->isNotEmpty()) {
            return Arr::random($categories->all());
        }

        return Category::factory()->create()?->id;
    }
}
