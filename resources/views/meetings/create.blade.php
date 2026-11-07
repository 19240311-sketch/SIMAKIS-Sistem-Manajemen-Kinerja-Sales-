@extends('layouts.app')

@section('title', 'Jadwalkan Rapat Mingguan')
@section('page-title', 'Jadwalkan Rapat')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">
    <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Jadwalkan Rapat Mingguan</h1>
                <p class="text-xs text-slate-500 mt-0.5">Atur jadwal, peserta, dan detail rapat evaluasi tim sales.</p>
            </div>
            <a href="{{ route('meetings.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs shadow-2xs transition shrink-0">
                <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Kembali ke Rapat</span>
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('meetings.store') }}" class="bg-white rounded-2xl border border-hairline shadow-sm p-6 space-y-5">
        @csrf

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-xs text-red-700 space-y-1">
                @foreach($errors->all() as $error)<p>• {{ $error }}</p>@endforeach
            </div>
        @endif

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul Rapat <span class="text-red-500">*</span></label>
            <input type="text" name="judul_rapat" value="{{ old('judul_rapat') }}" required placeholder="Contoh: Evaluasi Kinerja Mingguan Sales — Minggu ke-3 Oktober"
                   class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-violet-400 focus:outline-none {{ $errors->has('judul_rapat') ? 'border-red-400' : '' }}">
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal" value="{{ old('tanggal') }}" required
                       class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-violet-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Waktu Mulai <span class="text-red-500">*</span></label>
                <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', '09:00') }}" required
                       class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-violet-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Waktu Selesai <span class="text-red-500">*</span></label>
                <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', '11:00') }}" required
                       class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-violet-400 focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Metode Pelaksanaan <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-3 gap-3">
                @foreach(['online' => ['label' => 'Online', 'emoji' => '📹', 'color' => 'cyan'], 'offline' => ['label' => 'Offline', 'emoji' => '📍', 'color' => 'orange'], 'hybrid' => ['label' => 'Hybrid', 'emoji' => '🔀', 'color' => 'purple']] as $val => $meta)
                    <label class="flex flex-col items-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition {{ old('metode', 'offline') == $val ? 'border-violet-400 bg-violet-50' : 'border-slate-200 hover:border-violet-200' }}"
                           onclick="selectMetode('{{ $val }}', this)">
                        <input type="radio" name="metode" value="{{ $val }}" {{ old('metode', 'offline') == $val ? 'checked' : '' }} class="hidden">
                        <span class="text-2xl">{{ $meta['emoji'] }}</span>
                        <span class="text-xs font-bold text-slate-700">{{ $meta['label'] }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Online fields -->
        <div id="field_online" class="{{ old('metode', 'offline') == 'offline' ? 'hidden' : '' }}">
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Link Zoom / Meet</label>
            <input type="url" name="link_zoom" value="{{ old('link_zoom') }}" placeholder="https://zoom.us/j/..."
                   class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-violet-400 focus:outline-none">
        </div>

        <!-- Offline fields -->
        <div id="field_offline" class="{{ old('metode', 'offline') == 'online' ? 'hidden' : '' }} space-y-3">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Tempat</label>
                <input type="text" name="nama_tempat" value="{{ old('nama_tempat') }}" placeholder="Contoh: Ruang Rapat JG Motor"
                       class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-violet-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Lokasi</label>
                <textarea name="alamat_lokasi" rows="2" placeholder="Alamat lengkap lokasi rapat..."
                          class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-violet-400 focus:outline-none resize-none">{{ old('alamat_lokasi') }}</textarea>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Agenda Rapat <span class="text-red-500">*</span></label>
            <textarea name="agenda" rows="4" required placeholder="Tuliskan agenda dan poin-poin yang akan dibahas dalam rapat..."
                      class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-xs focus:border-violet-400 focus:outline-none resize-none {{ $errors->has('agenda') ? 'border-red-400' : '' }}">{{ old('agenda') }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi Tambahan</label>
            <textarea name="deskripsi" rows="2" placeholder="Informasi tambahan tentang rapat ini..."
                      class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-xs focus:border-violet-400 focus:outline-none resize-none">{{ old('deskripsi') }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-2">Peserta Rapat <span class="text-red-500">*</span></label>
            <div class="space-y-2 max-h-48 overflow-y-auto border border-slate-200 rounded-xl p-3">
                @foreach($salesList as $s)
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="peserta_ids[]" value="{{ $s->id }}"
                               {{ in_array($s->id, old('peserta_ids', [])) ? 'checked' : '' }}
                               class="h-4 w-4 rounded accent-violet-600">
                        <div class="flex items-center gap-2 flex-1">
                            <div class="h-6 w-6 rounded-full bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center text-white text-[9px] font-bold shrink-0">
                                {{ strtoupper(substr($s->nama, 0, 2)) }}
                            </div>
                            <span class="text-xs text-slate-700 group-hover:text-slate-900 font-medium">{{ $s->nama }}</span>
                            <span class="text-[10px] text-slate-400">{{ $s->kode_sales ?? '' }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('peserta_ids')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan untuk Peserta</label>
            <textarea name="catatan_peserta" rows="2" placeholder="Instruksi, persiapan, atau hal yang perlu dibawa peserta..."
                      class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-xs focus:border-violet-400 focus:outline-none resize-none">{{ old('catatan_peserta') }}</textarea>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('meetings.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold shadow-sm transition">
                Jadwalkan Rapat
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function selectMetode(val, el) {
    // Reset all
    el.closest('.grid').querySelectorAll('label').forEach(l => {
        l.classList.remove('border-violet-400', 'bg-violet-50');
        l.classList.add('border-slate-200');
    });
    el.classList.add('border-violet-400', 'bg-violet-50');
    el.classList.remove('border-slate-200');
    el.querySelector('input').checked = true;

    const online = document.getElementById('field_online');
    const offline = document.getElementById('field_offline');
    online.classList.toggle('hidden', val === 'offline');
    offline.classList.toggle('hidden', val === 'online');
}
</script>
@endpush
@endsection
