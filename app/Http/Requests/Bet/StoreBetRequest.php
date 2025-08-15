<?php

namespace App\Http\Requests\Bet;

use App\Rules\Event\OnePerMarket;
use App\Rules\Event\SameEvent;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'selection_ids' => ['required', 'array', new OnePerMarket(), new SameEvent()],
            'selection_ids.*' => ['required', 'integer', 'exists:selections,id'],
            'stake' => ['required', 'numeric', 'gt:0', 'max:1000000'],
        ];
    }
}
