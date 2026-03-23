@extends('layouts.admin')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active">Configuración del negocio</li>
@endsection
@section('content')

@include('admin.tenant-info.social-modal')

@foreach ($tenant_info as $item)

{{-- ══ Tab navigation header ══════════════════════════════════════ --}}
<div class="s-card" style="border-radius:var(--radius) var(--radius) 0 0;margin-bottom:0;">
    <div class="s-card-header" style="border-bottom:none;padding-bottom:0;">
        <div class="card-h-icon"><span class="material-icons">settings</span></div>
        <span class="card-h-title">Configuración del negocio</span>
    </div>
    <div style="padding:0 20px;border-bottom:1px solid var(--gray1);overflow-x:auto;">
        <ul class="nav" id="tenantTabs" role="tablist" style="flex-wrap:nowrap;gap:0;border:none;margin:0;">
            <li class="nav-item" role="presentation">
                <button class="ti-tab active" id="t-page-tab" data-bs-toggle="tab"
                    data-bs-target="#t-page" type="button" role="tab">
                    <span class="material-icons">language</span> Página
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="ti-tab" id="t-biz-tab" data-bs-toggle="tab"
                    data-bs-target="#t-biz" type="button" role="tab">
                    <span class="material-icons">store</span> Negocio
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="ti-tab" id="t-media-tab" data-bs-toggle="tab"
                    data-bs-target="#t-media" type="button" role="tab">
                    <span class="material-icons">image</span> Imágenes
                </button>
            </li>
            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
            <li class="nav-item" role="presentation">
                <button class="ti-tab" id="t-content-tab" data-bs-toggle="tab"
                    data-bs-target="#t-content" type="button" role="tab">
                    <span class="material-icons">article</span> Contenido
                </button>
            </li>
            @endif
            <li class="nav-item" role="presentation">
                <button class="ti-tab" id="t-social-tab" data-bs-toggle="tab"
                    data-bs-target="#t-social" type="button" role="tab">
                    <span class="material-icons">share</span> Redes Sociales
                </button>
            </li>
        </ul>
    </div>
</div>

