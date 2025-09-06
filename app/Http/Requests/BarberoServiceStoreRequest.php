<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class BarberoServiceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }


    public function rules(): array
    {
        return [
            'servicio_id' => ['required', 'integer', 'exists:servicios,id'],
            'price_cents' => ['nullable', 'integer', 'min:0'],
            'duration_minutes' => ['nullable', 'integer', 'min:5', 'max:480'],
            'activo' => ['sometimes', 'boolean'],
        ];
    }
}
