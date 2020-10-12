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
        '/admin/selfdiagnosis/main-img-upload/*',
        '/admin/maintenance/main-img-upload/*',
        '/warranty_extension/user-img-upload/*',
        '/warranty_extension/list/data/*'
    ];
}
