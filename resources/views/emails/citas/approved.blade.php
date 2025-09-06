<p>Hola {{ $nombre }},</p>
<p>Tu cita fue <strong>confirmada</strong> con {{ $barbero }}.</p>
<p><strong>Fecha y hora:</strong> {{ $fecha }}</p>
<p><strong>Servicios:</strong> {{ $servicios }}</p><br>
<p>Si cancelas la cita debes pagar un monto de: {{ $monto }} como politica de la barbería</p>


<p style="margin-top:16px">
    {{ $cancelText }}<br>
    {{ $reschText }}<br>
    <a href="{{ $cancelUrl }}">Cancelar cita</a> ·
    <a href="{{ $reschedUrl }}">Reprogramar cita</a>
</p>
