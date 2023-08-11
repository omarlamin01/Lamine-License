<?php

namespace Lamine\License\Middleware;

use Closure;
use Illuminate\Http\Request;

class WebWare
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session('license_is_valid')) {
            return redirect()->route('license');
        }
        return $next($request);
    }
}