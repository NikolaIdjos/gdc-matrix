<?php

namespace App\Models\Database;

use App\Models\Database\Enums\CompetitorTypeEnum;
use App\Models\Database\Enums\EventStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $league_id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon $scheduled_at
 * @property EventStatusEnum $status_id
 * @property CompetitorTypeEnum $competitor_type_id
 *
 * @property-read League $league
 * @property-read Market[]|HasMany $markets
 * @property-read Team[]|BelongsToMany $teams
 */
class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'league_id',
        'name',
        'slug',
        'scheduled_at',
        'status_id',
        'competitor_type_id',
    ];

    protected $casts = [
        'status_id' => EventStatusEnum::class,
        'competitor_type_id' => CompetitorTypeEnum::class,
        'scheduled_at' => 'datetime',
    ];

    public function markets(): HasMany
    {
        return $this->hasMany(Market::class);
    }

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(
            Team::class,
            'event_team',
            'event_id',
            'team_id'
        )->withPivot('qualifier_id');
    }
}
