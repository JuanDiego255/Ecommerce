<p>Hola {{ $cita->cliente_nombre }},</p>
<p>Te recordamos tu cita con {{ $cita->barbero->nombre }} mañana a las
    <strong>{{ $cita->starts_at->timezone(config('app.timezone'))->format('H:i') }}</strong>.
</p>
<p><strong>Servicios:</strong> {{ $cita->resumen_servicios }}</p>
