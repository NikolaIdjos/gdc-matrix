<?php

namespace Database\Seeders;

use App\Models\Database\Event;
use App\Models\Database\League;
use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    public function run(): void
    {
        League::all()->each(function ($league) {
            Event::factory(5)
                ->for($league)
                ->withTeams()
                ->create();
        });
    }
}
