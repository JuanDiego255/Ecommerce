@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <h1 class="ps-3 text-center">Completar La Compra</h1>
    <h6 class="ps-3 text-center mt-2">Debes adjuntar el comprobante del SINPE Móvil, una vez verificado te haremos
        llegar el envío.</h6>
    <div class="container">
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
                                            <input value="{{Auth::user()->name}}" required type="text" name="first_name"
                                                class="form-control float-left w-100">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Apellidos</label>
                                            <input value="{{Auth::user()->last_name}}" required type="text" name="last_name"
                                                class="form-control float-left w-100">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <div class="input-group input-group-static mb-4">
                                            <label>E-mail</label>
                                            <input value="{{Auth::user()->email}}" required type="text" name="email"
                                                class="form-control float-left w-100">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Teléfono</label>
                                            <input value="{{Auth::user()->telephone}}" required type="text" name="telephone"
                                                class="form-control float-left w-100">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Dirección 1</label>
                                            <input value="{{Auth::user()->address}}" required type="text" name="address"
                                                class="form-control float-left w-100">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Dirección 2</label>
                                            <input value="{{Auth::user()->address_two}}" type="text" name="address_optional"
                                                class="form-control float-left w-100">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Ciudad</label>
                                            <input value="{{Auth::user()->city}}" required type="text" name="city"
                                                class="form-control float-left w-100">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Provincia</label>
                                            <input value="{{Auth::user()->province}}" required type="text" name="state"
                                                class="form-control float-left w-100">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <div class="input-group input-group-static mb-4">
                                            <label>País</label>
                                            <input value="{{Auth::user()->country}}" required value="Costa Rica" type="text" name="country"
                                                class="form-control float-left w-100">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Código Postal</label>
                                            <input value="{{Auth::user()->postal_code}}" required type="text" name="code_postal"
                                                class="form-control float-left w-100">
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
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
@endsection
