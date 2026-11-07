@extends('layouts.app')

@section('title', 'Edit Rapat: ' . $meeting->judul_rapat)
@section('page-title', 'Edit Rapat')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">
    <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Edit Rapat</h1>
                <p class="text-xs text-slate-500 mt-0.5 truncate max-w-xs">{{ $meeting->judul_rapat }}</p>
            </div>
            <a href="{{ route('meetings.show', $meeting) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs shadow-2xs transition shrink-0">
                <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Kembali ke Detail</span>
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('meetings.update', $meeting) }}" class="bg-white rounded-2xl border border-hairline shadow-sm p-6 space-y-5">
        @csrf @method('PUT')

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-xs text-red-700 space-y-1">
                @foreach($errors->all() as $error)<p>• {{ $error }}</p>@endforeach
            </div>
        @endif

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul Rapat <span class="text-red-500">*</span></label>
            <input type="text" name="judul_rapat" value="{{ old('judul_rapat', $meeting->judul_rapat) }}" required
                   class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-violet-400 focus:outline-none">
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal" value="{{ old('tanggal', $meeting->tanggal) }}" required
                       class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-violet-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Waktu Mulai <span class="text-red-500">*</span></label>
                <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', \Carbon\Carbon::parse($meeting->waktu_mulai)->format('H:i')) }}" required
                       class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-violet-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Waktu Selesai <span class="text-red-500">*</span></label>
                <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', \Carbon\Carbon::parse($meeting->waktu_selesai)->format('H:i')) }}" required
                       class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-violet-400 focus:outline-none">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Metode <span class="text-red-500">*</span></label>
                <select name="metode" id="metode_edit" onchange="toggleFields()" required class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-violet-400 focus:outline-none">
                    <option value="online" {{ old('metode', $meeting->metode) == 'online' ? 'selected' : '' }}>Online</option>
                    <option value="offline" {{ old('metode', $meeting->metode) == 'offline' ? 'selected' : '' }}>Offline</option>
                    <option value="hybrid" {{ old('metode', $meeting->metode) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                <select name="status" required class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-violet-400 focus:outline-none">
                    <option value="dijadwalkan" {{ old('status', $meeting->status) == 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                    <option value="berlangsung" {{ old('status', $meeting->status) == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                    <option value="selesai" {{ old('status', $meeting->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ old('status', $meeting->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
        </div>

        <div id="fe_online" class="{{ !in_array(old('metode', $meeting->metode), ['online','hybrid']) ? 'hidden' : '' }}">
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Link Zoom / Meet</label>
            <input type="url" name="link_zoom" value="{{ old('link_zoom', $meeting->link_zoom) }}" placeholder="https://zoom.us/j/..."
                   class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-violet-400 focus:outline-none">
        </div>

        <div id="fe_offline" class="{{ old('metode', $meeting->metode) == 'online' ? 'hidden' : '' }} space-y-3">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Tempat</label>
                <input type="text" name="nama_tempat" value="{{ old('nama_tempat', $meeting->nama_tempat) }}"
                       class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-violet-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Lokasi</label>
                <textarea name="alamat_lokasi" rows="2" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-violet-400 focus:outline-none resize-none">{{ old('alamat_lokasi', $meeting->alamat_lokasi) }}</textarea>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Agenda Rapat <span class="text-red-500">*</span></label>
            <textarea name="agenda" rows="4" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-xs focus:border-violet-400 focus:outline-none resize-none">{{ old('agenda', $meeting->agenda) }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi Tambahan</label>
            <textarea name="deskripsi" rows="2" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-xs focus:border-violet-400 focus:outline-none resize-none">{{ old('deskripsi', $meeting->deskripsi) }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-2">Peserta Rapat <span class="text-red-500">*</span></label>
            <div class="space-y-2 max-h-48 overflow-y-auto border border-slate-200 rounded-xl p-3">
                @foreach($salesList as $s)
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="peserta_ids[]" value="{{ $s->id }}"
                               {{ in_array($s->id, old('peserta_ids', $selectedSalesIds)) ? 'checked' : '' }}
                               class="h-4 w-4 rounded accent-violet-600">
                        <div class="flex items-center gap-2 flex-1">
                            <div class="h-6 w-6 rounded-full bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center text-white text-[9px] font-bold shrink-0">
                                {{ strtoupper(substr($s->nama, 0, 2)) }}
                            </div>
                            <span class="text-xs text-slate-700 font-medium">{{ $s->nama }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan untuk Peserta</label>
            <textarea name="catatan_peserta" rows="2" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-xs focus:border-violet-400 focus:outline-none resize-none">{{ old('catatan_peserta', $meeting->catatan_peserta) }}</textarea>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('meetings.show', $meeting) }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold shadow-sm transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function toggleFields() {
    const val = document.getElementById('metode_edit').value;
    document.getElementById('fe_online').classList.toggle('hidden', !['online','hybrid'].includes(val));
    document.getElementById('fe_offline').classList.toggle('hidden', val === 'online');
}
</script>
@endpush
@endsection
