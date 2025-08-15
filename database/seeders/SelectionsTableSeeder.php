<?php

namespace Database\Seeders;

use App\Models\Database\Market;
use App\Models\Database\Selection;
use Illuminate\Database\Seeder;

class SelectionsTableSeeder extends Seeder
{
    public function run(): void
    {
        Market::all()->each(function (Market $market) {
            Selection::factory()
                ->count(3)
                ->for($market)
                ->create();
        });
    }
}
