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
        <div class="lg:col-span-1 bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6 flex flex-col justify-between hover:border-slate-300 transition-colors">
            <div>
                {{-- Header --}}
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-700">Supervisor Operasional</h2>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Aktif
                    </span>
                </div>

                @if($activeSupervisor)
                    {{-- Profile Box --}}
                    <div class="mt-4 p-4 rounded-xl bg-gradient-to-br from-slate-50 to-blue-50/30 border border-slate-100">
                        <div class="flex items-start gap-3.5">
                            @if($activeSupervisor->foto_url)
                                <img src="{{ $activeSupervisor->foto_url }}" alt="{{ $activeSupervisor->nama }}" class="h-12 w-12 rounded-xl object-cover ring-2 ring-blue-500/20 shadow-xs shrink-0">
                            @else
                                <div class="h-12 w-12 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-black text-base flex items-center justify-center shadow-xs shrink-0 ring-2 ring-blue-200">
                                    {{ $activeSupervisor->avatar_initials }}
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <h3 class="text-sm font-extrabold text-slate-900 truncate" title="{{ $activeSupervisor->nama }}">
                                    {{ $activeSupervisor->nama }}
                                </h3>
                                <p class="text-[11px] text-slate-500 truncate mt-0.5" title="{{ $activeSupervisor->email }}">
                                    {{ $activeSupervisor->email }}
                                </p>
                                @if($activeSupervisor->no_hp)
                                    <p class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                                        <svg class="h-3 w-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                        </svg>
                                        <span>{{ $activeSupervisor->no_hp }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Dealer Branch Tag (Rapi, Proporsional, Tidak Berantakan) --}}
                        <div class="mt-3 pt-2.5 border-t border-slate-200/60 flex items-center gap-1.5 text-xs text-blue-700 font-semibold">
                            <svg class="h-3.5 w-3.5 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            <span class="truncate">{{ $activeSupervisor->cabang ?? 'Yamaha JG Purwakarta' }}</span>
                        </div>
                    </div>

                    {{-- Structured Stats Rows --}}
                    <div class="mt-4 space-y-2 text-xs">
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-500 font-medium">Status Otoritas</span>
                            <span class="inline-flex items-center gap-1 font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100 text-[11px]">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Memimpin Operasional
                            </span>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-500 font-medium">Tim Sales Dibina</span>
                            <span class="font-extrabold text-slate-800 text-xs">
                                {{ $totalSales ?? 0 }} <span class="text-slate-400 font-normal">Personel</span>
                            </span>
                        </div>

                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-500 font-medium">Cakupan Otoritas</span>
                            <span class="font-bold text-blue-700 text-[11px] truncate max-w-[170px]" title="Target, Prospek, Ranking, Evaluasi & Rapat">
                                Target, Prospek &amp; Kinerja
                            </span>
                        </div>
                    </div>
                @else
                    <div class="my-8 text-center py-6 text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <svg class="h-8 w-8 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <p class="text-xs font-medium">Belum ada Supervisor aktif yang ditunjuk.</p>
                    </div>
                @endif
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100">
                <a href="{{ route('users.index') }}"
                   class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs transition-colors border border-blue-100">
                    <span>Kelola Pengguna Sistem</span>
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Pembagian Role SIMAKIS Panel -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6 flex flex-col justify-between hover:border-slate-300 transition-colors">
            <div>
                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3.5 border-b border-slate-100 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-700">Pembagian Hak Akses &amp; Peran SIMAKIS</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600">3 Level Akses</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">Struktur otorisasi dan tanggung jawab operasional dealer resmi Yamaha JG Purwakarta</p>
                    </div>
                    <a href="{{ route('users.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 inline-flex items-center gap-1 self-start sm:self-auto shrink-0">
                        <span>Atur Akun</span>
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>

                {{-- Grid 3 Roles --}}
                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                    <!-- Super Admin Box -->
                    <div class="p-4 rounded-xl bg-purple-50/50 border border-purple-200/80 flex flex-col justify-between hover:shadow-xs transition-shadow">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-purple-100 text-purple-800 border border-purple-200">
                                    SUPER ADMIN
                                </span>
                                <span class="text-xs font-bold text-purple-700">{{ $totalSuperAdmin ?? 0 }} Akun</span>
                            </div>
                            <h3 class="text-xs font-bold text-slate-900 mt-1">Manajemen Akses &amp; Akun</h3>
                            <ul class="mt-2.5 space-y-1.5 text-[11px] text-slate-600 leading-snug">
                                <li class="flex items-start gap-1.5">
                                    <svg class="h-3.5 w-3.5 text-purple-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    <span>Kelola akun pengguna &amp; role</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <svg class="h-3.5 w-3.5 text-purple-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    <span>Aktivasi / nonaktifkan akun</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <svg class="h-3.5 w-3.5 text-purple-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    <span>Audit jejak log aktivitas</span>
                                </li>
                                <li class="flex items-start gap-1.5 text-purple-700 font-semibold pt-1 border-t border-purple-100">
                                    <svg class="h-3.5 w-3.5 text-purple-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Fokus tata kelola sistem</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Supervisor Box -->
                    <div class="p-4 rounded-xl bg-blue-50/50 border border-blue-200/80 flex flex-col justify-between hover:shadow-xs transition-shadow">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-200">
                                    SUPERVISOR
                                </span>
                                <span class="text-xs font-bold text-blue-700">{{ $totalSupervisor ?? 0 }} Akun</span>
                            </div>
                            <h3 class="text-xs font-bold text-slate-900 mt-1">Manajemen Kinerja Sales</h3>
                            <ul class="mt-2.5 space-y-1.5 text-[11px] text-slate-600 leading-snug">
                                <li class="flex items-start gap-1.5">
                                    <svg class="h-3.5 w-3.5 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    <span>Monitoring prospek &amp; closing</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <svg class="h-3.5 w-3.5 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    <span>Pengaturan target &amp; reward</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <svg class="h-3.5 w-3.5 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    <span>Evaluasi kinerja &amp; ranking</span>
                                </li>
                                <li class="flex items-start gap-1.5 text-blue-700 font-semibold pt-1 border-t border-blue-100">
                                    <svg class="h-3.5 w-3.5 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Pengelolaan rapat mingguan</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Sales Box -->
                    <div class="p-4 rounded-xl bg-emerald-50/50 border border-emerald-200/80 flex flex-col justify-between hover:shadow-xs transition-shadow">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    SALES
                                </span>
                                <span class="text-xs font-bold text-emerald-700">{{ $totalSales ?? 0 }} Akun</span>
                            </div>
                            <h3 class="text-xs font-bold text-slate-900 mt-1">Pelaksana Operasional</h3>
                            <ul class="mt-2.5 space-y-1.5 text-[11px] text-slate-600 leading-snug">
                                <li class="flex items-start gap-1.5">
                                    <svg class="h-3.5 w-3.5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    <span>Input prospek &amp; follow-up</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <svg class="h-3.5 w-3.5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    <span>Pencatatan transaksi unit</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <svg class="h-3.5 w-3.5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    <span>Kejar target 15 unit/bulan</span>
                                </li>
                                <li class="flex items-start gap-1.5 text-emerald-700 font-semibold pt-1 border-t border-emerald-100">
                                    <svg class="h-3.5 w-3.5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Training &amp; tes mingguan</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                <span class="text-slate-400">Total terdaftar: <strong class="text-slate-700">{{ ($totalSuperAdmin ?? 0) + ($totalSupervisor ?? 0) + ($totalSales ?? 0) }} akun pengguna</strong></span>
                <a href="{{ route('users.index') }}" class="font-bold text-blue-600 hover:text-blue-700 inline-flex items-center gap-1">
                    <span>Kelola Seluruh Akun Pengguna</span>
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
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
