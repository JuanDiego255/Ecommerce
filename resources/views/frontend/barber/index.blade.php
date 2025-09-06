@extends('layouts.frontbarber')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <main>
        <!--? slider Area Start-->
        <div class="slider-area position-relative fix">
            <div class="slider-active">
                <!-- Single Slider -->
                <div class="single-slider slider-height d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-8 col-lg-9 col-md-11 col-sm-10">
                                <div class="hero__caption">
                                    <span data-animation="fadeInUp" data-delay="0.2s">Nuestro objetivo ofrecerles un lugar
                                        familiar y acogedor en donde puedan estar cómodamente recibiendo nuestro mejor
                                        servicio y atención.</span>
                                    <h1 data-animation="fadeInUp" data-delay="0.5s">
                                        JP Barbería
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Single Slider -->
                <div class="single-slider slider-height d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-8 col-lg-9 col-md-11 col-sm-10">
                                <div class="hero__caption">
                                    <span data-animation="fadeInUp" data-delay="0.2s">with patrick potter</span>
                                    <h1 data-animation="fadeInUp" data-delay="0.5s">
                                        {{ isset($tenantinfo->text_cintillo) ? $tenantinfo->text_cintillo : '' }}
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- stroke Text -->
            <div class="stock-text">
                <h2>Confianza en cada detalle</h2>
                <h2>Confianza en cada detalle</h2>
            </div>
            <!-- Arrow -->
            <div class="thumb-content-box">
                <div class="thumb-content">
                    <h3>make an appointment now</h3>
                    <a href="#"> <i class="fas fa-long-arrow-alt-right"></i></a>
                </div>
            </div>
        </div>
        <!-- slider Area End-->
        <!--? About Area Start -->
        <section class="about-area section-padding30 position-relative">
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
                                <span>Acerca de nsotros...</span>
                                <h2>Años de experiencia...</h2>
                            </div>
                            <p class="mb-30 pera-bottom">Brook presents your services with flexible, convenient and cdpoe
                                layouts. You can select your favorite layouts & elements for cular ts with unlimited
                                ustomization possibilities. Pixel-perfreplication of the designers is intended.</p>
                            <p class="pera-top mb-50">Brook presents your services with flexible, convefnient and ent
                                anipurpose layouts. You can select your favorite.</p>
                            <img src="{{ asset('/barber/img/gallery/signature.png') }}" alt="">
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
        <section class="service-area pb-170">
            <div class="container">
                <!-- Section Tittle -->
                <div class="row d-flex justify-content-center">
                    <div class="col-xl-7 col-lg-8 col-md-11 col-sm-11">
                        <div class="section-tittle text-center mb-90">
                            <span>Professional Services</span>
                            <h2>Our Best services that we offering to you</h2>
                        </div>
                    </div>
                </div>
                <!-- Section caption -->
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="services-caption text-center mb-30">
                            <div class="service-icon">
                                <i class="flaticon-healthcare-and-medical"></i>
                            </div>
                            <div class="service-cap">
                                <h4><a href="#">Stylish Hair Cut</a></h4>
                                <p>Sorem spsum dolor sit amsectetur adipisclit, seddo eiusmod tempor incididunt ut laborea.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="services-caption active text-center mb-30">
                            <div class="service-icon">
                                <i class="flaticon-fitness"></i>
                            </div>
                            <div class="service-cap">
                                <h4><a href="#">Body Massege</a></h4>
                                <p>Sorem spsum dolor sit amsectetur adipisclit, seddo eiusmod tempor incididunt ut laborea.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="services-caption text-center mb-30">
                            <div class="service-icon">
                                <i class="flaticon-clock"></i>
                            </div>
                            <div class="service-cap">
                                <h4><a href="#">Breard Style</a></h4>
                                <p>Sorem spsum dolor sit amsectetur adipisclit, seddo eiusmod tempor incididunt ut laborea.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Services Area End -->
        <!--? Team Start -->
        <div class="team-area pb-180">
            <div class="container">
                <!-- Section Tittle -->
                <div class="row justify-content-center">
                    <div class="col-xl-8 col-lg-8 col-md-11 col-sm-11">
                        <div class="section-tittle text-center mb-100">
                            <span>Nuestro equipo profesional</span>
                            <h2>Los mejores en lo que hacen</h2>
                        </div>
                    </div>
                </div>
                <div class="row team-active dot-style">
                    <!-- single Tem -->
                    {{-- profesional info --}}
                    @if (isset($barbers))
                        @foreach ($barbers as $item)
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-">
                                <div class="single-team mb-80 text-center">
                                    <div class="team-img">
                                        <img src="{{ isset($item->photo_path) ? route('file', $item->photo_path) : url('images/producto-sin-imagen.PNG') }}"
                                            alt="">
                                    </div>
                                    <div class="team-caption">
                                        <span></span>
                                        <h3><a
                                                href="{{ url('/barberos/' . $item->id . '/agendar/') }}">{{ $item->nombre }}</a>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <!-- Team End -->
        <!-- Best Pricing Area Start -->
        <div class="best-pricing section-padding2 position-relative">
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
        </div>
        <!-- Best Pricing Area End -->
        <!--? Gallery Area Start -->
        <div class="gallery-area section-padding30">
            <div class="container">
                <!-- Section Tittle -->
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-7 col-md-9 col-sm-10">
                        <div class="section-tittle text-center mb-100">
                            <span>our image gellary</span>
                            <h2>some images from our barber shop</h2>
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
        </div>
        <!-- Gallery Area End -->
        <!-- Cut Details Start -->
        <div class="cut-details section-bg section-padding2" data-background="assets/barber/img/gallery/section_bg02.png">
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
        </div>
        <!-- Cut Details End -->
        <!--? Blog Area Start -->
        <section class="home-blog-area section-padding30">
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
        </section>
        <!-- Blog Area End -->
    </main>
    {{-- Main Banner --}}
    <!-- Incluye la versión más reciente de Bootstrap 4 o 5 si no está ya incluida -->
    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
        <div class="carousel-inner">
            @if (isset($tenantcarousel) && count($tenantcarousel) > 0)
                @foreach ($tenantcarousel as $key => $carousel)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <img src="{{ route('file', $carousel->image) }}" class="w-100 h-100" alt="carousel image">
                        <div class="carousel-caption">
                            <h1>{{ $carousel->text1 ?? '' }}</h1>
                            <p style="font-size: 18px;">{{ $carousel->text2 ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="carousel-item active">
                    <div class="carousel-background"
                        style="background-image: url('{{ asset('images/producto-sin-imagen.PNG') }}'); background-size: cover; background-position: center; height: 600px;">
                    </div>
                    <div class="carousel-caption">
                        <h1>No hay contenido disponible</h1>
                        <p style="font-size: 18px;">Agregue contenido para el carrusel</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Controles de navegación -->
        <a class="carousel-control-prev" href="#carouselExampleFade" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleFade" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    {{-- Contact form --}}
    <section class="ftco-section ftco-no-pt bg-light mt-large-mobile">
        <div class="container">
            <div class="row no-gutters">
                <div class="col-md-12	featured-top">
                    <div class="row no-gutters">
                        <div class="col-md-4 d-flex align-items-center">
                            <form class="request-form ftco-animate bg-primary" action="{{ url('send-email/blog') }}"
                                method="POST" enctype="multipart/form-data">
                                <h2>¿Tienes alguna duda? ¡Contáctanos!</h2>
                                @csrf
                                <div class="form-group">
                                    <label for="" class="label">Nombre</label>
                                    <input type="text" class="form-control" name="name" required
                                        placeholder="Nombre completo">
                                </div>
                                <div class="form-group">
                                    <label for="" class="label">Teléfono</label>
                                    <input type="text" class="form-control" name="telephone" required
                                        placeholder="Número de teléfono">
                                </div>
                                <div class="form-group">
                                    <label for="" class="label">E-mail</label>
                                    <input type="text" class="form-control" name="email" required
                                        placeholder="Correo electrónico">
                                </div>
                                <div class="form-group">
                                    <label for="" class="label">Consulta</label>
                                    <input type="text" class="form-control" name="question" required
                                        placeholder="¿Cuál es tu duda?">
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Enviar formulario" class="btn btn-secondary py-3 px-4">
                                </div>
                            </form>
                        </div>
                        <div class="col-md-8 d-flex align-items-center">
                            <div class="services-wrap rounded-right w-100">
                                <h3 class="heading-section mb-4">En AUTOS GRECIA contamos con los mejores precios</h3>
                                <div class="row d-flex mb-4">
                                    <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                        <div class="services w-100 text-center">
                                            <div class="icon d-flex align-items-center justify-content-center"><span
                                                    class="flaticon-route"></span></div>
                                            <div class="text w-100">
                                                <h3 class="heading mb-2 text-service-center">Ubicación estratégica</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                        <div class="services w-100 text-center">
                                            <div class="icon d-flex align-items-center justify-content-center"><span
                                                    class="flaticon-handshake"></span></div>
                                            <div class="text w-100">
                                                <h3 class="heading mb-2 text-service-center">Trámite rápido y sencillo</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                                        <div class="services w-100 text-center">
                                            <div class="icon d-flex align-items-center justify-content-center"><span
                                                    class="flaticon-car"></span></div>
                                            <div class="text w-100">
                                                <h3 class="heading mb-2 text-service-center">Te ayudamos a escoger el carro
                                                    ideal</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ url('compare/vehicles') }}"
                                    class="btn btn-primary py-3 px-4 align-service-center">Comparar
                                    vehículos</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    {{-- Trending --}}
    @if (isset($tenantinfo->show_trending) && $tenantinfo->show_trending == 1)
        <section class="ftco-section ftco-no-pt bg-light">
            <div class="container">
                <!-- Header -->
                <div class="row justify-content-center">
                    <div class="col-md-12 text-center ftco-animate mb-1">
                        <h3 class="mb-2 title align-text-center">Descubre todos nuestros vehículos</h3>
                    </div>
                </div>

                <!-- Categorías como tabs -->
                <div class="d-flex justify-content-center">
                    <ul class="nav nav-pills mb-1" id="pills-tab" role="tablist">
                        @foreach ($clothings->groupBy('category_id') as $categoryId => $vehicles)
                            <li class="nav-item">
                                <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                                    id="pills-{{ $categoryId }}-tab" data-toggle="pill"
                                    href="#pills-{{ $categoryId }}" role="tab"
                                    aria-controls="pills-{{ $categoryId }}">
                                    {{ $vehicles->first()->category }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <hr>

                <div class="tab-content" id="pills-tabContent">
                    @foreach ($clothings->groupBy('category_id') as $categoryId => $vehicles)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                            id="pills-{{ $categoryId }}" role="tabpanel"
                            aria-labelledby="pills-{{ $categoryId }}-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="carousel-car owl-carousel">
                                        @foreach ($vehicles as $item)
                                            @php
                                                $precio = $item->price;
                                                if (isset($tenantinfo->custom_size) && $tenantinfo->custom_size == 1) {
                                                    $precio = $item->first_price;
                                                }
                                                if (
                                                    Auth::check() &&
                                                    Auth::user()->mayor == '1' &&
                                                    $item->mayor_price > 0
                                                ) {
                                                    $precio = $item->mayor_price;
                                                }
                                                $descuentoPorcentaje = $item->discount;
                                                $descuento = ($precio * $descuentoPorcentaje) / 100;
                                                $precioConDescuento = $precio - $descuento;
                                            @endphp

                                            <div class="item">

                                                <a
                                                    href="{{ url('detail-clothing/' . $item->id . '/' . $item->category_id) }}">
                                                    <div class="car-wrap rounded ftco-animate">
                                                        <div class="img rounded d-flex align-items-end"
                                                            style="background-image: url('{{ isset($item->main_image) ? route('file', $item->main_image) : url('images/producto-sin-imagen.PNG') }}');">
                                                        </div>
                                                        <div class="text">
                                                            @if ($item->created_at->diffInDays(now()) <= 7)
                                                                <span
                                                                    class="badge badge-pill ml-2 badge-date text-white animacion"
                                                                    id="comparison-count">
                                                                    Nuevo Ingreso
                                                                </span>
                                                            @endif
                                                            <h2 class="mb-0">
                                                                <a href="#">
                                                                    {{ $item->name . ' (' . $item->model . ')' }}

                                                                </a>
                                                            </h2>

                                                            <div class="d-flex mb-3">

                                                                <!-- <p class="price ml-auto">₡{{ number_format($precioConDescuento) }}</p> -->
                                                            </div>
                                                            <span class="line"><span>Tendencia</span></span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
    @endif
    {{-- About Us --}}
    @if (isset($tenantinfo->about) && $tenantinfo->about != '')
        <section class="ftco-section ftco-about" id="about_us">
            <div class="container">
                <div class="row no-gutters">
                    <div class="col-md-6 p-md-5 img img-2 d-flex justify-content-center align-items-center"
                        style="background-image: url(car-styles/images/about.jpg);">
                    </div>
                    <div class="col-md-6 wrap-about ftco-animate">
                        <div class="heading-section heading-section-white pl-md-5">
                            <span class="subheading">Nosotros</span>
                            <h2 class="mb-4">Bienvenido a {{ isset($tenantinfo->title) ? $tenantinfo->title : '' }}</h2>
                            <p class="text-justify">{{ $tenantinfo->about }}</p>
                            <p><button type="button" class="whatsapp-button-click btn btn-primary py-3 px-4">Agendar
                                    cita</button></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- Comments --}}
    @if (count($comments) != 0)
        <hr class="dark horizontal text-danger my-0">
        <section class="ftco-section testimony-section bg-light">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-7 text-center heading-section ftco-animate">
                        <span class="subheading">Testimonios de nuestros clientes</span>
                        <h2 class="mb-3">Clientes satisfechos</h2>
                    </div>
                </div>
                <div class="row ftco-animate">
                    <div class="col-md-12">
                        <div class="carousel-testimony owl-carousel ftco-owl">
                            @foreach ($comments as $item)
                                <div class="item">
                                    <div class="testimony-wrap rounded text-center py-4 pb-5">
                                        <div class="user-img mb-2"
                                            style="background-image: url('{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}')">
                                        </div>
                                        <div class="text pt-4">
                                            <p class="name">{{ $item->name }}</p>
                                            <div class="rated text-center">
                                                @for ($i = 1; $i <= $item->stars; $i++)
                                                    <label class="star-rating-complete"
                                                        title="text">{{ $i }} stars</label>
                                                @endfor
                                            </div>
                                            <p class="card-text card-comment">“{{ $item->description }}”</p>
                                            <span class="show-more">Ver más</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- blogs --}}
    @if (count($blogs) != 0)
        <section class="ftco-section">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-7 heading-section text-center ftco-animate">
                        <span class="subheading">Blog de {{ isset($tenantinfo->title) ? $tenantinfo->title : '' }},
                            explora nuestras secciones, y aclara las dudas acerca de nuestros servicios.</span>
                    </div>
                </div>
                {{-- Condición para centrado si hay dos o menos blogs --}}
                <div class="row d-flex ">
                    @foreach ($blogs as $item)
                        <div class="col-md-4 d-flex ftco-animate card shadow-sm mr-3 mb-5 ml-3 card-blog">
                            <div class="blog-entry justify-content-end">
                                <a href="blog-single.html" class="block-20"
                                    style="background-image: url('{{ isset($item->image) ? route('file', $item->image) : url('images/producto-sin-imagen.PNG') }}');"></a>
                                <div class="text pt-4">
                                    <div class="meta mb-3">
                                        <div><a href="#">{{ $item->fecha_post }}</a></div>
                                        <div><a href="#">{{ $item->autor }}</a></div>
                                    </div>
                                    <h3 class="heading mt-2"><a
                                            href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}">{{ $item->title }}</a>
                                    </h3>
                                    <p><a href="{{ url('/blog/' . $item->id . '/' . $item->name_url) }}"
                                            class="btn btn-primary">Leer más</a></p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    {{-- Counter --}}
    {{--  <section class="ftco-counter ftco-section img bg-light" id="section-counter">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-3 justify-content-center counter-wrap ftco-animate">
                    <div class="block-18">
                        <div class="text text-border d-flex align-items-center">
                            <strong class="number" data-number="40">0</strong>
                            <span>Años de <br>Experiencia</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 justify-content-center counter-wrap ftco-animate">
                    <div class="block-18">
                        <div class="text text-border d-flex align-items-center">
                            <strong class="number" data-number="{{ $car_count }}">0</strong>
                            <span>Vehículos <br>Disponibles</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 justify-content-center counter-wrap ftco-animate">
                    <div class="block-18">
                        <div class="text text-border d-flex align-items-center">
                            <strong class="number" data-number="{{ $comment_count }}">0</strong>
                            <span>Clientes <br>Satisfechos</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    {{-- @include('layouts.inc.carsale.footer') --}}
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
