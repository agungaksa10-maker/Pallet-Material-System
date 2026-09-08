<?php

namespace Database\Factories;

use App\Models\MasterMaterial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MasterMaterial>
 */
class MasterMaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_code' => fake()->unique()->numerify('300######'),
            'name' => fake()->words(3, true),
            'category' => fake()->randomElement(['Dressing', 'Consumable']),
            'default_unit' => fake()->randomElement(['UNIT', 'PCS', 'ROLL', 'SET']),
            'specification' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
