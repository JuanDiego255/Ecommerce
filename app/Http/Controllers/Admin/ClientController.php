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
        if ($term = $r->get('q')) {
            $q->where(function ($x) use ($term) {
                $x->where('nombre', 'like', "%$term%")
                    ->orWhere('email', 'like', "%$term%")
                    ->orWhere('telefono', 'like', "%$term%");
            });
        }
        $clientes = $q->orderByDesc('last_seen_at')->paginate(15);
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
            'nombre'   => 'nullable|string|max:120',
            'email'    => 'nullable|email|max:190',
            'telefono' => 'nullable|string|max:40',
            'auto_book_opt_in'      => 'nullable|boolean',
            'preferred_barbero_id'  => 'nullable|exists:barberos,id',
            'preferred_days'        => 'nullable|array',
            'preferred_days.*'      => 'integer|min:0|max:6',
            'preferred_start'       => 'nullable|date_format:H:i',
            'preferred_end'         => 'nullable|date_format:H:i|after:preferred_start',
            'notes'    => 'nullable|string|max:2000',
        ]);

        $client->fill($data);
        $client->auto_book_opt_in = (bool)$r->boolean('auto_book_opt_in');
        $client->last_seen_at = now();
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
