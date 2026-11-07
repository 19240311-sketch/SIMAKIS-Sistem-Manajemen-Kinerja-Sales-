@extends('layouts.app')

@section('title', 'Buat Program Reward')
@section('page-title', 'Buat Program Reward')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">
    <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Buat Program Reward</h1>
                <p class="text-xs text-slate-500 mt-0.5">Atur program apresiasi untuk memotivasi kinerja terbaik sales.</p>
            </div>
            <a href="{{ route('rewards.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs shadow-2xs transition shrink-0">
                <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Kembali ke Reward</span>
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('rewards.programs.store') }}" class="bg-white rounded-2xl border border-hairline shadow-sm p-6 space-y-5">
        @csrf

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-xs text-red-700 space-y-1">
                @foreach($errors->all() as $error)<p>• {{ $error }}</p>@endforeach
            </div>
        @endif

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Program <span class="text-red-500">*</span></label>
            <input type="text" name="nama_program" value="{{ old('nama_program') }}" required placeholder="Contoh: Reward Penjualan 15 Unit Bulanan"
                   class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-amber-400 focus:outline-none">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi Program</label>
            <textarea name="deskripsi" rows="3" placeholder="Jelaskan detail program reward ini..."
                      class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-xs focus:border-amber-400 focus:outline-none resize-none">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Periode Bulan (opsional)</label>
                <select name="periode_bulan" class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-amber-400 focus:outline-none">
                    <option value="">Berlaku Setiap Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ old('periode_bulan') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(2026, $m, 1)->locale('id')->isoFormat('MMMM') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Periode Tahun (opsional)</label>
                <select name="periode_tahun" class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-amber-400 focus:outline-none">
                    <option value="">Semua Tahun</option>
                    @foreach(range(date('Y') + 1, 2024) as $y)
                        <option value="{{ $y }}" {{ old('periode_tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Target Minimal Unit <span class="text-red-500">*</span></label>
            <div class="relative">
                <input type="number" name="target_minimal_unit" value="{{ old('target_minimal_unit', 15) }}" min="1" max="500" required
                       class="w-full h-10 rounded-xl border border-slate-200 px-3 pr-12 text-xs focus:border-amber-400 focus:outline-none">
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs">unit</span>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Jenis Hadiah <span class="text-red-500">*</span></label>
            <select name="jenis_hadiah" required class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-amber-400 focus:outline-none">
                <option value="bonus_insentif" {{ old('jenis_hadiah') == 'bonus_insentif' ? 'selected' : '' }}>Bonus / Insentif Tunai</option>
                <option value="voucher_belanja" {{ old('jenis_hadiah') == 'voucher_belanja' ? 'selected' : '' }}>Voucher Belanja</option>
                <option value="merchandise" {{ old('jenis_hadiah') == 'merchandise' ? 'selected' : '' }}>Merchandise / Barang</option>
                <option value="penghargaan_sales_terbaik" {{ old('jenis_hadiah') == 'penghargaan_sales_terbaik' ? 'selected' : '' }}>Penghargaan Sales Terbaik</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nilai / Nominal Hadiah <span class="text-red-500">*</span></label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-medium">Rp</span>
                <input type="number" name="nominal_hadiah" value="{{ old('nominal_hadiah', 1500000) }}" min="0" required
                       class="w-full h-10 rounded-xl border border-slate-200 pl-8 pr-3 text-xs focus:border-amber-400 focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Ketentuan & Syarat</label>
            <textarea name="ketentuan" rows="3" placeholder="Tuliskan syarat dan ketentuan berlakunya program reward ini..."
                      class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-xs focus:border-amber-400 focus:outline-none resize-none">{{ old('ketentuan') }}</textarea>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('rewards.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold shadow-sm transition">
                Simpan Program
            </button>
        </div>
    </form>
</div>
@endsection
