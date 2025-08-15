<?php

namespace App\Models\Database\Enums;

/**
 * Enum representing the status of an event.
 *
 * @property int $value
 * @property string $name
 *
 * @method static self SCHEDULED()
 * @method static self IN_PLAY()
 * @method static self FINISHED()
 * @method static self CANCELLED()
 */
enum EventStatusEnum: int
{
    case SCHEDULED = 1;
    case IN_PLAY = 2;
    case FINISHED = 3;
    case CANCELLED = 4;
}
