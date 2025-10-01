Hola {{ $clienteNombre ?? 'Hola' }},

Te reservamos una cita con {{ $barberoNombre }}:

- Fecha: {{ $fechaHuman }}
- Hora: {{ $horaHuman }} ({{ $duracionMin }} min)
@if (!empty($serviciosResumen))
    <div style="margin-bottom:6px"><strong>Servicios:</strong>
        <ul>
            @foreach ($serviciosResumen as $s)
                <li>{{ $s['nombre'] }} —
                    ₡{{ number_format($s['precio'], 0, ',', '.') }}
                    ({{ $s['duracion'] }} min)
                </li>
            @endforeach
        </ul>
    </div>
@endif
@if (isset($totalColones))
    - Total estimado: ₡{{ number_format((int) $totalColones, 0, ',', '.') }}
@endif

Acciones:
{{-- - Aceptar: {{ $acceptUrl }} --}}
{{-- - Cambiar hora: {{ $reschedUrl }} --}}
- Rechazar: {{ $declineUrl }}

@isset($cancelHours)
    Política: puedes cancelar hasta {{ $cancelHours }}h antes.
@endisset
@isset($reschedHours)
    Reprogramar: hasta {{ $reschedHours }}h antes.
@endisset

Gracias.
