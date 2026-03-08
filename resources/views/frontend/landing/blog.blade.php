@extends('layouts.design_ecommerce.frontmain')

@section('metatag')
    <title>{{ ($section->titulo ?? 'Blog') . ' - ' . ($tenantinfo->title ?? '') }}</title>
@endsection

@section('content')
<style>
    /* Tarjeta de blog con imagen overlay (estilo block1) */
    .blog-block {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,.1);
        display: block;
        text-decoration: none;
        background: #111;
    }
    .blog-block img {
        width: 100%;
        height: 270px;
        object-fit: cover;
        display: block;
        transition: transform .45s, opacity .45s;
        opacity: .88;
    }
    .blog-block:hover img { transform: scale(1.05); opacity: .7; }
    .blog-block-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,.78) 0%, rgba(0,0,0,.08) 55%);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        transition: background .3s;
    }
    .blog-block-date {
        font-size: .74rem;
        color: rgba(255,255,255,.65);
        text-transform: uppercase;
        letter-spacing: .07em;
        margin-bottom: .4rem;
    }
    .blog-block-title {
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
        line-height: 1.45;
        margin-bottom: .7rem;
    }
    .blog-block-cta {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-size: .82rem;
        font-weight: 700;
        color: #fff;
        background: var(--btn_cart, #333);
        padding: .35rem .9rem;
        border-radius: 40px;
        width: fit-content;
        transition: opacity .2s;
        text-decoration: none;
    }
    .blog-block:hover .blog-block-cta { opacity: .88; }
    .blog-no-img {
        height: 270px;
        background: linear-gradient(135deg, var(--navbar,#222) 0%, var(--btn_cart,#888) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        flex-direction: column;
        gap: .8rem;
    }
</style>

{{-- ── Page Banner ── --}}
<section style="background:var(--navbar);padding:72px 0 60px;text-align:center;">
    <h1 class="ltext-105 cl0">{{ $section->titulo ?? 'Blog' }}</h1>
    @if($section->subtitulo)
        <p class="stext-102 cl7 p-t-15" style="opacity:.82;max-width:560px;margin:0 auto;">{{ $section->subtitulo }}</p>
    @endif
</section>

{{-- ── Blog posts ── --}}
<div class="bg0 p-t-80 p-b-80">
    <div class="container">

        @if($blogs->isEmpty())
            <div class="txt-center p-t-50 p-b-50">
                <i class="fa fa-pencil" style="font-size:3.5rem;color:#dee2e6;"></i>
                <p class="stext-102 cl6 p-t-20">Próximamente publicaremos nuestros artículos.</p>
            </div>
        @else
            <div class="row">
                @foreach($blogs as $blog)
                    @php
                        $imgField = $blog->image ?? $blog->horizontal_image ?? $blog->image_path ?? null;
                        $blogUrl  = $blog->name_url
                                    ? url('blog/' . $blog->id . '/' . $blog->name_url)
                                    : '#';
                    @endphp
                    <div class="col-md-6 col-xl-4 p-b-30">

                        @if($imgField)
                            <a href="{{ $blogUrl }}" class="blog-block">
                                <img src="{{ route($ruta, $imgField) }}" alt="{{ $blog->title }}">
                                <div class="blog-block-overlay">
                                    <div class="blog-block-date">
                                        <i class="fa fa-calendar-o" style="margin-right:4px;"></i>
                                        {{ $blog->created_at->format('d M, Y') }}
                                    </div>
                                    <div class="blog-block-title">{{ $blog->title }}</div>
                                    <span class="blog-block-cta">
                                        Leer más <i class="fa fa-arrow-right"></i>
                                    </span>
                                </div>
                            </a>
                        @else
                            <div class="blog-no-img">
                                <i class="fa fa-pencil-square-o" style="font-size:2.5rem;color:rgba(255,255,255,.35);"></i>
                                <strong style="color:#fff;font-size:1rem;line-height:1.4;">{{ $blog->title }}</strong>
                                @if($blogUrl !== '#')
                                    <a href="{{ $blogUrl }}" class="blog-block-cta">
                                        Leer más <i class="fa fa-arrow-right"></i>
                                    </a>
                                @endif
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>

@endsection
