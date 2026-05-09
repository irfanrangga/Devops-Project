<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\Response;

class TrustProxies extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    protected $proxies = '*';

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
