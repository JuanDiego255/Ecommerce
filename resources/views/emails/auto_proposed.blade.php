@php
    // Variables esperadas:
    // $clienteNombre, $barberoNombre, $fechaHuman, $horaHuman, $duracionMin,
    // $serviciosResumen, $totalColones,
    // $acceptUrl, $reschedUrl, $declineUrl,
    // (opcionales) $cancelHours, $reschedHours
@endphp
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Propuesta de cita</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
</head>

<body
    style="margin:0;padding:0;background:#f6f7fb;font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,'Helvetica Neue',Arial,sans-serif;color:#111">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f6f7fb;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="620" cellpadding="0" cellspacing="0"
                    style="background:#fff;border-radius:12px;box-shadow:0 6px 24px rgba(0,0,0,.06);overflow:hidden">
                    <tr>
                        <td style="background:#6e00ff;height:6px"></td>
                    </tr>
                    <tr>
                        <td style="padding:28px 28px 8px 28px">
                            <h1 style="margin:0 0 8px 0;font-size:22px;line-height:1.3;">Hola
                                {{ $clienteNombre ?? '¡Hola!' }} 👋</h1>
                            <p style="margin:0;color:#4b5563;font-size:15px;line-height:1.6">
                                Te reservamos <strong>una cita tentativa</strong> con
                                <strong>{{ $barberoNombre }}</strong>.
                                Confírmala o cámbiala cuando gustes:
                            </p>
                        </td>
                    </tr>

                    {{-- tarjeta de detalles --}}
                    <tr>
                        <td style="padding:12px 28px 4px 28px">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="border:1px solid #e5e7eb;border-radius:12px">
                                <tr>
                                    <td style="padding:18px 20px">
                                        <div style="font-size:15px;line-height:1.6;color:#111">
                                            <div style="margin-bottom:8px">
                                                <strong>Fecha:</strong> {{ $fechaHuman }} · <strong>Hora:</strong>
                                                {{ $horaHuman }} ({{ $duracionMin }} min)
                                            </div>
                                            @if (!empty($serviciosResumen))
                                                <div style="margin-bottom:8px">
                                                    <strong>Servicios:</strong> {{ $serviciosResumen }}
                                                </div>
                                            @endif
                                            @if (isset($totalColones))
                                                <div style="margin-bottom:4px">
                                                    <strong>Total estimado:</strong>
                                                    ₡{{ number_format((int) $totalColones, 0, ',', '.') }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- botones --}}
                    <tr>
                        <td style="padding:16px 28px 0 28px">
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding:8px 0 16px 0">
                                        <a href="{{ $acceptUrl }}" target="_blank"
                                            style="display:inline-block;background:#6e00ff;color:#fff;text-decoration:none;
                              padding:12px 18px;border-radius:10px;font-weight:600">
                                            ✅ Aceptar cita
                                        </a>
                                        <span style="display:inline-block;width:8px"></span>
                                        {{--  <a href="{{ $reschedUrl }}" target="_blank"
                                            style="display:inline-block;background:#0ea5e9;color:#fff;text-decoration:none;
                              padding:12px 18px;border-radius:10px;font-weight:600">
                                            🔁 Cambiar hora
                                        </a> --}}
                                        <span style="display:inline-block;width:8px"></span>
                                        <a href="{{ $declineUrl }}" target="_blank"
                                            style="display:inline-block;background:#ef4444;color:#fff;text-decoration:none;
                              padding:12px 18px;border-radius:10px;font-weight:600">
                                            ❌ Rechazar
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:0 0 16px 0;color:#6b7280;font-size:13px;line-height:1.6">
                                Estos enlaces son personales y seguros.
                            </p>
                        </td>
                    </tr>

                    {{-- políticas --}}
                    <tr>
                        <td style="padding:0 28px 6px 28px">
                            <div
                                style="background:#f8fafc;border:1px dashed #e5e7eb;border-radius:10px;padding:12px 14px;color:#4b5563;font-size:13px;line-height:1.6">
                                @if (isset($cancelHours) || isset($reschedHours))
                                    Puedes cancelar hasta {{ $cancelHours ?? 'X' }}h antes y reprogramar hasta
                                    {{ $reschedHours ?? 'Y' }}h antes.
                                @else
                                    Recuerda nuestras políticas de cancelación y reprogramación.
                                @endif
                            </div>
                        </td>
                    </tr>

                    {{-- fallback enlaces --}}
                    <tr>
                        <td style="padding:10px 28px 24px 28px">
                            <p style="margin:0;color:#9ca3af;font-size:12px;line-height:1.6">
                                Si los botones no funcionan, copia y pega en tu navegador:
                                <br> Aceptar: <a href="{{ $acceptUrl }}"
                                    style="color:#6e00ff">{{ $acceptUrl }}</a>
                                {{--   <br> Cambiar: <a href="{{ $reschedUrl }}"
                                    style="color:#6e00ff">{{ $reschedUrl }}</a> --}}
                                <br> Rechazar: <a href="{{ $declineUrl }}"
                                    style="color:#6e00ff">{{ $declineUrl }}</a>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="background:#6e00ff;height:6px"></td>
                    </tr>
                </table>
                <div style="color:#9ca3af;font-size:12px;margin-top:12px">
                    © {{ date('Y') }} — Agenda Barbería
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
