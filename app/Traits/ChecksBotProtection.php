<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;

trait ChecksBotProtection
{
    /**
     * Aborts with 403 if the request shows bot patterns.
     *
     * Two checks (no external dependencies):
     *  1. Honeypot — invisible text field browsers leave empty; bots fill it.
     *  2. Time token — encrypted timestamp; a submit in under 3 s is a bot.
     *
     * The matching <x-bot-protection /> component must be present in the form.
     */
    protected function guardAgainstBots(Request $request): void
    {
        // Honeypot
        if ($request->filled('_hp_website')) {
            abort(403);
        }

        $token = $request->input('_form_token');

        if (!$token) {
            abort(403);
        }

        try {
            $loadedAt = (int) decrypt($token);
            if (now()->timestamp - $loadedAt < 3) {
                abort(403);
            }
        } catch (DecryptException $e) {
            abort(403);
        }
    }
}
