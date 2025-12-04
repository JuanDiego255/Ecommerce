<aside class="social-sharing">
    <ul class="menu-social">
        {{--  @if (isset($tenantinfo->tenant) && $tenantinfo->tenant === 'sakura318')
            <li class="social-item store-item">
                <a href="{{ url('all/products') }}" title="Tienda">
                    <span class="icon-sharing"></span>
                    <span class="label">Tienda</span>
                </a>
            </li>
        @endif --}}

        @foreach ($social_network as $social)
            @php
                $social_logo = null;
                if (stripos($social->social_network, 'Facebook') !== false) {
                    $social_logo = 'fa fa-facebook';
                } elseif (stripos($social->social_network, 'Instagram') !== false) {
                    $social_logo = 'fa fa-instagram';
                }
                if (stripos($social->social_network, 'Twitter') !== false) {
                    $social_logo = 'fa fa-twitter';
                }
                if (stripos($social->social_network, 'You tube') !== false) {
                    $social_logo = 'fab fa-youtube';
                }
                if (stripos($social->social_network, 'Wordpress') !== false) {
                    $social_logo = 'fab fa-wordpress';
                }
                if (stripos($social->social_network, 'Tik tok') !== false) {
                    $social_logo = 'fab fa-tiktok';
                }
            @endphp
            @php
                $mostrar = true;
                $tenant = $tenantinfo->tenant;
                if (
                    stripos($social->social_network, 'Facebook') !== false ||
                    stripos($social->social_network, 'Tik tok') !== false ||
                    stripos($social->social_network, 'X') !== false ||
                    stripos($social->social_network, 'Twitter') !== false ||
                    stripos($social->social_network, 'Strava') !== false ||
                    stripos($social->social_network, 'Wordpress') !== false
                ) {
                    $mostrar = false;
                }
            @endphp
            @if ($mostrar)
                <li class="social-item">
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $social->social_network }}"
                        href="{{ url($social->url) }}" target="blank">
                        <span class="icon-sharing"></span>
                        <span class="label">
                            {{ $social->social_network }}
                        </span>
                    </a>
                </li>
            @endif
        @endforeach
        <li class="social-item"><a target="blank" data-bs-toggle="tooltip" data-bs-placement="top" title="WhatsApp"
                href="{{ url('https://wa.me/506' . $tenantinfo->whatsapp) }}" data-action="share/whatsapp/share">
                <span class="icon-sharing"></span>
                <span class="label">WhatsApp</span>
            </a>
        </li>
        {{-- @if (isset($tenantinfo->tenant) && $tenantinfo->tenant != 'sakura318')
            <li class="social-item newsletter"><a data-bs-toggle="tooltip" data-bs-placement="top" title="Blog"
                    target="blank" href="{{ url('blog/index') }}" data-action="share/whatsapp/share"><span
                        class="screen-reader-text">Whatsapp</span></a></li>
            <li class="social-item"><a type="button" data-bs-toggle="modal" data-bs-target="#add-comment-modal"
                    href="#" data-action="share/whatsapp/share"><span
                        class="screen-reader-text">Whatsapp</span></a>
            </li>
        @endif --}}{{-- 
        <li class="social-item shopping-cart"><a data-bs-toggle="tooltip" data-bs-placement="top" title="Blog"
                target="blank" href="{{ url('blog/index') }}" data-action="share/whatsapp/share"><span
                    class="screen-reader-text">Whatsapp</span></a></li> --}}
        {{-- <li class="social-item"><a type="button" data-bs-toggle="modal" data-bs-target="#add-comment-modal"
                href="#" data-action="share/whatsapp/share"><span class="screen-reader-text">Whatsapp</span></a>
        </li> --}}
        <li class="social-item wishlist-item">
            <a href="{{ url('check/list-fav/' . (Auth::user()->code_love ?? 0)) }}" title="Wishlist">
                <span class="wishlist-badge">{{ $favNumber }}</span>
                <span class="icon-sharing"></span>
                <span class="label">Wishlist</span>
            </a>
        </li>

        <li class="social-item cart-item">
            <a type="button" class="js-show-cart" href="#" data-action="share/whatsapp/share">
                <span class="cart-badge">{{ $cartNumber }}</span>
                <span class="icon-sharing"></span>
                <span class="label">Carrito</span>
            </a>
        </li>
    </ul>
</aside>
