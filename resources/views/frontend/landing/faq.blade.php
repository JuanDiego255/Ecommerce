@extends('layouts.design_ecommerce.frontmain')

@section('metatag')
    <title>{{ ($section->titulo ?? 'Preguntas Frecuentes') . ' - ' . ($tenantinfo->title ?? '') }}</title>
@endsection

@section('content')
<style>
    .faq-item {
        background: #fff;
        border-radius: 12px;
        margin-bottom: 1rem;
        box-shadow: 0 2px 14px rgba(0,0,0,.06);
        overflow: hidden;
        border-left: 4px solid transparent;
        transition: border-color .25s, box-shadow .25s;
    }
    .faq-item.open {
        border-left-color: var(--btn_cart, #333);
        box-shadow: 0 4px 20px rgba(0,0,0,.1);
    }
    .faq-question {
        padding: 1.2rem 1.5rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        font-size: .95rem;
        color: var(--navbar, #222);
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
        transition: color .2s;
    }
    .faq-question:hover { color: var(--btn_cart, #555); }
    .faq-icon {
        flex-shrink: 0;
        margin-left: 1rem;
        color: var(--btn_cart, #333);
        transition: transform .3s;
    }
    .faq-item.open .faq-icon { transform: rotate(45deg); }
    .faq-answer {
        padding: 0 1.5rem 1.25rem 1.5rem;
        color: #555;
        font-size: .92rem;
        line-height: 1.78;
        border-top: 1px solid #f5f5f5;
    }
    .faq-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--btn_cart, #333);
        color: #fff;
        font-size: .75rem;
        font-weight: 700;
        flex-shrink: 0;
        margin-right: .85rem;
    }
</style>

{{-- ── Page Banner ── --}}
<section style="background:var(--navbar);padding:72px 0 60px;text-align:center;">
    <h1 class="ltext-105 cl0">{{ $section->titulo ?? 'Preguntas Frecuentes' }}</h1>
    @if($section->subtitulo)
        <p class="stext-102 cl7 p-t-15" style="opacity:.82;max-width:560px;margin:0 auto;">{{ $section->subtitulo }}</p>
    @endif
</section>

{{-- ── FAQs ── --}}
<div class="bg0 p-t-80 p-b-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                @if($faqs->isEmpty())
                    <div class="txt-center p-t-50 p-b-50">
                        <i class="fa fa-question-circle" style="font-size:3.5rem;color:#dee2e6;"></i>
                        <p class="stext-102 cl6 p-t-20">Próximamente publicaremos las preguntas frecuentes.</p>
                    </div>
                @else
                    @foreach($faqs as $index => $faq)
                        <div class="faq-item" id="faq-{{ $faq->id }}">
                            <button class="faq-question" onclick="toggleFaq({{ $faq->id }})">
                                <span style="display:flex;align-items:center;">
                                    <span class="faq-num">{{ $index + 1 }}</span>
                                    {{ $faq->pregunta }}
                                </span>
                                <span class="faq-icon">
                                    <i class="fa fa-plus" style="font-size:.88rem;"></i>
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
</div>

{{-- ── CTA ── --}}
@foreach($sections as $sec)
    @if($sec->section_key === 'contacto')
        <section style="background:#f8f8f8;padding:72px 0;text-align:center;">
            <div class="container">
                <h2 class="ltext-103 cl3" style="font-size:2rem;font-weight:700;margin-bottom:.5rem;">
                    ¿Tienes más preguntas?
                </h2>
                <div style="width:60px;height:4px;background:var(--btn_cart,#333);margin:16px auto 1.5rem;"></div>
                <p class="stext-102 cl6 p-b-30">Nuestro equipo está listo para ayudarte</p>
                <a href="{{ route('landing.contacto') }}"
                   class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04"
                   style="display:inline-flex;max-width:200px;margin:0 auto;">
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
    var item   = document.getElementById('faq-' + id);
    var body   = document.getElementById('faq-body-' + id);
    var isOpen = item.classList.contains('open');

    document.querySelectorAll('.faq-item').forEach(function(el) {
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
