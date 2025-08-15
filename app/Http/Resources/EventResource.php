<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'scheduled_at' => $this->scheduled_at?->toIso8601String(),
            'status_id' => $this->status_id->value,
            'status' => $this->status_id->name,
            'competitor_type_id' => $this->competitor_type_id->value,
            'competitor_type' => $this->competitor_type_id->name,
            'league' => new LeagueResource($this->whenLoaded('league')),
            'teams' => TeamResource::collection($this->whenLoaded('teams')),
            'markets' => MarketResource::collection($this->whenLoaded('markets')),
        ];
    }
}
