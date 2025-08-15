<?php

namespace Tests\Unit;

use App\Models\Database\Selection;
use App\Services\BetCalculator;
use Illuminate\Support\Collection;
use Tests\TestCase;

class BetCalculatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = $this->app->make(BetCalculator::class);
    }

    public function test_combined_odds()
    {
        $selections = new Collection([
            new Selection(['odds' => 1.5]),
            new Selection(['odds' => 2.0]),
        ]);

        $this->assertEquals(3.0, $this->calculator->combinedOdds($selections));
    }

    public function test_potential_payout_rounding()
    {
        $selections = new Collection([
            new Selection(['odds' => 1.3333]),
            new Selection(['odds' => 1.6667]),
        ]);

        $this->assertEquals(22.22, $this->calculator->potentialPayout($selections, 10));
    }

    public function test_edge_case_no_selections()
    {
        $selections = new Collection;

        $this->assertEquals(1.0, $this->calculator->combinedOdds($selections));
        $this->assertEquals(10.0, $this->calculator->potentialPayout($selections, 10));
    }

    public function test_large_values()
    {
        $selections = new Collection([
            new Selection(['odds' => 100]),
            new Selection(['odds' => 50]),
        ]);

        $this->assertEquals(5000, $this->calculator->combinedOdds($selections));
        $this->assertEquals(500000, $this->calculator->potentialPayout($selections, 100));
    }

    public function test_zero_odds()
    {
        $selections = new Collection([
            new Selection(['odds' => 2.0]),
            new Selection(['odds' => 0]),
        ]);

        $this->assertEquals(0.0, $this->calculator->combinedOdds($selections));
        $this->assertEquals(0.0, $this->calculator->potentialPayout($selections, 10));
    }

    public function test_potential_payout_precision()
    {
        $selections = new Collection([
            new Selection(['odds' => 1.2345]),
            new Selection(['odds' => 2.3456]),
        ]);

        $this->assertSame(28.95, $this->calculator->potentialPayout($selections, 10));
    }
}
