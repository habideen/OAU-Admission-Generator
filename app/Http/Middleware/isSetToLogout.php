<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isSetToLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->force_logout && !$request->has('password')) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $request->session()->flush();

            return redirect('/')->with([
                'status' => 'failed',
                'message' => 'Please login to continue'
            ]);
        }
        return $next($request);
    }
}
