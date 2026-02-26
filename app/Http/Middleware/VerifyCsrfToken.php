<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Meta llama a estos endpoints con un signed_request propio (no CSRF token de Laravel)
        'facebook/data-deletion',
        'facebook/deauthorize',
    ];
}
