@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container">
        <div class="breadcrumb-nav bc3x mt-4">
            <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }}  me-1"></i></a></li>
            <li class="bread-standard"><a href="{{ url('/buys') }}"><i class="fa fa-shopping-cart me-1"></i>Mis Compras</a>
            </li>
            <li class="bread-standard"><a href="#"><i class="fab fa-cc-mastercard me-1"></i>Detalles Del Pedido</a></li>
        </div>
        <div class="row w-100">
            <div class="col-md-6">
                <div class="input-group input-group-lg input-group-static my-3 w-100">
                    <label>Filtrar</label>
                    <input value="" placeholder="Escribe para filtrar...." type="text"
                        class="form-control form-control-lg" name="searchfor" id="searchfor">
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-lg input-group-static my-3 w-100">
                    <label>Mostrar</label>
                    <select id="recordsPerPage" name="recordsPerPage" class="form-control form-control-lg"
                        autocomplete="recordsPerPage">
                        <option value="5">5 Registros</option>
                        <option value="10">10 Registros</option>
                        <option selected value="15">15 Registros</option>
                        <option value="50">50 Registros</option>
                    </select>

                </div>
            </div>
        </div>

        <center>
            <div class="card w-100 mb-4">
                <div class="table-responsive">
                    <table id="table" class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary font-weight-bolder opacity-7">Artículo</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Atributos</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Pedido</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Cantidad</th>
                                {{-- <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Acciones</th> --}}

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($buysDetails as $buy)
                                @php
                                    $attributesValues = explode(', ', $buy->attributes_values);
                                @endphp
                                <tr>
                                    <td class="w-50">
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <a target="blank" data-fancybox="gallery"
                                                    href="{{ route('file', $buy->image) }}">
                                                    <img src="{{ route('file', $buy->image) }}"
                                                        class="avatar avatar-md me-3">
                                                </a>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h4 class="mb-0 text-lg">{{ $buy->name }}</h4>
                                                <p class="mb-0 mt-0 text-sm text-info">
                                                    Precio: ₡{{ number_format($buy->total) }}<br>
                                                    @if ($iva > 0)
                                                        I.V.A: ₡{{ number_format($buy->iva) }}
                                                    @endif
                                                </p>

                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <p class=" font-weight-bold mb-0">
                                            @foreach ($attributesValues as $attributeValue)
                                                @php
                                                    // Separa el atributo del valor por ": "
                                                    [$attribute, $value] = explode(': ', $attributeValue);
                                                @endphp

                                                {{ $attribute }}: {{ $value }}<br>
                                            @endforeach
                                        </p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">
                                            @switch($buy->cancel_item)
                                                @case(0)
                                                    Vigente
                                                @break

                                                @case(1)
                                                    En proceso cancelación
                                                @break

                                                @default
                                                    Cancelada
                                            @endswitch
                                        </p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">{{ $buy->quantity }}</p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </center>
    </div>
    @include('layouts.inc.indexfooter')
@endsection
@section('scripts')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
