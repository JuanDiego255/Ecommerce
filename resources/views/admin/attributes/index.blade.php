@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Atributos</li>
@endsection
@section('content')

    @include('admin.attributes.add')

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="page-header-title mb-0">Atributos globales</h4>
            <div class="page-header-sub">
                Administrá los tipos y valores disponibles para todos los productos.
                También podés crear atributos y valores directamente desde la ficha de cada producto.
            </div>
        </div>
        <button type="button" class="ph-btn ph-btn-add" data-bs-toggle="modal" data-bs-target="#add-attribute-modal"
                title="Nuevo atributo">
            <i class="fas fa-plus"></i>
        </button>
    </div>

    <div class="surface p-4">
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="filter-label">Filtrar</label>
                <input value="" placeholder="Escribe para filtrar…" type="text"
                    class="filter-input" name="searchfor" id="searchfor">
            </div>
            <div class="col-md-3">
                <label class="filter-label">Mostrar</label>
                <select id="recordsPerPage" name="recordsPerPage" class="filter-input">
                    <option value="5">5 registros</option>
                    <option value="10">10 registros</option>
                    <option selected value="15">15 registros</option>
                    <option value="50">50 registros</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-items-center mb-0 thead-lite" id="table">
                <thead>
                    <tr>
                        <th>Acciones</th>
                        <th>Nombre</th>
                        <th>Estilo</th>
                        <th>Valores</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attributes as $item)
                        <tr>
                            <td class="align-middle">
                                <form name="delete-attribute{{ $item->id }}"
                                    id="delete-attribute{{ $item->id }}" method="post"
                                    action="{{ url('/delete-attribute/' . $item->id) }}">
                                    @csrf @method('DELETE')
                                </form>
                                <button form="delete-attribute{{ $item->id }}" type="submit"
                                    onclick="return confirm('¿Eliminar este atributo y todos sus valores?')"
                                    class="btn btn-link text-danger p-1" title="Eliminar">
                                    <i class="material-icons" style="font-size:1.1rem">delete</i>
                                </button>
                                <a class="btn btn-link p-1" href="{{ url('/attribute-values/' . $item->id) }}"
                                   title="Ver y editar valores">
                                    <i class="material-icons" style="font-size:1.1rem">list</i>
                                </a>
                                <a class="btn btn-link p-1" href="{{ url('/attribute/' . $item->id . '/edit') }}"
                                   title="Editar nombre">
                                    <i class="material-icons" style="font-size:1.1rem">edit</i>
                                </a>
                            </td>
                            <td class="align-middle fw-600">{{ $item->name }}</td>
                            <td class="align-middle" style="font-size:.8rem;color:var(--gray4)">
                                {{ $item->type == 1 ? 'Seleccionador' : 'Botón simple' }}
                            </td>
                            <td class="align-middle" style="font-size:.8rem;color:var(--gray4)">
                                {{ $item->values_count ?? '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
