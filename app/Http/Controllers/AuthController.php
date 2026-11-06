<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'status_aktif' => true], $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            ActivityLogService::logAuth('login', "Pengguna {$user->nama} ({$user->role_label}) berhasil login ke sistem.", $user);

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali, ' . $user->nama . '!');
        }

        // Check if user exists but inactive
        $existingUser = User::where('email', $credentials['email'])->first();
        if ($existingUser && !$existingUser->status_aktif) {
            ActivityLogService::logAuth('login_failed', "Percobaan login gagal untuk akun nonaktif: {$credentials['email']}.", $existingUser);
        } else {
            ActivityLogService::logAuth('login_failed', "Percobaan login gagal untuk email: {$credentials['email']} (Kredensial tidak valid).");
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak sesuai, atau akun Anda nonaktif.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user) {
            ActivityLogService::logAuth('logout', "Pengguna {$user->nama} ({$user->role_label}) logout dari sistem.", $user);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah berhasil keluar dari sistem.');
    }
}
