@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    @if(isset($tenantinfo) && $tenantinfo->manage_department == 1)
        <li class="breadcrumb-item"><a href="{{ url('departments') }}">Departamentos</a></li>
        <li class="breadcrumb-item active">{{ $department_name }}</li>
    @else
        <li class="breadcrumb-item active">Categorías</li>
    @endif
@endsection
@section('content')

@include('admin.categories.import')

{{-- Header with dept context + search + CTAs --}}
<div class="s-card" style="margin-bottom:12px;">
    <div class="s-card-header">
        @if(isset($tenantinfo) && $tenantinfo->manage_department == 1)
        <a href="{{ url('departments') }}" class="act-btn ab-neutral" title="Volver a departamentos"
            style="flex-shrink:0;">
            <span class="material-icons">arrow_back</span>
        </a>
        @endif
        <div class="card-h-icon"><span class="material-icons">folder</span></div>
        <span class="card-h-title">{{ $department_name }}
            <span id="cat-count"
                style="font-size:.72rem;font-weight:500;color:var(--gray3);margin-left:4px;">({{ count($categories) }})</span>
        </span>
        <div class="card-h-actions">
            <input type="text" id="cat-search" class="filter-input"
                placeholder="Buscar..." style="width:180px;padding:6px 12px;font-size:.8rem;">
            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
                <button type="button" data-bs-toggle="modal" data-bs-target="#import-product-modal"
                    class="act-btn ab-neutral" title="Importar categorías">
                    <span class="material-icons">upload_file</span>
                </button>
            @endif
            <a href="{{ url('add-category/' . $department_id) }}" class="act-btn ab-add" title="Nueva categoría">
                <span class="material-icons">add</span>
            </a>
        </div>
    </div>
</div>

{{-- Department switcher (only when manage_department is on and there are multiple depts) --}}
@if(isset($tenantinfo) && $tenantinfo->manage_department == 1 && $departments->count() > 1)
<div class="cat-nav-bar" style="margin-bottom:12px;">
    <a href="{{ url('departments') }}" class="cn-back" title="Ver todos los departamentos">
        <span class="material-icons">grid_view</span>
        <span>Departamentos</span>
    </a>
    <div class="cat-nav-sep"></div>
    @foreach($departments as $dept)
    <a href="{{ url('categories/' . $dept->id) }}"
        class="cat-chip {{ $dept->id == $department_id ? 'active' : '' }}">
        {{ $dept->department }}
    </a>
    @endforeach
</div>
@endif

{{-- Category cards --}}
<div class="cat-grid" id="cat-grid">
    @forelse ($categories as $item)
    <div class="cat-card" data-name="{{ strtolower($item->name) }}">
        <div class="cat-card-top">
            <a href="{{ url('/add-item') . '/' . $item->id }}" class="cat-avatar" title="{{ $item->name }}">
                @if (isset($item->image) && $item->image)
                    <img src="{{ route('file', $item->image) }}" alt="{{ $item->name }}">
                @else
                    {{ strtoupper(substr($item->name, 0, 1)) }}
                @endif
            </a>
            <div class="act-group">
                <a class="act-btn ab-neutral" href="{{ url('/edit-category') . '/' . $item->id }}" title="Editar">
                    <span class="material-icons">edit</span>
                </a>
                <form name="delete-category{{ $item->id }}" id="delete-category{{ $item->id }}"
                    method="post" action="{{ url('/delete-category/' . $item->id) }}" style="display:inline">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="button" class="act-btn ab-del" title="Eliminar"
                        onclick="submitForm({{ $item->id }})">
                        <span class="material-icons">delete</span>
                    </button>
                </form>
            </div>
        </div>
        <p class="cat-name">{{ $item->name }}</p>
        <div class="cat-card-actions">
            <a href="{{ url('/add-item') . '/' . $item->id }}" class="cat-view-btn">
                <span class="material-icons">inventory_2</span> Ver productos
            </a>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--gray3);font-size:.85rem;">
        No hay categorías en este departamento.
        <br><a href="{{ url('add-category/' . $department_id) }}" class="btn btn-primary btn-sm" style="margin-top:12px;">
            <span class="material-icons">add</span> Crear primera categoría
        </a>
    </div>
    @endforelse
</div>

@endsection
@section('script')
<script>
function submitForm(itemId) {
    if (confirm('¿Deseas borrar esta categoría?')) {
        document.getElementById('delete-category' + itemId).submit();
    }
}
(function () {
    var input = document.getElementById('cat-search');
    var cards = document.querySelectorAll('#cat-grid .cat-card');
    var counter = document.getElementById('cat-count');
    input.addEventListener('input', function () {
        var q = this.value.toLowerCase().trim();
        var visible = 0;
        cards.forEach(function (card) {
            var match = !q || card.dataset.name.includes(q);
            card.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        counter.textContent = '(' + visible + ')';
    });
}());
</script>
@endsection
