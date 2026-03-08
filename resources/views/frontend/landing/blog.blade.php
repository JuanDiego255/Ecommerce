@extends('layouts.landing.main')

@section('title', ($section->titulo ?? 'Blog') . ' - ' . ($tenantinfo->title ?? ''))

@section('styles')
.lp-page-hero {
    background: var(--lp-primary);
    color: #fff;
    padding: 80px 0 60px;
    text-align: center;
}
.lp-page-hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.8rem, 4vw, 3rem);
    font-weight: 700;
    margin-bottom: .75rem;
}
.lp-page-hero p { opacity: .8; font-size: 1.05rem; }

.blog-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,.07);
    transition: box-shadow .25s, transform .25s;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.blog-card:hover {
    box-shadow: 0 12px 40px rgba(0,0,0,.14);
    transform: translateY(-4px);
}
.blog-card-img {
    width: 100%; height: 220px;
    object-fit: cover;
}
.blog-card-img-placeholder {
    width: 100%; height: 220px;
    background: linear-gradient(135deg, var(--lp-primary) 0%, var(--lp-secondary) 100%);
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,.4); font-size: 3rem;
}
.blog-card-body { padding: 1.5rem; flex: 1; display: flex; flex-direction: column; }
.blog-card-date {
    font-size: .8rem;
    color: var(--lp-secondary);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .06em;
    margin-bottom: .5rem;
}
.blog-card-title {
    font-weight: 700;
    color: var(--lp-primary);
    font-size: 1.05rem;
    margin-bottom: .6rem;
    flex: 1;
}
.blog-card-excerpt {
    color: #6c757d;
    font-size: .88rem;
    line-height: 1.65;
    margin-bottom: 1rem;
}
.blog-card-link {
    color: var(--lp-secondary);
    font-weight: 600;
    font-size: .9rem;
    text-decoration: none;
}
.blog-card-link:hover { color: var(--lp-primary); }
@endsection

@section('content')

{{-- ── Page Hero ── --}}
<section class="lp-page-hero">
    <div class="container">
        <h1>{{ $section->titulo ?? 'Blog' }}</h1>
        @if($section->subtitulo)
            <p>{{ $section->subtitulo }}</p>
        @endif
    </div>
</section>

{{-- ── Blog posts ── --}}
<section class="lp-section">
    <div class="container">

        @if($blogs->isEmpty())
            <div class="text-center py-5">
                <i class="fa fa-pencil" style="font-size:3rem;color:#dee2e6;"></i>
                <p class="mt-3" style="color:#6c757d;">Próximamente publicaremos nuestros artículos.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($blogs as $blog)
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-card">
                            {{-- Imagen --}}
                            @php
                                $imgField = $blog->image ?? $blog->horizontal_image ?? $blog->image_path ?? null;
                            @endphp
                            @if($imgField)
                                <img src="{{ route('file', $imgField) }}"
                                     alt="{{ $blog->title }}" class="blog-card-img">
                            @else
                                <div class="blog-card-img-placeholder">
                                    <i class="fa fa-pencil-square-o"></i>
                                </div>
                            @endif

                            <div class="blog-card-body">
                                <div class="blog-card-date">
                                    <i class="fa fa-calendar-o me-1"></i>
                                    {{ $blog->created_at->format('d M, Y') }}
                                </div>
                                <h5 class="blog-card-title">{{ $blog->title }}</h5>
                                @if($blog->title_opcional ?? $blog->description ?? null)
                                    <p class="blog-card-excerpt">
                                        {{ Str::limit($blog->title_opcional ?? $blog->description ?? '', 100) }}
                                    </p>
                                @endif
                                @if($blog->name_url ?? null)
                                    <a href="{{ url('blog/' . $blog->id . '/' . $blog->name_url) }}"
                                       class="blog-card-link">
                                        Leer más <i class="fa fa-arrow-right ms-1"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>

@endsection
