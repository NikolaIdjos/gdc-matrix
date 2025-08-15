<?php

namespace Tests\Feature;

use App\Models\Database\Enums\EventStatusEnum;
use App\Models\Database\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_all_events_without_filters()
    {
        Event::factory()->count(5)->create();

        $response = $this->getJson('/api/events');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_filters_events_by_status_id()
    {
        foreach (EventStatusEnum::cases() as $status) {
            Event::factory()->count(3)->create(['status_id' => $status->value]);
            $response = $this->getJson('/api/events?status_id='.$status->value);

            $response->assertStatus(200)
                ->assertJsonCount(3, 'data')
                ->assertJsonFragment(['status_id' => $status->value]);
        }
    }

    public function test_filters_events_by_search_term()
    {
        $event = Event::factory()->create(['name' => 'Super Cup Final']);
        Event::factory()->create(['name' => 'Friendly Match']);

        $response = $this->getJson('/api/events?search=Super');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => $event->name]);
    }

    public function test_filters_events_by_starts_after()
    {
        Event::factory()->create(['scheduled_at' => now()->addDays(5)]);
        $event = Event::factory()->create(['scheduled_at' => now()->addDays(10)]);

        $response = $this->getJson('/api/events?starts_after='.now()->addDays(7)->format('Y-m-d'));

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['id' => $event->id]);
    }

    public function test_combines_filters()
    {
        $statusId = EventStatusEnum::SCHEDULED->value;

        $event = Event::factory()->create([
            'name' => 'Championship Match',
            'status_id' => $statusId,
            'scheduled_at' => now()->addDays(5)->format('Y-m-d'),
        ]);

        Event::factory()->create([
            'name' => 'Friendly Match',
            'status_id' => EventStatusEnum::SCHEDULED->value,
            'scheduled_at' => now()->addDays(2)->format('Y-m-d'),
        ]);

        $response = $this->getJson(
            '/api/events?status_id='.$statusId.'&search=Championship&starts_after='.now()->format('Y-m-d')
        );

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['id' => $event->id]);
    }
}
