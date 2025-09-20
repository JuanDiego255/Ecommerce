<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // público
    }

    public function rules(): array
    {
        $eventId = $this->route('event'); // viene de {event}
        // Puede ser modelo o id; manejamos ambos
        $eventId = is_object($eventId) ? $eventId->id : $eventId;

        return [
            'nombre'    => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:120'],
            'telefono'  => [
                'required',
                'string',
                'max:25',
                // Regex CR típico: 8 dígitos, permite +506 y espacios/guiones
                'regex:/^(?:\+?506)?[\s-]?\d{4}[\s-]?\d{4}$/'
            ],
            'equipo'    => ['nullable', 'string', 'max:150'],
            'email'     => ['required', 'email:rfc,dns', 'max:150'],
            'comprobante_pago' => [
                'required',
                'file',
                // Acepta imagen o PDF
                'mimetypes:image/jpeg,image/png,application/pdf',
                'max:2048', // 2MB
            ],
            'terminos' => ['accepted'],
            // Si deseas reCAPTCHA v2/v3 (tienes anhskohbo/no-captcha)
            // 'g-recaptcha-response' => ['required', 'captcha'],
        ];
    }

    public function messages(): array
    {
        return [
            //'category_id.exists' => 'La categoría seleccionada no pertenece a este evento.',
            'terminos.accepted'  => 'Debes aceptar los términos y condiciones.',
            'comprobante.mimetypes' => 'El comprobante debe ser JPG, PNG o PDF.',
        ];
    }
}
