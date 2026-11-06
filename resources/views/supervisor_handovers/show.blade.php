@extends('layouts.app')

@section('title', 'Dokumen Serah Terima Jabatan — ' . $handover->nomor_serah_terima)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    {{-- ============================================================
         1. TOP ACTION BAR
         ============================================================ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('supervisor.handovers.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1 mb-1">
                &larr; Kembali ke Daftar Serah Terima
            </a>
            <div class="flex items-center gap-3">
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                    Berita Acara Serah Terima Jabatan
                </h1>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $handover->status_badge['bg'] }}">
                    {{ $handover->status_badge['label'] }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Nomor Registrasi: <span class="font-mono font-bold text-slate-800">{{ $handover->nomor_serah_terima }}</span></p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button type="button" onclick="window.print()"
                    class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs border border-slate-200 shadow-xs transition-colors flex items-center gap-1.5">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.056-.367-2.155-.367-3.279 0-4.032 3.106-7.319 7.02-7.518M12 18v3m-3-3h6m-9-3h12a2 2 0 002-2V9a2 2 0 00-2-2H6a2 2 0 00-2 2v4a2 2 0 002 2z" />
                </svg>
                <span>Cetak Dokumen</span>
            </button>

            {{-- Status Update Button --}}
            @if(Auth::user()->isSuperAdmin() || Auth::id() === $handover->supervisor_baru_id || Auth::id() === $handover->supervisor_lama_id)
            <button type="button" onclick="openStatusModal()"
                    style="background-color: #4f46e5 !important; color: #ffffff !important;"
                    class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition-colors flex items-center gap-1.5">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>Perbarui Status Dokumen</span>
            </button>
            @endif
        </div>
    </div>

    {{-- ============================================================
         2. KERTAS KERJA RESMI (BERITA ACARA)
         ============================================================ --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-md p-6 sm:p-10 space-y-8 print:border-none print:shadow-none print:p-0">

        {{-- KOP SURAT --}}
        <div class="border-b-2 border-slate-900 pb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="h-3 w-3 bg-[#0A4DF3] rounded-full"></span>
                    <span class="text-base font-black text-slate-900 tracking-wider">YAMAHA JG PURWAKARTA</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 mt-1">
                    BERITA ACARA SERAH TERIMA JABATAN SUPERVISOR
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Sistem Manajemen Kinerja Sales (SIMAKIS) &bull; PT. JG Motor Purwakarta</p>
            </div>
            <div class="text-left sm:text-right text-xs text-slate-600">
                <div class="font-mono font-bold text-slate-900">{{ $handover->nomor_serah_terima }}</div>
                <div class="text-slate-500 mt-0.5">Tanggal: {{ $handover->tanggal_serah_terima?->translatedFormat('d F Y') }}</div>
                <div class="mt-1">
                    <span class="px-2.5 py-0.5 rounded-full text-2xs font-bold border {{ $handover->status_badge['bg'] }}">
                        Status: {{ $handover->status_badge['label'] }}
                    </span>
                </div>
            </div>
        </div>

        {{-- PARAF PIHAK-PIHAK --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50 p-5 rounded-2xl border border-slate-200 text-xs">
            <div>
                <span class="text-2xs font-bold text-slate-400 uppercase tracking-wider">Pihak Pertama (Supervisor Lama)</span>
                <p class="text-sm font-extrabold text-slate-900 mt-1">{{ $handover->supervisorLama?->nama ?? 'Supervisor Lama' }}</p>
                <p class="text-2xs text-slate-500">{{ $handover->supervisorLama?->email }}</p>
                <span class="inline-block mt-2 px-2 py-0.5 rounded text-2xs font-bold bg-slate-200 text-slate-700">Yang Menyerahkan</span>
            </div>

            <div>
                <span class="text-2xs font-bold text-slate-400 uppercase tracking-wider">Pihak Kedua (Supervisor Baru)</span>
                <p class="text-sm font-extrabold text-blue-600 mt-1">{{ $handover->supervisorBaru?->nama ?? 'Supervisor Baru' }}</p>
                <p class="text-2xs text-slate-500">{{ $handover->supervisorBaru?->email }}</p>
                <span class="inline-block mt-2 px-2 py-0.5 rounded text-2xs font-bold bg-blue-100 text-blue-700">Yang Menerima</span>
            </div>

            <div>
                <span class="text-2xs font-bold text-slate-400 uppercase tracking-wider">Mengetahui / Mengesahkan</span>
                <p class="text-sm font-extrabold text-purple-700 mt-1">{{ $handover->superAdmin?->nama ?? 'Super Admin' }}</p>
                <p class="text-2xs text-slate-500">Super Admin SIMAKIS</p>
                <span class="inline-block mt-2 px-2 py-0.5 rounded text-2xs font-bold bg-purple-100 text-purple-700">Pimpinan / Verifikator</span>
            </div>
        </div>

        {{-- ALASAN PERGANTIAN --}}
        <div class="space-y-2">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Latar Belakang &amp; Alasan Pergantian</h3>
            <p class="text-xs sm:text-sm text-slate-800 bg-slate-50 p-4 rounded-xl border border-slate-200 leading-relaxed font-medium">
                {{ $handover->alasan_pergantian }}
            </p>
        </div>

        {{-- ========================================================
             BAGIAN 1: RINGKASAN PENJUALAN DEALER
             ======================================================== --}}
        <div class="space-y-3">
            <div class="flex items-center gap-2 border-b border-slate-200 pb-2">
                <span class="h-6 w-6 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs">1</span>
                <h3 class="text-sm font-bold text-slate-900">Ringkasan Penjualan Dealer Saat Serah Terima</h3>
            </div>

            @php $salesSummary = $handover->ringkasan_penjualan ?? []; @endphp
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-2xs text-slate-400 font-bold uppercase">Periode Catatan</span>
                    <p class="font-extrabold text-slate-800 text-sm mt-0.5">{{ $salesSummary['periode'] ?? '-' }}</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-2xs text-slate-400 font-bold uppercase">Total Unit Terjual</span>
                    <p class="font-extrabold text-blue-600 text-sm mt-0.5">{{ $salesSummary['total_unit'] ?? 0 }} Unit</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-2xs text-slate-400 font-bold uppercase">Total Omzet Penjualan</span>
                    <p class="font-extrabold text-slate-900 text-sm mt-0.5">Rp {{ number_format($salesSummary['total_omzet'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-2xs text-slate-400 font-bold uppercase">Sales Aktif</span>
                    <p class="font-extrabold text-slate-800 text-sm mt-0.5">{{ $salesSummary['jumlah_sales_aktif'] ?? 0 }} Orang</p>
                </div>
            </div>
        </div>

        {{-- ========================================================
             BAGIAN 2: PENCAPAIAN TARGET TIM SALES
             ======================================================== --}}
        <div class="space-y-3">
            <div class="flex items-center gap-2 border-b border-slate-200 pb-2">
                <span class="h-6 w-6 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">2</span>
                <h3 class="text-sm font-bold text-slate-900">Pencapaian Target Masing-Masing Sales</h3>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider text-2xs border-b border-slate-200">
                            <th class="py-2.5 px-4 font-bold">Nama Sales</th>
                            <th class="py-2.5 px-4 font-bold text-center">Target (Unit)</th>
                            <th class="py-2.5 px-4 font-bold text-center">Realisasi (Unit)</th>
                            <th class="py-2.5 px-4 font-bold text-center">Persentase Capaian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($handover->pencapaian_target ?? [] as $t)
                            <tr>
                                <td class="py-2.5 px-4 font-semibold text-slate-800">{{ $t['sales_nama'] ?? '-' }}</td>
                                <td class="py-2.5 px-4 text-center font-mono text-slate-600">{{ $t['target_unit'] ?? 0 }}</td>
                                <td class="py-2.5 px-4 text-center font-mono font-bold text-slate-900">{{ $t['realisasi_unit'] ?? 0 }}</td>
                                <td class="py-2.5 px-4 text-center">
                                    <span class="font-bold {{ ($t['persentase'] ?? 0) >= 100 ? 'text-emerald-600' : 'text-amber-600' }}">
                                        {{ $t['persentase'] ?? 0 }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-slate-400">Tidak ada data target sales terlampir.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ========================================================
             BAGIAN 3: DAFTAR PROSPEK AKTIF
             ======================================================== --}}
        <div class="space-y-3">
            <div class="flex items-center gap-2 border-b border-slate-200 pb-2">
                <span class="h-6 w-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">3</span>
                <h3 class="text-sm font-bold text-slate-900">Daftar Prospek Konsumen yang Masih dalam Proses</h3>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider text-2xs border-b border-slate-200">
                            <th class="py-2.5 px-4 font-bold">Nama Konsumen</th>
                            <th class="py-2.5 px-4 font-bold">No. Kontak</th>
                            <th class="py-2.5 px-4 font-bold">Minat Motor</th>
                            <th class="py-2.5 px-4 font-bold">Sales Penanggung Jawab</th>
                            <th class="py-2.5 px-4 font-bold">Status Saat Ini</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($handover->daftar_prospek_aktif ?? [] as $p)
                            <tr>
                                <td class="py-2 px-4 font-bold text-slate-800">{{ $p['nama_konsumen'] ?? '-' }}</td>
                                <td class="py-2 px-4 font-mono text-slate-500">{{ $p['no_hp'] ?? '-' }}</td>
                                <td class="py-2 px-4 text-slate-700">{{ $p['motor'] ?? '-' }}</td>
                                <td class="py-2 px-4 font-semibold text-slate-800">{{ $p['sales'] ?? '-' }}</td>
                                <td class="py-2 px-4">
                                    <span class="px-2 py-0.5 rounded text-2xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $p['status'] ?? 'Proses' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-slate-400">Tidak ada prospek aktif terlampir.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ========================================================
             BAGIAN 4: FOLLOW-UP PENDING
             ======================================================== --}}
        <div class="space-y-3">
            <div class="flex items-center gap-2 border-b border-slate-200 pb-2">
                <span class="h-6 w-6 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-xs">4</span>
                <h3 class="text-sm font-bold text-slate-900">Jadwal Follow-Up yang Belum Selesai (Pending)</h3>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider text-2xs border-b border-slate-200">
                            <th class="py-2.5 px-4 font-bold">Calon Konsumen</th>
                            <th class="py-2.5 px-4 font-bold">Jenis Follow-Up</th>
                            <th class="py-2.5 px-4 font-bold">Jadwal</th>
                            <th class="py-2.5 px-4 font-bold">Sales Terkait</th>
                            <th class="py-2.5 px-4 font-bold">Catatan Singkat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($handover->daftar_followup_pending ?? [] as $f)
                            <tr>
                                <td class="py-2 px-4 font-bold text-slate-800">{{ $f['konsumen'] ?? '-' }}</td>
                                <td class="py-2 px-4 font-semibold text-slate-700">{{ $f['tipe'] ?? '-' }}</td>
                                <td class="py-2 px-4 text-slate-600">{{ $f['tanggal'] ?? '-' }}</td>
                                <td class="py-2 px-4 font-medium text-slate-800">{{ $f['sales'] ?? '-' }}</td>
                                <td class="py-2 px-4 text-slate-500 max-w-xs truncate">{{ $f['catatan'] ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-slate-400">Tidak ada jadwal follow-up pending.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ========================================================
             BAGIAN 5: JADWAL RAPAT MENDATANG
             ======================================================== --}}
        <div class="space-y-3">
            <div class="flex items-center gap-2 border-b border-slate-200 pb-2">
                <span class="h-6 w-6 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-xs">5</span>
                <h3 class="text-sm font-bold text-slate-900">Jadwal Rapat &amp; Agenda Tim Mendatang</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                @forelse($handover->jadwal_rapat_mendatang ?? [] as $m)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-start gap-3">
                        <div class="h-8 w-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-xs shrink-0">
                            RP
                        </div>
                        <div>
                            <div class="font-bold text-slate-900">{{ $m['judul'] ?? '-' }}</div>
                            <div class="text-2xs text-slate-500 mt-0.5">Tanggal: {{ $m['tanggal'] ?? '-' }} &bull; {{ $m['jam'] ?? '' }}</div>
                            <div class="text-2xs text-slate-600 mt-0.5">Tempat: {{ $m['tempat'] ?? '-' }} ({{ $m['tipe'] ?? 'Offline' }})</div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 py-4 text-center text-slate-400 text-xs">
                        Tidak ada agenda rapat terjadwal.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ========================================================
             BAGIAN 6 & 7: CATATAN SUPERVISOR LAMA & SUPER ADMIN
             ======================================================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
            <div class="space-y-2">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Catatan Khusus Supervisor Lama</h3>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs text-slate-800 leading-relaxed min-h-[100px] whitespace-pre-line">
                    {{ $handover->catatan_supervisor_lama ?: 'Tidak ada catatan khusus.' }}
                </div>
            </div>

            <div class="space-y-2">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Arahan &amp; Catatan Super Admin</h3>
                <div class="p-4 bg-indigo-50/60 rounded-2xl border border-indigo-200 text-xs text-slate-800 leading-relaxed min-h-[100px] whitespace-pre-line">
                    {{ $handover->catatan_super_admin ?: 'Tidak ada arahan tambahan.' }}
                </div>
            </div>
        </div>

        {{-- TANDA TANGAN PARA PIHAK --}}
        <div class="pt-8 border-t-2 border-slate-900 grid grid-cols-3 gap-6 text-center text-xs">
            <div>
                <p class="text-slate-500 text-2xs uppercase tracking-wider font-bold">Pihak Yang Menyerahkan</p>
                <div class="h-20 flex items-center justify-center font-signature text-slate-400 italic">
                    ( Tanda Tangan Digital )
                </div>
                <p class="font-extrabold text-slate-900 underline">{{ $handover->supervisorLama?->nama ?? 'Supervisor Lama' }}</p>
                <p class="text-2xs text-slate-500 mt-0.5">Supervisor Demisioner</p>
            </div>

            <div>
                <p class="text-slate-500 text-2xs uppercase tracking-wider font-bold">Pihak Yang Menerima</p>
                <div class="h-20 flex items-center justify-center font-signature text-blue-600 italic">
                    ( Tanda Tangan Digital )
                </div>
                <p class="font-extrabold text-blue-700 underline">{{ $handover->supervisorBaru?->nama ?? 'Supervisor Baru' }}</p>
                <p class="text-2xs text-slate-500 mt-0.5">Supervisor Terpilih</p>
            </div>

            <div>
                <p class="text-slate-500 text-2xs uppercase tracking-wider font-bold">Mengesahkan</p>
                <div class="h-20 flex items-center justify-center font-signature text-purple-700 italic">
                    ( Tanda Tangan Digital )
                </div>
                <p class="font-extrabold text-purple-900 underline">{{ $handover->superAdmin?->nama ?? 'Super Admin' }}</p>
                <p class="text-2xs text-slate-500 mt-0.5">Super Admin SIMAKIS</p>
            </div>
        </div>

    </div>

</div>

{{-- ============================================================
     3. MODAL PERBARUI STATUS DOKUMEN
     ============================================================ --}}
<div id="statusModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-base font-extrabold text-slate-900">Perbarui Status Dokumen</h3>
            <button type="button" onclick="closeStatusModal()" class="text-slate-400 hover:text-slate-600 p-1">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('supervisor.handovers.update-status', $handover->id) }}" class="space-y-4 mt-4 text-xs">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                    Pilih Status Baru <span class="text-rose-500">*</span>
                </label>
                <select name="status" required
                        class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="menunggu_serah_terima" {{ $handover->status === 'menunggu_serah_terima' ? 'selected' : '' }}>
                        Menunggu Serah Terima
                    </option>
                    <option value="dalam_proses" {{ $handover->status === 'dalam_proses' ? 'selected' : '' }}>
                        Dalam Proses
                    </option>
                    <option value="selesai" {{ $handover->status === 'selesai' ? 'selected' : '' }}>
                        Selesai (Resmi Disahkan)
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                    Catatan Perkembangan / Verifikasi (Opsional)
                </label>
                <textarea name="catatan_tambahan" rows="3"
                          placeholder="Tambahkan catatan hasil verifikasi berkas, tindak lanjut tim, atau konfirmasi..."
                          class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                <button type="button" onclick="closeStatusModal()"
                        class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                    Batal
                </button>
                <button type="submit"
                        style="background-color: #4f46e5 !important; color: #ffffff !important;"
                        class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openStatusModal() {
        document.getElementById('statusModal').classList.remove('hidden');
    }
    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
    }
</script>
@endsection

