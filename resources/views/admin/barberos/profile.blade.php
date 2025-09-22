@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    {{-- Breadcrumbs --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            @can('barberos.manage')
                <li class="breadcrumb-item"><a href="{{ url('/barberos') }}">Barberos</a></li>
            @endcan
            <li class="breadcrumb-item active" aria-current="page">{{ $barbero->nombre }}</li>
        </ol>
    </nav>

    {{-- Tabs --}}
    <div class="card">
        <div class="card-header pb-0">
            <ul class="nav nav-tabs" id="barberoTabs" role="tablist">
                @php
                    $active = $tab; // 'info','servicios','agenda','calendario'
                @endphp
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $active === 'info' ? 'active' : '' }}"
                        href="{{ route('barberos.show', ['barbero' => $barbero->id, 'tab' => 'info', 'back' => $back]) }}"
                        role="tab">Información</a>
                </li>
                @can('barberos.manage')
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $active === 'servicios' ? 'active' : '' }}"
                            href="{{ route('barberos.show', ['barbero' => $barbero->id, 'tab' => 'servicios', 'back' => $back]) }}"
                            role="tab">Servicios</a>
                    </li>
                @endcan
                @can('barberos.manage')
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $active === 'agenda' ? 'active' : '' }}"
                            href="{{ route('barberos.show', ['barbero' => $barbero->id, 'tab' => 'agenda', 'back' => $back]) }}"
                            role="tab">Agenda</a>
                    </li>
                @endcan

                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $active === 'calendario' ? 'active' : '' }}"
                        href="{{ route('barberos.show', ['barbero' => $barbero->id, 'tab' => 'calendario', 'back' => $back]) }}"
                        role="tab">Calendario</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $active === 'galeria' ? 'active' : '' }}"
                        href="{{ route('barberos.show', ['barbero' => $barbero->id, 'tab' => 'galeria', 'back' => $back]) }}"
                        role="tab">Trabajos</a>
                </li>
                @can('barberos.manage')
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $active === 'stats' ? 'active' : '' }}"
                            href="{{ route('barberos.show', ['barbero' => $barbero->id, 'tab' => 'stats', 'back' => $back, 'start' => $start->toDateString(), 'end' => $end->toDateString()]) }}"
                            role="tab">Estadísticas</a>
                    </li>
                @endcan
            </ul>
        </div>

        <div class="card-body">
            {{-- Contenido de cada tab. Reutilizamos tus vistas existentes --}}
            @if ($active === 'info')
                {{-- Tu vista de datos del barbero (edición en modal, etc.) --}}
                @include('admin.barberos.partials.info', ['barbero' => $barbero])
            @elseif($active === 'servicios')
                {{-- Tu vista services.blade.php --}}
                @include('admin.barberos.partials.servicios', ['barbero' => $barbero])
            @elseif($active === 'agenda')
                {{-- Tu vista agenda.blade.php (bloques + excepciones) --}}
                @include('admin.barberos.partials.agenda', ['barbero' => $barbero])
            @elseif($active === 'calendario')
                {{-- Reutiliza tu fullCalendar del barbero --}}
                @include('admin.barberos.partials.calendario', ['barbero' => $barbero])
            @elseif($active === 'galeria')
                {{-- Reutiliza tu fullCalendar del barbero --}}
                @include('admin.barberos.partials._trabajos', ['barbero' => $barbero])
            @elseif($active === 'stats')
                {{-- Reutiliza tu fullCalendar del barbero --}}
                @include('admin.barberos.partials._stats', [
                    'barbero' => $barbero,
                    'start' => $start,
                    'end' => $end,
                    'stats' => $stats,
                ])
            @endif
        </div>
    </div>
    {{-- Modal: Editar barbero (mismo estilo que usas en admin) --}}
    <div class="modal fade" id="edit-barbero-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar barbero</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal"
                        aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="{{ url('barberos/update/' . $barbero->id) }}" method="post"
                        enctype="multipart/form-data" autocomplete="off">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        @include('admin.barberos.form', ['Modo' => 'editar', 'barbero' => $barbero])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // (Opcional) recuerda el último tab abierto por barbero con localStorage
        (function() {
            const key = 'barbero:lastTab:' + '{{ $barbero->id }}';
            // Si el tab vino por query, guardémoslo
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab) localStorage.setItem(key, tab);

            // Si no vino tab en query, podríamos redirigir al último recordado (descomenta si lo prefieres)
            // if (!tab) {
            //   const last = localStorage.getItem(key);
            //   if (last && ['info','servicios','agenda','calendario'].includes(last)) {
            //     const target = new URL(window.location.href);
            //     target.searchParams.set('tab', last);
            //     window.location.replace(target.toString());
            //   }
            // }
        })();
    </script>
@endsection
