<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bet\StoreBetRequest;
use App\Http\Resources\BetResource;
use App\Models\Database\Selection;
use App\Services\BetCalculator;
use Illuminate\Http\JsonResponse;

/**
 * Handles bet calculations and returns bet details including
 * combined odds and potential payout.
 */
class BetController extends Controller
{
    protected BetCalculator $calculator;

    /**
     * @param  BetCalculator  $calculator
     */
    public function __construct(BetCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * Calculate combined odds and potential payout for the given selections.
     */
    public function calculate(StoreBetRequest $request): JsonResponse
    {
        $selections = Selection::with('market.event')
            ->whereIn('id', $request->get('selection_ids'))
            ->get();

        $combinedOdds = $this->calculator->combinedOdds($selections);
        $potentialPayout = $this->calculator->potentialPayout($selections, $request->get('stake'));

        return response()->json(new BetResource((object) [
            'stake' => $request->get('stake'),
            'combined_odds' => $combinedOdds,
            'potential_payout' => $potentialPayout,
            'selections' => $selections,
            'event' => $selections->first()->market->event,
        ]));
    }
}
