<?php

namespace App\Rules\Event;

use App\Models\Database\Selection;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validation rule to ensure that all selected IDs belong to the same event.
 */
class SameEvent implements ValidationRule
{
    /**
     * Validate that all selection IDs belong to a single event.
     *
     * @param string $attribute The name of the attribute under validation
     * @param mixed $value The value of the attribute (expected to be an array of selection IDs)
     * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail Callback to indicate validation failure
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_array($value) || empty($value)) {
            return;
        }

        $selections = Selection::with('market:id,event_id')
            ->whereIn('id', $value)
            ->get();

        $eventIds = $selections->pluck('market.event_id')->unique();

        if (count($eventIds) > 1) {
            $fail('All selections must belong to the same event.');
        }
    }
}
