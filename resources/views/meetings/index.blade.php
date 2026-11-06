@extends('layouts.app')

@section('title', 'Rapat Mingguan')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-violet-100 text-violet-700">SIMAKIS</span>
                <span class="text-xs text-slate-400 font-medium">Dealer Yamaha JG Purwakarta</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-1">Rapat Mingguan Sales</h1>
            <p class="text-xs text-slate-500 mt-0.5">Jadwal, notulen, dan evaluasi rapat mingguan tim sales (online, offline, hybrid).</p>
        </div>
        @if(auth()->user()->isSupervisor())
            <a href="{{ route('meetings.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold shadow-sm transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                + Jadwalkan Rapat
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 text-xs font-medium rounded-xl px-4 py-3">
            <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-white rounded-2xl border border-hairline shadow-sm p-4 text-center">
            <div class="text-2xl font-extrabold text-violet-600">{{ $upcomingCount }}</div>
            <div class="text-[10px] text-slate-400 font-medium uppercase mt-1">Rapat Mendatang</div>
        </div>
        <div class="bg-white rounded-2xl border border-hairline shadow-sm p-4 text-center">
            <div class="text-2xl font-extrabold text-slate-700">{{ $meetings->total() }}</div>
            <div class="text-[10px] text-slate-400 font-medium uppercase mt-1">Total Rapat</div>
        </div>
        <div class="bg-white rounded-2xl border border-hairline shadow-sm p-4 text-center">
            <div class="text-2xl font-extrabold text-emerald-600">{{ $meetings->where('status', 'selesai')->count() }}</div>
            <div class="text-[10px] text-slate-400 font-medium uppercase mt-1">Sudah Selesai</div>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('meetings.index') }}" class="bg-white p-4 rounded-2xl border border-hairline shadow-sm flex flex-wrap items-center gap-3 text-xs">
        <select name="status" class="h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:border-violet-400 focus:outline-none">
            <option value="">Semua Status</option>
            <option value="dijadwalkan" {{ request('status') == 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
            <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <select name="metode" class="h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:border-violet-400 focus:outline-none">
            <option value="">Semua Metode</option>
            <option value="online" {{ request('metode') == 'online' ? 'selected' : '' }}>Online</option>
            <option value="offline" {{ request('metode') == 'offline' ? 'selected' : '' }}>Offline</option>
            <option value="hybrid" {{ request('metode') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
        </select>
        <input type="date" name="start_date" value="{{ request('start_date') }}" class="h-9 rounded-xl border border-slate-200 px-3 text-xs focus:border-violet-400 focus:outline-none">
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="h-9 rounded-xl border border-slate-200 px-3 text-xs focus:border-violet-400 focus:outline-none">
        <button type="submit" class="h-9 px-4 rounded-xl bg-violet-600 text-white font-semibold hover:bg-violet-700 transition">Filter</button>
        <a href="{{ route('meetings.index') }}" class="h-9 px-4 flex items-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition">Reset</a>
    </form>

    <!-- Meeting List -->
    <div class="space-y-3">
        @forelse($meetings as $meeting)
            @php
                $statusColors = [
                    'dijadwalkan' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'dot' => 'bg-blue-500', 'label' => 'Dijadwalkan'],
                    'berlangsung' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500', 'label' => 'Berlangsung'],
                    'selesai'     => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'dot' => 'bg-slate-400', 'label' => 'Selesai'],
                    'dibatalkan'  => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'dot' => 'bg-red-400', 'label' => 'Dibatalkan'],
                ];
                $sc = $statusColors[$meeting->status] ?? $statusColors['dijadwalkan'];
                $metodeColors = ['online' => 'bg-cyan-100 text-cyan-700', 'offline' => 'bg-orange-100 text-orange-700', 'hybrid' => 'bg-purple-100 text-purple-700'];
                $metodeLabels = ['online' => '📹 Online', 'offline' => '📍 Offline', 'hybrid' => '🔀 Hybrid'];
            @endphp
            <div class="bg-white rounded-2xl border border-hairline shadow-sm p-5 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-violet-200 transition">
                <!-- Date block -->
                <div class="shrink-0 text-center w-14">
                    <div class="text-2xl font-extrabold text-slate-800">{{ \Carbon\Carbon::parse($meeting->tanggal)->format('d') }}</div>
                    <div class="text-[10px] font-bold uppercase text-slate-400">{{ \Carbon\Carbon::parse($meeting->tanggal)->locale('id')->isoFormat('MMM YY') }}</div>
                </div>

                <!-- Divider -->
                <div class="hidden sm:block w-px h-12 bg-hairline"></div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $sc['bg'] }} {{ $sc['text'] }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $sc['dot'] }}"></span>
                            {{ $sc['label'] }}
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $metodeColors[$meeting->metode] ?? 'bg-slate-100 text-slate-500' }}">
                            {{ $metodeLabels[$meeting->metode] ?? $meeting->metode }}
                        </span>
                    </div>
                    <h3 class="font-bold text-sm text-slate-800 truncate">{{ $meeting->judul_rapat }}</h3>
                    <div class="flex flex-wrap gap-3 mt-1.5 text-[11px] text-slate-500">
                        <span>🕐 {{ \Carbon\Carbon::parse($meeting->waktu_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($meeting->waktu_selesai)->format('H:i') }} WIB</span>
                        @if($meeting->nama_tempat)
                            <span>📍 {{ $meeting->nama_tempat }}</span>
                        @endif
                        <span>👥 {{ $meeting->attendances->count() }} peserta</span>
                        <span>Oleh: {{ $meeting->creator->nama ?? '-' }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('meetings.show', $meeting) }}"
                       class="h-8 px-3 flex items-center rounded-lg bg-violet-50 hover:bg-violet-100 text-violet-700 text-xs font-semibold transition">
                        Detail
                    </a>
                    @if(auth()->user()->isSupervisor())
                        <a href="{{ route('meetings.edit', $meeting) }}"
                           class="h-8 w-8 flex items-center justify-center rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 transition">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('meetings.destroy', $meeting) }}" onsubmit="return confirm('Hapus/batalkan rapat ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="h-8 w-8 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-hairline shadow-sm p-16 text-center">
                <div class="flex flex-col items-center gap-3">
                    <div class="h-14 w-14 rounded-2xl bg-violet-50 flex items-center justify-center">
                        <svg class="h-7 w-7 text-violet-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5m-9-6h.008v.008H12V10.5zm0 3h.008v.008H12v-.008zm0 3h.008v.008H12V16.5z" />
                        </svg>
                    </div>
                    <div class="text-slate-400 text-xs">Belum ada rapat yang dijadwalkan.</div>
                    @if(auth()->user()->isSupervisor())
                        <a href="{{ route('meetings.create') }}" class="text-xs font-semibold text-violet-600 hover:underline">+ Jadwalkan Rapat Sekarang</a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    @if($meetings->hasPages())
        <div class="bg-white rounded-2xl border border-hairline shadow-sm p-4">
            {{ $meetings->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
