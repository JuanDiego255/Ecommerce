<?php

namespace App\Http\Controllers;

use App\Models\ConsentimientoPlantilla;
use Illuminate\Http\Request;

class ConsentimientoPlantillaController extends Controller
{
    public function index()
    {
        $plantillas = ConsentimientoPlantilla::withCount('firmados')
            ->orderByDesc('updated_at')
            ->get();

        return view('admin.ecd.consentimientos.index', compact('plantillas'));
    }

    public function create()
    {
        return view('admin.ecd.consentimientos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:150',
            'tipo'      => 'required|string|max:80',
            'contenido' => 'required|string',
        ]);

        ConsentimientoPlantilla::create([
            'nombre'    => $request->nombre,
            'tipo'      => $request->tipo,
            'contenido' => $request->contenido,
            'activo'    => true,
            'version'   => 1,
        ]);

        return redirect()->route('ecd.consentimientos.index')
            ->with('success', 'Plantilla de consentimiento creada.');
    }

    public function edit(ConsentimientoPlantilla $consentimiento)
    {
        return view('admin.ecd.consentimientos.edit', compact('consentimiento'));
    }

    public function update(Request $request, ConsentimientoPlantilla $consentimiento)
    {
        $request->validate([
            'nombre'    => 'required|string|max:150',
            'tipo'      => 'required|string|max:80',
            'contenido' => 'required|string',
        ]);

        $consentimiento->update([
            'nombre'    => $request->nombre,
            'tipo'      => $request->tipo,
            'contenido' => $request->contenido,
            'version'   => ($consentimiento->version ?? 1) + 1,
        ]);

        return redirect()->route('ecd.consentimientos.index')
            ->with('success', 'Consentimiento actualizado.');
    }

    public function destroy(ConsentimientoPlantilla $consentimiento)
    {
        if ($consentimiento->firmados()->count()) {
            return back()->with('error', 'No se puede eliminar: hay consentimientos firmados que la referencian.');
        }
        $consentimiento->delete();
        return redirect()->route('ecd.consentimientos.index')
            ->with('success', 'Plantilla eliminada.');
    }

    public function toggle(ConsentimientoPlantilla $consentimiento)
    {
        $consentimiento->update(['activo' => !$consentimiento->activo]);
        return back()->with('success', 'Estado actualizado.');
    }
}
