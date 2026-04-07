@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ecd.pacientes.index') }}">Expedientes</a></li>
    <li class="breadcrumb-item active">Nuevo paciente</li>
@endsection
@section('content')

    <div class="page-header d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Nuevo paciente</h4>
        <a href="{{ route('ecd.pacientes.index') }}" class="s-btn-sec">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('ecd.pacientes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            {{-- Left: personal data --}}
            <div class="col-lg-8">
                <div class="surface p-4 mb-3">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                        Datos personales
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="filter-label">Nombre *</label>
                            <input type="text" name="nombre" class="filter-input" value="{{ old('nombre') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="filter-label">Apellidos *</label>
                            <input type="text" name="apellidos" class="filter-input" value="{{ old('apellidos') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="filter-label">Cédula / ID</label>
                            <input type="text" name="cedula" class="filter-input" value="{{ old('cedula') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="filter-label">Fecha de nacimiento</label>
                            <input type="date" name="fecha_nacimiento" class="filter-input" value="{{ old('fecha_nacimiento') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="filter-label">Sexo *</label>
                            <select name="sexo" class="filter-input" required>
                                <option value="F" {{ old('sexo','F') === 'F' ? 'selected' : '' }}>Femenino</option>
                                <option value="M" {{ old('sexo') === 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="O" {{ old('sexo') === 'O' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="filter-label">Teléfono</label>
                            <input type="tel" name="telefono" class="filter-input" value="{{ old('telefono') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="filter-label">Correo electrónico</label>
                            <input type="email" name="email" class="filter-input" value="{{ old('email') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="filter-label">Ocupación</label>
                            <input type="text" name="ocupacion" class="filter-input" value="{{ old('ocupacion') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="filter-label">Ciudad</label>
                            <input type="text" name="ciudad" class="filter-input" value="{{ old('ciudad') }}">
                        </div>
                        <div class="col-12">
                            <label class="filter-label">Dirección</label>
                            <input type="text" name="direccion" class="filter-input" value="{{ old('direccion') }}">
                        </div>
                    </div>
                </div>

                <div class="surface p-4">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                        Información adicional
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="filter-label">Grupo sanguíneo</label>
                            <select name="grupo_sanguineo" class="filter-input">
                                <option value="">— No especificado —</option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $g)
                                    <option value="{{ $g }}" {{ old('grupo_sanguineo') === $g ? 'selected' : '' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="filter-label">¿Cómo nos conoció?</label>
                            <select name="fuente_referido" class="filter-input">
                                <option value="">— Seleccionar —</option>
                                @foreach(['Redes sociales','Recomendación','Google','Página web','Otro'] as $f)
                                    <option value="{{ $f }}" {{ old('fuente_referido') === $f ? 'selected' : '' }}>{{ $f }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="filter-label">Notas internas</label>
                            <textarea name="notas_internas" class="filter-input" rows="3">{{ old('notas_internas') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: photo --}}
            <div class="col-lg-4">
                <div class="surface p-4 text-center">
                    <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:1rem;">
                        Foto del paciente
                    </div>
                    <img id="previewImg"
                         src="https://ui-avatars.com/api/?name=Nuevo+Paciente&background=5e72e4&color=fff&size=120"
                         style="width:110px;height:110px;border-radius:50%;object-fit:cover;margin-bottom:1rem;">
                    <div>
                        <label class="filter-label">Foto de perfil</label>
                        <input type="file" name="foto_perfil" class="filter-input" accept="image/*" id="fotoInput">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('ecd.pacientes.index') }}" class="s-btn-sec">Cancelar</a>
            <button type="submit" class="s-btn-primary">
                <i class="fas fa-save me-1"></i> Guardar paciente
            </button>
        </div>
    </form>

@endsection

@push('js')
<script>
    document.getElementById('fotoInput').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => document.getElementById('previewImg').src = e.target.result;
        reader.readAsDataURL(file);
    });
</script>
@endpush
