<?php

namespace App\Http\Requests\Event;

use App\Models\Database\Enums\EventStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexEventRequest extends FormRequest
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
            'search' => ['nullable', 'string', 'min:1', 'max:255'],
            'status_id' => ['nullable', 'integer', Rule::in(array_column(EventStatusEnum::cases(), 'value'))],
            'starts_after' => ['nullable', 'date', 'date_format:Y-m-d'],
        ];
    }
}
