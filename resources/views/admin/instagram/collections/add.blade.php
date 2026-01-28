@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>{{ __('Nueva colección') }}</h3>
            <a href="{{ url('/instagram/collections') }}" class="btn btn-accion">Volver</a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ url('/instagram/collections/store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="input-group input-group-static">
                                    <label>Nombre</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="input-group input-group-static">
                                    <label>Notas (Opcional)</label>
                                    <input type="text" name="notes" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="input-group input-group-static">
                                <label>{{ __('Descripción') }} base (opcional)</label>
                                <textarea name="default_caption" class="form-control" rows="1"></textarea>
                            </div>
                            <small class="text-muted">Se usará como descripción por defecto si un carrusel no define descripción propio.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="input-group input-group-static">
                                <label>Plantilla de caption (Spintax)</label>
                                <select name="caption_template_id" class="form-control">
                                    <option value="">— Sin plantilla —</option>
                                    @foreach ($templates as $tpl)
                                        <option value="{{ $tpl->id }}">{{ $tpl->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-muted">
                                Selecciona una plantilla para generar captions variados automáticamente.
                                <a href="{{ url('/instagram/caption-templates') }}" target="_blank">Gestionar plantillas</a>
                            </small>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-accion">{{ __('Crear colección') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
