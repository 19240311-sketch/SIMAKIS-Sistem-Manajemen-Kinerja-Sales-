@extends('layouts.guest')

@section('title', 'Masuk ke Sistem')

@section('content')
<style>
    /* ======================================================== */
    /* 1. BACKGROUND FLOATING SHAPES (translateY & translateX) */
    /* ======================================================== */
    @keyframes floatBg1 {
        0%, 100% { transform: translateY(0px) translateX(0px); }
        50% { transform: translateY(-18px) translateX(8px); }
    }
    @keyframes floatBg2 {
        0%, 100% { transform: translateY(0px) translateX(0px); }
        50% { transform: translateY(-22px) translateX(-10px); }
    }
    @keyframes floatBg3 {
        0%, 100% { transform: translateY(0px) translateX(0px); }
        50% { transform: translateY(-14px) translateX(6px); }
    }
    @keyframes floatBgParticle1 {
        0%, 100% { transform: translateY(0px) translateX(0px); opacity: 0.12; }
        50% { transform: translateY(-24px) translateX(-8px); opacity: 0.24; }
    }
    @keyframes floatBgParticle2 {
        0%, 100% { transform: translateY(0px) translateX(0px); opacity: 0.10; }
        50% { transform: translateY(-20px) translateX(10px); opacity: 0.20; }
    }
    @keyframes pulseGlow {
        0%, 100% { opacity: 0.18; transform: scale(1); }
        50% { opacity: 0.28; transform: scale(1.08); }
    }

    /* ======================================================== */
    /* 2. LOGO JG MOTOR (Subtle Vertical Float 3-5px, No Tilt) */
    /* ======================================================== */
    @keyframes logoFloating {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-4px); }
    }

    /* ======================================================== */
    /* 3. BADGE DEALER RESMI YAMAHA (Horizontal Float 4-8px)   */
    /* ======================================================== */
    @keyframes badgeFloatingHorizontal {
        0%, 100% { transform: translateX(0px); }
        50% { transform: translateX(6px); }
    }

    /* ======================================================== */
    /* 4. THREE FEATURE CARDS (Distinct Timings & Offsets)     */
    /* ======================================================== */
    @keyframes cardFloat1 {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
    }
    @keyframes cardFloat2 {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(5px); }
    }
    @keyframes cardFloat3 {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-4px); }
    }

    /* ======================================================== */
    /* 5. CARD ICONS (Subtle Float & Scale Inside Cards)       */
    /* ======================================================== */
    @keyframes iconSubtleFloat {
        0%, 100% { transform: translateY(0px) scale(1); }
        50% { transform: translateY(-2.5px) scale(1.05); }
    }

    /* ======================================================== */
    /* 6. BOTTOM STATUS BADGE (Horizontal 3-5px & Soft Glow)   */
    /* ======================================================== */
    @keyframes bottomPillFloating {
        0%, 100% { 
            transform: translateX(0px); 
            box-shadow: 0 4px 14px rgba(10,30,70,0.18);
            opacity: 0.92;
        }
        50% { 
            transform: translateX(4px); 
            box-shadow: 0 6px 20px rgba(37,99,235,0.28);
            opacity: 1;
        }
    }

    /* ======================================================== */
    /* ANIMATION UTILITY CLASSES (Durations: 5s - 10s)         */
    /* ======================================================== */
    .animate-bg-float-1 { animation: floatBg1 8.5s ease-in-out infinite; }
    .animate-bg-float-2 { animation: floatBg2 9.5s ease-in-out infinite 1.8s; }
    .animate-bg-float-3 { animation: floatBg3 7.5s ease-in-out infinite 3.2s; }
    .animate-bg-particle-1 { animation: floatBgParticle1 9s ease-in-out infinite 0.5s; }
    .animate-bg-particle-2 { animation: floatBgParticle2 11s ease-in-out infinite 4s; }
    .animate-glow-pulse { animation: pulseGlow 8s ease-in-out infinite; }

    .animate-logo-float { animation: logoFloating 5.5s ease-in-out infinite; }
    .animate-badge-float { animation: badgeFloatingHorizontal 6.8s ease-in-out infinite 1s; }

    .animate-feature-card-1 { animation: cardFloat1 5.8s ease-in-out infinite 0s; }
    .animate-feature-card-2 { animation: cardFloat2 6.5s ease-in-out infinite 1.2s; }
    .animate-feature-card-3 { animation: cardFloat3 5.4s ease-in-out infinite 2.4s; }

    .animate-card-icon-1 { animation: iconSubtleFloat 4.8s ease-in-out infinite 0.2s; }
    .animate-card-icon-2 { animation: iconSubtleFloat 5.2s ease-in-out infinite 1.5s; }
    .animate-card-icon-3 { animation: iconSubtleFloat 4.5s ease-in-out infinite 2.8s; }

    .animate-bottom-pill { animation: bottomPillFloating 7.2s ease-in-out infinite 1.5s; }

    /* ======================================================== */
    /* ACCESSIBILITY: PREFERS-REDUCED-MOTION                   */
    /* ======================================================== */
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            animation-delay: 0ms !important;
        }
    }
