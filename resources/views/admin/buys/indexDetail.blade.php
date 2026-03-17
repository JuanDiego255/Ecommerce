@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
@php
    $firstBuy = $buysDetails->first();
    $hasAddress = $firstBuy && ($firstBuy->address != '' || $firstBuy->address_b != '');

    /* Status pill class */
    $stMap = [
        'Pendiente'  => 'pill-orange',
        'Aprobado'   => 'pill-blue',
        'Completado' => 'pill-green',
        'Cancelado'  => 'pill-red',
    ];
    $stClass = $stMap[$currentBuy->estado ?? ''] ?? 'pill-gray';
@endphp
<input type="hidden" id="buy_id" value="{{ $id }}">

{{-- ── Order header strip ─────────────────────────────────── --}}
<div class="order-header-strip">
    <div>
        <p class="order-id">Pedido #{{ $currentBuy->id }}</p>
        <span class="order-meta">{{ optional($currentBuy->created_at)->format('d/m/Y H:i') }}</span>
    </div>

    {{-- Status pill --}}
    @if(isset($currentBuy->estado))
        <span class="s-pill {{ $stClass }}">{{ $currentBuy->estado }}</span>
    @endif

    {{-- Origin pill --}}
    @if(isset($currentBuy->origen))
        <span class="s-pill pill-gray" style="font-size:.68rem;">{{ $currentBuy->origen }}</span>
    @endif

    <div class="oh-actions">
        {{-- Search other orders --}}
        <div style="width:220px;">
            <select id="search-select" class="form-control" placeholder="Buscar pedido..." name="search"></select>
        </div>

        {{-- Prev / Next --}}
        @if($previousBuy)
            <a href="{{ url('buy/details/admin/' . $previousBuy->id) }}"
               class="act-btn ab-neutral" title="Pedido anterior">
                <span class="material-icons" style="font-size:1rem;">arrow_back</span>
            </a>
        @endif
        @if($nextBuy)
            <a href="{{ url('buy/details/admin/' . $nextBuy->id) }}"
               class="act-btn ab-neutral" title="Siguiente pedido">
                <span class="material-icons" style="font-size:1rem;">arrow_forward</span>
            </a>
        @endif
        <a href="{{ url('buys-admin') }}" class="act-btn ab-neutral" title="Volver a pedidos">
            <span class="material-icons" style="font-size:1rem;">list</span>
        </a>
    </div>
</div>

