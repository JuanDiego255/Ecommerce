@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-center font-title"><strong>{{ __('Plantillas de Caption') }}</strong></h2>
            <div class="d-flex gap-2">
                <a href="{{ url('/instagram') }}" class="btn btn-outline-dark">Volver</a>
                <a href="{{ url('/instagram/caption-templates/create') }}" class="btn btn-accion">{{ __('+ Nueva plantilla') }}</a>
            </div>
        </div>

        @if (session('ok'))
            <div class="alert alert-success text-white">{{ session('ok') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger text-white">{{ session('error') }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">¿Qué es Spintax?</h5>
                <p class="text-muted mb-2">
                    Spintax permite crear múltiples variaciones de texto usando la sintaxis <code>{opción1|opción2|opción3}</code>.
                    Cada vez que se genera un caption, el sistema selecciona aleatoriamente una opción de cada bloque.
                </p>
                <div class="bg-light p-3 rounded">
                    <strong>Ejemplo:</strong><br>
                    <code>{Nueva|Hermosa|Linda} {colección|pieza} ✨</code><br>
                    <small class="text-muted">Puede generar: "Nueva colección ✨", "Hermosa pieza ✨", "Linda colección ✨", etc.</small>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @if ($templates->count() == 0)
                    <div class="text-muted">{{ __('Aún no hay plantillas. Crea una para empezar a variar tus publicaciones.') }}</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Vista previa</th>
                                    <th>Estado</th>
                                    <th>Creada</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($templates as $t)
                                    <tr>
                                        <td>{{ $t->id }}</td>
                                        <td><strong>{{ $t->name }}</strong></td>
                                        <td>
                                            <small class="text-muted" style="max-width: 300px; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                {{ Str::limit($t->template_text, 80) }}
                                            </small>
                                        </td>
                                        <td>
                                            @if ($t->is_active)
                                                <span class="badge bg-success">Activa</span>
                                            @else
                                                <span class="badge bg-secondary">Inactiva</span>
                                            @endif
                                        </td>
                                        <td>{{ $t->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a class="btn btn-accion btn-sm"
                                                    href="{{ url('/instagram/caption-templates/' . $t->id . '/edit') }}">
                                                    Editar
                                                </a>
                                                <form method="POST" action="{{ url('/instagram/caption-templates/' . $t->id) }}"
                                                    onsubmit="return confirm('¿Eliminar esta plantilla?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">Eliminar</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $templates->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
@section('script')
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
