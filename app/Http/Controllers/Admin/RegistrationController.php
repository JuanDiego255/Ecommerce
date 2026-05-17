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
        $mailer      = app(\App\Services\TenantMailService::class)->getMailer();
        $fromAddress = config('mail.from.address');
        $fromName    = config('mail.from.name', 'Info ' . $tenantinfo->title);

        if ($request->input('estado') === "approved") {
            $mailer->send(
                ['html' => 'emails.inscriptors.approved'],
                $viewData,
                function ($m) use ($email, $fromAddress, $fromName) {
                    $m->to($email)
                        ->from($fromAddress, $fromName)
                        ->subject('📅 Inscripción aprobada');
                }
            );
        } else if ($request->input('estado') === "rejected") {
            $mailer->send(
                ['html' => 'emails.inscriptors.cancel'],
                $viewData,
                function ($m) use ($email, $fromAddress, $fromName) {
                    $m->to($email)
                        ->from($fromAddress, $fromName)
                        ->subject('📅 Inscripción cancelada');
                }
            );
        }

        // Opcional: enviar correo al inscriptor informando el cambio
        return back()->with('success', 'Estado actualizado.');
    }
}
