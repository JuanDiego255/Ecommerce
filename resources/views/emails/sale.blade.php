<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nuevo Pedido – {{ $title ?? 'Tienda' }}</title>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background:#f0f2f5; color:#1d1d1f; }
  .wrap { max-width:600px; margin:32px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.08); }
  .header { background:linear-gradient(135deg,#1d1d1f 0%,#3a3a3c 100%); padding:32px 36px; text-align:center; }
  .header h1 { color:#fff; font-size:22px; font-weight:700; letter-spacing:-.02em; margin-bottom:4px; }
  .header p  { color:rgba(255,255,255,.6); font-size:13px; }
  .badge-new { display:inline-block; background:#34c759; color:#fff; font-size:11px; font-weight:700; border-radius:99px; padding:4px 12px; margin-bottom:12px; letter-spacing:.04em; text-transform:uppercase; }
  .section { padding:24px 36px; }
  .section + .section { border-top:1px solid #f0f0f0; }
  .section-title { font-size:11px; font-weight:700; color:#86868b; text-transform:uppercase; letter-spacing:.08em; margin-bottom:14px; }
  .item-row { display:flex; align-items:flex-start; gap:14px; padding:10px 0; border-bottom:1px solid #f5f5f7; }
  .item-row:last-child { border-bottom:none; }
  .item-num  { min-width:26px; height:26px; border-radius:8px; background:#f5f5f7; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; color:#86868b; flex-shrink:0; }
  .item-body { flex:1; }
  .item-name { font-size:14px; font-weight:600; color:#1d1d1f; margin-bottom:3px; }
  .item-meta { font-size:12px; color:#86868b; }
  .item-qty  { font-size:13px; font-weight:700; color:#1d1d1f; white-space:nowrap; }
  .total-row { display:flex; justify-content:space-between; align-items:center; padding:8px 0; font-size:13px; color:#3a3a3c; }
  .total-row.grand { font-size:16px; font-weight:700; color:#1d1d1f; padding-top:12px; border-top:2px solid #1d1d1f; margin-top:6px; }
  .footer { background:#f5f5f7; padding:20px 36px; text-align:center; }
  .footer p { font-size:12px; color:#86868b; line-height:1.6; }
  @media(max-width:480px) { .section { padding:20px 20px; } .header { padding:24px 20px; } }
</style>
</head>
<body>
<div class="wrap">

  {{-- Header --}}
  <div class="header">
    <div class="badge-new">Nuevo pedido</div>
    <h1>{{ $title ?? 'Se ha realizado una venta' }}</h1>
    <p>{{ now()->format('d/m/Y H:i') }} · Venta web</p>
  </div>

  {{-- Items --}}
  <div class="section">
    <div class="section-title">Artículos</div>
    @foreach ($cartItems as $i => $item)
      @php
        $attributesValues = !empty($item->attributes_values_str)
          ? array_filter(array_map('trim', explode(',', $item->attributes_values_str)))
          : [];
        $price = (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0)
          ? $item->mayor_price : $item->price;
      @endphp
      <div class="item-row">
        <div class="item-num">{{ $i + 1 }}</div>
        <div class="item-body">
          <div class="item-name">{{ $item->name }}</div>
          <div class="item-meta">
            Código: {{ $item->code }}
            @foreach ($attributesValues as $av)
              @php $parts = explode(': ', $av, 2); @endphp
              · {{ $parts[0] ?? '' }}: {{ $parts[1] ?? '' }}
            @endforeach
          </div>
        </div>
        <div class="item-qty">
          × {{ $item->quantity }}<br>
          <span style="font-weight:400;font-size:12px;color:#86868b;">₡{{ number_format($price) }}</span>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Totals --}}
  <div class="section">
    <div class="section-title">Resumen</div>
    <div class="total-row"><span>Subtotal</span><span>₡{{ number_format($total_price) }}</span></div>
    <div class="total-row grand"><span>Total</span><span>₡{{ number_format($total_price) }}</span></div>
  </div>

  {{-- Footer --}}
  <div class="footer">
    <p>Este correo fue generado automáticamente por el sistema de ventas.<br>No responder directamente a este mensaje.</p>
  </div>

</div>
</body>
</html>
