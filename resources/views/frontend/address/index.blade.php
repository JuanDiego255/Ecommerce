@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <div class="container">
        <div class="breadcrumb-nav bc3x mt-4">
            <li class="home"><a href="{{ url('/') }}"><i class="fas fa-{{ $icon->home }} me-1"></i></a></li>
            <li class="bread-standard"><a class="location" href="#"><i class="fas fa-{{ $icon->address }} me-1"></i>Mis
                    Direcciones</a></li>
            <li class="bread-standard"><a type="button" data-bs-toggle="modal" data-bs-target="#add-address-modal"><i
                        class="fas fa-plus me-1"></i>Nueva
                    Dirección</a></li>
            <li></li>
        </div>


        <center>

            @include('frontend.address.add')
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

            <div class="card w-100 mb-4">
                <div class="table-responsive">
                    <table id="table" class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Predeterminada</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Dirección</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Dirección 2</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    País</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Provincia</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Ciudad</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Código Postal</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                    Acciones</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($address as $item)
                                <tr>
                                    <form id="myForm" action="{{ url('address/status/' . $item->id) }}" method="POST">
                                        @csrf
                                        <!-- Otros campos del formulario -->
                                        <td class="align-middle text-center">
                                            <label for="checkboxSubmit">
                                                <div class="form-check">
                                                    <input id="checkboxSubmit" class="form-check-input" type="checkbox"
                                                        value="" name="status" onchange="this.form.submit()"
                                                        {{ $item->status == 1 ? 'checked' : '' }}>
                                                </div>
                                            </label>
                                        </td>
                                        <!-- Otros campos del formulario -->
                                    </form>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">{{ $item->address }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">{{ $item->address_two }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">{{ $item->country }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">{{ $item->province }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">{{ $item->city }}</p>
                                    </td>
                                    <td class="align-middle text-xxs text-center">
                                        <p class=" font-weight-bold mb-0">{{ $item->postal_code }}</p>
                                    </td>

                                    <td class="align-middle">
                                        <center>
                                            <button type="button" data-bs-toggle="modal"
                                                data-bs-target="#edit-address-modal{{ $item->id }}"
                                                class="btn btn-velvet" style="text-decoration: none;">Editar</button>

                                            <form method="post" action="{{ url('/delete/address/' . $item->id) }}"
                                                style="display:inline">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit"
                                                    onclick="return confirm('Deseas borrar esta dirección?')"
                                                    class="btn btn-velvet" style="text-decoration: none;">Borrar</button>
                                            </form>
                                        </center>

                                    </td>
                                    @include('frontend.address.edit')
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
