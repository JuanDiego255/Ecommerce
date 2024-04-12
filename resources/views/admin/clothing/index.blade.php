@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ $category_name }}</strong>
        </h2>
    </center>
    <div class="row w-50">
        <div class="col-md-6">
            <a href="{{ url('new-item/' . $category_id) }}" class="btn btn-velvet w-100">Agregar Nuevo Producto</a>
        </div>
    </div>

    <div class="row w-75">
        <div class="col-md-6">
            <div class="input-group input-group-lg input-group-static my-3 w-100">
                <label>Filtrar</label>
                <input value="" placeholder="Escribe para filtrar...." type="text"
                    class="form-control form-control-lg" name="searchfor" id="searchfor">
            </div>
        </div>       
    </div>
    <div class="row row-cols-1 row-cols-md-3 g-4 align-content-center card-group mt-5 mb-5">
        @foreach ($clothings as $clothing)
            <div class="col bg-transparent mb-2 card-container">
                <div class="card" data-animation="true">

                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <a target="blank" data-fancybox="gallery" href="{{ route('file', $clothing->image) }}"
                            class="d-block blur-shadow-image">
                            <img src="{{ tenant_asset('/') . '/' . $clothing->image }}" alt="img-blur-shadow"
                                class="img-fluid shadow border-radius-lg w-100">
                        </a>
                        <div class="colored-shadow"
                            style="background-image: url(&quot;https://demos.creative-tim.com/test/material-dashboard-pro/assets/img/products/product-1-min.jpg&quot;);">
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="font-weight-normal mt-3 clothing-name">{{ $clothing->name }}</h5>
                        <input type="hidden" class="code" name="code" value="{{$clothing->code}}">
                        <div class="d-flex mt-n6 mx-auto">
                            <form name="delete-clothing{{ $clothing->id }}" id="delete-clothing{{ $clothing->id }}"
                                method="post" action="{{ url('/delete-clothing/' . $clothing->id) }}">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                            <button form="delete-clothing{{ $clothing->id }}" type="submit"
                                onclick="return confirm('Deseas borrar esta prenda?')"
                                class="btn btn-link text-velvet ms-auto border-0" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" title="Eliminar">
                                <i class="material-icons text-lg">delete</i>
                            </button>
                            <a class="btn btn-link text-velvet me-auto border-0"
                                href="{{ url('/edit-clothing') . '/' . $clothing->id . '/' . $category_id }}"
                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar">
                                <i class="material-icons text-lg">edit</i>
                            </a>
                        </div>
                        <p class="mb-0">
                            {{ $clothing->description }}
                        </p>
                        @php
                            $sizes = explode(',', $clothing->available_sizes);
                            $stockPerSize = explode(',', $clothing->stock_per_size);
                        @endphp
                        @for ($i = 0; $i < count($sizes); $i++)
                            <p class="mb-0">Talla {{ $sizes[$i] }}: {{ $stockPerSize[$i] }}</p>
                        @endfor
                        @if ($clothing->discount)
                            <p class="mb-0">
                                Descuento: {{ $clothing->discount }}%
                            </p>
                        @endif

                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer d-flex">
                        <p class="font-weight-normal my-auto">Precio: ₡{{ number_format($clothing->price) }}</p>
                        <i class="material-icons position-relative ms-auto text-lg me-1 my-auto">electric_bolt</i>
                        <p class="text-sm my-auto"> Estado: @if ($clothing->total_stock > 0)
                                Disponible
                            @else
                                Agotado
                            @endif
                        </p>
                        <i class="material-icons position-relative ms-auto text-lg me-1 my-auto">inventory</i>
                        <p class="text-sm my-auto"> Stock: {{ $clothing->total_stock }}</p>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
    <center>
        <div class="container">
            {{ $clothings ?? ('')->links('pagination::simple-bootstrap-4') }}
        </div>
        <div class="col-md-12 mt-3">
            <a href="{{ url('categories') }}" class="btn btn-velvet w-25">Volver</a>
        </div>
    </center>
@endsection
@section('script')
    <script src="{{ asset('js/image-error-handler.js') }}"></script>
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
