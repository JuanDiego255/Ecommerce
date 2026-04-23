<?php

namespace App\Http\Middleware;

use App\Models\MobileToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ValidateMobileAppToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-App-Token');

        if (!$token) {
            return response()->json(['message' => 'X-App-Token header missing.'], 401);
        }

        try {
            $valid = MobileToken::validateToken($token);
        } catch (\Throwable $e) {
            // Table may not exist — tenant migration not yet applied.
            Log::error('[MobileToken] Validation exception: ' . $e->getMessage(), [
                'tenant' => tenant('id') ?? 'unknown',
            ]);
            return response()->json(['message' => 'Token validation unavailable. Run tenant migrations.'], 503);
        }

        if (!$valid) {
            Log::warning('[MobileToken] Invalid token attempt', [
                'tenant' => tenant('id') ?? 'unknown',
                'hash'   => hash('sha256', $token),
            ]);
            return response()->json(['message' => 'Invalid app token.'], 401);
        }

        return $next($request);
    }
}
