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
                        <div class="col-md-12 mb-1">
                            <div class="form-check">
                                <input class="form-check-input margin-left-checkbox" type="checkbox" value="1"
                                    @if ($especialista !== null && $especialista->especialista_id === null) checked @endif id="is_package" name="is_package">
                                <label class="custom-control-label"
                                    for="customCheck1">{{ __('Vender solo servicios o paquetes') }}</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-1">
                            <div class="form-check">
                                <input @if ($especialista !== null && $especialista->is_gift_card === 1) checked @endif
                                    @if ($especialista === null || ($especialista !== null && $especialista->especialista_id !== null)) disabled @endif
                                    class="form-check-input margin-left-checkbox" type="checkbox" value="1"
                                    id="gift_card" name="gift_card">
                                <label class="custom-control-label"
                                    for="customCheck1">{{ __('Incluir tarjeta de regalo') }}</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-1">
                            <div class="form-check">
                                <input class="form-check-input margin-left-checkbox" type="checkbox" value="1"
                                    id="set_clinica" name="set_clinica">
                                <label class="custom-control-label"
                                    for="customCheck1">{{ __('Asignar todo el monto a la clínica') }}</label>
                            </div>
                        </div>
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
                                                data-set_campo_esp="{{ $item->set_campo_esp }}"
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
                                            data-set_campo_esp="{{ $item->set_campo_esp }}"
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
                        <div class="col-md-12 mb-3 w-100">
                            <div class="input-group input-group-static">
                                <select id="select_servicios" name="select_servicios[]"
                                    class="form-control form-control-lg @error('select_servicios') is-invalid @enderror"
                                    autocomplete="select_servicios" autofocus multiple>


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
                                <input type="hidden" name="set_campo_esp" id="set_campo_esp">
                                <input type="hidden" name="aplica_113" id="aplica_113">
                                <input type="hidden" name="aplica_calc_tarjeta" id="aplica_calc_tarjeta">
                                <input type="hidden" name="is_gift_card" id="is_gift_card"
                                    value="{{ old('input_porcentaje', isset($especialista->is_gift_card) ? $especialista->is_gift_card : '') }}">
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

                            <div class="col-md-3 mb-3">
                                <div id="div_sal_serv"
                                    class="input-group is-filled input-group-lg input-group-outline my-3">
                                    <label class="form-label">Monto por servicio o salario (Opcional)</label>
                                    <input
                                        value="{{ old('monto_por_servicio_o_salario', isset($especialista->monto_por_servicio_o_salario) ? $especialista->monto_por_servicio_o_salario : '0') }}"
                                        type="number"
                                        class="form-control form-control-lg @error('monto_por_servicio_o_salario') is-invalid @enderror"
                                        name="monto_por_servicio_o_salario" id="monto_por_servicio_o_salario">
                                    @error('monto_por_servicio_o_salario')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Campo Requerido</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
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
                            <div class="col-md-3 mb-3">
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
                            <div class="col-md-3 mb-3">
                                <div id="div_monto_esp"
                                    class="input-group is-filled input-group-lg input-group-outline my-3">
                                    <label class="form-label">Nombre Cliente</label>
                                    <input
                                        value="{{ old('nombre_cliente', isset($especialista->nombre_cliente) ? $especialista->nombre_cliente : '') }}"
                                        type="text"
                                        class="form-control form-control-lg @error('nombre_cliente') is-invalid @enderror"
                                        name="nombre_cliente" id="nombre_cliente">
                                    @error('nombre_cliente')
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
            let choicesServicios = null;
            var chkValuePack = $('#is_package').prop('checked');
            var chkValueGiftCard = $('#gift_card').prop('checked');

            function cargarServicios(especialistaId, especialistaUpdate) {
                if (especialistaId !== null && especialistaUpdate !== null) {
                    var monto_salario = especialistaId.find(':selected').data('salary');
                    var set_campo_esp = especialistaId.find(':selected').data('set_campo_esp');
                    var monto_serv = especialistaId.find(':selected').data('service');
                    var aplica_calc = especialistaId.find(':selected').data('aplica');
                    var aplica_tarj = especialistaId.find(':selected').data('apli_tarj');
                    var aplica_prod = especialistaId.find(':selected').data('apli_prod');
                    var aplica_113 = especialistaId.find(':selected').data('apli_113');
                    var mont_salary_serv = monto_salario > 0 ? monto_salario : monto_serv > 0 ? monto_serv : 0;

                    $('#aplica').val(aplica_calc);
                    $('#aplica_calc_tarjeta').val(aplica_tarj);
                    $('#set_campo_esp').val(set_campo_esp);
                    $('#aplica_prod').val(aplica_prod);
                    $('#aplica_113').val(aplica_113);
                    $('#select_servicios').empty(); // Limpiar 
                    if (especialistaUpdate == 'N') {
                        $('#input_porcentaje').val(''); // Resetear el input
                        $('#monto_por_servicio_o_salario').val(mont_salary_serv);
                    }
                    $('#div_sal_serv').addClass('is-filled');
                }

                $.ajax({
                    url: "/get-list/especialistas/service/",
                    type: 'GET',
                    data: {
                        especialista_id: especialistaId !== null ? especialistaId.val() : null
                    },
                    success: function(response) {
                        const selectElement = document.getElementById('select_servicios');
                        // 🔁 Destruir instancia anterior si existe
                        if (choicesServicios) {
                            choicesServicios.destroy();
                            choicesServicios = null;
                        }
                        // Limpiar opciones previas
                        selectElement.innerHTML = '';

                        if (response.length > 0) {
                            let selectedIds = [];

                            if (especialistaUpdate != 'N') {
                                selectedIds = Array.isArray(especialistaUpdate) ?
                                    especialistaUpdate :
                                    especialistaUpdate.toString().split(',');
                            }

                            response.forEach(function(servicio) {
                                const option = document.createElement('option');
                                option.value = servicio.servicio_id;
                                option.textContent = servicio.servicio;
                                option.dataset.porcentaje = servicio.porcentaje;
                                option.dataset.price = servicio.price;
                                // ✅ Marcar como seleccionado si corresponde
                                if (selectedIds.includes(servicio.servicio_id.toString())) {
                                    option.selected = true;
                                }
                                selectElement.appendChild(option);
                            });
                            // ✅ Inicializar nuevamente Choices.js
                            choicesServicios = new Choices(selectElement, {
                                removeItemButton: true,
                                placeholder: true,
                                placeholderValue: especialistaUpdate != 'N' ? null :
                                    'Selecciona servicios',
                                shouldSort: false,
                                searchEnabled: true
                            });

                            let selected = $('#select_servicios option:selected').first();
                            $('#input_porcentaje').val(selected.data('porcentaje'));
                            $('#clothing_id').val(selected.val());

                        } else {
                            selectElement.innerHTML = '<option>No hay servicios disponibles</option>';
                            $('#input_porcentaje').val('');
                        }
                    }
                });

            }
            let especialistaSeleccionado = $('#select_especialista');
            if (especialistaSeleccionado) {
                if (chkValuePack) {
                    $('#especialista_id').val(null);
                    $('#select_especialista').prop('disabled', true);
                    especialistaSeleccionado = null;
                    if (chkValueGiftCard) {
                        $('#is_gift_card').val(1);
                    } else {
                        $('#is_gift_card').val(0);
                    }
                }
                cargarServicios(especialistaSeleccionado, especialistaUpdate);
            }
            var selectedEsp = !chkValuePack ? $('#select_especialista').val() : null;
            $('#especialista_id').val(selectedEsp);
            // Cargar servicios al cambiar de especialista
            $('#select_especialista').change(function() {
                if (especialistaUpdate != 'N') {
                    $('#type').val('S');
                    especialistaUpdate = 'N';
                }
                cargarServicios($(this), especialistaUpdate);
                $('#especialista_id').val($(this).val());
                $('#monto_clinica').val(0);
                $('#monto_venta').val(0);
                $('#monto_producto_venta').val(0);
                $('#monto_especialista').val(0);
            });
            $('#is_package').change(function() {
                var chkValue = $('#is_package').prop('checked');
                $('#monto_clinica').val(0);
                $('#monto_venta').val(0);
                $('#monto_producto_venta').val(0);
                $('#monto_especialista').val(0);
                $('#monto_por_servicio_o_salario').val(0);
                if (chkValue) {
                    cargarServicios(null, 'N');
                    $('#especialista_id').val(null);
                    $('#select_especialista').prop('disabled', true);
                    $('#gift_card').prop('disabled', false);
                } else {
                    let especialistaSeleccionado = $('#select_especialista');
                    if (especialistaSeleccionado) {
                        cargarServicios(especialistaSeleccionado, especialistaUpdate);
                    }
                    $('#especialista_id').val($('#select_especialista').val());
                    $('#select_especialista').prop('disabled', false);
                    $('#gift_card').prop('disabled', true);
                }
            });
            $('#gift_card').change(function() {
                var chkValue = $('#gift_card').prop('checked');
                const montoActual = parseFloat($('#monto_venta').val()) || 0;
                if (chkValue) {
                    $('#is_gift_card').val(1);
                    $('#monto_venta').val((montoActual + 2500).toFixed(2));
                } else {
                    $('#is_gift_card').val(0);
                    $('#monto_venta').val((montoActual - 2500).toFixed(2));
                }
            });
            // Capturar el cambio en el select de servicios
            $('#select_servicios').change(function() {
                let totalPrecio = 0;
                const chkValuePck = $('#is_package').prop('checked');
                const chkGiftCard = $('#gift_card').prop(
                    'checked'); // <-- chequeamos si gift card está activo
                const selectedOptions = $(this).find(':selected');

                if (chkValuePck) {
                    selectedOptions.each(function() {
                        let price = $(this).data('price') || 0;
                        totalPrecio += parseFloat(price);
                    });

                    if (chkGiftCard) {
                        totalPrecio += 2500; // <-- le sumamos los 2500 si está marcada
                    }

                    $('#monto_venta').val(totalPrecio.toFixed(2));
                }

                const selectedValues = $(this).val();
                const selectedCount = selectedValues ? selectedValues.length : 0;

                let porcentaje = selectedOptions.last().data('porcentaje') || 0;
                $('#input_porcentaje').val(porcentaje);
                $('#clothing_id').val(selectedValues);

                if (selectedCount <= 1) {
                    if (especialistaUpdate == 'N') {
                        $('#monto_clinica').val(0);
                        $('#monto_especialista').val(0);
                        $('#monto_producto_venta').val(0);
                    } else {
                        calcularMontos();
                    }
                }
            });


            $('#btnCalculate').click(function() {
                // Aquí va lo que quieres hacer cuando se haga clic               
                calcularMontos();
            });

            function calcularMontos() {
                var aplica = $('#aplica').val();
                var aplica_113 = $('#aplica_113').val();
                var set_campo_esp = $('#set_campo_esp').val();
                var aplica_prod = $('#aplica_prod').val();
                var aplica_calc_tarjeta = $('#aplica_calc_tarjeta').val();
                var monto_venta = parseFloat($('#monto_venta').val()); // Convierte a número decimal
                var porcentaje = parseFloat($('#input_porcentaje').val());
                var monto_producto = parseFloat($('#monto_producto_venta').val());
                var monto_serv_sal = parseFloat($('#monto_por_servicio_o_salario').val());
                var tipo_pago = $('#tipo_pago option:selected').text();
                var chkPackage = $('#is_package').prop('checked');
                var chkSetClinica = $('#set_clinica').prop('checked');
                var monto_venta_con_porc = 0;
                var monto_calc_prod_sin_iva = 0;
                var monto_calc_prod = 0;
                var monto_calc_total = 0;
                var monto_total_cli = 0;
                var monto_total_esp = 0;
                var monto_iva_aplicado = 0;
                var porc_prod = 0;
                var descuento = 0;
                var porc_prod = 0;
                var calc_extra = 0;
                var monto_prod_fijo = 0;
                monto_prod_fijo = monto_producto;
                if (monto_venta <= 0 && monto_producto <= 0) {
                    Swal.fire({
                        title: "Para realizar una venta debes ingresar el monto de la venta, o el monto del producto",
                        icon: "warning",
                    });
                    return;
                }
                // Ajuste de monto_venta si es pago con tarjeta
                if (tipo_pago.trim().toUpperCase() === "TARJETA" && !chkSetClinica) {
                    if (aplica_113 == 1) monto_venta /= 1.13;
                    if (aplica_calc_tarjeta == 1) monto_venta *= 1.13;
                }

                // Ajuste de monto_producto si aplica producto
                if (monto_producto > 0 && !chkPackage && aplica_prod == 1) {
                    monto_producto /= 1.13;
                    porc_prod = 0.10;
                } else {
                    porc_prod = 0;
                }

                // Cálculo de monto relacionado a producto
                monto_calc_prod = aplica_prod == 1 ? (monto_producto * porc_prod) : 0;

                // Cálculo de monto_venta_con_porc si hay porcentaje
                monto_venta_con_porc = porcentaje >= 0 ? (monto_venta * (porcentaje / 100)) : 0;

                // Inicialización de montos
                monto_total_cli = 0;
                monto_total_esp = 0;

                // Si aplica producto
                if (aplica_prod == 1 && monto_producto > 0) {
                    monto_total_cli = monto_producto - monto_calc_prod;
                    monto_total_esp = monto_calc_prod;
                }

                // Si aplica comisión general
                if (aplica == 1 || chkPackage) {
                    monto_total_cli += monto_venta_con_porc;
                    monto_total_esp += (monto_venta - monto_venta_con_porc);
                }

                // Si existe un servicio con saldo
                if (monto_serv_sal > 0) {
                    monto_total_cli += (monto_venta - monto_serv_sal);
                    monto_total_esp += monto_serv_sal;
                }
               
                if (!chkSetClinica) {
                    $('#monto_clinica').val(monto_total_cli);
                    if (set_campo_esp == 1 && !chkPackage) {
                        $('#monto_especialista').val(monto_total_esp);
                    } else {
                        const montoClinica = (monto_venta == 0 && monto_producto > 0 && aplica_prod) ?
                            (monto_producto - monto_calc_prod) :
                            (!chkPackage ? (monto_total_esp - monto_calc_prod + monto_producto) : (monto_total_esp +
                                monto_producto));

                        $('#monto_clinica').val(montoClinica);

                        if (!chkPackage) {
                            $('#monto_especialista').val(monto_calc_prod);
                        }
                    }
                } else {
                    $('#monto_clinica').val(monto_venta + monto_prod_fijo);
                    $('#monto_especialista').val(0);
                }

            }
        });
    </script>
@endsection
