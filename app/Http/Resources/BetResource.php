<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
