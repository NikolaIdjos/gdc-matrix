<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $market_id
 * @property string $name
 * @property float $odds
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Market $market
 */
class Selection extends Model
{
    use HasFactory;

    protected $fillable = [
        'market_id',
        'name',
        'odds',
    ];

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }
}
