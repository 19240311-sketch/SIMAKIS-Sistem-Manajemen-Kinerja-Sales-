<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIMAKIS') — Sistem Manajemen Kinerja Sales Yamaha JG Purwakarta</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v={{ time() }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}?v={{ time() }}">

    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Modern Clean Dropdown Styling */
        select:not([multiple]) {
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-color: #ffffff;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2'%3e%3cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'%3e%3c/path%3e%3c/svg%3e") !important;
            background-position: right 0.75rem center !important;
            background-repeat: no-repeat !important;
            background-size: 0.95rem 0.95rem !important;
            padding-left: 0.875rem !important;
            padding-right: 2.25rem !important;
            padding-top: 0.45rem !important;
            padding-bottom: 0.45rem !important;
            line-height: normal !important;
            vertical-align: middle;
            min-width: 7.5rem;
            box-sizing: border-box !important;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(241, 245, 249, 0.6);
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full font-sans text-slate-800 antialiased flex bg-slate-50 selection:bg-blue-500 selection:text-white">

    <!-- Mobile Sidebar Backdrop -->
    <div id="mobile-sidebar-backdrop" class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm hidden lg:hidden transition-opacity" onclick="toggleSidebar()"></div>

    <!-- Sidebar (Modern Navy Yamaha Theme) -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-[#0b1329] border-r border-slate-800/80 transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:static lg:z-auto shrink-0 shadow-2xl lg:shadow-none">
        
        <!-- Brand / Header Sidebar -->
        <div class="flex h-20 shrink-0 items-center justify-between px-6 border-b border-white/10 bg-[#090f21]">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <div class="flex h-12 w-14 shrink-0 items-center justify-center transition group-hover:scale-105">
                    <img src="{{ asset('images/logo.png') }}?v={{ file_exists(public_path('images/logo.png')) ? filemtime(public_path('images/logo.png')) : time() }}" alt="Yamaha Logo" class="h-full w-full object-contain filter drop-shadow">
                </div>
                <div class="flex flex-col min-w-0">
                    <div class="flex items-center gap-1.5">
                        <span class="font-display text-lg font-extrabold text-white tracking-tight group-hover:text-blue-400 transition">SIMAKIS</span>
                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-400/30">YAMAHA</span>
                    </div>
                    <span class="text-[10px] text-slate-400 font-medium truncate">Manajemen Kinerja Sales</span>
                    <span class="text-[9px] text-blue-400 font-semibold truncate tracking-wider uppercase">JG Purwakarta</span>
                </div>
            </a>
            <button type="button" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10" onclick="toggleSidebar()">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <div class="flex flex-1 flex-col justify-between overflow-y-auto px-4 py-5 space-y-6">
            <nav class="space-y-6">

                <!-- GRUP 1: UTAMA / MENU INTI -->
                <div class="space-y-1">
                    <div class="px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        Menu Utama
                    </div>

                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}"
                       class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Prospek -->
                    <a href="{{ route('prospects.index') }}"
                       class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('prospects.*') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('prospects.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <span>Prospek Konsumen</span>
                    </a>

                    <!-- Transaksi -->
                    <a href="{{ route('transactions.index') }}"
                       class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('transactions.*') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('transactions.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6H2.25m0 0v1.5c0 .414.336.75.75.75h18a.75.75 0 00.75-.75V6m0 0h-.75A.75.75 0 0120.25 5.25V4.5m0 0H3.75m16.5 0a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 4.5m16.5 0v12.75a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V4.5" />
                        </svg>
                        <span>Transaksi Penjualan</span>
                    </a>

                    <!-- Target & Reward -->
                    <a href="{{ route('targets.index') }}"
                       class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ (request()->routeIs('targets.*') || request()->routeIs('rewards.*')) ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 {{ (request()->routeIs('targets.*') || request()->routeIs('rewards.*')) ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.504-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.003 0H9.497m5.003 0a2.25 2.25 0 002.25-2.25v-.872c0-.528-.364-.978-.883-1.06a8.27 8.27 0 00-6.734 0c-.519.082-.883.532-.883 1.06v.872a2.25 2.25 0 002.25 2.25m6-7.5a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                        <div class="flex items-center justify-between w-full">
                            <span>Target & Reward</span>
                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-amber-400/20 text-amber-300 border border-amber-400/30">15 Unit</span>
                        </div>
                    </a>

                    <!-- Ranking Sales -->
                    <a href="{{ route('sales.index') }}"
                       class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('sales.*') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('sales.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                        </svg>
                        <span>Ranking Sales</span>
                    </a>

                    <!-- Penilaian Kinerja -->
                    <a href="{{ route('performance.index') }}"
                       class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('performance.*') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('performance.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                        </svg>
                        <span>Evaluasi Kinerja</span>
                    </a>

                    <!-- Log Aktivitas -->
                    <a href="{{ route('activity-logs.index') }}"
                       class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('activity-logs.*') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('activity-logs.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>
                            @if(auth()->user()->isSupervisor())
                                Log Aktivitas Sistem
                            @else
                                Log Aktivitas Saya
                            @endif
                        </span>
                    </a>
                </div>

                <!-- GRUP 2: PEMBELAJARAN & TES MINGGUAN -->
                <div class="space-y-1">
                    <div class="px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        Training & Kompetensi
                    </div>

                    <!-- Training -->
                    <a href="{{ route('trainings.index') }}"
                       class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('trainings.*') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('trainings.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                        <span>Training Sales</span>
                    </a>

                    <!-- Evaluasi / Tes Mingguan -->
                    <a href="{{ route('weekly-tests.index') }}"
                       class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('weekly-tests.*') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('weekly-tests.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                        </svg>
                        <span>Tes Mingguan</span>
                    </a>

                    <!-- Temuan Lapangan & Rapat -->
                    <a href="{{ route('field-findings.index') }}"
                       class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('field-findings.*') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('field-findings.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <span>Temuan Lapangan</span>
                    </a>

                    @php
                        $sidebarMeetingCount = \App\Models\Meeting::where('tanggal', '>=', now()->toDateString())
                            ->whereIn('status', ['dijadwalkan', 'berlangsung'])
                            ->when(auth()->user()?->isSales(), function ($q) {
                                $q->whereHas('attendances', fn($sq) => $sq->where('user_id', auth()->id()));
                            })
                            ->count();
                    @endphp
                    <a href="{{ route('meetings.index') }}"
                       class="group flex items-center justify-between rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('meetings.*') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('meetings.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            <span>Rapat Mingguan</span>
                        </div>
                        @if($sidebarMeetingCount > 0)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ request()->routeIs('meetings.*') ? 'bg-white text-blue-700' : 'bg-blue-500/80 text-white' }}">
                                {{ $sidebarMeetingCount }}
                            </span>
                        @endif
                    </a>
                </div>

                <!-- GRUP 3: LAPORAN -->
                <div class="space-y-1">
                    <div class="px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        Laporan Resmi
                    </div>

                    <a href="{{ route('reports.sales') }}"
                       class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('reports.sales') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('reports.sales') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <span>Laporan Penjualan</span>
                    </a>

                    <a href="{{ route('reports.sales-person') }}"
                       class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('reports.sales-person') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('reports.sales-person') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <span>Laporan per Sales</span>
                    </a>

                    <a href="{{ route('reports.performance') }}"
                       class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-all {{ request()->routeIs('reports.performance') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('reports.performance') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                        <span>Laporan Kinerja Tim</span>
                    </a>
                </div>

                <!-- GRUP 4: MASTER DATA & PENGELOLAAN SISTEM (SUPERVISOR / ADMIN) -->
                @if(auth()->user()->isSupervisor())
                    <div class="space-y-1 pt-2 border-t border-white/10">
                        <div class="px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-blue-300">
                            Kelola Master & Sistem
                        </div>

                        <!-- Data Motor -->
                        <a href="{{ route('master.motorcycles.index') }}"
                           class="group flex items-center gap-3 rounded-xl px-3.5 py-2 text-xs font-semibold transition-all {{ request()->routeIs('master.motorcycles.*') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('master.motorcycles.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.25V4.5m0 0H9.75M14.25 7.5H9.75" />
                            </svg>
                            <span>Data Motor & Varian</span>
                        </a>

                        <!-- Sumber Prospek -->
                        <a href="{{ route('master.sources.index') }}"
                           class="group flex items-center gap-3 rounded-xl px-3.5 py-2 text-xs font-semibold transition-all {{ request()->routeIs('master.sources.*') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('master.sources.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582" />
                            </svg>
                            <span>Sumber Prospek</span>
                        </a>

                        <!-- Riwayat Jabatan Supervisor -->
                        <a href="{{ route('supervisor.history') }}"
                           class="group flex items-center gap-3 rounded-xl px-3.5 py-2 text-xs font-semibold transition-all {{ request()->routeIs('supervisor.history') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('supervisor.history') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
                            </svg>
                            <span>Riwayat Jabatan Supervisor</span>
                        </a>

                        <!-- Serah Terima Jabatan -->
                        <a href="{{ route('supervisor.handovers.index') }}"
                           class="group flex items-center gap-3 rounded-xl px-3.5 py-2 text-xs font-semibold transition-all {{ request()->routeIs('supervisor.handovers.*') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('supervisor.handovers.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <span>Serah Terima Jabatan</span>
                        </a>

                        <!-- Kelola Akun Pengguna -->
                        <a href="{{ route('users.index') }}"
                           class="group flex items-center gap-3 rounded-xl px-3.5 py-2 text-xs font-semibold transition-all {{ request()->routeIs('users.*') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('users.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            <span>Kelola Pengguna</span>
                        </a>

                        <!-- Kelola Modul Training Admin -->
                        <a href="{{ route('admin.trainings.index') }}"
                           class="group flex items-center gap-3 rounded-xl px-3.5 py-2 text-xs font-semibold transition-all {{ request()->routeIs('admin.trainings.*') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('admin.trainings.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342" />
                            </svg>
                            <span>Kelola Training (Admin)</span>
                        </a>

                        <!-- Kelola Tes Mingguan Admin -->
                        <a href="{{ route('admin.weekly-tests.index') }}"
                           class="group flex items-center gap-3 rounded-xl px-3.5 py-2 text-xs font-semibold transition-all {{ request()->routeIs('admin.weekly-tests.*') ? 'bg-[#0A4DF3] text-white shadow-lg shadow-blue-600/30 font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('admin.weekly-tests.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                            <span>Kelola Tes Mingguan</span>
                        </a>
                    </div>
                @endif

            </nav>

            <!-- User Profile Card & Logout (Bottom Sidebar) -->
            <div class="mt-auto pt-4 border-t border-white/10">
                <a href="{{ route('profile.show') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition group">
                    @if(auth()->user()->foto_url)
                        <img src="{{ auth()->user()->foto_url }}" alt="{{ auth()->user()->name }}"
                             class="h-9 w-9 shrink-0 rounded-full object-cover ring-2 ring-blue-500/50">
                    @else
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white font-bold text-xs ring-2 ring-blue-400/40">
                            {{ auth()->user()->avatar_initials }}
                        </div>
                    @endif
                    <div class="flex flex-col min-w-0 flex-1">
                        <span class="text-xs font-bold text-white truncate group-hover:text-blue-300 transition">{{ auth()->user()->name }}</span>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-semibold
                                @if(auth()->user()->isSuperAdmin()) bg-purple-500/20 text-purple-300
                                @elseif(auth()->user()->isStrictSupervisor()) bg-indigo-500/20 text-indigo-300
                                @else bg-emerald-500/20 text-emerald-300 @endif">
                                {{ auth()->user()->role_label }}
                            </span>
                            <span class="text-[9px] text-slate-400 truncate">&bull; Edit Profil</span>
                        </div>
                    </div>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="mt-2.5">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 rounded-xl bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 px-3 py-2 text-xs font-semibold text-red-300 hover:text-red-200 transition">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        <span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex flex-1 flex-col min-w-0 overflow-hidden">
        
        <!-- Modern Header / Top Bar -->
        <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b border-slate-200 bg-white/95 px-4 sm:px-6 lg:px-8 backdrop-blur shadow-xs">
            <div class="flex items-center gap-3">
                <button type="button" class="lg:hidden text-slate-700 hover:text-[#0A4DF3] p-2 rounded-xl border border-slate-200 hover:border-blue-300" onclick="toggleSidebar()">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div class="hidden sm:flex items-center gap-2 text-xs">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-50 text-[#0A4DF3] font-bold border border-blue-100">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                        Yamaha JG Purwakarta
                    </span>
                    <span class="text-slate-300">/</span>
                    <span class="font-medium text-slate-500">SIMAKIS Live System</span>
                </div>
            </div>

            <!-- Header Right Section -->
            <div class="flex items-center gap-3">
                <!-- Date Pill -->
                <div class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-600">
                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    <span>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                </div>

                <!-- In-App Notification Bell (AJAX - tidak memperlambat page load) -->
                <div class="relative" id="simakis-notif-container">
                    <button type="button"
                            id="simakis-notif-btn"
                            class="relative p-2 rounded-xl text-slate-600 hover:text-blue-600 hover:bg-slate-100 transition border border-transparent hover:border-slate-200 focus:outline-none"
                            aria-label="Notifikasi Pengingat">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        <!-- Badge notifikasi - diisi oleh JavaScript setelah fetch AJAX -->
                        <span id="simakis-notif-badge" class="hidden absolute -top-1 -right-1 flex h-4 w-4">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span id="simakis-notif-count" class="relative inline-flex rounded-full h-4 w-4 bg-rose-600 text-white text-[10px] font-black items-center justify-center leading-none shadow-xs ring-2 ring-white">0</span>
                        </span>
                    </button>

                    <!-- Dropdown Panel -->
                    <div id="simakis-notif-dropdown"
                         class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-slate-200 z-50 overflow-hidden divide-y divide-slate-100">
                        <!-- Header -->
                        <div class="px-4 py-3 bg-slate-50/90 border-b border-slate-100 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs text-slate-900 tracking-tight">Notifikasi</span>
                                <span id="simakis-notif-header-badge" class="hidden inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 whitespace-nowrap"></span>
                            </div>
                            <span class="text-[10px] text-slate-400 font-medium whitespace-nowrap">SIMAKIS Alert</span>
                        </div>

                        <!-- Body: skeleton saat loading, lalu diisi JS -->
                        <div id="simakis-notif-body" class="max-h-80 overflow-y-auto divide-y divide-slate-100">
                            <!-- Skeleton loading -->
                            <div id="simakis-notif-skeleton" class="p-4 space-y-3">
                                <div class="flex items-start gap-3 animate-pulse">
                                    <div class="h-9 w-9 rounded-xl bg-slate-200 shrink-0"></div>
                                    <div class="flex-1 space-y-2">
                                        <div class="h-3 bg-slate-200 rounded w-3/4"></div>
                                        <div class="h-3 bg-slate-200 rounded w-full"></div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 animate-pulse">
                                    <div class="h-9 w-9 rounded-xl bg-slate-200 shrink-0"></div>
                                    <div class="flex-1 space-y-2">
                                        <div class="h-3 bg-slate-200 rounded w-1/2"></div>
                                        <div class="h-3 bg-slate-200 rounded w-5/6"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div id="simakis-notif-footer" class="hidden py-2.5 px-4 bg-slate-50/70 border-t border-slate-100 text-center">
                            <span class="text-[11px] text-slate-400 font-medium">Klik notifikasi untuk langsung menuju halaman terkait</span>
                        </div>
                    </div>
                </div>

                <!-- User Dropdown Pill -->
                <a href="{{ route('profile.show') }}" class="flex items-center gap-2.5 pl-2 pr-3 py-1 rounded-full hover:bg-slate-100 transition border border-transparent hover:border-slate-200 group">
                    @if(auth()->user()->foto_url)
                        <img src="{{ auth()->user()->foto_url }}" alt="" class="h-8 w-8 rounded-full object-cover ring-2 ring-blue-500/40">
                    @else
                        <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-blue-700 to-blue-500 text-white text-xs font-bold flex items-center justify-center shadow-xs">
                            {{ auth()->user()->avatar_initials }}
                        </div>
                    @endif
                    <div class="flex flex-col text-left">
                        <span class="text-xs font-bold text-slate-800 group-hover:text-[#0A4DF3] transition leading-tight truncate max-w-[120px] sm:max-w-[160px]">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-slate-500 font-medium leading-tight capitalize">{{ auth()->user()->role_label }}</span>
                    </div>
                    <svg class="h-3.5 w-3.5 text-slate-400 group-hover:text-[#0A4DF3] transition hidden sm:block" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </a>
            </div>
        </header>

        <!-- Flash Messages / Toast Alerts -->
        @if(session('success'))
            <div class="mx-4 sm:mx-6 lg:mx-8 mt-5">
                <div class="flex items-center justify-between gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-semibold text-emerald-800 shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white shrink-0">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="text-emerald-700 hover:text-emerald-950 text-base font-bold px-1" onclick="this.parentElement.remove()">&times;</button>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="mx-4 sm:mx-6 lg:mx-8 mt-5">
                <div class="flex items-center justify-between gap-3 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-xs font-semibold text-blue-800 shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-6 w-6 items-center justify-center rounded-full bg-[#0A4DF3] text-white shrink-0">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                        </div>
                        <span>{{ session('info') }}</span>
                    </div>
                    <button type="button" class="text-blue-700 hover:text-blue-950 text-base font-bold px-1" onclick="this.parentElement.remove()">&times;</button>
                </div>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="mx-4 sm:mx-6 lg:mx-8 mt-5">
                <div class="flex items-start justify-between gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3.5 text-xs font-semibold text-rose-800 shadow-xs">
                    <div class="flex items-start gap-2.5">
                        <div class="flex h-6 w-6 items-center justify-center rounded-full bg-rose-500 text-white shrink-0 mt-0.5">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div>
                            @if(session('error'))
                                <p>{{ session('error') }}</p>
                            @endif
                            @if($errors->any())
                                <ul class="list-disc list-inside space-y-0.5 mt-0.5 font-normal">
                                    @foreach($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                    <button type="button" class="text-rose-700 hover:text-rose-950 text-base font-bold px-1" onclick="this.parentElement.parentElement.remove()">&times;</button>
                </div>
            </div>
        @endif

        <!-- Main Page Content Canvas -->
        <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-6">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        // Notification dropdown toggle + AJAX fetch
        document.addEventListener('DOMContentLoaded', function () {
            const btn       = document.getElementById('simakis-notif-btn');
            const dropdown  = document.getElementById('simakis-notif-dropdown');
            const container = document.getElementById('simakis-notif-container');
            const badge     = document.getElementById('simakis-notif-badge');
            const countEl   = document.getElementById('simakis-notif-count');
            const headerBadge = document.getElementById('simakis-notif-header-badge');
            const body      = document.getElementById('simakis-notif-body');
            const skeleton  = document.getElementById('simakis-notif-skeleton');
            const footer    = document.getElementById('simakis-notif-footer');

            let loaded = false;

            // Icon SVG helpers
            function iconSvg(icon) {
                const paths = {
                    trophy:   'M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.504-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.004 0A7.472 7.472 0 0018 7.5V6a2.25 2.25 0 00-2.25-2.25h-7.5A2.25 2.25 0 006 6v1.5c0 2.58 1.306 4.856 3.25 6.125',
                    calendar: 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5',
                    clock:    'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
                    document: 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z',
                };
                return `<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="${paths[icon] || paths.document}" /></svg>`;
            }

            function iconBg(type) {
                const map = { target: 'background:#f5f3ff;color:#9333ea', meeting: 'background:#eff6ff;color:#2563eb', followup: 'background:#fffbeb;color:#d97706', handover: 'background:#ecfdf5;color:#059669' };
                return map[type] || 'background:#ecfdf5;color:#059669';
            }

            function levelBadgeCls(level) {
                if (level === 'warning') return 'background:#fffbeb;color:#b45309;border:1px solid #fde68a';
                if (level === 'success') return 'background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0';
                return 'background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe';
            }

            function renderNotifications(notifs) {
                // Hide skeleton
                if (skeleton) skeleton.remove();

                if (notifs.length === 0) {
                    body.innerHTML = `<div class="p-6 text-center">
                        <div class="h-10 w-10 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-base mb-2 font-bold">✓</div>
                        <p class="text-xs font-bold text-slate-700">Semua Tugas Up-to-Date</p>
                        <p class="text-[11px] text-slate-400 mt-1">Tidak ada jadwal rapat atau follow-up tertunda saat ini.</p>
                    </div>`;
                    return;
                }

                body.innerHTML = notifs.map(n => `
                    <a href="${n.action_url || n.url || '#'}"
                       class="p-3.5 flex items-start gap-3 hover:bg-slate-50 transition group block" style="text-decoration:none">
                        <div class="h-9 w-9 rounded-xl shrink-0 flex items-center justify-center text-xs font-bold"
                             style="${iconBg(n.type)}">
                            ${iconSvg(n.icon)}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1.5">
                                <p class="text-xs font-bold text-slate-900 truncate" style="max-width:160px">${n.title || 'Pemberitahuan'}</p>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full shrink-0 whitespace-nowrap"
                                      style="${levelBadgeCls(n.level)}">${n.badge || n.time || 'Info'}</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">${n.message || ''}</p>
                        </div>
                    </a>`).join('');

                // Show footer
                if (footer) footer.classList.remove('hidden');
            }

            function fetchNotifications() {
                if (loaded) return;
                loaded = true;

                fetch('{{ route("notifications.fetch") }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => {
                    const count = data.count || 0;

                    // Update badge
                    if (count > 0) {
                        badge.classList.remove('hidden');
                        countEl.textContent = count > 9 ? '9+' : count;
                        headerBadge.textContent = count + ' Baru';
                        headerBadge.classList.remove('hidden');
                    }

                    renderNotifications(data.notifications || []);
                })
                .catch(() => {
                    if (skeleton) skeleton.remove();
                    body.innerHTML = '<div class="p-4 text-center text-xs text-slate-400">Gagal memuat notifikasi.</div>';
                });
            }

            if (btn && dropdown) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isOpen = !dropdown.classList.contains('hidden');
                    dropdown.classList.toggle('hidden');
                    if (!isOpen) {
                        fetchNotifications();
                    }
                });

                document.addEventListener('click', function (e) {
                    if (container && !container.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                });
            }

            // Auto-fetch setelah 2 detik halaman load (untuk update badge tanpa klik)
            setTimeout(fetchNotifications, 2000);
        });
    </script>
    @stack('scripts')
</body>
</html>
