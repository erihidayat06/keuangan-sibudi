<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // User demo praktikum bebas mengakses seluruh modul
        if (auth()->check() && auth()->user()->is_demo) {
            return $next($request);
        }

        if (auth()->check() && auth()->user()->role != 'admin') {
            return redirect('/');
        }
        return $next($request);
    }
}
