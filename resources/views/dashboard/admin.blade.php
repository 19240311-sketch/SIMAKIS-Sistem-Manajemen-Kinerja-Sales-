@extends('layouts.app')

@section('title', 'Dashboard Administrator — SIMAKIS Yamaha JG Purwakarta')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    {{-- ============================================================
         1. TOP HEADER ADMIN
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                        Portal Administrator Sistem
                    </span>
                    <span class="text-xs text-slate-400 font-medium">Dealer Resmi Yamaha JG Purwakarta</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                    Pengelolaan Sistem &amp; Monitoring Master Data
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Kelola akun sales, konfigurasi master motor, sumber &amp; status prospek, modul pembelajaran, dan tes mingguan.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('users.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A4DF3] hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition-all active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.765z" />
                    </svg>
                    <span>Kelola Pengguna</span>
                </a>
                <a href="{{ route('master.motorcycles.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold border border-slate-200 shadow-xs transition-all active:scale-95">
                    <span>Master Motor &rarr;</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Overdue Follow-up Alert (Jika ada) --}}
    @if(isset($overdueCount) && $overdueCount > 0)
        <div class="rounded-2xl border border-amber-300 bg-amber-50/90 p-4 sm:p-5 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-500 text-white font-bold shadow-xs">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-amber-950">
                            Monitoring: {{ $overdueCount }} Prospek Dealer Belum Menerima Follow-Up (&gt; 3 Hari)
                        </h3>
                        <p class="text-xs text-amber-900 mt-0.5">
                            Segera ingatkan tim Sales agar menghubungi calon konsumen yang telah terdata.
                        </p>
                    </div>
                </div>
                <a href="{{ route('prospects.index', ['needs_follow_up' => 1]) }}"
                   class="inline-flex items-center justify-center rounded-xl bg-amber-600 px-4 py-2 text-xs font-bold text-white hover:bg-amber-700 transition shadow-xs whitespace-nowrap">
                    Lihat Prospek Tertunda &rarr;
                </a>
            </div>
        </div>
    @endif

    {{-- ============================================================
         2. RINGKASAN DATA SISTEM (STAT CARDS)
         ============================================================ --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        
        <!-- Total Akun Pengguna -->
        <div class="bg-white p-4.5 rounded-2xl border border-slate-200 shadow-xs hover:border-purple-300 transition-colors">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Akun Pengguna</span>
                <div class="h-8 w-8 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900">{{ $totalUsers ?? 0 }} <span class="text-xs font-normal text-slate-500">User</span></div>
            <p class="text-[11px] text-slate-400 mt-1">{{ $totalSales ?? 0 }} Sales &bull; {{ $totalSupervisor ?? 0 }} SPV</p>
        </div>

        <!-- Master Motor & Varian -->
        <div class="bg-white p-4.5 rounded-2xl border border-slate-200 shadow-xs hover:border-blue-300 transition-colors">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Master Motor</span>
                <div class="h-8 w-8 rounded-xl bg-blue-50 text-[#0A4DF3] flex items-center justify-center">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.25V4.5m0 0H9.75M14.25 7.5H9.75" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-black text-[#0A4DF3]">{{ $totalMotorcycles ?? 0 }} <span class="text-xs font-normal text-slate-500">Tipe</span></div>
            <p class="text-[11px] text-emerald-600 font-semibold mt-1">{{ $activeMotorcycles ?? 0 }} Aktif dipasarkan</p>
        </div>

        <!-- Total Prospek Masuk -->
        <div class="bg-white p-4.5 rounded-2xl border border-slate-200 shadow-xs hover:border-blue-300 transition-colors">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Prospek</span>
                <div class="h-8 w-8 rounded-xl bg-cyan-50 text-cyan-700 flex items-center justify-center">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900">{{ $totalProspects ?? 0 }} <span class="text-xs font-normal text-slate-500">Data</span></div>
            <p class="text-[11px] text-slate-400 mt-1">Calon konsumen dealer</p>
        </div>

        <!-- Follow Up Phone & Visit -->
        <div class="bg-white p-4.5 rounded-2xl border border-slate-200 shadow-xs hover:border-emerald-300 transition-colors">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Aktivitas Follow-Up</span>
                <div class="h-8 w-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-black text-emerald-600">{{ ($totalPhone ?? 0) + ($totalVisit ?? 0) }}</div>
            <p class="text-[11px] text-slate-400 mt-1">{{ $totalPhone ?? 0 }} Telp &bull; {{ $totalVisit ?? 0 }} Visit</p>
        </div>

        <!-- Total Transaksi Valid -->
        <div class="bg-white p-4.5 rounded-2xl border border-slate-200 shadow-xs hover:border-slate-300 transition-colors">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Unit Terjual Valid</span>
                <div class="h-8 w-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6H2.25m0 0v1.5c0 .414.336.75.75.75h18a.75.75 0 00.75-.75V6m0 0h-.75A.75.75 0 0120.25 5.25V4.5m0 0H3.75m16.5 0a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 4.5m16.5 0v12.75a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V4.5" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900">{{ $totalUnitsSold ?? 0 }} <span class="text-xs font-normal text-slate-500">Unit</span></div>
            <p class="text-[11px] text-slate-400 mt-1">{{ $totalTransactions ?? 0 }} Faktur Transaksi</p>
        </div>

    </div>

    {{-- ============================================================
         3. PINTASAN PENGELOLAAN MASTER & MODUL ADMIN
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6 space-y-4">
        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider text-slate-500">
            Akses Cepat Pengelolaan Modul Sistem
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <a href="{{ route('users.index') }}"
               class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 hover:bg-white hover:border-purple-300 hover:shadow-xs transition text-center group">
                <div class="h-10 w-10 mx-auto rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold mb-2 group-hover:scale-105 transition-transform">
                    👥
                </div>
                <div class="text-xs font-bold text-slate-800 group-hover:text-purple-700">Kelola Akun</div>
                <p class="text-[10px] text-slate-400 mt-0.5">Sales &amp; Supervisor</p>
            </a>

            <a href="{{ route('master.motorcycles.index') }}"
               class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 hover:bg-white hover:border-blue-300 hover:shadow-xs transition text-center group">
                <div class="h-10 w-10 mx-auto rounded-xl bg-blue-100 text-[#0A4DF3] flex items-center justify-center font-bold mb-2 group-hover:scale-105 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5zM15.75 18.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5zM8.25 15h7.5m-5.25-6l2.25 6m3-6l-2.25 6M9 9h6"/></svg>
                </div>
                <div class="text-xs font-bold text-slate-800 group-hover:text-[#0A4DF3]">Master Motor</div>
                <p class="text-[10px] text-slate-400 mt-0.5">Model, Tipe, Harga</p>
            </a>

            <a href="{{ route('master.sources.index') }}"
               class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 hover:bg-white hover:border-cyan-300 hover:shadow-xs transition text-center group">
                <div class="h-10 w-10 mx-auto rounded-xl bg-cyan-100 text-cyan-700 flex items-center justify-center font-bold mb-2 group-hover:scale-105 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18zM3.6 9h16.8M3.6 15h16.8M12 3a14.25 14.25 0 010 18M12 3a14.25 14.25 0 000 18"/></svg>
                </div>
                <div class="text-xs font-bold text-slate-800 group-hover:text-cyan-700">Sumber Prospek</div>
                <p class="text-[10px] text-slate-400 mt-0.5">Walk-in, Digital, Event</p>
            </a>

            <a href="{{ route('master.statuses.index') }}"
               class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 hover:bg-white hover:border-emerald-300 hover:shadow-xs transition text-center group">
                <div class="h-10 w-10 mx-auto rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold mb-2 group-hover:scale-105 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3zM6 6h.008v.008H6V6z"/></svg>
                </div>
                <div class="text-xs font-bold text-slate-800 group-hover:text-emerald-700">Status Prospek</div>
                <p class="text-[10px] text-slate-400 mt-0.5">Tahapan Pipeline</p>
            </a>

            <a href="{{ route('admin.trainings.index') }}"
               class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 hover:bg-white hover:border-amber-300 hover:shadow-xs transition text-center group">
                <div class="h-10 w-10 mx-auto rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold mb-2 group-hover:scale-105 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                </div>
                <div class="text-xs font-bold text-slate-800 group-hover:text-amber-700">Modul Training</div>
                <p class="text-[10px] text-slate-400 mt-0.5">Materi &amp; Video</p>
            </a>

            <a href="{{ route('admin.weekly-tests.index') }}"
               class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 hover:bg-white hover:border-rose-300 hover:shadow-xs transition text-center group">
                <div class="h-10 w-10 mx-auto rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold mb-2 group-hover:scale-105 transition-transform">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                    📝
                </div>
                <div class="text-xs font-bold text-slate-800 group-hover:text-rose-700">Tes Mingguan</div>
                <p class="text-[10px] text-slate-400 mt-0.5">Bank Soal &amp; Jadwal</p>
            </a>
        </div>
    </div>

    {{-- ============================================================
         4. DISTRIBUSI STATUS PROSPEK
         ============================================================ --}}
    @if(isset($statuses) && $statuses->isNotEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Distribusi Status Prospek Dealer</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Jumlah calon konsumen pada setiap tahapan pipeline operasional.</p>
                </div>
                <a href="{{ route('prospects.index') }}" class="text-xs font-semibold text-[#0A4DF3] hover:underline">
                    Lihat Seluruh Prospek &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
                @foreach($statuses as $st)
                    <a href="{{ route('prospects.index', ['status_id' => $st->id]) }}"
                       class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-white hover:border-blue-300 hover:shadow-xs transition flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-700">{{ $st->nama }}</span>
                            <span class="h-2 w-2 rounded-full bg-[#0A4DF3]"></span>
                        </div>
                        <div class="text-2xl font-black text-slate-900">
                            {{ $statusCounts[$st->code]['count'] ?? 0 }}
                        </div>
                        <span class="text-[10px] text-slate-400 mt-1">Calon konsumen</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ============================================================
         5. AKTIVITAS TERBARU (PROSPEK TERBARU & TRANSAKSI TERBARU)
         ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Prospek Terbaru Masuk -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900">Prospek Terbaru Masuk</h3>
                <a href="{{ route('prospects.index') }}" class="text-xs font-semibold text-[#0A4DF3] hover:underline">
                    Semua Prospek &rarr;
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @if(isset($recentProspects) && $recentProspects->isNotEmpty())
                    @foreach($recentProspects as $p)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50/80 transition text-xs">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="h-8 w-8 rounded-full bg-blue-50 text-[#0A4DF3] font-bold text-xs flex items-center justify-center shrink-0">
                                    {{ $p->initials ?? substr($p->nama_prospek ?? 'PS', 0, 2) }}
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('prospects.show', $p->id) }}" class="font-bold text-slate-900 hover:text-[#0A4DF3] truncate block">
                                        {{ $p->nama_prospek }}
                                    </a>
                                    <p class="text-[11px] text-slate-400 truncate">
                                        {{ $p->motorcycle->nama_model ?? 'Yamaha' }} &bull; Sales: {{ $p->user->nama ?? '-' }}
                                    </p>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 shrink-0">
                                {{ $p->status->nama ?? 'Baru' }}
                            </span>
                        </div>
                    @endforeach
                @else
                    <div class="p-6 text-center text-xs text-slate-400 italic">
                        Belum ada data prospek tercatat.
                    </div>
                @endif
            </div>
        </div>

        <!-- Transaksi Terbaru Valid -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900">Transaksi Terbaru Valid</h3>
                <a href="{{ route('transactions.index') }}" class="text-xs font-semibold text-[#0A4DF3] hover:underline">
                    Semua Transaksi &rarr;
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @if(isset($recentTransactions) && $recentTransactions->isNotEmpty())
                    @foreach($recentTransactions as $trx)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50/80 transition text-xs">
                            <div class="min-w-0">
                                <div class="font-bold text-slate-900 truncate">
                                    {{ $trx->motorcycle->nama_model ?? 'Motor Yamaha' }}
                                    <span class="text-slate-400 font-normal">({{ $trx->jumlah_unit }} Unit)</span>
                                </div>
                                <p class="text-[11px] text-slate-400 truncate">
                                    Konsumen: {{ $trx->nama_konsumen }} &bull; Sales: {{ $trx->user->nama ?? '-' }}
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-bold text-emerald-600 block">
                                    Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                </span>
                                <span class="text-[10px] text-slate-400">
                                    {{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="p-6 text-center text-xs text-slate-400 italic">
                        Belum ada transaksi tercatat.
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
