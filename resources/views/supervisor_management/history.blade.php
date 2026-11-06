@extends('layouts.app')

@section('title', 'Riwayat Manajemen Supervisor — SIMAKIS Yamaha JG Purwakarta')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    {{-- ============================================================
         1. TOP HEADER & KONTROL PERGANTIAN
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                        Manajemen Kepemimpinan Sales
                    </span>
                    <span class="text-xs text-slate-400 font-medium">SIMAKIS &bull; Yamaha JG Purwakarta</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                    Riwayat Manajemen &amp; Masa Jabatan Supervisor
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Jejak historis penunjukan, masa tugas, dan pergantian jabatan Supervisor. Seluruh riwayat transaksi operasional masa lalu tetap tersimpan utuh dan tidak terhapus.
                </p>
            </div>

            @if(Auth::user()->isSuperAdmin())
            <div class="flex items-center gap-2.5">
                <button type="button"
                        onclick="openReplaceModal()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition-all active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
                    </svg>
                    <span>Lakukan Pergantian Supervisor</span>
                </button>
            </div>
            @endif
        </div>
    </div>

    {{-- ============================================================
         2. SUPERVISOR AKTIF SAAT INI (CARD HIGHLIGHT)
         ============================================================ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="md:col-span-2 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden"
             style="background-color: #0f172a !important; background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%) !important; border: 1px solid #1e3a8a !important;">
            <div class="absolute right-0 top-0 translate-x-12 -translate-y-8 w-60 h-60 rounded-full blur-2xl pointer-events-none" style="background-color: rgba(59, 130, 246, 0.25);"></div>
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    @php $activeSup = $currentSupervisors->first(); @endphp
                    @if($activeSup)
                        @if($activeSup->foto_url)
                            <img src="{{ $activeSup->foto_url }}" alt="{{ $activeSup->nama }}" class="h-16 w-16 rounded-2xl object-cover shadow-md" style="border: 3px solid rgba(255,255,255,0.4);">
                        @else
                            <div class="h-16 w-16 rounded-2xl font-black text-2xl flex items-center justify-center shadow-md shrink-0"
                                 style="background-color: #2563eb !important; color: #ffffff !important; border: 3px solid rgba(255,255,255,0.3) !important;">
                                {{ $activeSup->avatar_initials }}
                            </div>
                        @endif
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-2xs font-extrabold"
                                  style="background-color: #064e3b !important; color: #6ee7b7 !important; border: 1px solid #059669 !important;">
                                <span class="h-1.5 w-1.5 rounded-full" style="background-color: #10b981 !important;"></span>
                                Sedang Menjabat
                            </span>
                            <h2 class="text-xl font-black mt-1" style="color: #ffffff !important;">{{ $activeSup->nama }}</h2>
                            <p class="text-xs mt-0.5" style="color: #cbd5e1 !important;">{{ $activeSup->email }} &bull; Cabang {{ $activeSup->cabang ?? 'Yamaha JG Purwakarta' }}</p>
                        </div>
                    @else
                        <div class="py-4">
                            <p class="text-sm font-semibold" style="color: #cbd5e1 !important;">Belum ada Supervisor aktif yang terdaftar.</p>
                        </div>
                    @endif
                </div>

                @if(Auth::user()->isSuperAdmin() && $activeSup)
                <div class="shrink-0">
                    <button type="button"
                            onclick="openReplaceModal({{ $activeSup->id }})"
                            class="px-4 py-2.5 rounded-xl font-bold text-xs shadow-md transition-all active:scale-95 flex items-center gap-2"
                            style="background-color: #ffffff !important; color: #1e3a8a !important; border: 1px solid #ffffff !important;">
                        <span>Ganti Supervisor Ini</span>
                        <span aria-hidden="true">&rarr;</span>
                    </button>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 flex flex-col justify-between">
            <div>
                <span class="text-2xs font-bold text-slate-400 uppercase tracking-wider">Integritas Data Historis</span>
                <h3 class="text-sm font-extrabold text-slate-800 mt-1">Pemeliharaan Catatan Wewenang</h3>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                    Pergantian Supervisor tidak pernah menghapus catatan penjualan, prospek, follow-up, target, atau laporan yang dibuat pada periode terdahulu.
                </p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Total Masa Jabatan Tercatat:</span>
                <span class="font-extrabold text-slate-900">{{ $histories->total() }} Periode</span>
            </div>
        </div>
    </div>

    {{-- ============================================================
         3. TABEL RIWAYAT MANAJEMEN SUPERVISOR
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900">Kronologi &amp; Riwayat Masa Jabatan</h2>
            <span class="text-xs text-slate-400">Total {{ $histories->total() }} catatan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider text-2xs">
                        <th class="py-3 px-4 font-bold w-12 text-center">No.</th>
                        <th class="py-3 px-4 font-bold">Nama Supervisor</th>
                        <th class="py-3 px-4 font-bold">Periode Menjabat</th>
                        <th class="py-3 px-4 font-bold">Durasi Tugas</th>
                        <th class="py-3 px-4 font-bold">Status Jabatan</th>
                        <th class="py-3 px-4 font-bold">Alasan Pergantian</th>
                        <th class="py-3 px-4 font-bold">Ditetapkan Oleh</th>
                        <th class="py-3 px-4 font-bold">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($histories as $index => $history)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-4 text-center font-medium text-slate-400">
                                {{ $histories->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-700 font-extrabold text-xs flex items-center justify-center shrink-0">
                                        {{ $history->user?->avatar_initials ?? 'SP' }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $history->user?->nama ?? 'Pengguna Dihapus' }}</div>
                                        <div class="text-2xs text-slate-400">{{ $history->user?->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-semibold text-slate-800">
                                    {{ $history->tanggal_mulai?->translatedFormat('d M Y') ?? '-' }}
                                    <span class="text-slate-400 mx-1">&rarr;</span>
                                    @if($history->tanggal_selesai)
                                        {{ $history->tanggal_selesai?->translatedFormat('d M Y') }}
                                    @else
                                        <span class="text-emerald-600 font-bold">Sekarang</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-600">
                                @php
                                    $end = $history->tanggal_selesai ?? now();
                                    $start = $history->tanggal_mulai ?? now();
                                    $diffMonths = $start->diffInMonths($end);
                                    $diffDays = $start->diffInDays($end) % 30;
                                @endphp
                                <span class="font-medium">
                                    @if($diffMonths > 0)
                                        {{ $diffMonths }} bln {{ $diffDays > 0 ? $diffDays . ' hr' : '' }}
                                    @else
                                        {{ $start->diffInDays($end) }} hari
                                    @endif
                                </span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-2xs font-bold border {{ $history->status_badge['bg'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $history->status_badge['dot'] }}"></span>
                                    {{ $history->status_badge['label'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 max-w-xs">
                                <span class="font-medium text-slate-800">{{ $history->alasan_pergantian ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-600">
                                @if($history->superAdmin)
                                    <span class="font-semibold text-slate-800">{{ $history->superAdmin->nama }}</span>
                                    <span class="block text-2xs text-purple-600 font-bold">Super Admin</span>
                                @else
                                    <span class="text-slate-400">Sistem / Seeder</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 max-w-xs truncate" title="{{ $history->catatan }}">
                                {{ $history->catatan ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                Belum ada riwayat manajemen Supervisor yang tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($histories->hasPages())
            <div class="px-5 py-4 border-t border-slate-200">
                {{ $histories->links() }}
            </div>
        @endif
    </div>

</div>

{{-- ============================================================
     4. MODAL PERGANTIAN SUPERVISOR (SUPER ADMIN ONLY)
     ============================================================ --}}
@if(Auth::user()->isSuperAdmin())
<div id="replaceSupervisorModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Pergantian Jabatan Supervisor</h3>
                <p class="text-xs text-slate-400 mt-0.5">Penunjukan Supervisor baru dan transisi Supervisor lama</p>
            </div>
            <button type="button" onclick="closeReplaceModal()" class="text-slate-400 hover:text-slate-600 p-1">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('supervisor.replace') }}" class="space-y-4 mt-4 text-xs">
            @csrf

            {{-- 1. Supervisor Lama --}}
            <div>
                <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                    Supervisor Lama (Yang Digantikan) <span class="text-rose-500">*</span>
                </label>
                <select name="old_supervisor_id" id="replaceOldSupervisor" required
                        class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach($currentSupervisors as $sup)
                        <option value="{{ $sup->id }}">{{ $sup->nama }} ({{ $sup->email }})</option>
                    @endforeach
                </select>
            </div>

            {{-- 2. Supervisor Baru --}}
            <div>
                <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                    Pilih Pengguna untuk Ditunjuk Menjadi Supervisor Baru <span class="text-rose-500">*</span>
                </label>
                <select name="new_supervisor_id" id="replaceNewSupervisor" required
                        class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Calon Supervisor (Dari Tim Sales) --</option>
                    @foreach($salesCandidates as $candidate)
                        <option value="{{ $candidate->id }}">
                            {{ $candidate->nama }} (Kode: {{ $candidate->kode_sales ?? '-' }}) &bull; {{ $candidate->cabang ?? 'Purwakarta' }}
                        </option>
                    @endforeach
                </select>
                <p class="text-2xs text-slate-400 mt-1">Role pengguna terpilih akan secara otomatis dinaikkan menjadi Supervisor.</p>
            </div>

            {{-- 3. Tanggal Efektif Pergantian --}}
            <div>
                <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                    Tanggal Efektif Pergantian <span class="text-rose-500">*</span>
                </label>
                <input type="date" name="tanggal_efektif" value="{{ date('Y-m-d') }}" required
                       class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- 4. Status Akun Supervisor Lama --}}
            <div>
                <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                    Tindakan Terhadap Akun Supervisor Lama <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="border border-slate-200 rounded-xl p-3 flex items-start gap-2.5 cursor-pointer hover:bg-slate-50">
                        <input type="radio" name="status_lama" value="sales" checked class="mt-0.5 text-blue-600 focus:ring-blue-500">
                        <div>
                            <span class="font-bold text-slate-800 block text-xs">Jadikan Sales</span>
                            <span class="text-2xs text-slate-400 block mt-0.5">Role diubah ke Sales, akun tetap aktif beroperasi.</span>
                        </div>
                    </label>
                    <label class="border border-slate-200 rounded-xl p-3 flex items-start gap-2.5 cursor-pointer hover:bg-slate-50">
                        <input type="radio" name="status_lama" value="nonaktif" class="mt-0.5 text-blue-600 focus:ring-blue-500">
                        <div>
                            <span class="font-bold text-rose-700 block text-xs">Nonaktifkan</span>
                            <span class="text-2xs text-slate-400 block mt-0.5">Akun dinonaktifkan (tidak bisa login lagi).</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- 5. Alasan Pergantian --}}
            <div>
                <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                    Alasan / Penyebab Pergantian Jabatan <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="alasan_pergantian" required
                       placeholder="Contoh: Promosi jabatan, rotasi cabang, mengundurkan diri, dsb."
                       class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- 6. Catatan Tambahan --}}
            <div>
                <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                    Catatan Arahan Super Admin
                </label>
                <textarea name="catatan" rows="3"
                          placeholder="Instruksi serah terima pekerjaan, fokus target, atau catatan pimpinan..."
                          class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            {{-- Notice Keamanan & Integritas --}}
            <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 text-2xs leading-relaxed">
                <span class="font-bold">Penting:</span> Seluruh riwayat aktivitas lama (prospek, transaksi motor, penilaian sales) akan tetap tersimpan atas nama pembuat data aslinya. Pergantian ini hanya memperbarui wewenang manajerial terkini.
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeReplaceModal()"
                        class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition-colors">
                    Simpan &amp; Lantik Supervisor Baru
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReplaceModal(oldSupId = null) {
        if (oldSupId) {
            document.getElementById('replaceOldSupervisor').value = oldSupId;
        }
        document.getElementById('replaceSupervisorModal').classList.remove('hidden');
    }

    function closeReplaceModal() {
        document.getElementById('replaceSupervisorModal').classList.add('hidden');
    }
</script>
@endif
@endsection

