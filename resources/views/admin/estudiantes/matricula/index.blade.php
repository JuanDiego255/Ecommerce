@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    @php
        $label = $item->tipo_estudiante == 'Y' ? 'mensualidad' : 'matricula';
    @endphp
    <center>
        <h2 class="text-center font-title">
            <strong>{{ $item->tipo_estudiante == 'C' ? 'Gestión de matriculas del estudiante ' : 'Gestión de mensualidades del estudiante ' . $item->nombre }}</strong>
        </h2>
    </center>
    <button type="button" data-bs-toggle="modal" data-bs-target="#matricula-estudiante-modal{{ $item->id }}"
        class="btn btn-velvet">
        {{ __('Nueva ') . $label }}</button>
    @include('admin.estudiantes.matricula')
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
                            <option value="10">10 Registros</option>
                            <option selected value="15">15 Registros</option>
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
                                <th class="text-secondary font-weight-bolder opacity-7">
                                    {{ __('Acciones') }}
                                </th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Curso') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Monto Pagado') }}</th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">{{ __('Monto del curso') }}
                                </th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">
                                    {{ __('Fecha ') . ($item->tipo_estudiante == 'C' ? 'Matrícula' : 'Inicio') }}
                                </th>
                                <th class="text-secondary font-weight-bolder opacity-7 ps-2">
                                    {{ __('Próxima fecha de pago') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($matriculas as $matricula)
                                <tr>
                                    <td class="align-middle">
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#edit-matricula-modal{{ $matricula->id }}"
                                            class="btn btn-velvet" style="text-decoration: none;">Editar</button>

                                        <form method="post" action="{{ url('/delete/matricula/' . $matricula->id) }}"
                                            style="display:inline">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" data-bs-toggle="modal"
                                                onclick="return confirm('Deseas borrar esta matrícula?')"
                                                class="btn btn-admin-delete" style="text-decoration: none;">Borrar</button>
                                        </form>
                                        <a href="{{ url('/pagos/matricula/' . $matricula->id) }}"
                                            class="btn btn-admin-open" style="text-decoration: none;">Gestión de pagos</a>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="mb-0">{{ $matricula->curso }}
                                        </p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="text-success font-weight-bold mb-0">
                                            ₡{{ number_format($matricula->monto_pago) }}</p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="text-success font-weight-bold mb-0">
                                            ₡{{ number_format($matricula->monto_curso) }}</p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <p class="mb-0">{{ $matricula->fecha_matricula }}
                                        </p>
                                    </td>
                                    <td class="align-middle text-sm">
                                        @if ($matricula->proxima_fecha_pago <= $fechaCostaRica)
                                            <p class=" font-weight-bold mb-0">
                                                <span
                                                    class="badge badge-pill ml-2 badge-date animacion badge-date text-white"
                                                    id="comparison-count">{{ $matricula->proxima_fecha_pago }}</span>
                                            </p>
                                        @else
                                            <p class=" font-weight-bold mb-0">
                                                <span class="badge badge-pill ml-2 badge-date-blue text-white"
                                                    id="comparison-count">{{ $matricula->proxima_fecha_pago }}</span>
                                            </p>
                                        @endif
                                    </td>
                                    @include('admin.estudiantes.matricula.edit')
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
            <a href="{{ url('estudiantes/manage/' . ($item->tipo_estudiante == 'C' ? 'clases' : 'yoga')) }}"
                class="btn btn-velvet w-25">{{ __('Volver') }}</a>
        </div>
    </center>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
