<?php

// app/Http/Controllers/Admin/ServicioController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicioController extends Controller
{
    public function index(Request $request)
    {
        $items = Servicio::when($request->q, fn($q) => $q->where('nombre', 'like', '%' . $request->q . '%'))
            ->orderBy('nombre')->paginate(20);
        return view('admin.servicios.index', compact('items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:480'],
            'base_price_view' => ['nullable', 'integer', 'min:0'],
            'activo' => ['sometimes', 'boolean'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('uploads', 'public');
        }
        $payload = [
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            'duration_minutes' => $data['duration_minutes'],
            'base_price_cents' => isset($data['base_price_view']) ? (int)$data['base_price_view'] * 100 : 0,
            'activo' => (bool)($data['activo'] ?? true),
            'image' => $image ?? null
        ];
        Servicio::create($payload);
        return back()->with('ok', 'Servicio creado');
    }

    public function update(Request $request, $id)
    {
        $srv = Servicio::findOrFail($id);
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:480'],
            'base_price_view' => ['nullable', 'integer', 'min:0'],
            'activo' => ['sometimes', 'boolean'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:50000'],
        ]);
        if ($request->hasFile('image')) {
            Storage::delete('public/' . $srv->image);
            $image = $request->file('image')->store('uploads', 'public');
            $image = $image;
        }
        $srv->update([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            'duration_minutes' => $data['duration_minutes'],
            'base_price_cents' => isset($data['base_price_view']) ? (int)$data['base_price_view'] * 100 : $srv->base_price_cents * 100,
            'activo' => (bool)($data['activo'] ?? $srv->activo),
            'image' => $image ?? null
        ]);
        return back()->with('ok', 'Servicio actualizado');
    }

    public function destroy($id)
    {
        $srv = Servicio::findOrfail($id);
        //Servicio::findOrFail($id)->delete();
        if (
            Storage::delete('public/' . $srv->image)

        ) {
            Servicio::destroy($id);
        }
        Servicio::destroy($id);
        return back()->with('ok', 'Servicio eliminado');
    }
}
