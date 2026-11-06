@extends('layouts.app')

@section('title', 'Program Reward & Apresiasi Sales')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-700">SIMAKIS</span>
                <span class="text-xs text-slate-400 font-medium">Dealer Yamaha JG Purwakarta</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-1">Program Reward & Apresiasi Sales</h1>
            <p class="text-xs text-slate-500 mt-0.5">Pantau progress pencapaian reward dan apresiasi kinerja terbaik sales.</p>
        </div>
        @if(auth()->user()->isSupervisor())
            <a href="{{ route('rewards.programs.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold shadow-sm transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                + Buat Program Reward
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

    <!-- Period Selector -->
    <form method="GET" action="{{ route('rewards.index') }}" class="bg-white p-3.5 sm:p-4 rounded-2xl border border-hairline shadow-xs flex flex-wrap items-center gap-2.5 text-xs">
        <span class="text-slate-500 font-bold uppercase tracking-wider text-[11px] mr-1">Periode Reward:</span>
        <select name="bulan" class="h-9 min-w-[145px] rounded-xl border border-slate-200 pl-3.5 pr-8 text-xs font-semibold bg-white text-slate-700 hover:border-slate-300 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/20 focus:outline-none transition cursor-pointer">
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create(2026, $m, 1)->locale('id')->isoFormat('MMMM') }}
                </option>
            @endfor
        </select>
        <select name="tahun" class="h-9 min-w-[105px] rounded-xl border border-slate-200 pl-3.5 pr-8 text-xs font-semibold bg-white text-slate-700 hover:border-slate-300 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/20 focus:outline-none transition cursor-pointer">
            @foreach(range(date('Y'), 2024) as $y)
                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
        <button type="submit" class="h-9 px-4 rounded-xl bg-amber-500 text-white font-bold hover:bg-amber-600 shadow-xs transition cursor-pointer">Tampilkan</button>
    </form>

    <!-- Active Program Banner -->
    @if($activeProgram)
        <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl p-5 text-white shadow-md">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.504-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.003 0H9.497m5.003 0a2.25 2.25 0 002.25-2.25v-.872c0-.528-.364-.978-.883-1.06a8.27 8.27 0 00-6.734 0c-.519.082-.883.532-.883 1.06v.872a2.25 2.25 0 002.25 2.25m6-7.5a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                        <span class="text-xs font-bold uppercase tracking-widest opacity-80">Program Aktif</span>
                    </div>
                    <h2 class="text-lg font-extrabold">{{ $activeProgram->nama_program }}</h2>
                    <p class="text-sm opacity-80 mt-0.5">{{ $activeProgram->deskripsi }}</p>
                </div>
                <div class="shrink-0 bg-white/20 rounded-2xl p-4 text-center min-w-[120px]">
                    <div class="text-3xl font-extrabold">{{ $activeProgram->target_minimal_unit }}</div>
                    <div class="text-xs opacity-80 font-medium mt-1">Unit Min. untuk Reward</div>
                    <div class="mt-2 text-sm font-bold">Rp {{ number_format($activeProgram->nominal_hadiah, 0, ',', '.') }}</div>
                    <div class="text-[10px] opacity-70">{{ ucwords(str_replace('_', ' ', $activeProgram->jenis_hadiah)) }}</div>
                </div>
            </div>
        </div>
    @endif

    <!-- Sales Progress Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse($salesProgress as $item)
            @php
                $pct = min(100, $item['persentase']);
                $isEligible = $item['is_eligible'];
                $status = $item['reward_record']?->status ?? 'belum_tercapai';
                $statusColors = [
                    'belum_tercapai'    => ['bg' => 'bg-slate-100', 'text' => 'text-slate-500', 'label' => 'Belum Tercapai'],
                    'menunggu_verifikasi' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Menunggu Verifikasi'],
                    'disetujui'         => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Disetujui'],
                    'hadiah_diberikan'  => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Hadiah Diberikan ✓'],
                ];
                $sc = $statusColors[$status] ?? $statusColors['belum_tercapai'];
            @endphp
            <div class="bg-white rounded-2xl border border-hairline shadow-sm p-5 space-y-4 {{ $isEligible ? 'ring-2 ring-amber-400 ring-offset-2' : '' }}">
                <!-- Header Sales -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                {{ strtoupper(substr($item['user']->nama, 0, 2)) }}
                            </div>
                            @if($isEligible)
                                <div class="absolute -top-1 -right-1 h-5 w-5 bg-amber-400 rounded-full flex items-center justify-center border-2 border-white">
                                    <svg class="h-2.5 w-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="font-bold text-sm text-slate-800">{{ $item['user']->nama }}</div>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold {{ $sc['bg'] }} {{ $sc['text'] }}">{{ $sc['label'] }}</span>
                        </div>
                    </div>
                    @if($isEligible)
                        <span class="inline-flex items-center gap-1 text-xs font-black text-amber-500">
                            <svg class="h-3.5 w-3.5 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0016.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.98 6.98 0 01-2.77.588 6.98 6.98 0 01-2.77-.588" />
                            </svg>
                            <span>Eligible!</span>
                        </span>
                    @endif
                </div>

                <!-- Progress -->
                <div>
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="text-slate-500">{{ $item['realisasi_unit'] }} / {{ $item['target_reward'] }} unit</span>
                        <span class="font-bold {{ $isEligible ? 'text-amber-600' : 'text-slate-500' }}">{{ $item['persentase'] }}%</span>
                    </div>
                    <div class="h-3 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full {{ $isEligible ? 'bg-amber-400' : 'bg-blue-400' }} transition-all duration-500"
                             style="width: {{ min(100, $item['persentase']) }}%"></div>
                    </div>
                    @if(!$isEligible)
                        <p class="text-[10px] text-slate-400 mt-1">Butuh {{ $item['sisa_unit'] }} unit lagi untuk mencapai target reward.</p>
                    @else
                        <p class="text-[10px] text-amber-600 font-semibold mt-1">✓ Target reward telah tercapai!</p>
                    @endif
                </div>

                <!-- Supervisor Verification -->
                @if(auth()->user()->isSupervisor() && $item['reward_record'])
                    <div class="border-t border-hairline pt-3">
                        <form method="POST" action="{{ route('rewards.verify', $item['reward_record']->id) }}" class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <select name="status" class="flex-1 h-9 rounded-xl border border-slate-200 pl-3 pr-8 text-xs font-semibold bg-white text-slate-700 hover:border-slate-300 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/20 focus:outline-none transition cursor-pointer">
                                <option value="menunggu_verifikasi" {{ $status == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                <option value="disetujui" {{ $status == 'disetujui' ? 'selected' : '' }}>Setujui</option>
                                <option value="hadiah_diberikan" {{ $status == 'hadiah_diberikan' ? 'selected' : '' }}>Hadiah Diberikan</option>
                                <option value="belum_tercapai" {{ $status == 'belum_tercapai' ? 'selected' : '' }}>Belum Tercapai</option>
                            </select>
                            <button type="submit" class="h-9 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold shadow-xs transition shrink-0 cursor-pointer">
                                Simpan
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-3 bg-white rounded-2xl border border-hairline shadow-sm p-16 text-center text-slate-400 text-xs">
                Belum ada data sales aktif untuk periode ini.
            </div>
        @endforelse
    </div>

    <!-- Reward History -->
    @if($history->count())
        <div class="bg-white rounded-2xl border border-hairline shadow-sm">
            <div class="px-5 py-4 border-b border-hairline">
                <h3 class="font-bold text-sm text-slate-800">Riwayat Reward Sales</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-hairline text-slate-500 uppercase tracking-wide text-[10px]">
                            <th class="text-left px-4 py-3">Sales</th>
                            <th class="text-left px-4 py-3">Program</th>
                            <th class="text-center px-4 py-3">Periode</th>
                            <th class="text-center px-4 py-3">Realisasi</th>
                            <th class="text-center px-4 py-3">Status</th>
                            <th class="text-left px-4 py-3">Diverifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($history as $h)
                            @php
                                $statusColors = [
                                    'belum_tercapai'    => 'bg-slate-100 text-slate-500',
                                    'menunggu_verifikasi' => 'bg-amber-100 text-amber-700',
                                    'disetujui'         => 'bg-blue-100 text-blue-700',
                                    'hadiah_diberikan'  => 'bg-emerald-100 text-emerald-700',
                                ];
                                $statusLabels = [
                                    'belum_tercapai'    => 'Belum Tercapai',
                                    'menunggu_verifikasi' => 'Menunggu',
                                    'disetujui'         => 'Disetujui',
                                    'hadiah_diberikan'  => 'Diberikan ✓',
                                ];
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-medium text-slate-700">{{ $h->user->nama ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $h->program->nama_program ?? '-' }}</td>
                                <td class="px-4 py-3 text-center text-slate-500">
                                    {{ \Carbon\Carbon::create($h->periode_tahun, $h->periode_bulan, 1)->locale('id')->isoFormat('MMM YYYY') }}
                                </td>
                                <td class="px-4 py-3 text-center font-semibold">{{ $h->realisasi_unit }} unit</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $statusColors[$h->status] ?? 'bg-slate-100 text-slate-500' }}">
                                        {{ $statusLabels[$h->status] ?? $h->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-500">{{ $h->verifier->nama ?? '-' }}</td>
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
@endsection
