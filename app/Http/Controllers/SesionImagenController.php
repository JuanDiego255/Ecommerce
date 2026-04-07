<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\SesionClinica;
use App\Models\SesionImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SesionImagenController extends Controller
{
    public function store(Request $request, Paciente $paciente, SesionClinica $sesion)
    {
        abort_unless($sesion->paciente_id === $paciente->id, 404);

        $request->validate([
            'imagenes'            => 'required|array|min:1',
            'imagenes.*'          => 'required|image|max:5120',
            'tipo'                => 'required|in:antes,durante,despues,referencia',
            'zona_corporal'       => 'nullable|string|max:100',
            'titulo'              => 'nullable|string|max:150',
        ]);

        $orden = $sesion->imagenes()->max('orden') ?? 0;

        foreach ($request->file('imagenes') as $file) {
            $path = $file->store('sesiones/' . $sesion->id, 'public');
            $orden++;
            SesionImagen::create([
                'sesion_id'    => $sesion->id,
                'paciente_id'  => $paciente->id,
                'tipo'         => $request->tipo,
                'path'         => $path,
                'titulo'       => $request->titulo,
                'zona_corporal' => $request->zona_corporal,
                'orden'        => $orden,
                'es_favorita'  => false,
            ]);
        }

        return redirect()->route('ecd.sesiones.show', [$paciente, $sesion])
            ->with('success', 'Imágenes subidas correctamente.');
    }

    public function destroy(Paciente $paciente, SesionClinica $sesion, SesionImagen $imagen)
    {
        abort_unless($sesion->paciente_id === $paciente->id, 404);
        abort_unless($imagen->sesion_id === $sesion->id, 404);

        Storage::disk('public')->delete($imagen->path);
        $imagen->delete();

        if (request()->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('ecd.sesiones.show', [$paciente, $sesion])
            ->with('success', 'Imagen eliminada.');
    }

    public function toggleFavorita(Paciente $paciente, SesionClinica $sesion, SesionImagen $imagen)
    {
        abort_unless($sesion->paciente_id === $paciente->id, 404);
        abort_unless($imagen->sesion_id === $sesion->id, 404);

        $imagen->update(['es_favorita' => !$imagen->es_favorita]);

        return response()->json(['es_favorita' => $imagen->es_favorita]);
    }
}
