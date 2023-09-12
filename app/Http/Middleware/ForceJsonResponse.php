<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the URL contains the version segment (e.g., '/api/v1/')
        if (str_contains($request->getPathInfo(), '/api/')) {
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }
}
