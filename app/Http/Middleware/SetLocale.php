<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('language')) {
            app()->setLocale(session('language'));
        } else {
            app()->setLocale('id'); // Default to Indonesian
        }
        
        return $next($request);
    }
}
