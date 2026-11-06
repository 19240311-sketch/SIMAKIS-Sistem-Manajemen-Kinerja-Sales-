@extends('layouts.app')

@section('title', 'Evaluasi Kinerja Sales')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-indigo-100 text-indigo-700">SIMAKIS</span>
                <span class="text-xs text-slate-400 font-medium">Dealer Yamaha JG Purwakarta</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-1">Evaluasi Kinerja Sales</h1>
            <p class="text-xs text-slate-500 mt-0.5">Penilaian dan pemantauan capaian kinerja berdasarkan realisasi unit vs target per periode.</p>
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

    <!-- Filter -->
    <form method="GET" action="{{ route('performance.index') }}" class="bg-white p-4 rounded-2xl border border-hairline shadow-sm flex flex-wrap items-center gap-3 text-xs">
        <select name="bulan" class="h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:border-[#0A4DF3] focus:outline-none">
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create(2026, $m, 1)->locale('id')->isoFormat('MMMM') }}
                </option>
            @endfor
        </select>
        <select name="tahun" class="h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:border-[#0A4DF3] focus:outline-none">
            @foreach(range(date('Y'), 2024) as $y)
                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
        @if(auth()->user()->isSupervisor())
            <select name="sales_id" class="h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:border-[#0A4DF3] focus:outline-none">
                <option value="">Semua Sales</option>
                @foreach($allSalesOptions as $s)
                    <option value="{{ $s->id }}" {{ $selectedSalesId == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                @endforeach
            </select>
        @endif
        <button type="submit" class="h-9 px-4 rounded-xl bg-[#0A4DF3] text-white font-semibold hover:bg-blue-700 transition">Filter</button>
        <a href="{{ route('performance.index') }}" class="h-9 px-4 flex items-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition">Reset</a>
    </form>

    <!-- Performance Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse($displayList as $item)
            @php
                $pct = $item['persentase'];
                $colorClass = match(true) {
                    $pct >= 100 => ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-700', 'badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200', 'bar' => 'bg-emerald-500'],
                    $pct >= 80  => ['bg' => 'bg-blue-500', 'text' => 'text-blue-700', 'badge' => 'bg-blue-50 text-[#0A4DF3] border border-blue-200', 'bar' => 'bg-blue-500'],
                    $pct >= 60  => ['bg' => 'bg-amber-500', 'text' => 'text-amber-700', 'badge' => 'bg-amber-50 text-amber-700 border border-amber-200', 'bar' => 'bg-amber-500'],
                    default     => ['bg' => 'bg-red-500', 'text' => 'text-red-700', 'badge' => 'bg-rose-50 text-rose-700 border border-rose-200', 'bar' => 'bg-red-400'],
                };
            @endphp
            <div class="bg-white rounded-2xl border border-hairline shadow-sm p-5 space-y-4">
                <!-- Sales info -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="h-11 w-11 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                {{ strtoupper(substr($item['user']->nama, 0, 2)) }}
                            </div>
                            <div class="absolute -top-1 -right-1 h-5 w-5 rounded-full {{ $colorClass['bg'] }} text-white flex items-center justify-center text-[9px] font-black border-2 border-white">
                                {{ $item['peringkat'] }}
                            </div>
                        </div>
                        <div>
                            <div class="font-bold text-sm text-slate-800">{{ $item['user']->nama }}</div>
                            <div class="text-[10px] text-slate-400">{{ $item['user']->kode_sales ?? $item['user']->email }}</div>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold {{ $colorClass['badge'] }}">
                        {{ $item['status_kinerja'] ?? '-' }}
                    </span>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="bg-slate-50 rounded-xl p-2.5">
                        <div class="text-base font-extrabold text-slate-800">{{ $item['target_unit'] }}</div>
                        <div class="text-[9px] text-slate-400 font-medium uppercase mt-0.5">Target</div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-2.5">
                        <div class="text-base font-extrabold text-slate-800">{{ $item['realisasi_unit'] }}</div>
                        <div class="text-[9px] text-slate-400 font-medium uppercase mt-0.5">Realisasi</div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-2.5">
                        <div class="text-base font-extrabold {{ $colorClass['text'] }}">{{ number_format($pct, 1) }}%</div>
                        <div class="text-[9px] text-slate-400 font-medium uppercase mt-0.5">Capaian</div>
                    </div>
                </div>

                <!-- Progress bar -->
                <div>
                    <div class="flex justify-between text-[10px] text-slate-400 mb-1">
                        <span>Progress Pencapaian</span>
                        <span class="{{ $colorClass['text'] }} font-bold">{{ min(100, $pct) }}%</span>
                    </div>
                    <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full {{ $colorClass['bar'] }} transition-all duration-500"
                             style="width: {{ min(100, $pct) }}%"></div>
                    </div>
                </div>

                @if(auth()->user()->isSupervisor())
                    <!-- Evaluation form -->
                    <div class="border-t border-hairline pt-3">
                        @if($item['evaluation'])
                            <div class="text-[10px] text-slate-500 mb-2 flex items-center gap-1">
                                <svg class="h-3 w-3 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Sudah dievaluasi oleh Supervisor
                            </div>
                        @endif
                        <button type="button" onclick="toggleEvalForm('eval-{{ $item['user']->id }}')"
                                class="w-full text-center text-xs font-semibold text-[#0A4DF3] hover:underline">
                            {{ $item['evaluation'] ? 'Edit Catatan Evaluasi' : '+ Tambah Catatan Evaluasi' }}
                        </button>
                        <div id="eval-{{ $item['user']->id }}" class="hidden mt-3">
                            <form method="POST" action="{{ route('performance.evaluate') }}" class="space-y-2">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $item['user']->id }}">
                                <input type="hidden" name="periode_bulan" value="{{ $bulan }}">
                                <input type="hidden" name="periode_tahun" value="{{ $tahun }}">
                                <input type="hidden" name="target_unit" value="{{ $item['target_unit'] }}">
                                <input type="hidden" name="realisasi_unit" value="{{ $item['realisasi_unit'] }}">
                                <input type="hidden" name="persentase_capaian" value="{{ $pct }}">
                                <input type="hidden" name="peringkat" value="{{ $item['peringkat'] }}">
                                <input type="hidden" name="status_kinerja" value="{{ $item['status_kinerja'] }}">
                                <textarea name="catatan_evaluasi" rows="2" required placeholder="Catatan evaluasi kinerja sales..."
                                          class="w-full rounded-xl border border-slate-200 px-2.5 py-1.5 text-[11px] focus:border-[#0A4DF3] focus:outline-none resize-none">{{ $item['evaluation']?->catatan_evaluasi }}</textarea>
                                <textarea name="rekomendasi" rows="2" placeholder="Rekomendasi (opsional)..."
                                          class="w-full rounded-xl border border-slate-200 px-2.5 py-1.5 text-[11px] focus:border-[#0A4DF3] focus:outline-none resize-none">{{ $item['evaluation']?->rekomendasi }}</textarea>
                                <button type="submit" class="w-full py-1.5 rounded-lg bg-[#0A4DF3] text-white text-[11px] font-bold hover:bg-blue-700 transition">Simpan Evaluasi</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-3 bg-white rounded-2xl border border-hairline shadow-sm p-16 text-center">
                <div class="flex flex-col items-center gap-3">
                    <div class="h-12 w-12 rounded-2xl bg-slate-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75z" />
                        </svg>
                    </div>
                    <div class="text-slate-400 text-xs">Tidak ada data kinerja untuk periode yang dipilih.</div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Kriteria Penilaian -->
    @if(auth()->user()->isSupervisor() && $criteria->count())
        <div class="bg-white rounded-2xl border border-hairline shadow-sm">
            <div class="px-5 py-4 border-b border-hairline flex items-center justify-between">
                <h3 class="font-bold text-sm text-slate-800">Kriteria Penilaian Kinerja</h3>
                <button type="button" onclick="toggleSection('criteria-form')" class="text-xs font-semibold text-[#0A4DF3] hover:underline">
                    Edit Batas Persentase
                </button>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                    @foreach($criteria as $c)
                        @php
                            $badgeClass = match(strtolower($c->label ?? '')) {
                                'excellent', 'sangat baik' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                'good', 'baik'             => 'bg-blue-50 text-[#0A4DF3] border border-blue-200',
                                'cukup', 'fair'            => 'bg-amber-50 text-amber-700 border border-amber-200',
                                default                    => 'bg-rose-50 text-rose-700 border border-rose-200',
                            };
                        @endphp
                        <div class="rounded-xl bg-slate-50 p-3 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold {{ $badgeClass }}">{{ $c->label }}</span>
                            <p class="mt-1.5 font-semibold text-slate-600">{{ $c->persentase_min }}% – {{ $c->persentase_max !== null ? $c->persentase_max.'%' : '∞' }}</p>
                            @if($c->deskripsi)
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $c->deskripsi }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div id="criteria-form" class="hidden mt-4 border-t border-hairline pt-4">
                    <form method="POST" action="{{ route('performance.criteria.update') }}" class="space-y-3">
                        @csrf @method('PUT')
                        @foreach($criteria as $c)
                            <div class="flex items-center gap-3">
                                <input type="hidden" name="criteria[{{ $loop->index }}][id]" value="{{ $c->id }}">
                                <span class="text-xs font-semibold text-slate-600 w-24 shrink-0">{{ $c->label }}</span>
                                <input type="number" name="criteria[{{ $loop->index }}][persentase_min]" value="{{ $c->persentase_min }}" min="0" step="0.1"
                                       placeholder="Min %" class="h-8 w-24 rounded-lg border border-slate-200 px-2 text-xs focus:border-[#0A4DF3] focus:outline-none">
                                <span class="text-slate-400 text-xs">–</span>
                                <input type="number" name="criteria[{{ $loop->index }}][persentase_max]" value="{{ $c->persentase_max }}" min="0" step="0.1"
                                       placeholder="Max %" class="h-8 w-24 rounded-lg border border-slate-200 px-2 text-xs focus:border-[#0A4DF3] focus:outline-none">
                                <input type="text" name="criteria[{{ $loop->index }}][deskripsi]" value="{{ $c->deskripsi }}" placeholder="Deskripsi"
                                       class="h-8 flex-1 rounded-lg border border-slate-200 px-2 text-xs focus:border-[#0A4DF3] focus:outline-none">
                            </div>
                        @endforeach
                        <div class="flex justify-end">
                            <button type="submit" class="px-5 py-2 rounded-xl bg-[#0A4DF3] text-white text-xs font-bold hover:bg-blue-700 transition">Simpan Kriteria</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- History Evaluasi -->
    @if($history->count())
        <div class="bg-white rounded-2xl border border-hairline shadow-sm">
            <div class="px-5 py-4 border-b border-hairline">
                <h3 class="font-bold text-sm text-slate-800">Riwayat Catatan Evaluasi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-hairline text-slate-500 uppercase tracking-wide text-[10px]">
                            <th class="text-left px-4 py-3">Sales</th>
                            <th class="text-left px-4 py-3">Periode</th>
                            <th class="text-center px-4 py-3">Target</th>
                            <th class="text-center px-4 py-3">Realisasi</th>
                            <th class="text-center px-4 py-3">Capaian</th>
                            <th class="text-left px-4 py-3">Catatan Evaluasi</th>
                            <th class="text-left px-4 py-3">Supervisor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($history as $h)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-medium text-slate-700">{{ $h->user->nama ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ \Carbon\Carbon::create($h->periode_tahun, $h->periode_bulan, 1)->locale('id')->isoFormat('MMMM YYYY') }}</td>
                                <td class="px-4 py-3 text-center">{{ $h->target_unit }}</td>
                                <td class="px-4 py-3 text-center">{{ $h->realisasi_unit }}</td>
                                <td class="px-4 py-3 text-center font-bold {{ $h->persentase_capaian >= 100 ? 'text-emerald-600' : ($h->persentase_capaian >= 80 ? 'text-blue-600' : 'text-amber-600') }}">
                                    {{ number_format($h->persentase_capaian, 1) }}%
                                </td>
                                <td class="px-4 py-3 text-slate-600 max-w-sm whitespace-normal leading-relaxed break-words">{{ $h->catatan_evaluasi ?: '-' }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $h->supervisor->nama ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($history->hasPages())
                <div class="px-4 py-3 border-t border-hairline">{{ $history->withQueryString()->links() }}</div>
            @endif
        </div>
    @endif
</div>

@push('scripts')
<script>
function toggleEvalForm(id) {
    const el = document.getElementById(id);
    el.classList.toggle('hidden');
}
function toggleSection(id) {
    const el = document.getElementById(id);
    el.classList.toggle('hidden');
}
</script>
@endpush
@endsection
