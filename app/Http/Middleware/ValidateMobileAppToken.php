<?php

namespace App\Http\Middleware;

use App\Models\MobileToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateMobileAppToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-App-Token');

        if (!$token || !MobileToken::validateToken($token)) {
            return response()->json(['message' => 'Invalid or missing app token.'], 401);
        }

        return $next($request);
    }
}
