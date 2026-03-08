@extends('layouts.landing.main')

@section('title', ($section->titulo ?? 'Preguntas Frecuentes') . ' - ' . ($tenantinfo->title ?? ''))

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

.faq-item {
    background: #fff;
    border-radius: 12px;
    margin-bottom: 1rem;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    overflow: hidden;
}
.faq-question {
    padding: 1.25rem 1.5rem;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    color: var(--lp-primary);
    font-size: 1rem;
    border: none;
    background: transparent;
    width: 100%;
    text-align: left;
}
.faq-question:hover { color: var(--lp-secondary); }
.faq-icon { flex-shrink: 0; transition: transform .3s; }
.faq-answer {
    padding: 0 1.5rem 1.25rem;
    color: #4a4a4a;
    font-size: .95rem;
    line-height: 1.75;
}
.faq-item.open .faq-icon { transform: rotate(45deg); }
@endsection

@section('content')

{{-- ── Page Hero ── --}}
<section class="lp-page-hero">
    <div class="container">
        <h1>{{ $section->titulo ?? 'Preguntas Frecuentes' }}</h1>
        @if($section->subtitulo)
            <p>{{ $section->subtitulo }}</p>
        @endif
    </div>
</section>

{{-- ── FAQs ── --}}
<section class="lp-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                @if($faqs->isEmpty())
                    <div class="text-center py-5">
                        <i class="fa fa-question-circle" style="font-size:3rem;color:#dee2e6;"></i>
                        <p class="mt-3" style="color:#6c757d;">
                            Próximamente publicaremos las preguntas frecuentes.
                        </p>
                    </div>
                @else
                    @foreach($faqs as $faq)
                        <div class="faq-item" id="faq-{{ $faq->id }}">
                            <button class="faq-question" onclick="toggleFaq({{ $faq->id }})">
                                <span>{{ $faq->pregunta }}</span>
                                <span class="faq-icon ms-3">
                                    <i class="fa fa-plus" style="font-size:.9rem;"></i>
                                </span>
                            </button>
                            <div class="faq-answer" id="faq-body-{{ $faq->id }}" style="display:none;">
                                {!! nl2br(e($faq->respuesta)) !!}
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>
    </div>
</section>

{{-- ── CTA ── --}}
@foreach($sections as $sec)
    @if($sec->section_key === 'contacto')
        <section class="lp-section lp-section-alt" style="text-align:center;">
            <div class="container">
                <h2 class="lp-section-title">¿Tienes más preguntas?</h2>
                <div class="lp-divider"></div>
                <p class="lp-section-subtitle">Nuestro equipo está listo para ayudarte</p>
                <a href="{{ route('landing.contacto') }}" class="btn btn-lp-primary btn-lg">
                    Contáctanos
                </a>
            </div>
        </section>
        @break
    @endif
@endforeach

@endsection

@section('scripts')
<script>
function toggleFaq(id) {
    const item   = document.getElementById('faq-' + id);
    const body   = document.getElementById('faq-body-' + id);
    const isOpen = item.classList.contains('open');

    // Cierra todos
    document.querySelectorAll('.faq-item').forEach(el => {
        el.classList.remove('open');
        el.querySelector('.faq-answer').style.display = 'none';
    });

    if (!isOpen) {
        item.classList.add('open');
        body.style.display = 'block';
    }
}
</script>
@endsection
