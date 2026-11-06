@extends('layouts.app')

@section('title', 'Daftar Prospek Konsumen — SIMAKIS Yamaha JG Purwakarta')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Header / Actions Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50 text-[#0A4DF3] border border-blue-100">
                    SIMAKIS Prospek
                </span>
                <span class="text-xs text-slate-400 font-medium">Dealer Yamaha JG Purwakarta</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                {{ auth()->user()->isAdmin() ? 'Monitoring Prospek Konsumen' : 'Daftar Prospek Saya' }}
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Total <strong>{{ $prospects->total() }}</strong> calon konsumen terdata dalam sistem
            </p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <!-- Export CSV Button -->
            <a href="{{ route('prospects.export', request()->query()) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition">
                <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <span>Unduh CSV</span>
            </a>

            <a href="{{ route('prospects.create', ['mode' => 'prospect']) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A4DF3] hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition active:scale-95">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Prospek Baru</span>
            </a>

            <a href="{{ route('prospects.create', ['mode' => 'deal']) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition active:scale-95">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
                <span>+ Input Deal</span>
            </a>
        </div>
    </div>

    <!-- Segmented Tab Navigation: Semua vs Prospect vs Deal -->
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('prospects.index', array_merge(request()->except('tab', 'page'), ['tab' => 'all'])) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition shadow-xs {{ ($tab ?? 'all') === 'all' ? 'bg-[#0A4DF3] text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            <span>Semua Data</span>
            <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ ($tab ?? 'all') === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $countAll ?? 0 }}</span>
        </a>

        <a href="{{ route('prospects.index', array_merge(request()->except('tab', 'page'), ['tab' => 'prospect'])) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition shadow-xs {{ ($tab ?? 'all') === 'prospect' ? 'bg-[#0A4DF3] text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            <span>Prospek Berjalan</span>
            <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ ($tab ?? 'all') === 'prospect' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $countProspect ?? 0 }}</span>
        </a>

        <a href="{{ route('prospects.index', array_merge(request()->except('tab', 'page'), ['tab' => 'deal'])) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition shadow-xs {{ ($tab ?? 'all') === 'deal' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            <span>Prospek Deal (Closing)</span>
            <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ ($tab ?? 'all') === 'deal' ? 'bg-white/20 text-white' : 'bg-emerald-50 text-emerald-700' }}">{{ $countDeal ?? 0 }}</span>
        </a>
    </div>

    <!-- Filters & Search Bar Card -->
    <div class="rounded-2xl bg-white p-5 border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('prospects.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 {{ auth()->user()->isAdmin() ? 'lg:grid-cols-6' : 'lg:grid-cols-5' }} items-end">
            <!-- Search Input -->
            <div class="{{ auth()->user()->isAdmin() ? 'lg:col-span-2' : 'lg:col-span-2' }}">
                <label for="search" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-600">Cari Nama / Telepon</label>
                <div class="relative">
                    <input id="search" name="search" type="text" value="{{ request('search') }}"
                           placeholder="Ketik nama atau nomor telepon..."
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-3 text-xs text-slate-800 placeholder:text-slate-400 focus:border-[#0A4DF3] focus:outline-none">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status_id" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-600">Status Prospek</label>
                <select id="status_id" name="status_id"
                        class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-xs text-slate-800 focus:border-[#0A4DF3] focus:outline-none">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st->id }}" {{ request('status_id') == $st->id ? 'selected' : '' }}>
                            {{ $st->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Source Filter -->
            <div>
                <label for="source_id" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-600">Sumber Prospek</label>
                <select id="source_id" name="source_id"
                        class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-xs text-slate-800 focus:border-[#0A4DF3] focus:outline-none">
                    <option value="">Semua Sumber</option>
                    @foreach($sources as $src)
                        <option value="{{ $src->id }}" {{ request('source_id') == $src->id ? 'selected' : '' }}>
                            {{ $src->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sales Filter (Supervisor/Admin Only) -->
            @if(auth()->user()->isAdmin())
                <div>
                    <label for="user_id" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-600">Sales PJ</label>
                    <select id="user_id" name="user_id"
                            class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-xs text-slate-800 focus:border-[#0A4DF3] focus:outline-none">
                        <option value="">Semua Sales</option>
                        @foreach($salesUsers as $sUser)
                            <option value="{{ $sUser->id }}" {{ request('user_id') == $sUser->id ? 'selected' : '' }}>
                                {{ $sUser->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex w-full items-center gap-2">
                <button type="submit" 
                        class="flex-1 inline-flex h-10 items-center justify-center gap-1.5 rounded-xl bg-[#0A4DF3] px-3 text-xs font-bold text-white shadow-xs transition hover:bg-blue-700 active:scale-95 cursor-pointer">
                    <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.539.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.378-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                    </svg>
                    <span>Filter</span>
                </button>
                @if(request()->hasAny(['search', 'status_id', 'source_id', 'user_id', 'motorcycle_id', 'needs_follow_up']))
                    <a href="{{ route('prospects.index') }}" 
                       class="flex-1 inline-flex h-10 items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 text-xs font-bold text-slate-700 shadow-xs transition hover:bg-slate-50 active:scale-95 cursor-pointer"
                       title="Reset filter">
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>

        <!-- Quick Filter Pills -->
        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-2 flex-wrap text-xs">
            <span class="text-slate-400 font-semibold text-[11px] uppercase tracking-wider">Filter Cepat:</span>
            <a href="{{ request()->fullUrlWithQuery(['needs_follow_up' => request('needs_follow_up') ? null : 1]) }}"
               class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 font-semibold transition border {{ request('needs_follow_up') ? 'bg-amber-500 text-white border-amber-600 shadow-xs' : 'bg-slate-50 text-amber-900 border-amber-200 hover:bg-amber-100' }}">
                <span class="h-2 w-2 rounded-full {{ request('needs_follow_up') ? 'bg-white' : 'bg-amber-500 animate-pulse' }}"></span>
                ⚠️ Perlu Follow-up Segera (&gt; 3 Hari)
            </a>
        </div>
    </div>

    <!-- Prospects List Table Container -->
    <div class="rounded-2xl bg-white border border-slate-200 shadow-xs overflow-hidden">
        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/80 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-5 py-3.5">Konsumen</th>
                        <th class="px-5 py-3.5">Motor Diminati</th>
                        <th class="px-5 py-3.5">Sumber</th>
                        <th class="px-5 py-3.5">Status</th>
                        @if(auth()->user()->isAdmin())
                            <th class="px-5 py-3.5">Sales PJ</th>
                        @endif
                        <th class="px-5 py-3.5">Aktivitas Terakhir</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($prospects as $p)
                        <tr class="hover:bg-blue-50/40 transition">
                            <!-- Consumer Info -->
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-[#0A4DF3] font-bold text-xs border border-blue-100">
                                        {{ $p->initials }}
                                    </div>
                                    <div>
                                        <a href="{{ route('prospects.show', $p->id) }}" class="font-bold text-slate-900 hover:text-[#0A4DF3] block">
                                            {{ $p->name }}
                                        </a>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="text-slate-400 text-[11px]">{{ $p->phone }}</span>
                                            @if($p->wa_number)
                                                <a href="{{ $p->wa_link }}" target="_blank" title="Kirim Pesan WhatsApp Follow-Up"
                                                   class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white border border-emerald-200 text-[10px] font-bold transition">
                                                    <span>💬 Chat WA</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Motorcycle -->
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-slate-900">{{ $p->motorcycle->full_display_name ?? ($p->motorcycle->nama_model ?? '-') }}</div>
                                @if($p->warna_motor_diminati)
                                    <span class="text-[11px] text-[#0A4DF3] font-medium block">{{ $p->warna_motor_diminati }}</span>
                                @endif
                            </td>

                            <!-- Source -->
                            <td class="px-5 py-3.5 text-slate-500">
                                <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-medium border border-slate-200">
                                    {{ $p->source->name ?? '-' }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-5 py-3.5">
                                <x-status-badge :status="$p->status" />
                            </td>

                            <!-- Sales (if supervisor/admin) -->
                            @if(auth()->user()->isAdmin())
                                <td class="px-5 py-3.5 text-slate-600">
                                    <span class="font-semibold text-slate-900">{{ $p->user->name ?? '-' }}</span>
                                </td>
                            @endif

                            <!-- Last Activity & Overdue Warning -->
                            <td class="px-5 py-3.5 text-[11px]">
                                @if($p->needs_follow_up)
                                    <div class="space-y-0.5">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-800 border border-amber-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Perlu Follow-up ({{ $p->days_since_last_activity }} hr)
                                        </span>
                                        <p class="text-[10px] text-slate-400">
                                            @if($p->latestActivity)
                                                Terakhir: {{ $p->latestActivity->activity_at->diffForHumans() }}
                                            @else
                                                Belum dihubungi
                                            @endif
                                        </p>
                                    </div>
                                @elseif($p->latestActivity)
                                    <span class="font-bold uppercase text-[#0A4DF3]">{{ $p->latestActivity->type }}</span> &bull; <span class="text-slate-400">{{ $p->latestActivity->activity_at->diffForHumans() }}</span>
                                @else
                                    <span class="italic text-slate-400">Belum ada aktivitas</span>
                                @endif
                            </td>

                            <!-- Action -->
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <a href="{{ route('prospects.show', $p->id) }}"
                                   class="inline-flex items-center gap-1 rounded-xl bg-blue-50 px-3 py-1.5 text-xs font-bold text-[#0A4DF3] hover:bg-[#0A4DF3] hover:text-white transition">
                                    Detail &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}" class="p-12 text-center text-slate-400">
                                <p class="text-base font-bold text-slate-700">Tidak ada data prospek ditemukan</p>
                                <p class="text-xs text-slate-400 mt-1">Coba sesuaikan filter pencarian atau buat data prospek baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List View (< 640px) -->
        <div class="block sm:hidden divide-y divide-slate-100">
            @forelse($prospects as $p)
                <div class="p-4 space-y-3 text-xs">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-50 text-[#0A4DF3] font-bold text-xs">
                                {{ $p->initials }}
                            </div>
                            <div>
                                <a href="{{ route('prospects.show', $p->id) }}" class="font-bold text-slate-900">
                                    {{ $p->name }}
                                </a>
                                <div class="text-[11px] text-slate-400">{{ $p->phone }}</div>
                            </div>
                        </div>
                        <x-status-badge :status="$p->status" />
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <span class="font-bold text-slate-800">{{ $p->motorcycle->nama_model ?? '-' }}</span>
                        <span class="text-[11px] text-slate-500">{{ $p->source->name ?? '-' }}</span>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-[11px]">
                        <span class="text-slate-400">
                            {{ $p->latestActivity ? $p->latestActivity->activity_at->diffForHumans() : 'Belum ada follow-up' }}
                        </span>
                        <a href="{{ route('prospects.show', $p->id) }}" class="font-bold text-[#0A4DF3]">
                            Detail &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    Tidak ada data prospek ditemukan.
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($prospects->hasPages())
            <div class="p-4 border-t border-slate-100 bg-white">
                {{ $prospects->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
