<!-- resources/views/emails/sale.blade.php -->
<!DOCTYPE html>
<html>

<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>Tiquete de compra</h1>
        </div>
        <div class="receipt-body">
            <table class="receipt-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Código</th>
                        <th>Cantidad</th>
                        <th>Precio C/U</th>
                        <th>Descuento</th>
                        <th>Atributos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $item)
                        @php                            
                            $attributesValues = explode(', ', $item->attributes_values_str);
                        @endphp
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0 ? $item->mayor_price : $item->price }}
                            </td>
                            <td>{{ $item->discount != 0 ? $item->discount : '-' }}</td>
                            <td>
                                <span class="ps-3 textmuted">
                                    @foreach ($attributesValues as $attributeValue)
                                        @php
                                            // Separa el atributo del valor por ": "
                                            [$attribute, $value] = explode(': ', $attributeValue);
                                        @endphp

                                        {{ $attribute }}: {{ $value }}
                                    @endforeach
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="total-price-row">
                        <td colspan="5">Precio (IVA + Envío):</td>
                        <td>{{ $total_price }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr>
        <div class="receipt-footer">
            <p>Compra recibida</p>
        </div>
    </div>
</body>

</html>
