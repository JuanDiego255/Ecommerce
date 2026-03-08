<footer class="lp-footer">
    <div class="container">
        <div class="row g-4">

            <!-- Logo y descripción -->
            <div class="col-lg-4 col-md-6">
                @if(isset($tenantinfo->logo) && $tenantinfo->logo)
                    <img src="{{ route('file', $tenantinfo->logo) }}" alt="{{ $tenantinfo->title ?? '' }}"
                         style="height:50px;object-fit:contain;margin-bottom:1rem;filter:brightness(0) invert(1);">
                @endif
                <p style="font-size:.9rem;line-height:1.6;">
                    {{ $tenantinfo->mision ?? '' }}
                </p>
                <!-- Redes sociales -->
                <div class="mt-3">
                    @if(isset($tenantinfo->whatsapp) && $tenantinfo->whatsapp)
                        <a href="https://wa.me/506{{ $tenantinfo->whatsapp }}" target="_blank"
                           class="social-icon" title="WhatsApp">
                            <i class="fa fa-whatsapp"></i>
                        </a>
                    @endif
                    @foreach($social as $sn)
                        @php
                            $icon = 'fa-globe';
                            if (stripos($sn->social_network ?? $sn->name ?? '', 'facebook') !== false)  $icon = 'fa-facebook';
                            elseif (stripos($sn->social_network ?? $sn->name ?? '', 'instagram') !== false) $icon = 'fa-instagram';
                            elseif (stripos($sn->social_network ?? $sn->name ?? '', 'twitter') !== false)   $icon = 'fa-twitter';
                            elseif (stripos($sn->social_network ?? $sn->name ?? '', 'linkedin') !== false)  $icon = 'fa-linkedin';
                            elseif (stripos($sn->social_network ?? $sn->name ?? '', 'tiktok') !== false)    $icon = 'fa-music';
                        @endphp
                        <a href="{{ $sn->url ?? '#' }}" target="_blank" class="social-icon" title="{{ $sn->social_network ?? $sn->name ?? '' }}">
                            <i class="fa {{ $icon }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Navegación -->
            <div class="col-lg-3 col-md-6">
                <h5>Páginas</h5>
                <ul class="list-unstyled" style="line-height:2;">
                    @foreach($sections as $sec)
                        @php
                            $routes = [
                                'inicio'    => '/',
                                'nosotros'  => route('landing.nosotros'),
                                'servicios' => route('landing.servicios'),
                                'faq'       => route('landing.faq'),
                                'blog'      => route('landing.blog'),
                                'contacto'  => route('landing.contacto'),
                            ];
                            $href = $routes[$sec->section_key] ?? '#';
                        @endphp
                        <li>
                            <a href="{{ $href }}">{{ $sec->titulo ?? ucfirst($sec->section_key) }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Contacto -->
            <div class="col-lg-5 col-md-12">
                <h5>Contacto</h5>
                <ul class="list-unstyled" style="line-height:2;font-size:.9rem;">
                    @if(isset($tenantinfo->email) && $tenantinfo->email)
                        <li>
                            <i class="fa fa-envelope-o me-2" style="color:var(--lp-secondary)"></i>
                            <a href="mailto:{{ $tenantinfo->email }}">{{ $tenantinfo->email }}</a>
                        </li>
                    @endif
                    @if(isset($tenantinfo->whatsapp) && $tenantinfo->whatsapp)
                        <li>
                            <i class="fa fa-whatsapp me-2" style="color:var(--lp-secondary)"></i>
                            <a href="https://wa.me/506{{ $tenantinfo->whatsapp }}" target="_blank">
                                +506 {{ $tenantinfo->whatsapp }}
                            </a>
                        </li>
                    @endif
                    @if(isset($settings->landing_direccion) && $settings->landing_direccion)
                        <li>
                            <i class="fa fa-map-marker me-2" style="color:var(--lp-secondary)"></i>
                            {{ $settings->landing_direccion }}
                        </li>
                    @endif
                    @if(isset($settings->landing_horario) && $settings->landing_horario)
                        <li>
                            <i class="fa fa-clock-o me-2" style="color:var(--lp-secondary)"></i>
                            {{ $settings->landing_horario }}
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="border-top mt-4 pt-3 d-flex flex-column flex-md-row justify-content-between align-items-center"
             style="font-size:.82rem;">
            <span>&copy; {{ date('Y') }} {{ $tenantinfo->title ?? '' }}. Todos los derechos reservados.</span>
            <span class="mt-2 mt-md-0">
                <a href="{{ route('privacy.policy') }}" style="color:rgba(255,255,255,.5)">Política de Privacidad</a>
            </span>
        </div>
    </div>
</footer>
