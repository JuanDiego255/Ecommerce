@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <center>
        @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 1)
            <h2 class="text-center font-title"><strong>{{ __('Gestiona las categorías') }}</strong></h2>
        @else
            <h2 class="text-center font-title"><strong>{{ __('Categorías del departamento ') }}
                    {{ $department_name }}.</strong></h2>
        @endif

    </center>
    @include('admin.categories.import')
    <div class="row w-50">
        <div class="col-md-3">
            <a href="{{ url('add-category/' . $department_id) }}" class="btn btn-velvet">{{ __('Nueva categoría') }}</a>
        </div>
        <div class="col-md-3">
            <button type="button" data-bs-toggle="modal" data-bs-target="#import-product-modal"
                class="btn btn-icon btn-3 btn-success">Importar Productos
                <i class="fas fa-file-excel"></i>
            </button>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <div class="row w-100">
                <div class="col-md-6">
                    <div class="input-group input-group-lg input-group-static my-3 w-100">
                        <label>Filtrar</label>
                        <input value="" placeholder="Escribe para filtrar...." type="text"
                            class="form-control form-control-lg" name="searchfor" id="searchfor">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <div class="row w-100">
                <div class="row row-cols-1 row-cols-md-3 g-4 align-content-center card-group mt-2 mb-5">
                    @foreach ($categories as $item)
                        <div class="col-md-3 col-sm-6 mb-2 card-container">
                            <div class="product-grid product_data">
                                <div class="product-image">

                                    <img src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                                    <ul class="product-links">
                                        <li><a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar"
                                                target="blank" href="{{ url('/edit-category') . '/' . $item->id }}"><i
                                                    class="fa fa-pencil"></i></a>
                                        </li>

                                        <form name="delete-category{{ $item->id }}"
                                            id="delete-category{{ $item->id }}" method="post"
                                            action="{{ url('/delete-category/' . $item->id) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                        <li>
                                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar"
                                                href="javascript:void(0);" onclick="submitForm({{ $item->id }})">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </li>
                                    </ul>
                                    <a href="{{ url('/add-item') . '/' . $item->id }}"
                                        class="add-to-cart">{{ __('Ver colección') }}</a>
                                </div>
                                <div class="product-content">
                                    <h3 class="text-muted category"><a
                                            href="{{ url('/add-item') . '/' . $item->id }}">{{ $item->name }}</a>
                                    </h3>
                                    <h5
                                        class="text-muted-normal {{ isset($tenantinfo->kind_business) && ($tenantinfo->kind_business != 2 && $tenantinfo->kind_business != 3) ? 'd-block' : 'd-none' }}">
                                        {{ $item->description }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <center>
        <div class="container mt-3">
            {{ $categories ?? ('')->links('pagination::simple-bootstrap-4') }}
        </div>
    </center>
@endsection
@section('script')
    <script src="{{ asset('js/image-error-handler.js') }}"></script>
    <script>
        function submitForm(itemId) {
            var form = document.getElementById('delete-category' + itemId);
            var confirmDelete = confirm('Deseas borrar esta categoría?');

            if (confirmDelete) {
                form.submit();
            }
        }

        $(document).ready(function() {
            $('#searchfor').on('keyup', function() {
                var searchTerm = $(this).val().toLowerCase();
                $('.card-container').each(function() {
                    var name = $(this).find('.category').text().toLowerCase();
                    if (name.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

        });
    </script>
@endsection
