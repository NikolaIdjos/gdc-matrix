<?php

namespace App\Models\Database;

use Database\Factories\Database\SelectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $market_id
 * @property string $name
 * @property float $odds
 * @property-read Market $market
 */
class Selection extends Model
{
    /**
     * @use HasFactory<SelectionFactory>
     */
    use HasFactory;

    protected $fillable = [
        'market_id',
        'name',
        'odds',
    ];

    /**
     * @return BelongsTo<Market,$this>
     */
    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }
}
