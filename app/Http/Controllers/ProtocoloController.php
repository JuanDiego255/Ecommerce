<?php

namespace App\Http\Controllers;

use App\Models\Protocolo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProtocoloController extends Controller
{
    public function index()
    {
        $protocolos = Protocolo::orderBy('nombre')->get();
        return view('admin.ecd.protocolos.index', compact('protocolos'));
    }

    public function create()
    {
        return view('admin.ecd.protocolos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'                => 'required|string|max:150',
            'descripcion'           => 'nullable|string',
            'categoria'             => 'nullable|string|max:80',
            'duracion_estimada_min' => 'nullable|integer|min:1|max:999',
            'nivel_dificultad'      => 'required|in:basico,intermedio,avanzado',
            'contraindicaciones'    => 'nullable|string',
            'notas_post'            => 'nullable|string',
            'materiales_json'       => 'nullable|string',
            'pasos_json'            => 'nullable|string',
        ]);

        $materiales = [];
        if (!empty($data['materiales_json'])) {
            $decoded = json_decode($data['materiales_json'], true);
            $materiales = is_array($decoded) ? $decoded : [];
        }

        $pasos = [];
        if (!empty($data['pasos_json'])) {
            $decoded = json_decode($data['pasos_json'], true);
            $pasos = is_array($decoded) ? $decoded : [];
        }

        Protocolo::create([
            'nombre'                => $data['nombre'],
            'descripcion'           => $data['descripcion'],
            'categoria'             => $data['categoria'],
            'duracion_estimada_min' => $data['duracion_estimada_min'],
            'nivel_dificultad'      => $data['nivel_dificultad'],
            'contraindicaciones'    => $data['contraindicaciones'],
            'notas_post'            => $data['notas_post'],
            'materiales_necesarios' => $materiales,
            'pasos'                 => $pasos,
            'activo'                => true,
            'created_by'            => Auth::id(),
        ]);

        return redirect()->route('ecd.protocolos.index')
            ->with('success', 'Protocolo creado correctamente.');
    }

    public function show(Protocolo $protocolo)
    {
        return view('admin.ecd.protocolos.show', compact('protocolo'));
    }

    public function edit(Protocolo $protocolo)
    {
        return view('admin.ecd.protocolos.edit', compact('protocolo'));
    }

    public function update(Request $request, Protocolo $protocolo)
    {
        $data = $request->validate([
            'nombre'                => 'required|string|max:150',
            'descripcion'           => 'nullable|string',
            'categoria'             => 'nullable|string|max:80',
            'duracion_estimada_min' => 'nullable|integer|min:1|max:999',
            'nivel_dificultad'      => 'required|in:basico,intermedio,avanzado',
            'contraindicaciones'    => 'nullable|string',
            'notas_post'            => 'nullable|string',
            'materiales_json'       => 'nullable|string',
            'pasos_json'            => 'nullable|string',
        ]);

        $materiales = !empty($data['materiales_json'])
            ? (json_decode($data['materiales_json'], true) ?? [])
            : [];

        $pasos = !empty($data['pasos_json'])
            ? (json_decode($data['pasos_json'], true) ?? [])
            : [];

        $protocolo->update([
            'nombre'                => $data['nombre'],
            'descripcion'           => $data['descripcion'],
            'categoria'             => $data['categoria'],
            'duracion_estimada_min' => $data['duracion_estimada_min'],
            'nivel_dificultad'      => $data['nivel_dificultad'],
            'contraindicaciones'    => $data['contraindicaciones'],
            'notas_post'            => $data['notas_post'],
            'materiales_necesarios' => $materiales,
            'pasos'                 => $pasos,
        ]);

        return redirect()->route('ecd.protocolos.show', $protocolo)
            ->with('success', 'Protocolo actualizado.');
    }

    public function destroy(Protocolo $protocolo)
    {
        $protocolo->delete();
        return redirect()->route('ecd.protocolos.index')
            ->with('success', 'Protocolo eliminado.');
    }

    public function toggle(Protocolo $protocolo)
    {
        $protocolo->update(['activo' => !$protocolo->activo]);
        return back()->with('success', 'Estado actualizado.');
    }
}
