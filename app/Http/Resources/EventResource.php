<?php

namespace App\Http\Resources;

use App\Models\Database\Enums\CompetitorTypeEnum;
use App\Models\Database\Enums\EventStatusEnum;
use App\Models\Database\Event;
use App\Models\Database\League;
use App\Models\Database\Market;
use App\Models\Database\Team;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Event
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property Carbon|null $scheduled_at
 * @property EventStatusEnum $status_id
 * @property CompetitorTypeEnum $competitor_type_id
 * @property-read League|null $league
 * @property-read Collection|Team[] $teams
 * @property-read Collection|Market[] $markets
 */
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
