@extends('layouts.app')

@section('title', 'Laporan Penjualan Motor')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-blue-100 text-[#0A4DF3]">
                    SIMAKIS
                </span>
                <span class="text-xs text-slate-400 font-medium">Laporan Rekapitulasi</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-1">
                Laporan Penjualan Motor
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Rekapitulasi penjualan dealer Yamaha JG Motor Purwakarta periode: <span class="font-bold text-slate-800">{{ $periodeText }}</span>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('reports.sales.word', request()->query()) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span>Unduh Dokumen Word (.docx)</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <form method="GET" action="{{ route('reports.sales') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 text-xs">
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

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Periode Minggu</label>
                <select name="minggu" class="w-full h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:border-[#0A4DF3] focus:outline-none">
                    <option value="">Semua Minggu (1 Bulan)</option>
                    <option value="1" {{ ($minggu ?? '') == '1' ? 'selected' : '' }}>Minggu 1 (Tgl 1 - 7)</option>
                    <option value="2" {{ ($minggu ?? '') == '2' ? 'selected' : '' }}>Minggu 2 (Tgl 8 - 14)</option>
                    <option value="3" {{ ($minggu ?? '') == '3' ? 'selected' : '' }}>Minggu 3 (Tgl 15 - 21)</option>
                    <option value="4" {{ ($minggu ?? '') == '4' ? 'selected' : '' }}>Minggu 4 (Tgl 22 - 28)</option>
                    <option value="5" {{ ($minggu ?? '') == '5' ? 'selected' : '' }}>Minggu 5 (Tgl 29 - Akhir)</option>
                </select>
            </div>

            @if(auth()->user()->isSupervisor())
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Sales Person</label>
                <select name="sales_id" class="w-full h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:border-[#0A4DF3] focus:outline-none">
                    <option value="">Semua Sales</option>
                    @foreach($salesList as $s)
                        <option value="{{ $s->id }}" {{ $salesId == $s->id ? 'selected' : '' }}>{{ $s->nama }} ({{ $s->kode_sales ?? 'N/A' }})</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Model Sepeda Motor</label>
                <select name="motorcycle_id" class="w-full h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:border-[#0A4DF3] focus:outline-none">
                    <option value="">Semua Model</option>
                    @foreach($motorcycles as $m)
                        <option value="{{ $m->id }}" {{ $motorcycleId == $m->id ? 'selected' : '' }}>{{ $m->nama_model }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Status Transaksi</label>
                <select name="status" class="w-full h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:border-[#0A4DF3] focus:outline-none">
                    <option value="valid" {{ $status === 'valid' ? 'selected' : '' }}>Hanya Valid</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="batal" {{ $status === 'batal' ? 'selected' : '' }}>Batal</option>
                    <option value="" {{ $status === '' ? 'selected' : '' }}>Semua Status</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full h-9 rounded-xl bg-[#0A4DF3] hover:bg-blue-700 text-white font-bold text-xs transition flex items-center justify-center gap-1.5 shadow-sm">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <span>Terapkan</span>
                </button>
                <a href="{{ route('reports.sales') }}" class="h-9 px-3 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold text-xs flex items-center justify-center transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Realisasi Terjual</span>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-emerald-600">{{ $totalUnit }}</span>
                <span class="text-xs font-semibold text-slate-500">Unit</span>
            </div>
            <p class="text-[10px] text-slate-400 mt-1">Status penjualan valid</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Target Periode</span>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-[#0A4DF3]">{{ $totalTarget }}</span>
                <span class="text-xs font-semibold text-slate-500">Unit</span>
            </div>
            <p class="text-[10px] text-slate-400 mt-1">Target terdaftar di sistem</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Capaian Target</span>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-extrabold {{ $persentase >= 100 ? 'text-emerald-600' : ($persentase >= 75 ? 'text-blue-600' : 'text-amber-600') }}">
                    {{ $persentase }}%
                </span>
            </div>
            <!-- Progress Bar -->
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2">
                <div class="h-1.5 rounded-full {{ $persentase >= 100 ? 'bg-emerald-500' : ($persentase >= 75 ? 'bg-blue-500' : 'bg-amber-500') }}" style="width: {{ min(100, $persentase) }}%"></div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Pendapatan</span>
            <div class="text-xl font-extrabold text-slate-900 truncate">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </div>
            <p class="text-[10px] text-slate-400 mt-1">Dari {{ $totalTransaksi }} transaksi tercatat</p>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-hairline shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-hairline flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <span>Rincian Transaksi Penjualan</span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600">
                    {{ $transactions->total() }} Data
                </span>
            </h2>
            <span class="text-xs text-slate-400">Diurutkan berdasarkan tanggal terbaru</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500 font-bold border-b border-hairline">
                    <tr>
                        <th class="py-3 px-4">No.</th>
                        <th class="py-3 px-4">No. Transaksi</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Nama Sales</th>
                        <th class="py-3 px-4">Konsumen</th>
                        <th class="py-3 px-4">Model & Warna Motor</th>
                        <th class="py-3 px-4 text-center">Unit</th>
                        <th class="py-3 px-4">Metode Bayar</th>
                        <th class="py-3 px-4 text-right">Total Nilai</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $index => $trx)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3.5 px-4 font-medium text-slate-400">
                            {{ $transactions->firstItem() + $index }}
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-slate-900 whitespace-nowrap">
                            {{ $trx->nomor_transaksi }}
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y') }}
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <span class="font-semibold text-slate-900">{{ $trx->sales->nama ?? '-' }}</span>
                            @if(isset($trx->sales->kode_sales))
                                <span class="text-[10px] text-slate-400 block">{{ $trx->sales->kode_sales }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-medium text-slate-800">{{ $trx->nama_konsumen }}</div>
                            <span class="text-[10px] text-slate-400">{{ $trx->telepon_konsumen ?? '-' }}</span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="font-bold text-slate-900">{{ $trx->motorcycle->nama_model ?? '-' }}</span>
                            @if($trx->nama_warna)
                                <span class="text-[10px] text-slate-500 block">Warna: {{ $trx->nama_warna }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center font-bold text-slate-900">
                            {{ $trx->jumlah_unit }}
                        </td>
                        <td class="py-3.5 px-4 uppercase text-[11px] font-semibold text-slate-600">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $trx->metode_pembayaran === 'cash' ? 'bg-emerald-50 text-emerald-700' : 'bg-indigo-50 text-indigo-700' }}">
                                {{ $trx->metode_pembayaran ?? 'CASH' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right font-bold text-slate-900 whitespace-nowrap">
                            Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if($trx->status_transaksi === 'valid')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    Valid
                                </span>
                            @elseif($trx->status_transaksi === 'pending')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                    Pending
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">
                                    Batal
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="py-12 text-center text-slate-400">
                            <svg class="h-10 w-10 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <p class="font-medium text-xs">Tidak ada data transaksi yang sesuai dengan filter.</p>
                            <p class="text-[11px] text-slate-400 mt-1">Coba sesuaikan bulan, tahun, atau filter lainnya.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div class="px-5 py-4 border-t border-hairline">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>

    <!-- Info Box: Format Dokumen Word -->
    <div class="bg-blue-50/70 border border-blue-200/60 rounded-2xl p-4 flex items-start gap-3">
        <svg class="h-5 w-5 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
        </svg>
        <div class="text-xs text-blue-900 leading-relaxed">
            <span class="font-bold">Informasi Ekspor Dokumen Word:</span>
            File yang diunduh diformat resmi sesuai standar dealer Yamaha JG Purwakarta, lengkap dengan kop surat SIMAKIS, tabel rekapitulasi data penjualan per item, dan kolom tanda tangan Supervisor Sales.
        </div>
    </div>

</div>
@endsection
