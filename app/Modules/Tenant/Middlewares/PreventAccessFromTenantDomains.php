<?php

namespace App\Modules\Tenant\Middlewares;

use Closure;
use Illuminate\Support\Facades\Config;

class PreventAccessFromTenantDomains
{
    public function handle($request, Closure $next)
    {
        $centralDomains = Config::get('tenancy.central_domains', []);

        // If current host is NOT in central domains → it's a tenant domain
        if (! in_array($request->getHost(), $centralDomains)) {
            abort(403, 'Forbidden: This route is only accessible from central domain.');
        }

        return $next($request);
    }
}
