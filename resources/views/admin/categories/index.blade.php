@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active">Categorías</li>
@endsection
@section('content')
    @include('admin.categories.import')
    <div class="d-flex gap-2 mb-0">
        <a href="{{ url('add-category/' . $department_id) }}" class="btn btn-primary btn-sm">
            <span class="material-icons">add</span> {{ __('Nueva categoría') }}
        </a>
        @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
            <button type="button" data-bs-toggle="modal" data-bs-target="#import-product-modal"
                class="btn btn-secondary btn-sm">
                <span class="material-icons">upload_file</span> Importar Productos
            </button>
        @endif
    </div>

    <div class="s-card">
        <div class="s-card-header">
            <div class="card-h-icon"><span class="material-icons">tune</span></div>
            <span class="card-h-title">Filtros</span>
        </div>
        <div class="s-card-body" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;">
            <div>
                <label class="filter-label">Buscar</label>
                <input value="" placeholder="Escribe para filtrar..." type="text"
                    class="filter-input" name="searchfor" id="searchfor">
            </div>
            <div>
                <label class="filter-label">Mostrar</label>
                <select id="recordsPerPage" name="recordsPerPage" class="filter-input">
                    <option value="5">5 registros</option>
                    <option value="10">10 registros</option>
                    <option selected value="15">15 registros</option>
                    <option value="50">50 registros</option>
                </select>
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
                                    {{ __('Acciones') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Categoría') }}
                                </th>{{-- 
                                <th class="text-secondary font-weight-bolder opacity-7">
                                    {{ __('Descripción') }}</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $item)
                                <tr>
                                    <td class="align-middle text-center">

                                        <form name="delete-category{{ $item->id }}"
                                            id="delete-category{{ $item->id }}" method="post"
                                            action="{{ url('/delete-category/' . $item->id) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                        <button class="btn btn-link text-velvet ms-auto border-0" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Eliminar"
                                            class="btn btn-link text-velvet me-auto border-0"
                                            onclick="submitForm({{ $item->id }})">
                                            <i class="material-icons text-lg">delete</i>
                                        </button>
                                        <a class="btn btn-link text-velvet me-auto border-0"
                                            href="{{ url('/edit-category') . '/' . $item->id }}" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Editar">
                                            <i class="material-icons text-lg">edit</i>
                                        </a>
                                        <a class="btn btn-link text-velvet me-auto border-0"
                                            href="{{ url('/add-item') . '/' . $item->id }}" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Ver colección">
                                            <i class="material-icons text-lg">visibility</i>
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

                                            </div>
                                        </div>
                                    </td>

                                    {{-- <td class="align-middle text-sm">
                                        <p class="mb-0">{!! $item->description !!}
                                        </p>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @if (isset($tenantinfo->manage_department) && $tenantinfo->manage_department != 0)
        <center>
            <div class="col-md-12 mt-3">
                <a href="{{ url('departments') }}" class="btn btn-accion w-25">Volver</a>
            </div>
        </center>
    @endif
@endsection
@section('script')
    <script>
        function submitForm(itemId) {
            var form = document.getElementById('delete-category' + itemId);
            var confirmDelete = confirm('Deseas borrar esta categoría?');

            if (confirmDelete) {
                form.submit();
            }
        }
    </script>
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
