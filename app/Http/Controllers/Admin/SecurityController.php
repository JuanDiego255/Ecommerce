<?php

// app/Http/Controllers/Admin/SecurityController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Barbero;
use App\Models\TenantInfo;
use App\Models\TenantSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class SecurityController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Gate::allows('tenant.settings'), 403);

        $users = User::orderBy('name')->paginate(20);
        $barberos = Barbero::orderBy('nombre')->get();
        $tenantId = TenantInfo::first()->tenant;
        $settings_barber = TenantSetting::where('tenant_id', $tenantId)->first();

        return view('admin.security.index', compact('users', 'barberos','settings_barber'));
    }

    public function updateRole(Request $request, $userId)
    {
        abort_unless(Gate::allows('tenant.settings'), 403);

        $data = $request->validate([
            'role' => ['required', Rule::in(['owner', 'manager', 'barber'])],
        ]);

        $user = User::findOrFail($userId);
        $user->update(['role' => $data['role']]);

        return back()->with('ok', 'Rol actualizado.');
    }

    public function attachBarbero(Request $request, $userId)
    {
        abort_unless(Gate::allows('tenant.settings'), 403);

        $data = $request->validate([
            'barbero_id' => ['nullable', 'integer', 'exists:barberos,id'],
        ]);

        $user = User::findOrFail($userId);

        // Desvincular barbero actualmente vinculado a este usuario (si lo hay)
        if ($user->barbero) {
            $user->barbero->update(['user_id' => null]);
        }

        // Vincular el nuevo (o dejar null si qieren quitar)
        if (!empty($data['barbero_id'])) {
            $barbero = Barbero::findOrFail($data['barbero_id']);

            // Si ese barbero estaba vinculado a otra cuenta, la soltamos
            if ($barbero->user_id && $barbero->user_id !== $user->id) {
                // Garantizamos unicidad
                // (opción A: bloquear con error; opción B: reasignar)
                // Aquí reasignamos:
                // Limpia el vínculo anterior
                $barbero->user()->dissociate();
            }

            $barbero->user()->associate($user);
            $barbero->save();
        }

        return back()->with('ok', 'Vinculación actualizada.');
    }
}
