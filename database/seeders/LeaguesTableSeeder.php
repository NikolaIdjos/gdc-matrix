<?php

namespace Database\Seeders;

use App\Models\Database\League;
use Illuminate\Database\Seeder;

class LeaguesTableSeeder extends Seeder
{
    public function run(): void
    {
        League::factory(3)->create();
    }
}
