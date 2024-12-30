@extends('layouts.front')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@section('content')
    <!-- About 1 - Bootstrap Brain Component -->
    <section class="py-3 py-md-5">
        <div class="container">
            <div class="row gy-3 gy-md-4 gy-lg-0 align-items-lg-center">
                <div class="col-12 col-lg-6 col-xl-5">
                    <img src="{{ route('file', isset($tenantinfo->login_image) ? $tenantinfo->login_image : '') }}"
                        class="img-fluid rounded" alt="" />
                </div>
                <div class="col-12 col-lg-6 col-xl-7">
                    <div class="row justify-content-xl-center">
                        <div class="col-12 col-xl-11">
                            <h2 class="mb-3">QUIENES SOMOS?</h2>
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
                            @endif --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
@endsection
