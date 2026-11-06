@extends('layouts.app')

@section('title', 'Dashboard Supervisor')

@section('content')
<div class="space-y-6">

    {{-- ============================================================
         SECTION 1: HEADER & FILTER PERIODE
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            {{-- Title & Dealer Info --}}
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50 text-[#0A4DF3] border border-blue-100">
                        Portal Supervisor
                    </span>
                    <span class="text-xs text-slate-400 font-medium">Dealer Yamaha JG Purwakarta</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    Dashboard Manajemen Kinerja Sales
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Monitoring prospek, follow-up konsumen, transaksi penjualan, target bulanan, reward 15 unit, dan evaluasi 6 bulan.
                </p>
            </div>

            {{-- Filter Periode Form --}}
            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-center gap-2">
                <input type="hidden" name="data_mode" value="{{ $dataMode }}">

                {{-- Toggle Cepat Periode --}}
                <div class="inline-flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 text-xs">
                    <button type="submit" name="period_type" value="today"
                            class="px-2.5 py-1 text-xs font-semibold rounded-lg transition {{ $filterType === 'today' ? 'bg-white text-[#0A4DF3] shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                        Hari Ini
                    </button>
                    <button type="submit" name="period_type" value="week"
                            class="px-2.5 py-1 text-xs font-semibold rounded-lg transition {{ $filterType === 'week' ? 'bg-white text-[#0A4DF3] shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                        Minggu Ini
                    </button>
                    <button type="submit" name="period_type" value="month"
                            class="px-2.5 py-1 text-xs font-semibold rounded-lg transition {{ $filterType === 'month' ? 'bg-white text-[#0A4DF3] shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                        Bulan Ini
                    </button>
                    <button type="submit" name="period_type" value="year"
                            class="px-2.5 py-1 text-xs font-semibold rounded-lg transition {{ $filterType === 'year' ? 'bg-white text-[#0A4DF3] shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                        Tahun Ini
                    </button>
                </div>

                {{-- Pilih Bulan & Tahun Khusus --}}
                <div class="flex items-center gap-1.5">
                    <select name="bulan" onchange="this.form.submit()"
                            class="h-9 min-w-[135px] rounded-xl border border-slate-200 bg-white pl-3 pr-8 text-xs font-semibold text-slate-700 hover:border-slate-300 focus:border-[#0A4DF3] focus:ring-1 focus:ring-[#0A4DF3]/20 focus:outline-none transition cursor-pointer">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(2026, $m, 1)->locale('id')->isoFormat('MMMM') }}
                            </option>
                        @endfor
                    </select>
                    <select name="tahun" onchange="this.form.submit()"
                            class="h-9 min-w-[95px] rounded-xl border border-slate-200 bg-white pl-3 pr-8 text-xs font-semibold text-slate-700 hover:border-slate-300 focus:border-[#0A4DF3] focus:ring-1 focus:ring-[#0A4DF3]/20 focus:outline-none transition cursor-pointer">
                        @for($y = 2027; $y >= 2024; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </form>
        </div>

        {{-- Active Filter Indicator Banner --}}
        <div class="mt-4 pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs text-slate-500">
            <div class="flex items-center gap-2">
                <svg class="h-4 w-4 text-[#0A4DF3]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
                <span>Menampilkan periode aktif: <strong class="text-slate-800 font-semibold">{{ $periodeLabel }}</strong></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 text-[#0A4DF3] font-semibold text-[11px] border border-blue-100">
                    Target Reward Bulanan: {{ $targetRewardUnit }} Unit Motor
                </span>
            </div>
        </div>
    </div>

    {{-- ============================================================
         SECTION 2: HALL OF FAME — APRESIASI SALES TERBAIK
         (BULAN INI & 6 BULAN / SEMESTERAN)
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        {{-- Header Hall of Fame & Switcher --}}
        <div class="px-5 py-3.5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <div class="h-8 w-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-base border border-amber-100">
                    <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0016.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.98 6.98 0 01-2.77.588 6.98 6.98 0 01-2.77-.588"/></svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900 tracking-tight">Hall of Fame — Apresiasi Sales Terbaik</h2>
                    <p class="text-[11px] text-slate-400">Pajangan prestasi tenaga penjual periode bulanan dan semesteran (6 bulan).</p>
                </div>
            </div>

            {{-- Switcher Button --}}
            <div class="inline-flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 text-xs self-start sm:self-auto">
                <button type="button" onclick="switchShowcase('all')" id="btn-showcase-all"
                        class="px-3 py-1 font-bold rounded-lg transition bg-white text-[#0A4DF3] shadow-xs">
                    Tampilkan Keduanya
                </button>
                <button type="button" onclick="switchShowcase('month')" id="btn-showcase-month"
                        class="inline-flex items-center gap-1.5 px-3 py-1 font-semibold rounded-lg transition text-slate-600 hover:text-slate-900">
                    <svg class="h-3.5 w-3.5 text-amber-500 fill-amber-400 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <span>Bulan Ini</span>
                </button>
                <button type="button" onclick="switchShowcase('six_months')" id="btn-showcase-six"
                        class="inline-flex items-center gap-1.5 px-3 py-1 font-semibold rounded-lg transition text-slate-600 hover:text-slate-900">
                    <svg class="h-3.5 w-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0016.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.98 6.98 0 01-2.77.588 6.98 6.98 0 01-2.77-.588"/></svg>
                    <span>6 Bulan</span>
                </button>
            </div>
        </div>

        {{-- Isi Showcase Cards --}}
        <div class="p-5 space-y-4">

            {{-- 1. Kartu Sales Terbaik Bulan Ini --}}
            @if($salesTerbaikBulanIni)
            <div id="card-showcase-month" class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-xs transition">
                {{-- Header Strip Kuning/Emas Tipis --}}
                <div class="bg-amber-50/70 border-b border-amber-100 px-5 py-2.5 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-amber-500 fill-amber-400 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="text-xs font-bold text-amber-900 uppercase tracking-wide">Sales Terbaik Bulan Ini</span>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-amber-900 bg-amber-100/90 px-2.5 py-1 rounded-md border border-amber-300 whitespace-nowrap shrink-0 shadow-2xs">
                        <svg class="h-3.5 w-3.5 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h14.25A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h14.25A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h14.25A2.25 2.25 0 0121 9v7.5" />
                        </svg>
                        <span>Periode: {{ $salesTerbaikBulanIni['periode_label'] }}</span>
                    </span>
                </div>

                <div class="p-5 space-y-4">
                    {{-- Baris Profil Sales --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="relative flex-shrink-0">
                                @if($salesTerbaikBulanIni['user']->foto_url)
                                    <img src="{{ $salesTerbaikBulanIni['user']->foto_url }}" alt="{{ $salesTerbaikBulanIni['user']->nama }}"
                                         class="h-16 w-16 rounded-2xl object-cover border-2 border-amber-300 shadow-xs">
                                @else
                                    <div class="h-16 w-16 rounded-2xl bg-amber-100 text-amber-800 font-extrabold text-xl flex items-center justify-center border-2 border-amber-300 shadow-xs">
                                        {{ $salesTerbaikBulanIni['user']->avatar_initials }}
                                    </div>
                                @endif
                                <div class="absolute -top-1.5 -right-1.5 h-6 w-6 rounded-full bg-amber-400 text-slate-900 flex items-center justify-center font-bold text-xs shadow-xs">
                                    <svg class="h-3.5 w-3.5 text-amber-950 fill-amber-950" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                            </div>

                            <div class="min-w-0">
                                <div class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight truncate">
                                    {{ $salesTerbaikBulanIni['user']->nama }}
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5 flex flex-wrap items-center gap-2">
                                    <span>Kode: <strong class="text-slate-700">{{ $salesTerbaikBulanIni['user']->kode_sales ?? 'SL-002' }}</strong></span>
                                    <span>•</span>
                                    <span>Dealer Yamaha JG Purwakarta</span>
                                </div>
                            </div>
                        </div>

                        {{-- Badge Pencapaian Reward di Sisi Kanan --}}
                        @if($salesTerbaikBulanIni['reached_reward'])
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold self-start sm:self-center flex-shrink-0">
                                <svg class="h-4 w-4 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span>Lolos Reward {{ $targetRewardUnit }} Unit &bull; Bonus Rp 1.500.000</span>
                            </div>
                        @endif
                    </div>

                    {{-- 4 Kolom Statistik Metrik (Full Width, Tidak Berantakan & Tanpa Tabrakan) --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1">
                        {{-- Unit Terjual --}}
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70 text-center">
                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Unit Terjual</p>
                            <div class="text-2xl font-black text-slate-900 mt-1">
                                {{ $salesTerbaikBulanIni['units'] }} <span class="text-xs font-normal text-slate-500">Unit</span>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-0.5">Target: {{ $salesTerbaikBulanIni['target'] }} Unit</p>
                        </div>

                        {{-- Capaian Target --}}
                        <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-200/70 text-center">
                            <p class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Capaian</p>
                            <div class="text-2xl font-black text-emerald-600 mt-1">
                                {{ $salesTerbaikBulanIni['percentage'] }}%
                            </div>
                            <p class="text-[11px] text-emerald-600 font-semibold mt-0.5">
                                {{ $salesTerbaikBulanIni['percentage'] >= 100 ? 'Target Tercapai' : 'Mendekati' }}
                            </p>
                        </div>

                        {{-- Prospek Deal --}}
                        <div class="p-3.5 rounded-xl bg-blue-50/70 border border-blue-200/70 text-center">
                            <p class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Prospek Deal</p>
                            <div class="text-2xl font-black text-[#0A4DF3] mt-1">
                                {{ $salesTerbaikBulanIni['deal_prospects'] }}
                            </div>
                            <p class="text-[11px] text-blue-600 mt-0.5">dari {{ $salesTerbaikBulanIni['total_prospects'] }} prospek</p>
                        </div>

                        {{-- Rasio Konversi --}}
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70 text-center">
                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Konversi</p>
                            <div class="text-2xl font-black text-slate-800 mt-1">
                                {{ $salesTerbaikBulanIni['conversion_rate'] }}%
                            </div>
                            <p class="text-[11px] text-slate-400 mt-0.5">Rasio Deal</p>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div id="card-showcase-month" class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-center text-xs text-slate-500">
                Belum ada data transaksi valid tercatat untuk menentukan Sales Terbaik pada periode {{ $periodeLabel }}.
            </div>
            @endif

            {{-- 2. Kartu Sales Terbaik 6 Bulan / Semesteran --}}
            @if($salesTerbaik6Bulan)
            <div id="card-showcase-six" class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-xs transition">
                {{-- Header Strip Hijau/Emerald Tipis --}}
                <div class="bg-emerald-50/70 border-b border-emerald-100 px-5 py-2.5 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0016.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.98 6.98 0 01-2.77.588 6.98 6.98 0 01-2.77-.588"/></svg>
                        <span class="text-xs font-bold text-emerald-900 uppercase tracking-wide">Sales Terbaik 6 Bulan (Semesteran)</span>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-900 bg-emerald-100/90 px-2.5 py-1 rounded-md border border-emerald-300 whitespace-nowrap shrink-0 shadow-2xs">
                        <svg class="h-3.5 w-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h14.25A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h14.25A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h14.25A2.25 2.25 0 0121 9v7.5" />
                        </svg>
                        <span>Rentang Evaluasi: {{ $recapPeriodeLabel }}</span>
                    </span>
                </div>

                <div class="p-5 space-y-4">
                    {{-- Baris Profil Sales 6 Bulan --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="relative flex-shrink-0">
                                @if($salesTerbaik6Bulan['user']->foto_url)
                                    <img src="{{ $salesTerbaik6Bulan['user']->foto_url }}" alt="{{ $salesTerbaik6Bulan['user']->nama }}"
                                         class="h-16 w-16 rounded-2xl object-cover border-2 border-emerald-300 shadow-xs">
                                @else
                                    <div class="h-16 w-16 rounded-2xl bg-emerald-100 text-emerald-800 font-extrabold text-xl flex items-center justify-center border-2 border-emerald-300 shadow-xs">
                                        {{ $salesTerbaik6Bulan['user']->avatar_initials }}
                                    </div>
                                @endif
                                <div class="absolute -top-1.5 -right-1.5 h-6 w-6 rounded-full bg-gradient-to-br from-amber-300 via-amber-400 to-amber-500 text-amber-950 flex items-center justify-center font-bold text-xs shadow-sm ring-1 ring-white/90">
                                    <svg class="h-3.5 w-3.5 text-amber-950" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0016.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.98 6.98 0 01-2.77.588 6.98 6.98 0 01-2.77-.588"/></svg>
                                </div>
                            </div>

                            <div class="min-w-0">
                                <div class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight truncate">
                                    {{ $salesTerbaik6Bulan['user']->nama }}
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5 flex flex-wrap items-center gap-2">
                                    <span>Kode: <strong class="text-slate-700">{{ $salesTerbaik6Bulan['user']->kode_sales ?? 'SL-002' }}</strong></span>
                                    <span>•</span>
                                    <span>Dealer Yamaha JG Purwakarta</span>
                                </div>
                            </div>
                        </div>

                        {{-- Badge Konsistensi 6 Bulan di Sisi Kanan --}}
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold self-start sm:self-center flex-shrink-0">
                            <svg class="h-4 w-4 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            <span>Konsistensi: {{ $salesTerbaik6Bulan['months_reached_target'] }}x Tembus Target Reward (&ge; {{ $targetRewardUnit }} Unit)</span>
                        </div>
                    </div>

                    {{-- 4 Kolom Statistik Metrik 6 Bulan (Full Width, Rapi & Seimbang) --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1">
                        {{-- Total Unit 6 Bulan --}}
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70 text-center">
                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Terjual (6 Bln)</p>
                            <div class="text-2xl font-black text-slate-900 mt-1">
                                {{ $salesTerbaik6Bulan['total_units_6m'] }} <span class="text-xs font-normal text-slate-500">Unit</span>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-0.5">Target: {{ $salesTerbaik6Bulan['total_target_6m'] }} Unit</p>
                        </div>

                        {{-- Capaian Kumulatif --}}
                        <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-200/70 text-center">
                            <p class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Capaian Kumulatif</p>
                            <div class="text-2xl font-black text-emerald-600 mt-1">
                                {{ $salesTerbaik6Bulan['pct_cumulative'] }}%
                            </div>
                            <p class="text-[11px] text-emerald-600 font-semibold mt-0.5">Vs Target 90 Unit</p>
                        </div>

                        {{-- Rata-rata per Bulan --}}
                        <div class="p-3.5 rounded-xl bg-blue-50/70 border border-blue-200/70 text-center">
                            <p class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Rata-rata / Bulan</p>
                            <div class="text-2xl font-black text-[#0A4DF3] mt-1">
                                {{ $salesTerbaik6Bulan['avg_units_per_month'] }} <span class="text-xs font-normal text-slate-500">Unit</span>
                            </div>
                            <p class="text-[11px] text-blue-600 font-semibold mt-0.5">{{ $salesTerbaik6Bulan['months_reached_target'] }} / 6 Bulan &ge; 15</p>
                        </div>

                        {{-- Konversi 6 Bulan --}}
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70 text-center">
                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Konversi 6 Bln</p>
                            <div class="text-2xl font-black text-slate-800 mt-1">
                                {{ $salesTerbaik6Bulan['conversion_rate_6m'] }}%
                            </div>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $salesTerbaik6Bulan['deal_prospects_6m'] }} dari {{ $salesTerbaik6Bulan['total_prospects_6m'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- ============================================================
         SECTION 3: 10 KARTU STATISTIK UTAMA (KPI CARDS)
         ============================================================ --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
        
        <!-- Card 1: Total Sales Aktif -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-blue-300 transition-colors">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Sales Aktif</span>
                <div class="h-8 w-8 rounded-lg bg-blue-50 text-[#0A4DF3] flex items-center justify-center">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-slate-900">{{ $totalSalesAktif }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Personil tim aktif</p>
        </div>

        <!-- Card 2: Total Unit Terjual -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-emerald-300 transition-colors">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Unit Terjual</span>
                <div class="h-8 w-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-emerald-600">{{ $totalUnitTerjual }} <span class="text-xs font-normal text-slate-500">Unit</span></div>
            <p class="text-[10px] text-slate-400 mt-0.5">Realisasi transaksi valid</p>
        </div>

        <!-- Card 3: Target Unit Reguler -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-slate-300 transition-colors">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Target Unit</span>
                <div class="h-8 w-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-slate-900">{{ $totalTargetPenjualan }} <span class="text-xs font-normal text-slate-500">Unit</span></div>
            <p class="text-[10px] text-slate-400 mt-0.5">Akumulasi kuota tim</p>
        </div>

        <!-- Card 4: Persentase Capaian Tim -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-blue-300 transition-colors">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Pencapaian</span>
                <div class="h-8 w-8 rounded-lg bg-blue-50 text-[#0A4DF3] flex items-center justify-center font-bold text-xs">
                    %
                </div>
            </div>
            <div class="text-2xl font-bold text-[#0A4DF3]">{{ $persentaseCapaian }}%</div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2 overflow-hidden">
                <div class="bg-[#0A4DF3] h-1.5 rounded-full" style="width: {{ min(100, $persentaseCapaian) }}%"></div>
            </div>
        </div>

        <!-- Card 5: Top Sales Bulan Ini -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-amber-300 transition-colors">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Top Sales</span>
                <div class="h-8 w-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center">
                    <svg class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0016.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.98 6.98 0 01-2.77.588 6.98 6.98 0 01-2.77-.588"/></svg>
                </div>
            </div>
            <div class="text-sm font-bold text-slate-900 truncate">
                {{ $salesTerbaikBulanIni ? $salesTerbaikBulanIni['user']->nama : ($leaderboard->first() ? $leaderboard->first()['user']->nama : '—') }}
            </div>
            <p class="text-[10px] text-slate-400 mt-0.5 truncate">
                {{ $salesTerbaikBulanIni ? $salesTerbaikBulanIni['units'] . ' unit terjual' : 'Belum ada data' }}
            </p>
        </div>

        <!-- Card 6: Capai Target (>= 100%) -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-emerald-300 transition-colors">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Capai Target</span>
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
            </div>
            <div class="text-2xl font-bold text-slate-900">{{ $countReachedTarget }} <span class="text-xs font-normal text-slate-500">Sales</span></div>
            <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">&ge; 100% target reguler</p>
        </div>

        <!-- Card 7: Capai Target Reward (>= 15 Unit) -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-blue-300 transition-colors">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Capai Reward</span>
                <span class="text-[9px] font-bold bg-blue-50 text-[#0A4DF3] px-1.5 py-0.5 rounded border border-blue-100">&ge; {{ $targetRewardUnit }} Unit</span>
            </div>
            <div class="text-2xl font-bold text-[#0A4DF3]">{{ $countReachedReward }} <span class="text-xs font-normal text-slate-500">Sales</span></div>
            <p class="text-[10px] text-slate-400 mt-0.5">Layak dapat reward</p>
        </div>

        <!-- Card 8: Hampir Capai (75% - 99%) -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-amber-300 transition-colors">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Hampir Capai</span>
                <span class="h-2 w-2 rounded-full bg-amber-400"></span>
            </div>
            <div class="text-2xl font-bold text-amber-600">{{ $countAlmostTarget }} <span class="text-xs font-normal text-slate-500">Sales</span></div>
            <p class="text-[10px] text-amber-600 font-semibold mt-0.5">75% – 99% target</p>
        </div>

        <!-- Card 9: Rapat Evaluasi Terdekat -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-blue-300 transition-colors">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Rapat Evaluasi</span>
                <div class="h-8 w-8 rounded-lg bg-blue-50 text-[#0A4DF3] flex items-center justify-center">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-slate-900">{{ $upcomingMeetingsCount }} <span class="text-xs font-normal text-slate-500">Jadwal</span></div>
            <p class="text-[10px] text-slate-400 mt-0.5">Online / offline / hybrid</p>
        </div>

        <!-- Card 10: Temuan Lapangan Pending -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs hover:border-red-300 transition-colors">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider">Temuan Pending</span>
                <div class="h-8 w-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-red-500">{{ $pendingFindingsCount }} <span class="text-xs font-normal text-slate-500">Kasus</span></div>
            <p class="text-[10px] text-red-400 font-semibold mt-0.5">Perlu tindak lanjut</p>
        </div>

    </div>

    {{-- ============================================================
         SECTION 4: LEADERBOARD PENJUALAN & JADWAL / TEMUAN
         ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Tabel Leaderboard Penjualan (Col-Span 2) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Peringkat Penjualan — {{ $periodeLabel }}</h2>
                    <p class="text-[11px] text-slate-400 mt-0.5">Diurutkan: Unit Terjual &rarr; Omset &rarr; Rasio Konversi Prospek.</p>
                </div>
                <a href="{{ route('sales.index') }}" class="text-xs font-semibold text-[#0A4DF3] hover:underline flex items-center gap-1">
                    <span>Semua Data</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-left border-collapse text-xs table-auto">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 uppercase text-[10px] font-bold tracking-wider bg-slate-50">
                            <th class="py-2.5 px-3 text-center w-16">Peringkat</th>
                            <th class="py-2.5 px-3">Nama Sales</th>
                            <th class="py-2.5 px-2 text-center">Target</th>
                            <th class="py-2.5 px-2 text-center">Realisasi</th>
                            <th class="py-2.5 px-2">Capaian</th>
                            <th class="py-2.5 px-2 text-center">Status</th>
                            <th class="py-2.5 px-3 text-center">Reward</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($leaderboard as $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    @if($item['rank'] === 1)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-amber-50 border border-amber-200 text-amber-800 font-bold text-[10px]">
                                            <svg class="h-3 w-3 text-amber-500 fill-amber-500 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            #1
                                        </span>
                                    @elseif($item['rank'] === 2)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 text-slate-600 font-bold text-[10px]">
                                            <svg class="h-3 w-3 text-slate-400 fill-slate-400 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            #2
                                        </span>
                                    @elseif($item['rank'] === 3)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-orange-50 border border-orange-200 text-orange-700 font-bold text-[10px]">
                                            <svg class="h-3 w-3 text-orange-400 fill-orange-400 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            #3
                                        </span>
                                    @else
                                        <span class="text-slate-400 font-semibold text-[10px]">#{{ $item['rank'] }}</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3 whitespace-nowrap">
                                    <a href="{{ route('sales.show', $item['user']->id) }}" class="font-semibold text-slate-900 hover:text-[#0A4DF3] transition-colors flex items-center gap-2">
                                        <div class="h-6 w-6 rounded-full bg-blue-50 text-[#0A4DF3] font-bold text-[10px] flex items-center justify-center flex-shrink-0">
                                            {{ $item['user']->avatar_initials }}
                                        </div>
                                        <span>{{ $item['user']->nama }}</span>
                                    </a>
                                </td>
                                <td class="py-2.5 px-2 text-center font-medium whitespace-nowrap">{{ $item['target'] }} Unit</td>
                                <td class="py-2.5 px-2 text-center font-bold text-emerald-600 whitespace-nowrap">{{ $item['units'] }} Unit</td>
                                <td class="py-2.5 px-2 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-10 bg-slate-100 rounded h-1.5 overflow-hidden flex-shrink-0">
                                            <div class="bg-[#0A4DF3] h-full rounded" style="width: {{ min(100, $item['percentage']) }}%"></div>
                                        </div>
                                        <span class="font-bold text-slate-800 text-[11px]">{{ $item['percentage'] }}%</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-2 text-center whitespace-nowrap">
                                    @php
                                        $badgeColor = match($item['status_kinerja']) {
                                            'Sangat Baik' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'Baik' => 'bg-blue-50 text-[#0A4DF3] border-blue-200',
                                            'Cukup' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            default => 'bg-rose-50 text-rose-700 border-rose-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold border {{ $badgeColor }}">
                                        {{ $item['status_kinerja'] }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    @if($item['reached_reward'])
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-200">
                                            <svg class="h-3 w-3 text-purple-600 fill-purple-500 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            <span>Layak</span>
                                        </span>
                                    @else
                                        <span class="text-[10px] text-slate-400 font-medium">
                                            Kurang {{ max(0, $targetRewardUnit - $item['units']) }} unit
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-slate-400 italic">Belum ada data sales terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Kolom Kanan: Rapat Mendatang & Temuan Lapangan (Col-Span 1) --}}
        <div class="space-y-6">

            <!-- Card Jadwal Rapat -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-3.5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <svg class="h-4 w-4 text-[#0A4DF3]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        <span>Rapat Mingguan Terdekat</span>
                    </h3>
                    <a href="{{ route('meetings.create') }}" class="text-xs font-semibold text-[#0A4DF3] hover:underline">+ Buat Rapat</a>
                </div>

                @forelse($upcomingMeetings as $meeting)
                    <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/70 hover:bg-white hover:border-blue-200 transition space-y-2">
                        <div class="flex items-start justify-between gap-2">
                            <a href="{{ route('meetings.show', $meeting->id) }}" class="text-xs font-semibold text-slate-900 hover:text-[#0A4DF3] transition line-clamp-1">
                                {{ $meeting->judul_rapat }}
                            </a>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold border {{ $meeting->metode_badge }}">
                                {{ ucfirst($meeting->metode) }}
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-500 flex items-center gap-2">
                            <span>📅 {{ \Carbon\Carbon::parse($meeting->tanggal)->locale('id')->isoFormat('D MMM Y') }}</span>
                            <span>⏰ {{ substr($meeting->waktu_mulai, 0, 5) }} - {{ substr($meeting->waktu_selesai, 0, 5) }}</span>
                        </div>
                        @if($meeting->link_zoom)
                            <a href="{{ $meeting->link_zoom }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-blue-700 bg-blue-100/70 hover:bg-blue-200/70 px-2.5 py-1 rounded-lg transition">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 6.75v9a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                                <span>Buka Link Zoom</span>
                            </a>
                        @endif
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic py-2">Tidak ada rapat mendatang yang dijadwalkan.</p>
                @endforelse
            </div>

            <!-- Card Temuan Lapangan Pending -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-3.5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <svg class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <span>Temuan Lapangan Pending</span>
                    </h3>
                    <a href="{{ route('field-findings.index') }}" class="text-xs font-semibold text-[#0A4DF3] hover:underline">Semua</a>
                </div>

                @forelse($recentFindings as $finding)
                    <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/70 hover:bg-white hover:border-rose-200 hover:shadow-2xs transition space-y-2">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="font-bold text-slate-900">{{ $finding->user->nama ?? 'Sales' }}</span>
                            <span class="text-slate-400">{{ \Carbon\Carbon::parse($finding->tanggal_temuan)->format('d/m/Y') }}</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-800 leading-snug">
                            {{ $finding->motorcycle->nama_model ?? 'Motor Yamaha' }} — {{ $finding->warna_motor ?: 'Warna Bebas' }}
                        </p>
                        <div class="p-2.5 rounded-lg bg-rose-50/70 border border-rose-100 text-xs text-rose-700 leading-relaxed">
                            <span class="font-bold text-rose-900">Kendala:</span> {{ $finding->kendala ?: $finding->catatan_komunikasi }}
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic py-2">Seluruh temuan lapangan sudah selesai ditindaklanjuti.</p>
                @endforelse
            </div>

        </div>

    </div>

    {{-- ============================================================
         SECTION 5: REKAP PRESTASI SALES SELAMA ENAM BULAN
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6 space-y-6">
        
        <!-- Header Rekap 6 Bulan & Filter Fleksibel -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50 text-[#0A4DF3] border border-blue-100">
                        Evaluasi Multi-Bulan
                    </span>
                    <span class="text-xs text-slate-500">Rentang Waktu: <strong>{{ $recapPeriodeLabel }}</strong></span>
                </div>
                <h2 class="text-lg font-bold text-slate-900 tracking-tight mt-1">
                    Rekap Prestasi Sales 6 Bulan
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Evaluasi akumulatif performa prospek, deal, target kumulatif, dan konsistensi pencapaian reward 15 unit.
                </p>
            </div>

            <!-- Form Pemilih Rentang 6 Bulan -->
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                <input type="hidden" name="period_type" value="{{ $filterType }}">
                <input type="hidden" name="data_mode" value="{{ $dataMode }}">
                <span class="text-xs text-slate-500 font-medium whitespace-nowrap">Hingga Bulan:</span>
                <select name="recap_month" onchange="this.form.submit()"
                        class="h-8 rounded-lg border border-slate-200 bg-white px-2.5 text-xs font-medium text-slate-700 focus:border-[#0A4DF3] focus:outline-none">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $recapEndMonth == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(2026, $m, 1)->locale('id')->isoFormat('MMMM') }}
                        </option>
                    @endfor
                </select>
                <select name="recap_year" onchange="this.form.submit()"
                        class="h-8 rounded-lg border border-slate-200 bg-white px-2.5 text-xs font-medium text-slate-700 focus:border-[#0A4DF3] focus:outline-none">
                    @for($y = 2027; $y >= 2024; $y--)
                        <option value="{{ $y }}" {{ $recapEndYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>

        <!-- Banner Juara Akumulasi 6 Bulan -->
        @if($salesTerbaik6Bulan)
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 font-extrabold text-base flex items-center justify-center flex-shrink-0">
                    <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0016.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.98 6.98 0 01-2.77.588 6.98 6.98 0 01-2.77-.588"/></svg>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider">Juara Akumulasi 6 Bulan</span>
                    <h3 class="text-base font-bold text-slate-900">
                        {{ $salesTerbaik6Bulan['user']->nama }}
                        <span class="text-xs font-normal text-slate-500">({{ $salesTerbaik6Bulan['user']->kode_sales ?? 'Sales' }})</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Total <strong>{{ $salesTerbaik6Bulan['total_units_6m'] }} Unit</strong> terjual selama 6 bulan (Rata-rata <strong>{{ $salesTerbaik6Bulan['avg_units_per_month'] }} unit/bln</strong>).
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 text-right">
                <div class="bg-white px-3 py-1.5 rounded-lg border border-slate-200 text-center">
                    <span class="text-[10px] text-slate-400 block font-semibold uppercase">Capaian Kumulatif</span>
                    <span class="text-base font-bold text-slate-900">{{ $salesTerbaik6Bulan['pct_cumulative'] }}%</span>
                </div>
                <div class="bg-white px-3 py-1.5 rounded-lg border border-slate-200 text-center">
                    <span class="text-[10px] text-slate-400 block font-semibold uppercase">Tembus &ge;15 Unit</span>
                    <span class="text-base font-bold text-emerald-600">{{ $salesTerbaik6Bulan['months_reached_target'] }} / 6 Bulan</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Tabel Rekap Prestasi 6 Bulan Seluruh Sales -->
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 uppercase text-[10px] font-bold tracking-wider bg-slate-50">
                        <th class="py-2.5 px-3">Peringkat</th>
                        <th class="py-2.5 px-3">Nama Sales</th>
                        <th class="py-2.5 px-3 text-center">Total Prospek</th>
                        <th class="py-2.5 px-3 text-center">Total Deal</th>
                        <th class="py-2.5 px-3 text-center">Konversi (%)</th>
                        <th class="py-2.5 px-3 text-center">Total Unit</th>
                        <th class="py-2.5 px-3 text-center">Target 6 Bln</th>
                        <th class="py-2.5 px-3 text-center">Capaian (%)</th>
                        <th class="py-2.5 px-3 text-center">Rata-rata/Bln</th>
                        <th class="py-2.5 px-3 text-center">Capai &ge;15 Unit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($recapLeaderboard as $sRecap)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-3">
                                @if($sRecap['rank'] === 1)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-amber-50 border border-amber-200 text-amber-800 font-bold text-[10px]">
                                        <svg class="h-3 w-3 text-amber-500 fill-amber-500 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        #1
                                    </span>
                                @elseif($sRecap['rank'] === 2)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-slate-100 border border-slate-200 text-slate-600 font-bold text-[10px]">
                                        <svg class="h-3 w-3 text-slate-400 fill-slate-400 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        #2
                                    </span>
                                @elseif($sRecap['rank'] === 3)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-orange-50 border border-orange-200 text-orange-700 font-bold text-[10px]">
                                        <svg class="h-3 w-3 text-orange-400 fill-orange-400 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        #3
                                    </span>
                                @else
                                    <span class="text-slate-400 font-semibold text-[10px]">#{{ $sRecap['rank'] }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-3">
                                <a href="{{ route('sales.show', $sRecap['user']->id) }}" class="font-semibold text-slate-900 hover:text-[#0A4DF3] transition flex items-center gap-2">
                                    <div class="h-6 w-6 rounded-full bg-blue-50 text-[#0A4DF3] font-bold text-[10px] flex items-center justify-center">
                                        {{ $sRecap['user']->avatar_initials }}
                                    </div>
                                    <div>
                                        <span>{{ $sRecap['user']->nama }}</span>
                                        <span class="text-[10px] text-slate-400 block font-normal">{{ $sRecap['user']->kode_sales ?? 'Sales' }}</span>
                                    </div>
                                </a>
                            </td>
                            <td class="py-3 px-3 text-center font-medium">{{ $sRecap['total_prospects_6m'] }}</td>
                            <td class="py-3 px-3 text-center font-bold text-emerald-600">{{ $sRecap['deal_prospects_6m'] }}</td>
                            <td class="py-3 px-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $sRecap['conversion_rate_6m'] }}%
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center font-bold text-slate-900">{{ $sRecap['total_units_6m'] }} <span class="text-[10px] font-normal text-slate-500">Unit</span></td>
                            <td class="py-3 px-3 text-center text-slate-500">{{ $sRecap['total_target_6m'] }} Unit</td>
                            <td class="py-3 px-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <div class="w-12 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-[#0A4DF3] h-1.5 rounded-full" style="width: {{ min(100, $sRecap['pct_cumulative']) }}%"></div>
                                    </div>
                                    <span class="font-bold text-slate-800 text-[11px]">{{ $sRecap['pct_cumulative'] }}%</span>
                                </div>
                            </td>
                            <td class="py-3 px-3 text-center font-medium text-slate-700">{{ $sRecap['avg_units_per_month'] }} unit</td>
                            <td class="py-3 px-3 text-center">
                                @if($sRecap['months_reached_target'] >= 3)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="h-3 w-3 text-amber-500 fill-amber-400 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span>{{ $sRecap['months_reached_target'] }} / 6 Bulan</span>
                                    </span>
                                @elseif($sRecap['months_reached_target'] >= 1)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-[#0A4DF3] border border-blue-200">
                                        {{ $sRecap['months_reached_target'] }} / 6 Bulan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-500">
                                        0 / 6 Bulan
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-6 text-center text-slate-400 italic">Belum ada data rekap penjualan 6 bulan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 2 Grafik Khusus Rekap 6 Bulan (Chart.js) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-2">
            <!-- Grafik 1: Perkembangan Penjualan Sales 6 Bulan -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white">
                <div class="mb-3">
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-[#0A4DF3]"></span>
                        <span>Grafik Perkembangan Penjualan 6 Bulan (Per Sales)</span>
                    </h4>
                    <p class="text-[11px] text-slate-500">Perbandingan volume penjualan masing-masing sales person setiap bulan.</p>
                </div>
                <div class="relative" style="height:250px;">
                    <canvas id="chart6mSalesCanvas"></canvas>
                </div>
            </div>

            <!-- Grafik 2: Perkembangan Prospek vs Deal Tim 6 Bulan -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white">
                <div class="mb-3">
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span>Grafik Prospek vs Deal & Konversi Tim (6 Bulan)</span>
                    </h4>
                    <p class="text-[11px] text-slate-500">Tren volume prospek masuk dibandingkan dengan prospek yang berhasil deal.</p>
                </div>
                <div class="relative" style="height:250px;">
                    <canvas id="chart6mProspectDealCanvas"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- ============================================================
         SECTION 6: ANALISIS KONVERSI PROSPEK & PERFORMA SALES
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900">Analisis Konversi Prospek & Performa Sales ({{ $periodeLabel }})</h2>
                <p class="text-xs text-slate-500 mt-0.5">Analisis efektivitas follow-up dan perbandingan sales dengan prospek banyak vs konversi tinggi.</p>
            </div>
            <a href="{{ route('prospects.index') }}" class="text-xs font-semibold text-[#0A4DF3] hover:underline">Kelola Prospek &rarr;</a>
        </div>

        {{-- Summary 4 Kartu Status Prospek --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60 text-center">
                <div class="text-2xl font-bold text-slate-900">{{ $totalProspek }}</div>
                <p class="text-[11px] text-slate-500 mt-0.5 font-medium">Total Prospek Masuk</p>
            </div>
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-100 text-center">
                <div class="text-2xl font-bold text-emerald-600">{{ $prospekDeal }}</div>
                <p class="text-[11px] text-emerald-700 mt-0.5 font-medium">Berhasil Deal</p>
            </div>
            <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-100 text-center">
                <div class="text-2xl font-bold text-amber-600">{{ $prospekProses }}</div>
                <p class="text-[11px] text-amber-700 mt-0.5 font-medium">Dalam Proses</p>
            </div>
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-100 text-center">
                <div class="text-2xl font-bold text-rose-600">{{ $prospekBatal }}</div>
                <p class="text-[11px] text-rose-700 mt-0.5 font-medium">Tidak Deal / Batal</p>
            </div>
        </div>

        {{-- Conversion Rate Bar --}}
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center gap-4">
            <div class="text-2xl font-bold text-[#0A4DF3]">{{ $persentaseKonversi }}%</div>
            <div class="flex-1">
                <p class="text-xs font-medium text-slate-700 mb-1">Rasio Konversi Keseluruhan = (Jumlah Deal &divide; Total Prospek) &times; 100%</p>
                <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                    <div class="bg-[#0A4DF3] h-2 rounded-full transition-all duration-700"
                         style="width: {{ min(100, $persentaseKonversi) }}%"></div>
                </div>
            </div>
        </div>

        {{-- Tabel Perbandingan Performa Sales --}}
        @if($prospectConversionBySales->isNotEmpty())
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 uppercase text-[10px] font-bold tracking-wider bg-slate-50">
                        <th class="py-2.5 px-3">Sales Person</th>
                        <th class="py-2.5 px-3 text-center">Total Prospek</th>
                        <th class="py-2.5 px-3 text-center">Berhasil Deal</th>
                        <th class="py-2.5 px-3 text-center">Sedang Proses</th>
                        <th class="py-2.5 px-3 text-center">Tidak Deal</th>
                        <th class="py-2.5 px-3">Rasio Konversi (%)</th>
                        <th class="py-2.5 px-3">Karakteristik Kinerja</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @foreach($prospectConversionBySales as $item)
                        @php
                            $charLabel = 'Seimbang';
                            $charClass = 'bg-slate-100 text-slate-700';
                            if ($item['total_prospek'] >= 100 && $item['conversion_rate'] >= 12) {
                                $charLabel = 'Volume Tinggi & Konversi Tinggi';
                                $charClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                            } elseif ($item['total_prospek'] >= 100 && $item['conversion_rate'] < 12) {
                                $charLabel = 'Volume Tinggi, Perlu Optimalisasi';
                                $charClass = 'bg-amber-50 text-amber-700 border-amber-200';
                            } elseif ($item['total_prospek'] < 100 && $item['conversion_rate'] >= 13) {
                                $charLabel = 'Prospek Terbatas, Konversi Efektif';
                                $charClass = 'bg-blue-50 text-[#0A4DF3] border-blue-200';
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-3">
                                <div class="flex items-center gap-2">
                                    <div class="h-6 w-6 rounded-full bg-blue-50 text-[#0A4DF3] font-bold text-[10px] flex items-center justify-center">
                                        {{ $item['sales']->avatar_initials }}
                                    </div>
                                    <span class="font-semibold text-slate-900">{{ $item['sales']->nama }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-3 text-center font-medium">{{ $item['total_prospek'] }}</td>
                            <td class="py-3 px-3 text-center font-bold text-emerald-600">{{ $item['deal'] }}</td>
                            <td class="py-3 px-3 text-center font-medium text-amber-600">{{ $item['proses'] }}</td>
                            <td class="py-3 px-3 text-center font-medium text-rose-500">{{ $item['batal'] }}</td>
                            <td class="py-3 px-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-[#0A4DF3] h-1.5 rounded-full" style="width: {{ min(100, $item['conversion_rate']) }}%"></div>
                                    </div>
                                    <span class="font-bold text-slate-800 text-[11px]">{{ $item['conversion_rate'] }}%</span>
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border {{ $charClass }}">
                                    {{ $charLabel }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="text-xs text-slate-400 italic text-center py-4">Belum ada data prospek pada periode yang dipilih.</p>
        @endif
    </div>

    {{-- ============================================================
         SECTION 7: PANEL DEMO DATA MANAGEMENT (Supervisor Only)
         ============================================================ --}}
    @if($demoDataCount > 0)
    <div class="bg-amber-50/70 border border-amber-200 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="h-9 w-9 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center flex-shrink-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-amber-900">Mode Simulasi Presentasi Aktif</h3>
                <p class="text-xs text-amber-800 mt-0.5">
                    Terdapat <strong>{{ $demoDataCount }}</strong> data simulasi (<code>is_demo = true</code>) untuk kebutuhan presentasi dosen. Sebelum serah terima operasional mitra, data demo ini dapat dibersihkan secara aman tanpa memengaruhi akun asli.
                </p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2 flex-shrink-0">
            @if($includeDemo)
                <a href="{{ route('dashboard', array_merge(request()->query(), ['data_mode' => 'real'])) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-amber-300 text-amber-900 text-xs font-semibold hover:bg-amber-50 transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                    <span>Saring Hanya Data Asli</span>
                </a>
            @else
                <a href="{{ route('dashboard', array_merge(request()->query(), ['data_mode' => 'all'])) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#0A4DF3] text-white text-xs font-semibold hover:bg-blue-700 transition shadow-xs">
                    <span>Tampilkan Mode Presentasi</span>
                </a>
            @endif

            <form method="POST" action="{{ route('demo.clean') }}"
                  onsubmit="return confirm('⚠️ KONFIRMASI PEMBERSIHAN DATA DEMO:\n\nApakah Anda yakin ingin membersihkan data simulasi (is_demo=true)?\n\nAkun sales asli (Budi Santoso & Siti Rahmawati) serta data operasional nyata TIDAK AKAN DIHAPUS.')">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold transition shadow-xs">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    <span>Bersihkan Data Demo</span>
                </button>
            </form>
        </div>
    </div>
    @endif

</div>

@push('scripts')
<style>
    /* Hilangkan horizontal scrollbar pada seluruh tabel dashboard */
    .no-scrollbar::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }
    .no-scrollbar {
        -ms-overflow-style: none !important;
        scrollbar-width: none !important;
    }
</style>
<script>
// ============================================================
// SHOWCASE SWITCHER (BULAN INI vs 6 BULAN / SEMESTERAN)
// ============================================================
function switchShowcase(mode) {
    const cardMonth = document.getElementById('card-showcase-month');
    const cardSix = document.getElementById('card-showcase-six');
    const btnAll = document.getElementById('btn-showcase-all');
    const btnMonth = document.getElementById('btn-showcase-month');
    const btnSix = document.getElementById('btn-showcase-six');

    [btnAll, btnMonth, btnSix].forEach(btn => {
        if (!btn) return;
        btn.classList.remove('bg-white', 'text-[#0A4DF3]', 'shadow-xs', 'font-bold');
        btn.classList.add('text-slate-600', 'hover:text-slate-900', 'font-semibold');
    });

    if (mode === 'month') {
        if (cardMonth) cardMonth.style.display = 'block';
        if (cardSix) cardSix.style.display = 'none';
        if (btnMonth) {
            btnMonth.classList.add('bg-white', 'text-[#0A4DF3]', 'shadow-xs', 'font-bold');
            btnMonth.classList.remove('text-slate-600', 'hover:text-slate-900');
        }
    } else if (mode === 'six_months') {
        if (cardMonth) cardMonth.style.display = 'none';
        if (cardSix) cardSix.style.display = 'block';
        if (btnSix) {
            btnSix.classList.add('bg-white', 'text-[#0A4DF3]', 'shadow-xs', 'font-bold');
            btnSix.classList.remove('text-slate-600', 'hover:text-slate-900');
        }
    } else {
        if (cardMonth) cardMonth.style.display = 'block';
        if (cardSix) cardSix.style.display = 'block';
        if (btnAll) {
            btnAll.classList.add('bg-white', 'text-[#0A4DF3]', 'shadow-xs', 'font-bold');
            btnAll.classList.remove('text-slate-600', 'hover:text-slate-900');
        }
    }
}
window.switchShowcase = switchShowcase;

// ============================================================
// CHART.JS — SUPERVISOR DASHBOARD CHARTS
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    const ChartLib = window.Chart;
    if (!ChartLib) {
        console.warn('Chart.js belum dimuat. Pastikan assets sudah di-build.');
        return;
    }

    // -------------------------------------------------------
    // Chart 1: Perbandingan Penjualan Setiap Sales (6 Bulan)
    // -------------------------------------------------------
    const sixMSalesCtx = document.getElementById('chart6mSalesCanvas');
    if (sixMSalesCtx) {
        const sixMData = @json($chart6mSales);
        new ChartLib(sixMSalesCtx, {
            type: 'line',
            data: {
                labels: sixMData.labels,
                datasets: sixMData.datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { font: { size: 11, weight: '600' }, color: '#475569', boxWidth: 12, padding: 12 }
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ` ${ctx.dataset.label}: ${ctx.raw} Unit`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 }, color: '#64748b' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: {
                            font: { size: 11 },
                            color: '#94a3b8',
                            callback: (val) => val + ' Unit'
                        }
                    }
                }
            }
        });
    }

    // -------------------------------------------------------
    // Chart 2: Prospek vs Deal & Konversi Tim (6 Bulan)
    // -------------------------------------------------------
    const sixMProspectCtx = document.getElementById('chart6mProspectDealCanvas');
    if (sixMProspectCtx) {
        const pdData = @json($chart6mProspectDeal);
        new ChartLib(sixMProspectCtx, {
            type: 'bar',
            data: {
                labels: pdData.labels,
                datasets: [
                    {
                        type: 'line',
                        label: 'Konversi (%)',
                        data: pdData.rates,
                        borderColor: '#f59e0b',
                        backgroundColor: '#f59e0b',
                        borderWidth: 2,
                        yAxisID: 'y1',
                        tension: 0.3,
                        pointRadius: 4,
                    },
                    {
                        type: 'bar',
                        label: 'Prospek Masuk',
                        data: pdData.prospects,
                        backgroundColor: 'rgba(10, 77, 243, 0.25)',
                        borderColor: '#0A4DF3',
                        borderWidth: 1,
                        borderRadius: 6,
                        yAxisID: 'y',
                    },
                    {
                        type: 'bar',
                        label: 'Prospek Deal',
                        data: pdData.deals,
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                        yAxisID: 'y',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { font: { size: 11, weight: '600' }, color: '#475569', boxWidth: 12, padding: 12 }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                if (ctx.dataset.yAxisID === 'y1') {
                                    return ` ${ctx.dataset.label}: ${ctx.raw}%`;
                                }
                                return ` ${ctx.dataset.label}: ${ctx.raw} Prospek`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 }, color: '#64748b' }
                    },
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: {
                            font: { size: 11 },
                            color: '#94a3b8',
                            callback: (val) => val
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: {
                            font: { size: 11 },
                            color: '#f59e0b',
                            callback: (val) => val + '%'
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
