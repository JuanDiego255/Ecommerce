<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barbero;
use App\Models\BarberoPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarberoTrabajoController extends Controller
{
    public function index(Barbero $barbero)
    {
        $fotos = $barbero->trabajos()->latest()->get();
        return view('admin.barberos.trabajos', compact('barbero', 'fotos'));
    }

    public function store(Request $request, Barbero $barbero)
    {
        $request->validate([
            'photos.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'captions' => ['array'],
        ]);

        foreach ($request->file('photos', []) as $idx => $file) {
            if ($file->isValid()) {
                $path = $file->store('uploads', 'public');
            }
            $thumbName = uniqid('job_t_') . '.webp';
            $thumbPath = $path . '/' . $thumbName;
            $caption = $request->input("captions.$idx");

            BarberoPhoto::create([
                'barbero_id' => $barbero->id,
                'path'       => $path,
                'thumb_path' => $thumbPath,
                'caption'    => $caption,
            ]);
        }
        return redirect()->route('barberos.show', [$barbero->id, 'tab' => 'galeria'])
            ->with('ok', 'Trabajos subidos correctamente.');
    }

    public function destroy(Barbero $barbero, BarberoPhoto $photo)
    {
        // seguridad mínima
        abort_if($photo->barbero_id !== $barbero->id, 404);

        Storage::delete([$photo->path, $photo->thumb_path]);
        $photo->delete();

        return redirect()->route('barberos.show', [$barbero->id, 'tab' => 'galeria'])
            ->with('ok', 'Trabajo eliminado.');
    }

    public function feature(Barbero $barbero, BarberoPhoto $photo)
    {
        abort_if($photo->barbero_id !== $barbero->id, 404);

        $barbero->trabajos()->update(['is_featured' => false]);
        $photo->update(['is_featured' => true]);

        return redirect()->route('barberos.show', [$barbero->id, 'tab' => 'galeria'])
            ->with('ok', 'Marcado como destacado.');
    }
}
