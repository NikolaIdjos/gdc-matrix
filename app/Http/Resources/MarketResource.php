<?php

namespace App\Http\Resources;

use App\Models\Database\Market;
use App\Models\Database\Selection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Market
 * @property int $id
 * @property string $name
 * @property-read Collection|Selection[] $selections
 */
class MarketResource extends JsonResource
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
            'selections' => SelectionResource::collection($this->whenLoaded('selections')),
        ];
    }
}
