<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id'   => ['required', 'integer'],
            'nombre'   => ['required', 'string', 'max:120'],
            'edad_min' => ['nullable', 'integer', 'min:0', 'lte:edad_max'],
            'edad_max' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
