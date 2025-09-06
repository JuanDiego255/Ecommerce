<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class BarberoUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }


    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:120'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'slot_minutes' => ['required', 'integer', 'in:15,20,30,45,60'],
            'work_start' => ['required', 'date_format:H:i'],
            'work_end' => ['required', 'date_format:H:i', 'after:work_start'],
            'work_days' => ['required', 'array', 'min:1'],
            'work_days.*' => ['integer', 'between:0,6'],
            'activo' => ['sometimes', 'boolean'],
        ];
    }
}
