@extends('layouts.admin')

@section('content')

@if (session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- ── Header ──────────────────────────────────────────────── --}}
<div class="s-card" style="margin-bottom:16px;">
    <div class="s-card-header">
        <div class="card-h-icon">
            <span class="material-icons">photo_camera</span>
        </div>
        <span class="card-h-title">Instagram</span>

        {{-- Account badge --}}
        <div style="margin-left:auto;">
            @if($account)
                <span class="ig-account-badge">
                    <span class="dot"></span>
                    {{ $account->instagram_username ?? 'Cuenta conectada' }}
                    @if($account->account_type)
                        &nbsp;·&nbsp;<span style="opacity:.7;font-weight:400;">{{ $account->account_type }}</span>
                    @endif
                </span>
            @else
                <span class="ig-account-badge disconnected">
                    <span class="dot"></span>
                    Sin cuenta conectada
                </span>
            @endif
        </div>
    </div>
    <div class="s-card-body" style="padding:14px 20px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        @if($account)
            <form method="POST" action="{{ route('instagram.disconnect', $account->id) }}" id="ig-disconnect-form" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="button" class="act-btn ab-del" title="Desconectar cuenta de Instagram"
                    onclick="confirmDelete('ig-disconnect-form', '¿Desconectar cuenta de Instagram?')">
                    <span class="material-icons">link_off</span>
                </button>
            </form>
        @else
            <a href="{{ route('instagram.connect') }}" class="act-btn ab-add" title="Conectar Instagram">
                <span class="material-icons">add_link</span>
            </a>
        @endif
    </div>
</div>

{{-- ── Nav cards ───────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px;">
    <a href="{{ url('/instagram/posts') }}" class="ig-nav-card">
        <div class="ig-nav-icon">
            <span class="material-icons">photo_library</span>
        </div>
        <p class="ig-nav-title">Publicaciones</p>
        <p class="ig-nav-desc">Ver y gestionar posts individuales</p>
    </a>

    <a href="{{ url('/instagram/collections') }}" class="ig-nav-card">
        <div class="ig-nav-icon">
            <span class="material-icons">view_carousel</span>
        </div>
        <p class="ig-nav-title">Colecciones</p>
        <p class="ig-nav-desc">Organizar carruseles con drag &amp; drop</p>
    </a>

    <a href="{{ url('/instagram/caption-templates') }}" class="ig-nav-card">
        <div class="ig-nav-icon">
            <span class="material-icons">auto_awesome</span>
        </div>
        <p class="ig-nav-title">Plantillas</p>
        <p class="ig-nav-desc">Captions variados con Spintax</p>
    </a>

    <a href="{{ url('/instagram/caption-settings') }}" class="ig-nav-card">
        <div class="ig-nav-icon">
            <span class="material-icons">tune</span>
        </div>
        <p class="ig-nav-title">Configuración</p>
        <p class="ig-nav-desc">Hashtags, CTAs y opciones</p>
    </a>
</div>

@section('script')
<script>
function confirmDelete(formId, message) {
    Swal.fire({
        title: 'Confirmación', text: message, icon: 'warning',
        showCancelButton: true, confirmButtonText: 'Sí, desconectar',
        cancelButtonText: 'Cancelar', confirmButtonColor: '#ff3b30',
    }).then((result) => { if (result.isConfirmed) document.getElementById(formId).submit(); });
}
</script>
@endsection
