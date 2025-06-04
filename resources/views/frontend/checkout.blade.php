@extends('layouts.front')
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
    @switch($tenantinfo->tenant)
        @case('sakura318')
            <div class="container mt-4 mb-4">
                <div class="breadcrumb-nav-sk">

                    @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                        <li class="home-sk"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a></li>
                        <li class="bread-sk"><a href="{{ url('category/') }}"><i
                                    class="fas fa-{{ $icon->categories }} me-1"></i>Categorías</a></li>
                        <li class="bread-sk"><a href="{{ url('/view-cart/cnormal-in') }}"><i
                                    class="fas fa-{{ $icon->cart }} me-1"></i>Carrito</a></li>
                        <li class="bread-sk"><a href="#"><i class="fab fa-cc-mastercard me-1"></i>Finalizar Compra</a></li>
                    @else
                        <li class="home-sk"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i></a></li>
                        <li class="bread-sk"><a href="{{ url('departments/index') }}"><i
                                    class="fas fa-shapes me-1"></i>Departamentos</a></li>
                        <li class="bread-sk"><a href="{{ url('/view-cart/cnormal-in') }}"><i
                                    class="fas fa-{{ $icon->cart }} me-1"></i>Carrito</a></li>
                        <li class="bread-sk"><a href="#"><i class="fab fa-cc-mastercard me-1"></i>Finalizar Compra</a></li>
                    @endif

                </div>


                <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-1">
                    <div class="col bg-transparent">
                        <div id="sinpeContent" class="bg-transparent">
                            <div class="card card-frame">
                                <h3 class="ps-3 mt-2 text-center sakura-font sakura-color">
                                    Detalles Básicos
                                </h3>

                                <div class="card-body">
                                    <form action="{{ url('payment') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" value="0" name="delivery" id="delivery">
                                        <input type="hidden" value="" name="sucursal_rec" id="sucursal_rec">
                                        <input type="hidden" value="{{ $delivery }}" name="total_delivery"
                                            id="total_delivery">
                                        <input type="hidden" value="V" name="kind_of" id="kind_of">
                                        <input type="hidden" value="" name="apply_code" id="apply_code">
                                        <input type="hidden" value="" name="credit_use" id="credit_use">
                                        <div class="row checkout-form">
                                            <div class="col-md-6">
                                                <div class="input-group input-group-static mb-4">
                                                    <label class="sakura-font">Nombre Completo</label>
                                                    <input value="{{ isset(Auth::user()->name) ? Auth::user()->name : '' }}"
                                                        required type="text" name="name"
                                                        class="form-control float-left w-100">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group input-group-static mb-4">
                                                    <label class="sakura-font">E-mail</label>
                                                    <input value="{{ isset(Auth::user()->email) ? Auth::user()->email : '' }}"
                                                        required type="text" name="email"
                                                        class="form-control float-left w-100">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-2">
                                                <div class="input-group input-group-static mb-4">
                                                    <label class="sakura-font">Teléfono (WhatsApp)</label>
                                                    <input
                                                        value="{{ isset(Auth::user()->telephone) ? Auth::user()->telephone : '' }}"
                                                        required type="text" name="telephone"
                                                        class="form-control float-left w-100">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-2">
                                                <div class="input-group input-group-static mb-4">
                                                    <label class="sakura-font">País</label>
                                                    <input
                                                        value="{{ isset($user_info->country) ? $user_info->country : 'Costa Rica' }}"
                                                        required readonly value="Costa Rica" type="text" name="country"
                                                        class="form-control float-left w-100">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-2">
                                                <div class="input-group input-group-static mb-4">
                                                    <label class="sakura-font">Provincia</label>
                                                    <input value="{{ isset($user_info->province) ? $user_info->province : '' }}"
                                                        required type="text" name="province"
                                                        class="form-control float-left w-100">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-2">
                                                <div class="input-group input-group-static mb-4">
                                                    <label class="sakura-font">Cantón</label>
                                                    <input value="{{ isset($user_info->city) ? $user_info->city : '' }}" required
                                                        type="text" name="city" class="form-control float-left w-100">
                                                </div>
                                            </div>
                                            @if ($tenant != 'mandicr')
                                                <div class="col-md-6 mt-2">
                                                    <div class="input-group input-group-static mb-4">
                                                        <label class="sakura-font">Distrito</label>
                                                        <input
                                                            value="{{ isset($user_info->address_two) ? $user_info->address_two : '' }}"
                                                            type="text" name="address_two"
                                                            class="form-control float-left w-100">
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-md-6 mt-2">
                                                <div class="input-group input-group-static mb-4">
                                                    <label class="sakura-font">
                                                        Dirección Exacta
                                                    </label>
                                                    <input value="{{ isset($user_info->address) ? $user_info->address : '' }}"
                                                        required type="text" name="address"
                                                        class="form-control float-left w-100">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-2">
                                                <div class="input-group input-group-static mb-4">
                                                    <label class="sakura-font">Código Postal</label>
                                                    <input
                                                        value="{{ isset($user_info->postal_code) ? $user_info->postal_code : '' }}"
                                                        required type="text" name="postal_code"
                                                        class="form-control float-left w-100">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group input-group-static mb-4">
                                                    <label class="sakura-font">comprobante (SINPE Móvil)</label>
                                                    <input required class="form-control" type="file" name="image">
                                                </div>
                                            </div>
                                            <span class="sakura-color sakura-font">SINPE Móvil:
                                                {{ isset($tenantinfo->sinpe) ? $tenantinfo->sinpe : '' }}
                                                {{ isset($tenantinfo->count) ? '| Cuenta bancaria: ' . $tenantinfo->count : '' }}</span>
                                            <h6 class="sakura-color sakura-font mt-2">Realiza una transferencia bancaria, o cancela
                                                por
                                                medio de
                                                SINPE Móvil, debes adjuntar el comprobante para que su compra sea aprobada</h6>

                                            <button id="btnSinpe" type="submit" class="btn btn-add_to_cart d-block h8">Pagar
                                                ₡<span id="btnPay">{{ number_format($total_price) }}</span></button>
                                            @if (!Auth::check())
                                                <h6 class="sakura-font">
                                                    Una vez que te <a class="text-info"
                                                        href="{{ route('register') }}">registres</a>
                                                    no
                                                    deberás
                                                    completar los detalles de entrega, e
                                                    información personal. Además de encontrar increíbles descuentos, y promociones.

                                                </h6>
                                            @else
                                                <h6 class="sakura-font">
                                                    Para cambiar la dirección de entrega ve a
                                                    <a class="text-info" href="{{ url('address') }}">direcciones</a> y selecciona
                                                    la que desees.

                                                </h6>
                                            @endif

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div id="cardContent" style="display: none;">
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
                    </div>

                    <div class="col bg-transparent">
                        <div>
                            <div class="col col-12 ps-md-5 p-0">
                                <div class="box-left">
                                    <p class="fw-bold h7 sakura-color">Detalles de la compra</p>
                                    <div class="h8">
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

                                                    <span class="ps-3 textmuted"><i
                                                            class="material-icons my-auto textmuted">done</i>
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
                                        <div class="d-flex h7">
                                            <p class="">Total + I.V.A</p>
                                            <p class="ms-auto sakura-color"></span>₡<span
                                                    id="totalIva">{{ number_format($total_price) }}</span></p>
                                        </div>
                                        <p class="fw-bold h7 sakura-color">Tarifa de envío por medio de correos.
                                            ₡{{ $delivery }}
                                            {{ $address_tenant }}</p>
                                        <p class="fw-bold h7 sakura-color">SINPE Móvil:
                                            {{ isset($tenantinfo->sinpe) ? $tenantinfo->sinpe : '' }}
                                            {{ '(' . $sinpe_name . ')' }}
                                        </p>
                                        <div class="h8">
                                            <label for="checkboxSubmit">
                                                <div class="form-check">
                                                    <input id="envio" class="form-check-input envio" type="checkbox"
                                                        value="" name="envio" onchange="checkEnvio(this);">
                                                    <label class="form-check-label mb-2" for="envio">
                                                        Realizar Envío
                                                    </label>
                                                </div>
                                            </label><br>
                                            <label for="checkboxSubmit">
                                                <div class="form-check">
                                                    <input id="envio2" class="form-check-input envio" type="checkbox"
                                                        value="" name="envio2" onchange="checkEnvio(this);">
                                                    <label class="form-check-label mb-2" for="envio2">
                                                        Recolección (Sucursal Guadalupe)
                                                    </label>
                                                </div>
                                            </label><br>
                                            <label for="checkboxSubmit">
                                                <div class="form-check">
                                                    <input id="envio3" class="form-check-input envio" type="checkbox"
                                                        value="" name="envio3" onchange="checkEnvio(this);">
                                                    <label class="form-check-label mb-2" for="envio3">
                                                        Recolección (Sucursal Tibás)
                                                    </label>
                                                </div>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" mt-4">
                            <div class="col col-12 ps-md-5 p-0">
                                <div class="box-left">
                                    <p class="fw-bold h7 sakura-color">¿Tienes un cupón? Se aplicará sobre el precio total</p>
                                    <div class="h8 row">
                                        <div class="col-md-6">
                                            <div class="input-group input-group-static mb-4">
                                                <input value="" placeholder="Ingrese el código" type="text"
                                                    name="code" id="code" class="form-control float-left w-100 code">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <button id="btnCode" type="submit"
                                                class="btn btn-add_to_cart d-block h8 btnCode">Canjear</button>
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
                            <div class="col col-12 ps-md-5 p-0">
                                <div class="box-left">
                                    <p class="fw-bold h7 sakura-color">Métodos de pago</p>
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
        @break

        @default
            <div class="container mt-4 mb-4">
                <div class="breadcrumb-nav bc3x">
                    @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
                        <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a>
                        </li>
                        <li class="bread-standard"><a href="{{ url('category/') }}"><i
                                    class="fas fa-{{ $icon->categories }} me-1"></i>Categorías</a></li>
                        <li class="bread-standard"><a href="{{ url('/view-cart/cnormal-in') }}"><i
                                    class="fas fa-{{ $icon->cart }} me-1"></i>Carrito</a>
                        </li>
                        <li class="bread-standard"><a href="#"><i class="fab fa-cc-mastercard me-1"></i>Finalizar
                                Compra</a>
                        </li>
                    @else
                        <li class="home"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i></a></li>
                        <li class="bread-standard"><a href="{{ url('departments/index') }}"><i
                                    class="fas fa-shapes me-1"></i>Departamentos</a></li>
                        <li class="bread-standard"><a href="{{ url('/view-cart/cnormal-in') }}"><i
                                    class="fas fa-{{ $icon->cart }} me-1"></i>Carrito</a>
                        </li>
                        <li class="bread-standard"><a href="#"><i class="fab fa-cc-mastercard me-1"></i>Finalizar
                                Compra</a>
                        </li>
                    @endif

                </div>


                <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-1">
                    <div class="col bg-transparent">
                        <div id="sinpeContent" class="bg-transparent">
                            <div class="card card-frame">
                                <h3 class="ps-3 mt-2 text-center">
                                    Detalles Básicos
                                </h3>

                                <div class="card-body">
                                    <form action="{{ url('payment') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" value="0" name="delivery" id="delivery">
                                        <input type="hidden" value="{{ $delivery }}" name="total_delivery"
                                            id="total_delivery">
                                        <input type="hidden" value="V" name="kind_of" id="kind_of">
                                        <input type="hidden" value="" name="apply_code" id="apply_code">
                                        <input type="hidden" value="" name="credit_use" id="credit_use">
                                        <div class="row checkout-form">
                                            <div class="col-md-6">
                                                <div class="input-group input-group-static mb-4">
                                                    <label>Nombre Completo</label>
                                                    <input value="{{ isset(Auth::user()->name) ? Auth::user()->name : '' }}"
                                                        required type="text" name="name"
                                                        class="form-control float-left w-100">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group input-group-static mb-4">
                                                    <label>E-mail</label>
                                                    <input value="{{ isset(Auth::user()->email) ? Auth::user()->email : '' }}"
                                                        required type="text" name="email"
                                                        class="form-control float-left w-100">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-2">
                                                <div class="input-group input-group-static mb-4">
                                                    <label>Teléfono (WhatsApp)</label>
                                                    <input
                                                        value="{{ isset(Auth::user()->telephone) ? Auth::user()->telephone : '' }}"
                                                        required type="text" name="telephone"
                                                        class="form-control float-left w-100">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-2">
                                                <div class="input-group input-group-static mb-4">
                                                    <label>País</label>
                                                    <input
                                                        value="{{ isset($user_info->country) ? $user_info->country : 'Costa Rica' }}"
                                                        required readonly value="Costa Rica" type="text" name="country"
                                                        class="form-control float-left w-100">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-2">
                                                <div class="input-group input-group-static mb-4">
                                                    <label>Provincia</label>
                                                    <input value="{{ isset($user_info->province) ? $user_info->province : '' }}"
                                                        required type="text" name="province"
                                                        class="form-control float-left w-100">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-2">
                                                <div class="input-group input-group-static mb-4">
                                                    <label>Cantón</label>
                                                    <input value="{{ isset($user_info->city) ? $user_info->city : '' }}" required
                                                        type="text" name="city" class="form-control float-left w-100">
                                                </div>
                                            </div>
                                            @if ($tenant != 'mandicr')
                                                <div class="col-md-6 mt-2">
                                                    <div class="input-group input-group-static mb-4">
                                                        <label>Distrito</label>
                                                        <input
                                                            value="{{ isset($user_info->address_two) ? $user_info->address_two : '' }}"
                                                            type="text" name="address_two"
                                                            class="form-control float-left w-100">
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-md-6 mt-2">
                                                <div class="input-group input-group-static mb-4">
                                                    <label>
                                                        Dirección Exacta
                                                    </label>
                                                    <input value="{{ isset($user_info->address) ? $user_info->address : '' }}"
                                                        required type="text" name="address"
                                                        class="form-control float-left w-100">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-2">
                                                <div class="input-group input-group-static mb-4">
                                                    <label>Código Postal</label>
                                                    <input
                                                        value="{{ isset($user_info->postal_code) ? $user_info->postal_code : '' }}"
                                                        required type="text" name="postal_code"
                                                        class="form-control float-left w-100">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group input-group-static mb-4">
                                                    <label>comprobante (SINPE Móvil)</label>
                                                    <input required class="form-control" type="file" name="image">
                                                </div>
                                            </div>
                                            <span class="text-muted">SINPE Móvil:
                                                {{ isset($tenantinfo->sinpe) ? $tenantinfo->sinpe : '' }}
                                                {{ isset($tenantinfo->count) ? '| Cuenta bancaria: ' . $tenantinfo->count : '' }}</span>
                                            <h5 class="text-muted-normal mt-2">Realiza una transferencia bancaria, o cancela por
                                                medio de
                                                SINPE Móvil, debes adjuntar el comprobante para que su compra sea aprobada</h5>

                                            <button id="btnSinpe" type="submit" class="btn btn-add_to_cart d-block h8">Pagar
                                                ₡<span id="btnPay">{{ number_format($total_price) }}</span></button>
                                            @if (!Auth::check())
                                                <h5 class="text-muted-normal">
                                                    Una vez que te <a class="text-info"
                                                        href="{{ route('register') }}">registres</a>
                                                    no
                                                    deberás
                                                    completar los detalles de entrega, e
                                                    información personal. Además de encontrar increíbles descuentos, y promociones.

                                                </h5>
                                            @else
                                                <h5 class="text-muted-normal">
                                                    Para cambiar la dirección de entrega ve a
                                                    <a class="text-info" href="{{ url('address') }}">direcciones</a> y selecciona
                                                    la que desees.

                                                </h5>
                                            @endif

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div id="cardContent" style="display: none;">
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
                    </div>

                    <div class="col bg-transparent">
                        <div>
                            <div class="col col-12 ps-md-5 p-0">
                                <div class="box-left">
                                    <p class="fw-bold h7">Detalles de la compra</p>
                                    <div class="h8">
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

                                                    <span class="ps-3 textmuted"><i
                                                            class="material-icons my-auto textmuted">done</i>
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
                                        <div class="d-flex h7">
                                            <p class="">Total + I.V.A</p>
                                            <p class="ms-auto"></span>₡<span
                                                    id="totalIva">{{ number_format($total_price) }}</span></p>
                                        </div>
                                        <p class="fw-bold h7">Tarifa de envío por medio de correos. ₡{{ $delivery }}
                                            {{ $address_tenant }}</p>
                                        <p class="fw-bold h7">SINPE Móvil:
                                            {{ isset($tenantinfo->sinpe) ? $tenantinfo->sinpe : '' }}
                                            {{ '(' . $sinpe_name . ')' }}
                                        </p>
                                        <div class="h8">
                                            <label for="checkboxSubmit">
                                                <div class="form-check">
                                                    <input id="envio" class="form-check-input envio" type="checkbox"
                                                        value="" name="envio" onchange="checkEnvio();">
                                                    <label class="form-check-label mb-2" for="envio">
                                                        Realizar Envío
                                                    </label>
                                                </div>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" mt-4">
                            <div class="col col-12 ps-md-5 p-0">
                                <div class="box-left">
                                    <p class="fw-bold h7">¿Tienes un cupón? Se aplicará sobre el precio total</p>
                                    <div class="h8 row">
                                        <div class="col-md-6">
                                            <div class="input-group input-group-static mb-4">
                                                <input value="" placeholder="Ingrese el código" type="text"
                                                    name="code" id="code" class="form-control float-left w-100 code">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <button id="btnCode" type="submit"
                                                class="btn btn-add_to_cart d-block h8 btnCode">Canjear</button>
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
                            <div class="col col-12 ps-md-5 p-0">
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
        @break
    @endswitch
    @include('layouts.inc.indexfooter')
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
        var inputSucursalRec = document.getElementById("sucursal_rec");
        var cardContent = document.getElementById("cardContent");

        $('.btnCode').click(function(e) {
            var code = document.getElementById("code").value;

            var btnCodeCancel = $(
                '#divCodeCancel'
            );

            if (code == "") {
                Swal.fire({
                    title: "Debe ingresar un código",
                    icon: "warning",
                });
            } else {
                $.ajax({
                    method: "GET",
                    url: "/gift-code/" + code,
                    success: function(giftCard) {
                        if (typeof giftCard.status === 'undefined') {
                            Swal.fire({
                                title: "El código ingresado no es valido, o no está vigente",
                                icon: "warning",
                            });
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
                                    Swal.fire({
                                        title: "Se aplicó el cupón por un monto de ₡" +
                                            credit_use.toLocaleString(),
                                        icon: "success",
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Este cupón ya está en uso",
                                        icon: "warning",
                                    });
                                }



                            } else {
                                Swal.fire({
                                    title: "este cupón ya ha sido utilizado y no contiene saldo.",
                                    icon: "warning",
                                });
                            }
                        }
                    }
                });
            }
        });

        $('.btnCodeCancel').click(function(e) {
            location.reload();
        });

        function checkEnvio(checkPressed) {
            const checkboxes = document.querySelectorAll('.envio');
            var check_id = checkPressed.id;
            var checkEnvioPressed = checkBox.checked;
            switch (check_id) {
                case "envio2":
                    inputSucursalRec.value = checkPressed.checked ? "G" : null;
                    break;
                case "envio3":
                    inputSucursalRec.value = checkPressed.checked ? "T" : null;
                    break;
                default:
                    break;
            }

            // Recorrer todos los checkboxes
            checkboxes.forEach((checkboxOpc) => {
                // Si no es el checkbox que se acaba de cambiar, lo desmarcamos
                if (checkboxOpc !== event.target) {
                    checkboxOpc.checked = false;
                }
            });
            var code = document.getElementById("code").value;
            if (code != "") {
                Swal.fire({
                    title: "Activaste un cupón, si deseas gestionar el envío, debe cancelar el cupón",
                    icon: "warning",
                });
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
                    if (check_id == "envio" || (check_id !== "envio" && checkEnvioPressed)) {
                        var numericTotalIva = convertToNumber(labelTotal.textContent);
                        labelTotal.textContent = `${(numericTotalIva - envio).toLocaleString('en-US')}`;
                        labelBtnPay.textContent = `${(numericTotalIva - envio).toLocaleString('en-US')}`;
                        inputTotal.value = 0;
                    }
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
