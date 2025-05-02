<?php

namespace Database\Factories;

use App\Models\Project;
use App\Enums\RecordStatus;
use App\Enums\PaymentStatus;
use App\Models\Subscription;
use App\Enums\ApprovalStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdFactory extends Factory
{
    public function definition(): array
    {
        return [
            'status' => RecordStatus::ACTIVE,
            'content_path' => fake()->imageUrl(),
            'ads_type_id' => fake()->randomDigit(),
            'content_type_id' => fake()->randomDigit(),
            'payment_status' => PaymentStatus::PENDING,
            'approval_status' => ApprovalStatus::PENDING,
            'discount' => fake()->randomFloat(2, 0, 100.00),
            'total_price' => fake()->randomFloat(2, 0, 100000.00),
            'subscription_id' => Subscription::factory()->create()->id,
            'total_final_price' => fake()->randomFloat(2, 0, 100000.00),
            'project_id' => fake()->randomElement(Project::pluck('id')->all())
        ];
    }
}
