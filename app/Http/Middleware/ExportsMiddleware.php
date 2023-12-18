<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ExportsMiddleware
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
        $request->headers->set("Access-Control-Expose-Headers", ["Content-Disposition"]);
        return $next($request);
    }
}
