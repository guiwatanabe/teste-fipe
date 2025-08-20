<?php

namespace Database\Factories;

use App\Models\VehicleModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VehicleModelYear>
 */
class VehicleModelYearFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vehicle_model_id' => VehicleModel::factory(),
            'model_year_code' => fake()->numberBetween(1, 99999),
            'model_year_name' => fake()->word(),
            'model_year_notes' => fake()->text(),
            'update_user' => null,
        ];
    }
}
