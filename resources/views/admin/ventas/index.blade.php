@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ url('ventas/list') }}">Ventas</a></li>
    <li class="breadcrumb-item active">{{ isset($especialista) ? 'Editar venta' : 'Nueva venta' }}</li>
@endsection
@section('content')

    {{-- Guía rápida para el operador --}}
    <div class="surface p-3 mb-3" style="border-left:4px solid var(--primary,#5e72e4);">
        <p class="fw-semibold mb-1" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.05em;color:var(--primary,#5e72e4);">
            <i class="fas fa-info-circle me-1"></i> ¿Cómo registrar una venta?
        </p>
        <div class="d-flex flex-wrap gap-2 mt-1">
            <span class="s-pill pill-blue">1 · Elige el modo</span>
            <span style="color:#aaa;align-self:center;">→</span>
            <span class="s-pill pill-blue">2 · Selecciona especialista y servicios</span>
            <span style="color:#aaa;align-self:center;">→</span>
            <span class="s-pill pill-blue">3 · Ingresa el monto</span>
            <span style="color:#aaa;align-self:center;">→</span>
            <span class="s-pill pill-blue">4 · Calcula</span>
            <span style="color:#aaa;align-self:center;">→</span>
            <span class="s-pill pill-blue">5 · Guarda</span>
        </div>
        <p class="mb-0 mt-2" style="font-size:.78rem;color:#888;">
            <i class="fas fa-credit-card me-1 text-warning"></i>
            Para pago con <strong>Tarjeta</strong>: el sistema ajusta automáticamente el 13% al presionar Calcular.
        </p>
    </div>

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">{{ isset($especialista) ? 'Editar venta' : 'Nueva venta' }}</h4>
        <div class="d-flex gap-2">
            @if (isset($especialista))
                <a href="{{ url('ventas/especialistas/0') }}" class="s-btn-sec">
                    <i class="fas fa-plus me-1"></i> Nueva venta
                </a>
            @endif
            <a href="{{ url('ventas/list') }}" class="s-btn-sec">
                <i class="fas fa-list me-1"></i> Ver ventas
            </a>
        </div>
    </div>

    <div class="row g-3">

        {{-- Panel izquierdo: Modo y selección --}}
        <div class="col-md-4">
            <div class="surface p-3 h-100">
                <p style="font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:#a0aec0;margin-bottom:.75rem;">Modo de venta</p>

                {{-- Opciones --}}
                <div style="display:grid;gap:6px;margin-bottom:1rem;">
                    <label class="venta-opt" for="is_package">
                        <input type="checkbox" value="1" id="is_package" name="is_package"
                            @if ($especialista !== null && $especialista->especialista_id === null) checked @endif>
                        <span class="venta-opt-text">
                            Solo paquetes o servicios
                            <small>Sin especialista asignado</small>
                        </span>
                    </label>
                    <label class="venta-opt" for="gift_card">
                        <input type="checkbox" value="1" id="gift_card" name="gift_card"
                            @if ($especialista !== null && $especialista->is_gift_card === 1) checked @endif
                            @if ($especialista === null || ($especialista !== null && $especialista->especialista_id !== null)) disabled @endif>
                        <span class="venta-opt-text">
                            Incluir tarjeta de regalo
                            <small>Suma ₡2,500 al monto</small>
                        </span>
                    </label>
                    <label class="venta-opt" for="set_clinica">
                        <input type="checkbox" value="1" id="set_clinica" name="set_clinica">
                        <span class="venta-opt-text">
                            Todo el monto a la clínica
                            <small>El especialista recibe ₡0</small>
                        </span>
                    </label>
                    <label class="venta-opt" for="custom_date">
                        <input type="checkbox" value="1" id="custom_date" name="custom_date">
                        <span class="venta-opt-text">
                            Fecha manual
                            <small>Distinta a la fecha de hoy</small>
                        </span>
                    </label>
                </div>
                <style>
                    .venta-opt {
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        padding: 8px 10px;
                        border-radius: 8px;
                        cursor: pointer;
                        transition: background .15s;
                        border: 1px solid transparent;
                    }
                    .venta-opt:hover { background: #f4f6ff; border-color: #e0e4ff; }
                    .venta-opt input[type=checkbox] {
                        appearance: none;
                        -webkit-appearance: none;
                        width: 17px;
                        height: 17px;
                        min-width: 17px;
                        border: 2px solid #cbd5e0;
                        border-radius: 4px;
                        background: #fff;
                        cursor: pointer;
                        transition: all .15s;
                        position: relative;
                    }
                    .venta-opt input[type=checkbox]:checked {
                        background: #5e72e4;
                        border-color: #5e72e4;
                    }
                    .venta-opt input[type=checkbox]:checked::after {
                        content: '';
                        position: absolute;
                        left: 3px; top: 0px;
                        width: 5px; height: 9px;
                        border: 2px solid #fff;
                        border-top: none; border-left: none;
                        transform: rotate(45deg);
                    }
                    .venta-opt input[type=checkbox]:disabled {
                        opacity: .4;
                        cursor: not-allowed;
                    }
                    .venta-opt-text {
                        display: flex;
                        flex-direction: column;
                        font-size: .83rem;
                        font-weight: 500;
                        color: #2d3748;
                        text-transform: none;
                        letter-spacing: 0;
                        line-height: 1.3;
                    }
                    .venta-opt-text small {
                        font-size: .72rem;
                        color: #a0aec0;
                        font-weight: 400;
                        text-transform: none;
                        letter-spacing: 0;
                    }
                </style>

                {{-- Campos de fecha manual (ocultos por defecto) --}}
                <div id="custom_date_fields" class="d-none mb-3" style="display:grid;gap:10px;">
                    <div>
                        <label class="filter-label">Fecha de la venta</label>
                        <input type="date" class="filter-input @error('fecha_venta') is-invalid @enderror"
                            id="fecha_venta" name="fecha_venta" max="{{ now()->format('Y-m-d') }}"
                            value="{{ old('fecha_venta') }}">
                        @error('fecha_venta')
                            <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                        @enderror
                        <p class="mb-0 mt-1" style="font-size:.75rem;color:#888;" id="arqueo_hint">
                            La venta se registrará en el arqueo que estaba abierto en esa fecha.
                        </p>
                    </div>
                    <div>
                        <label class="filter-label">¿Por qué no es hoy?</label>
                        <textarea class="filter-input @error('motivo_fecha') is-invalid @enderror"
                            id="motivo_fecha" name="motivo_fecha" rows="2"
                            placeholder="Describe el motivo">{{ old('motivo_fecha') }}</textarea>
                        @error('motivo_fecha')
                            <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Separador --}}
                <hr class="my-2">
                <p style="font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:#a0aec0;margin-bottom:.75rem;">Especialista</p>

                <div style="display:grid;gap:10px;">
                    <div>
                        <label class="filter-label">Seleccionar especialista</label>
                        <select id="select_especialista" name="select_especialista"
                            class="filter-input @error('select_especialista') is-invalid @enderror">
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
                                    value="{{ $item->id }}"
                                    data-service="{{ $item->monto_por_servicio }}"
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
                            <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="filter-label">Servicios</label>
                        <select id="select_servicios" name="select_servicios[]"
                            class="@error('select_servicios') is-invalid @enderror"
                            multiple style="width:100%;">
                        </select>
                        @error('select_servicios')
                            <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel derecho: Montos y formulario --}}
        <div class="col-md-8">
            <div class="surface p-3">
                <p style="font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:#a0aec0;margin-bottom:.75rem;">Detalle de la venta</p>

                <form class="form-horizontal" action="{{ url('venta/especialista/store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- Campos ocultos --}}
                    <input type="hidden" name="custom_date" id="custom_date_hidden" value="{{ old('custom_date', 0) }}">
                    <input type="hidden" name="fecha_venta" id="fecha_venta_hidden" value="{{ old('fecha_venta') }}">
                    <input type="hidden" name="motivo_fecha" id="motivo_fecha_hidden" value="{{ old('motivo_fecha') }}">
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

                    <div class="row g-3">
                        {{-- Porcentaje (readonly, auto-llenado) --}}
                        <div class="col-md-2" id="div_porc">
                            <label class="filter-label">% Servicio</label>
                            <input readonly
                                value="{{ old('input_porcentaje', isset($especialista->porcentaje) ? $especialista->porcentaje : '') }}"
                                type="number" class="filter-input @error('input_porcentaje') is-invalid @enderror"
                                name="input_porcentaje" id="input_porcentaje"
                                style="background:#f8f9fa;cursor:default;" placeholder="Auto">
                        </div>

                        {{-- Monto de venta --}}
                        <div class="col-md-4">
                            <label class="filter-label">Monto de venta (₡)</label>
                            <input value="{{ old('monto_venta', isset($especialista->monto_venta) ? $especialista->monto_venta : '0') }}"
                                required type="number" class="filter-input @error('monto_venta') is-invalid @enderror"
                                name="monto_venta" id="monto_venta">
                            @error('monto_venta')
                                <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Monto producto --}}
                        <div class="col-md-4">
                            <label class="filter-label">Monto producto (₡)</label>
                            <input value="{{ old('monto_producto_venta', isset($especialista->monto_producto_venta) ? $especialista->monto_producto_venta : '0') }}"
                                type="number" class="filter-input @error('monto_producto_venta') is-invalid @enderror"
                                name="monto_producto_venta" id="monto_producto_venta">
                            @error('monto_producto_venta')
                                <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Tipo de pago --}}
                        <div class="col-md-4">
                            <label class="filter-label">Tipo de pago</label>
                            <select id="tipo_pago" name="tipo_pago"
                                class="filter-input @error('tipo_pago') is-invalid @enderror">
                                @foreach ($tipos as $key => $item)
                                    @if (isset($especialista) && $especialista->tipo_pago_id == $item->id)
                                        <option value="{{ $item->id }}" selected>{{ $item->tipo }}</option>
                                    @endif
                                    <option @if ($key == 0 && $especialista == null) selected @endif
                                        value="{{ $item->id }}">{{ $item->tipo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_pago')
                                <span class="text-danger" style="font-size:.75rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Calcular --}}
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" id="btnCalculate" class="s-btn-primary w-100"
                                style="height:38px;" title="Calcular montos clínica / especialista">
                                <i class="fas fa-calculator me-1"></i> Calcular
                            </button>
                        </div>

                        {{-- Salario/servicio opcional --}}
                        <div class="col-md-4" id="div_sal_serv">
                            <label class="filter-label">Monto por servicio / salario (₡)</label>
                            <input value="{{ old('monto_por_servicio_o_salario', isset($especialista->monto_por_servicio_o_salario) ? $especialista->monto_por_servicio_o_salario : '0') }}"
                                type="number" class="filter-input @error('monto_por_servicio_o_salario') is-invalid @enderror"
                                name="monto_por_servicio_o_salario" id="monto_por_servicio_o_salario">
                        </div>

                        {{-- Monto clínica (readonly, calculado) --}}
                        <div class="col-md-4" id="div_monto_cli">
                            <label class="filter-label">Monto Clínica (₡)</label>
                            <input value="{{ old('monto_clinica', isset($especialista->monto_clinica) ? $especialista->monto_clinica : '0') }}"
                                type="number" required readonly
                                class="filter-input @error('monto_clinica') is-invalid @enderror"
                                name="monto_clinica" id="monto_clinica"
                                style="background:#f8f9fa;cursor:default;">
                        </div>

                        {{-- Monto especialista (readonly, calculado) --}}
                        <div class="col-md-4" id="div_monto_esp">
                            <label class="filter-label">Monto Especialista (₡)</label>
                            <input value="{{ old('monto_especialista', isset($especialista->monto_especialista) ? $especialista->monto_especialista : '0') }}"
                                type="number" required readonly
                                class="filter-input @error('monto_especialista') is-invalid @enderror"
                                name="monto_especialista" id="monto_especialista"
                                style="background:#f8f9fa;cursor:default;">
                        </div>

                        {{-- Nombre cliente --}}
                        <div class="col-md-12">
                            <label class="filter-label">Nombre del cliente <span class="text-muted">(opcional)</span></label>
                            <input value="{{ old('nombre_cliente', isset($especialista->nombre_cliente) ? $especialista->nombre_cliente : '') }}"
                                type="text" class="filter-input @error('nombre_cliente') is-invalid @enderror"
                                name="nombre_cliente" id="nombre_cliente" placeholder="Ej: Juan Pérez">
                        </div>
                    </div>

                    {{-- Tip visual resultados --}}
                    <div id="resultado_preview" class="mt-3 d-none">
                        <div class="surface p-2" style="border-left:3px solid #48bb78;">
                            <div class="row g-1 text-center">
                                <div class="col-4">
                                    <small class="text-muted d-block">Clínica</small>
                                    <span class="fw-bold text-success" id="preview_clinica">₡0</span>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Especialista</small>
                                    <span class="fw-bold text-primary" id="preview_esp">₡0</span>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">% aplicado</small>
                                    <span class="fw-bold text-danger" id="preview_porc">0%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Los campos con fondo gris se calculan automáticamente.
                        </small>
                        <button type="submit" class="s-btn-primary" style="min-width:140px;">
                            <i class="fas fa-save me-1"></i>
                            {{ isset($especialista) ? 'Guardar cambios' : 'Realizar venta' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
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
                    $('#select_servicios').empty();
                    if (especialistaUpdate == 'N') {
                        $('#input_porcentaje').val('');
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
                        if (choicesServicios) {
                            choicesServicios.destroy();
                            choicesServicios = null;
                        }
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
                                if (selectedIds.includes(servicio.servicio_id.toString())) {
                                    option.selected = true;
                                }
                                selectElement.appendChild(option);
                            });

                            choicesServicios = new Choices(selectElement, {
                                removeItemButton: true,
                                placeholder: true,
                                placeholderValue: especialistaUpdate != 'N' ? null : 'Selecciona servicios',
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
                    $('#is_gift_card').val(chkValueGiftCard ? 1 : 0);
                }
                cargarServicios(especialistaSeleccionado, especialistaUpdate);
            }
            var selectedEsp = !chkValuePack ? $('#select_especialista').val() : null;
            $('#especialista_id').val(selectedEsp);

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
                $('#resultado_preview').addClass('d-none');
            });

            $('#is_package').change(function() {
                var chkValue = $(this).prop('checked');
                $('#monto_clinica').val(0);
                $('#monto_venta').val(0);
                $('#monto_producto_venta').val(0);
                $('#monto_especialista').val(0);
                $('#monto_por_servicio_o_salario').val(0);
                $('#resultado_preview').addClass('d-none');
                if (chkValue) {
                    cargarServicios(null, 'N');
                    $('#especialista_id').val(null);
                    $('#select_especialista').prop('disabled', true);
                    $('#gift_card').prop('disabled', false);
                } else {
                    let esp = $('#select_especialista');
                    cargarServicios(esp, especialistaUpdate);
                    $('#especialista_id').val(esp.val());
                    $('#select_especialista').prop('disabled', false);
                    $('#gift_card').prop('disabled', true);
                }
            });

            $('#gift_card').change(function() {
                var chkValue = $(this).prop('checked');
                const montoActual = parseFloat($('#monto_venta').val()) || 0;
                $('#is_gift_card').val(chkValue ? 1 : 0);
                $('#monto_venta').val(chkValue ? (montoActual + 2500).toFixed(2) : (montoActual - 2500).toFixed(2));
            });

            $('#select_servicios').change(function() {
                let totalPrecio = 0;
                const chkValuePck = $('#is_package').prop('checked');
                const chkGiftCard = $('#gift_card').prop('checked');
                const selectedOptions = $(this).find(':selected');

                if (chkValuePck) {
                    selectedOptions.each(function() {
                        totalPrecio += parseFloat($(this).data('price') || 0);
                    });
                    if (chkGiftCard) totalPrecio += 2500;
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
                        $('#resultado_preview').addClass('d-none');
                    } else {
                        calcularMontos();
                    }
                }
            });

            $('#btnCalculate').click(function() {
                calcularMontos();
            });

            function calcularMontos() {
                var aplica = $('#aplica').val();
                var aplica_113 = $('#aplica_113').val();
                var set_campo_esp = $('#set_campo_esp').val();
                var aplica_prod = $('#aplica_prod').val();
                var aplica_calc_tarjeta = $('#aplica_calc_tarjeta').val();
                var monto_venta = parseFloat($('#monto_venta').val());
                var porcentaje = parseFloat($('#input_porcentaje').val());
                var monto_producto = parseFloat($('#monto_producto_venta').val());
                var monto_serv_sal = parseFloat($('#monto_por_servicio_o_salario').val());
                var tipo_pago = $('#tipo_pago option:selected').text();
                var chkPackage = $('#is_package').prop('checked');
                var chkSetClinica = $('#set_clinica').prop('checked');
                var monto_venta_con_porc = 0;
                var monto_calc_prod = 0;
                var monto_total_cli = 0;
                var monto_total_esp = 0;
                var porc_prod = 0;
                var monto_prod_fijo = monto_producto;

                if (monto_venta <= 0 && monto_producto <= 0) {
                    Swal.fire({
                        title: "Ingresa el monto de venta o el monto del producto para calcular.",
                        icon: "warning",
                    });
                    return;
                }

                if (tipo_pago.trim().toUpperCase() === "TARJETA" && !chkSetClinica) {
                    if (aplica_113 == 1) monto_venta /= 1.13;
                    if (aplica_calc_tarjeta == 1) monto_venta *= 1.13;
                }

                if (monto_producto > 0 && !chkPackage && aplica_prod == 1) {
                    monto_producto /= 1.13;
                    porc_prod = 0.10;
                }

                monto_calc_prod = aplica_prod == 1 ? (monto_producto * porc_prod) : 0;
                monto_venta_con_porc = porcentaje >= 0 ? (monto_venta * (porcentaje / 100)) : 0;
                monto_total_cli = 0;
                monto_total_esp = 0;

                if (aplica_prod == 1 && monto_producto > 0) {
                    monto_total_cli = monto_producto - monto_calc_prod;
                    monto_total_esp = monto_calc_prod;
                }

                if (aplica == 1 || chkPackage) {
                    monto_total_cli += monto_venta_con_porc;
                    monto_total_esp += (monto_venta - monto_venta_con_porc);
                }

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
                            (!chkPackage ? (monto_total_esp - monto_calc_prod + monto_producto) : (monto_total_esp + monto_producto));
                        $('#monto_clinica').val(montoClinica);
                        if (!chkPackage) {
                            $('#monto_especialista').val(monto_calc_prod);
                        }
                    }
                } else {
                    $('#monto_clinica').val(monto_venta + monto_prod_fijo);
                    $('#monto_especialista').val(0);
                }

                // Mostrar preview de resultados
                $('#preview_clinica').text('₡' + parseFloat($('#monto_clinica').val()).toLocaleString('es-CR'));
                $('#preview_esp').text('₡' + parseFloat($('#monto_especialista').val()).toLocaleString('es-CR'));
                $('#preview_porc').text(($('#input_porcentaje').val() || 0) + '%');
                $('#resultado_preview').removeClass('d-none');
            }

            // Fecha manual
            const today = new Date().toISOString().split('T')[0];
            const $chkCustomDate = $('#custom_date');
            const $customFields = $('#custom_date_fields');
            const $fechaVenta = $('#fecha_venta');
            const $motivoFecha = $('#motivo_fecha');
            const $arqueoHint = $('#arqueo_hint');
            const $customDateHidden = $('#custom_date_hidden');
            const $fechaVentaHidden = $('#fecha_venta_hidden');
            const $motivoFechaHidden = $('#motivo_fecha_hidden');

            function syncHiddenFromUI() {
                $customDateHidden.val($chkCustomDate.is(':checked') ? 1 : 0);
                $fechaVentaHidden.val($fechaVenta.val() || '');
                $motivoFechaHidden.val($motivoFecha.val() || '');
            }

            $fechaVenta.attr('max', today);

            function toggleCustomDateFields() {
                const on = $chkCustomDate.is(':checked');
                $customFields.toggleClass('d-none', !on);
                $fechaVenta.prop('disabled', !on).prop('required', on);
                $motivoFecha.prop('disabled', !on);
                if (!on) {
                    $fechaVenta.val('');
                    $motivoFecha.val('').prop('required', false);
                    $arqueoHint.text('La venta se registrará en el arqueo que estaba abierto en esa fecha.');
                }
            }

            $chkCustomDate.on('change', function() {
                toggleCustomDateFields();
                syncHiddenFromUI();
            });

            $fechaVenta.on('change keyup', function() {
                const selected = $(this).val();
                if (selected && selected > today) {
                    $(this).val(today);
                    Swal.fire({ title: "Fecha inválida", text: "No puedes seleccionar una fecha futura.", icon: "warning" });
                }
                const finalVal = $(this).val();
                $motivoFecha.prop('required', finalVal && finalVal !== today);
                $arqueoHint.text(finalVal ?
                    `La venta se registrará en el arqueo del ${finalVal}.` :
                    'La venta se registrará en el arqueo que estaba abierto en esa fecha.');
                syncHiddenFromUI();
            });

            $motivoFecha.on('change keyup', function() { syncHiddenFromUI(); });

            $('form[action="{{ url('venta/especialista/store') }}"]').on('submit', function() { syncHiddenFromUI(); });
        });
    </script>
@endsection
