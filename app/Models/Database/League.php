<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Database\Event[]|HasMany $events
 */
class League extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
