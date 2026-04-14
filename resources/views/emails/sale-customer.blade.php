<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Confirmación de tu pedido – {{ $store_name ?? 'Tienda' }}</title>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background:#f0f2f5; color:#1d1d1f; }
  .wrap { max-width:600px; margin:32px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.08); }

  /* Hero */
  .hero { background:linear-gradient(135deg,#007aff 0%,#0051c7 100%); padding:40px 36px 32px; text-align:center; }
  .hero-icon { width:64px; height:64px; border-radius:20px; background:rgba(255,255,255,.2); margin:0 auto 16px; display:flex; align-items:center; justify-content:center; font-size:32px; }
  .hero h1  { color:#fff; font-size:24px; font-weight:700; letter-spacing:-.02em; margin-bottom:6px; }
  .hero p   { color:rgba(255,255,255,.75); font-size:14px; line-height:1.5; }

  /* Info banner */
  .info-banner { background:#f0f7ff; border-left:4px solid #007aff; margin:24px 36px 0; border-radius:0 10px 10px 0; padding:14px 18px; font-size:13px; color:#0051c7; line-height:1.5; }

  /* Section */
  .section { padding:24px 36px; }
  .section + .section { border-top:1px solid #f0f0f0; }
  .section-title { font-size:11px; font-weight:700; color:#86868b; text-transform:uppercase; letter-spacing:.08em; margin-bottom:14px; }

  /* Item card */
  .item-card { border:1px solid #f0f0f0; border-radius:12px; padding:14px; margin-bottom:10px; display:flex; gap:14px; align-items:flex-start; }
  .item-card:last-child { margin-bottom:0; }
  .item-badge { min-width:32px; height:32px; border-radius:8px; background:#f5f5f7; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; color:#007aff; flex-shrink:0; }
  .item-body  { flex:1; }
  .item-name  { font-size:14px; font-weight:600; color:#1d1d1f; margin-bottom:4px; }
  .item-attrs { display:flex; flex-wrap:wrap; gap:4px; margin-bottom:4px; }
  .attr-chip  { font-size:11px; font-weight:600; background:#f5f5f7; color:#86868b; border-radius:99px; padding:2px 8px; }
  .item-price { font-size:12px; color:#86868b; }
  .item-qty   { text-align:right; flex-shrink:0; }
  .qty-num    { font-size:20px; font-weight:700; color:#1d1d1f; }
  .qty-label  { font-size:11px; color:#86868b; text-transform:uppercase; letter-spacing:.04em; }

  /* Totals */
  .total-row { display:flex; justify-content:space-between; align-items:center; padding:7px 0; font-size:13px; color:#3a3a3c; }
  .total-row.grand { font-size:17px; font-weight:700; color:#1d1d1f; padding-top:12px; border-top:2px solid #1d1d1f; margin-top:6px; }

  /* CTA */
  .cta { text-align:center; padding:28px 36px; }
  .cta p { font-size:13px; color:#86868b; margin-bottom:16px; }

  /* Footer */
  .footer { background:#f5f5f7; padding:20px 36px; text-align:center; }
  .footer p { font-size:12px; color:#86868b; line-height:1.6; }
  .footer strong { color:#3a3a3c; }

  @media(max-width:480px) {
    .section, .cta, .footer { padding:20px; }
    .hero { padding:30px 20px; }
    .info-banner { margin:20px 20px 0; }
  }
</style>
</head>
<body>
<div class="wrap">

  {{-- Hero --}}
  <div class="hero">
    <div class="hero-icon">🛍️</div>
    <h1>¡Gracias por tu pedido, {{ $customer_name ?? 'Cliente' }}!</h1>
    <p>Hemos recibido tu compra correctamente.<br>En breve nos comunicaremos para coordinar la entrega.</p>
  </div>

  {{-- Info banner --}}
  <div class="info-banner">
    📦 Tu pedido ha sido registrado el <strong>{{ now()->translatedFormat('d \d\e F \d\e Y') }}</strong> a las <strong>{{ now()->format('H:i') }}</strong>.
  </div>

  {{-- Items --}}
  <div class="section">
    <div class="section-title">Tu pedido incluye</div>
    @foreach ($cartItems as $i => $item)
      @php
        $attributesValues = !empty($item->attributes_values_str)
          ? array_filter(array_map('trim', explode(',', $item->attributes_values_str)))
          : [];
        $price = (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0)
          ? $item->mayor_price : $item->price;
      @endphp
      <div class="item-card">
        <div class="item-badge">{{ $i + 1 }}</div>
        <div class="item-body">
          <div class="item-name">{{ $item->name }}</div>
          @if (!empty($attributesValues))
            <div class="item-attrs">
              @foreach ($attributesValues as $av)
                @php $parts = explode(': ', $av, 2); @endphp
                <span class="attr-chip">{{ $parts[0] ?? '' }}: {{ $parts[1] ?? '' }}</span>
              @endforeach
            </div>
          @endif
          <div class="item-price">₡{{ number_format($price) }} c/u</div>
        </div>
        <div class="item-qty">
          <div class="qty-num">{{ $item->quantity }}</div>
          <div class="qty-label">unid.</div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Total --}}
  <div class="section">
    <div class="section-title">Resumen de pago</div>
    <div class="total-row"><span>Subtotal</span><span>₡{{ number_format($total_price) }}</span></div>
    @if (isset($delivery) && $delivery > 0)
      <div class="total-row"><span>Envío</span><span>₡{{ number_format($delivery) }}</span></div>
    @endif
    <div class="total-row grand">
      <span>Total</span>
      <span>₡{{ number_format($total_price + ($delivery ?? 0)) }}</span>
    </div>
  </div>

  {{-- CTA --}}
  <div class="cta">
    <p>Si tienes alguna duda sobre tu pedido, no dudes en contactarnos.</p>
  </div>

  {{-- Footer --}}
  <div class="footer">
    <p><strong>{{ $store_name ?? 'Tienda' }}</strong><br>
    Este correo fue enviado automáticamente como confirmación de tu compra.<br>
    Por favor no respondas directamente a este mensaje.</p>
  </div>

</div>
</body>
</html>
