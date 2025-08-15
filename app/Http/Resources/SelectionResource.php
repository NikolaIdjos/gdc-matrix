<?php

namespace App\Http\Resources;

use App\Models\Database\Selection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Selection
 * @property int $id
 * @property int $market_id
 * @property string $name
 * @property float $odds
 */
class SelectionResource extends JsonResource
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
            'market_id' => $this->market_id,
            'name' => $this->name,
            'odds' => (float) $this->odds,
        ];
    }
}
