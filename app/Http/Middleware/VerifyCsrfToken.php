<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/api/v1/login',
        '/api/v1/register',
        '/api/v1/customeradd',
        '/api/v1/customeredit',
        '/api/v1/customerview',
        '/api/v1/storecreate'

    ];
}
