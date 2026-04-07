@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.pacientes.index') }}">Expedientes</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.pacientes.show', $paciente) }}">{{ $paciente->nombre_completo }}</a></li>
    <li class="breadcrumb-item active">Historia clínica</li>
@endsection
@section('content')

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-0">Historia clínica</h4>
            <p style="font-size:.82rem;color:#64748b;margin:0;">{{ $paciente->nombre_completo }} &middot; Exp. {{ $expediente->numero_expediente }}</p>
        </div>
        <a href="{{ route('ecd.pacientes.show', $paciente) }}" class="s-btn-sec">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('ecd.pacientes.updateHistoria', $paciente) }}" method="POST">
        @csrf @method('PUT')

        <div class="row g-3">
            {{-- Conditions checkboxes --}}
            <div class="col-12">
                <div class="surface p-4">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                        Condiciones médicas
                    </div>
                    @php
                        $conditions = [
                            'embarazo'            => ['Embarazo', 'pill-red'],
                            'lactancia'           => ['Lactancia', 'pill-yellow'],
                            'diabetes'            => ['Diabetes', 'pill-red'],
                            'hipertension'        => ['Hipertensión', 'pill-yellow'],
                            'epilepsia'           => ['Epilepsia', 'pill-red'],
                            'problemas_coagulacion' => ['Problemas coagulación', 'pill-red'],
                            'piel_sensible'       => ['Piel sensible', 'pill-yellow'],
                            'queloides'           => ['Queloides', 'pill-yellow'],
                            'rosacea'             => ['Rosácea', 'pill-yellow'],
                            'fuma'                => ['Fumadora/or', 'pill-blue'],
                            'consume_alcohol'     => ['Consume alcohol', 'pill-blue'],
                        ];
                    @endphp
                    <div class="row g-2">
                        @foreach($conditions as $field => [$label, $pillClass])
                            <div class="col-md-3 col-sm-4 col-6">
                                <label class="d-flex align-items-center gap-2 p-2 rounded"
                                       style="cursor:pointer;border:1px solid #e2e8f0;font-size:.85rem;user-select:none;"
                                       id="cond-label-{{ $field }}">
                                    <input type="hidden" name="{{ $field }}" value="0">
                                    <input type="checkbox" name="{{ $field }}" value="1"
                                           id="cond-{{ $field }}"
                                           class="cond-checkbox"
                                           {{ old($field, $expediente->$field) ? 'checked' : '' }}
                                           style="width:16px;height:16px;accent-color:#5e72e4;flex-shrink:0;">
                                    <span>{{ $label }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Medical text fields --}}
            <div class="col-lg-6">
                <div class="surface p-4 h-100">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                        Antecedentes y medicación
                    </div>
                    <div class="mb-3">
                        <label class="filter-label">Alergias conocidas</label>
                        <textarea name="alergias" class="filter-input" rows="3">{{ old('alergias', $expediente->alergias) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="filter-label">Medicamentos actuales</label>
                        <textarea name="medicamentos_actuales" class="filter-input" rows="3">{{ old('medicamentos_actuales', $expediente->medicamentos_actuales) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="filter-label">Condiciones médicas adicionales</label>
                        <textarea name="condiciones_medicas" class="filter-input" rows="3">{{ old('condiciones_medicas', $expediente->condiciones_medicas) }}</textarea>
                    </div>
                    <div>
                        <label class="filter-label">Antecedentes familiares</label>
                        <textarea name="antecedentes_familiares" class="filter-input" rows="3">{{ old('antecedentes_familiares', $expediente->antecedentes_familiares) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Aesthetic & observations --}}
            <div class="col-lg-6">
                <div class="surface p-4 h-100">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                        Antecedentes estéticos y observaciones
                    </div>
                    <div class="mb-3">
                        <label class="filter-label">Antecedentes estéticos / tratamientos previos</label>
                        <textarea name="antecedentes_esteticos" class="filter-input" rows="5">{{ old('antecedentes_esteticos', $expediente->antecedentes_esteticos) }}</textarea>
                    </div>
                    <div>
                        <label class="filter-label">Observaciones generales</label>
                        <textarea name="observaciones_generales" class="filter-input" rows="5">{{ old('observaciones_generales', $expediente->observaciones_generales) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('ecd.pacientes.show', $paciente) }}" class="s-btn-sec">Cancelar</a>
            <button type="submit" class="s-btn-primary">
                <i class="fas fa-save me-1"></i> Guardar historia clínica
            </button>
        </div>
    </form>

@endsection

@push('js')
<script>
    // Highlight checked condition labels
    document.querySelectorAll('.cond-checkbox').forEach(cb => {
        const applyStyle = () => {
            const label = cb.closest('label');
            if (cb.checked) {
                label.style.borderColor = '#5e72e4';
                label.style.background = '#eef2ff';
            } else {
                label.style.borderColor = '#e2e8f0';
                label.style.background = '';
            }
        };
        applyStyle();
        cb.addEventListener('change', applyStyle);
    });
</script>
@endpush
