@extends('layouts.app')

@section('title', 'Detail Kinerja: ' . $sale->nama)

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <div class="flex items-center gap-4">
            @if($sale->foto_url)
                <img src="{{ $sale->foto_url }}" alt="{{ $sale->nama }}" class="h-16 w-16 rounded-2xl object-cover border-2 border-blue-200 shadow-sm">
            @else
                <div class="h-16 w-16 rounded-2xl bg-blue-100 text-[#0A4DF3] font-extrabold text-xl flex items-center justify-center border-2 border-blue-200 shadow-sm">
                    {{ $sale->avatar_initials }}
                </div>
            @endif
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-[#0A4DF3]">
                        SLS-{{ str_pad($sale->id, 3, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="text-xs font-semibold text-slate-500">{{ $sale->cabang ?? 'Yamaha JG Purwakarta' }}</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">
                    {{ $sale->nama }}
                </h1>
                <p class="text-xs text-slate-500">
                    {{ $sale->email }} &bull; {{ $sale->no_hp ?: 'No HP belum diatur' }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('reports.sales-person.word', ['sales_id' => $sale->id, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition shadow-xs">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <span>Unduh Laporan Sales (.docx)</span>
            </a>
            <a href="{{ route('sales.index') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                &larr; Kembali
            </a>
        </div>
    </div>

    <!-- Filter Periode Bulan / Tahun -->
    <form method="GET" action="{{ route('sales.show', $sale->id) }}" class="bg-white p-3.5 rounded-2xl border border-hairline shadow-sm flex items-center justify-between gap-3 text-xs">
        <div class="flex items-center gap-2">
            <span class="font-bold text-slate-700">Filter Periode Kinerja:</span>
            <select name="bulan" class="h-9 min-w-[135px] rounded-xl border border-slate-200 bg-white pl-3 pr-8 text-xs font-semibold text-slate-700 hover:border-slate-300 focus:border-[#0A4DF3] focus:ring-1 focus:ring-[#0A4DF3]/20 focus:outline-none transition cursor-pointer">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create(2026, $m, 1)->locale('id')->isoFormat('MMMM') }}
                    </option>
                @endfor
            </select>
            <select name="tahun" class="h-9 min-w-[95px] rounded-xl border border-slate-200 bg-white pl-3 pr-8 text-xs font-semibold text-slate-700 hover:border-slate-300 focus:border-[#0A4DF3] focus:ring-1 focus:ring-[#0A4DF3]/20 focus:outline-none transition cursor-pointer">
                @foreach(range(date('Y') - 1, date('Y') + 1) as $y)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <button type="submit" class="h-9 px-4 rounded-xl bg-[#0A4DF3] text-white font-bold hover:bg-blue-700 shadow-xs transition cursor-pointer">
                Terapkan
            </button>
        </div>
    </form>

    <!-- Ringkasan Statistik Periode Terpilih -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-hairline shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Unit Terjual</span>
            <div class="text-2xl font-extrabold text-emerald-600">{{ $totalUnitTerjual }} <span class="text-xs text-slate-500">Unit</span></div>
            <p class="text-[10px] text-slate-400 mt-0.5">Transaksi valid periode ini</p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-hairline shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Target Penjualan</span>
            <div class="text-2xl font-extrabold text-slate-900">{{ $targetUnit }} <span class="text-xs text-slate-500">Unit</span></div>
            <p class="text-[10px] text-slate-400 mt-0.5">Sisa target: {{ max(0, $targetUnit - $totalUnitTerjual) }} unit</p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-hairline shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Capaian Target</span>
            <div class="text-2xl font-extrabold text-[#0A4DF3]">{{ $persentase }}%</div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2 overflow-hidden">
                <div class="bg-[#0A4DF3] h-1.5 rounded-full" style="width: {{ min(100, $persentase) }}%"></div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-hairline shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Status Kinerja</span>
            @php
                $badgeStyle = match($statusKinerja) {
                    'Sangat Baik' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                    'Baik' => 'bg-blue-100 text-blue-800 border-blue-200',
                    'Cukup' => 'bg-amber-100 text-amber-800 border-amber-200',
                    default => 'bg-rose-100 text-rose-800 border-rose-200',
                };
            @endphp
            <div>
                <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-extrabold border {{ $badgeStyle }}">
                    {{ $statusKinerja }}
                </span>
            </div>
            <p class="text-[10px] text-slate-400 mt-1.5">Kategori evaluasi SIMAKIS</p>
        </div>
    </div>

    <!-- Riwayat Transaksi Penjualan Valid -->
    <div class="bg-white rounded-2xl border border-hairline shadow-sm p-5 space-y-3.5">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Riwayat Transaksi Penjualan (Bulan {{ $bulan }}/{{ $tahun }})</h3>
            <span class="text-xs text-slate-500 font-semibold">{{ $transactions->count() }} Transaksi</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                        <th class="py-2.5 px-3">No. Transaksi</th>
                        <th class="py-2.5 px-3">Tanggal</th>
                        <th class="py-2.5 px-3">Konsumen</th>
                        <th class="py-2.5 px-3">Model Motor &amp; Warna</th>
                        <th class="py-2.5 px-3 text-center">Unit</th>
                        <th class="py-2.5 px-3 text-right">Total Nilai</th>
                        <th class="py-2.5 px-3 text-center">Skema</th>
                        <th class="py-2.5 px-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $t)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-2.5 px-3 font-mono font-bold text-[#0A4DF3]">{{ $t->nomor_transaksi }}</td>
                            <td class="py-2.5 px-3">{{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d/m/Y') }}</td>
                            <td class="py-2.5 px-3 font-semibold text-slate-900">{{ $t->nama_konsumen }}</td>
                            <td class="py-2.5 px-3">{{ $t->motorcycle->nama_model ?? '-' }} ({{ $t->nama_warna ?: '-' }})</td>
                            <td class="py-2.5 px-3 text-center font-bold text-emerald-600">{{ $t->jumlah_unit }}</td>
                            <td class="py-2.5 px-3 text-right font-medium">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                            <td class="py-2.5 px-3 text-center uppercase text-[10px] font-bold">{{ $t->skema_pembelian }}</td>
                            <td class="py-2.5 px-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $t->status_transaksi === 'valid' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ ucfirst($t->status_transaksi) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-slate-400 italic">Belum ada transaksi penjualan pada bulan yang dipilih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
