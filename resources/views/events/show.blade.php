@extends('layouts.design_ecommerce.frontmain')

@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection

@section('content')
    <div class="container p-t-30 p-b-30">
        {{-- Migas --}}
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-lr-0-lg">
            <a href="{{ url(($prefix == 'aclimate' ? $prefix . '' : '') . '/') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Inicio
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>
            <span class="stext-109 cl4">Evento</span>
        </div>

        {{-- Mensajes --}}
        @if (session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
        @endif

        {{-- ============================
         FILA 1: DETALLE DEL EVENTO
         ============================ --}}
        <div class="row m-t-40">
            <div class="col-12">
                <div class="bor10 p-lr-40 p-t-30 p-b-40 m-lr-0-xl p-lr-15-sm mb-4">
                    <h4 class="mtext-109 clnew p-b-20">Detalles del evento</h4>

                    @if (!empty($event->imagen_premios))
                        <div class="card mb-3">
                            <div class="card-body text-center">
                                <img src="{{ isset($event->imagen_premios) ? route('file', $event->imagen_premios) : url('images/producto-sin-imagen.PNG') }}"
                                    alt="Premios" class="img-fluid rounded w-25">
                                <small class="d-block text-muted mt-2">Imagen de premios (si aplica)</small>
                            </div>
                        </div>
                    @endif

                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <i class="fa fa-calendar me-2 mt-1"></i>
                                <div>
                                    <div class="fw-bold">Fecha de inscripción</div>
                                    <div>
                                        {{ \Illuminate\Support\Carbon::parse($event->fecha_inscripcion)->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex align-items-start">
                                <i class="fa fa-map-marker me-2 mt-1"></i>
                                <div>
                                    <div class="fw-bold">Ubicación</div>
                                    <div>{{ $event->ubicacion }}</div>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex align-items-start">
                                <i class="fa fa-money me-2 mt-1"></i>
                                <div>
                                    <div class="fw-bold">Costo</div>
                                    <div>₡{{ number_format((int) ($event->costo_crc ?? 0), 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (!empty($event->detalles))
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="fw-bold mb-1">Descripción</div>
                                <div class="text-muted" style="white-space: pre-line;">
                                    {{ $event->detalles }}
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Métodos de pago desde el evento --}}
                    @if (!empty($event->cuenta_sinpe) || !empty($event->cuenta_iban))
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="fw-bold mb-2">Métodos de pago</div>
                                @if (!empty($event->cuenta_sinpe))
                                    <div class="mb-2">
                                        <span class="badge bg-success me-2">SINPE Móvil</span>
                                        <span>{{ $event->cuenta_sinpe }}</span>
                                    </div>
                                @endif
                                @if (!empty($event->cuenta_iban))
                                    <div>
                                        <span class="badge bg-primary me-2">Transferencia bancaria (IBAN)</span>
                                        <span>{{ $event->cuenta_iban }}</span>
                                    </div>
                                @endif
                                <small class="text-muted d-block mt-2">
                                    Recuerda adjuntar el comprobante de pago en el formulario.
                                </small>
                            </div>
                        </div>
                    @endif

                    {{-- Categorías disponibles --}}
                    <div class="card">
                        <div class="card-body">
                            <div class="fw-bold mb-2">Categorías</div>
                            @if (($event->categories ?? collect())->count())
                                <ul class="list-unstyled mb-0">
                                    @foreach ($event->categories as $cat)
                                        <li class="mb-2">
                                            <i class="fa fa-tag me-1"></i>
                                            <strong>{{ $cat->nombre }}</strong>
                                            @if (!is_null($cat->edad_min) || !is_null($cat->edad_max))
                                                <small class="text-muted"> —
                                                    {{ $cat->edad_min ?? '0' }}–{{ $cat->edad_max ?? '∞' }} años
                                                </small>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-muted">Aún no hay categorías configuradas.</div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ============================
         FILA 2: FORMULARIO
         ============================ --}}
        <div class="row">
            <div class="col-12">
                <div class="bor10 p-lr-40 p-t-30 p-b-40 m-lr-0-xl p-lr-15-sm">
                    <h4 class="mtext-109 clnew p-b-30">Inscripción al evento</h4>

                    {{-- resumen corto (nombre + costo) --}}
                    <div class="card mb-4">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-1">{{ $event->nombre }}</h5>
                                <small class="text-muted">Fecha de inscripción:
                                    {{ \Illuminate\Support\Carbon::parse($event->fecha_inscripcion)->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold">Costo:</span>
                                <span
                                    class="h5 mb-0">₡{{ number_format((int) ($event->costo_crc ?? 0), 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('registration.store', $event->id) }}" method="POST"
                        enctype="multipart/form-data" autocomplete="off">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="bor8 dis-flex p-l-15 m-b-20">
                                    <input
                                        class="mtext-107 clnew size-114 plh2 p-r-15 @error('nombre') is-invalid @enderror"
                                        type="text" name="nombre" id="nombre"
                                        value="{{ old('nombre', Auth::user()->name ?? '') }}" placeholder="Nombre" required
                                        maxlength="100">
                                </div>
                                @error('nombre')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="bor8 dis-flex p-l-15 m-b-20">
                                    <input
                                        class="mtext-107 clnew size-114 plh2 p-r-15 @error('apellidos') is-invalid @enderror"
                                        type="text" name="apellidos" id="apellidos" value="{{ old('apellidos') }}"
                                        placeholder="Apellidos" required maxlength="120">
                                </div>
                                @error('apellidos')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="bor8 dis-flex p-l-15 m-b-20">
                                    <input
                                        class="mtext-107 clnew size-114 plh2 p-r-15 @error('telefono') is-invalid @enderror"
                                        type="text" name="telefono" id="telefono"
                                        value="{{ old('telefono', Auth::user()->telephone ?? '') }}"
                                        placeholder="Teléfono (WhatsApp)" required>
                                </div>
                                @error('telefono')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="bor8 dis-flex p-l-15 m-b-20">
                                    <input class="mtext-107 clnew size-114 plh2 p-r-15 @error('email') is-invalid @enderror"
                                        type="email" name="email" id="email"
                                        value="{{ old('email', Auth::user()->email ?? '') }}"
                                        placeholder="Correo Electrónico" required maxlength="150">
                                </div>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="bor8 dis-flex p-l-15 m-b-20">
                                    <input
                                        class="mtext-107 clnew size-114 plh2 p-r-15 @error('equipo') is-invalid @enderror"
                                        type="text" name="equipo" id="equipo" value="{{ old('equipo') }}"
                                        placeholder="Equipo o lugar de procedencia (opcional)" maxlength="150">
                                </div>
                                @error('equipo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="bor8 dis-flex p-l-15 m-b-20">
                                    <select name="category_id" id="category_id"
                                        class="mtext-107 clnew size-114 plh2 p-r-15 form-select @error('category_id') is-invalid @enderror"
                                        required>
                                        <option value="">Seleccione categoría</option>
                                        @foreach ($event->categories ?? [] as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->nombre }}
                                                @if (!is_null($cat->edad_min) || !is_null($cat->edad_max))
                                                    ({{ $cat->edad_min ?? '0' }}–{{ $cat->edad_max ?? '∞' }} años)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('category_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="mb-1">Comprobante de pago (JPG/PNG/PDF)</label>
                                <div class="bor8 dis-flex p-l-15 m-b-20">
                                    <input
                                        class="mtext-107 clnew size-114 plh2 p-r-15 @error('comprobante') is-invalid @enderror"
                                        type="file" name="comprobante_pago" id="comprobante_pago"
                                        accept="image/jpeg,image/png,application/pdf" required>
                                </div>
                                @error('comprobante')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check mb-3">
                                    <input id="terminos" class="form-check-input" type="checkbox" name="terminos"
                                        value="1" {{ old('terminos') ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="terminos">
                                        Estoy de acuerdo con los términos y condiciones
                                    </label>
                                </div>
                                @error('terminos')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <h6 class="sakura-color sakura-font mt-2 m-b-25">
                            Realiza la transferencia (SINPE Móvil o bancaria). Adjunta el comprobante para aprobar tu
                            inscripción.
                        </h6>

                        <button type="submit"
                            class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer text-white">
                            Enviar inscripción — ₡{{ number_format((int) ($event->costo_crc ?? 0), 0, ',', '.') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    @include('layouts.inc.design_ecommerce.footer')
@endsection

@section('scripts')
    <script>
        // Límite de 2MB como en el FormRequest
        document.getElementById('comprobante_pago')?.addEventListener('change', function() {
            if (this.files?.length) {
                const f = this.files[0];
                if (f.size > (2 * 1024 * 1024)) {
                    alert('El archivo supera los 2MB permitidos.');
                    this.value = '';
                }
            }
        });
    </script>
@endsection
