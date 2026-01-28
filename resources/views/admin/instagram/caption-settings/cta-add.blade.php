@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-center font-title"><strong>Nuevo CTA</strong></h2>
            <a href="{{ url('/instagram/caption-settings') }}" class="btn btn-outline-dark">Volver</a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger text-white">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ url('/instagram/caption-settings/ctas/store') }}">
                            @csrf

                            <div class="input-group input-group-static mb-4">
                                <label>Nombre del CTA *</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name') }}"
                                    placeholder="Ej: CTA WhatsApp Principal"
                                    required>
                            </div>

                            <div class="input-group input-group-static mb-4">
                                <label>Texto del CTA * (soporta spintax)</label>
                                <textarea name="cta_text" class="form-control" rows="4"
                                    placeholder="{Escríbenos por DM|Pídelo por WhatsApp|Visita nuestra tienda} para más información 💬"
                                    required>{{ old('cta_text') }}</textarea>
                                <small class="text-muted">
                                    Puedes usar spintax para variaciones: {opción1|opción2|opción3}
                                </small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tipo de CTA *</label>
                                    <select name="type" class="form-control" required>
                                        @foreach ($types as $value => $label)
                                            <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">El tipo ayuda a categorizar tus CTAs</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Peso (probabilidad) *</label>
                                    <input type="number" name="weight" class="form-control"
                                        value="{{ old('weight', 1) }}" min="1" max="100" required>
                                    <small class="text-muted">Mayor peso = mayor probabilidad de selección</small>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-accion">Crear CTA</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Ejemplos de CTAs</h5>
                        <div class="bg-light p-3 rounded mb-3">
                            <strong>DM:</strong><br>
                            <code class="small">{Escríbenos|Envíanos un mensaje} por DM para {pedidos|consultas|más info} 💬</code>
                        </div>
                        <div class="bg-light p-3 rounded mb-3">
                            <strong>WhatsApp:</strong><br>
                            <code class="small">{Pídelo|Ordénalo|Consúltanos} por WhatsApp 📱 Link en bio</code>
                        </div>
                        <div class="bg-light p-3 rounded mb-3">
                            <strong>Tienda:</strong><br>
                            <code class="small">{Visita|Explora} nuestra tienda online 🛍️ {Link en bio|www.mitienda.com}</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
