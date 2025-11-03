<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use GuzzleHttp\Client;
use Exception;

class FcmHttpV1
{
    /**
     * Envía una notificación push usando Firebase Cloud Messaging HTTP v1.
     *
     * $projectId  -> ID del proyecto Firebase (por ej. barbernotifications)
     * $message    -> Array con la estructura del mensaje FCM:
     *               [
     *                   'token' => 'DEVICE_TOKEN_AQUI',    // o 'topic' => 'algo'
     *                   'notification' => [
     *                       'title' => 'Título',
     *                       'body'  => 'Contenido',
     *                   ],
     *                   'data' => [
     *                       'clave1' => 'valor1',
     *                       'clave2' => 'valor2',
     *                   ],
     *               ]
     *
     * Devuelve array con la respuesta de FCM, o lanza Exception si falla.
     */
    public static function send(string $projectId, array $message): array
    {
        // 1. Obtener token OAuth2 usando la cuenta de servicio
        $accessToken = self::getAccessToken();

        // 2. Preparar el request HTTP a la API HTTP v1
        $client = new Client();

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'message' => $message,
                ],
                'http_errors' => false, // no lances excepción automática de Guzzle
            ]);
        } catch (Exception $e) {
            dd($e->getMessage());
            throw new Exception('Error HTTP al intentar contactar FCM: ' . $e->getMessage(), 0, $e);
        }

        $status = $response->getStatusCode();
        $body   = (string) $response->getBody();
        $json   = json_decode($body, true);

        if ($status < 200 || $status >= 300) {
            // Algo falló. Te doy info útil para debug.
            throw new Exception(
                'FCM devolvió error ' . $status . ' => ' . $body
            );
        }

        // Éxito. Normalmente viene algo como:
        // { "name": "projects/<project>/messages/<id>" }
        return $json ?? [];
    }

    /**
     * Genera / renueva el access token OAuth2 para hacer llamadas a FCM HTTP v1.
     * Usa las credenciales del service account en GOOGLE_APPLICATION_CREDENTIALS (.env)
     */
    protected static function getAccessToken(): string
    {
        $credentialsPath = env('GOOGLE_APPLICATION_CREDENTIALS');

        if (!$credentialsPath || !file_exists($credentialsPath)) {
            throw new Exception(
                'No se encontró el archivo de credenciales del Service Account. ' .
                    'Revisa GOOGLE_APPLICATION_CREDENTIALS en .env'
            );
        }

        // Scope requerido para FCM HTTP v1
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

        // Esta clase viene del paquete google/auth
        $creds = new ServiceAccountCredentials($scopes, $credentialsPath);

        // fetchAuthToken() obtiene y guarda internamente el token actual
        $creds->fetchAuthToken();

        $tokenInfo = $creds->getLastReceivedToken();

        if (
            !is_array($tokenInfo) ||
            !isset($tokenInfo['access_token']) ||
            empty($tokenInfo['access_token'])
        ) {
            throw new Exception('No pude obtener access_token desde las credenciales de servicio.');
        }

        return $tokenInfo['access_token'];
    }
}
