@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active">Departamentos</li>
@endsection
@section('content')

@include('admin.departments.add')

{{-- Header with search + CTA --}}
<div class="s-card" style="margin-bottom:12px;">
    <div class="s-card-header">
        <div class="card-h-icon"><span class="material-icons">category</span></div>
        <span class="card-h-title">Departamentos <span id="dept-count"
            style="font-size:.72rem;font-weight:500;color:var(--gray3);margin-left:4px;">({{ count($departments) }})</span>
        </span>
        <div class="card-h-actions">
            <input type="text" id="dept-search" class="filter-input"
                placeholder="Buscar..." style="width:190px;padding:6px 12px;font-size:.8rem;">
            <button type="button" data-bs-toggle="modal" data-bs-target="#add-department-modal"
                class="act-btn ab-add" title="Nuevo departamento">
                <span class="material-icons">add</span>
            </button>
        </div>
    </div>
</div>

{{-- Department cards --}}
<div class="cat-grid" id="dept-grid">
    @forelse ($departments as $item)
    <div class="cat-card" data-name="{{ strtolower($item->department) }}">
        <div class="cat-card-top">
            <div class="cat-avatar">
                @if ($item->image)
                    <img src="{{ route('file', $item->image) }}" alt="{{ $item->department }}">
                @else
                    <img src="{{ url('images/producto-sin-imagen.PNG') }}" alt="{{ $item->department }}">
                @endif
            </div>
            <div class="act-group">
                <button type="button" class="act-btn ab-neutral" title="Editar"
                    data-bs-toggle="modal"
                    data-bs-target="#edit-department-modal{{ $item->id }}">
                    <span class="material-icons">edit</span>
                </button>
                <form method="post" action="{{ url('/delete/department/' . $item->id) }}" style="display:inline">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit" class="act-btn ab-del" title="Borrar"
                        onclick="return confirm('¿Deseas borrar este departamento?')">
                        <span class="material-icons">delete</span>
                    </button>
                </form>
            </div>
        </div>
        <p class="cat-name">{{ $item->department }}</p>
        <div class="cat-card-actions">
            <a href="{{ url('categories/' . $item->id) }}" class="cat-view-btn">
                <span class="material-icons">folder_open</span> Ver categorías
            </a>
        </div>
    </div>
    {{-- Edit modal needs $item context --}}
    @include('admin.departments.edit')
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--gray3);font-size:.85rem;">
        No hay departamentos aún.
    </div>
    @endforelse
</div>

@endsection
@section('script')
<script>
(function () {
    var input = document.getElementById('dept-search');
    var cards = document.querySelectorAll('#dept-grid .cat-card');
    var counter = document.getElementById('dept-count');
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
