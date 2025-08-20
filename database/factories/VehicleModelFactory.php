<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VehicleModel>
 */
class VehicleModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'brand_id' => Brand::factory(),
            'model_code' => fake()->numberBetween(1, 99999),
            'model_name' => fake()->word(),
            'model_notes' => fake()->text(),
            'processed' => 0,
        ];
    }

    /**
     * Gerar um modelo com o status de processado
     */
    public function processed(): static
    {
        return $this->state(fn (array $attributes) => [
            'processed' => 1,
        ]);
    }
}
