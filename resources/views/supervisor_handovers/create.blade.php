@extends('layouts.app')

@section('title', 'Buat Berita Acara Serah Terima Jabatan — SIMAKIS Yamaha JG Purwakarta')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    {{-- ============================================================
         1. TOP HEADER
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <a href="{{ route('supervisor.handovers.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1 mb-2">
                    &larr; Kembali ke Daftar Serah Terima
                </a>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                    Penyusunan Berita Acara Serah Terima Jabatan
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Sistem akan secara otomatis menyertakan snapshot kinerja penjualan, target sales, prospek berjalan, follow-up, dan agenda rapat terkini ke dalam dokumen ini.
                </p>
            </div>
        </div>
    </div>

    {{-- ============================================================
         2. FORM PENYUSUNAN
         ============================================================ --}}
    <form method="POST" action="{{ route('supervisor.handovers.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6 space-y-5">
            <h2 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                <span class="h-6 w-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs">1</span>
                Informasi &amp; Pihak Terlibat
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Supervisor Lama --}}
                <div>
                    <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Supervisor Lama (Yang Menyerahkan) <span class="text-rose-500">*</span>
                    </label>
                    <select name="supervisor_lama_id" required
                            class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach($supervisors as $sup)
                            <option value="{{ $sup->id }}" {{ $activeSupervisor && $activeSupervisor->id == $sup->id ? 'selected' : '' }}>
                                {{ $sup->nama }} ({{ $sup->email }}) &bull; {{ $sup->role_label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Supervisor Baru --}}
                <div>
                    <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Supervisor Baru (Yang Menerima) <span class="text-rose-500">*</span>
                    </label>
                    <select name="supervisor_baru_id" required
                            class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Pilih Supervisor Baru --</option>
                        @foreach($supervisors as $sup)
                            <option value="{{ $sup->id }}" {{ old('supervisor_baru_id') == $sup->id ? 'selected' : '' }}>
                                {{ $sup->nama }} ({{ $sup->email }}) &bull; {{ $sup->role_label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal Serah Terima --}}
                <div>
                    <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Tanggal Serah Terima <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="tanggal_serah_terima" value="{{ old('tanggal_serah_terima', date('Y-m-d')) }}" required
                           class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                {{-- Alasan Pergantian --}}
                <div>
                    <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Alasan Serah Terima / Pergantian Jabatan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="alasan_pergantian" value="{{ old('alasan_pergantian') }}" required
                           placeholder="Contoh: Rotasi manajerial rutin, promosi jabatan, dsb."
                           class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            {{-- Catatan Supervisor Lama --}}
            <div>
                <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                    Catatan Penting dari Supervisor Lama
                </label>
                <textarea name="catatan_supervisor_lama" rows="3"
                          placeholder="Poin penting terkait nasabah prioritas, kendala operasional sales di lapangan, atau rekomendasi..."
                          class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('catatan_supervisor_lama') }}</textarea>
            </div>

            {{-- Catatan Super Admin --}}
            <div>
                <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                    Arahan &amp; Catatan Super Admin (Pimpinan)
                </label>
                <textarea name="catatan_super_admin" rows="3"
                          placeholder="Instruksi fokus kuartal, target khusus, arahan kedisiplinan tim sales..."
                          class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('catatan_super_admin') }}</textarea>
            </div>
        </div>

        {{-- ============================================================
             3. PREVIEW SNAPSHOT DATA OPERASIONAL OTOMATIS
             ============================================================ --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="h-6 w-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">2</span>
                    Snapshot Operasional Otomatis yang Akan Disimpan
                </h2>
                <span class="text-2xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                    Live Data Snapshot
                </span>
            </div>

            <p class="text-xs text-slate-500 leading-relaxed">
                Saat dokumen ini disimpan, data berikut akan dibekukan (freeze) sebagai bukti kondisi operasional saat momen serah terima berlangsung:
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-2xs font-bold text-slate-400 uppercase">Periode Kinerja</span>
                    <p class="text-sm font-bold text-slate-900 mt-0.5">{{ $snapshotPreview['ringkasan_penjualan']['periode'] }}</p>
                    <p class="text-2xs text-slate-500 mt-1">Realisasi: {{ $snapshotPreview['ringkasan_penjualan']['total_unit'] }} Unit Motor (Rp {{ number_format($snapshotPreview['ringkasan_penjualan']['total_omzet'], 0, ',', '.') }})</p>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-2xs font-bold text-slate-400 uppercase">Target Tim Sales</span>
                    <p class="text-sm font-bold text-slate-900 mt-0.5">{{ count($snapshotPreview['pencapaian_target']) }} Tenaga Sales</p>
                    <p class="text-2xs text-slate-500 mt-1">Rekap capaian target unit per sales terlampir</p>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-2xs font-bold text-slate-400 uppercase">Prospek &amp; Agenda Rapat</span>
                    <p class="text-sm font-bold text-slate-900 mt-0.5">{{ count($snapshotPreview['daftar_prospek_aktif']) }} Prospek Aktif</p>
                    <p class="text-2xs text-slate-500 mt-1">{{ count($snapshotPreview['daftar_followup_pending']) }} Follow-Up Pending &bull; {{ count($snapshotPreview['jadwal_rapat_mendatang']) }} Agenda Rapat</p>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('supervisor.handovers.index') }}"
               class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                Batal
            </a>
            <button type="submit"
                    style="background-color: #4f46e5 !important; color: #ffffff !important;"
                    class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition-all active:scale-95">
                Simpan &amp; Terbitkan Dokumen Serah Terima
            </button>
        </div>
    </form>

</div>
@endsection

