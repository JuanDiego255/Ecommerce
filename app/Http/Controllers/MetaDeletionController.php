<?php

namespace App\Http\Controllers;

use App\Models\InstagramAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MetaDeletionController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────────
    // POST /facebook/data-deletion
    // Meta llama a este endpoint cuando un usuario solicita borrar sus datos
    // desde el Centro de Privacidad de Facebook.
    // Referencia: https://developers.facebook.com/docs/development/create-an-app/app-dashboard/data-deletion-callback
    // ──────────────────────────────────────────────────────────────────────────
    public function deletionCallback(Request $request)
    {
        $signedRequest = $request->input('signed_request');

        if (!$signedRequest) {
            return response()->json(['error' => 'Missing signed_request'], 400);
        }

        $payload = $this->parseSignedRequest($signedRequest);

        if (!$payload) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $facebookUserId   = $payload['user_id'] ?? null;
        $confirmationCode = Str::upper(Str::random(16));

        // ── Registrar la solicitud ────────────────────────────────────────────
        DB::table('meta_deletion_requests')->insert([
            'confirmation_code' => $confirmationCode,
            'facebook_user_id'  => $facebookUserId,
            'status'            => 'pending',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // ── Borrar tokens/cuentas asociadas al usuario de Facebook ────────────
        // La tabla instagram_accounts almacena page access tokens del usuario.
        // No hay facebook_user_id directo, así que eliminamos todas las cuentas
        // activas (el usuario puede reconectar si lo desea).
        // Ajusta esta lógica si agregas facebook_user_id a instagram_accounts.
        try {
            InstagramAccount::where('is_active', true)->delete();

            DB::table('meta_deletion_requests')
                ->where('confirmation_code', $confirmationCode)
                ->update(['status' => 'completed', 'updated_at' => now()]);

            Log::info('[Meta] Solicitud de eliminación procesada', [
                'facebook_user_id'  => $facebookUserId,
                'confirmation_code' => $confirmationCode,
            ]);
        } catch (\Throwable $e) {
            DB::table('meta_deletion_requests')
                ->where('confirmation_code', $confirmationCode)
                ->update([
                    'status'     => 'failed',
                    'notes'      => $e->getMessage(),
                    'updated_at' => now(),
                ]);

            Log::error('[Meta] Error al procesar eliminación', [
                'facebook_user_id' => $facebookUserId,
                'error'            => $e->getMessage(),
            ]);
        }

        $statusUrl = url('/facebook/deletion-status/' . $confirmationCode);

        return response()->json([
            'url'               => $statusUrl,
            'confirmation_code' => $confirmationCode,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // GET /facebook/deletion-status/{code}
    // Página pública donde el usuario puede verificar el estado de su solicitud.
    // ──────────────────────────────────────────────────────────────────────────
    public function deletionStatus(string $code)
    {
        $record = DB::table('meta_deletion_requests')
            ->where('confirmation_code', $code)
            ->first();

        return view('public.meta-deletion-status', [
            'record' => $record,
            'code'   => $code,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // POST /facebook/deauthorize
    // Meta llama a este endpoint cuando un usuario elimina la app desde la
    // configuración de su cuenta de Facebook (sin borrar todos sus datos).
    // Referencia: https://developers.facebook.com/docs/facebook-login/manually-build-a-login-flow#deauth-callback
    // ──────────────────────────────────────────────────────────────────────────
    public function deauthorizeCallback(Request $request)
    {
        $signedRequest = $request->input('signed_request');

        if (!$signedRequest) {
            return response()->json(['error' => 'Missing signed_request'], 400);
        }

        $payload = $this->parseSignedRequest($signedRequest);

        if (!$payload) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $facebookUserId = $payload['user_id'] ?? null;

        Log::info('[Meta] Deauthorize callback recibido', [
            'facebook_user_id' => $facebookUserId,
        ]);

        // Marcar la cuenta como inactiva (no borramos para conservar historial de posts)
        InstagramAccount::where('is_active', true)->update(['is_active' => false]);

        return response()->json(['success' => true]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Valida y decodifica el signed_request de Meta.
    // Formato: base64url(signature).base64url(payload)
    // Firma: HMAC-SHA256(base64url(payload), app_secret)
    // ──────────────────────────────────────────────────────────────────────────
    private function parseSignedRequest(string $signedRequest): ?array
    {
        [$encodedSig, $encodedPayload] = array_pad(explode('.', $signedRequest, 2), 2, null);

        if (!$encodedSig || !$encodedPayload) {
            return null;
        }

        $sig     = $this->base64UrlDecode($encodedSig);
        $payload = $this->base64UrlDecode($encodedPayload);

        $appSecret     = config('meta.app_secret');
        $expectedSig   = hash_hmac('sha256', $encodedPayload, $appSecret, true);

        if (!hash_equals($expectedSig, $sig)) {
            Log::warning('[Meta] Firma inválida en signed_request');
            return null;
        }

        return json_decode($payload, true);
    }

    private function base64UrlDecode(string $input): string
    {
        return base64_decode(strtr($input, '-_', '+/') . str_repeat('=', (4 - strlen($input) % 4) % 4));
    }
}
