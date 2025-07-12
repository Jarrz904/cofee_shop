<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login dan memiliki role 'admin'
        if (Auth::check() && Auth::user()->role == 'admin') {
            return $next($request);
        }

        // Jika bukan admin, redirect ke dashboard user
        return redirect('/dashboard');
        
    }
}