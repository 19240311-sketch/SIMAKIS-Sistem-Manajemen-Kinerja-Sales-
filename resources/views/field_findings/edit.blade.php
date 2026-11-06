@extends('layouts.app')

@section('title', 'Edit Temuan Lapangan')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">
    <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('field-findings.show', $fieldFinding) }}" class="h-8 w-8 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Edit Temuan Lapangan</h1>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui catatan interaksi dan tindak lanjut temuan lapangan.</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('field-findings.update', $fieldFinding) }}" class="bg-white rounded-2xl border border-hairline shadow-sm p-6 space-y-5">
        @csrf @method('PUT')

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-xs text-red-700 space-y-1">
                @foreach($errors->all() as $error)<p>• {{ $error }}</p>@endforeach
            </div>
        @endif

        @if(auth()->user()->isSupervisor())
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Sales <span class="text-red-500">*</span></label>
                <select name="user_id" required class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-teal-400 focus:outline-none">
                    @foreach($salesList as $s)
                        <option value="{{ $s->id }}" {{ old('user_id', $fieldFinding->user_id) == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Temuan <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_temuan" value="{{ old('tanggal_temuan', $fieldFinding->tanggal_temuan) }}" required
                       class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-teal-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Minat <span class="text-red-500">*</span></label>
                <select name="status_minat" required class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-teal-400 focus:outline-none">
                    <option value="rendah" {{ old('status_minat', $fieldFinding->status_minat) == 'rendah' ? 'selected' : '' }}>Rendah</option>
                    <option value="sedang" {{ old('status_minat', $fieldFinding->status_minat) == 'sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="tinggi" {{ old('status_minat', $fieldFinding->status_minat) == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                </select>
            </div>
        </div>

        {{-- Ambil dari daftar prospek --}}
        @if($prospects->isNotEmpty())
        <div class="rounded-xl border border-teal-100 bg-teal-50/60 p-3.5 space-y-2">
            <p class="text-[11px] font-bold text-teal-700 flex items-center gap-1.5">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
                Ambil dari Daftar Prospek (Opsional)
            </p>
            <select id="prospect_picker" class="w-full h-10 rounded-xl border border-teal-200 bg-white px-3 text-xs focus:border-teal-400 focus:outline-none text-slate-700">
                <option value="">-- Pilih prospek untuk mengisi nama & kontak otomatis --</option>
                @foreach($prospects as $p)
                    <option value="{{ $p->id }}"
                            data-nama="{{ $p->nama_konsumen }}"
                            data-kontak="{{ $p->nomor_telepon }}">
                        {{ $p->nama_konsumen }}{{ $p->nomor_telepon ? ' · ' . $p->nomor_telepon : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Konsumen</label>
                <input type="text" name="nama_konsumen" id="nama_konsumen" value="{{ old('nama_konsumen', $fieldFinding->nama_konsumen) }}"
                       class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-teal-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kontak</label>
                <input type="text" name="kontak_konsumen" id="kontak_konsumen" value="{{ old('kontak_konsumen', $fieldFinding->kontak_konsumen) }}"
                       class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-teal-400 focus:outline-none">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Motor yang Diminati</label>
                <select name="sepeda_motor_id" class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-teal-400 focus:outline-none">
                    <option value="">-- Pilih Motor --</option>
                    @foreach($motorcycles as $m)
                        <option value="{{ $m->id }}" {{ old('sepeda_motor_id', $fieldFinding->sepeda_motor_id) == $m->id ? 'selected' : '' }}>{{ $m->nama_model }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Warna</label>
                <input type="text" name="warna_motor" value="{{ old('warna_motor', $fieldFinding->warna_motor) }}"
                       class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-teal-400 focus:outline-none">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Metode Pembayaran <span class="text-red-500">*</span></label>
                <select name="metode_pembayaran" required class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-teal-400 focus:outline-none">
                    <option value="belum_pasti" {{ old('metode_pembayaran', $fieldFinding->metode_pembayaran) == 'belum_pasti' ? 'selected' : '' }}>Belum Pasti</option>
                    <option value="cash" {{ old('metode_pembayaran', $fieldFinding->metode_pembayaran) == 'cash' ? 'selected' : '' }}>Tunai</option>
                    <option value="kredit" {{ old('metode_pembayaran', $fieldFinding->metode_pembayaran) == 'kredit' ? 'selected' : '' }}>Kredit</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kisaran Anggaran (Rp)</label>
                <input type="number" name="kisaran_anggaran" value="{{ old('kisaran_anggaran', $fieldFinding->kisaran_anggaran) }}" min="0"
                       class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-teal-400 focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan Komunikasi <span class="text-red-500">*</span></label>
            <textarea name="catatan_komunikasi" rows="4" required class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-xs focus:border-teal-400 focus:outline-none resize-none">{{ old('catatan_komunikasi', $fieldFinding->catatan_komunikasi) }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kendala</label>
            <textarea name="kendala" rows="3" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-xs focus:border-teal-400 focus:outline-none resize-none">{{ old('kendala', $fieldFinding->kendala) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Tindak Lanjut <span class="text-red-500">*</span></label>
                <select name="status_tindak_lanjut" required class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-teal-400 focus:outline-none">
                    <option value="menunggu" {{ old('status_tindak_lanjut', $fieldFinding->status_tindak_lanjut) == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="sedang_diproses" {{ old('status_tindak_lanjut', $fieldFinding->status_tindak_lanjut) == 'sedang_diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="selesai" {{ old('status_tindak_lanjut', $fieldFinding->status_tindak_lanjut) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ old('status_tindak_lanjut', $fieldFinding->status_tindak_lanjut) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Tindak Lanjut</label>
                <input type="date" name="tanggal_tindak_lanjut" value="{{ old('tanggal_tindak_lanjut', $fieldFinding->tanggal_tindak_lanjut) }}"
                       class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-teal-400 focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Rencana Tindak Lanjut</label>
            <textarea name="rencana_tindak_lanjut" rows="3" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-xs focus:border-teal-400 focus:outline-none resize-none">{{ old('rencana_tindak_lanjut', $fieldFinding->rencana_tindak_lanjut) }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan Hasil Tindak Lanjut</label>
            <textarea name="catatan_hasil_tindak_lanjut" rows="2" placeholder="Isi setelah tindak lanjut dilakukan..."
                      class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-xs focus:border-teal-400 focus:outline-none resize-none">{{ old('catatan_hasil_tindak_lanjut', $fieldFinding->catatan_hasil_tindak_lanjut) }}</textarea>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('field-findings.show', $fieldFinding) }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-sm transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const picker = document.getElementById('prospect_picker');
    if (picker) {
        picker.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt.value) {
                document.getElementById('nama_konsumen').value = opt.dataset.nama || '';
                document.getElementById('kontak_konsumen').value = opt.dataset.kontak || '';
            }
        });
    }
</script>
@endpush

@endsection