{{-- ══ Main form ══════════════════════════════════════════════════ --}}
<form class="form-horizontal" action="{{ url('tenant-info/update/' . $item->id) }}"
    method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <div class="s-card" style="border-radius:0 0 var(--radius) var(--radius);border-top:none;">
        <div class="tab-content" id="tenantTabsContent">

            {{-- ── Tab 1: Página ───────────────────────────────── --}}
            <div class="tab-pane fade show active" id="t-page" role="tabpanel">
                <div class="s-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="filter-label">Empresa</label>
                            <input value="{{ $item->title ?? '' }}" required type="text"
                                class="filter-input @error('title') is-invalid @enderror"
                                name="title">
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="filter-label">Cintillo</label>
                            <input value="{{ $item->text_cintillo ?? '' }}" type="text"
                                class="filter-input @error('text_cintillo') is-invalid @enderror"
                                name="text_cintillo">
                            @error('text_cintillo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="filter-label">Misión</label>
                            <textarea class="filter-input @error('mision') is-invalid @enderror"
                                name="mision" rows="3"
                                placeholder="Esta misión se mostrará en la sección Misión de la página principal" required>{{ $item->mision ?? '' }}</textarea>
                            @error('mision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="filter-label">Pie de página</label>
                            <input value="{{ $item->footer ?? '' }}" type="text"
                                class="filter-input @error('footer') is-invalid @enderror"
                                name="footer" placeholder="Frase llamativa para el pie de página">
                            @error('footer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
                        <div class="col-md-6">
                            <label class="filter-label">Descuento — texto sección inicio</label>
                            <textarea class="filter-input" name="title_discount" rows="2"
                                placeholder="Descripción para la sección de descuentos">{{ $item->title_discount ?? '' }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="filter-label">Instagram — texto sección inicio</label>
                            <textarea class="filter-input" name="title_instagram" rows="2"
                                placeholder="Descripción para la sección de Instagram">{{ $item->title_instagram ?? '' }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="filter-label">Tendencia — texto sección inicio</label>
                            <textarea class="filter-input" name="title_trend" rows="2"
                                placeholder="Descripción para artículos en tendencia">{{ $item->title_trend ?? '' }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="filter-label">Título suscripción</label>
                            <input value="{{ $item->title_suscrib_a ?? '' }}" type="text"
                                class="filter-input" name="title_suscrib_a"
                                placeholder="Enlace para suscribirse">
                        </div>
                        <div class="col-md-12">
                            <label class="filter-label">Descripción suscripción</label>
                            <input value="{{ $item->description_suscrib ?? '' }}" type="text"
                                class="filter-input" name="description_suscrib"
                                placeholder="Mensaje que incita al usuario a suscribirse">
                        </div>
                        @endif
                        @if ($item->kind_business == 1)
                        <div class="col-md-12">
                            <label class="filter-label">Acerca de</label>
                            <textarea class="filter-input" name="about" rows="3"
                                placeholder="Acerca de la empresa...">{{ $item->about ?? '' }}</textarea>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── Tab 2: Negocio ───────────────────────────────── --}}
            <div class="tab-pane fade" id="t-biz" role="tabpanel">
                <div class="s-card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="filter-label">Precio de envío</label>
                            <input onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                required value="{{ $item->delivery ?? '' }}" type="text"
                                class="filter-input @error('delivery') is-invalid @enderror"
                                name="delivery">
                            @error('delivery')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="filter-label">WhatsApp</label>
                            <input required value="{{ $item->whatsapp ?? '' }}" type="text"
                                class="filter-input @error('whatsapp') is-invalid @enderror"
                                name="whatsapp">
                            @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="filter-label">SINPE Móvil</label>
                            <input onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                required value="{{ $item->sinpe ?? '' }}" type="text"
                                class="filter-input @error('sinpe') is-invalid @enderror"
                                name="sinpe">
                            @error('sinpe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="filter-label">Cuenta bancaria</label>
                            <input onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                value="{{ $item->count ?? '' }}" type="text"
                                class="filter-input @error('count') is-invalid @enderror"
                                name="count">
                            @error('count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="filter-label">E-mail (notificaciones de compra)</label>
                            <input placeholder="Correo para recibir notificaciones cuando se realiza una compra"
                                value="{{ $item->email ?? '' }}" type="email"
                                class="filter-input @error('email') is-invalid @enderror"
                                name="email">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Tab 3: Imágenes ──────────────────────────────── --}}
            <div class="tab-pane fade" id="t-media" role="tabpanel">
                <div class="s-card-body">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="ti-media-slot">
                                @if ($item->logo)
                                    <a href="{{ route('file', $item->logo) }}" target="_blank">
                                        <img src="{{ route('file', $item->logo) }}" class="ti-media-preview" alt="Logo">
                                    </a>
                                @else
                                    <div class="ti-media-empty"><span class="material-icons">business</span></div>
                                @endif
                                <label class="filter-label">Logo</label>
                                <input class="filter-input" type="file" name="logo" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="ti-media-slot">
                                @if ($item->login_image)
                                    <img src="{{ route('file', $item->login_image) }}" class="ti-media-preview" alt="Login">
                                @else
                                    <div class="ti-media-empty"><span class="material-icons">login</span></div>
                                @endif
                                <label class="filter-label">Imagen Login</label>
                                <input class="filter-input" type="file" name="login_image" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="ti-media-slot">
                                @if ($item->logo_ico)
                                    <a href="{{ route('file', $item->logo_ico) }}" target="_blank">
                                        <img src="{{ route('file', $item->logo_ico) }}" class="ti-media-preview" alt="Favicon">
                                    </a>
                                @else
                                    <div class="ti-media-empty"><span class="material-icons">star</span></div>
                                @endif
                                <label class="filter-label">Favicon</label>
                                <input class="filter-input" type="file" name="logo_ico" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label style="display:inline-flex;align-items:center;gap:8px;cursor:pointer;font-size:.82rem;color:var(--red);font-weight:500;">
                                <input type="checkbox" value="1" name="delete_image" {{ old('delete_image') ? 'checked' : '' }}>
                                <span>Eliminar imágenes de esta sección</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Tab 4: Contenido (CKEditor) ─────────────────── --}}
            @if (isset($tenantinfo->tenant) && $tenantinfo->tenant !== 'rutalimon')
            <div class="tab-pane fade" id="t-content" role="tabpanel">
                <div class="s-card-body">
                    <label class="filter-label">Acerca del negocio</label>
                    <textarea id="editor" class="form-control" name="about_us"
                        placeholder="Acerca del negocio...">{{ $tenantinfo->about_us }}</textarea>
                </div>
            </div>
            @endif

            {{-- ── Tab 5: Redes Sociales ────────────────────────── --}}
            <div class="tab-pane fade" id="t-social" role="tabpanel">
                <div class="s-card-body">
                    <div style="display:flex;justify-content:flex-end;margin-bottom:14px;">
                        <button type="button" class="btn btn-primary btn-sm"
                            data-bs-toggle="modal" data-bs-target="#add-tenant-social-modal">
                            <span class="material-icons">add</span> Nueva red social
                        </button>
                    </div>
                    @forelse ($tenantsocial as $social)
                        @include('admin.tenant-info.social-modal-edit')
                        @php
                            if (stripos($social->social_network, 'Facebook') !== false) {
                                $social_logo = 'fab fa-facebook';
                            } elseif (stripos($social->social_network, 'Instagram') !== false) {
                                $social_logo = 'fab fa-instagram';
                            } elseif (stripos($social->social_network, 'Twitter') !== false) {
                                $social_logo = 'fab fa-twitter';
                            } elseif (stripos($social->social_network, 'LinkedIn') !== false) {
                                $social_logo = 'fab fa-linkedin';
                            } elseif (stripos($social->social_network, 'You tube') !== false) {
                                $social_logo = 'fab fa-youtube';
                            } elseif (stripos($social->social_network, 'Wordpress') !== false) {
                                $social_logo = 'fab fa-wordpress';
                            } elseif (stripos($social->social_network, 'Tik tok') !== false) {
                                $social_logo = 'fab fa-tiktok';
                            } else {
                                $social_logo = 'fas fa-link';
                            }
                        @endphp
                        <div class="ti-social-card" style="margin-bottom:8px;">
                            <i class="{{ $social_logo }} ti-social-icon"></i>
                            <span class="ti-social-name">{{ $social->social_network }}</span>
                            <div class="ti-social-actions">
                                <button type="button" class="act-btn ab-neutral" title="Editar"
                                    data-bs-toggle="modal"
                                    data-bs-target="#edit-tenant-social-modal{{ $social->id }}">
                                    <span class="material-icons">edit</span>
                                </button>
                                <form id="deleteForm{{ $social->id }}" method="post"
                                    action="{{ url('/delete/tenant-social/' . $social->id) }}"
                                    style="display:inline">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="button" class="act-btn ab-del" title="Eliminar"
                                        onclick="confirmAndSubmit({{ $social->id }})">
                                        <span class="material-icons">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p style="color:var(--gray3);font-size:.82rem;margin:0;">
                            No hay redes sociales configuradas aún.
                        </p>
                    @endforelse
                </div>
            </div>

        </div>{{-- end tab-content --}}

        {{-- Save button --}}
        <div style="padding:14px 20px;border-top:1px solid var(--gray1);display:flex;justify-content:flex-end;">
            <button type="submit" class="btn btn-primary">
                <span class="material-icons">save</span> Guardar cambios
            </button>
        </div>
    </div>{{-- end s-card --}}
</form>

@endforeach
@endsection

@section('script')
<script>
    function confirmAndSubmit(id) {
        if (confirm('¿Deseas borrar esta red social?')) {
            document.getElementById('deleteForm' + id).submit();
        }
    }

    function confirmAndSubmitCar(id) {
        if (confirm('¿Deseas borrar esta imagen del carousel?')) {
            document.getElementById('deleteFormCarousel' + id).submit();
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        var lazyBackgrounds = document.querySelectorAll('.lazy-background');
        lazyBackgrounds.forEach(function(background) {
            var imageUrl = background.getAttribute('data-background');
            background.style.backgroundImage = 'url(' + imageUrl + ')';
        });
    });

    /* Init CKEditor when the Contenido tab is shown (avoids hidden element issue) */
    var editorInstance = null;
    document.getElementById('t-content-tab') && document.getElementById('t-content-tab').addEventListener('shown.bs.tab', function () {
        if (!editorInstance) {
            ClassicEditor.create(document.querySelector('#editor'), {
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Párrafo', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Título', class: 'ck-heading_heading1' }
                    ]
                },
                removePlugins: ['Image', 'ImageToolbar', 'ImageCaption', 'ImageStyle', 'ImageResize', 'CKFinder'],
                fontSize: { options: [9, 10, 11, 12, 14, 16, 18, 20, 22, 24] },
            }).then(function(editor) {
                editorInstance = editor;
            }).catch(function(error) {
                console.log(error);
            });
        }
    });
</script>
@endsection
