<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class StoreCitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // públicas desde landing
    }


    public function rules(): array
    {
        return [
            'especialista_id' => ['required', 'integer', 'exists:especialistas,id'],
            'servicios' => ['required', 'array', 'min:1'],
            'servicios.*' => ['integer', 'exists:servicios,id'],
            'date' => ['required', 'date_format:Y-m-d'],
            'time' => ['required', 'date_format:H:i'],
            'cliente_nombre' => ['nullable', 'string', 'max:120'],
            'cliente_email' => ['nullable', 'email', 'max:120'],
            'cliente_telefono' => ['nullable', 'string', 'max:50'],
            'notas' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
