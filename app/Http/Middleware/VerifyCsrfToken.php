<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        // 'http://127.0.0.1:8000/api/api-demands/',
        'stripe/*',
        'http://127.0.0.1:8000/plata/*',
        'https://beta.reformex.ro/plata/*',
        'http://127.0.0.1:8000/api/banners/personal/plata/*',
    ];
}
