<?php

namespace App\Http\Resources;

use App\Models\Database\Enums\QualifierEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
            'qualifier_id' => $this->whenPivotLoaded('event_team', function () {
                return $this->pivot->qualifier_id;
            }),
            'qualifier' => $this->whenPivotLoaded('event_team', function () {
                return QualifierEnum::from($this->pivot->qualifier_id)->name;
            }),
        ];
    }
}
