@extends('layouts.app')

@section('title', 'Temuan Lapangan Sales')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-teal-100 text-teal-700">SIMAKIS</span>
                <span class="text-xs text-slate-400 font-medium">Dealer Yamaha JG Purwakarta</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-1">Temuan Lapangan Sales</h1>
            <p class="text-xs text-slate-500 mt-0.5">Pencatatan dan tindak lanjut catatan interaksi konsumen, kendala, dan prospek di lapangan.</p>
        </div>
        <div class="flex items-center gap-2">
            @if($unresolvedCount > 0)
                <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-red-50 border border-red-200 text-red-600 text-xs font-bold">
                    {{ $unresolvedCount }} Belum Ditindaklanjuti
                </span>
            @endif
            <a href="{{ route('field-findings.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-sm transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                + Catat Temuan
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 text-xs font-medium rounded-xl px-4 py-3">
            <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <form method="GET" action="{{ route('field-findings.index') }}" class="bg-white p-4 rounded-2xl border border-hairline shadow-sm flex flex-wrap items-center gap-3 text-xs">
        <div class="relative min-w-[180px]">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama konsumen, kendala..."
                   class="h-9 w-full rounded-xl border border-slate-200 pl-8 pr-3 text-xs focus:border-teal-400 focus:outline-none">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5 text-slate-400">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
        </div>
        <select name="status" class="h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:border-teal-400 focus:outline-none">
            <option value="">Semua Status</option>
            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="sedang_diproses" {{ request('status') == 'sedang_diproses' ? 'selected' : '' }}>Sedang Diproses</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <select name="status_minat" class="h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:border-teal-400 focus:outline-none">
            <option value="">Semua Minat</option>
            <option value="tinggi" {{ request('status_minat') == 'tinggi' ? 'selected' : '' }}>Minat Tinggi</option>
            <option value="sedang" {{ request('status_minat') == 'sedang' ? 'selected' : '' }}>Minat Sedang</option>
            <option value="rendah" {{ request('status_minat') == 'rendah' ? 'selected' : '' }}>Minat Rendah</option>
        </select>
        @if(auth()->user()->isSupervisor())
            <select name="sales_id" class="h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:border-teal-400 focus:outline-none">
                <option value="">Semua Sales</option>
                @foreach($salesList as $s)
                    <option value="{{ $s->id }}" {{ request('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                @endforeach
            </select>
        @endif
        <input type="date" name="start_date" value="{{ request('start_date') }}" class="h-9 rounded-xl border border-slate-200 px-3 text-xs focus:border-teal-400 focus:outline-none">
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="h-9 rounded-xl border border-slate-200 px-3 text-xs focus:border-teal-400 focus:outline-none">
        <div class="flex items-center gap-1.5">
            <input type="checkbox" id="unresolved_only" name="unresolved_only" value="1" {{ request('unresolved_only') ? 'checked' : '' }} class="h-3.5 w-3.5 rounded accent-teal-600">
            <label for="unresolved_only" class="text-xs text-slate-600 cursor-pointer">Belum Selesai</label>
        </div>
        <button type="submit" class="h-9 px-4 rounded-xl bg-teal-600 text-white font-semibold hover:bg-teal-700 transition">Filter</button>
        <a href="{{ route('field-findings.index') }}" class="h-9 px-4 flex items-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition">Reset</a>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-hairline shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-hairline text-slate-500 uppercase tracking-wide text-[10px]">
                        <th class="text-left px-4 py-3 font-semibold">Tanggal</th>
                        @if(auth()->user()->isSupervisor())
                            <th class="text-left px-4 py-3 font-semibold">Sales</th>
                        @endif
                        <th class="text-left px-4 py-3 font-semibold">Konsumen</th>
                        <th class="text-left px-4 py-3 font-semibold">Motor</th>
                        <th class="text-center px-4 py-3 font-semibold">Minat</th>
                        <th class="text-left px-4 py-3 font-semibold">Kendala</th>
                        <th class="text-center px-4 py-3 font-semibold">Status</th>
                        <th class="text-center px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($findings as $f)
                        @php
                            $minatColors = ['tinggi' => 'bg-emerald-100 text-emerald-700', 'sedang' => 'bg-amber-100 text-amber-700', 'rendah' => 'bg-slate-100 text-slate-500'];
                            $statusColors = [
                                'menunggu'       => 'bg-amber-100 text-amber-700',
                                'sedang_diproses' => 'bg-blue-100 text-blue-700',
                                'selesai'        => 'bg-emerald-100 text-emerald-700',
                                'dibatalkan'     => 'bg-red-100 text-red-600',
                            ];
                            $statusLabels = [
                                'menunggu'       => 'Menunggu',
                                'sedang_diproses' => 'Diproses',
                                'selesai'        => 'Selesai',
                                'dibatalkan'     => 'Dibatalkan',
                            ];
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 text-slate-500 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($f->tanggal_temuan)->locale('id')->isoFormat('D MMM YYYY') }}
                            </td>
                            @if(auth()->user()->isSupervisor())
                                <td class="px-4 py-3">
                                    <span class="font-semibold text-slate-700">{{ $f->user->nama ?? '-' }}</span>
                                </td>
                            @endif
                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-700">{{ $f->nama_konsumen ?? 'Anonim' }}</div>
                                @if($f->kontak_konsumen)
                                    <div class="text-slate-400 text-[10px]">{{ $f->kontak_konsumen }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ $f->motorcycle->nama_model ?? '-' }}
                                @if($f->warna_motor) <span class="text-slate-400">· {{ $f->warna_motor }}</span> @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $minatColors[$f->status_minat] ?? '' }}">
                                    {{ ucfirst($f->status_minat) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-500 max-w-[160px] truncate">{{ $f->kendala ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $statusColors[$f->status_tindak_lanjut] ?? '' }}">
                                    {{ $statusLabels[$f->status_tindak_lanjut] ?? $f->status_tindak_lanjut }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('field-findings.show', $f) }}"
                                       class="h-7 w-7 flex items-center justify-center rounded-lg bg-teal-50 hover:bg-teal-100 text-teal-700 transition" title="Detail">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('field-findings.edit', $f) }}"
                                       class="h-7 w-7 flex items-center justify-center rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 transition" title="Edit">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('field-findings.destroy', $f) }}" onsubmit="return confirm('Hapus temuan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="h-7 w-7 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition" title="Hapus">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isSupervisor() ? 8 : 7 }}" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="h-12 w-12 rounded-2xl bg-teal-50 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-teal-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z" />
                                        </svg>
                                    </div>
                                    <div class="text-slate-400 text-xs">Belum ada catatan temuan lapangan.</div>
                                    <a href="{{ route('field-findings.create') }}" class="text-xs font-semibold text-teal-600 hover:underline">+ Catat Temuan Sekarang</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($findings->hasPages())
            <div class="px-4 py-3 border-t border-hairline">{{ $findings->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
