@extends('layouts.app')

@section('title', 'Detail Log Aktivitas #' . $log->id . ' — SIMAKIS Yamaha JG Purwakarta')
@section('page-title', 'Detail Log Aktivitas')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    {{-- ============================================================
         1. TOP HEADER & BREADCRUMB
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="mb-3">
                    <a href="{{ route('activity-logs.index') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-2xs transition">
                        <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        <span>Kembali ke Log Aktivitas</span>
                    </a>
                </div>
                <div class="flex items-center gap-3">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                        Detail Log Aktivitas #{{ $log->id }}
                    </h1>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $log->activity_badge_class }}">
                        {{ $log->activity_label }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Tercatat pada {{ $log->waktu_label }} ({{ $log->created_at->diffForHumans() }})
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold border {{ $log->role_badge_class }}">
                    {{ $log->role_label }}
                </span>
            </div>
        </div>
    </div>

    {{-- ============================================================
         2. DETAIL INFORMASI
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6 space-y-6">
        <h2 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
            <svg class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            Informasi Aktivitas
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-2xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Pelaku Aktivitas</span>
                <div class="font-bold text-slate-900 text-sm">{{ $log->user_nama ?? 'Sistem / Anonim' }}</div>
                <div class="text-slate-500 text-2xs mt-0.5">Role: {{ $log->role_label }} &bull; User ID: {{ $log->user_id ?? '-' }}</div>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-2xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Modul / Halaman Terdampak</span>
                <div class="font-bold text-slate-900 text-sm capitalize">{{ $log->module_label }}</div>
                <div class="text-slate-500 text-2xs mt-0.5">Modul Sistem: {{ $log->modul }}</div>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-2xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Jenis Aktivitas</span>
                <div class="font-bold text-slate-900 text-sm">{{ $log->activity_label }}</div>
                <div class="text-slate-500 text-2xs mt-0.5">Kode: {{ $log->jenis_aktivitas }}</div>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-2xs font-bold text-slate-400 uppercase tracking-wider block mb-1">IP Address &amp; Waktu</span>
                <div class="font-bold text-slate-900 font-mono">{{ $log->ip_address ?: '-' }}</div>
                <div class="text-slate-500 text-2xs mt-0.5">{{ $log->waktu_label }}</div>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-indigo-50/50 border border-indigo-100">
            <span class="text-2xs font-bold text-indigo-700 uppercase tracking-wider block mb-1">Deskripsi Aktivitas</span>
            <p class="text-sm text-slate-800 font-medium leading-relaxed">{{ $log->deskripsi }}</p>
        </div>

        {{-- Record ID if any --}}
        @if($log->record_id)
            <div class="text-xs text-slate-500 flex items-center gap-2">
                <span class="font-semibold text-slate-700">ID Record Terkait:</span>
                <span class="font-mono bg-slate-100 px-2 py-0.5 rounded text-slate-800">#{{ $log->record_id }}</span>
            </div>
        @endif
    </div>

    {{-- ============================================================
         3. PERUBAHAN DATA (BEFORE & AFTER / DETAIL PERUBAHAN)
         ============================================================ --}}
    @if(!empty($log->data_sebelum) || !empty($log->data_sesudah))
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6 space-y-4">
        <h2 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
            <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
            </svg>
            Data Snapshot Sebelum &amp; Sesudah Perubahan
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Sebelum --}}
            <div class="space-y-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Data Sebelum
                </span>
                <div class="p-3.5 rounded-xl bg-slate-900 text-slate-100 text-2xs font-mono overflow-x-auto max-h-80">
                    <pre>{{ !empty($log->data_sebelum) ? json_encode($log->data_sebelum, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : 'Tidak ada data sebelum.' }}</pre>
                </div>
            </div>

            {{-- Sesudah --}}
            <div class="space-y-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Data Sesudah
                </span>
                <div class="p-3.5 rounded-xl bg-slate-900 text-slate-100 text-2xs font-mono overflow-x-auto max-h-80">
                    <pre>{{ !empty($log->data_sesudah) ? json_encode($log->data_sesudah, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : 'Tidak ada data sesudah.' }}</pre>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

