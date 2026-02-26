@extends('layouts.public')

@section('title', 'Estado de Eliminación de Datos — ' . config('app.name'))

@section('content')
<div class="container py-5" style="max-width: 640px;">

    <h1 class="h3 fw-bold mb-1">Estado de Solicitud de Eliminación</h1>
    <p class="text-muted mb-4">Código de confirmación: <code>{{ $code }}</code></p>

    @if(!$record)
        <div class="alert alert-warning">
            No se encontró ninguna solicitud con ese código de confirmación.
        </div>
    @else
        @php
            $badges = [
                'pending'   => ['bg-warning text-dark', 'En proceso'],
                'completed' => ['bg-success text-white', 'Completada'],
                'failed'    => ['bg-danger text-white', 'Error'],
            ];
            [$badgeClass, $badgeLabel] = $badges[$record->status] ?? ['bg-secondary text-white', $record->status];
        @endphp

        <div class="card shadow-sm">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Estado</dt>
                    <dd class="col-sm-8">
                        <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                    </dd>

                    <dt class="col-sm-4">Fecha de solicitud</dt>
                    <dd class="col-sm-8">
                        {{ \Carbon\Carbon::parse($record->created_at)->translatedFormat('d \d\e F \d\e Y, H:i') }}
                    </dd>

                    @if($record->status === 'completed')
                        <dt class="col-sm-4">Procesada el</dt>
                        <dd class="col-sm-8">
                            {{ \Carbon\Carbon::parse($record->updated_at)->translatedFormat('d \d\e F \d\e Y, H:i') }}
                        </dd>
                    @endif
                </dl>
            </div>
        </div>

        @if($record->status === 'completed')
            <div class="alert alert-success mt-4">
                Sus datos han sido eliminados de nuestros sistemas correctamente.
            </div>
        @elseif($record->status === 'pending')
            <div class="alert alert-info mt-4">
                Su solicitud está siendo procesada. Esto puede tardar hasta 30 días hábiles.
            </div>
        @endif
    @endif

    <p class="mt-4 text-muted small">
        Si tiene preguntas, contáctenos a
        <a href="mailto:{{ env('MAIL_FROM_ADDRESS') }}">{{ env('MAIL_FROM_ADDRESS') }}</a>.
    </p>

</div>
@endsection
