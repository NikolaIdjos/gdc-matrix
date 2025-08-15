<?php

namespace Database\Seeders;

use App\Models\Database\Team;
use Illuminate\Database\Seeder;

class TeamsTableSeeder extends Seeder
{
    public function run(): void
    {
        Team::factory(10)->create();
    }
}
