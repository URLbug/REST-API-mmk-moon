<?php

namespace Database\Factories;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->company(),
            'parentActivityID' => null,
        ];
    }

    public function withParent(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'parentActivityID' => Activity::factory()->create()->activityID,
            ];
        });
    }
}
