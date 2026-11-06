@extends('layouts.app')

@section('title', 'Laporan Penjualan per Sales')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-indigo-100 text-indigo-700">
                    SIMAKIS
                </span>
                <span class="text-xs text-slate-400 font-medium">Laporan Individual Sales</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-1">
                Laporan Penjualan per Sales
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Rincian capaian target dan distribusi penjualan model motor untuk setiap personil sales.
            </p>
        </div>

        @if($selectedSales)
        <div class="flex items-center gap-2">
            <a href="{{ route('reports.sales-person.word', ['sales_id' => $selectedSales->id, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span>Unduh Laporan Sales (.docx)</span>
            </a>
        </div>
        @endif
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <form method="GET" action="{{ route('reports.sales-person') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
            @if(auth()->user()->isSupervisor())
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Pilih Tenaga Sales <span class="text-rose-500">*</span></label>
                <select name="sales_id" class="w-full h-9 rounded-xl border border-slate-200 px-3 text-xs font-semibold text-slate-900 focus:border-[#0A4DF3] focus:outline-none" required>
                    <option value="">-- Pilih Sales --</option>
                    @foreach($salesList as $s)
                        <option value="{{ $s->id }}" {{ ($selectedSales && $selectedSales->id === $s->id) ? 'selected' : '' }}>
                            {{ $s->nama }} ({{ $s->kode_sales ?? 'Sales' }})
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Bulan</label>
                <select name="bulan" class="w-full h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:border-[#0A4DF3] focus:outline-none">
                    @php
                        $namaBulanList = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                    @endphp
                    @foreach($namaBulanList as $num => $nama)
                        <option value="{{ $num }}" {{ (int)$bulan === $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Tahun</label>
                <select name="tahun" class="w-full h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:border-[#0A4DF3] focus:outline-none">
                    @for($y = date('Y') + 1; $y >= date('Y') - 3; $y--)
                        <option value="{{ $y }}" {{ (int)$tahun === $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full h-9 rounded-xl bg-[#0A4DF3] hover:bg-blue-700 text-white font-bold text-xs transition flex items-center justify-center gap-1.5 shadow-sm">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <span>Tampilkan Laporan</span>
                </button>
            </div>
        </form>
    </div>

    @if(!$selectedSales)
        <!-- Empty State Prompt -->
        <div class="bg-white rounded-2xl border border-hairline shadow-sm p-12 text-center">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl mx-auto flex items-center justify-center mb-4">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">Silakan Pilih Sales Terlebih Dahulu</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
                Pilih salah satu personil sales pada formulir filter di atas untuk melihat detail capaian target, perolehan peringkat, dan rincian unit motor yang berhasil terjual.
            </p>
        </div>
    @else
        <!-- Sales Profile & Status Banner -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-2xl p-6 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur border border-white/20 flex items-center justify-center text-2xl font-black text-amber-400 shrink-0">
                    {{ strtoupper(substr($selectedSales->nama, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl font-extrabold text-white">{{ $selectedSales->nama }}</h2>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-400 text-slate-900">
                            {{ $peringkat }} Bulan Ini
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-300 mt-1">
                        <span>Kode: <strong class="text-white">{{ $selectedSales->kode_sales ?? 'N/A' }}</strong></span>
                        <span>•</span>
                        <span>Email: <strong class="text-white">{{ $selectedSales->email }}</strong></span>
                        @if($selectedSales->telepon)
                            <span>•</span>
                            <span>No. HP: <strong class="text-white">{{ $selectedSales->telepon }}</strong></span>
                        @endif
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1.5">
                        Periode Penilaian: <strong class="text-indigo-200">{{ $namaBulanList[(int)$bulan] ?? '' }} {{ $tahun }}</strong>
                    </p>
                </div>
            </div>

            <!-- Quick Score / Evaluation Badge -->
            <div class="bg-white/10 backdrop-blur rounded-xl p-4 border border-white/10 flex items-center gap-4 min-w-[200px] justify-between">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-300 block">Kategori Kinerja</span>
                    <span class="text-sm font-black text-emerald-400">
                        @if($persentase >= 100)
                            Sangat Baik (A)
                        @elseif($persentase >= 80)
                            Baik (B)
                        @elseif($persentase >= 60)
                            Cukup (C)
                        @else
                            Perlu Pembinaan (D)
                        @endif
                    </span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] uppercase font-bold text-slate-300 block">Capaian</span>
                    <span class="text-lg font-black text-white">{{ $persentase }}%</span>
                </div>
            </div>
        </div>

        <!-- 4 KPI Summary Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Realisasi Penjualan</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-emerald-600">{{ $totalUnit }}</span>
                    <span class="text-xs font-semibold text-slate-500">Unit</span>
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Transaksi valid</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Target Individu</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-[#0A4DF3]">{{ $targetUnit }}</span>
                    <span class="text-xs font-semibold text-slate-500">Unit</span>
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Bulan {{ $namaBulanList[(int)$bulan] ?? '' }}</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Status Target</span>
                <div class="flex items-baseline gap-2">
                    @if($sisaUnit == 0 && $targetUnit > 0)
                        <span class="text-lg font-extrabold text-emerald-600">Target Tercapai!</span>
                    @elseif($targetUnit == 0)
                        <span class="text-sm font-semibold text-slate-500">Target Belum Ditetapkan</span>
                    @else
                        <span class="text-2xl font-extrabold text-amber-600">-{{ $sisaUnit }}</span>
                        <span class="text-xs font-semibold text-slate-500">Unit Lagi</span>
                    @endif
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Kekurangan unit target</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Nilai Penjualan</span>
                <div class="text-xl font-extrabold text-slate-900 truncate">
                    Rp {{ number_format($totalNilai, 0, ',', '.') }}
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Kontribusi omset dealer</p>
            </div>
        </div>

        <!-- Breakdown by Motorcycle Model -->
        <div class="bg-white rounded-2xl border border-hairline shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-hairline flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Distribusi Penjualan per Model Motor</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Rincian tipe dan varian motor Yamaha yang berhasil dijual oleh {{ $selectedSales->nama }}</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#0A4DF3]">
                    {{ $breakdown->count() }} Model Motor
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500 font-bold border-b border-hairline">
                        <tr>
                            <th class="py-3 px-4 w-12 text-center">No.</th>
                            <th class="py-3 px-4">Model Sepeda Motor</th>
                            <th class="py-3 px-4">Varian Warna</th>
                            <th class="py-3 px-4 text-center">Jumlah Unit</th>
                            <th class="py-3 px-4 text-center">Kontribusi (%)</th>
                            <th class="py-3 px-4 text-right">Total Nilai (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($breakdown as $item)
                        @php
                            $kontribusi = $totalUnit > 0 ? round(($item['total_unit'] / $totalUnit) * 100, 1) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-4 text-center font-medium text-slate-400">
                                {{ $loop->iteration }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $item['model'] }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">
                                {{ $item['warna_list'] ?: 'Standard' }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-900 text-sm">
                                {{ $item['total_unit'] }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700">
                                    {{ $kontribusi }}%
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold text-slate-900 whitespace-nowrap">
                                Rp {{ number_format($item['total_harga'], 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <p class="font-medium text-xs">Belum ada transaksi penjualan pada periode ini.</p>
                                <p class="text-[11px] text-slate-400 mt-1">Silakan periksa periode bulan/tahun yang dipilih.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($breakdown->count() > 0)
                    <tfoot class="bg-slate-50/80 font-bold text-xs text-slate-900 border-t-2 border-slate-200">
                        <tr>
                            <td colspan="3" class="py-3 px-4 text-right uppercase tracking-wider text-[11px] text-slate-500">Total Keseluruhan:</td>
                            <td class="py-3 px-4 text-center text-emerald-600 text-sm font-extrabold">{{ $totalUnit }} Unit</td>
                            <td class="py-3 px-4 text-center text-slate-600">100%</td>
                            <td class="py-3 px-4 text-right text-slate-900 text-sm font-extrabold whitespace-nowrap">
                                Rp {{ number_format($totalNilai, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
