<?php

namespace App\Http\Controllers;

use App\Models\ArticleBlog;
use App\Models\Blog;
use App\Models\Faq;
use App\Models\LandingSection;
use App\Models\Settings;
use App\Models\Servicio;
use App\Models\TenantInfo;
use App\Models\TenantSocialNetwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class LandingController extends Controller
{
    protected int $ttl = 30; // minutos de caché

    private function baseData(): array
    {
        $tenantinfo = Cache::remember('tenant_info', $this->ttl, fn() => TenantInfo::first());
        $settings   = Cache::remember('landing_settings', $this->ttl, fn() => Settings::first());
        $sections   = Cache::remember('landing_sections_active', $this->ttl,
            fn() => LandingSection::activas()->get()
        );
        $social     = Cache::remember('landing_social', $this->ttl, fn() => TenantSocialNetwork::all());

        return compact('tenantinfo', 'settings', 'sections', 'social');
    }

    public function home()
    {
        $data = $this->baseData();
        $section = LandingSection::where('section_key', 'inicio')->first();
        return view('frontend.landing.home', array_merge($data, compact('section')));
    }

    public function nosotros()
    {
        $this->abortIfInactive('nosotros');
        $data    = $this->baseData();
        $section = LandingSection::where('section_key', 'nosotros')->first();
        return view('frontend.landing.nosotros', array_merge($data, compact('section')));
    }

    public function servicios()
    {
        $this->abortIfInactive('servicios');
        $data     = $this->baseData();
        $section  = LandingSection::where('section_key', 'servicios')->first();
        $services = Cache::remember('landing_servicios', $this->ttl,
            fn() => Servicio::where('activo', true)->orderBy('nombre')->get()
        );
        return view('frontend.landing.servicios', array_merge($data, compact('section', 'services')));
    }

    public function faq()
    {
        $this->abortIfInactive('faq');
        $data    = $this->baseData();
        $section = LandingSection::where('section_key', 'faq')->first();
        $faqs    = Cache::remember('landing_faqs', $this->ttl,
            fn() => Faq::activos()->get()
        );
        return view('frontend.landing.faq', array_merge($data, compact('section', 'faqs')));
    }

    public function blog()
    {
        $this->abortIfInactive('blog');
        $data    = $this->baseData();
        $section = LandingSection::where('section_key', 'blog')->first();
        $blogs   = Cache::remember('landing_blogs', $this->ttl,
            fn() => Blog::orderBy('created_at', 'desc')->take(9)->get()
        );
        return view('frontend.landing.blog', array_merge($data, compact('section', 'blogs')));
    }

    public function contacto()
    {
        $this->abortIfInactive('contacto');
        $data    = $this->baseData();
        $section = LandingSection::where('section_key', 'contacto')->first();
        return view('frontend.landing.contacto', array_merge($data, compact('section')));
    }

    public function sendContacto(Request $request)
    {
        $request->validate([
            'nombre'  => 'required|string|max:200',
            'email'   => 'required|email|max:200',
            'mensaje' => 'required|string|max:2000',
        ]);

        $tenantinfo = TenantInfo::first();
        $to = $tenantinfo->email ?? null;

        if ($to) {
            Mail::raw(
                "Nombre: {$request->nombre}\nEmail: {$request->email}\n\nMensaje:\n{$request->mensaje}",
                function ($m) use ($to, $tenantinfo) {
                    $m->to($to)
                      ->subject("Nuevo mensaje de contacto - {$tenantinfo->title}");
                }
            );
        }

        return redirect()->route('landing.contacto')
            ->with('success', '¡Mensaje enviado correctamente! Nos pondremos en contacto pronto.');
    }

    private function abortIfInactive(string $key): void
    {
        $section = LandingSection::where('section_key', $key)->first();
        if (!$section || !$section->activo) {
            abort(404);
        }
    }
}
