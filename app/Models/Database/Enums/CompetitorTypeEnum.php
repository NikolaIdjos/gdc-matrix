<?php

namespace App\Models\Database\Enums;

/**
 * Enum representing types of competitors.
 *
 * @property int $value
 * @property string $name
 *
 * @method static self TEAM()
 * @method static self PLAYER()
 */
enum CompetitorTypeEnum: int
{
    case TEAM = 1;
    case PLAYER = 2;
}
