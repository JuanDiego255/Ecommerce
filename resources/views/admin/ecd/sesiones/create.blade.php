@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.pacientes.index') }}">Expedientes</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.pacientes.show', $paciente) }}">{{ $paciente->nombre_completo }}</a></li>
    <li class="breadcrumb-item active">Nueva sesión</li>
@endsection
@section('content')

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-0">Nueva sesión clínica</h4>
            <p style="font-size:.82rem;color:#64748b;margin:0;">{{ $paciente->nombre_completo }}</p>
        </div>
        <a href="{{ route('ecd.pacientes.show', $paciente) }}" class="ph-btn ph-btn-back" title="Volver" data-bs-toggle="tooltip" data-bs-placement="left">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('ecd.sesiones.store', $paciente) }}" method="POST" id="sesionForm">
        @csrf

        <div class="row g-3">
            {{-- Basic session data --}}
            <div class="col-12">
                <div class="surface p-4">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                        Información de la sesión
                    </div>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="filter-label">Título / Tratamiento *</label>
                            <input type="text" name="titulo" class="filter-input"
                                   value="{{ old('titulo') }}" required
                                   placeholder="Ej: Limpieza facial profunda, Mesoterapia capilar...">
                        </div>
                        <div class="col-md-4">
                            <label class="filter-label">Estado</label>
                            <select name="estado" class="filter-input">
                                <option value="borrador" {{ old('estado','borrador') === 'borrador' ? 'selected' : '' }}>Borrador</option>
                                <option value="completada" {{ old('estado') === 'completada' ? 'selected' : '' }}>Completada</option>
                                <option value="cancelada" {{ old('estado') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="filter-label">Fecha de sesión *</label>
                            <input type="date" name="fecha_sesion" class="filter-input"
                                   value="{{ old('fecha_sesion', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="filter-label">Hora inicio</label>
                            <input type="time" name="hora_inicio" class="filter-input"
                                   value="{{ old('hora_inicio') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="filter-label">Hora fin</label>
                            <input type="time" name="hora_fin" class="filter-input"
                                   value="{{ old('hora_fin') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="filter-label">Especialista</label>
                            <select name="especialista_id" class="filter-input">
                                <option value="">— Sin asignar —</option>
                                @foreach($especialistas as $esp)
                                    <option value="{{ $esp->id }}" {{ old('especialista_id') == $esp->id ? 'selected' : '' }}>
                                        {{ $esp->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="filter-label">Plantilla de ficha</label>
                            <select name="plantilla_id" class="filter-input" id="plantillaSelect">
                                <option value="">— Sin plantilla —</option>
                                @foreach($plantillas as $pl)
                                    <option value="{{ $pl->id }}"
                                            data-campos="{{ json_encode($pl->campos) }}"
                                            {{ old('plantilla_id') == $pl->id ? 'selected' : '' }}>
                                        {{ $pl->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dynamic form (rendered from template) --}}
            <div class="col-12" id="dynamicFormContainer" style="display:none;">
                <div class="surface p-4" id="dynamicFormBody">
                    {{-- Filled dynamically by JS --}}
                </div>
            </div>

            {{-- Clinical notes --}}
            <div class="col-lg-6">
                <div class="surface p-4 h-100">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                        Notas clínicas
                    </div>
                    <div class="mb-3">
                        <label class="filter-label">Observaciones pre-sesión</label>
                        <textarea name="observaciones_pre" class="filter-input" rows="3">{{ old('observaciones_pre') }}</textarea>
                    </div>
                    <div>
                        <label class="filter-label">Observaciones post-sesión</label>
                        <textarea name="observaciones_post" class="filter-input" rows="3">{{ old('observaciones_post') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="surface p-4 h-100">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                        Seguimiento
                    </div>
                    <div class="mb-3">
                        <label class="filter-label">Productos utilizados</label>
                        <textarea name="productos_usados" class="filter-input" rows="2">{{ old('productos_usados') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="filter-label">Recomendaciones al paciente</label>
                        <textarea name="recomendaciones" class="filter-input" rows="2">{{ old('recomendaciones') }}</textarea>
                    </div>
                    <div>
                        <label class="filter-label">Próxima cita sugerida</label>
                        <input type="date" name="proxima_cita" class="filter-input" value="{{ old('proxima_cita') }}">
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="surface p-4">
                    <label class="filter-label">Notas internas (no visibles para el paciente)</label>
                    <textarea name="notas_internas" class="filter-input" rows="2">{{ old('notas_internas') }}</textarea>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('ecd.pacientes.show', $paciente) }}" class="s-btn-sec">Cancelar</a>
            <button type="submit" class="s-btn-primary">
                <i class="fas fa-save me-1"></i> Guardar sesión
            </button>
        </div>
    </form>

@endsection

@push('js')
<script>
    const plantillaSelect = document.getElementById('plantillaSelect');
    const container = document.getElementById('dynamicFormContainer');
    const body = document.getElementById('dynamicFormBody');

    function renderPlantilla(campos) {
        if (!campos || !campos.secciones || !campos.secciones.length) {
            container.style.display = 'none';
            return;
        }

        let html = '<div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">Campos de la plantilla</div>';

        campos.secciones.forEach(seccion => {
            if (seccion.titulo) {
                html += `<div style="font-size:.78rem;font-weight:700;color:#475569;margin:1rem 0 .5rem;text-transform:uppercase;letter-spacing:.04em;">${seccion.titulo}</div>`;
            }
            html += '<div class="row g-3">';
            (seccion.campos || []).forEach(campo => {
                const col = campo.ancho === 'completo' ? 'col-12' : (campo.ancho === 'mitad' ? 'col-md-6' : 'col-md-4');
                html += `<div class="${col}">`;
                html += `<label class="filter-label">${campo.etiqueta || campo.key}${campo.requerido ? ' *' : ''}</label>`;
                html += `<input type="hidden" name="tipos[${campo.key}]" value="${campo.tipo}">`;

                if (campo.tipo === 'texto' || campo.tipo === 'numero') {
                    html += `<input type="${campo.tipo === 'numero' ? 'number' : 'text'}" name="respuestas[${campo.key}]" class="filter-input" ${campo.requerido ? 'required' : ''}>`;
                } else if (campo.tipo === 'area') {
                    html += `<textarea name="respuestas[${campo.key}]" class="filter-input" rows="3" ${campo.requerido ? 'required' : ''}></textarea>`;
                } else if (campo.tipo === 'seleccion' || campo.tipo === 'select') {
                    html += `<select name="respuestas[${campo.key}]" class="filter-input" ${campo.requerido ? 'required' : ''}>`;
                    html += `<option value="">— Seleccionar —</option>`;
                    (campo.opciones || []).forEach(op => {
                        html += `<option value="${op}">${op}</option>`;
                    });
                    html += `</select>`;
                } else if (campo.tipo === 'booleano' || campo.tipo === 'boolean') {
                    html += `<select name="respuestas[${campo.key}]" class="filter-input">
                        <option value="">— No indicado —</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>`;
                } else if (campo.tipo === 'fecha') {
                    html += `<input type="date" name="respuestas[${campo.key}]" class="filter-input" ${campo.requerido ? 'required' : ''}>`;
                } else {
                    html += `<input type="text" name="respuestas[${campo.key}]" class="filter-input" ${campo.requerido ? 'required' : ''}>`;
                }
                html += '</div>';
            });
            html += '</div>';
        });

        body.innerHTML = html;
        container.style.display = '';
    }

    plantillaSelect.addEventListener('change', function () {
        const opt = this.selectedOptions[0];
        if (!opt || !opt.dataset.campos) {
            container.style.display = 'none';
            return;
        }
        try {
            renderPlantilla(JSON.parse(opt.dataset.campos));
        } catch(e) {
            container.style.display = 'none';
        }
    });

    // Trigger on page load if old value is set
    if (plantillaSelect.value) {
        plantillaSelect.dispatchEvent(new Event('change'));
    }
</script>
@endpush
