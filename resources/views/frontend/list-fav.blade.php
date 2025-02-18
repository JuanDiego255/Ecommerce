@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container mt-4">
        <h3 class="text-title text-center">Lista seleccionada por {{ $user->name }}</h3>
        <div class="row w-75">
            <div class="col-md-6">
                <div class="input-group input-group-lg input-group-static my-3 w-100">
                    <label>Filtrar</label>
                    <input value="" placeholder="Escribe para filtrar...." type="text"
                        class="form-control form-control-lg" name="searchfor" id="searchfor">
                </div>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-md-4 g-4 align-content-center card-group mt-2 mb-5">
            @foreach ($clothings as $item)
                <div class="col-md-3 col-sm-6 mb-2 card-container">
                    <input type="hidden" class="code" name="code" value="{{ $item->code }}">
                    <div class="product-grid product_data">
                        <div class="product-image">
                            <img
                                src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                            @if ($item->discount)
                                <span class="product-discount-label">-{{ $item->discount }}%</span>
                            @endif

                            <ul class="product-links">
                                <li><a target="blank"
                                        href="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"><i
                                            class="fas fa-eye"></i></a></li>
                                @if (Auth::check())
                                    <li>
                                        <a class="add_favorite" data-clothing-id="{{ $item->id }}" href="#">
                                            <i
                                                class="fas fa-heart {{ $clothing_favs->contains('clothing_id', $item->id) ? 'text-danger' : '' }}"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                            <a href="{{ url('detail-clothing/' . $item->id . '/' . $item->category_id) }}"
                                class="add-to-cart">Detallar</a>
                        </div>
                        <div class="product-content">
                            <h3
                                class="text-muted text-uppercase {{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'd-none' : '' }}">
                                {{ $item->casa }}
                            </h3>
                            <h3 class="title clothing-name"><a href="#">({{ $item->category }})</a>
                            </h3>
                            <h3
                                class="{{ isset($tenantinfo->tenant) && $tenantinfo->tenant != 'fragsperfumecr' ? 'text-muted' : 'text-muted' }}">
                                <a href="{{ url('detail-clothing/' . $item->id . '/' . $item->category_id) }}">{{ $item->name }}
                                    @if ($item->total_stock == 0)
                                        <s class="text-danger"> Agotado</s>
                                    @endif
                                </a>
                            </h3>

                            @if (isset($tenantinfo->show_stock) && $tenantinfo->show_stock != 0)
                                <h4 class="title">
                                    Stock:
                                    @if ($item->total_stock > 0)
                                        {{ $item->total_stock }}
                                    @elseif ($item->total_stock == 0)
                                        <s class="text-danger">0</s>
                                    @else
                                        <span class="text-info">Sin manejo de stock</span>
                                    @endif
                                </h4>
                            @endif

                            @php
                                $precio = $item->price;
                                if (
                                    isset($tenantinfo->custom_size) &&
                                    $tenantinfo->custom_size == 1 &&
                                    $item->first_price > 0
                                ) {
                                    $precio = $item->first_price;
                                }
                                if (Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0) {
                                    $precio = $item->mayor_price;
                                }
                                $descuentoPorcentaje = $item->discount;
                                // Calcular el descuento
                                $descuento = ($precio * $descuentoPorcentaje) / 100;
                                // Calcular el precio con el descuento aplicado
                                $precioConDescuento = $precio - $descuento;
                            @endphp
                            <div class="price">₡{{ number_format($precioConDescuento) }}
                                @if ($item->discount)
                                    <s class="text-danger"><span
                                            class="text-danger">₡{{ number_format(Auth::check() && Auth::user()->mayor == '1' && $item->mayor_price > 0 ? $item->mayor_price : $item->price) }}
                                        </span></s>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <center>
            <div class="container mb-5">
                {{ $clothings ?? ('')->links('pagination::simple-bootstrap-4') }}
            </div>
        </center>
    </div>
    @if (isset($tenantinfo->kind_business) && $tenantinfo->kind_business != 1)
        @include('layouts.inc.indexfooter')
    @endif
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#searchfor').on('keyup', function() {
                var searchTerm = $(this).val().toLowerCase();
                $('.card-container').each(function() {
                    var name = $(this).find('.clothing-name').text().toLowerCase();
                    var code = $(this).find('.code').val().toLowerCase();
                    if (name.includes(searchTerm) || code.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

        });
    </script>
@endsection
