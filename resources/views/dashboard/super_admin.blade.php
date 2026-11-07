@extends('layouts.app')

@section('title', 'Dashboard Super Admin — SIMAKIS Yamaha JG Purwakarta')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    {{-- ============================================================
         1. TOP HEADER SUPER ADMIN
         ============================================================ --}}
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-2xl border border-indigo-900/40 shadow-lg p-6 text-white relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-5">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                        <span class="h-2 w-2 rounded-full bg-indigo-400 animate-pulse"></span>
                        Portal Utama Super Admin
                    </span>
                    <span class="text-xs text-slate-400 font-medium">SIMAKIS &bull; Yamaha JG Purwakarta</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Pusat Kontrol Sistem &amp; Manajemen Pengguna
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
                    Pengawasan otoritas tertinggi sistem: pengelolaan akun pengguna lintas peran, hak akses, status akun, serta audit jejak log aktivitas sistem dealer.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('users.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-md transition-all active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.765l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    <span>Kelola Pengguna &amp; Role</span>
                </a>
                <a href="{{ route('activity-logs.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold border border-slate-700 shadow-xs transition-all active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Log Aktivitas Sistem</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ============================================================
         2. STATISTIK UTAMA SISTEM & PENGGUNA
         ============================================================ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Pengguna -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pengguna</span>
                <span class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.765l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl sm:text-3xl font-black text-slate-900">{{ $totalUsers ?? 0 }} <span class="text-sm font-bold text-slate-500">Akun</span></div>
                <div class="flex items-center gap-2 mt-1.5 text-xs text-slate-500">
                    <span class="font-bold text-emerald-600">{{ $activeUsers ?? 0 }} Aktif</span>
                    <span>&bull;</span>
                    <span class="font-semibold text-rose-500">{{ $inactiveUsers ?? 0 }} Nonaktif</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Komposisi Tim -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Komposisi Tim</span>
                <span class="h-9 w-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl sm:text-3xl font-black text-slate-900">{{ $totalSales ?? 0 }} <span class="text-sm font-bold text-slate-500">Sales</span></div>
                <div class="flex items-center gap-2 mt-1.5 text-xs text-slate-500">
                    <span class="font-bold text-indigo-600">{{ $totalSupervisor ?? 0 }} Supervisor</span>
                    <span>&bull;</span>
                    <span class="font-semibold text-purple-600">{{ $totalSuperAdmin ?? 0 }} Super Admin</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Status Akun Sistem -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Akses Akun</span>
                <span class="h-9 w-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                @php
                    $activeRate = ($totalUsers ?? 0) > 0 ? round((($activeUsers ?? 0) / $totalUsers) * 100) : 100;
                @endphp
                <div class="text-2xl sm:text-3xl font-black text-slate-900">{{ $activeRate }}% <span class="text-sm font-bold text-slate-500">Aktif</span></div>
                <div class="flex items-center gap-2 mt-1.5 text-xs text-slate-500">
                    <span class="font-bold text-emerald-600">{{ $activeUsers ?? 0 }} Siap Operasional</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Log Aktivitas Hari Ini -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Aktivitas Sistem</span>
                <span class="h-9 w-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl sm:text-3xl font-black text-slate-900">{{ $todayLogsCount ?? 0 }} <span class="text-sm font-bold text-slate-500">Hari Ini</span></div>
                <div class="flex items-center gap-2 mt-1.5 text-xs text-slate-500">
                    <span class="font-semibold text-slate-600">{{ $totalLogsCount ?? 0 }} Total Log Tersimpan</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         3. STATUS SUPERVISOR & PEMBAGIAN ROLE SIMAKIS
         ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Supervisor Aktif Panel -->
        <div class="lg:col-span-1 bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Supervisor Aktif Saat Ini
                    </h2>
                    <a href="{{ route('users.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                        Kelola Akun &rarr;
                    </a>
                </div>

                @if($activeSupervisor)
                    <div class="mt-5 text-center sm:text-left flex flex-col sm:flex-row items-center gap-4">
                        @if($activeSupervisor->foto_url)
                            <img src="{{ $activeSupervisor->foto_url }}" alt="{{ $activeSupervisor->nama }}" class="h-16 w-16 rounded-2xl object-cover ring-2 ring-blue-500/20 shadow-xs">
                        @else
                            <div class="h-16 w-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-extrabold text-xl flex items-center justify-center shadow-xs">
                                {{ $activeSupervisor->avatar_initials }}
                            </div>
                        @endif
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">{{ $activeSupervisor->nama }}</h3>
                            <p class="text-xs text-slate-500">{{ $activeSupervisor->email }} &bull; {{ $activeSupervisor->no_hp ?? '-' }}</p>
                            <span class="inline-flex items-center gap-1.5 mt-2 px-2.5 py-0.5 rounded-full text-2xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                <span>Cabang: {{ $activeSupervisor->cabang ?? 'Yamaha JG Purwakarta' }}</span>
                            </span>
                        </div>
                    </div>

                    <div class="mt-5 pt-4 border-t border-slate-100 space-y-2.5 text-xs">
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Status Otoritas:</span>
                            <span class="font-bold text-emerald-600">Aktif Memimpin Operasional</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Jumlah Sales Terbina:</span>
                            <span class="font-bold text-slate-900">{{ $totalSales ?? 0 }} Tenaga Penjual</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Akses Modul:</span>
                            <span class="font-bold text-blue-600">Target, Prospek, Ranking, Evaluasi &amp; Rapat</span>
                        </div>
                    </div>
                @else
                    <div class="mt-8 text-center py-6 text-slate-400">
                        <p class="text-xs">Belum ada Supervisor aktif yang ditunjuk.</p>
                    </div>
                @endif
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex gap-2">
                <a href="{{ route('users.index') }}"
                   class="flex-1 text-center py-2.5 px-3 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs transition-colors">
                    Kelola Seluruh Akun Pengguna &rarr;
                </a>
            </div>
        </div>

        <!-- Pembagian Role SIMAKIS Panel -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Pembagian Hak Akses &amp; Peran SIMAKIS</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Struktur otorisasi dan tanggung jawab operasional sistem dealer</p>
                    </div>
                    <a href="{{ route('users.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                        Atur Role &rarr;
                    </a>
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                    <!-- Super Admin Box -->
                    <div class="p-4 rounded-xl bg-purple-50/70 border border-purple-200 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-0.5 rounded text-2xs font-extrabold bg-purple-100 text-purple-800 border border-purple-200">
                                    SUPER ADMIN
                                </span>
                                <span class="text-xs font-bold text-purple-700">{{ $totalSuperAdmin ?? 0 }} Akun</span>
                            </div>
                            <h3 class="text-xs font-bold text-slate-900">Manajemen Akses &amp; Akun</h3>
                            <ul class="mt-2 space-y-1.5 text-2xs text-slate-600 leading-relaxed list-disc list-inside">
                                <li>Pengelolaan akun pengguna &amp; role</li>
                                <li>Aktivasi / nonaktivasi status akun</li>
                                <li>Audit jejak log aktivitas sistem</li>
                                <li class="text-rose-600 font-semibold">Bebas dari beban transaksi/prospek</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Supervisor Box -->
                    <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-200 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-0.5 rounded text-2xs font-extrabold bg-blue-100 text-blue-800 border border-blue-200">
                                    SUPERVISOR
                                </span>
                                <span class="text-xs font-bold text-blue-700">{{ $totalSupervisor ?? 0 }} Akun</span>
                            </div>
                            <h3 class="text-xs font-bold text-slate-900">Manajemen Kinerja Sales</h3>
                            <ul class="mt-2 space-y-1.5 text-2xs text-slate-600 leading-relaxed list-disc list-inside">
                                <li>Monitoring prospek &amp; penjualan</li>
                                <li>Pengaturan target &amp; verifikasi reward</li>
                                <li>Evaluasi kinerja &amp; ranking sales</li>
                                <li>Pengelolaan rapat mingguan dealer</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Sales Box -->
                    <div class="p-4 rounded-xl bg-emerald-50/70 border border-emerald-200 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-0.5 rounded text-2xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    SALES
                                </span>
                                <span class="text-xs font-bold text-emerald-700">{{ $totalSales ?? 0 }} Akun</span>
                            </div>
                            <h3 class="text-xs font-bold text-slate-900">Pelaksana Operasional</h3>
                            <ul class="mt-2 space-y-1.5 text-2xs text-slate-600 leading-relaxed list-disc list-inside">
                                <li>Input prospek &amp; follow-up konsumen</li>
                                <li>Pencatatan realisasi transaksi unit</li>
                                <li>Pantau target 15 unit &amp; reward</li>
                                <li>Training &amp; tes mingguan kompetensi</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex justify-end">
                <a href="{{ route('users.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">
                    Buka Manajemen Pengguna &rarr;
                </a>
            </div>
        </div>
    </div>

    {{-- ============================================================
         4. AUDIT LOG AKTIVITAS SISTEM TERBARU
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
            <div>
                <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Audit Log Aktivitas Terkini (Real-time)
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Catatan aktivitas perubahan akun, hak akses, status pengguna, dan autentikasi</p>
            </div>
            <a href="{{ route('activity-logs.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                Lihat Seluruh Log &rarr;
            </a>
        </div>

        <div class="overflow-x-auto mt-4">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 uppercase tracking-wider text-2xs">
                        <th class="pb-2.5 font-bold">Waktu</th>
                        <th class="pb-2.5 font-bold">Pengguna</th>
                        <th class="pb-2.5 font-bold">Role</th>
                        <th class="pb-2.5 font-bold">Modul</th>
                        <th class="pb-2.5 font-bold">Aktivitas</th>
                        <th class="pb-2.5 font-bold">Deskripsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentLogs ?? [] as $log)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 text-slate-500 whitespace-nowrap">
                                {{ $log->created_at?->translatedFormat('d M H:i') }}
                            </td>
                            <td class="py-3 font-semibold text-slate-900 whitespace-nowrap">
                                {{ $log->user_nama ?? 'Sistem' }}
                            </td>
                            <td class="py-3 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-2xs font-bold
                                    @if($log->user_peran === 'super_admin') bg-purple-100 text-purple-800
                                    @elseif($log->user_peran === 'supervisor') bg-blue-100 text-blue-800
                                    @elseif($log->user_peran === 'sales') bg-emerald-100 text-emerald-800
                                    @else bg-slate-100 text-slate-700 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $log->user_peran ?? 'guest')) }}
                                </span>
                            </td>
                            <td class="py-3 text-slate-600 font-medium whitespace-nowrap">
                                {{ ucfirst($log->modul) }}
                            </td>
                            <td class="py-3 whitespace-nowrap">
                                <span class="font-bold text-slate-800">{{ ucfirst($log->jenis_aktivitas) }}</span>
                            </td>
                            <td class="py-3 text-slate-600 max-w-md truncate" title="{{ $log->deskripsi }}">
                                {{ $log->deskripsi }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400">
                                Belum ada aktivitas yang dicatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
