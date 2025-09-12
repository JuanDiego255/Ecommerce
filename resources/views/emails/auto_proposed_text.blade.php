Hola {{ $clienteNombre ?? 'Hola' }},

Te reservamos una cita tentativa con {{ $barberoNombre }}:

- Fecha: {{ $fechaHuman }}
- Hora: {{ $horaHuman }} ({{ $duracionMin }} min)
@if (!empty($serviciosResumen))
    - Servicios: {{ $serviciosResumen }}
@endif
@if (isset($totalColones))
    - Total estimado: ₡{{ number_format((int) $totalColones, 0, ',', '.') }}
@endif

Acciones:
- Aceptar: {{ $acceptUrl }}
{{-- - Cambiar hora: {{ $reschedUrl }} --}}
- Rechazar: {{ $declineUrl }}

@isset($cancelHours)
    Política: puedes cancelar hasta {{ $cancelHours }}h antes.
@endisset
@isset($reschedHours)
    Reprogramar: hasta {{ $reschedHours }}h antes.
@endisset

Gracias.
