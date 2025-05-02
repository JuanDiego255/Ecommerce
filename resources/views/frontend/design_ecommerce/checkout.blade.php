@extends('layouts.design_ecommerce.frontmain')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@php
    $address_tenant = '';
    $sinpe_name = '';
    switch ($tenantinfo->tenant) {
        case 'abril7cr':
        case 'aycfashion':
            $address_tenant = '';
            $sinpe_name = 'Ariel Valdivia';
            break;

        default:
            break;
    }
@endphp
@section('content')
    <div class="container m-t-80">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                <a href="{{ url(($prefix == 'aclimate' ? $prefix . '' : '') .'/') }}" class="stext-109 cl8 hov-cl1 trans-04">
                    Inicio
                    <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                </a>
                <a href="{{ url(($prefix == 'aclimate' ? $prefix . '' : '') .'/category') }}" class="stext-109 cl8 hov-cl1 trans-04">
                    Categorías
                    <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                </a>
                <a href="{{ url(($prefix == 'aclimate' ? $prefix . '' : '') .'/view-cart') }}" class="stext-109 cl8 hov-cl1 trans-04">
                    Carrito
                    <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                </a>
                <span class="stext-109 cl4">
                    Checkout
                </span>
            @else
                <a href="{{ url(($prefix == 'aclimate' ? $prefix . '' : '') .'/') }}" class="stext-109 cl8 hov-cl1 trans-04">
                    Inicio
                    <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                </a>
                <a href="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') .'departments/index') }}" class="stext-109 cl8 hov-cl1 trans-04">
                    Departamentos
                    <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                </a>
                <a href="{{ url(($prefix == 'aclimate' ? $prefix . '' : '') .'/view-cart') }}" class="stext-109 cl8 hov-cl1 trans-04">
                    Carrito
                    <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                </a>
                <span class="stext-109 cl4">
                    Checkout
                </span>
            @endif
        </div>
        <div class="row m-t-40">
            <div id="sinpeContent" class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
                <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-20 m-r-10 m-lr-0-xl p-lr-15-sm">
                    <h4 class="mtext-109 clnew p-b-30">Formulario de compra</h4>
                    <form action="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') .'payment') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="0" name="delivery" id="delivery">
                        <input type="hidden" value="{{ $prefix }}" name="prefix" id="prefix">
                        <input type="hidden" value="{{ $delivery }}" name="total_delivery" id="total_delivery">
                        <input type="hidden" value="V" name="kind_of" id="kind_of">
                        <input type="hidden" value="" name="apply_code" id="apply_code">
                        <input type="hidden" value="" name="credit_use" id="credit_use">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="bor8 dis-flex p-l-15 m-b-20">
                                    <input class="mtext-107 clnew size-114 plh2 p-r-15" type="text" name="name"
                                        value="{{ isset(Auth::user()->name) ? Auth::user()->name : '' }}" id="name"
                                        placeholder="Nombre Completo" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bor8 dis-flex p-l-15 m-b-20">
                                    <input class="mtext-107 clnew size-114 plh2 p-r-15" type="email" name="email"
                                        value="{{ isset(Auth::user()->email) ? Auth::user()->email : '' }}" id="email"
                                        placeholder="Correo Electrónico" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bor8 dis-flex p-l-15 m-b-20">
                                    <input class="mtext-107 clnew size-114 plh2 p-r-15" type="text" name="telephone"
                                        value="{{ isset(Auth::user()->telephone) ? Auth::user()->telephone : '' }}"
                                        id="telephone" placeholder="Teléfono (WhatsApp)" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bor8 dis-flex p-l-15 m-b-20">
                                    <input class="mtext-107 clnew size-114 plh2 p-r-15" type="text" name="country"
                                        id="country" value="Costa Rica" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bor8 dis-flex p-l-15 m-b-20">
                                    <input class="mtext-107 clnew size-114 plh2 p-r-15" type="text" name="province"
                                        id="province" placeholder="Provincia" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bor8 dis-flex p-l-15 m-b-20">
                                    <input class="mtext-107 clnew size-114 plh2 p-r-15" type="text" name="city"
                                        id="city" placeholder="Cantón" required>
                                </div>
                            </div>
                            @if ($tenant != 'mandicr')
                                <div class="col-md-6">
                                    <div class="bor8 dis-flex p-l-15 m-b-20">
                                        <input class="mtext-107 clnew size-114 plh2 p-r-15" type="text"
                                            name="address_two" id="address_two" placeholder="Distrito" required>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-6">
                                <div class="bor8 dis-flex p-l-15 m-b-20">
                                    <input class="mtext-107 clnew size-114 plh2 p-r-15" type="text" name="address"
                                        id="address" placeholder="Dirección Exacta" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bor8 dis-flex p-l-15 m-b-20">
                                    <input class="mtext-107 clnew size-114 plh2 p-r-15" type="text" name="postal_code"
                                        id="postal_code" placeholder="Código Postal" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="">Comprobante</label>
                                <div class="bor8 dis-flex p-l-15 m-b-20">
                                    <input class="mtext-107 clnew size-114 plh2 p-r-15" type="file" name="image"
                                        id="image" required>
                                </div>
                            </div>
                        </div>
                        <h6 class="sakura-color sakura-font mt-2 m-b-25">Realiza una transferencia bancaria, o cancela
                            por
                            medio de
                            SINPE Móvil, debes adjuntar el comprobante para que su compra sea aprobada</h6>

                        <button type="submit"
                            class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer text-white">Pagar
                            ₡<span id="btnPay">{{ number_format($total_price) }}</span></button>
                        @if (!Auth::check())
                            <h6 class="sakura-font m-t-25">
                                Una vez que te <a class="text-info" href="{{ route('register') }}">registres</a>
                                no
                                deberás
                                completar los detalles de entrega, e
                                información personal. Además de encontrar increíbles descuentos, y promociones.

                            </h6>
                        @else
                            <h6 class="sakura-font m-t-25">
                                Para cambiar la dirección de entrega ve a
                                <a class="text-info" href="{{ url(($prefix == 'aclimate' ? $prefix . '/' : '') .'address') }}">direcciones</a> y selecciona
                                la que desees.

                            </h6>
                        @endif
                    </form>
                </div>
            </div>
            <div id="cardContent" class="col-lg-10 col-xl-7 m-lr-auto m-b-50 dis-none">
                <div class="col col-12 ps-md-5 p-0">
                    <div class="box-left">
                        <p class="fw-bold h7">Nuestro método de pago por medio de tarjeta se encuentra deshabilitado.
                        </p>

                        <div class="">

                            {{-- <div class="btn-add_to_cart" id="paypal-button-container">

                    </div> --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-10 col-lg-5 col-xl-5 m-lr-auto m-b-50">
                <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                    <h4 class="mtext-109 clnew p-b-30">
                        Detalles
                    </h4>
                    <div class="row m-0 border mb-3">
                        @foreach ($cartItems as $item)
                            @php
                                $precio = $item->price;
                                if (
                                    isset($tenantinfo->custom_size) &&
                                    $tenantinfo->custom_size == 1 &&
                                    $item->stock_price > 0
                                ) {
                                    $precio = $item->stock_price;
                                }
                                $descuentoPorcentaje = $item->discount;
                                // Calcular el descuento
                                $descuento = ($precio * $descuentoPorcentaje) / 100;
                                // Calcular el precio con el descuento aplicado
                                $precioConDescuento = $precio - $descuento;
                                $attributesValues = !empty($item->attributes_values)
                                    ? explode(', ', $item->attributes_values)
                                    : [];
                            @endphp
                            <div class="d-flex justify-content-lg-start justify-content-center p-2">

                                <span class="ps-3 textmuted"><i class="fa fa-check"></i>
                                    {{ $item->name }} | Cant: {{ $item->quantity }} | Atributos
                                    @foreach ($attributesValues as $attributeValue)
                                        @php
                                            // Verifica que el atributo tenga el formato esperado antes de hacer explode
                                            $parts = explode(': ', $attributeValue, 2);
                                            $attribute = $parts[0] ?? '';
                                            $value = $parts[1] ?? '';

                                            // Solo mostrar si hay un atributo válido

                                        @endphp

                                        @if (!empty($attribute))
                                            {{ $attribute }}: {{ $value }}<br>
                                        @endif
                                    @endforeach


                                    |
                                    Precio:
                                    ₡{{ $item->discount > 0 ? $precioConDescuento * $item->quantity : ($tenantinfo->custom_size == 1 ? $item->stock_price * $item->quantity : $item->price * $item->quantity) }}
                                </span>
                            </div>
                            <hr class="dark horizontal my-0">
                        @endforeach
                    </div>
                    <div class="flex-w flex-t bor12 p-b-13">
                        <div class="size-208">
                            <span class="stext-110 clnew">
                                Total + IVA:
                            </span>
                        </div>

                        <div class="size-209">
                            <span>₡</span>
                            <span id="totalIva" class="mtext-110 clnew">
                                {{ number_format($total_price) }}
                            </span>
                        </div>
                    </div>
                    <p class="fw-bold h7 sakura-color m-t-5">Tarifa de envío por medio de correos.
                        ₡{{ $delivery }}
                        {{ $address_tenant }}</p>
                    <p class="fw-bold h7 sakura-color">SINPE Móvil:
                        {{ isset($tenantinfo->sinpe) ? $tenantinfo->sinpe : '' }}
                        {{ '(' . $sinpe_name . ')' }}
                    </p>
                    <div class="h8">
                        <label for="checkboxSubmit">
                            <div class="form-check">
                                <input id="envio" class="form-check-input envio" type="checkbox" value=""
                                    name="envio" onchange="checkEnvio();">
                                <label class="form-check-label mb-2" for="envio">
                                    Realizar Envío
                                </label>
                            </div>
                        </label>
                    </div>
                </div>
                <div class=" mt-4">
                    <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                        <div class="box-left">
                            <p class="fw-bold h7">¿Tienes un cupón? Se aplicará sobre el precio total</p>
                            <div class="h8 row">
                                <div class="col-md-12">
                                    <div class="bor8 dis-flex p-l-15 m-b-20">
                                        <input value="" placeholder="Ingrese el código" type="text"
                                            name="code" id="code"
                                            class="mtext-107 clnew size-114 plh2 p-r-15 code">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button id="btnCode" type="submit"
                                        class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer text-white btnCode">Canjear</button>
                                </div>
                                <div class="col-md-3 d-none" id="divCodeCancel">
                                    <button id="btnCodeCancel" type="submit"
                                        class="btn btn-add_to_cart d-block h8 btnCodeCancel">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" mt-4">
                    <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                        <div class="box-left">
                            <p class="fw-bold h7">Métodos de pago</p>
                            <div class="h8">

                                <label for="checkboxSubmit">
                                    <div class="form-check">
                                        <input id="sinpe" class="form-check-input" type="checkbox" value=""
                                            name="sinpe" checked onchange="togglePaypalButton();">
                                        <label class="form-check-label mb-2" for="sinpe">
                                            Pagar Por SINPE o Transferencia bancaria
                                        </label>
                                    </div>

                                </label>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.inc.design_ecommerce.footer')
@endsection
@section('scripts')
    @if (isset($advert))
        <script>
            // Verifica si la alerta ya ha sido mostrada en esta sesión
            if (!sessionStorage.getItem('alertShown')) {
                // Muestra la alerta
                var advertContent = @json($advert->content);

                // Muestra la alerta
                Swal.fire({
                    title: 'Anuncio importante!',
                    html: advertContent,
                    icon: "info",
                    showCloseButton: true,
                    focusConfirm: false,
                    confirmButtonText: `
                        <i class="fa fa-thumbs-up"></i> Entendido!
                    `,
                    confirmButtonAriaLabel: "Thumbs up, great!"
                });
                // Marca que la alerta ha sido mostrada
                sessionStorage.setItem('alertShown', 'true');
            }
        </script>
    @endif
    <script
        src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT_ID') }}&components=buttons,funding-eligibility">
    </script>
    <script>
        /*  paypal.Buttons({
                                                                                                                                                                                                                                                                                    locale: 'es',
                                                                                                                                                                                                                                                                                    fundingSource: paypal.FUNDING.CARD,
                                                                                                                                                                                                                                                                                    createOrder: function(data, actions) {
                                                                                                                                                                                                                                                                                        return actions.order.create({

                                                                                                                                                                                                                                                                                            payer: {
                                                                                                                                                                                                                                                                                                email_address: '{{ isset(Auth::user()->email) ? Auth::user()->email : '' }}',
                                                                                                                                                                                                                                                                                                name: {
                                                                                                                                                                                                                                                                                                    given_name: '{{ isset(Auth::user()->name) ? Auth::user()->name : '' }}',
                                                                                                                                                                                                                                                                                                    surname: ''
                                                                                                                                                                                                                                                                                                },
                                                                                                                                                                                                                                                                                                address: {
                                                                                                                                                                                                                                                                                                    country_code: "CR",
                                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                                            },
                                                                                                                                                                                                                                                                                            purchase_units: [{
                                                                                                                                                                                                                                                                                                amount: {
                                                                                                                                                                                                                                                                                                    value: {{ $paypal_amount }}
                                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                                            }]
                                                                                                                                                                                                                                                                                        });
                                                                                                                                                                                                                                                                                    },

                                                                                                                                                                                                                                                                                    onApprove(data) {
                                                                                                                                                                                                                                                                                        return fetch("/paypal/process/" + data.orderID)
                                                                                                                                                                                                                                                                                            .then((response) => response.json())
                                                                                                                                                                                                                                                                                            .then((orderData) => {
                                                                                                                                                                                                                                                                                                if (!orderData.success) {
                                                                                                                                                                                                                                                                                                    swal({
                                                                                                                                                                                                                                                                                                        title: orderData.status,
                                                                                                                                                                                                                                                                                                        icon: orderData.icon,
                                                                                                                                                                                                                                                                                                    }).then((value) => {
                                                                                                                                                                                                                                                                                                        // Esta función se ejecuta cuando el usuario hace clic en el botón "Ok"
                                                                                                                                                                                                                                                                                                        if (value) {
                                                                                                                                                                                                                                                                                                            // Recargar la página
                                                                                                                                                                                                                                                                                                            window.location.href = '{{ url('/') }}';
                                                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                                                    });
                                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                                                swal({
                                                                                                                                                                                                                                                                                                    title: orderData.status,
                                                                                                                                                                                                                                                                                                    icon: orderData.icon,
                                                                                                                                                                                                                                                                                                }).then((value) => {
                                                                                                                                                                                                                                                                                                    // Esta función se ejecuta cuando el usuario hace clic en el botón "Ok"
                                                                                                                                                                                                                                                                                                    if (value) {
                                                                                                                                                                                                                                                                                                        // Recargar la página
                                                                                                                                                                                                                                                                                                        window.location.href = '{{ url('/') }}';
                                                                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                                                                });
                                                                                                                                                                                                                                                                                            });
                                                                                                                                                                                                                                                                                    },
                                                                                                                                                                                                                                                                                    onError: function(err) {
                                                                                                                                                                                                                                                                                        alert(err);
                                                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                                                }).render('#paypal-button-container'); */

        function togglePaypalButton() {
            var checkBox = document.getElementById("sinpe");
            var paypalButton = document.getElementById("paypal-button-container");
            var sinpeContent = document.getElementById("sinpeContent");
            var cardContent = document.getElementById("cardContent");

            if (checkBox.checked != true) {
                //paypalButton.style.display = "block";
                sinpeContent.style.display = "none";
                cardContent.style.display = "block";
            } else {
                //paypalButton.style.display = "none";
                sinpeContent.style.display = "block";
                cardContent.style.display = "none";
            }
        }

        var envio = parseFloat(document.getElementById("total_delivery").value);
        var checkBox = document.getElementById("envio");
        var labelTotal = document.getElementById("totalIva");
        var labelBtnPay = document.getElementById("btnPay");
        var inputTotal = document.getElementById("delivery");
        var cardContent = document.getElementById("cardContent");

        $('.btnCode').click(function(e) {
            var code = document.getElementById("code").value;
            var prefix = document.getElementById('prefix').value == "aclimate" ? document.getElementById('prefix')
            .value : '';
            var url = (prefix === 'aclimate' ? '/' + prefix : '') + '/gift-code/' + code;

            var btnCodeCancel = $(
                '#divCodeCancel'
            );

            if (code == "") {
                swal("Proceso...",
                    "Debe ingresar un código", "warning");
            } else {
                $.ajax({
                    method: "GET",
                    url: url,
                    success: function(giftCard) {
                        if (typeof giftCard.status === 'undefined') {
                            swal("Proceso...",
                                "El código ingresado no es valido, o no está vigente", "warning");
                        } else {
                            $('.btnCode').prop('disabled', true);
                            $('.code').prop('readonly', true);
                            btnCodeCancel.removeClass('d-none').addClass('d-block');
                            if (giftCard.credit > 0) {
                                var numericTotalIva = convertToNumber(labelTotal.textContent);
                                var credit_use = 0;
                                var checkCode = document.getElementById("apply_code").value;
                                if (checkCode == "") {
                                    document.getElementById("apply_code").value = giftCard.code;
                                    if (giftCard.credit >= numericTotalIva) {
                                        document.getElementById("credit_use").value = numericTotalIva;
                                        credit_use = numericTotalIva;
                                        labelTotal.textContent = `${0}`;
                                        labelBtnPay.textContent = `${0}`;
                                    } else {
                                        credit_use = giftCard.credit;
                                        document.getElementById("credit_use").value = giftCard.credit;
                                        labelTotal.textContent =
                                            `${(numericTotalIva - giftCard.credit).toLocaleString()}`;
                                        labelBtnPay.textContent =
                                            `${(numericTotalIva - giftCard.credit).toLocaleString()}`;
                                    }
                                    swal("Proceso...",
                                        "Se aplicó el cupón por un monto de ₡" +
                                        credit_use.toLocaleString(), "success");
                                } else {
                                    swal("Proceso...",
                                        "Este cupón ya está en uso", "warning");
                                }



                            } else {
                                swal("Proceso...",
                                    "Este cupón ya ha sido utilizado y no contiene saldo.",
                                    "warning");
                            }
                        }
                    }
                });
            }
        });
        $('.btnCodeCancel').click(function(e) {
            location.reload();
        });

        function checkEnvio() {
            var code = document.getElementById("code").value;
            if (code != "") {
                swal("Proceso...",
                    "Activaste un cupón, si deseas gestionar el envío, debe cancelar el cupón", "warning");
                if (checkBox.checked) {
                    checkBox.checked = false;
                } else {
                    checkBox.checked = true;
                }
            } else {
                if (checkBox.checked) {
                    var numericTotalIva = convertToNumber(labelTotal.textContent);
                    labelTotal.textContent = `${(numericTotalIva + envio).toLocaleString('en-US')}`;
                    labelBtnPay.textContent = `${(numericTotalIva + envio).toLocaleString('en-US')}`;
                    inputTotal.value = envio;
                } else {
                    var numericTotalIva = convertToNumber(labelTotal.textContent);
                    labelTotal.textContent = `${(numericTotalIva - envio).toLocaleString('en-US')}`;
                    labelBtnPay.textContent = `${(numericTotalIva - envio).toLocaleString('en-US')}`;
                    inputTotal.value = 0;
                }
            }
        }

        function convertToNumber(str) {
            // Eliminar espacios y comas de la cadena
            let cleanedStr = str.replace(/[\s,]/g, '');
            // Convertir la cadena limpia en un número
            let num = Number(cleanedStr);
            return num;
        }
    </script>
@endsection
