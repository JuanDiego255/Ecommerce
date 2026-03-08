<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\LandingSection;
use App\Models\Settings;
use App\Models\TenantInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class LandingAdminController extends Controller
{
    // ─── Secciones ───────────────────────────────────────────────────────────

    public function sectionsIndex()
    {
        // Inicializa secciones por defecto si aún no existen
        LandingSection::initializeDefaults();

        $sections   = LandingSection::orderBy('orden')->get();
        $tenantinfo = TenantInfo::first();
        $settings   = Settings::first();

        return view('admin.landing.sections.index', compact('sections', 'tenantinfo', 'settings'));
    }

    public function sectionsUpdate(Request $request, LandingSection $section)
    {
        $request->validate([
            'titulo'    => 'nullable|string|max:200',
            'subtitulo' => 'nullable|string|max:500',
            'activo'    => 'boolean',
        ]);

        $section->update([
            'titulo'    => $request->titulo,
            'subtitulo' => $request->subtitulo,
            'activo'    => $request->boolean('activo'),
        ]);

        $this->clearLandingCache();

        return redirect()->route('admin.landing.sections')
            ->with(['status' => 'Sección actualizada correctamente.', 'icon' => 'success']);
    }

    public function sectionsReorder(Request $request)
    {
        $request->validate([
            'orden'   => 'required|array',
            'orden.*' => 'integer|exists:landing_sections,id',
        ]);

        foreach ($request->orden as $position => $id) {
            LandingSection::where('id', $id)->update(['orden' => $position + 1]);
        }

        $this->clearLandingCache();

        return response()->json(['ok' => true]);
    }

    public function sectionsToggle(LandingSection $section)
    {
        $section->update(['activo' => !$section->activo]);
        $this->clearLandingCache();

        return redirect()->route('admin.landing.sections')
            ->with(['status' => 'Estado de sección actualizado.', 'icon' => 'success']);
    }

    // ─── Apariencia (settings landing) ───────────────────────────────────────

    public function settingsIndex()
    {
        $settings   = Settings::first();
        $tenantinfo = TenantInfo::first();
        return view('admin.landing.settings.index', compact('settings', 'tenantinfo'));
    }

    public function settingsUpdate(Request $request)
    {
        $request->validate([
            'landing_primary'        => 'nullable|string|max:30',
            'landing_secondary'      => 'nullable|string|max:30',
            'landing_text_hero'      => 'nullable|string|max:30',
            'landing_bg_section'     => 'nullable|string|max:30',
            'landing_hero_titulo'    => 'nullable|string|max:200',
            'landing_hero_subtitulo' => 'nullable|string|max:1000',
            'landing_hero_btn_texto' => 'nullable|string|max:100',
            'landing_hero_btn_url'   => 'nullable|string|max:300',
            'landing_direccion'      => 'nullable|string|max:300',
            'landing_horario'        => 'nullable|string|max:200',
            'landing_hero_image'     => 'nullable|image|max:4096',
        ]);

        $settings = Settings::first();
        $data = $request->only([
            'landing_primary', 'landing_secondary', 'landing_text_hero', 'landing_bg_section',
            'landing_hero_titulo', 'landing_hero_subtitulo',
            'landing_hero_btn_texto', 'landing_hero_btn_url',
            'landing_direccion', 'landing_horario',
        ]);

        if ($request->hasFile('landing_hero_image')) {
            $path = $request->file('landing_hero_image')->store('landing', 'public');
            $data['landing_hero_image'] = $path;
        }

        $settings->update($data);
        $this->clearLandingCache();

        return redirect()->route('admin.landing.settings')
            ->with(['status' => 'Configuración de apariencia actualizada.', 'icon' => 'success']);
    }

    // ─── FAQs ────────────────────────────────────────────────────────────────

    public function faqIndex()
    {
        $faqs = Faq::orderBy('orden')->get();
        return view('admin.landing.faq.index', compact('faqs'));
    }

    public function faqCreate()
    {
        return view('admin.landing.faq.create');
    }

    public function faqStore(Request $request)
    {
        $request->validate([
            'pregunta'  => 'required|string|max:500',
            'respuesta' => 'required|string',
            'orden'     => 'nullable|integer|min:0',
        ]);

        Faq::create([
            'pregunta'  => $request->pregunta,
            'respuesta' => $request->respuesta,
            'orden'     => $request->orden ?? (Faq::max('orden') + 1),
            'activo'    => true,
        ]);

        $this->clearLandingCache();

        return redirect()->route('admin.landing.faq')
            ->with(['status' => 'Pregunta frecuente creada correctamente.', 'icon' => 'success']);
    }

    public function faqEdit(Faq $faq)
    {
        return view('admin.landing.faq.edit', compact('faq'));
    }

    public function faqUpdate(Request $request, Faq $faq)
    {
        $request->validate([
            'pregunta'  => 'required|string|max:500',
            'respuesta' => 'required|string',
            'orden'     => 'nullable|integer|min:0',
            'activo'    => 'boolean',
        ]);

        $faq->update([
            'pregunta'  => $request->pregunta,
            'respuesta' => $request->respuesta,
            'orden'     => $request->orden ?? $faq->orden,
            'activo'    => $request->boolean('activo'),
        ]);

        $this->clearLandingCache();

        return redirect()->route('admin.landing.faq')
            ->with(['status' => 'Pregunta frecuente actualizada.', 'icon' => 'success']);
    }

    public function faqDestroy(Faq $faq)
    {
        $faq->delete();
        $this->clearLandingCache();

        return redirect()->route('admin.landing.faq')
            ->with(['status' => 'Pregunta eliminada.', 'icon' => 'success']);
    }

    // ─── Helper ──────────────────────────────────────────────────────────────

    private function clearLandingCache(): void
    {
        Cache::forget('landing_sections_active');
        Cache::forget('landing_faqs');
        Cache::forget('landing_servicios');
        Cache::forget('landing_blogs');
        Cache::forget('landing_settings');
    }
}