</style>

<div class="relative min-h-screen w-full flex items-center justify-center p-4 sm:p-6 lg:p-10 overflow-hidden"
     style="background: linear-gradient(145deg, #183A60 0%, #1e4673 45%, #214A75 65%, #173653 100%);">

    {{-- ======================================================== --}}
    {{-- BACKGROUND DECORATIVE FLOATING BLOBS & SHAPES (LOW OPACITY) --}}
    {{-- ======================================================== --}}
    
    {{-- Ambient radial background lights --}}
    <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full pointer-events-none animate-glow-pulse"
         style="background: radial-gradient(circle, rgba(37,99,235,0.25) 0%, transparent 70%); filter: blur(50px);"></div>
    <div class="absolute -bottom-32 -right-32 w-[460px] h-[460px] rounded-full pointer-events-none animate-glow-pulse"
         style="background: radial-gradient(circle, rgba(14,165,233,0.18) 0%, transparent 70%); filter: blur(60px); animation-delay: 3s;"></div>
    <div class="absolute top-1/2 left-1/3 -translate-y-1/2 w-80 h-80 rounded-full pointer-events-none"
         style="background: radial-gradient(circle, rgba(30,64,175,0.2) 0%, transparent 75%); filter: blur(55px);"></div>

    {{-- Subtle top highlight accent line --}}
    <div class="absolute top-0 left-0 right-0 h-[2px] pointer-events-none"
         style="background: linear-gradient(90deg, transparent 5%, rgba(59,130,246,0.5) 45%, rgba(96,165,250,0.6) 55%, transparent 95%);"></div>

    {{-- Floating Decorative Glass Shapes (Different sizes, slow drift & subtle delays) --}}
    {{-- Shape 1: Top-left floating circle --}}
    <div class="hidden sm:block absolute top-12 left-10 lg:left-20 w-24 h-24 rounded-full border border-white/10 bg-white/5 backdrop-blur-md pointer-events-none animate-bg-float-1"
         style="box-shadow: 0 8px 32px rgba(10,30,70,0.25);">
        <div class="w-full h-full rounded-full flex items-center justify-center opacity-40">
            <div class="w-8 h-8 rounded-full border border-blue-200/20"></div>
        </div>
    </div>

    {{-- Shape 2: Bottom-left decorative pill --}}
    <div class="hidden sm:block absolute bottom-14 left-12 w-28 h-12 rounded-full border border-white/10 bg-white/5 backdrop-blur-md pointer-events-none animate-bg-float-3"
         style="box-shadow: 0 8px 24px rgba(10,30,70,0.2);"></div>

    {{-- Shape 3: Floating small bubble near center-right --}}
    <div class="hidden md:block absolute top-20 right-16 lg:right-32 w-16 h-16 rounded-full border border-blue-300/15 bg-blue-500/5 backdrop-blur-sm pointer-events-none animate-bg-float-2"></div>

    {{-- Shape 4: Subtle floating bubble near bottom-right --}}
    <div class="hidden sm:block absolute bottom-12 right-20 w-20 h-20 rounded-full border border-white/10 bg-white/5 backdrop-blur-md pointer-events-none animate-bg-float-1"
         style="animation-delay: 2.5s;"></div>

    {{-- Additional Natural Floating Transparent Particles / Bubbles --}}
    <div class="hidden lg:block absolute top-1/3 left-1/4 w-12 h-12 rounded-full border border-blue-200/10 bg-blue-400/5 backdrop-blur-sm pointer-events-none animate-bg-particle-1"></div>
    <div class="hidden lg:block absolute bottom-1/4 left-1/2 w-10 h-10 rounded-full border border-white/10 bg-white/5 backdrop-blur-sm pointer-events-none animate-bg-particle-2"></div>
    <div class="hidden md:block absolute top-2/3 right-1/4 w-14 h-14 rounded-full border border-blue-300/10 bg-blue-500/5 backdrop-blur-sm pointer-events-none animate-bg-particle-1" style="animation-delay: 5s;"></div>

    {{-- Bottom vignette for rich grounded feel --}}
    <div class="absolute bottom-0 left-0 right-0 h-28 pointer-events-none"
         style="background: linear-gradient(to top, rgba(16,38,62,0.45) 0%, transparent 100%);"></div>

    {{-- ======================================================== --}}
    {{-- MAIN 2-COLUMN ENTERPRISE LAYOUT CONTAINER               --}}
    {{-- ======================================================== --}}
    <div class="w-full max-w-6xl mx-auto relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center py-4">

        {{-- ==================================================== --}}
        {{-- KOLOM KIRI: AREA BRANDING & VISUAL SALES ECOSYSTEM   --}}
        {{-- ==================================================== --}}
        <div class="lg:col-span-7 flex flex-col justify-center text-left text-white px-1 sm:px-4">

            {{-- Brand Logo & Official Dealer Tag --}}
            <div class="flex flex-wrap items-center gap-3.5 mb-5 sm:mb-6">
                {{-- Logo JG MOTOR with subtle vertical floating animation --}}
                <div class="relative inline-flex items-center animate-logo-float">
                    {{-- Soft logo halo for crisp separation --}}
                    <div class="absolute -inset-2 rounded-2xl bg-white/10 filter blur-md pointer-events-none"></div>
                    <img src="{{ asset('images/logo.png') }}?v={{ file_exists(public_path('images/logo.png')) ? filemtime(public_path('images/logo.png')) : time() }}"
                         alt="JG Motor Purwakarta - SIMAKIS"
                         class="relative h-14 sm:h-16 lg:h-20 w-auto object-contain"
                         style="filter: drop-shadow(0 4px 14px rgba(0,0,0,0.35));">
                </div>

                {{-- Official Badge with smooth horizontal floating animation --}}
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-blue-100 text-xs font-semibold tracking-wide shadow-sm animate-badge-float">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Dealer Resmi Yamaha</span>
                </div>
            </div>

            {{-- Main Titles & Description --}}
            <div class="space-y-3 mb-7 sm:mb-8">
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-none"
                        style="font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif; text-shadow: 0 2px 12px rgba(0,0,0,0.25);">
                        SIMAKIS
                    </h1>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-blue-500/25 border border-blue-400/30 text-blue-200 uppercase tracking-widest">
                        Enterprise v2.0
                    </span>
                </div>

                <h2 class="text-lg sm:text-xl font-bold leading-snug" style="color: #e0edff;">
                    Sistem Manajemen Kinerja Sales
                </h2>

                <p class="text-sm sm:text-base leading-relaxed max-w-xl font-normal" style="color: rgba(205,225,250,0.88);">
                    Kelola kinerja penjualan sales, target, reward, rapat mingguan, dan laporan operasional secara terintegrasi dalam satu sistem terpusat.
                </p>
            </div>

            {{-- Visual Sales Ecosystem & Feature Cards with individual floating animations --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 sm:gap-3 lg:gap-3.5 mb-6">

                {{-- Feature 1: Prospek & Target Konsumen (Card 1: translateY -5px) --}}
                <div class="group rounded-xl p-3.5 sm:p-4 bg-white/10 hover:bg-white/15 backdrop-blur-md border border-white/15 hover:border-white/25 transition-all duration-300 shadow-sm hover:shadow-md animate-feature-card-1">
                    <div class="w-9 h-9 rounded-lg bg-blue-500/30 border border-blue-400/40 flex items-center justify-center text-blue-200 mb-2.5 group-hover:scale-105 transition-transform animate-card-icon-1">
                        {{-- Target / Prospek Icon --}}
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xs sm:text-[13px] font-bold text-white tracking-wide">
                        Prospek Konsumen
                    </h3>
                    <p class="text-[11px] leading-relaxed mt-1" style="color: rgba(200,225,255,0.75);">
                        Pipeline prospek Cold, Warm, Hot, hingga closing SPK.
                    </p>
                </div>

                {{-- Feature 2: Aktivitas Sales & Checklist (Card 2: translateY +5px) --}}
                <div class="group rounded-xl p-3.5 sm:p-4 bg-white/10 hover:bg-white/15 backdrop-blur-md border border-white/15 hover:border-white/25 transition-all duration-300 shadow-sm hover:shadow-md animate-feature-card-2">
                    <div class="w-9 h-9 rounded-lg bg-emerald-500/25 border border-emerald-400/40 flex items-center justify-center text-emerald-200 mb-2.5 group-hover:scale-105 transition-transform animate-card-icon-2">
                        {{-- Checklist / Aktivitas Icon --}}
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xs sm:text-[13px] font-bold text-white tracking-wide">
                        Aktivitas &amp; Agenda
                    </h3>
                    <p class="text-[11px] leading-relaxed mt-1" style="color: rgba(200,225,255,0.75);">
                        Canvassing, pameran, telemarketing &amp; jadwal harian.
                    </p>
                </div>

                {{-- Feature 3: Unit Motor & Grafik Penjualan (Card 3: translateY -4px) --}}
                <div class="group rounded-xl p-3.5 sm:p-4 bg-white/10 hover:bg-white/15 backdrop-blur-md border border-white/15 hover:border-white/25 transition-all duration-300 shadow-sm hover:shadow-md animate-feature-card-3">
                    <div class="w-9 h-9 rounded-lg bg-amber-500/25 border border-amber-400/40 flex items-center justify-center text-amber-200 mb-2.5 group-hover:scale-105 transition-transform animate-card-icon-3">
                        {{-- Motorcycle / Unit Sales Icon --}}
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                        </svg>
                    </div>
                    <h3 class="text-xs sm:text-[13px] font-bold text-white tracking-wide">
                        Distribusi Unit Yamaha
                    </h3>
                    <p class="text-[11px] leading-relaxed mt-1" style="color: rgba(200,225,255,0.75);">
                        Monitoring target penjualan &amp; realisasi unit motor.
                    </p>
                </div>
            </div>

            {{-- Floating Live Status Micro-Pill (Horizontal 3-5px + Subtle Glow) --}}
            <div class="hidden sm:inline-flex items-center gap-3 py-2 px-3.5 rounded-xl bg-white/8 backdrop-blur-md border border-white/10 w-fit text-xs text-blue-100 shadow-sm animate-bottom-pill">
                {{-- Motorcycle Icon --}}
                <svg class="w-4 h-4 text-blue-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="5.5" cy="17.5" r="3.5" />
                    <circle cx="18.5" cy="17.5" r="3.5" />
                    <path d="M15 6h-3l-2.5 5.5h-4" />
                    <path d="M12 11.5L14 17.5" />
                    <path d="M18.5 17.5L16 9h3" />
                </svg>
                <span>Monitoring Penjualan Dealer Yamaha JG Motor Purwakarta</span>
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
            </div>

        </div>

        {{-- ==================================================== --}}
        {{-- KOLOM KANAN: AREA LOGIN CARD                         --}}
        {{-- ==================================================== --}}
        <div class="lg:col-span-5 w-full max-w-[430px] mx-auto lg:max-w-none relative">

            {{-- Soft Decorative Ambient Card Glow Backdrop --}}
            <div class="absolute -inset-1.5 rounded-[28px] pointer-events-none opacity-30"
                 style="background: linear-gradient(135deg, rgba(255,255,255,0.4) 0%, rgba(37,99,235,0.3) 100%); filter: blur(12px);"></div>

            {{-- Main Login Card Container --}}
            <div class="relative rounded-2xl sm:rounded-3xl p-7 sm:p-9 bg-white"
                 style="box-shadow: 0 20px 45px -10px rgba(10,25,50,0.38), 0 0 0 1px rgba(226,232,240,0.9);">

                {{-- Card Header --}}
                <div class="mb-6 text-center sm:text-left">
                    <div class="inline-flex items-center gap-2 mb-2">
                        <span class="h-2 w-2 rounded-full bg-[#0A4DF3]"></span>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Autentikasi Akun</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight"
                        style="font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;">
                        Masuk ke Sistem
                    </h2>
                    <p class="mt-1 text-xs sm:text-[13px] text-slate-500">
                        Masukkan email dan kata sandi Anda untuk mengakses portal SIMAKIS.
                    </p>
                </div>

                {{-- Alert info session --}}
                @if(session('info'))
                    <div class="mb-5 rounded-xl bg-blue-50 p-3.5 text-xs text-blue-800 border border-blue-100 flex items-center gap-2.5">
                        <svg class="h-4 w-4 shrink-0 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-5 rounded-xl bg-amber-50 p-3.5 text-xs text-amber-800 border border-amber-200 flex items-center gap-2.5">
                        <svg class="h-4 w-4 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        <span>{{ session('warning') }}</span>
                    </div>
                @endif

                {{-- Login Form --}}
                <form class="space-y-4 sm:space-y-5" action="{{ route('login.post') }}" method="POST">
                    @csrf

                    {{-- Email Field --}}
                    <div>
                        <label for="email" class="mb-1.5 block text-[11px] font-bold uppercase tracking-[0.08em] text-slate-600">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                   value="{{ old('email') }}"
                                   placeholder="nama@simakis.com"
                                   style="border-color: #e2e8f0; background: #f8fafc;"
                                   class="h-11 w-full rounded-xl border pl-11 pr-4 text-sm font-medium text-slate-800 placeholder:text-slate-400 hover:border-slate-300 focus:bg-white focus:border-[#0A4DF3] focus:ring-2 focus:ring-blue-100 focus:outline-none transition @error('email') !border-red-400 !bg-red-50 @enderror">
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-[11px] text-red-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Field --}}
                    <div>
                        <label for="password" class="mb-1.5 block text-[11px] font-bold uppercase tracking-[0.08em] text-slate-600">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                   placeholder="••••••••"
                                   style="border-color: #e2e8f0; background: #f8fafc;"
                                   class="h-11 w-full rounded-xl border pl-11 pr-11 text-sm font-medium text-slate-800 placeholder:text-slate-400 hover:border-slate-300 focus:bg-white focus:border-[#0A4DF3] focus:ring-2 focus:ring-blue-100 focus:outline-none transition @error('password') !border-red-400 !bg-red-50 @enderror">
                            
                            {{-- Show/Hide Toggle --}}
                            <button type="button" onclick="togglePasswordVisibility()"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 transition cursor-pointer text-slate-400 hover:text-[#0A4DF3]"
                                    title="Tampilkan / Sembunyikan Kata Sandi">
                                <svg id="eyeIcon" class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg id="eyeSlashIcon" class="h-[18px] w-[18px] hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-[11px] text-red-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center pt-0.5">
                        <input id="remember" name="remember" type="checkbox"
                               class="h-[16px] w-[16px] rounded border-slate-300 text-[#0A4DF3] cursor-pointer focus:ring-2 focus:ring-blue-200">
                        <label for="remember" class="ml-2 block text-xs sm:text-[13px] font-medium text-slate-600 cursor-pointer select-none">
                            Ingat saya di perangkat ini
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-2">
                        <button type="submit"
                                class="w-full inline-flex h-12 items-center justify-center gap-2.5 rounded-xl text-sm font-bold text-white tracking-wide transition-all duration-200 cursor-pointer focus:outline-none focus:ring-4 focus:ring-blue-200 active:scale-[0.98]"
                                style="background: linear-gradient(180deg, #0A4DF3 0%, #073dc4 100%); box-shadow: 0 4px 14px rgba(10,77,243,0.35);"
                                onmouseover="this.style.background='linear-gradient(180deg, #073dc4 0%, #06319e 100%)'; this.style.boxShadow='0 6px 18px rgba(10,77,243,0.45)';"
                                onmouseout="this.style.background='linear-gradient(180deg, #0A4DF3 0%, #073dc4 100%)'; this.style.boxShadow='0 4px 14px rgba(10,77,243,0.35)';">
                            <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                            <span>Masuk ke Sistem</span>
                        </button>
                    </div>
                </form>

                {{-- Card Footer Note --}}
                <div class="mt-6 pt-5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-400 gap-1.5">
                    <span class="font-medium text-slate-500">JG Motor Purwakarta</span>
                    <span>SIMAKIS &copy; {{ date('Y') }}</span>
                </div>
            </div>

            {{-- Security & Privacy Mini Note under card --}}
            <div class="mt-4 text-center">
                <p class="text-[11px] text-blue-200/60 font-medium">
                    Sistem resmi tertutup khusus karyawan &amp; manajemen JG Motor
                </p>
            </div>

        </div>

    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const input = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeSlashIcon = document.getElementById('eyeSlashIcon');

        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeSlashIcon.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeSlashIcon.classList.add('hidden');
        }
    }
</script>
@endsection
