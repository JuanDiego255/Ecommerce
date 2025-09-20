@php
    // Espera: $barberoNombre, $clienteNombre, $clienteEmail, $clientePhone,
    // $fechaHuman, $horaHuman, $duracionMin, $serviciosResumen, $totalColones,
    // $adminShowUrl (link al detalle en admin)
@endphp
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Nueva inscripción recibida</title>
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
                            <h1 style="margin:0 0 8px 0;font-size:22px;line-height:1.3;">
                                Nueva inscripción recibida
                            </h1>
                            <p style="margin:0;color:#4b5563;font-size:15px;line-height:1.6">
                                Se registró una inscripción desde el sitio con los siguientes detalles:
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
                                            <div style="margin-bottom:6px"><strong>Inscriptor:</strong>
                                                {{ $clienteNombre }}</div>
                                            @if (!empty($clienteEmail))
                                                <div style="margin-bottom:6px"><strong>Email:</strong>
                                                    {{ $clienteEmail }}</div>
                                            @endif
                                            @if (!empty($clientePhone))
                                                <div style="margin-bottom:6px"><strong>Teléfono:</strong>
                                                    {{ $clientePhone }}</div>
                                            @endif

                                            @isset($totalColones)
                                                <div style="margin-bottom:2px">
                                                    <strong>Total:</strong>
                                                    ₡{{ number_format((int) $totalColones, 0, ',', '.') }}
                                                </div>
                                            @endisset
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- botón ver detalle --}}
                    <tr>
                        <td style="padding:16px 28px 0 28px">
                            <p style="margin:0 0 16px 0;color:#6b7280;font-size:13px;line-height:1.6;text-align:center">
                                Puedes gestionar las inscripciones desde el panel.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="background:#6e00ff;height:6px"></td>
                    </tr>
                </table>
                <div style="color:#9ca3af;font-size:12px;margin-top:12px">© {{ date('Y') }} —
                    {{ $tenantinfo->title }}
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
