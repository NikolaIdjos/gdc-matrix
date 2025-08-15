<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property float $stake
 * @property float $combined_odds
 * @property float $potential_payout
 * @property array<int, SelectionResource>|null $selections
 * @property EventResource|null $event
 */
class BetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'stake' => $this->stake,
            'combined_odds' => $this->combined_odds,
            'potential_payout' => $this->potential_payout,
            'selections' => SelectionResource::collection($this->selections),
            'event' => new EventResource($this->event),
        ];
    }
}
