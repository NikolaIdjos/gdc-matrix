<?php

namespace Tests\Feature;

use App\Models\Database\Enums\QualifierEnum;
use App\Models\Database\Event;
use App\Models\Database\League;
use App\Models\Database\Market;
use App\Models\Database\Selection;
use App\Models\Database\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_single_event()
    {
        $league = League::factory()->create();
        $teams = Team::factory()->count(2)->create();

        $event = Event::factory()->create(['league_id' => $league->id]);
        $market = Market::factory()->create(['event_id' => $event->id]);
        $selection = Selection::factory()->create(['market_id' => $market->id]);
        $event->teams()->attach(
            $teams->pluck('id'),
            ['qualifier_id' => collect(QualifierEnum::cases())->random()->value]
        );

        $response = $this->getJson('/api/events/'.$event->id);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $event->id,
                'name' => $event->name,
                'status_id' => $event->status_id->value,
                'status' => $event->status_id->name,
                'competitor_type_id' => $event->competitor_type_id->value,
                'competitor_type' => $event->competitor_type_id->name,
            ]);

        $response->assertJsonFragment([
            'id' => $league->id,
            'name' => $league->name,
            'slug' => $league->slug,
        ]);

        $this->assertCount($teams->count(), $response->json('teams'));
        foreach ($teams as $team) {
            $response->assertJsonFragment([
                'id' => $team->id,
                'name' => $team->name,
                'slug' => $team->slug,
            ]);
        }

        $this->assertCount(1, $response->json('markets'));
        $response->assertJsonFragment([
            'id' => $market->id,
            'name' => $market->name,
        ]);

        $this->assertCount(1, $response->json('markets.0.selections'));
        $response->assertJsonFragment([
            'id' => $selection->id,
            'market_id' => $selection->market_id,
            'name' => $selection->name,
            'odds' => $selection->odds,
        ]);
    }
}
