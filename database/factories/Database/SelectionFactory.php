<?php

namespace Database\Factories\Database;

use App\Models\Database\Market;
use App\Models\Database\Selection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Selection>
 */
class SelectionFactory extends Factory
{
    protected $model = Selection::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'market_id' => Market::factory(),
            'name' => $this->faker->randomElement(['Team A', 'Team B', 'Over 2.5', 'Under 2.5']),
            'odds' => $this->faker->randomFloat(2, 1.1, 5.0),
        ];
    }
}
