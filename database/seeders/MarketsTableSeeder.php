<?php

namespace Database\Seeders;

use App\Models\Database\Event;
use App\Models\Database\Market;
use App\Models\Database\Selection;
use Illuminate\Database\Seeder;

class MarketsTableSeeder extends Seeder
{
    public function run(): void
    {
        $marketsData = [
            'Match Result' => ['Team A', 'Draw', 'Team B'],
            'Over/Under 2.5 Goals' => ['Over 2.5', 'Under 2.5'],
            'Both Teams to Score' => ['Yes', 'No'],
            'Double Chance' => ['Team A or Draw', 'Draw or Team B', 'Team A or Team B'],
            'Correct Score' => ['1-0', '2-0', '2-1', '0-0', '1-1', '0-1', '0-2', '1-2', '2-2'],
            'First Team to Score' => ['Team A', 'Team B', 'No Goal'],
        ];

        $events = Event::all();

        foreach ($events as $event) {
            foreach ($marketsData as $marketName => $selections) {
                $market = Market::factory()->create([
                    'name' => $marketName,
                    'event_id' => $event->id, // vezujemo market za event
                ]);

                foreach ($selections as $selectionName) {
                    Selection::factory()->create([
                        'market_id' => $market->id,
                        'name' => $selectionName,
                    ]);
                }
            }
        }
    }
}
