@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container">
        <div class="alert alert-secondary alert-dismissible text-white fade show mt-4" role="alert">
            <span class="alert-icon align-middle">
                <span class="material-icons text-md">
                    payments
                </span>
            </span>
            <span class="alert-text"><strong>Completar La Compra!</strong></span><br>
            <span class="alert-icon align-middle">
                <span class="material-icons text-md">
                    domain_verification
                </span>
            </span>
            <span class="alert-text"><strong>Debes adjuntar el comprobante del SINPE Móvil, una vez verificado te haremos
                    llegar el envío.</strong></span>
            @if (!Auth::check())
                <br>
                <span class="alert-icon align-middle">
                    <span class="material-icons text-md">
                        person_add
                    </span>
                </span>
                <span class="alert-text">
                    <strong>Una vez que te <a class="text-info" href="{{ route('register') }}">registres</a> no deberás
                        completar los detalles de entrega, e
                        información personal. Además de encontrar increíbles descuentos, y promociones.
                    </strong>
                </span>
            @else
                <br>
                <span class="alert-icon align-middle">
                    <span class="material-icons text-md">
                        location_on
                    </span>
                </span>
                <span class="alert-text">
                    <strong>Para cambiar la dirección de entrega ve a 
                        <a class="text-info" href="{{url('address')}}">direcciones</a> y selecciona la que desees.
                    </strong>
                </span>
            @endif


        </div>
        <center>

            <form action="{{ url('payment') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group">
                    <div class="col bg-transparent">
                        <div class="card card-frame">
                            <h3 class="ps-3 mt-2">
                                Detalles Básicos
                            </h3>
                            <div class="card-body">
                                <div class="row checkout-form">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Nombre</label>
                                            <input value="{{ isset(Auth::user()->name) ? Auth::user()->name : '' }}"
                                                required type="text" name="name"
                                                class="form-control float-left w-100">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-2">
                                        <div class="input-group input-group-static mb-4">
                                            <label>E-mail</label>
                                            <input value="{{ isset(Auth::user()->email) ? Auth::user()->email : '' }}"
                                                required type="text" name="email"
                                                class="form-control float-left w-100">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Teléfono</label>
                                            <input
                                                value="{{ isset(Auth::user()->telephone) ? Auth::user()->telephone : '' }}"
                                                required type="text" name="telephone"
                                                class="form-control float-left w-100">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Dirección 1</label>
                                            <input value="{{ isset($user_info->address) ? $user_info->address : '' }}"
                                                required type="text" name="address"
                                                class="form-control float-left w-100">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Dirección 2</label>
                                            <input
                                                value="{{ isset($user_info->address_two) ? $user_info->address_two : '' }}"
                                                type="text" name="address_two" class="form-control float-left w-100">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Ciudad</label>
                                            <input value="{{ isset($user_info->city) ? $user_info->city : '' }}" required
                                                type="text" name="city" class="form-control float-left w-100">
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
                                            <label>País</label>
                                            <input
                                                value="{{ isset($user_info->country) ? $user_info->country : 'Costa Rica' }}"
                                                required value="Costa Rica" type="text" name="country"
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
                                    <div class="col-md-6 mb-3">
                                        <div class="input-group input-group-static mb-4">
                                            <label>comprobante (SINPE Móvil)</label>
                                            <input required class="form-control" type="file" name="image">
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="col bg-transparent">
                        <div class="card card-frame">
                            <h3 class="ps-3 mt-2">
                                Detalles De Orden
                            </h3>
                            <div class="card-body">
                                <div class="row checkout-form">
                                    @foreach ($cartItems as $item)
                                        <div class="d-flex justify-content-lg-start justify-content-center p-2">

                                            <h4 class="ps-3 text-muted"><i class="material-icons my-auto">done</i>
                                                {{ $item->name }} | Cant: {{ $item->quantity }} | Talla:
                                                {{ $item->size }}
                                                |
                                                Precio: ₡{{ number_format($item->price * $item->quantity) }}</h4>
                                        </div>
                                        <hr class="dark horizontal my-0">
                                    @endforeach
                                    <div class="card-footer d-flex">
                                        <h4 class="ps-3 my-auto text-success">I.V.A: ₡{{ number_format($iva) }}</h4>
                                        <i
                                            class="material-icons position-relative ms-auto text-lg me-1 my-auto">payments</i>
                                        <h4 class="ps-3 my-auto text-success">Total: ₡{{ number_format($total_price) }}
                                        </h4>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <button type="submit" class="btn btn-icon btn-5 mt-4 btn-success w-50">
                            <span class="btn-inner--icon"><i class="material-icons">local_atm</i></span>
                            <span class="btn-inner--text">Completar Pago</span>
                        </button>
                        <a href="{{ url('view-cart') }}" class="btn btn-icon btn-5 mt-1 btn-info w-50"> <span
                                class="btn-inner--icon"><i class="material-icons">shopping_cart</i></span>
                            <span class="btn-inner--text">Ir al carrito</span></a>
                    </div>
                </div>
            </form>
        </center>

    </div>
    @include('layouts.inc.indexfooter')
@endsection
