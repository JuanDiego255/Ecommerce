@php
    function colones($cents)
    {
        return number_format((int) ($cents / 100), 0, ',', '.');
    }

    $weekLabel = $payroll->week_start->format('d/m/Y') . ' — ' . $payroll->week_end->format('d/m/Y');
    $statusMap = ['open' => 'Abierta', 'closed' => 'Cerrada', 'paid' => 'Pagada'];
    $statusText = $statusMap[$payroll->status] ?? ucfirst($payroll->status);
@endphp
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Nómina {{ $weekLabel }}</title>
    <style>
        @page {
            margin: 28mm 16mm 22mm 16mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
        }

        .header {
            position: fixed;
            top: -20mm;
            left: 0;
            right: 0;
            height: 18mm;
        }

        .footer {
            position: fixed;
            bottom: -16mm;
            left: 0;
            right: 0;
            font-size: 10px;
            color: #6b7280;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .w-50 {
            width: calc(50% - 6px);
        }

        .w-33 {
            width: calc(33.333% - 8px);
        }

        .w-25 {
            width: calc(25% - 9px);
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
        }

        .title {
            font-size: 18px;
            font-weight: 700;
        }

        .muted {
            color: #6b7280;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 11px;
        }

        .badge-open {
            background: #cffafe;
            color: #0c4a6e;
        }

        .badge-closed {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
        }

        .surface {
            border: 1px dashed #e5e7eb;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
        }

        .surface-sub {
            font-size: 11px;
            color: #6b7280;
        }

        .surface-num {
            font-size: 16px;
            font-weight: 700;
            margin-top: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            padding: 8px 6px;
            border-bottom: 1px solid #eceff1;
        }

        th {
            font-weight: 600;
            font-size: 12px;
            background: #f8fafc;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .mb-6 {
            margin-bottom: 18px;
        }

        .mb-3 {
            margin-bottom: 10px;
        }

        .logo {
            height: 28px;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">
        <table style="width:100%;">
            <tr>
                <td>
                    @if (isset($tenantinfo->logo_ico))
                        <img src="{{ route('file', $tenantinfo->logo_ico) }}" class="logo" alt="logo">
                    @else
                        <strong>{{ $tenantinfo->title }}</strong>
                    @endif
                </td>
                <td class="text-right">
                    <div class="muted">Nómina</div>
                    <div><strong>{{ $weekLabel }}</strong></div>
                </td>
            </tr>
        </table>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        Generado el {{ $now->format('d/m/Y H:i') }} — {{ $tenant->name ?? config('app.name') }}
    </div>

    <main>
        {{-- Título y estado --}}
        <div class="mb-6">
            <div class="title">Nómina semanal</div>
            <div class="muted">Semana: {{ $weekLabel }}</div>
            <div style="margin-top:6px;">
                @php $cls = $payroll->status === 'open' ? 'badge-open' : ($payroll->status==='closed' ? 'badge-closed' : 'badge-paid'); @endphp
                <span class="badge {{ $cls }}">{{ $statusText }}</span>
            </div>
        </div>

        {{-- Totales --}}
        <div class="card mb-6">
            <div class="row">
                <div class="surface w-25">
                    <div class="surface-sub">Servicios</div>
                    <div class="surface-num">{{ number_format($totals['services']) }}</div>
                </div>
                <div class="surface w-25">
                    <div class="surface-sub">Bruto</div>
                    <div class="surface-num">₡{{ colones($totals['gross']) }}</div>
                </div>
                <div class="surface w-25">
                    <div class="surface-sub">Barberos</div>
                    <div class="surface-num">₡{{ colones($totals['barber']) }}</div>
                </div>
                <div class="surface w-25">
                    <div class="surface-sub">Ajustes</div>
                    <div class="surface-num">₡{{ colones($totals['adjust']) }}</div>
                </div>
                <div class="surface w-25">
                    <div class="surface-sub">Final barbero</div>
                    <div class="surface-num">₡{{ colones($totals['final_barber']) }}</div>
                </div>
                <div class="surface w-25">
                    <div class="surface-sub">Propietario</div>
                    <div class="surface-num">₡{{ colones($totals['owner']) }}</div>
                </div>
            </div>
        </div>

        {{-- Tabla por barbero --}}
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Barbero</th>
                        <th class="text-center">Servicios</th>
                        <th class="text-right">Bruto</th>
                        <th class="text-center">% Comisión</th>
                        <th class="text-right">Barbero</th>
                        <th class="text-right">Ajuste</th>
                        <th class="text-right">Final barbero</th>
                        <th class="text-right">Propietario</th>
                        <th class="text-center">Pagado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $it)
                        @php
                            $grossCol = colones($it->gross_cents);
                            $barbCol = colones($it->barber_commission_cents);
                            $adjCol = colones($it->adjustment_cents);
                            $finalBarbCol = colones($it->barber_commission_cents + $it->adjustment_cents);
                            $ownerCol = colones($it->owner_commission_cents);
                        @endphp
                        <tr>
                            <td>{{ $it->barbero->nombre ?? '#' . $it->barbero_id }}</td>
                            <td class="text-center">{{ $it->services_count }}</td>
                            <td class="text-right">₡{{ $grossCol }}</td>
                            <td class="text-center">{{ number_format($it->commission_rate, 2) }}%</td>
                            <td class="text-right">₡{{ $barbCol }}</td>
                            <td class="text-right">₡{{ $adjCol }}</td>
                            <td class="text-right"><strong>₡{{ $finalBarbCol }}</strong></td>
                            <td class="text-right">₡{{ $ownerCol }}</td>
                            <td class="text-center">{{ $it->paid_at ? 'Sí' : 'No' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>
