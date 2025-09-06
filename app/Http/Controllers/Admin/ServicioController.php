<?php

// app/Http/Controllers/Admin/ServicioController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;

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
        ]);
        $payload = [
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            'duration_minutes' => $data['duration_minutes'],
            'base_price_cents' => isset($data['base_price_view']) ? (int)$data['base_price_view'] * 100 : 0,
            'activo' => (bool)($data['activo'] ?? true),
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
        ]);
        $srv->update([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            'duration_minutes' => $data['duration_minutes'],
            'base_price_cents' => isset($data['base_price_view']) ? (int)$data['base_price_view'] : $srv->base_price_cents,
            'activo' => (bool)($data['activo'] ?? $srv->activo),
        ]);
        return back()->with('ok', 'Servicio actualizado');
    }

    public function destroy($id)
    {
        Servicio::findOrFail($id)->delete();
        return back()->with('ok', 'Servicio eliminado');
    }
}
