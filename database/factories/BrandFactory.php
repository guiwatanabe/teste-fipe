<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vehicle_type_id' => fake()->numberBetween(1, 3),
            'brand_code' => fake()->numberBetween(1, 99999),
            'brand_name' => fake()->word(),
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
