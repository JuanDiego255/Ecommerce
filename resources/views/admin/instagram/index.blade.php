@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item active">Instagram</li>
@endsection
@section('content')

    @if(session('ok'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('ok') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Header ─────────────────────────────────────────────── --}}
    <div class="surface p-4 mb-1">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span class="material-icons" style="color:#fff;font-size:1.3rem;">photo_camera</span>
            </div>
            <div class="flex-grow-1">
                <h5 class="mb-0" style="font-size:1rem;font-weight:700;color:var(--black);">Instagram</h5>
                <div style="font-size:.78rem;color:var(--gray3);margin-top:.15rem;">
                    Gestión de publicaciones, colecciones y configuración
                </div>
            </div>
            {{-- Account status badge --}}
            @if($account)
                <span class="ig-account-badge">
                    <span class="dot"></span>
                    {{ $account->instagram_username ?? 'Cuenta conectada' }}
                    @if($account->account_type)
                        &nbsp;·&nbsp;<span style="opacity:.7;font-weight:400;">{{ $account->account_type }}</span>
                    @endif
                </span>
                <form method="POST" action="{{ route('instagram.disconnect', $account->id) }}"
                      id="ig-disconnect-form" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="button" class="act-btn ab-red"
                            title="Desconectar cuenta"
                            onclick="confirmDisconnect()">
                        <span class="material-icons">link_off</span>
                    </button>
                </form>
            @else
                <span class="ig-account-badge disconnected">
                    <span class="dot"></span>
                    Sin cuenta conectada
                </span>
                <a href="{{ route('instagram.connect') }}" class="s-btn-primary" style="display:inline-flex;align-items:center;gap:.4rem;white-space:nowrap;">
                    <span class="material-icons" style="font-size:1rem;">add_link</span>
                    Conectar Instagram
                </a>
            @endif
        </div>
    </div>

    {{-- ── Nav cards ────────────────────────────────────────────── --}}
    <div class="row g-3">
        <div class="col-6 col-md-3">
            <a href="{{ url('/instagram/posts') }}" class="ig-nav-card">
                <div class="ig-nav-icon">
                    <span class="material-icons">photo_library</span>
                </div>
                <p class="ig-nav-title">Publicaciones</p>
                <p class="ig-nav-desc">Ver y gestionar posts individuales</p>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ url('/instagram/collections') }}" class="ig-nav-card">
                <div class="ig-nav-icon">
                    <span class="material-icons">view_carousel</span>
                </div>
                <p class="ig-nav-title">Colecciones</p>
                <p class="ig-nav-desc">Organizar carruseles con drag &amp; drop</p>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ url('/instagram/caption-templates') }}" class="ig-nav-card">
                <div class="ig-nav-icon">
                    <span class="material-icons">auto_awesome</span>
                </div>
                <p class="ig-nav-title">Plantillas</p>
                <p class="ig-nav-desc">Captions variados con Spintax</p>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ url('/instagram/caption-settings') }}" class="ig-nav-card">
                <div class="ig-nav-icon">
                    <span class="material-icons">tune</span>
                </div>
                <p class="ig-nav-title">Configuración</p>
                <p class="ig-nav-desc">Hashtags, CTAs y opciones</p>
            </a>
        </div>
    </div>

@endsection
@section('script')
<script>
function confirmDisconnect() {
    Swal.fire({
        title: '¿Desconectar Instagram?',
        text: 'Se eliminará la vinculación con tu cuenta. Los datos existentes no se borran.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, desconectar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#ff3b30',
    }).then((result) => {
        if (result.isConfirmed) document.getElementById('ig-disconnect-form').submit();
    });
}
</script>
@endsection
