<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventSuperAdminAccess
{
    /**
     * Handle an incoming request.
     * Prevents Super Admin from accessing sales operational modules
     * (prospects, transactions, targets, evaluations, reports, meetings, etc.)
     * as Super Admin is strictly dedicated to account, role, and access rights management.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Super Admin hanya bertugas mengelola akun pengguna, role, hak akses, dan status akun.');
        }

        return $next($request);
    }
}

