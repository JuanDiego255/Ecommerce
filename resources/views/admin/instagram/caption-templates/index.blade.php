@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-center font-title"><strong>{{ __('Plantillas de Caption') }}</strong></h2>
            <div class="act-group">
                <a href="{{ url('/instagram') }}" class="act-btn ab-neutral" title="Volver a Instagram">
                    <span class="material-icons">arrow_back</span>
                </a>
                <a href="{{ url('/instagram/caption-templates/create') }}" class="act-btn ab-add" title="Nueva plantilla">
                    <span class="material-icons">add</span>
                </a>
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
                                            <form method="POST" action="{{ url('/instagram/caption-templates/' . $t->id) }}"
                                                id="delete-tpl{{ $t->id }}">
                                                @csrf @method('DELETE')
                                            </form>
                                            <div class="act-group">
                                                <a class="act-btn ab-neutral" title="Editar"
                                                    href="{{ url('/instagram/caption-templates/' . $t->id . '/edit') }}">
                                                    <span class="material-icons">edit</span>
                                                </a>
                                                <button type="button" class="act-btn ab-del" title="Eliminar"
                                                    onclick="confirmDelete('delete-tpl{{ $t->id }}', '¿Eliminar esta plantilla?')">
                                                    <span class="material-icons">delete</span>
                                                </button>
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
    <script>
    function confirmDelete(formId, message) {
        Swal.fire({
            title: 'Confirmación', text: message, icon: 'warning',
            showCancelButton: true, confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar', confirmButtonColor: '#ff3b30',
        }).then((result) => { if (result.isConfirmed) document.getElementById(formId).submit(); });
    }
    </script>
    <script src="{{ asset('js/datatables.js') }}"></script>
@endsection
