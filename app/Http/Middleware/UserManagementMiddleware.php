<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserManagementMiddleware
{
    /**
     * Handle an incoming request.
     * Allows Super Admin (primary account administrator) and Supervisor to access user management.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || (!$request->user()->isSuperAdmin() && !$request->user()->isSupervisor())) {
            abort(403, 'Akses ditolak. Halaman ini hanya dapat diakses oleh Administrator.');
        }

        return $next($request);
    }
}

