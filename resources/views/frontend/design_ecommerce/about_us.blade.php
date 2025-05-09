@extends('layouts.design_ecommerce.frontmain')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <!-- About 1 - Bootstrap Brain Component -->
    {{--  <section class="py-3 py-md-5">
        <div class="container">
            <div class="row gy-3 gy-md-4 gy-lg-0 align-items-lg-center">
                <div class="col-12 col-lg-6 col-xl-5">
                    <img src="{{ route('file', isset($tenantinfo->login_image) ? $tenantinfo->login_image : '') }}"
                        class="img-fluid rounded" alt="" />
                </div>
                <div class="col-12 col-lg-6 col-xl-7">
                    <div class="row justify-content-xl-center">
                        <div class="col-12 col-xl-11">
                            <h2 class="mb-3">{{ $tenantinfo->tenant == 'rutalimon' ? 'ACERCA DE' : '¿QUIENES SOMOS?' }}</h2>
                            {!! isset($tenantinfo->about_us) ? $tenantinfo->about_us : '' !!}
                          {{--   @if (isset($tenantinfo->tenant) && $tenantinfo->tenant == 'muebleriasarchi')
                                <div class="row gy-4 gy-md-0 gx-xxl-5X">
                                    <div class="col-12 col-md-6">
                                        <div class="d-flex">
                                            <div class="me-4 text-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                    fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h2 class="h4 mb-3">Exclusividad en cada trabajo</h2>
                                                <p class="text-secondary mb-0">Nos encargamos de que cada detalle sea único</p>
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                            @endif 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('design_ecommerce/images/bg-01.jpg');">
        <h2 class="ltext-105 cl0 txt-center">
            Acerca De
        </h2>
    </section>


    <!-- Content page -->
    <section class="bg0 p-t-75 p-b-120">
        <div class="container">
            <div class="row p-b-148">
                <div class="col-md-7 col-lg-8">
                    <div class="p-t-7 p-r-85 p-r-15-lg p-r-0-md">
                        <h3 class="mtext-111 cl2 p-b-16">
                           Nuestra Historia
                        </h3>

                        <p class="stext-113 cl6 p-b-26">
                            {!! isset($tenantinfo->about_us) ? $tenantinfo->about_us : '' !!}
                        </p>                       
                    </div>
                </div>

                <div class="col-11 col-md-5 col-lg-4 m-lr-auto">
                    <div class="how-bor1 ">
                        <div class="hov-img0">
                            <img src="{{ route('file', isset($tenantinfo->login_image) ? $tenantinfo->login_image : '') }}"
                                alt="IMG">
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="row">
                <div class="order-md-2 col-md-7 col-lg-8 p-b-30">
                    <div class="p-t-7 p-l-85 p-l-15-lg p-l-0-md">
                        <h3 class="mtext-111 cl2 p-b-16">
                            Our Mission
                        </h3>

                        <p class="stext-113 cl6 p-b-26">
                            Mauris non lacinia magna. Sed nec lobortis dolor. Vestibulum rhoncus dignissim risus, sed
                            consectetur erat. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac
                            turpis egestas. Nullam maximus mauris sit amet odio convallis, in pharetra magna gravida.
                            Praesent sed nunc fermentum mi molestie tempor. Morbi vitae viverra odio. Pellentesque ac velit
                            egestas, luctus arcu non, laoreet mauris. Sed in ipsum tempor, consequat odio in, porttitor
                            ante. Ut mauris ligula, volutpat in sodales in, porta non odio. Pellentesque tempor urna vitae
                            mi vestibulum, nec venenatis nulla lobortis. Proin at gravida ante. Mauris auctor purus at lacus
                            maximus euismod. Pellentesque vulputate massa ut nisl hendrerit, eget elementum libero iaculis.
                        </p>

                        <div class="bor16 p-l-29 p-b-9 m-t-22">
                            <p class="stext-114 cl6 p-r-40 p-b-11">
                                Creativity is just connecting things. When you ask creative people how they did something,
                                they feel a little guilty because they didn't really do it, they just saw something. It
                                seemed obvious to them after a while.
                            </p>

                            <span class="stext-111 cl8">
                                - Steve Job’s
                            </span>
                        </div>
                    </div>
                </div>

                <div class="order-md-1 col-11 col-md-5 col-lg-4 m-lr-auto p-b-30">
                    <div class="how-bor2">
                        <div class="hov-img0">
                            <img src="images/about-02.jpg" alt="IMG">
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </section>
    @include('layouts.inc.design_ecommerce.footer')
@endsection
@section('scripts')
@endsection
