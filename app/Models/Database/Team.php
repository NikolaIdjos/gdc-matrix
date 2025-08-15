<?php

namespace App\Models\Database;

use App\Models\Database\Pivot\EventTeam;
use Database\Factories\Database\TeamFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property-read Collection|Event[] $events
 */
class Team extends Model
{
    /**
     * @use HasFactory<TeamFactory>
     */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * @return BelongsToMany<Event,$this,EventTeam,'pivot'>
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(
            Event::class,
            'event_team',
            'team_id',
            'event_id'
        )
            ->using(EventTeam::class)
            ->withPivot('qualifier_id')
            ->orderBy('pivot_qualifier_id');
    }
}
