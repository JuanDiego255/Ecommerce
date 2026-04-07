<?php

namespace App\Http\Controllers;

use App\Models\FichaPlantilla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FichaPlantillaController extends Controller
{
    public function index()
    {
        $plantillas = FichaPlantilla::withCount('sesiones')
            ->orderByDesc('updated_at')
            ->get();

        return view('admin.ecd.plantillas.index', compact('plantillas'));
    }

    public function create()
    {
        return view('admin.ecd.plantillas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:120',
            'descripcion' => 'nullable|string|max:500',
            'categoria'   => 'nullable|string|max:80',
            'color_etiqueta' => 'nullable|string|max:20',
            'campos_json' => 'required|json',
        ]);

        $campos = json_decode($request->campos_json, true);
        $campos = $this->assignKeys($campos);

        FichaPlantilla::create([
            'nombre'         => $request->nombre,
            'descripcion'    => $request->descripcion,
            'categoria'      => $request->categoria,
            'color_etiqueta' => $request->color_etiqueta ?? '#5e72e4',
            'campos'         => $campos,
            'activa'         => true,
            'es_sistema'     => false,
            'created_by'     => Auth::id(),
        ]);

        return redirect()->route('ecd.plantillas.index')
            ->with('success', 'Plantilla creada correctamente.');
    }

    public function edit(FichaPlantilla $plantilla)
    {
        return view('admin.ecd.plantillas.edit', compact('plantilla'));
    }

    public function update(Request $request, FichaPlantilla $plantilla)
    {
        $request->validate([
            'nombre'      => 'required|string|max:120',
            'descripcion' => 'nullable|string|max:500',
            'categoria'   => 'nullable|string|max:80',
            'color_etiqueta' => 'nullable|string|max:20',
            'campos_json' => 'required|json',
        ]);

        $campos = json_decode($request->campos_json, true);
        $campos = $this->assignKeys($campos);

        $plantilla->update([
            'nombre'         => $request->nombre,
            'descripcion'    => $request->descripcion,
            'categoria'      => $request->categoria,
            'color_etiqueta' => $request->color_etiqueta ?? $plantilla->color_etiqueta,
            'campos'         => $campos,
            'version'        => ($plantilla->version ?? 1) + 1,
        ]);

        return redirect()->route('ecd.plantillas.index')
            ->with('success', 'Plantilla actualizada.');
    }

    public function destroy(FichaPlantilla $plantilla)
    {
        if ($plantilla->es_sistema) {
            return back()->with('error', 'Las plantillas del sistema no se pueden eliminar.');
        }
        $plantilla->delete();
        return redirect()->route('ecd.plantillas.index')
            ->with('success', 'Plantilla eliminada.');
    }

    public function duplicate(FichaPlantilla $plantilla)
    {
        $nueva = $plantilla->replicate();
        $nueva->nombre    = $plantilla->nombre . ' (copia)';
        $nueva->es_sistema = false;
        $nueva->created_by = Auth::id();
        $nueva->save();

        return redirect()->route('ecd.plantillas.edit', $nueva)
            ->with('success', 'Plantilla duplicada. Puedes editarla a continuación.');
    }

    public function toggle(FichaPlantilla $plantilla)
    {
        $plantilla->update(['activa' => !$plantilla->activa]);

        if (request()->wantsJson()) {
            return response()->json(['activa' => $plantilla->activa]);
        }

        return back()->with('success', 'Estado actualizado.');
    }

    // Ensure every field has a stable unique key (UUID-like)
    private function assignKeys(array $campos): array
    {
        if (!isset($campos['secciones'])) return $campos;

        foreach ($campos['secciones'] as &$seccion) {
            foreach ($seccion['campos'] ?? [] as &$campo) {
                if (empty($campo['key'])) {
                    $campo['key'] = Str::uuid()->toString();
                }
            }
        }
        return $campos;
    }
}
