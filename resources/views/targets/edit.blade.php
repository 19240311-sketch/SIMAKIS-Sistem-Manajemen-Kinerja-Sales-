@extends('layouts.app')

@section('title', 'Edit Target Penjualan')
@section('page-title', 'Edit Target')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">
    <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Edit Target Penjualan</h1>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui kuota penjualan untuk sales pada periode tertentu.</p>
            </div>
            <a href="{{ route('targets.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs shadow-2xs transition shrink-0">
                <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Kembali ke Target</span>
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('targets.update', $target) }}" class="bg-white rounded-2xl border border-hairline shadow-sm p-6 space-y-5">
        @csrf @method('PUT')

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-xs text-red-700 space-y-1">
                @foreach($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Sales <span class="text-red-500">*</span></label>
            <select name="user_id" required class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-[#0A4DF3] focus:outline-none">
                @foreach($salesList as $s)
                    <option value="{{ $s->id }}" {{ old('user_id', $target->user_id) == $s->id ? 'selected' : '' }}>{{ $s->nama }} ({{ $s->kode_sales ?? $s->email }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tipe Periode <span class="text-red-500">*</span></label>
            <select name="periode_tipe" id="periode_tipe" required onchange="togglePeriodeFields()" class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-[#0A4DF3] focus:outline-none">
                <option value="bulanan" {{ old('periode_tipe', $target->periode_tipe) == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                <option value="mingguan" {{ old('periode_tipe', $target->periode_tipe) == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                <option value="khusus" {{ old('periode_tipe', $target->periode_tipe) == 'khusus' ? 'selected' : '' }}>Khusus / Spesial</option>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tahun <span class="text-red-500">*</span></label>
                <select name="tahun" required class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-[#0A4DF3] focus:outline-none">
                    @foreach(range(date('Y') + 1, 2020) as $y)
                        <option value="{{ $y }}" {{ old('tahun', $target->tahun) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div id="field_bulan">
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Bulan</label>
                <select name="bulan" class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-[#0A4DF3] focus:outline-none">
                    <option value="">-- Pilih Bulan --</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ old('bulan', $target->bulan) == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(2026, $m, 1)->locale('id')->isoFormat('MMMM') }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>

        <div id="field_minggu" class="hidden">
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Minggu ke-</label>
            <select name="minggu_ke" class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-[#0A4DF3] focus:outline-none">
                <option value="">-- Pilih Minggu --</option>
                @for($w = 1; $w <= 5; $w++)
                    <option value="{{ $w }}" {{ old('minggu_ke', $target->minggu_ke) == $w ? 'selected' : '' }}>Minggu ke-{{ $w }}</option>
                @endfor
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $target->tanggal_mulai) }}" required
                       class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-[#0A4DF3] focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Selesai <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $target->tanggal_selesai) }}" required
                       class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-[#0A4DF3] focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Target Unit Penjualan <span class="text-red-500">*</span></label>
            <div class="relative">
                <input type="number" name="target_unit" value="{{ old('target_unit', $target->target_unit) }}" min="1" max="1000" required
                       class="w-full h-10 rounded-xl border border-slate-200 px-3 pr-12 text-xs focus:border-[#0A4DF3] focus:outline-none">
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-medium">unit</span>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan (opsional)</label>
            <textarea name="catatan" rows="3" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-xs focus:border-[#0A4DF3] focus:outline-none resize-none">{{ old('catatan', $target->catatan) }}</textarea>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('targets.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#0A4DF3] hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function togglePeriodeFields() {
    const tipe = document.getElementById('periode_tipe').value;
    document.getElementById('field_bulan').classList.toggle('hidden', tipe === 'mingguan');
    document.getElementById('field_minggu').classList.toggle('hidden', tipe !== 'mingguan');
}
togglePeriodeFields();
</script>
@endpush
@endsection
