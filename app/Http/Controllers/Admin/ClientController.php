<?php

// app/Http/Controllers/Admin/ClientController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Client, Barbero};
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $r)
    {
        $q = Client::query();        
        $clientes = $q->orderByDesc('last_seen_at')->get();
        return view('admin.clientes.index', compact('clientes'));
    }

    public function edit(Client $client)
    {
        $barberos = \App\Models\Barbero::orderBy('nombre')->get(['id', 'nombre']);
        return view('admin.clientes.edit', compact('client', 'barberos'));
    }

    public function update(Request $r, Client $client)
    {
        $data = $r->validate([
            'nombre'    => 'nullable|string|max:255',
            'email'     => 'nullable|email',
            'telefono'  => 'nullable|string|max:50',

            'auto_book_opt_in'     => 'nullable|boolean',
            'auto_book_frequency'  => 'nullable|in:weekly,biweekly',
            'cadence_days'         => 'nullable|integer|min:1|max:60',
            'auto_book_lookahead_days' => 'nullable|integer|min:7|max:60',
            'preferred_barbero_id' => 'nullable|exists:barberos,id',
            'preferred_days'       => 'nullable|array',
            'preferred_days.*'     => 'integer|min:0|max:6',
            'preferred_start'       => 'nullable|date_format:H:i',
            'preferred_end'         => 'nullable|date_format:H:i|after:preferred_start',
            'discount'  => 'nullable|numeric|min:0',
            'notes'     => 'nullable|string',
            'next_due_at_reset' => 'nullable|boolean',
        ]);

        // Normaliza boolean
        $data['auto_book_opt_in'] = (bool) ($data['auto_book_opt_in'] ?? false);

        // Si viene frecuencia y no viene cadence, deriva 7/14
        if (($data['auto_book_frequency'] ?? null) && empty($data['cadence_days'])) {
            $data['cadence_days'] = $data['auto_book_frequency'] === 'weekly' ? 7 : 14;
        }

        // Normaliza preferred_days por si llegan como 1..7 (1=Lun..7=Dom) -> 0..6
        if (!empty($data['preferred_days'])) {
            $data['preferred_days'] = array_map(function ($d) {
                return ($d >= 1 && $d <= 7) ? ($d % 7) : (int)$d;
            }, $data['preferred_days']);
        }

        // Guardar
        $client->fill($data);

        // Reset programado si se pide
        if (!empty($data['next_due_at_reset'])) {
            $client->next_due_at = now(); // entra en el próximo ciclo del scheduler
        }

        // Si cambian parámetros clave y no se pidió reset explícito, puedes decidir forzar igual:
        // if ($client->isDirty(['auto_book_opt_in','auto_book_frequency','cadence_days','preferred_days','preferred_start','preferred_end'])) {
        //   $client->next_due_at = now();
        // }

        $client->save();
        return back()->with('ok', 'Guardado.');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'nombre' => 'nullable|string|max:120',
            'email'  => 'nullable|email|max:190|unique:clients,email',
            'telefono' => 'nullable|string|max:40',
        ]);
        Client::create($data);
        return redirect()->route('clientes.index')->with('ok', 'Cliente creado.');
    }
}
