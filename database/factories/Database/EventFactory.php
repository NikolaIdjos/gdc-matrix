<?php

namespace Database\Factories\Database;

use App\Models\Database\Enums\CompetitorTypeEnum;
use App\Models\Database\Enums\EventStatusEnum;
use App\Models\Database\Enums\QualifierEnum;
use App\Models\Database\Event;
use App\Models\Database\League;
use App\Models\Database\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->sentence(3);

        return [
            'league_id' => League::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'scheduled_at' => $this->faker->dateTimeBetween('+1 days', '+1 month'),
            'status_id' => EventStatusEnum::SCHEDULED->value,
            'competitor_type_id' => CompetitorTypeEnum::TEAM->value,
        ];
    }

    public function withTeams(int $count = 2): static
    {
        return $this->afterCreating(function (Event $event) use ($count) {
            $teams = Team::factory($count)->create();
            $event->teams()->attach([
                $teams[0]->id => ['qualifier_id' => QualifierEnum::HOME->value],
                $teams[1]->id => ['qualifier_id' => QualifierEnum::AWAY->value],
            ]);
        });
    }
}
