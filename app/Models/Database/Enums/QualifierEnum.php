<?php

namespace App\Models\Database\Enums;

/**
 * Enum representing the qualifier of a team in an event.
 *
 * @property int $value
 * @property string $name
 *
 * @method static self HOME()
 * @method static self AWAY()
 */
enum QualifierEnum: int
{
    case HOME = 1;
    case AWAY = 2;
}
