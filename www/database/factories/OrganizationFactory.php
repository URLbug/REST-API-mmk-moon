<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use App\Models\Phone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
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
            'phoneID' => Phone::factory()->create()->phoneID,
            'buildingID' => Building::factory()->create()->buildingID,
            'activityID' => Activity::factory()->withParent()->create()->activityID,
        ];
    }
}
