<?php

namespace App\Models\Database;

use App\Models\Database\Enums\CompetitorTypeEnum;
use App\Models\Database\Enums\EventStatusEnum;
use App\Models\Database\Pivot\EventTeam;
use Database\Factories\Database\EventFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $league_id
 * @property string $name
 * @property string $slug
 * @property Carbon $scheduled_at
 * @property EventStatusEnum $status_id
 * @property CompetitorTypeEnum $competitor_type_id
 * @property-read League $league
 * @property-read Collection<int, Market> $markets
 * @property-read Collection<int, Team> $teams
 */
class Event extends Model
{
    /**
     * @use HasFactory<EventFactory>
     */
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

    /**
     * @return HasMany<Market, $this>
     */
    public function markets(): HasMany
    {
        return $this->hasMany(Market::class);
    }

    /**
     * @return BelongsTo<League, $this>
     */
    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    /**
     * @return BelongsToMany<Team, $this, EventTeam, 'pivot'>
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(
            Team::class,
            'event_team',
            'event_id',
            'team_id'
        )
            ->using(EventTeam::class)
            ->withPivot('qualifier_id');
    }
}
