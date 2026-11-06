@extends('layouts.app')

@section('title', 'Dashboard Sales — SIMAKIS Yamaha JG Purwakarta')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    {{-- ============================================================
         1. TOP HEADER & QUICK ACTIONS
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50 text-[#0A4DF3] border border-blue-100">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#0A4DF3]"></span>
                        Portal Kinerja Sales
                    </span>
                    <span class="text-xs text-slate-400 font-medium">Dealer Resmi Yamaha JG Purwakarta</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                    Selamat Datang, {{ auth()->user()->nama }}!
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Pantau posisi pencapaian pribadi, target reward bulanan 15 unit, dan rekap apresiasi juara penjualan.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('transactions.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A4DF3] hover:bg-blue-700 text-white text-xs font-bold shadow-sm hover:shadow transition-all active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>+ Catat Transaksi Baru</span>
                </a>
                <a href="{{ route('prospects.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold border border-slate-200 shadow-xs transition-all active:scale-95">
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.765z" />
                    </svg>
                    <span>+ Tambah Prospek</span>
                </a>
                <a href="{{ route('field-findings.create') }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-all">
                    <span>+ Temuan Lapangan</span>
                </a>
            </div>
        </div>

        {{-- Active Filter / Current Period Strip --}}
        <div class="mt-4 pt-3.5 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs">
            <div class="flex items-center gap-2 text-slate-600">
                <svg class="h-4 w-4 text-[#0A4DF3]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
                <span>Periode Evaluasi Aktif: <strong class="text-slate-900 font-bold">{{ $periodeLabel }}</strong></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200">
                    Posisi Anda Bulan Ini: <strong class="text-[#0A4DF3] font-extrabold text-sm">#{{ $myRank }}</strong>
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-50 text-[#0A4DF3] font-semibold text-xs border border-blue-100">
                    Target Reward: <strong>{{ $targetRewardUnit }} Unit Motor</strong>
                </span>
            </div>
        </div>
    </div>

    {{-- ============================================================
         2. BAGIAN KHUSUS: PRESTASI SALES (WAJIB ADA SALES TERBAIK)
         (Sales Terbaik Bulan Ini & Sales Terbaik 6 Bulan / Semesteran)
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        {{-- Section Header --}}
        <div class="px-5 py-4 bg-gradient-to-r from-slate-900 via-[#0b1329] to-slate-900 border-b border-slate-800 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-amber-400/20 text-amber-300 border border-amber-400/30 flex items-center justify-center font-extrabold text-xl shadow-xs">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0016.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.98 6.98 0 01-2.77.588 6.98 6.98 0 01-2.77-.588"/></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs uppercase font-extrabold tracking-wider text-amber-300">Papan Apresiasi</span>
                        <span class="text-xs text-slate-400">&bull;</span>
                        <span class="text-xs text-slate-300">Yamaha JG Purwakarta</span>
                    </div>
                    <h2 class="text-base sm:text-lg font-bold text-white tracking-tight">
                        PRESTASI SALES
                    </h2>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-200 bg-white/10 px-3 py-1 rounded-lg border border-white/15 whitespace-nowrap self-start sm:self-auto">
                <svg class="h-3.5 w-3.5 text-amber-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0016.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.98 6.98 0 01-2.77.588 6.98 6.98 0 01-2.77-.588" />
                </svg>
                <span>Standar Penilaian Resmi Supervisor</span>
            </span>
        </div>

        {{-- 2 Kartu Apresiasi (Grid 2 Kolom Seimbang) --}}
        <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- ==================================================== --}}
            {{-- KARTU A: SALES TERBAIK BULAN INI                    --}}
            {{-- ==================================================== --}}
            <div class="rounded-2xl border {{ $isWinnerMonth ? 'border-amber-400 ring-2 ring-amber-400/30 bg-amber-50/20' : 'border-slate-200 bg-white' }} shadow-xs overflow-hidden flex flex-col justify-between transition-all">
                {{-- Header Strip --}}
                <div class="px-5 py-3 border-b {{ $isWinnerMonth ? 'bg-amber-100/70 border-amber-200 text-amber-900' : 'bg-slate-50 border-slate-100 text-slate-700' }} flex items-center justify-between gap-3 flex-wrap sm:flex-nowrap">
                    <div class="flex items-center gap-2 min-w-0">
                        <svg class="h-4 w-4 text-amber-500 fill-amber-400 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <h3 class="text-xs font-extrabold uppercase tracking-wider truncate">
                            Sales Terbaik Bulan Ini
                        </h3>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-md border whitespace-nowrap shrink-0 {{ $isWinnerMonth ? 'bg-amber-100 text-amber-900 border-amber-300' : 'bg-white text-slate-600 border-slate-200' }}">
                        <svg class="h-3.5 w-3.5 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h14.25A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h14.25A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h14.25A2.25 2.25 0 0121 9v7.5" />
                        </svg>
                        <span>{{ $salesTerbaikBulanIni ? $salesTerbaikBulanIni['periode_label'] : $periodeLabel }}</span>
                    </span>
                </div>

                <div class="p-5 space-y-4">
                    {{-- Winner Badge (Bila user adalah pemenang) --}}
                    @if($isWinnerMonth)
                        <div class="p-3.5 rounded-xl text-white shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3 border border-amber-500/40"
                             style="background: linear-gradient(135deg, #92400e 0%, #b45309 50%, #78350f 100%);">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="h-9 w-9 rounded-xl bg-amber-400/20 border border-amber-300/40 flex items-center justify-center text-amber-300 shrink-0">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0016.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.98 6.98 0 01-2.77.588 6.98 6.98 0 01-2.77-.588"/></svg>
                                </span>
                                <div>
                                    <div class="text-[10px] font-black uppercase tracking-wider text-amber-200">Apresiasi Juara</div>
                                    <div class="font-black text-xs sm:text-sm tracking-tight text-white leading-snug">
                                        SELAMAT! ANDA ADALAH SALES TERBAIK BULAN INI!
                                    </div>
                                </div>
                            </div>
                            <div class="shrink-0 self-start sm:self-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-950/80 text-amber-200 border border-amber-400/40 text-[11px] font-black uppercase tracking-wider whitespace-nowrap shadow-sm">
                                    <svg class="h-3 w-3 text-amber-300 fill-amber-300" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span>Juara 1</span>
                                </span>
                            </div>
                        </div>
                    @endif

                    @if($salesTerbaikBulanIni)
                        {{-- Profil Juara Bulan Ini --}}
                        <div class="flex items-center gap-3.5">
                            <div class="relative flex-shrink-0">
                                @if($salesTerbaikBulanIni['user']->foto_url)
                                    <img src="{{ $salesTerbaikBulanIni['user']->foto_url }}" alt="{{ $salesTerbaikBulanIni['user']->nama }}"
                                         class="h-14 w-14 rounded-2xl object-cover ring-2 ring-amber-400 shadow-xs">
                                @else
                                    <div class="h-14 w-14 rounded-2xl bg-amber-100 text-amber-900 font-extrabold text-lg flex items-center justify-center ring-2 ring-amber-400 shadow-xs">
                                        {{ $salesTerbaikBulanIni['user']->avatar_initials }}
                                    </div>
                                @endif
                                <div class="absolute -top-1.5 -right-1.5 h-6 w-6 rounded-full bg-amber-400 text-slate-950 flex items-center justify-center font-bold text-xs shadow-xs">
                                    <svg class="h-3.5 w-3.5 text-amber-950 fill-amber-950" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-base font-bold text-slate-900 truncate">
                                        {{ $salesTerbaikBulanIni['user']->nama }}
                                    </h4>
                                    @if($isWinnerMonth)
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-200 text-amber-900">Akun Anda</span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5 flex flex-wrap items-center gap-1.5">
                                    <span>Kode: <strong class="text-slate-700">{{ $salesTerbaikBulanIni['user']->kode_sales ?? 'SL-001' }}</strong></span>
                                    <span>&bull;</span>
                                    <span>Dealer Yamaha JG Purwakarta</span>
                                </div>
                            </div>
                        </div>

                        {{-- 4 Metrik Juara Bulan Ini --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-1">
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-center">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">Terjual</span>
                                <span class="text-xl font-extrabold text-slate-900 mt-0.5 block">{{ $salesTerbaikBulanIni['units'] }} <span class="text-[11px] font-normal text-slate-500">Unit</span></span>
                                <span class="text-[10px] text-slate-400">Target {{ $salesTerbaikBulanIni['target'] }} unit</span>
                            </div>
                            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200/80 text-center">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 block">Capaian</span>
                                <span class="text-xl font-extrabold text-emerald-600 mt-0.5 block">{{ $salesTerbaikBulanIni['percentage'] }}%</span>
                                <span class="text-[10px] text-emerald-700 font-semibold">{{ $salesTerbaikBulanIni['percentage'] >= 100 ? 'Target Lolos' : 'Hampir Capai' }}</span>
                            </div>
                            <div class="p-3 rounded-xl bg-blue-50 border border-blue-200/80 text-center">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-800 block">Prospek Deal</span>
                                <span class="text-xl font-extrabold text-[#0A4DF3] mt-0.5 block">{{ $salesTerbaikBulanIni['deal_prospects'] }}</span>
                                <span class="text-[10px] text-blue-600">dari {{ $salesTerbaikBulanIni['total_prospects'] }}</span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-center">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">Konversi</span>
                                <span class="text-xl font-extrabold text-slate-800 mt-0.5 block">{{ $salesTerbaikBulanIni['conversion_rate'] }}%</span>
                                <span class="text-[10px] text-slate-400">Rasio Deal</span>
                            </div>
                        </div>

                        {{-- Reward Badge Juara --}}
                        <div class="flex items-center justify-between text-xs px-3.5 py-2 rounded-xl {{ $salesTerbaikBulanIni['reached_reward'] ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                            <span class="font-medium">Status Reward Bulanan (15 Unit):</span>
                            @if($salesTerbaikBulanIni['reached_reward'])
                                <span class="font-extrabold text-emerald-700 flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5 text-amber-500 fill-amber-400 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span>Lolos Reward &bull; Berhak Bonus Dealer</span>
                                </span>
                            @else
                                <span class="font-semibold text-slate-500">
                                    Belum mencapai minimal {{ $targetRewardUnit }} unit
                                </span>
                            @endif
                        </div>
                    @else
                        <div class="p-6 text-center text-xs text-slate-400 italic">
                            Belum ada transaksi valid yang tercatat pada periode ini.
                        </div>
                    @endif
                </div>
            </div>

            {{-- ==================================================== --}}
            {{-- KARTU B: SALES TERBAIK 6 BULAN (SEMESTERAN)        --}}
            {{-- ==================================================== --}}
            <div class="rounded-2xl border {{ $isWinner6m ? 'border-emerald-400 ring-2 ring-emerald-400/30 bg-emerald-50/20' : 'border-slate-200 bg-white' }} shadow-xs overflow-hidden flex flex-col justify-between transition-all">
                {{-- Header Strip --}}
                <div class="px-5 py-3 border-b {{ $isWinner6m ? 'bg-emerald-100/70 border-emerald-200 text-emerald-900' : 'bg-slate-50 border-slate-100 text-slate-700' }} flex items-center justify-between gap-3 flex-wrap sm:flex-nowrap">
                    <div class="flex items-center gap-2 min-w-0">
                        <svg class="h-4 w-4 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0016.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.98 6.98 0 01-2.77.588 6.98 6.98 0 01-2.77-.588"/></svg>
                        <h3 class="text-xs font-extrabold uppercase tracking-wider truncate">
                            Sales Terbaik 6 Bulan (Semesteran)
                        </h3>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-md border whitespace-nowrap shrink-0 {{ $isWinner6m ? 'bg-emerald-100 text-emerald-900 border-emerald-300' : 'bg-white text-slate-600 border-slate-200' }}">
                        <svg class="h-3.5 w-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h14.25A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h14.25A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h14.25A2.25 2.25 0 0121 9v7.5" />
                        </svg>
                        <span>Akumulasi 6 Bulan Terakhir</span>
                    </span>
                </div>

                <div class="p-5 space-y-4">
                    {{-- Winner Badge (Bila user adalah pemenang) --}}
                    @if($isWinner6m)
                        <div class="p-3.5 rounded-xl text-white shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3 border border-emerald-600/50"
                             style="background: linear-gradient(135deg, #065f46 0%, #047857 50%, #064e3b 100%);">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="h-9 w-9 rounded-xl bg-amber-400/20 border border-amber-300/40 flex items-center justify-center text-amber-300 shrink-0 shadow-inner">
                                    <svg class="h-5 w-5 text-amber-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0016.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.98 6.98 0 01-2.77.588 6.98 6.98 0 01-2.77-.588"/></svg>
                                </span>
                                <div>
                                    <div class="text-[10px] font-black uppercase tracking-wider text-emerald-300">Prestasi Penjualan</div>
                                    <div class="font-black text-xs sm:text-sm tracking-tight text-white leading-snug">
                                        SELAMAT! ANDA SALES TERBAIK 6 BULAN (SEMESTERAN)!
                                    </div>
                                </div>
                            </div>
                            <div class="shrink-0 self-start sm:self-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-950/80 text-emerald-200 border border-emerald-400/40 text-[11px] font-black uppercase tracking-wider whitespace-nowrap shadow-sm">
                                    <span class="text-amber-300 text-xs">★</span>
                                    <span>Juara Semester</span>
                                </span>
                            </div>
                        </div>
                    @endif

                    @if($salesTerbaik6Bulan)
                        {{-- Profil Juara 6 Bulan --}}
                        <div class="flex items-center gap-3.5">
                            <div class="relative flex-shrink-0">
                                @if($salesTerbaik6Bulan['user']->foto_url)
                                    <img src="{{ $salesTerbaik6Bulan['user']->foto_url }}" alt="{{ $salesTerbaik6Bulan['user']->nama }}"
                                         class="h-14 w-14 rounded-2xl object-cover ring-2 ring-emerald-400 shadow-xs">
                                @else
                                    <div class="h-14 w-14 rounded-2xl bg-emerald-100 text-emerald-900 font-extrabold text-lg flex items-center justify-center ring-2 ring-emerald-400 shadow-xs">
                                        {{ $salesTerbaik6Bulan['user']->avatar_initials }}
                                    </div>
                                @endif
                                <div class="absolute -top-1.5 -right-1.5 h-6 w-6 rounded-full bg-gradient-to-br from-amber-300 via-amber-400 to-amber-500 text-amber-950 flex items-center justify-center font-bold text-xs shadow-sm ring-1 ring-white/90">
                                    <svg class="h-3.5 w-3.5 text-amber-950" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0016.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.98 6.98 0 01-2.77.588 6.98 6.98 0 01-2.77-.588"/></svg>
                                </div>
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-base font-bold text-slate-900 truncate">
                                        {{ $salesTerbaik6Bulan['user']->nama }}
                                    </h4>
                                    @if($isWinner6m)
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-200 text-emerald-900">Akun Anda</span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5 flex flex-wrap items-center gap-1.5">
                                    <span>Kode: <strong class="text-slate-700">{{ $salesTerbaik6Bulan['user']->kode_sales ?? 'SL-002' }}</strong></span>
                                    <span>&bull;</span>
                                    <span>Dealer Yamaha JG Purwakarta</span>
                                </div>
                            </div>
                        </div>

                        {{-- 4 Metrik Juara 6 Bulan --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-1">
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-center">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">Total 6 Bln</span>
                                <span class="text-xl font-extrabold text-slate-900 mt-0.5 block">{{ $salesTerbaik6Bulan['total_units_6m'] }} <span class="text-[11px] font-normal text-slate-500">Unit</span></span>
                                <span class="text-[10px] text-slate-400">Target {{ $salesTerbaik6Bulan['total_target_6m'] }}</span>
                            </div>
                            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200/80 text-center">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 block">Capaian</span>
                                <span class="text-xl font-extrabold text-emerald-600 mt-0.5 block">{{ $salesTerbaik6Bulan['pct_cumulative'] }}%</span>
                                <span class="text-[10px] text-emerald-700 font-semibold">Kumulatif</span>
                            </div>
                            <div class="p-3 rounded-xl bg-blue-50 border border-blue-200/80 text-center">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-800 block">Rata-rata</span>
                                <span class="text-xl font-extrabold text-[#0A4DF3] mt-0.5 block">{{ $salesTerbaik6Bulan['avg_units_per_month'] }}</span>
                                <span class="text-[10px] text-blue-600">Unit / Bulan</span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-center">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">Konversi</span>
                                <span class="text-xl font-extrabold text-slate-800 mt-0.5 block">{{ $salesTerbaik6Bulan['conversion_rate_6m'] }}%</span>
                                <span class="text-[10px] text-slate-400">Rasio 6 Bln</span>
                            </div>
                        </div>

                        {{-- Konsistensi Badge Juara 6 Bulan --}}
                        <div class="flex items-center justify-between text-xs px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200">
                            <span class="font-medium">Konsistensi Tembus Target Reward (&ge;15 Unit):</span>
                            <span class="font-extrabold text-emerald-700 flex items-center gap-1">
                                <svg class="h-3.5 w-3.5 text-amber-500 fill-amber-400 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span>{{ $salesTerbaik6Bulan['months_reached_target'] }} dari 6 Bulan Tercapai</span>
                            </span>
                        </div>
                    @else
                        <div class="p-6 text-center text-xs text-slate-400 italic">
                            Belum ada rekap data 6 bulan yang tersedia.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- ============================================================
         3. PENCAPAIAN PRIBADI SALES YANG SEDANG LOGIN
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6 space-y-6">
        {{-- Header Identitas Sales Login --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div class="flex items-center gap-4">
                @if(auth()->user()->foto_url)
                    <img src="{{ auth()->user()->foto_url }}" alt="{{ auth()->user()->nama }}"
                         class="h-14 w-14 rounded-2xl object-cover ring-2 ring-blue-500/30 shadow-xs">
                @else
                    <div class="h-14 w-14 rounded-2xl bg-blue-600 text-white font-extrabold text-xl flex items-center justify-center shadow-xs">
                        {{ auth()->user()->avatar_initials }}
                    </div>
                @endif
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-lg font-bold text-slate-900 tracking-tight">
                            Pencapaian Pribadi Anda: {{ auth()->user()->nama }}
                        </h2>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Kode Sales: <strong class="text-slate-800">{{ auth()->user()->kode_sales ?? 'SL-USER' }}</strong> &bull; Cabang: <strong class="text-slate-800">Yamaha JG Purwakarta</strong>
                    </p>
                </div>
            </div>

            {{-- 2 Badge Peringkat Personal --}}
            <div class="flex items-center gap-2.5">
                <div class="px-3.5 py-2 rounded-xl bg-blue-50 border border-blue-200 text-center">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-blue-700 block">Ranking Bulan Ini</span>
                    <span class="text-lg font-black text-[#0A4DF3]">#{{ $myRank }}</span>
                </div>
                <div class="px-3.5 py-2 rounded-xl bg-slate-100 border border-slate-200 text-center">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600 block">Ranking 6 Bulan</span>
                    <span class="text-lg font-black text-slate-800">#{{ $my6mRank }}</span>
                </div>
            </div>
        </div>

        {{-- 4 Kartu KPI Kinerja Pribadi Periode Berjalan --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Unit Terjual Pribadi -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 hover:border-emerald-300 transition-colors">
                <div class="flex items-center justify-between text-slate-400 mb-1.5">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Unit Terjual Bulan Ini</span>
                    <div class="h-7 w-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-emerald-600">{{ $myUnits }} <span class="text-xs font-normal text-slate-500">Unit</span></div>
                <p class="text-[11px] text-slate-400 mt-0.5">Target: {{ $myTarget }} Unit</p>
            </div>

            <!-- Capaian Target Pribadi -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 hover:border-blue-300 transition-colors">
                <div class="flex items-center justify-between text-slate-400 mb-1.5">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Pencapaian Target</span>
                    <div class="h-7 w-7 rounded-lg bg-blue-50 text-[#0A4DF3] flex items-center justify-center font-bold text-xs">
                        %
                    </div>
                </div>
                <div class="text-2xl font-black text-[#0A4DF3]">{{ $myPercentage }}%</div>
                <p class="text-[11px] text-amber-600 font-semibold mt-0.5">Sisa: {{ $myRemaining }} Unit lagi</p>
            </div>

            <!-- Prospek & Rasio Konversi Pribadi -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 hover:border-blue-300 transition-colors">
                <div class="flex items-center justify-between text-slate-400 mb-1.5">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Konversi Prospek</span>
                    <div class="h-7 w-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-800">{{ $myConversionRate }}%</div>
                <p class="text-[11px] text-slate-500 mt-0.5 font-medium">{{ $myDealProspects }} Deal / {{ $myTotalProspects }} Prospek</p>
            </div>

            <!-- Status Kinerja & Kategori Evaluasi -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition-colors">
                <div class="flex items-center justify-between text-slate-400 mb-1.5">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Status Kinerja</span>
                    <span class="h-2 w-2 rounded-full {{ $myPercentage >= 100 ? 'bg-emerald-500' : ($myPercentage >= 75 ? 'bg-amber-400' : 'bg-rose-500') }}"></span>
                </div>
                @php
                    $badgeStyle = match($myStatusKinerja) {
                        'Sangat Baik' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'Baik' => 'bg-blue-50 text-[#0A4DF3] border-blue-200',
                        'Cukup' => 'bg-amber-50 text-amber-700 border-amber-200',
                        default => 'bg-rose-50 text-rose-700 border-rose-200',
                    };
                @endphp
                <div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold border {{ $badgeStyle }} mt-1">
                        {{ $myStatusKinerja }}
                    </span>
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Kategori penilaian reguler</p>
            </div>

        </div>

        {{-- Progress Bar Reward 15 Unit --}}
        <div class="p-5 rounded-2xl bg-gradient-to-r from-slate-900 to-blue-950 text-white space-y-3 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-amber-400 fill-amber-400 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <h3 class="text-sm font-bold text-white tracking-tight">
                        Progress Menuju Reward Bulanan (Minimal {{ $targetRewardUnit }} Unit Motor)
                    </h3>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-extrabold px-3 py-1 rounded-lg whitespace-nowrap {{ $rewardEligible ? 'bg-emerald-400 text-slate-950 border border-emerald-300' : 'bg-white/10 text-amber-300 border border-white/15' }}">
                    @if($rewardEligible)
                        <svg class="h-3.5 w-3.5 text-slate-950 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        <span>TARGET REWARD TERCAPAI!</span>
                    @else
                        <span>Kurang {{ $rewardRemaining }} Unit Lagi</span>
                    @endif
                </span>
            </div>

            <!-- Progress Track -->
            <div class="w-full bg-white/10 rounded-full h-3.5 p-0.5 overflow-hidden ring-1 ring-white/10">
                <div class="bg-gradient-to-r from-blue-500 via-indigo-400 to-amber-300 h-2.5 rounded-full transition-all duration-700"
                     style="width: {{ $rewardProgressPercentage }}%"></div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-300 pt-0.5">
                <span>Realisasi Pribadi: <strong class="text-white">{{ $myUnits }} Unit</strong> / {{ $targetRewardUnit }} Unit</span>
                <span class="font-bold text-amber-300">{{ $rewardProgressPercentage }}% Tercapai</span>
            </div>
        </div>

        {{-- Rekap Akumulatif 6 Bulan Pribadi --}}
        <div class="border-t border-slate-100 pt-5 space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                Pencapaian Akumulasi 6 Bulan Terakhir Anda
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                    <span class="text-[10px] text-slate-500 font-bold uppercase block">Total Unit 6 Bln</span>
                    <span class="text-xl font-extrabold text-slate-900 mt-0.5 block">{{ $my6mUnits }} <span class="text-xs font-normal text-slate-500">Unit</span></span>
                    <span class="text-[10px] text-slate-400">Target: {{ $my6mTarget }} unit</span>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                    <span class="text-[10px] text-slate-500 font-bold uppercase block">Capaian 6 Bln</span>
                    <span class="text-xl font-extrabold text-slate-900 mt-0.5 block">{{ $my6mPct }}%</span>
                    <span class="text-[10px] text-slate-400">Kumulatif tim</span>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                    <span class="text-[10px] text-slate-500 font-bold uppercase block">Rata-rata/Bulan</span>
                    <span class="text-xl font-extrabold text-[#0A4DF3] mt-0.5 block">{{ $my6mAvg }} <span class="text-xs font-normal text-slate-500">Unit</span></span>
                    <span class="text-[10px] text-blue-600 font-medium">Konsistensi unit</span>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                    <span class="text-[10px] text-slate-500 font-bold uppercase block">Konversi 6 Bln</span>
                    <span class="text-xl font-extrabold text-emerald-600 mt-0.5 block">{{ $my6mConvRate }}%</span>
                    <span class="text-[10px] text-emerald-600 font-medium">{{ $my6mDeals }} Deal / {{ $my6mProspects }} Prospek</span>
                </div>
            </div>
        </div>

    </div>

    {{-- ============================================================
         4. RINGKASAN RANKING SALES (LEADERBOARD SUMMARY AMAN UNTUK SALES)
         ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Tabel Ranking 5 Teratas (Col-Span 2) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Ringkasan Klasemen Sales — {{ $periodeLabel }}</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Bandingkan posisi pencapaian Anda terhadap sales teratas.</p>
                </div>
                <span class="text-xs font-semibold text-[#0A4DF3] bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100">
                    Peringkat Anda: #{{ $myRank }}
                </span>
            </div>

            <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 uppercase text-[10px] font-bold tracking-wider bg-slate-50">
                            <th class="py-2.5 px-3 text-center w-16">Peringkat</th>
                            <th class="py-2.5 px-3">Sales Person</th>
                            <th class="py-2.5 px-2 text-center">Unit Terjual</th>
                            <th class="py-2.5 px-2 text-center">Target</th>
                            <th class="py-2.5 px-2">Capaian (%)</th>
                            <th class="py-2.5 px-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($safeLeaderboard as $sItem)
                            @php
                                $isMe = ($sItem['user']->id === auth()->id());
                            @endphp
                            <tr class="transition-colors {{ $isMe ? 'bg-blue-50/70 font-semibold' : 'hover:bg-slate-50/70' }}">
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    @if($sItem['rank'] === 1)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-amber-50 border border-amber-200 text-amber-800 font-bold text-[10px]">
                                            <svg class="h-3 w-3 text-amber-500 fill-amber-500 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            #1
                                        </span>
                                    @elseif($sItem['rank'] === 2)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 text-slate-600 font-bold text-[10px]">
                                            <svg class="h-3 w-3 text-slate-400 fill-slate-400 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            #2
                                        </span>
                                    @elseif($sItem['rank'] === 3)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-orange-50 border border-orange-200 text-orange-700 font-bold text-[10px]">
                                            <svg class="h-3 w-3 text-orange-400 fill-orange-400 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            #3
                                        </span>
                                    @else
                                        <span class="text-slate-400 font-semibold text-[10px]">#{{ $sItem['rank'] }}</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="h-6 w-6 rounded-full {{ $isMe ? 'bg-[#0A4DF3] text-white' : 'bg-slate-100 text-slate-700' }} font-bold text-[10px] flex items-center justify-center flex-shrink-0">
                                            {{ $sItem['user']->avatar_initials }}
                                        </div>
                                        <span>{{ $sItem['user']->nama }}</span>
                                        @if($isMe)
                                            <span class="text-[9px] font-bold px-1.5 py-0.2 rounded bg-blue-200 text-blue-900">Anda</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-2.5 px-2 text-center font-bold text-emerald-600 whitespace-nowrap">
                                    {{ $sItem['units'] }} Unit
                                </td>
                                <td class="py-2.5 px-2 text-center text-slate-500 whitespace-nowrap">
                                    {{ $sItem['target'] }} Unit
                                </td>
                                <td class="py-2.5 px-2 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-12 bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-[#0A4DF3] h-1.5 rounded-full" style="width: {{ min(100, $sItem['percentage']) }}%"></div>
                                        </div>
                                        <span class="font-bold text-slate-800 text-[11px]">{{ $sItem['percentage'] }}%</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    @if($sItem['reached_reward'])
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-200">
                                            <svg class="h-3 w-3 text-purple-600 fill-purple-500 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            <span>Lolos</span>
                                        </span>
                                    @else
                                        <span class="text-[10px] text-slate-400 font-medium">
                                            Kurang {{ max(0, $targetRewardUnit - $sItem['units']) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-slate-400 italic">Belum ada data ranking tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Kolom Kanan: Rapat Terdekat & Catatan Evaluasi (Col-Span 1) --}}
        <div class="space-y-6">

            <!-- Card Agenda Rapat Mendatang -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-3.5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <svg class="h-4 w-4 text-[#0A4DF3]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        <span>Rapat Mingguan Anda</span>
                    </h3>
                    <a href="{{ route('meetings.index') }}" class="text-xs font-semibold text-[#0A4DF3] hover:underline">Semua</a>
                </div>

                @if($nextMeeting)
                    <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50 space-y-2 text-xs">
                        <div class="flex items-start justify-between gap-2">
                            <h4 class="font-bold text-slate-900 line-clamp-1">{{ $nextMeeting->judul_rapat }}</h4>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $nextMeeting->metode_badge }}">
                                {{ ucfirst($nextMeeting->metode) }}
                            </span>
                        </div>
                        <p class="text-slate-500 text-[11px]">
                            📅 {{ \Carbon\Carbon::parse($nextMeeting->tanggal)->locale('id')->isoFormat('D MMMM Y') }} &bull; ⏰ {{ substr($nextMeeting->waktu_mulai, 0, 5) }} WIB
                        </p>
                        @if($nextMeeting->link_zoom)
                            <a href="{{ $nextMeeting->link_zoom }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-xs mt-1">
                                <span>Buka Link Zoom &rarr;</span>
                            </a>
                        @endif
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic py-2">Tidak ada rapat mendatang terjadwal untuk Anda.</p>
                @endif
            </div>

            <!-- Card Catatan Evaluasi Supervisor -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-3.5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <svg class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                        </svg>
                        <span>Catatan Evaluasi Supervisor</span>
                    </h3>
                </div>

                @if($latestEvaluation)
                    <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50 space-y-2 text-xs">
                        <div class="flex items-center justify-between text-slate-500 text-[11px]">
                            <span>Bulan {{ $latestEvaluation->periode_bulan }}/{{ $latestEvaluation->periode_tahun }}</span>
                            <span class="font-bold px-2 py-0.5 rounded text-[11px] {{ $latestEvaluation->badge_class }}">
                                {{ $latestEvaluation->status_kinerja }}
                            </span>
                        </div>
                        @php $catatan = $latestEvaluation->catatan_evaluasi ?: 'Pertahankan performa follow-up prospek Anda!'; @endphp
                        <div class="relative">
                            <p id="eval-text-short" class="text-slate-700 italic leading-relaxed line-clamp-3">"{{ $catatan }}"</p>
                            <p id="eval-text-full" class="text-slate-700 italic leading-relaxed hidden">"{{ $catatan }}"</p>
                            @if(strlen($catatan) > 120)
                                <button onclick="document.getElementById('eval-text-short').classList.toggle('hidden'); document.getElementById('eval-text-full').classList.toggle('hidden'); this.textContent = this.textContent === 'Lihat selengkapnya' ? 'Sembunyikan' : 'Lihat selengkapnya';"
                                        class="text-[11px] font-semibold text-[#0A4DF3] hover:underline mt-1">
                                    Lihat selengkapnya
                                </button>
                            @endif
                        </div>
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic py-2">Belum ada evaluasi tersimpan dari Supervisor untuk akun Anda.</p>
                @endif
            </div>

        </div>

    </div>

    {{-- ============================================================
         5. TRANSAKSI TERBARU PRIBADI SAYA
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Transaksi Penjualan Terakhir Anda</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Riwayat unit motor yang berhasil Anda realisasikan.</p>
            </div>
            <a href="{{ route('transactions.index') }}" class="text-xs font-semibold text-[#0A4DF3] hover:underline">
                Lihat Seluruh Transaksi Saya &rarr;
            </a>
        </div>

        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 uppercase text-[10px] font-bold tracking-wider bg-slate-50">
                        <th class="py-2.5 px-3">Tanggal</th>
                        <th class="py-2.5 px-3">No. Transaksi</th>
                        <th class="py-2.5 px-3">Model Motor</th>
                        <th class="py-2.5 px-3">Nama Konsumen</th>
                        <th class="py-2.5 px-3 text-center">Jumlah Unit</th>
                        <th class="py-2.5 px-3 text-right">Total Nilai</th>
                        <th class="py-2.5 px-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($myRecentTransactions as $trx)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-2.5 px-3 whitespace-nowrap text-slate-500">
                                {{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y') }}
                            </td>
                            <td class="py-2.5 px-3 font-semibold text-slate-900 whitespace-nowrap">
                                {{ $trx->nomor_transaksi ?? 'TRX-' . str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="py-2.5 px-3 font-medium whitespace-nowrap">
                                {{ $trx->motorcycle->nama_model ?? '-' }}
                            </td>
                            <td class="py-2.5 px-3 whitespace-nowrap">
                                {{ $trx->nama_konsumen }}
                            </td>
                            <td class="py-2.5 px-3 text-center font-bold text-emerald-600 whitespace-nowrap">
                                {{ $trx->jumlah_unit }} Unit
                            </td>
                            <td class="py-2.5 px-3 text-right font-medium whitespace-nowrap">
                                Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Valid
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400 italic">
                                Belum ada transaksi penjualan yang tercatat untuk akun Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
