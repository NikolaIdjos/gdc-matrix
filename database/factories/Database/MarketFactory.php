<?php

namespace Database\Factories\Database;

use App\Models\Database\Event;
use App\Models\Database\Market;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Market>
 */
class MarketFactory extends Factory
{
    protected $model = Market::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $marketsData = [
            'Match Result',
            'Over/Under 2.5 Goals',
            'Both Teams to Score',
            'Double Chance',
            'Correct Score',
            'First Team to Score',
        ];

        return [
            'event_id' => Event::factory(),
            'name' => $this->faker->randomElement($marketsData),
        ];
    }
}
