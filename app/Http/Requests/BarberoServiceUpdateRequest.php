<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class BarberoServiceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }


    public function rules(): array
    {
        return [
            'price_cents' => ['nullable', 'integer', 'min:0'],
            'duration_minutes' => ['nullable', 'integer', 'min:5', 'max:480'],
            'activo' => ['sometimes', 'boolean'],
        ];
    }
}
