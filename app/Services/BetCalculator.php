<?php

namespace App\Services;

use Illuminate\Support\Collection;

/**
 * Service class for calculating bet-related values like
 * combined odds and potential payout.
 */
class BetCalculator
{
    /**
     * Calculate the combined odds of multiple selections.
     *
     * Multiplies all selection odds together using high-precision arithmetic.
     *
     * @param  Collection  $selections  Collection of Selection models with an 'odds' property
     * @return float Combined odds
     */
    public function combinedOdds(Collection $selections): float
    {
        $result = 1;
        foreach ($selections as $selection) {
            $result = bcmul($result, (string) $selection->odds, 10);
        }

        return (float) $result;
    }

    /**
     * Calculate the potential payout for a bet given selections and stake.
     *
     * Uses combined odds and multiplies by stake.
     *
     * @param  Collection  $selections  Collection of Selection models
     * @param  float  $stake  Amount of money bet
     * @return float Potential payout
     */
    public function potentialPayout(Collection $selections, float $stake): float
    {
        return (float) bcdiv(bcmul($stake, $this->combinedOdds($selections), 4), '1', 2);
    }
}