{{-- ── Two-column layout ──────────────────────────────────── --}}
<div class="detail-layout">

    {{-- LEFT — Items table --}}
    <div>
        <div class="s-card">
            <div class="s-card-header">
                <div class="card-h-icon"><span class="material-icons">shopping_bag</span></div>
                <span class="card-h-title">Artículos del pedido</span>
                <div class="card-h-actions">
                    <span class="s-pill pill-gray" style="font-size:.68rem;">{{ $buysDetails->count() }} ítem(s)</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="orders-table" id="buysDetails">
                    <thead>
                        <tr>
                            <th>Acciones</th>
                            <th>Artículo</th>
                            <th>Atributos</th>
                            <th style="text-align:center;">Estado</th>
                            <th style="text-align:center;">Cant.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($buysDetails as $buy)
                            @php
                                $attributesValues = !empty($buy->attributes_values)
                                    ? explode(', ', $buy->attributes_values)
                                    : [];
                                $itemStatus = match($buy->cancel_item) {
                                    0 => ['label' => 'Vigente',    'cls' => 'pill-green'],
                                    1 => ['label' => 'En proceso', 'cls' => 'pill-orange'],
                                    default => ['label' => 'Cancelado', 'cls' => 'pill-red'],
                                };
                            @endphp
                            <tr>
                                {{-- Actions --}}
                                <td>
                                    <div class="act-group">
                                        @if ($buy->cancel_item == 1)
                                            <form style="display:contents"
                                                action="{{ url('cancel/buy-item/' . $buy->item_id . '/' . $buy->cancel_item) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="action" value="1">
                                                <input type="hidden" name="buy" value="{{ $buy->buy }}">
                                                <button type="submit" class="act-btn ab-ok" title="Aprobar cancelación">
                                                    <span class="material-icons">check</span>
                                                </button>
                                            </form>
                                            <form style="display:contents"
                                                action="{{ url('cancel/buy-item/' . $buy->item_id . '/' . $buy->cancel_item) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="action" value="0">
                                                <input type="hidden" name="buy" value="{{ $buy->buy }}">
                                                <button type="submit" class="act-btn ab-del" title="Rechazar cancelación">
                                                    <span class="material-icons">cancel</span>
                                                </button>
                                            </form>
                                        @else
                                            <span style="color:var(--gray2);font-size:.75rem;">—</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Article --}}
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <a target="_blank" data-fancybox="gallery" href="{{ route('file', $buy->image) }}">
                                            <img src="{{ route('file', $buy->image) }}" class="thumb-img">
                                        </a>
                                        <div>
                                            <p class="cell-main">{{ $buy->name }}</p>
                                            <p class="cell-sub">
                                                ₡{{ number_format($buy->total) }}
                                                @if($iva > 0)
                                                    &nbsp;·&nbsp;IVA ₡{{ number_format($buy->iva) }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Attributes --}}
                                <td>
                                    <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                        @foreach ($attributesValues as $attributeValue)
                                            @php
                                                $parts = explode(': ', $attributeValue, 2);
                                                $attr  = $parts[0] ?? '';
                                                $val   = $parts[1] ?? '';
                                            @endphp
                                            @if ($attr !== '')
                                                <span class="s-pill pill-gray" style="font-size:.68rem;">{{ $attr }}: {{ $val }}</span>
                                            @endif
                                        @endforeach
                                        @if(empty($attributesValues) || $attributesValues === [''])
                                            <span style="color:var(--gray2);font-size:.78rem;">—</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td style="text-align:center;">
                                    <span class="s-pill {{ $itemStatus['cls'] }}">{{ $itemStatus['label'] }}</span>
                                </td>

                                {{-- Qty --}}
                                <td style="text-align:center;">
                                    <span class="cell-mono">{{ $buy->quantity }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Apartado payment (wide card, shown only when applicable) --}}
        @if($currentBuy->apartado == 1 &&
            $currentBuy->total_buy + $currentBuy->total_delivery - $currentBuy->monto_apartado > 0)
        <div class="s-card">
            <div class="s-card-header">
                <div class="card-h-icon"><span class="material-icons">payments</span></div>
                <span class="card-h-title">Cancelar apartado</span>
            </div>
            <div class="s-card-body">
                <div class="apartado-box" style="margin-bottom:14px;">
                    <p class="ap-pending">Monto pendiente</p>
                    <p class="ap-amount">₡{{ number_format($currentBuy->total_buy + $currentBuy->total_delivery - $currentBuy->monto_apartado) }}</p>
                </div>
                <form action="{{ url('payment/apartado/' . $currentBuy->id) }}" method="POST">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
                        <div>
                            <label class="filter-label">Monto a abonar</label>
                            <input type="number" min="1000"
                                max="{{ $currentBuy->total_buy + $currentBuy->total_delivery - $currentBuy->monto_apartado }}"
                                value="{{ $currentBuy->total_buy + $currentBuy->total_delivery - $currentBuy->monto_apartado }}"
                                required class="filter-input" name="monto_apartado">
                        </div>
                        <div style="display:flex;align-items:flex-end;">
                            <button type="submit" class="btn btn-primary w-100">
                                <span class="material-icons">check_circle</span> Registrar pago
                            </button>
                        </div>
                    </div>
                </form>
                <a href="{{ url('/new-buy/' . $currentBuy->id) }}" class="btn btn-secondary w-100">
                    <span class="material-icons">edit</span> Editar pedido
                </a>
            </div>
        </div>
        @endif
    </div>

    {{-- RIGHT — Controls + Shipping --}}
    <div class="detail-panel-right">

        {{-- Guide number --}}
        <div class="s-card">
            <div class="s-card-header">
                <div class="card-h-icon"><span class="material-icons">local_shipping</span></div>
                <span class="card-h-title">Número de guía</span>
            </div>
            <div class="s-card-body">
                <form action="{{ url('save/guide-number/' . $currentBuy->id) }}" method="POST">
                    @csrf
                    <label class="filter-label">Guía de envío</label>
                    <div style="display:flex;gap:8px;">
                        <input value="{{ $currentBuy->guide_number }}" type="text"
                            class="filter-input" name="guide_number" id="guide_number"
                            placeholder="Ej. CR123456789" style="flex:1;">
                        <button type="submit" class="btn btn-primary" style="padding:9px 14px;">
                            <span class="material-icons">save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Notes --}}
        <div class="s-card">
            <div class="s-card-header">
                <div class="card-h-icon"><span class="material-icons">notes</span></div>
                <span class="card-h-title">Notas del pedido</span>
            </div>
            <div class="s-card-body">
                <form action="{{ url('save/detail/' . $currentBuy->id) }}" method="POST">
                    @csrf
                    <textarea rows="3" class="filter-input" name="detail" id="detail"
                        placeholder="Agregar nota interna..."
                        style="width:100%;resize:vertical;">{{ $currentBuy->detail }}</textarea>
                    <button type="submit" class="btn btn-primary w-100 mt-2">
                        <span class="material-icons">save</span> Guardar nota
                    </button>
                </form>
            </div>
        </div>

        {{-- Order totals --}}
        <div class="s-card">
            <div class="s-card-header">
                <div class="card-h-icon"><span class="material-icons">receipt_long</span></div>
                <span class="card-h-title">Resumen del pedido</span>
            </div>
            <div class="s-card-body" style="padding:14px 20px;">
                <div class="info-row">
                    <span class="ir-label">Subtotal</span>
                    <span class="ir-value cell-mono">₡{{ number_format($currentBuy->total_buy ?? 0) }}</span>
                </div>
                @if(isset($currentBuy->total_delivery) && $currentBuy->total_delivery > 0)
                <div class="info-row">
                    <span class="ir-label">Envío</span>
                    <span class="ir-value cell-mono">₡{{ number_format($currentBuy->total_delivery) }}</span>
                </div>
                @endif
                @if(isset($currentBuy->monto_apartado) && $currentBuy->monto_apartado > 0)
                <div class="info-row">
                    <span class="ir-label">Abonado</span>
                    <span class="ir-value cell-mono" style="color:var(--green);">₡{{ number_format($currentBuy->monto_apartado) }}</span>
                </div>
                @if($currentBuy->total_buy + $currentBuy->total_delivery - $currentBuy->monto_apartado > 0)
                <div class="info-row">
                    <span class="ir-label">Pendiente</span>
                    <span class="ir-value cell-mono" style="color:var(--orange);">₡{{ number_format($currentBuy->total_buy + $currentBuy->total_delivery - $currentBuy->monto_apartado) }}</span>
                </div>
                @endif
                @endif
                <div class="info-row" style="border-top:2px solid var(--gray1);margin-top:4px;padding-top:10px;">
                    <span class="ir-label" style="font-size:.8rem;color:var(--black);">Total</span>
                    <span class="ir-value" style="font-size:1.1rem;font-weight:800;color:var(--black);letter-spacing:-.02em;">
                        ₡{{ number_format(($currentBuy->total_buy ?? 0) + ($currentBuy->total_delivery ?? 0)) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Shipping details --}}
        @if($hasAddress)
            @php $item = $buysDetails->first(); @endphp
            <div class="s-card">
                <div class="s-card-header">
                    <div class="card-h-icon"><span class="material-icons">place</span></div>
                    <span class="card-h-title">Datos de envío</span>
                </div>
                <div class="s-card-body" style="padding:14px 20px;">
                    @php
                        $fields = [
                            'Nombre'    => $item->person_name ?? $item->person_name_b ?? null,
                            'E-mail'    => $item->email ?? $item->email_b ?? null,
                            'Teléfono'  => $item->telephone ?? $item->telephone_b ?? null,
                            'País'      => $item->country ?? $item->country_b ?? null,
                            'Provincia' => $item->province ?? $item->province_b ?? null,
                            'Cantón'    => $item->city ?? $item->city_b ?? null,
                            'Distrito'  => $item->address_two ?? $item->address_two_b ?? null,
                            'Dirección' => $item->address ?? $item->address_b ?? null,
                            'Cod. Postal'=> $item->postal_code ?? $item->postal_code_b ?? null,
                        ];
                    @endphp
                    @foreach($fields as $label => $value)
                        @if($value)
                        <div class="info-row">
                            <span class="ir-label">{{ $label }}</span>
                            <span class="ir-value">{{ $value }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>

@endsection
@section('script')
<script src="{{ asset('js/datatables.js') }}"></script>
<script>
$(document).ready(function () {
    // Search-select (navigate to other orders)
    $('#search-select').select2({
        placeholder: "Buscar pedido...",
        allowClear: true,
        width: '100%',
        ajax: {
            url: '/get/buys/select/{{ $id }}',
            dataType: 'json',
            delay: 250,
            data: params => ({ search: params.term }),
            processResults: data => ({
                results: data.map(b => ({ id: b.id, text: b.display_name }))
            })
        }
    });
    $('#search-select').on('change', function () {
        var id = $(this).val();
        if (id) window.location.href = '/buy/details/admin/' + id;
    });
});
</script>
@endsection
