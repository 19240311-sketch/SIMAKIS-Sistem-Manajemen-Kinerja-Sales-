@extends('layouts.app')

@section('title', 'Laporan Kinerja Sales')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-800">
                    SIMAKIS
                </span>
                <span class="text-xs text-slate-400 font-medium">Evaluasi & Kinerja Holistik</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-1">
                Laporan Kinerja Sales
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Rekapitulasi komprehensif performa penjualan, reward, keaktifan temuan lapangan, dan kehadiran rapat mingguan.
            </p>
        </div>

        @if($selectedSales && $reportData)
        <div class="flex items-center gap-2">
            <a href="{{ route('reports.performance.word', ['sales_id' => $selectedSales->id, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span>Unduh Dokumen Word (.docx)</span>
            </a>
        </div>
        @endif
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <form method="GET" action="{{ route('reports.performance') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
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
                    <span>Tampilkan Evaluasi</span>
                </button>
            </div>
        </form>
    </div>

    @if(!$selectedSales || !$reportData)
        <!-- Empty State Prompt -->
        <div class="bg-white rounded-2xl border border-hairline shadow-sm p-12 text-center">
            <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-2xl mx-auto flex items-center justify-center mb-4">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">Silakan Pilih Sales Terlebih Dahulu</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
                Pilih salah satu personil sales untuk menampilkan laporan evaluasi kinerja secara holistik, meliputi capaian penjualan, kehadiran rapat mingguan, dan reward.
            </p>
        </div>
    @else
        <!-- Sales Profile Banner -->
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 rounded-2xl p-6 text-white shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-amber-400 text-slate-950 flex items-center justify-center text-2xl font-black shrink-0 shadow-lg">
                    {{ strtoupper(substr($selectedSales->nama, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl font-extrabold text-white">{{ $selectedSales->nama }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-400 text-slate-950">
                            Peringkat {{ $reportData['peringkat'] }}
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-300 mt-1">
                        <span>Kode Sales: <strong class="text-white">{{ $selectedSales->kode_sales ?? '-' }}</strong></span>
                        <span>•</span>
                        <span>Email: <strong class="text-white">{{ $selectedSales->email }}</strong></span>
                        @if($selectedSales->telepon)
                            <span>•</span>
                            <span>Telp: <strong class="text-white">{{ $selectedSales->telepon }}</strong></span>
                        @endif
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1.5">
                        Periode Evaluasi: <strong class="text-amber-300">{{ $namaBulanList[(int)$bulan] ?? '' }} {{ $tahun }}</strong>
                    </p>
                </div>
            </div>

            <!-- Overall Category Box -->
            <div class="bg-white/10 backdrop-blur rounded-2xl p-4 border border-white/10 flex items-center gap-4">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-300 block">Status Kinerja</span>
                    <span class="text-base font-black text-amber-400">{{ $reportData['status_kinerja'] }}</span>
                </div>
                <div class="h-8 w-px bg-white/20"></div>
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-300 block">Persentase</span>
                    <span class="text-base font-black text-white">{{ $reportData['persentase'] }}%</span>
                </div>
            </div>
        </div>

        <!-- 4 Key Performance Indicators -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 1. Realisasi Penjualan -->
            <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Penjualan Motor</span>
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-[#0A4DF3]">Target {{ $reportData['target_unit'] }} Unit</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $reportData['realisasi_unit'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">/ {{ $reportData['target_unit'] }} Unit</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3">
                    <div class="h-1.5 rounded-full {{ $reportData['persentase'] >= 100 ? 'bg-emerald-500' : ($reportData['persentase'] >= 75 ? 'bg-blue-500' : 'bg-amber-500') }}" style="width: {{ min(100, $reportData['persentase']) }}%"></div>
                </div>
                <span class="text-[10px] text-slate-400 mt-1 block">Capaian: {{ $reportData['persentase'] }}%</span>
            </div>

            <!-- 2. Program Reward 15 Unit -->
            <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Reward Penjualan</span>
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-800">Min. {{ $reportData['reward_target'] }} Unit</span>
                </div>
                <div class="text-base font-extrabold text-slate-900 truncate mt-1">
                    {{ $reportData['reward_status'] }}
                </div>
                <p class="text-[11px] text-slate-500 mt-2">
                    @if($reportData['realisasi_unit'] >= $reportData['reward_target'])
                        <span class="text-emerald-600 font-bold">✓ Memenuhi syarat bonus!</span>
                    @else
                        Kurang {{ $reportData['reward_target'] - $reportData['realisasi_unit'] }} unit menuju reward
                    @endif
                </p>
            </div>

            <!-- 3. Temuan Lapangan -->
            <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Temuan Lapangan</span>
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-teal-50 text-teal-700">Aktivitas</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-teal-700">{{ $reportData['total_findings'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">Laporan</span>
                </div>
                <p class="text-[11px] text-slate-500 mt-2">
                    <span class="font-bold text-emerald-600">{{ $reportData['resolved_findings'] }}</span> selesai ditindaklanjuti
                </p>
            </div>

            <!-- 4. Kehadiran Rapat Mingguan -->
            <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Rapat Mingguan</span>
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-violet-50 text-violet-700">Disiplin</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-violet-700">{{ $reportData['attendance_count'] }}</span>
                    <span class="text-xs font-semibold text-slate-500">/ {{ $reportData['total_meetings'] }} Rapat</span>
                </div>
                <p class="text-[11px] text-slate-500 mt-2">
                    Tingkat Kehadiran: <strong class="text-slate-900">{{ $reportData['total_meetings'] > 0 ? round(($reportData['attendance_count'] / $reportData['total_meetings']) * 100) : 100 }}%</strong>
                </p>
            </div>
        </div>

        <!-- Formal Evaluation Card -->
        <div class="bg-white rounded-2xl border border-hairline shadow-sm p-6">
            <div class="flex items-center justify-between border-b border-hairline pb-4 mb-5">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Catatan & Evaluasi Supervisor</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Penilaian kualitatif dan arahan pembinaan sales untuk bulan berjalan</p>
                </div>
                @if(auth()->user()->isSupervisor())
                <a href="{{ route('performance.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-xs font-semibold text-slate-700 transition">
                    <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                    <span>Kelola Evaluasi Kinerja</span>
                </a>
                @endif
            </div>

            @if($reportData['evaluation'])
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs">
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1.5">Catatan Evaluasi</span>
                        <p class="text-slate-800 leading-relaxed font-medium">
                            {{ $reportData['evaluation']->catatan_evaluasi ?: 'Belum ada catatan evaluasi khusus.' }}
                        </p>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1.5">Rekomendasi Supervisor</span>
                        <p class="text-slate-800 leading-relaxed font-medium">
                            {{ $reportData['evaluation']->rekomendasi ?: 'Tidak ada rekomendasi khusus.' }}
                        </p>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1.5">Rencana Tindak Lanjut</span>
                        <p class="text-slate-800 leading-relaxed font-medium">
                            {{ $reportData['evaluation']->rencana_tindak_lanjut ?: 'Lanjutkan rutinitas prospek dan follow-up konsumen.' }}
                        </p>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-hairline flex items-center justify-between text-xs text-slate-400">
                    <span>Evaluator: <strong class="text-slate-700">{{ $reportData['evaluation']->supervisor->nama ?? 'Supervisor Sales' }}</strong></span>
                    <span>Terakhir Diperbarui: {{ $reportData['evaluation']->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            @else
                <div class="bg-amber-50/60 border border-amber-200/60 rounded-xl p-5 text-center text-xs">
                    <p class="text-amber-900 font-semibold">Formulir evaluasi kualitatif belum diisi oleh Supervisor untuk periode ini.</p>
                    <p class="text-amber-700/80 mt-1">
                        Supervisor dapat mengisi formulir evaluasi melalui menu <a href="{{ route('performance.index') }}" class="underline font-bold hover:text-amber-950">Penilaian Kinerja</a> agar catatan resmi tampil pada dokumen Word yang diekspor.
                    </p>
                </div>
            @endif
        </div>

        <!-- Info Box: Ekspor Word -->
        <div class="bg-blue-50/70 border border-blue-200/60 rounded-2xl p-4 flex items-start gap-3">
            <svg class="h-5 w-5 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            <div class="text-xs text-blue-900 leading-relaxed">
                <span class="font-bold">Format Dokumen Word:</span>
                Dokumen Word yang dihasilkan memuat lembar penilaian resmi evaluasi kinerja sales per periode bulan, lengkap dengan indikator capaian penjualan, status reward, temuan lapangan, kehadiran rapat mingguan, serta kolom pengesahan Supervisor Sales Dealer Yamaha JG Purwakarta.
            </div>
        </div>
    @endif

</div>
@endsection
