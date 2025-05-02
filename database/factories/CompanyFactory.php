<?php

namespace Database\Factories;

use App\Models\Category;
use App\Enums\RecordStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    public function definition(): array
    {
        $category = Category::get();

        return [
            'name' => fake()->name(),
            'logo' => fake()->imageUrl(),
            'status' => RecordStatus::ACTIVE,
            'category_id' => $category->isNotEmpty()
                ? fake()->randomElement($category->pluck('id')->all())
                : Category::factory()->create()->id,
            'description' => fake()->sentence()
        ];
    }
}
