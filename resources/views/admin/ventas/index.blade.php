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
                                        <option @if ($key == 0) selected @endif
                                            value="{{ $item->id }}" data-service="{{ $item->monto_por_servicio }}"
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
                <div class="card-body">
                    <form class="form-horizontal" action="{{ url('venta/especialista/store') }}" method="post"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <input type="hidden" name="clothing_id" id="clothing_id">
                                <input type="hidden" name="especialista_id" id="especialista_id">
                                <div id="div_porc" class="input-group input-group-lg input-group-outline is-filled my-3">
                                    <label class="form-label">Porcentaje (Servicio)</label>
                                    <input readonly value="" type="number"
                                        class="form-control form-control-lg @error('input_porcentaje') is-invalid @enderror"
                                        name="input_porcentaje" id="input_porcentaje">
                                    @error('input_porcentaje')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group input-group-lg input-group-outline is-filled my-3">
                                            <label class="form-label">Monto de venta</label>
                                            <input value="" required type="number"
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
                            <div class="col-md-4 mb-3">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="input-group is-filled input-group-lg input-group-outline my-3">
                                            <label class="form-label">Monto venta de producto</label>
                                            <input value="" type="number"
                                                class="form-control form-control-lg @error('monto_producto_venta') is-invalid @enderror"
                                                name="monto_producto_venta" id="monto_producto_venta">
                                            @error('monto_producto_venta')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Campo Requerido</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" id="btnCalculate" class="btn btn-admin-open w-100 h8 mt-3"><i
                                                class="material-icons opacity-10 pr-1">calculate</i>
                                        </button>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4 mb-3">
                                <div id="div_sal_serv" class="input-group is-filled input-group-lg input-group-outline my-3">
                                    <label class="form-label">Monto por servicio o salario (Opcional)</label>
                                    <input value="" type="number"
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
                                <div id="div_monto_cli" class="input-group is-filled input-group-lg input-group-outline my-3">
                                    <label class="form-label">Monto Clínica</label>
                                    <input value="" type="number" required
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
                                <div id="div_monto_esp" class="input-group is-filled input-group-lg input-group-outline my-3">
                                    <label class="form-label">Monto Especialista</label>
                                    <input value="" type="number" required
                                        class="form-control form-control-lg @error('monto_especialista') is-invalid @enderror"
                                        name="monto_especialista" readonly id="monto_especialista">
                                    @error('monto_especialista')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group input-group-static">
                                    <label>Tipo de pago</label>
                                    <select id="tipo_pago" name="tipo_pago"
                                        class="form-control form-control-lg @error('tipo_pago') is-invalid @enderror"
                                        autocomplete="tipo_pago" autofocus>
                                        @foreach ($tipos as $key => $item)
                                            <option @if ($key == 0) selected @endif
                                                value="{{ $item->id }}">
                                                {{ $item->tipo }}
                                            </option>
                                        @endforeach
    
                                    </select>
                                    @error('tipo_pago')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <center>
                            <input class="btn btn-velvet" type="submit" value="Realizar venta">
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(document).ready(function() {
            function cargarServicios(especialistaId) {
                $('#select_servicios').empty(); // Limpiar servicios
                $('#input_porcentaje').val(''); // Resetear el input
                var monto_salario = especialistaId.find(':selected').data(
                    'salary');
                var monto_serv = especialistaId.find(':selected').data(
                    'service');
                var mont_salary_serv = 0;
                if (monto_salario > 0) {
                    mont_salary_serv = monto_salario;
                } else if (monto_serv > 0) {
                    mont_salary_serv = monto_serv;
                }
                $('#monto_por_servicio_o_salario').val(mont_salary_serv);
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
                                $('#select_servicios').append('<option value="' + servicio
                                    .servicio_id +
                                    '" data-porcentaje="' + servicio.porcentaje + '">' +
                                    servicio.servicio +
                                    '</option>');
                            });

                            // Seleccionar el primer servicio automáticamente
                            let firstOption = $('#select_servicios option:first');
                            let porcentaje = firstOption.data('porcentaje');
                            $('#input_porcentaje').val(porcentaje);
                            $('#clothing_id').val(firstOption.val());
                        } else {
                            $('#select_servicios').append(
                                '<option value="">No hay servicios disponibles</option>');
                            $('#input_porcentaje').val('');
                        }
                    }
                });
            }

            // Cargar servicios al cargar la página
            let especialistaSeleccionado = $('#select_especialista');
            if (especialistaSeleccionado) {
                cargarServicios(especialistaSeleccionado);
            }
            $('#especialista_id').val($('#select_especialista').val());
            // Cargar servicios al cambiar de especialista
            $('#select_especialista').change(function() {
                cargarServicios($(this));
                $('#especialista_id').val($(this).val());
                $('#monto_clinica').val(0);                
                $('#monto_venta').val('');
                $('#monto_producto_venta').val(0);
                $('#monto_especialista').val(0);
            });

            // Capturar el cambio en el select de servicios
            $('#select_servicios').change(function() {
                let porcentaje = $(this).find(':selected').data(
                    'porcentaje'); // Obtener el porcentaje del servicio seleccionado
                $('#input_porcentaje').val(porcentaje);
                $('#clothing_id').val($(this).val());
                $('#monto_clinica').val(0);
                $('#monto_venta').val('');
                $('#monto_especialista').val(0);
                $('#monto_producto_venta').val(0);
            });
            $('#btnCalculate').click(function() {
                // Aquí va lo que quieres hacer cuando se haga clic
                var monto_venta = parseFloat($('#monto_venta').val()); // Convierte a número decimal
                var porcentaje = parseFloat($('#input_porcentaje').val());
                var monto_producto = parseFloat($('#monto_producto_venta').val());
                var monto_serv_sal = $('#monto_por_servicio_o_salario').val();
                var monto_venta_con_porc = 0;
                var monto_calc_prod_sin_iva = 0;
                var monto_calc_prod = 0;
                var monto_calc_total = 0;
                if (monto_venta <= 0) {
                    Swal.fire({
                        title: "El monto venta no puede ser menor o igual a 0",
                        icon: "warning",
                    });
                    return;
                } else if (monto_venta == null || monto_venta == "") {
                    Swal.fire({
                        title: "El monto venta no puede quedar vacío",
                        icon: "warning",
                    });
                    return;
                }
                if (porcentaje > 0) {
                    //Calculo para el producto
                    monto_venta_con_porc = (monto_venta * (porcentaje / 100));
                    if(monto_producto > 0){
                        monto_calc_prod_sin_iva = monto_producto - (monto_producto * (13 / 100));
                        monto_calc_prod = (monto_calc_prod_sin_iva * (10/100));
                    }
                    $('#monto_clinica').val(monto_venta_con_porc + (monto_calc_prod_sin_iva - monto_calc_prod));
                    $('#monto_especialista').val((monto_venta - monto_venta_con_porc) + monto_calc_prod);
                } else {
                    $('#monto_clinica').val(0);
                    $('#monto_especialista').val(0);
                }               
            });
        });
    </script>
@endsection
