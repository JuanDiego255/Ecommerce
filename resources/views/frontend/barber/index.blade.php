@extends('layouts.frontbarber')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    @php
        $heroImg = 'hero-img';
        switch ($tenantinfo->tenant) {
            case 'barberiajp':
                $heroImg = 'hero-img-jp';
                break;
            case 'andresbarberiacr':
                $heroImg = 'hero-img-andres';
                break;
        }
    @endphp
    <main>
        <!--? slider Area Start-->
        <div class="slider-area position-relative fix pb-120 {{ $heroImg }}">
            <div class="slider-active">
                <!-- Single Slider -->
                <div class="single-slider slider-height d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-8 col-lg-9 col-md-11 col-sm-10">
                                <div class="hero__caption">
                                    <span data-animation="fadeInUp"
                                        data-delay="0.2s">{{ isset($tenantinfo->mision) ? $tenantinfo->mision : '' }}</span>
                                    <h1 data-animation="fadeInUp" data-delay="0.5s">
                                        {{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Single Slider -->
            </div>
            <!-- stroke Text -->
            <div class="stock-text">
                <h2>Confianza en cada detalle</h2>
                <h2>Confianza en cada detalle</h2>
            </div>
            <!-- Arrow -->
            <div class="thumb-content-box">
                <div class="thumb-content">
                    <h3>Ir a nuestra tienda online</h3>
                    <a href="{{ url('catalogo/barber') }}"> <i class="fas fa-long-arrow-alt-right"></i></a>
                </div>
            </div>
        </div>
        <!--? Team Start -->
        <div class="team-area pb-120 pt-60" id="reservation">
            <div class="container">
                <!-- Section Tittle -->
                <div class="row justify-content-center">
                    <div class="col-xl-8 col-lg-8 col-md-11 col-sm-11">
                        <div class="section-tittle text-center mb-60">
                            <span>Nuestro equipo profesional</span>
                            <h2>Los mejores en lo que hacen</h2>
                        </div>
                    </div>
                </div>

                {{-- Desktop layout (md and above) --}}
                <div class="d-none d-md-flex justify-content-center flex-wrap" style="gap:32px;">
                    @if (isset($barbers))
                        @foreach ($barbers as $item)
                            <a href="{{ url('/barberos/' . $item->id . '/agendar/') }}"
                               class="barber-card-desktop"
                               style="text-decoration:none;display:flex;flex-direction:column;
                                      width:260px;border-radius:12px;overflow:hidden;
                                      background:#111;border:1px solid rgba(255,255,255,.08);
                                      transition:transform .3s ease,box-shadow .3s ease;position:relative;">
                                <div style="position:relative;overflow:hidden;height:300px;">
                                    <img src="{{ isset($item->photo_path) ? route('file', $item->photo_path) : url('images/barber.PNG') }}"
                                         alt="{{ $item->nombre }}"
                                         style="width:100%;height:100%;object-fit:cover;object-position:top;
                                                transition:transform .4s ease;">
                                    <div style="position:absolute;inset:0;
                                                background:linear-gradient(to top, rgba(0,0,0,.85) 30%, transparent 70%);"></div>
                                    <div style="position:absolute;bottom:0;left:0;right:0;padding:20px 20px 16px;">
                                        <p style="color:#fff;font-size:1.15rem;font-weight:700;margin:0 0 4px;
                                                  letter-spacing:.5px;text-shadow:0 1px 4px rgba(0,0,0,.6);">
                                            {{ $item->nombre }}
                                        </p>
                                        <span style="font-size:.75rem;color:var(--btn_cart);text-transform:uppercase;
                                                     letter-spacing:2px;font-weight:700;">
                                            Barbero profesional
                                        </span>
                                    </div>
                                </div>
                                <div style="padding:16px 20px;display:flex;align-items:center;justify-content:space-between;
                                            border-top:1px solid rgba(255,255,255,.08);">
                                    <span style="color:#bbb;font-size:.82rem;letter-spacing:.5px;">
                                        <i class="fas fa-calendar-check mr-1" style="color:var(--btn_cart);"></i>
                                        Reservar cita
                                    </span>
                                    <span style="background:var(--btn_cart);color:var(--btn_cart_text);
                                                 font-size:.78rem;font-weight:700;padding:6px 14px;border-radius:20px;
                                                 text-transform:uppercase;letter-spacing:1px;">
                                        Reservar &rsaquo;
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>

                {{-- Mobile layout (sm and below) --}}
                <div class="d-flex d-md-none justify-content-center flex-wrap" style="gap:20px;">
                    @if (isset($barbers))
                        @foreach ($barbers as $item)
                            <a href="{{ url('/barberos/' . $item->id . '/agendar/') }}"
                               style="display:flex;flex-direction:column;align-items:center;
                                      text-decoration:none;width:110px;">
                                <img src="{{ isset($item->photo_path) ? route('file', $item->photo_path) : url('images/barber.PNG') }}"
                                     alt="{{ $item->nombre }}"
                                     style="width:88px;height:88px;border-radius:50%;object-fit:cover;object-position:top;
                                            border:2px solid var(--btn_cart);margin-bottom:10px;">
                                <p style="color:#fff;font-size:.88rem;font-weight:600;margin:0 0 6px;
                                          text-align:center;white-space:nowrap;overflow:hidden;
                                          text-overflow:ellipsis;width:100%;">
                                    {{ $item->nombre }}
                                </p>
                                <span style="font-size:.7rem;color:var(--btn_cart);text-transform:uppercase;
                                             letter-spacing:1px;font-weight:700;">
                                    Reservar &rsaquo;
                                </span>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <!-- Team End -->

        <style>
            .barber-card-desktop:hover {
                transform: translateY(-6px);
                box-shadow: 0 16px 40px rgba(0,0,0,.5), 0 0 0 1px var(--btn_cart);
            }
            .barber-card-desktop:hover img {
                transform: scale(1.05);
            }
        </style>
        <!-- slider Area End-->
        <!--? About Area Start -->
        <section class="about-area section-padding30 position-relative" id="about">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-11">
                        <!-- about-img -->
                        <div class="about-img ">
                            <img src="{{ route('file', isset($tenantinfo->login_image) ? $tenantinfo->login_image : '') }}"
                                alt="">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="about-caption">
                            <!-- Section Tittle -->
                            <div class="section-tittle section-tittle3 mb-35">
                                <span>Acerca de nosotros...</span>
                                <h2>Años de experiencia...</h2>
                            </div>
                            <p class="mb-30 pera-bottom">
                                {!! isset($tenantinfo->about_us) ? $tenantinfo->about_us : '' !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- About Shape -->
            <div class="about-shape">
                <img src="{{ asset('/barber/barber/img/gallery/about-shape.png') }}" alt="">
            </div>
        </section>
        <!-- About-2 Area End -->
        <!--? Services Area Start -->
        @if (isset($service_categories) && $service_categories->count() > 0)
            <section class="service-area pb-120" id="services">
                <div class="container">
                    <!-- Section Tittle -->
                    <div class="row d-flex justify-content-center">
                        <div class="col-xl-7 col-lg-8 col-md-11 col-sm-11">
                            <div class="section-tittle text-center mb-90">
                                <span>Lo que ofrecemos</span>
                                <h2>Nuestras categorías de servicios</h2>
                            </div>
                        </div>
                    </div>
                    <!-- Category cards -->
                    <div class="row justify-content-center">
                        @foreach ($service_categories as $category)
                            <div class="col-xl-4 col-lg-4 col-md-6">
                                <div class="services-caption text-center mb-30" style="cursor:pointer;"
                                    data-toggle="modal" data-target="#categoryModal{{ $category->id }}">
                                    <div class="service-icon">
                                        <i class="fas fa-scissors"></i>
                                    </div>
                                    <div class="service-cap">
                                        <h4>
                                            <a href="#" data-toggle="modal"
                                                data-target="#categoryModal{{ $category->id }}"
                                                onclick="return false;">
                                                {{ $category->nombre }}
                                            </a>
                                        </h4>
                                        @if ($category->descripcion)
                                            <p>{{ $category->descripcion }}</p>
                                        @endif
                                        <span class="btn-style-1 btn btn-sm mt-2"
                                            style="background:var(--btn_cart);color:var(--btn_cart_text);border:none;">
                                            Ver servicios
                                            <i class="fas fa-chevron-right ml-1"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal for this category -->
                            <div class="modal fade" id="categoryModal{{ $category->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="categoryModalLabel{{ $category->id }}"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <div class="modal-content"
                                        style="background:#1a1a1a;border:1px solid #333;border-radius:8px;">
                                        <div class="modal-header"
                                            style="border-bottom:1px solid #333;background:var(--navbar);">
                                            <h5 class="modal-title"
                                                id="categoryModalLabel{{ $category->id }}"
                                                style="color:var(--navbar_text);font-weight:700;letter-spacing:1px;">
                                                <i class="fas fa-scissors mr-2"></i>
                                                {{ strtoupper($category->nombre) }}
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close"
                                                style="color:var(--navbar_text);opacity:1;">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="padding:30px;">
                                            @if ($category->servicios->count() > 0)
                                                <div class="row">
                                                    @foreach ($category->servicios as $servicio)
                                                        <div class="col-md-6 mb-4">
                                                            <div style="background:#222;border-radius:8px;padding:20px;border:1px solid #333;height:100%;">
                                                                @if ($servicio->image)
                                                                    <div class="mb-3 text-center">
                                                                        <img src="{{ route('file', $servicio->image) }}"
                                                                            alt="{{ $servicio->nombre }}"
                                                                            style="width:100%;height:160px;object-fit:cover;border-radius:6px;">
                                                                    </div>
                                                                @else
                                                                    <div class="mb-3 text-center"
                                                                        style="height:60px;line-height:60px;">
                                                                        <i class="fas fa-cut"
                                                                            style="font-size:2rem;color:var(--btn_cart);"></i>
                                                                    </div>
                                                                @endif
                                                                <h6 style="color:#fff;font-weight:700;margin-bottom:8px;">
                                                                    {{ $servicio->nombre }}
                                                                </h6>
                                                                @if ($servicio->descripcion)
                                                                    <p style="color:#aaa;font-size:.85rem;margin-bottom:10px;">
                                                                        {{ $servicio->descripcion }}
                                                                    </p>
                                                                @endif
                                                                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:auto;">
                                                                    @if ($servicio->base_price_cents > 0)
                                                                        <span style="color:var(--btn_cart);font-weight:700;font-size:1rem;">
                                                                            ₡{{ number_format($servicio->base_price_cents / 100, 0, ',', '.') }}
                                                                        </span>
                                                                    @else
                                                                        <span style="color:#888;font-size:.85rem;">Precio a consultar</span>
                                                                    @endif
                                                                    <span style="color:#888;font-size:.8rem;">
                                                                        <i class="fas fa-clock mr-1"></i>
                                                                        {{ $servicio->duration_minutes }} min
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-center" style="color:#888;">
                                                    No hay servicios disponibles en esta categoría.
                                                </p>
                                            @endif
                                        </div>
                                        <div class="modal-footer" style="border-top:1px solid #333;">
                                            <a href="#reservation"
                                                class="btn"
                                                style="background:var(--btn_cart);color:var(--btn_cart_text);border:none;font-weight:700;"
                                                data-dismiss="modal">
                                                Reservar ahora
                                            </a>
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End modal -->
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
        <!-- Services Area End -->

        <!-- Best Pricing Area Start -->
        {{--  <div class="best-pricing section-padding2 position-relative">
            <div class="container">
                <div class="row justify-content-end">
                    <div class="col-xl-7 col-lg-7">
                        <div class="section-tittle mb-50">
                            <span>Our Best Pricing</span>
                            <h2>We provide best price<br> in the city!</h2>
                        </div>
                        <!-- Pricing  -->
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="pricing-list">
                                    <ul>
                                        <li>Styling. . . . . . . . . . . . . . . . . . . . . . . . . . . . <span>$25</span>
                                        </li>
                                        <li>Styling + Color. . . . . . . . . . . . . . . . . . . <span>$65</span></li>
                                        <li>Styling + Tint. . . . . . . . . . . . . . . . . . . . . .<span>$65</span></li>
                                        <li> Semi-permanent wave. . . . . . . . . . . . .<span>$65</span></li>
                                        <li> Cut + Styling. . . . . . . . . . . . . . . . . . . . . .<span>$63</span></li>
                                        <li> Cut + Styling + Color. . . . . . . . . . . . . <span>$100</span></li>
                                        <li> Cut + Styling + Tint. . . . . . . . . . . . . . . .<span>$100</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="pricing-list">
                                    <ul>
                                        <li>Cut. . . . . . . . . . . . . . . . . . . . . . . . . . . . .<span>$25</span>
                                        </li>
                                        <li>Shave. . . . . . . . . . . . . . . . . . . . . . . . . . <span>$65</span></li>
                                        <li>Beard trim. . . . . . . . . . . . . . . . . . . . . . <span>$65</span></li>
                                        <li>Cut + beard trim. . . . . . . . . . . . . . . . . <span>$65</span></li>
                                        <li>Cut + shave. . . . . . . . . . . . . . . . . . . . . . .<span>$63</span></li>
                                        <li>Clean up. . . . . . . . . . . . . . . . . . . . . . . . .<span>$100</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- pricing img -->
            <div class="pricing-img">
                <img class="pricing-img1" src="{{ asset('/barber/img/gallery/pricing1.png') }}" alt="">
                <img class="pricing-img2" src="{{ asset('/barber/img/gallery/pricing2.png') }}" alt="">
            </div>
        </div> --}}
        <!-- Best Pricing Area End -->
        <!--? Gallery Area Start -->
        {{-- <div class="gallery-area section-padding10">
            <div class="container">
                <!-- Section Tittle -->
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-7 col-md-9 col-sm-10">
                        <div class="section-tittle text-center mb-100">
                            <span>Nuestros trabajos realizados</span>
                            <h2>Algunas imagenes de nuestra barbería</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="box snake mb-30">
                            <div class="gallery-img "
                                style="background-image: url({{ asset('/barber/img/gallery/gallery1.png') }});">
                            </div>
                            <div class="overlay"></div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-6 col-sm-6">
                        <div class="box snake mb-30">
                            <div class="gallery-img "
                                style="background-image: url({{ asset('/barber/img/gallery/gallery2.png') }});">
                            </div>
                            <div class="overlay"></div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-6 col-sm-6">
                        <div class="box snake mb-30">
                            <div class="gallery-img "
                                style="background-image: url({{ asset('/barber/img/gallery/gallery3.png') }});">
                            </div>
                            <div class="overlay"></div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="box snake mb-30">
                            <div class="gallery-img "
                                style="background-image: url({{ asset('/barber/img/gallery/gallery4.png') }});">
                            </div>
                            <div class="overlay"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- Gallery Area End -->
        <!-- Cut Details Start -->
        {{--  <div class="cut-details section-bg section-padding2" data-background="assets/barber/img/gallery/section_bg02.png">
            <div class="container">
                <div class="cut-active dot-style">
                    <div class="single-cut">
                        <div class="cut-icon mb-20">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="50px" height="50px">
                                <image x="0px" y="0px" width="50px" height="50px"
                                    xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAQAAAC0NkA6AAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QA/4ePzL8AAAAHdElNRQfkBQ4MDDIERuyfAAADc0lEQVRYw7WYXWxTZRjH/+e0ikhh7QgfiYJZZ7bhBC6mU0LQ6DBADNGYLEaNJGpi4jTEQczYjQG8EL2ThAUTvTRGBwmECyBA+XRKHJpUL1yXFseWbe1ixgZCSAg/Lmo9bXe+up0+/5vT//Oc9/ee8z7nqwbyGbVqUL2iiuiurmtMKf2tu/52DXtW1OhVtekFRZTSkCY1rYcV0VI1arl+VULH9JvnGLhpHT/wD728z+M22QVs5ksyJOlkgds4zqlWEgzSQQ3uEzF4ju8ZpZsHK4NEOcgo7xL2AFhq4CgDtPmHPEWGg0R9AwrayjD77CY2s/RtsrRXDMhrCSc5wyIvyE6GaJ4lQogQB/idZW6QjxlkxRwQee0lWdoupec0a9uqlauHM8VrYyXqyLIuEIQIcYLPZ0JC/EJnQIh8C4xYDV0wO0hgBAgRm0kxrxhSS46mQBFCHKa7GLKbbwNHiCayRAqQCBMBdVW5etlRgGzjWFUQYgMDGHnIaZfbSIxTWNFP3MGzl0GaViQWMVXoAhv9SGn0O3hO+oLPkHiZ4y5FacrD3nPSJn5GptbrJ7+P+VnERa3VA6bWKFlFyC0NqdFUXOkqQqS06kwt1XhVIeNaZiqqSZeS0z4955jWwrBCuudSskvSRklSTDEXzznuaJ74l/m+rt4Wm3Zt8WxhcYAOU5Na7OuwJ3165RHTlKlhrfQFaZckXfH0ymOFhsNKaZX6POYSU7v2SZJ6XTz7aFJKbKfH9ZxuLLp9pIk5evaKM4ZMndXzrjOJ/7+V0Uv/rYKdZx9tOi8Jg3HqPY+kn66iGdt59jrMe/nnyX52V+mhVcsNFuchLWQqeH+vRB9xCBVeJC7xZhUQYTKstyBb+JNQ4JB3OJvfKhgJPggYEeEaz5ZCmpgI4H2+WD18Xdi2zG4uBbj8r5GxvtUs2+AE+wNCrCZHq/W7OBUlya4AEI9yjbeKnfL0VbrmiIgzyCelXnnJI/zBV3NYm6cZoaPcnVkW4yQXZtVpBp1keWVmxq7YpIsc2ys8nmbOc5k6u5zTLqtIkOQNn/eBer4hx4eY9nm3XbdwkTSfun67PEQ7R8ixh1rnKsPj/64WbdPrmtI5XdGAruqGrmu+IlquBj2hDXpGl/WdDumm2yBeEEky9KRe1Go16jFFFNVt3dSEUvpLfbqgae8B7gNdcvnkrRzZ4gAAAABJRU5ErkJggg==" />
                            </svg>
                        </div>
                        <div class="cut-descriptions">
                            <p>Vestibulum varius, velit sit amet tempor efficitur, ligula mi lacinia libero, vehicula dui
                                nisi eget purus. Integer cursus nibh non risus maximus dictum. Suspendis.</p>
                            <span>JONT NICOLIN KOOK</span>
                        </div>
                    </div>
                    <div class="single-cut">
                        <div class="cut-icon mb-20">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="50px" height="50px">
                                <image x="0px" y="0px" width="50px" height="50px"
                                    xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAQAAAC0NkA6AAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QA/4ePzL8AAAAHdElNRQfkBQ4MDDIERuyfAAADc0lEQVRYw7WYXWxTZRjH/+e0ikhh7QgfiYJZZ7bhBC6mU0LQ6DBADNGYLEaNJGpi4jTEQczYjQG8EL2ThAUTvTRGBwmECyBA+XRKHJpUL1yXFseWbe1ixgZCSAg/Lmo9bXe+up0+/5vT//Oc9/ee8z7nqwbyGbVqUL2iiuiurmtMKf2tu/52DXtW1OhVtekFRZTSkCY1rYcV0VI1arl+VULH9JvnGLhpHT/wD728z+M22QVs5ksyJOlkgds4zqlWEgzSQQ3uEzF4ju8ZpZsHK4NEOcgo7xL2AFhq4CgDtPmHPEWGg0R9AwrayjD77CY2s/RtsrRXDMhrCSc5wyIvyE6GaJ4lQogQB/idZW6QjxlkxRwQee0lWdoupec0a9uqlauHM8VrYyXqyLIuEIQIcYLPZ0JC/EJnQIh8C4xYDV0wO0hgBAgRm0kxrxhSS46mQBFCHKa7GLKbbwNHiCayRAqQCBMBdVW5etlRgGzjWFUQYgMDGHnIaZfbSIxTWNFP3MGzl0GaViQWMVXoAhv9SGn0O3hO+oLPkHiZ4y5FacrD3nPSJn5GptbrJ7+P+VnERa3VA6bWKFlFyC0NqdFUXOkqQqS06kwt1XhVIeNaZiqqSZeS0z4955jWwrBCuudSskvSRklSTDEXzznuaJ74l/m+rt4Wm3Zt8WxhcYAOU5Na7OuwJ3165RHTlKlhrfQFaZckXfH0ymOFhsNKaZX6POYSU7v2SZJ6XTz7aFJKbKfH9ZxuLLp9pIk5evaKM4ZMndXzrjOJ/7+V0Uv/rYKdZx9tOi8Jg3HqPY+kn66iGdt59jrMe/nnyX52V+mhVcsNFuchLWQqeH+vRB9xCBVeJC7xZhUQYTKstyBb+JNQ4JB3OJvfKhgJPggYEeEaz5ZCmpgI4H2+WD18Xdi2zG4uBbj8r5GxvtUs2+AE+wNCrCZHq/W7OBUlya4AEI9yjbeKnfL0VbrmiIgzyCelXnnJI/zBV3NYm6cZoaPcnVkW4yQXZtVpBp1keWVmxq7YpIsc2ys8nmbOc5k6u5zTLqtIkOQNn/eBer4hx4eY9nm3XbdwkTSfun67PEQ7R8ixh1rnKsPj/64WbdPrmtI5XdGAruqGrmu+IlquBj2hDXpGl/WdDumm2yBeEEky9KRe1Go16jFFFNVt3dSEUvpLfbqgae8B7gNdcvnkrRzZ4gAAAABJRU5ErkJggg==" />
                            </svg>
                        </div>
                        <div class="cut-descriptions">
                            <p>Vestibulum varius, velit sit amet tempor efficitur, ligula mi lacinia libero, vehicula dui
                                nisi eget purus. Integer cursus nibh non risus maximus dictum. Suspendis.</p>
                            <span>JONT NICOLIN KOOK</span>
                        </div>
                    </div>
                    <div class="single-cut">
                        <div class="cut-icon mb-20">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="50px" height="50px">
                                <image x="0px" y="0px" width="50px" height="50px"
                                    xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAQAAAC0NkA6AAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QA/4ePzL8AAAAHdElNRQfkBQ4MDDIERuyfAAADc0lEQVRYw7WYXWxTZRjH/+e0ikhh7QgfiYJZZ7bhBC6mU0LQ6DBADNGYLEaNJGpi4jTEQczYjQG8EL2ThAUTvTRGBwmECyBA+XRKHJpUL1yXFseWbe1ixgZCSAg/Lmo9bXe+up0+/5vT//Oc9/ee8z7nqwbyGbVqUL2iiuiurmtMKf2tu/52DXtW1OhVtekFRZTSkCY1rYcV0VI1arl+VULH9JvnGLhpHT/wD728z+M22QVs5ksyJOlkgds4zqlWEgzSQQ3uEzF4ju8ZpZsHK4NEOcgo7xL2AFhq4CgDtPmHPEWGg0R9AwrayjD77CY2s/RtsrRXDMhrCSc5wyIvyE6GaJ4lQogQB/idZW6QjxlkxRwQee0lWdoupec0a9uqlauHM8VrYyXqyLIuEIQIcYLPZ0JC/EJnQIh8C4xYDV0wO0hgBAgRm0kxrxhSS46mQBFCHKa7GLKbbwNHiCayRAqQCBMBdVW5etlRgGzjWFUQYgMDGHnIaZfbSIxTWNFP3MGzl0GaViQWMVXoAhv9SGn0O3hO+oLPkHiZ4y5FacrD3nPSJn5GptbrJ7+P+VnERa3VA6bWKFlFyC0NqdFUXOkqQqS06kwt1XhVIeNaZiqqSZeS0z4955jWwrBCuudSskvSRklSTDEXzznuaJ74l/m+rt4Wm3Zt8WxhcYAOU5Na7OuwJ3165RHTlKlhrfQFaZckXfH0ymOFhsNKaZX6POYSU7v2SZJ6XTz7aFJKbKfH9ZxuLLp9pIk5evaKM4ZMndXzrjOJ/7+V0Uv/rYKdZx9tOi8Jg3HqPY+kn66iGdt59jrMe/nnyX52V+mhVcsNFuchLWQqeH+vRB9xCBVeJC7xZhUQYTKstyBb+JNQ4JB3OJvfKhgJPggYEeEaz5ZCmpgI4H2+WD18Xdi2zG4uBbj8r5GxvtUs2+AE+wNCrCZHq/W7OBUlya4AEI9yjbeKnfL0VbrmiIgzyCelXnnJI/zBV3NYm6cZoaPcnVkW4yQXZtVpBp1keWVmxq7YpIsc2ys8nmbOc5k6u5zTLqtIkOQNn/eBer4hx4eY9nm3XbdwkTSfun67PEQ7R8ixh1rnKsPj/64WbdPrmtI5XdGAruqGrmu+IlquBj2hDXpGl/WdDumm2yBeEEky9KRe1Go16jFFFNVt3dSEUvpLfbqgae8B7gNdcvnkrRzZ4gAAAABJRU5ErkJggg==" />
                            </svg>
                        </div>
                        <div class="cut-descriptions">
                            <p>Vestibulum varius, velit sit amet tempor efficitur, ligula mi lacinia libero, vehicula dui
                                nisi eget purus. Integer cursus nibh non risus maximus dictum. Suspendis.</p>
                            <span>JONT NICOLIN KOOK</span>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- Cut Details End -->
        <!--? Blog Area Start -->
        {{-- <section class="home-blog-area section-padding30">
            <div class="container">
                <!-- Section Tittle -->
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-7 col-md-10 col-sm-10">
                        <div class="section-tittle text-center mb-90">
                            <span>our recent news</span>
                            <h2>Hipos and tricks from recent blog</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <div class="home-blog-single mb-30">
                            <div class="blog-img-cap">
                                <div class="blog-img">
                                    <img src="{{ asset('/barber/img/gallery/home-blog1.png') }}" alt="">
                                    <!-- Blog date -->
                                    <div class="blog-date text-center">
                                        <span>24</span>
                                        <p>Now</p>
                                    </div>
                                </div>
                                <div class="blog-cap">
                                    <p>| Physics</p>
                                    <h3><a href="blog_details.html">Footprints in Time is perfect House in Kurashiki</a>
                                    </h3>
                                    <a href="blog_details.html" class="more-btn">became a member »</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <div class="home-blog-single mb-30">
                            <div class="blog-img-cap">
                                <div class="blog-img">
                                    <img src="{{ asset('/barber/img/gallery/home-blog2.png') }}" alt="">
                                    <!-- Blog date -->
                                    <div class="blog-date text-center">
                                        <span>24</span>
                                        <p>Now</p>
                                    </div>
                                </div>
                                <div class="blog-cap">
                                    <p>| Physics</p>
                                    <h3><a href="blog_details.html">Footprints in Time is perfect House in Kurashiki</a>
                                    </h3>
                                    <a href="blog_details.html" class="more-btn">became a member »</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> --}}
        <!-- Blog Area End -->
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var showMoreButtons = document.querySelectorAll('.show-more');

            showMoreButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var cardText = button.previousElementSibling;
                    if (cardText.classList.contains('expanded')) {
                        cardText.classList.remove('expanded');
                        button.textContent = 'Ver más';
                    } else {
                        cardText.classList.add('expanded');
                        button.textContent = 'Ver menos';
                    }
                });
            });
        });
    </script>
@endsection
