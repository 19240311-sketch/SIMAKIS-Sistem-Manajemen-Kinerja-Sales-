<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isSupervisor()) {
            abort(403, 'Akses ditolak. Halaman ini hanya dapat diakses oleh Supervisor.');
        }

        return $next($request);
    }
}
