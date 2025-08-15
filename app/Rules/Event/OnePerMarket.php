<?php

namespace App\Rules\Event;

use App\Models\Database\Selection;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validation rule to ensure that only one selection is chosen per market.
 */
class OnePerMarket implements ValidationRule
{
    /**
     * Validate that the given selection IDs contain at most one selection per market.
     *
     * @param  string  $attribute  The name of the attribute under validation
     * @param  mixed  $value  The value of the attribute (expected to be an array of selection IDs)
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail  Callback to indicate validation failure
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_array($value) || empty($value)) {
            return;
        }

        $marketIds = Selection::whereIn('id', $value)
            ->pluck('market_id')
            ->toArray();

        if (count($marketIds) > count(array_unique($marketIds))) {
            $fail('You can only choose one selection per market.');
        }
    }
}
