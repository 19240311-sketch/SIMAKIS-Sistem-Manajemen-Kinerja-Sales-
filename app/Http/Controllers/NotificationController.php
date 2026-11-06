<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mengembalikan notifikasi real-time untuk user yang sedang login (via AJAX / fetch).
     * Dipanggil oleh navbar bell setiap kali halaman selesai dimuat, bukan saat render server-side.
     */
    public function fetch(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['notifications' => [], 'count' => 0]);
        }

        $notifications = NotificationService::getNotificationsFor($user);

        return response()->json([
            'notifications' => $notifications->values()->toArray(),
            'count'         => $notifications->count(),
        ]);
    }
}
