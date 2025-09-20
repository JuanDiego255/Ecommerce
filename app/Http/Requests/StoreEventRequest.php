<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; //$this->user()?->can('create', \App\Models\Event::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'nombre'            => ['required', 'string', 'max:180'],
            'fecha_inscripcion' => ['required', 'date'],
            'costo_crc'         => ['required', 'integer', 'min:0'],
            'ubicacion'         => ['required', 'string', 'max:255'],
            'detalles'          => ['required', 'string'],
            'cuenta_sinpe'      => ['required', 'string'],           
            'cuenta_iban'      => ['required', 'string'],           
            'imagen_premios'    => ['nullable', 'file', 'mimetypes:image/jpeg,image/png', 'max:2048'],
            'activo'            => ['boolean'],
        ];
    }
}
