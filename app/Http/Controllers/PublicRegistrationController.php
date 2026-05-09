<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegistrationRequest;
use App\Models\Event;
use App\Models\Registration;
use App\Models\TenantInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PublicRegistrationController extends Controller
{
    public function store(StoreRegistrationRequest $request, $id)
    {
        //abort_unless($event->activo, 404);
        $event = Event::where('id', $id)->first();
        $imagen = null;
        return DB::transaction(function () use ($request, $event, $imagen) {
            if ($request->hasFile('comprobante_pago')) {
                $imagen = $request->file('comprobante_pago')
                    ->store('premios', 'public'); // pública si quieres mostrarla
            }

            $reg = Registration::create([
                'event_id'        => $event->id,
                'category_id'     => $request->category_id,
                'nombre'          => $request->nombre,
                'apellidos'       => $request->apellidos,
                'telefono'        => $request->telefono,
                'equipo'          => $request->equipo,
                'email'           => $request->email,
                'comprobante_pago' => $imagen,
                'terminos'        => true,
                'estado'          => 'pending',
            ]);
            $tenantinfo = TenantInfo::first();
            $email = $tenantinfo->email;

            if ($email) {
                $viewData = [
                    'clienteNombre'  => $request->nombre . ' ' .$request->apellidos,
                    'clienteEmail'   => $request->email,
                    'clientePhone'   => $request->telefono,
                    'totalColones'   => $event->costo_crc,
                ];

                $mailer      = app(\App\Services\TenantMailService::class)->getMailer();
                $fromAddress = config('mail.from.address');
                $fromName    = config('mail.from.name', 'Info ' . $tenantinfo->title);
                $mailer->send(
                    ['html' => 'emails.inscription'],
                    $viewData,
                    function ($m) use ($email, $request, $fromAddress, $fromName) {
                        $m->to($email)
                            ->from($fromAddress, $fromName)
                            ->subject('📅 Nueva inscripción recibida — ' . $request->nombre . ' ' . $request->apellidos);
                    }
                );
            }
            // Para no exponer el archivo, si necesitas mostrarlo luego,
            // genera URLs temporales con un controlador protegido.

            return redirect()
                ->back()
                ->with('success', '¡Inscripción recibida! Te contactaremos por correo cuando sea aprobada.');
        });
    }
    public function show()
    {
        $event = Event::where('activo', 1)->first();
        return view('events.show', compact('event'));
    }
}
