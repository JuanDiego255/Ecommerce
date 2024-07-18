@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@php
    $exist_attr = false;
@endphp
@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ $category_name }}</strong>
        </h2>
    </center>
    <div class="row w-50">
        <div class="col-md-6">
            <a href="{{ url('new-item/' . $category_id) }}"
                class="btn btn-velvet w-100">{{ __('Agregar nuevo producto') }}</a>
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
    <div class="row row-cols-1 row-cols-md-2 g-4 align-content-center card-group mt-1">

        <div class="col-md-12">
            <div class="card p-2">
                <div class="table-responsive">

                    <table class="table align-items-center mb-0" id="table">
                        <thead>
                            <tr>
                                <th class="text-center text-secondary font-weight-bolder opacity-7">
                                    {{ __('Activo') }}</th>
                                <th class="text-center text-secondary font-weight-bolder opacity-7">
                                    {{ __('Acciones') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Producto') }}
                                </th>
                                <th class="text-center text-secondary font-weight-bolder opacity-7">
                                    {{ __('Precio') }}</th>
                                <th class="text-center text-secondary font-weight-bolder opacity-7">
                                    {{ __('Atributos') }}</th>
                                <th class="text-center text-secondary font-weight-bolder opacity-7">
                                    {{ __('Stock') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clothings as $item)
                                @php
                                    $stockPerSize = explode(',', $item->stock_per_size);
                                    $attrPerItem = explode(',', $item->attr_id_per_size);
                                    $attr = explode(',', $item->available_attr);
                                @endphp
                                <tr>
                                    <td class="align-middle text-center">
                                        <form name="formActive{{ $item->id }}" id="formActive" method="post"
                                            action="{{ url('status/' . $item->id) }}" style="display:inline">
                                            {{ csrf_field() }}
                                            <label for="checkLicense">
                                                <div class="form-check">
                                                    <input id="checkLicense" class="form-check-input" type="checkbox"
                                                        value="1" name="status"
                                                        onchange="submitForm('formActive{{ $item->id }}')"
                                                        {{ $item->status == 1 ? 'checked' : '' }}>
                                                </div>
                                            </label>

                                        </form>
                                    </td>
                                    <td class="align-middle text-center">
                                        {{-- <form name="delete-clothing{{ $item->id }}"
                                            id="delete-clothing{{ $item->id }}" method="post"
                                            action="{{ url('/delete-clothing/' . $item->id) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                        <button form="delete-clothing{{ $item->id }}" type="submit"
                                            onclick="return confirm('Deseas borrar este producto?')"
                                            class="btn btn-link text-velvet ms-auto border-0" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Eliminar">
                                            <i class="material-icons text-lg">delete</i>
                                        </button> --}}
                                        <form name="delete-item" id="delete-item" class="delete-form">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                        </form>
                                        <button data-item-id="{{ $item->id }}"
                                            class="btn btn-link text-velvet ms-auto border-0 btnDeleteItem"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar">
                                            <i class="material-icons text-lg">delete</i>
                                        </button>
                                        <a class="btn btn-link text-velvet me-auto border-0"
                                            href="{{ url('/edit-clothing') . '/' . $item->id . '/' . $category_id }}"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar">
                                            <i class="material-icons text-lg">edit</i>
                                        </a>
                                    </td>
                                    <td class="w-50">
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <a target="blank" data-fancybox="gallery"
                                                    href="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}">
                                                    <img src="{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}"
                                                        class="avatar avatar-md me-3">
                                                </a>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h4 class="mb-0 text-lg">{{ $item->name }}</h4>
                                                <p class="text-xs text-secondary mb-0">Código: {{ $item->code }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <p class="text-success mb-0">₡{{ number_format($item->price) }}
                                        </p>
                                    </td>
                                    <td class="align-middle text-center text-sm">

                                        @for ($i = 0; $i < count($attr); $i++)
                                            @if ($attrPerItem[$i] != null && $attrPerItem[$i] != '' && $attr[$i] != 'Stock')
                                                @php
                                                    $exist_attr = true;
                                                @endphp
                                            @endif
                                        @endfor
                                        @if ($exist_attr == true)
                                            <p class="mb-0">{{ __('Con atributos') }}
                                            </p>
                                        @else
                                            <p class="mb-0">{{ __('Sin atributos') }}
                                            </p>
                                        @endif

                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <p class="text-success mb-0">
                                            {{ $item->manage_stock == 0 ? 'No maneja' : $item->total_stock }}
                                        </p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <center>

        <div class="col-md-12 mt-3">
            <a href="{{ url('categories/' . $department_id) }}" class="btn btn-velvet w-25">Volver</a>
        </div>
    </center>
@endsection
@section('script')
    <script>
        function getTotal(itemId, callback) {
            $.ajax({
                method: "GET",
                url: "/get-total-categories/" + itemId,
                success: function(total) {
                    callback(total); // Llama al callback con el total
                }
            });
        }

        $('.btnDeleteItem').click(function(e) {
            e.preventDefault();

            var itemId = $(this).data('item-id');

            // Llama a getTotal y maneja el resultado en el callback
            getTotal(itemId, function(total) {
                let message = (total > 1) ?
                    'Este producto se encuentra ligado a más de una categoría, ¿desea borrarlo?' :
                    '¿Deseas borrar este artículo?';

                Swal.fire({
                    title: 'Confirmación',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Borrar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: "POST",
                            url: "/delete-clothing/" + itemId,
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE',
                            },
                            success: function(response) {
                                location.reload();
                            },
                            error: function(xhr, status, error) {
                                // Manejar errores si es necesario
                                console.error(xhr.responseText);
                            }
                        });
                    }
                });
            });
        });

        function submitForm(alias) {
            var form = document.querySelector('form[name="' + alias + '"]');
            form.submit();
        }
    </script>
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
