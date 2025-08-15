<?php

namespace Tests\Feature;

use App\Models\Database\Enums\EventStatusEnum;
use App\Models\Database\Event;
use App\Models\Database\Market;
use App\Models\Database\Selection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BetCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculates_combined_odds_and_potential_payout()
    {
        $event = Event::factory()->create(['status_id' => collect(EventStatusEnum::cases())->random()->value]);
        $market = Market::factory()->create(['event_id' => $event->id]);
        $selection = Selection::factory()->create([
            'market_id' => $market->id,
            'odds' => 2,
        ]);

        $stake = 100;

        $response = $this->postJson('/api/bets', [
            'selection_ids' => [$selection->id],
            'stake' => $stake,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'stake' => $stake,
                'combined_odds' => 2,
                'potential_payout' => 200,
            ])
            ->assertJsonStructure([
                'stake',
                'combined_odds',
                'potential_payout',
                'selections',
                'event',
            ]);
    }

    public function test_returns_422_if_selection_ids_or_stake_missing()
    {
        $response = $this->postJson('/api/bets', []);
        $response->assertStatus(422);
    }

    public function test_fails_if_multiple_selections_from_same_market()
    {
        $market = Market::factory()->create();
        $selection1 = Selection::factory()->create(['market_id' => $market->id]);
        $selection2 = Selection::factory()->create(['market_id' => $market->id]);

        $stake = 100;

        $response = $this->postJson('/api/bets', [
            'selection_ids' => [$selection1->id, $selection2->id],
            'stake' => $stake,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['selection_ids'])
            ->assertJsonFragment([
                'selection_ids' => ['You can only choose one selection per market.'],
            ]);
    }

    public function test_fails_if_selections_from_different_events()
    {
        $event1 = Event::factory()->create();
        $event2 = Event::factory()->create();

        $market1 = Market::factory()->create(['event_id' => $event1->id]);
        $market2 = Market::factory()->create(['event_id' => $event2->id]);

        $selection1 = Selection::factory()->create(['market_id' => $market1->id]);
        $selection2 = Selection::factory()->create(['market_id' => $market2->id]);

        $stake = 100;

        $response = $this->postJson('/api/bets', [
            'selection_ids' => [$selection1->id, $selection2->id],
            'stake' => $stake,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['selection_ids'])
            ->assertJsonFragment([
                'selection_ids' => ['All selections must belong to the same event.'],
            ]);
    }
}
