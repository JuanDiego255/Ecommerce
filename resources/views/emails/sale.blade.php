<!-- resources/views/emails/sale.blade.php -->
<!DOCTYPE html>
<html>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>Tiquete de compra</h1>
        </div>
        <div class="receipt-body">
            @foreach ($cartItems as $item)
                <div class="receipt-line">
                    <div>{{ $item->name }}</div>
                    <div>{{ $item->code }}</div>
                </div>
                <div class="receipt-line">
                    <div>Cantidad: {{ $item->quantity }}</div>
                    <div>Precio C/U: {{ Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0 ? $item->mayor_price : $item->price }}</div>
                </div>
                @if ($item->discount != 0)
                    <div class="receipt-line">
                        <div>Descuento: {{ $item->discount }}</div>
                    </div>
                @endif
                <div class="receipt-line">
                    <div>Talla: {{ $item->size }}</div>
                </div>
                <hr>
            @endforeach
            <div class="receipt-line">
                <div>Precio (IVA + Envío):</div>
                <div class="total-price">{{ $total_price }}</div>
            </div>
        </div>
        <div class="receipt-footer">
            <p>Compra recibida</p>
        </div>
    </div>
</body>
</html>
