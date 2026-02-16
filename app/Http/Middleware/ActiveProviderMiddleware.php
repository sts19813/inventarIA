<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveProviderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if ($user->role === 'provider') {
            if (!$user->is_active || !$user->provider || !$user->provider->is_active) {
                auth()->logout();
                abort(403, 'Proveedor deshabilitado');
            }
        }

        return $next($request);
    }

}
