<?php

namespace App\Http\Middleware;

use Closure;

class AuthMiddleware
{
    public function handle($request, Closure $next, $role = null)
    {
        if (!isset($_SESSION['user'])) {
            return redirect('/login');
        }

        if ($role && $_SESSION['user']['role'] !== $role && $role !== 'all') {
            return redirect('/dashboard')->with('error', 'Akses ditolak!');
        }

        return $next($request);
    }
}