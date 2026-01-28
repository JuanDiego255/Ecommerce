<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstagramCaptionTemplate;
use App\Domain\Instagram\Services\SpintaxService;
use Illuminate\Http\Request;

class InstagramCaptionTemplateController extends Controller
{
    public function __construct(
        protected SpintaxService $spintaxService
    ) {}

    /**
     * Listado de plantillas
     */
    public function index()
    {
        $templates = InstagramCaptionTemplate::orderByDesc('id')->paginate(20);
        return view('admin.instagram.caption-templates.index', compact('templates'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        return view('admin.instagram.caption-templates.add');
    }

    /**
     * Guardar nueva plantilla
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'template_text' => 'required|string',
            'weight' => 'required|integer|min:1|max:100',
        ]);

        $templateText = $request->template_text;

        // Validar sintaxis spintax
        if (!$this->spintaxService->validate($templateText)) {
            return back()
                ->withInput()
                ->with('error', 'La sintaxis del template no es válida. Verifica que las llaves {} estén balanceadas.');
        }

        InstagramCaptionTemplate::create([
            'name' => $request->name,
            'template_text' => $templateText,
            'weight' => $request->weight,
            'is_active' => true,
            'tenant_domain' => request()->getHost(),
        ]);

        return redirect('/instagram/caption-templates')
            ->with('ok', 'Plantilla creada correctamente.');
    }

    /**
     * Formulario de edición
     */
    public function edit($id)
    {
        $template = InstagramCaptionTemplate::findOrFail($id);
        return view('admin.instagram.caption-templates.edit', compact('template'));
    }

    /**
     * Actualizar plantilla
     */
    public function update(Request $request, $id)
    {
        $template = InstagramCaptionTemplate::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'template_text' => 'required|string',
            'weight' => 'required|integer|min:1|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $templateText = $request->template_text;

        // Validar sintaxis spintax
        if (!$this->spintaxService->validate($templateText)) {
            return back()
                ->withInput()
                ->with('error', 'La sintaxis del template no es válida. Verifica que las llaves {} estén balanceadas.');
        }

        $template->update([
            'name' => $request->name,
            'template_text' => $templateText,
            'weight' => $request->weight,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect('/instagram/caption-templates')
            ->with('ok', 'Plantilla actualizada.');
    }

    /**
     * Eliminar plantilla
     */
    public function destroy($id)
    {
        $template = InstagramCaptionTemplate::findOrFail($id);

        // Verificar si está siendo usada por colecciones
        $usageCount = $template->collections()->count();
        if ($usageCount > 0) {
            return back()->with('error', "Esta plantilla está siendo usada por {$usageCount} colección(es). Desasóciala primero.");
        }

        $template->delete();

        return back()->with('ok', 'Plantilla eliminada.');
    }

    /**
     * Vista previa: genera variaciones aleatorias (AJAX)
     */
    public function preview(Request $request)
    {
        $request->validate([
            'template_text' => 'required|string',
            'count' => 'nullable|integer|min:1|max:10',
        ]);

        $templateText = $request->template_text;
        $count = $request->input('count', 3);

        if (!$this->spintaxService->validate($templateText)) {
            return response()->json([
                'ok' => false,
                'message' => 'Sintaxis inválida. Verifica que las llaves {} estén balanceadas.',
            ], 422);
        }

        $variations = $this->spintaxService->generateVariations($templateText, $count);
        $possibleCount = $this->spintaxService->countPossibleVariations($templateText);
        $blocks = $this->spintaxService->extractBlocks($templateText);

        return response()->json([
            'ok' => true,
            'variations' => $variations,
            'possible_count' => $possibleCount,
            'blocks_count' => count($blocks),
        ]);
    }

    /**
     * Genera una sola variación del template (AJAX)
     * Útil para generar el caption final antes de publicar
     */
    public function generate(Request $request)
    {
        $request->validate([
            'template_id' => 'required|integer|exists:instagram_caption_templates,id',
        ]);

        $template = InstagramCaptionTemplate::findOrFail($request->template_id);
        $caption = $this->spintaxService->process($template->template_text);

        return response()->json([
            'ok' => true,
            'caption' => $caption,
        ]);
    }
}
