<nav class="lp-navbar navbar navbar-expand-lg">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="/">
            @if(isset($tenantinfo->logo) && $tenantinfo->logo)
                <img src="{{ route('file', $tenantinfo->logo) }}" alt="{{ $tenantinfo->title ?? '' }}">
            @else
                <span style="color:#fff;font-weight:700;font-size:1.2rem;">{{ $tenantinfo->title ?? '' }}</span>
            @endif
        </a>

        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#lpNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="lpNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
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
                        $isActive = request()->url() === $href;
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ $href }}">
                            {{ $sec->titulo ?? ucfirst($sec->section_key) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>
