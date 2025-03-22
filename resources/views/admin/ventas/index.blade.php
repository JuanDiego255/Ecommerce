@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <center>
        <h2 class="text-center font-title">
            <strong>{{ __('Gestionar ventas por especialista') }}</strong>
        </h2>
    </center>
    @if (isset($especialista))
        <div class="col-md-12 mb-2">
            <a href="{{ url('ventas/especialistas/0') }}" class="btn btn-velvet w-25">{{ __('Nueva venta') }}</a>
        </div>
    @endif
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
        <div class="col-md-4">
            <div class="card p-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-static">
                                <label>Especialistas</label>
                                <select id="select_especialista" name="select_especialista"
                                    class="form-control form-control-lg @error('select_especialista') is-invalid @enderror"
                                    autocomplete="select_especialista" autofocus>
                                    @foreach ($especialistas as $key => $item)
                                        @if (isset($especialista) && $especialista->especialista_id == $item->id)
                                            <option value="{{ $item->id }}" selected
                                                data-service="{{ $item->monto_por_servicio }}"
                                                data-aplica="{{ $item->aplica_calc }}"
                                                data-apli_tarj="{{ $item->aplica_porc_tarjeta }}"
                                                data-apli_113="{{ $item->aplica_porc_113 }}"
                                                data-apli_prod="{{ $item->aplica_porc_prod }}"
                                                data-salary="{{ $item->salario_base }}">
                                                {{ $item->nombre }}
                                            </option>
                                            @continue
                                        @endif
                                        <option @if ($key == 0 && $especialista == null) selected @endif
                                            value="{{ $item->id }}" data-service="{{ $item->monto_por_servicio }}"
                                            data-aplica="{{ $item->aplica_calc }}"
                                            data-apli_tarj="{{ $item->aplica_porc_tarjeta }}"
                                            data-apli_113="{{ $item->aplica_porc_113 }}"
                                            data-apli_prod="{{ $item->aplica_porc_prod }}"
                                            data-salary="{{ $item->salario_base }}">
                                            {{ $item->nombre }}
                                        </option>
                                    @endforeach


                                </select>
                                @error('select_especialista')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-static">
                                <label>Servicios</label>
                                <select id="select_servicios" name="select_servicios"
                                    class="form-control form-control-lg @error('select_servicios') is-invalid @enderror"
                                    autocomplete="select_servicios" autofocus>


                                </select>
                                @error('select_servicios')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card p-2">
                <div class="card-header">
                    <h6 class="text-muted">Al realizar el pago con tarjeta, se aplica el 13% sobre el monto del
                        especialista. (Se aplica al calcular)</h6>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ url('venta/especialista/store') }}" method="post"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <input type="hidden" name="clothing_id" id="clothing_id">
                                <input type="hidden" name="aplica" id="aplica">
                                <input type="hidden" name="aplica_prod" id="aplica_prod">
                                <input type="hidden" name="aplica_113" id="aplica_113">
                                <input type="hidden" name="aplica_calc_tarjeta" id="aplica_calc_tarjeta">
                                <input type="hidden" name="venta_id" id="venta_id"
                                    value="{{ isset($especialista->id) ? $especialista->id : '' }}">
                                <input type="hidden" name="type" id="type"
                                    value="{{ isset($especialista) ? 'U' : 'S' }}">
                                <input type="hidden" name="especialista_id" id="especialista_id">
                                <div id="div_porc" class="input-group input-group-lg input-group-outline is-filled my-3">
                                    <label class="form-label">Porcentaje (Servicio)</label>
                                    <input readonly
                                        value="{{ old('input_porcentaje', isset($especialista->porcentaje) ? $especialista->porcentaje : '') }}"
                                        type="number"
                                        class="form-control form-control-lg @error('input_porcentaje') is-invalid @enderror"
                                        name="input_porcentaje" id="input_porcentaje">
                                    @error('input_porcentaje')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group input-group-lg input-group-outline is-filled my-3">
                                            <label class="form-label">Monto de venta</label>
                                            <input
                                                value="{{ old('monto_venta', isset($especialista->monto_venta) ? $especialista->monto_venta : '0') }}"
                                                required type="number"
                                                class="form-control form-control-lg @error('monto_venta') is-invalid @enderror"
                                                name="monto_venta" id="monto_venta">
                                            @error('monto_venta')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Campo Requerido</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group is-filled input-group-lg input-group-outline my-3">
                                            <label class="form-label">Monto venta de producto</label>
                                            <input
                                                value="{{ old('monto_producto_venta', isset($especialista->monto_producto_venta) ? $especialista->monto_producto_venta : '0') }}"
                                                type="number"
                                                class="form-control form-control-lg @error('monto_producto_venta') is-invalid @enderror"
                                                name="monto_producto_venta" id="monto_producto_venta">
                                            @error('monto_producto_venta')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Campo Requerido</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="d-flex align-items-end">
                                    <div
                                        class="input-group is-filled input-group-lg input-group-outline my-3 me-2 flex-grow-1">
                                        <label class="form-label">Tipo pago</label>
                                        <select id="tipo_pago" name="tipo_pago"
                                            class="form-control form-control-lg @error('tipo_pago') is-invalid @enderror"
                                            autocomplete="tipo_pago" autofocus>
                                            @foreach ($tipos as $key => $item)
                                                @if (isset($especialista) && $especialista->tipo_pago_id == $item->id)
                                                    <option value="{{ $item->id }}" selected>
                                                        {{ $item->tipo }}
                                                    </option>
                                                @endif
                                                <option @if ($key == 0 && $especialista == null) selected @endif
                                                    value="{{ $item->id }}">{{ $item->tipo }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tipo_pago')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <button type="button" id="btnCalculate" class="btn btn-admin-open h-100">
                                        <i class="material-icons opacity-10">calculate</i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div id="div_sal_serv"
                                    class="input-group is-filled input-group-lg input-group-outline my-3">
                                    <label class="form-label">Monto por servicio o salario (Opcional)</label>
                                    <input
                                        value="{{ old('monto_por_servicio_o_salario', isset($especialista->monto_por_servicio_o_salario) ? $especialista->monto_por_servicio_o_salario : '0') }}"
                                        type="number" readonly
                                        class="form-control form-control-lg @error('monto_por_servicio_o_salario') is-invalid @enderror"
                                        name="monto_por_servicio_o_salario" id="monto_por_servicio_o_salario">
                                    @error('monto_por_servicio_o_salario')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div id="div_monto_cli"
                                    class="input-group is-filled input-group-lg input-group-outline my-3">
                                    <label class="form-label">Monto Clínica</label>
                                    <input
                                        value="{{ old('monto_clinica', isset($especialista->monto_clinica) ? $especialista->monto_clinica : '0') }}"
                                        type="number" required
                                        class="form-control form-control-lg @error('monto_clinica') is-invalid @enderror"
                                        name="monto_clinica" readonly id="monto_clinica">
                                    @error('monto_clinica')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div id="div_monto_esp"
                                    class="input-group is-filled input-group-lg input-group-outline my-3">
                                    <label class="form-label">Monto Especialista</label>
                                    <input
                                        value="{{ old('monto_especialista', isset($especialista->monto_especialista) ? $especialista->monto_especialista : '0') }}"
                                        type="number" required
                                        class="form-control form-control-lg @error('monto_especialista') is-invalid @enderror"
                                        name="monto_especialista" readonly id="monto_especialista">
                                    @error('monto_especialista')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <center>
                            <input class="btn btn-velvet" type="submit"
                                value="{{ isset($especialista) ? 'Guardar cambios' : 'Realizar venta' }}">
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <center>
        <div class="col-md-12 mt-3">
            <a href="{{ url('ventas/list') }}" class="btn btn-velvet w-25">{{ __('Ventas realizadas') }}</a>
        </div>
    </center>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(document).ready(function() {
            var especialistaUpdate = "{{ $especialista->clothing_id ?? 'N' }}";

            function cargarServicios(especialistaId, especialistaUpdate) {

                var monto_salario = especialistaId.find(':selected').data('salary');
                var monto_serv = especialistaId.find(':selected').data('service');
                var aplica_calc = especialistaId.find(':selected').data('aplica');
                var aplica_tarj = especialistaId.find(':selected').data('apli_tarj');
                var aplica_prod = especialistaId.find(':selected').data('apli_prod');
                var aplica_113 = especialistaId.find(':selected').data('apli_113');
 
                var mont_salary_serv = monto_salario > 0 ? monto_salario : monto_serv > 0 ? monto_serv : 0;

                $('#aplica').val(aplica_calc);
                $('#aplica_calc_tarjeta').val(aplica_tarj);
                $('#aplica_prod').val(aplica_prod);
                $('#aplica_113').val(aplica_113);
                $('#select_servicios').empty(); // Limpiar 
                if (especialistaUpdate == 'N') {
                    $('#input_porcentaje').val(''); // Resetear el input
                    $('#monto_por_servicio_o_salario').val(mont_salary_serv);
                }
                $('#div_sal_serv').addClass('is-filled');

                $.ajax({
                    url: "/get-list/especialistas/service/",
                    type: 'GET',
                    data: {
                        especialista_id: especialistaId.val()
                    },
                    success: function(response) {
                        if (response.length > 0) {
                            response.forEach(function(servicio) {
                                $('#select_servicios').append(
                                    `<option value="${servicio.servicio_id}" data-porcentaje="${servicio.porcentaje}">
                            ${servicio.servicio}
                        </option>`
                                );
                            });

                            // Si hay un servicio preseleccionado en PHP, seleccionarlo
                            if (especialistaUpdate != 'N') {
                                $('#select_servicios').val(especialistaUpdate);
                                let firstOption = $('#select_servicios option:first');
                                $('#clothing_id').val(firstOption.val());
                            } else {
                                let firstOption = $('#select_servicios option:first');
                                $('#input_porcentaje').val(firstOption.data('porcentaje'));
                                $('#clothing_id').val(firstOption.val());
                            }
                        } else {
                            $('#select_servicios').append(
                                '<option value="">No hay servicios disponibles</option>');
                            $('#input_porcentaje').val('');
                        }
                    }
                });
            }
            let especialistaSeleccionado = $('#select_especialista');
            if (especialistaSeleccionado) {
                cargarServicios(especialistaSeleccionado, especialistaUpdate);
            }
            $('#especialista_id').val($('#select_especialista').val());
            // Cargar servicios al cambiar de especialista
            $('#select_especialista').change(function() {
                if (especialistaUpdate != 'N') {
                    $('#type').val('S');
                    especialistaUpdate = 'N';
                }
                cargarServicios($(this), especialistaUpdate);
                $('#especialista_id').val($(this).val());
                $('#monto_clinica').val(0);
                $('#monto_venta').val('');
                $('#monto_producto_venta').val(0);
                $('#monto_especialista').val(0);
            });

            // Capturar el cambio en el select de servicios
            $('#select_servicios').change(function() {
                let porcentaje = $(this).find(':selected').data(
                    'porcentaje');
                $('#input_porcentaje').val(porcentaje);
                $('#clothing_id').val($(this).val());
                if (especialistaUpdate == 'N') {
                    $('#monto_clinica').val(0);
                    $('#monto_venta').val('');
                    $('#monto_especialista').val(0);
                    $('#monto_producto_venta').val(0);
                } else {
                    calcularMontos();
                }
            });
            $('#btnCalculate').click(function() {
                // Aquí va lo que quieres hacer cuando se haga clic               
                calcularMontos();
            });

            function calcularMontos() {
                var aplica = $('#aplica').val();
                var aplica_113 = $('#aplica_113').val();
                var aplica_prod = $('#aplica_prod').val();
                var aplica_calc_tarjeta = $('#aplica_calc_tarjeta').val();
                var monto_venta = parseFloat($('#monto_venta').val()); // Convierte a número decimal
                var porcentaje = parseFloat($('#input_porcentaje').val());
                var monto_producto = parseFloat($('#monto_producto_venta').val());
                var monto_serv_sal = $('#monto_por_servicio_o_salario').val();
                var tipo_pago = $('#tipo_pago option:selected').text();
                var monto_venta_con_porc = 0;
                var monto_calc_prod_sin_iva = 0;
                var monto_calc_prod = 0;
                var monto_calc_total = 0;
                var monto_max = 0;
                var iva = 0;
                var porc_prod = 0;
                var descuento = 0;
                var porc_prod = 0;
                var calc_extra = 0;
                if (monto_venta <= 0 && monto_producto <= 0) {
                    Swal.fire({
                        title: "Para realizar una venta debes ingresar el monto de la venta, o el monto del producto",
                        icon: "warning",
                    });
                    return;
                }
                if (tipo_pago.trim().toUpperCase() === "TARJETA") {
                    monto_venta = aplica_113 == 1 ? monto_venta / 1.13 : monto_venta;                    
                    monto_venta = aplica_calc_tarjeta == 1 ? (monto_venta * 0.13) + monto_venta : monto_venta;
                    //$('#monto_venta').val(monto_venta);
                }
                if (monto_producto > 0) {
                    iva = aplica_prod == 1 ? monto_producto * (13 / 100) : 0;
                    porc_prod = aplica_prod == 1 ? 10 / 100 : 0;
                    monto_calc_prod_sin_iva = monto_producto - iva;
                    monto_calc_prod = (monto_calc_prod_sin_iva * porc_prod);
                    calc_extra = aplica_prod == 1 ? monto_calc_prod : 0;
                    monto_max = monto_calc_prod_sin_iva - calc_extra;
                    $('#monto_clinica').val(monto_max);
                }

                if (porcentaje >= 0) {
                    monto_venta_con_porc = (monto_venta * (porcentaje / 100));
                    monto_max = monto_venta_con_porc + monto_max;
                    $('#monto_clinica').val(monto_max);
                    if (aplica == 1) {
                        $('#monto_especialista').val((monto_venta - monto_venta_con_porc - monto_serv_sal) +
                            monto_calc_prod);
                    } else {
                        $('#monto_clinica').val(monto_venta + monto_producto);
                        $('#monto_especialista').val(0);
                    }
                }
                if (monto_serv_sal > 0) {
                    monto_max = monto_max + (monto_venta - monto_serv_sal);
                    $('#monto_clinica').val(monto_max);
                    if (aplica == 1) {
                        $('#monto_especialista').val((monto_venta - (monto_venta - monto_serv_sal)) +
                            monto_calc_prod);
                    } else {
                        $('#monto_clinica').val((monto_venta - (monto_venta - monto_serv_sal)) +
                            monto_calc_prod);
                        $('#monto_especialista').val(0);
                    }
                }
            }
        });
    </script>
@endsection
