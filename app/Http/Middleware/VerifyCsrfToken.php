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
<<<<<<< HEAD
        //
=======
        '*',  // Exclude all routes from CSRF for now (development only)
>>>>>>> 8d9dc5c10d4e1a2398b8f8ca4ab547e2bde2f568
    ];
}
