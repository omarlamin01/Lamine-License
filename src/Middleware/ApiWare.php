<?php

namespace Lamine\License\Middleware;

use Closure;
use Illuminate\Http\Request;
use Lamine\License\Models\ProductKey;

class ApiWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $license = ProductKey::first();

        if (!$license || $license->isExpired()) {
            return response()->json([
                'message' => 'Product Not Active or License Expired'
            ], 401);
        }
        return $next($request);
    }
}