@extends('layouts.app')

@section('title', 'Target Penjualan')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-blue-100 text-[#0A4DF3]">SIMAKIS</span>
                <span class="text-xs text-slate-400 font-medium">Dealer Yamaha JG Purwakarta</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-1">Target Penjualan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Penetapan dan pemantauan kuota target unit penjualan per sales per periode.</p>
        </div>
        @if(auth()->user()->isSupervisor())
            <a href="{{ route('targets.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A4DF3] hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                + Tetapkan Target Baru
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

    <!-- Filter -->
    <form method="GET" action="{{ route('targets.index') }}" class="bg-white p-3.5 sm:p-4 rounded-2xl border border-hairline shadow-xs flex flex-wrap items-center gap-2.5 text-xs">
        <div class="flex items-center gap-1.5 text-slate-400 font-semibold mr-1 shrink-0">
            <svg class="h-4 w-4 text-[#0A4DF3]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
            </svg>
            <span class="text-[11px] uppercase tracking-wider text-slate-500 font-bold">Filter Target:</span>
        </div>

        <select name="tahun" class="h-9 min-w-[125px] rounded-xl border border-slate-200 pl-3.5 pr-8 text-xs font-semibold bg-white text-slate-700 hover:border-slate-300 focus:border-[#0A4DF3] focus:ring-1 focus:ring-[#0A4DF3]/20 focus:outline-none transition cursor-pointer">
            @foreach(range(date('Y'), 2020) as $y)
                <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
            @endforeach
        </select>

        <select name="bulan" class="h-9 min-w-[145px] rounded-xl border border-slate-200 pl-3.5 pr-8 text-xs font-semibold bg-white text-slate-700 hover:border-slate-300 focus:border-[#0A4DF3] focus:ring-1 focus:ring-[#0A4DF3]/20 focus:outline-none transition cursor-pointer">
            <option value="">Semua Bulan</option>
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create(2026, $m, 1)->locale('id')->isoFormat('MMMM') }}
                </option>
            @endfor
        </select>

        <select name="periode_tipe" class="h-9 min-w-[135px] rounded-xl border border-slate-200 pl-3.5 pr-8 text-xs font-semibold bg-white text-slate-700 hover:border-slate-300 focus:border-[#0A4DF3] focus:ring-1 focus:ring-[#0A4DF3]/20 focus:outline-none transition cursor-pointer">
            <option value="">Semua Tipe</option>
            <option value="bulanan" {{ request('periode_tipe') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
            <option value="mingguan" {{ request('periode_tipe') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
            <option value="khusus" {{ request('periode_tipe') == 'khusus' ? 'selected' : '' }}>Khusus</option>
        </select>

        @if(auth()->user()->isSupervisor())
            <select name="sales_id" class="h-9 min-w-[160px] rounded-xl border border-slate-200 pl-3.5 pr-8 text-xs font-semibold bg-white text-slate-700 hover:border-slate-300 focus:border-[#0A4DF3] focus:ring-1 focus:ring-[#0A4DF3]/20 focus:outline-none transition cursor-pointer">
                <option value="">Semua Sales</option>
                @foreach($salesList as $s)
                    <option value="{{ $s->id }}" {{ request('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                @endforeach
            </select>
        @endif

        <div class="flex items-center gap-2 ml-auto sm:ml-0">
            <button type="submit" class="h-9 px-4 rounded-xl bg-[#0A4DF3] text-white font-bold hover:bg-blue-700 shadow-2xs transition inline-flex items-center gap-1.5 cursor-pointer">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <span>Filter</span>
            </button>
            <a href="{{ route('targets.index') }}" class="h-9 px-3.5 flex items-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 font-medium transition cursor-pointer">
                Reset
            </a>
        </div>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-hairline shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-hairline text-slate-500 uppercase tracking-wide text-[10px]">
                        <th class="text-left px-4 py-3 font-semibold">Sales</th>
                        <th class="text-left px-4 py-3 font-semibold">Periode</th>
                        <th class="text-left px-4 py-3 font-semibold">Tipe</th>
                        <th class="text-center px-4 py-3 font-semibold">Target Unit</th>
                        <th class="text-left px-4 py-3 font-semibold">Tanggal Berlaku</th>
                        <th class="text-left px-4 py-3 font-semibold">Ditetapkan Oleh</th>
                        <th class="text-left px-4 py-3 font-semibold">Catatan</th>
                        @if(auth()->user()->isSupervisor())
                            <th class="text-center px-4 py-3 font-semibold">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($targets as $target)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white text-[10px] font-bold shrink-0">
                                        {{ strtoupper(substr($target->user->nama ?? '-', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800">{{ $target->user->nama ?? '-' }}</div>
                                        <div class="text-slate-400 text-[10px]">{{ $target->user->kode_sales ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                @if($target->periode_tipe === 'bulanan')
                                    {{ \Carbon\Carbon::create($target->tahun, $target->bulan, 1)->locale('id')->isoFormat('MMMM YYYY') }}
                                @elseif($target->periode_tipe === 'mingguan')
                                    Minggu ke-{{ $target->minggu_ke }}, {{ $target->tahun }}
                                @else
                                    {{ \Carbon\Carbon::parse($target->tanggal_mulai)->locale('id')->isoFormat('D MMM') }} – {{ \Carbon\Carbon::parse($target->tanggal_selesai)->locale('id')->isoFormat('D MMM YYYY') }}
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $tipeColors = ['bulanan' => 'bg-blue-100 text-blue-700', 'mingguan' => 'bg-purple-100 text-purple-700', 'khusus' => 'bg-amber-100 text-amber-700'];
                                    $tipeLabels = ['bulanan' => 'Bulanan', 'mingguan' => 'Mingguan', 'khusus' => 'Khusus'];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $tipeColors[$target->periode_tipe] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $tipeLabels[$target->periode_tipe] ?? $target->periode_tipe }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-blue-600 text-white font-extrabold text-sm shadow">
                                    {{ $target->target_unit }}
                                </span>
                                <div class="text-[10px] text-slate-400 mt-0.5">unit</div>
                            </td>
                            <td class="px-4 py-3 text-slate-500">
                                {{ \Carbon\Carbon::parse($target->tanggal_mulai)->locale('id')->isoFormat('D MMM YYYY') }}
                                <span class="text-slate-300">–</span>
                                {{ \Carbon\Carbon::parse($target->tanggal_selesai)->locale('id')->isoFormat('D MMM YYYY') }}
                            </td>
                            <td class="px-4 py-3 text-slate-500">{{ $target->creator->nama ?? 'Sistem' }}</td>
                            <td class="px-4 py-3 text-slate-500 max-w-[150px] truncate">{{ $target->catatan ?? '-' }}</td>
                            @if(auth()->user()->isSupervisor())
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('targets.edit', $target) }}"
                                           class="h-7 w-7 flex items-center justify-center rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 transition" title="Edit">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('targets.destroy', $target) }}" onsubmit="return confirm('Hapus target ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="h-7 w-7 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition" title="Hapus">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isSupervisor() ? 8 : 7 }}" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="h-12 w-12 rounded-2xl bg-slate-100 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                                        </svg>
                                    </div>
                                    <div class="text-slate-400 text-xs">Belum ada target yang ditetapkan untuk periode ini.</div>
                                    @if(auth()->user()->isSupervisor())
                                        <a href="{{ route('targets.create') }}" class="text-xs font-semibold text-[#0A4DF3] hover:underline">+ Tetapkan Target Sekarang</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($targets->hasPages())
            <div class="px-4 py-3 border-t border-hairline">
                {{ $targets->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
