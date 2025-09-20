<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\TenantInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        //Gate::authorize('viewAny', Registration::class);

        $estado = $request->query('estado');
        $query = Registration::with(['event', 'category'])->latest();
        if ($estado) $query->where('estado', $estado);

        $regs = $query->paginate(20);

        return view('admin.registrations.index', compact('regs', 'estado'));
    }

    public function updateEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => ['required', 'in:pending,approved,rejected']
        ]);

        $reg = Registration::findOrFail($id);
        //Gate::authorize('update', $reg);        
        $reg->estado = $request->input('estado');
        $reg->save();

        $email = $reg->email;
        $tenantinfo = TenantInfo::first();
        if ($email) {
            $viewData = [
                'title'  => $tenantinfo->title,
            ];
        } else {
            return back()->with('success', 'Estado actualizado.');
        }
        if ($request->input('estado') === "approved") {
            // Si prefieres encolar, puedes usar un Mailable. Como pediste Mail::send, lo dejo así:
            Mail::send(
                ['html' => 'emails.inscriptors.approved'],
                $viewData,
                function ($m) use ($email, $tenantinfo) {
                    $m->to($email)
                        ->from(env('MAIL_FROM_ADDRESS'), 'Info ' . $tenantinfo->title) // 👈 aquí cambias el nombre visible
                        ->subject('📅 Inscripción aprobada');
                }
            );
        } else if ($request->input('estado') === "rejected") {
            // Si prefieres encolar, puedes usar un Mailable. Como pediste Mail::send, lo dejo así:
            Mail::send(
                ['html' => 'emails.inscriptors.cancel'],
                $viewData,
                function ($m) use ($email, $tenantinfo) {
                    $m->to($email)
                        ->from(env('MAIL_FROM_ADDRESS'), 'Info ' . $tenantinfo->title) // 👈 aquí cambias el nombre visible
                        ->subject('📅 Inscripción cancelada');
                }
            );
        }

        // Opcional: enviar correo al inscriptor informando el cambio
        return back()->with('success', 'Estado actualizado.');
    }
}
