@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <h1 class="font-title text-center">
        {{ __('Detalles de la compra') }}</h1>
    <div class="container-fluid">
        <div class="card mt-3 mb-3">
            <div class="card-body">
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
                                <option selected value="10">10 Registros</option>
                                <option value="25">25 Registros</option>
                                <option value="50">50 Registros</option>
                            </select>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <center>
            <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group">
                <div class="col-lg-8 bg-transparent">
                    <div class="card w-100 mb-4">
                        <div class="table-responsive">
                            <table id="buysDetails" class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            {{ __('Acciones') }}</th>
                                        <th class="text-uppercase text-secondary font-weight-bolder opacity-7">Artículo</th>
                                        <th class="text-uppercase text-secondary font-weight-bolder opacity-7">Atributos
                                        </th>

                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            {{ __('Estado') }}</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            {{ __('Cantidad') }}</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($buysDetails as $buy)
                                        @php
                                            $attributesValues = explode(', ', $buy->attributes_values);
                                        @endphp
                                        <tr>
                                            <td class="align-middle">
                                                @if ($buy->cancel_item == 1)
                                                    <form style="display:inline"
                                                        action="{{ url('cancel/buy-item/' . $buy->item_id . '/' . $buy->cancel_item) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf

                                                        <input type="hidden" name="action" value="1">
                                                        <input type="hidden" name="buy" value="{{ $buy->buy }}">
                                                        <button class="btn btn-velvet text-white btn-tooltip"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Aprobar Cancelación" data-container="body"
                                                            data-animation="true" type="submit"> <i
                                                                class="material-icons opacity-10">
                                                                check
                                                            </i>
                                                        </button>
                                                    </form>
                                                    <form style="display:inline"
                                                        action="{{ url('cancel/buy-item/' . $buy->item_id . '/' . $buy->cancel_item) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf

                                                        <input type="hidden" name="action" value="0">
                                                        <input type="hidden" name="buy" value="{{ $buy->buy }}">
                                                        <button class="btn btn-velvet text-white btn-tooltip"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Desaprobar Cancelación" data-container="body"
                                                            data-animation="true" type="submit"> <i
                                                                class="material-icons opacity-10">
                                                                cancel
                                                            </i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
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
                </div>

                <div class="col-lg-4 bg-transparent">
                    <div class="card card-frame">
                        <h3 class="ps-3 mt-2">
                            Detalles Del Envío
                        </h3>
                        <div class="card-body text-center">
                            <div class="row checkout-form">
                                @foreach ($buysDetails as $item)
                                    <div class="d-flex justify-content-center p-2">

                                        <h4 class="text-muted">
                                            <i class="material-icons my-auto">done</i>
                                            País: {{ isset($item->country) ? $item->country : $item->country_b }}<br>
                                            <i class="material-icons my-auto">done</i>
                                            Provincia:
                                            {{ isset($item->province) ? $item->province : $item->province_b }}<br>
                                            <i class="material-icons my-auto">done</i>
                                            Ciudad: {{ isset($item->city) ? $item->city : $item->city_b }}<br>
                                            @if ($tenant != 'mandicr')
                                                <i class="material-icons my-auto">done</i>
                                                Dirección:
                                                {{ isset($item->address) ? $item->address : $item->address_b }}<br>
                                            @else
                                                <i class="material-icons my-auto">done</i>
                                                Dirección Exacta:
                                                {{ isset($item->address) ? $item->address : $item->address_b }}<br>
                                            @endif
                                            @if ($tenant != 'mandicr')
                                                <i class="material-icons my-auto">done</i>
                                                Dirección 2:
                                                {{ isset($item->address_two) ? $item->address_two : $item->address_two_b }}<br>
                                            @endif

                                            <i class="material-icons my-auto">done</i>
                                            Código Postal:
                                            {{ isset($item->postal_code) ? $item->postal_code : $item->postal_code_b }}

                                        </h4>


                                    </div>
                                    <hr class="dark horizontal my-0">
                                @break
                            @endforeach
                            <div class="card-footer d-flex">

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </center>
</div>
<center>

    <div class="col-md-12 mt-3">
        <a href="{{ url('buys-admin') }}" class="btn btn-velvet">Volver</a>
    </div>
</center>
@endsection
@section('script')
<script src="{{ asset('js/datatables.js') }}"></script>
@endsection
