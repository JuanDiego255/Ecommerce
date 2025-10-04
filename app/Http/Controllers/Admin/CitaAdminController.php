<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Support\BackUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CitaAdminController extends Controller
{
    public function index(Request $request)
    {
        $citas = Cita::with(['barbero'])
            ->orderByDesc('starts_at')
            ->get();

        return view('admin.citas.index', compact('citas'));
    }

    // Dentro de CitaAdminController@myIndex ya filtramos por barbero_id
    // Si agregas acciones de cambiar estado aquí, valida ownership:
    public function updateStatus(Request $request, $id)
    {
        $cita = \App\Models\Cita::with('barbero')->findOrFail($id);

        if (Gate::allows('citas.manage-any')) {
            // owner/manager: OK
        } else {
            // barber: sólo si es su cita
            $barbero = \App\Models\Barbero::where('user_id', auth()->id())->first();
            if (!$barbero || $barbero->id !== $cita->barbero_id) {
                abort(403);
            }
        }

        $data = $request->validate(['status' => 'required|in:pending,confirmed,completed,cancelled,not_arrive']);
        $cita->update(['status' => $data['status']]);

        return back()->with('ok', 'Estado actualizado');
    }


    public function destroy($id)
    {
        Cita::findOrFail($id)->delete();
        return redirect('citas')->with('ok', 'Cita eliminada');
    }
    public function show($id, Request $request)
    {
        $cita = \App\Models\Cita::with([
            'barbero',
            'servicios' => function ($q) {
                $q->orderBy('nombre');
            }
        ])->findOrFail($id);


        $defaultBack = route('citas.index');

        // Si vienes de “Mis citas”, el enlace llevará ?back=..., lo respetamos:
        $back = $request->query('back');
        // total ya viene en total_cents; desglose via pivot
        return view('admin.citas.show', compact('cita', 'back'));
    }

    /**
     * Lista solo las citas del barbero logueado.
     * Requiere que Barbero tenga user_id -> users.id
     */
    public function myIndex(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();

        $barbero = \App\Models\Barbero::where('user_id', $user->id)->first();
        if (!$barbero) {
            return redirect('/dashboard')->with('error', 'No estás vinculado como barbero.');
        }

        $items = \App\Models\Cita::with('barbero')
            ->where('barbero_id', $barbero->id)
            ->orderByDesc('starts_at')
            ->paginate(20);

        return view('admin.citas.mine', compact('items', 'barbero'));
    }

    public function updateTotal(Request $request, $id)
    {
        //abort_unless(Gate::allows('tenant.settings'), 403);
        $data = $request->validate([
            'total' => ['required'],
        ]);

        $cita = Cita::findOrFail($id);
        $cita->update(['total_cents' => $data['total'] * 100]);

        return back()->with('ok', 'Monto de la cita actualizado.');
    }
}
