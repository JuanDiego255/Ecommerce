<?php

namespace App\Services;

use App\Models\AdminDeviceToken;
use App\Services\FcmHttpV1;
use Illuminate\Support\Facades\Log;

class AppointmentNotifier
{
    /**
     * Envía notificación push a todos los dispositivos admin de un tenant
     * cuando se crea una nueva cita.
     */
    public static function nuevaCitaParaTenant(string $tenantId, array $payload): void
    {
        // 1. Buscar todos los tokens de este tenant
        $tokens = AdminDeviceToken::where('tenant', $tenantId)
            ->pluck('token')
            ->all();

        if (empty($tokens)) {
            return;
        }

        // 2. Mandar a cada token individualmente (simple y claro).
        //    Podríamos optimizar a futuro con colas y manejo de errores.
        foreach ($tokens as $token) {
            $message = [
                'token' => $token,
                'notification' => [
                    'title' => $payload['title'],
                    'body'  => $payload['body'],
                ],
                'data' => [
                    'type'    => 'appointment',
                    'cita_id' => (string)($payload['cita_id']),
                    'url'     => $payload['url'],
                ],
            ];

            try {
                FcmHttpV1::send(env('FCM_PROJECT_ID'), $message);
            } catch (\Throwable $e) {
                // Aquí podrías:
                // - Loguear
                // - Si el error dice "registration token not a valid FCM registration token"
                //   eliminar ese token de la BD.
                Log::warning('No se pudo enviar push a token FCM', [
                    'token' => $token,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
