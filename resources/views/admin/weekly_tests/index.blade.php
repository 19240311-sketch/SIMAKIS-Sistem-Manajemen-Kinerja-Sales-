@extends('layouts.app')

@section('title', 'Manajemen Tes Online Mingguan — SIMAKIS Admin')
@section('page-title', 'Kelola Tes Online Mingguan')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-heading-md font-extrabold text-slate-900">Manajemen Tes Online Mingguan Sales</h2>
            <p class="text-xs text-slate-500">Buat jadwal tes berkala, susun pertanyaan pilihan ganda, dan pantau hasil evaluasi mingguan seluruh tim sales.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('weekly-tests.index') }}" class="btn-secondary" target="_blank">
                <svg class="h-4 w-4 text-[#1E3A8A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span>Lihat Tampilan Sales</span>
            </a>

            <button type="button" onclick="document.getElementById('modalCreateTest').classList.remove('hidden')" class="btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Buat Tes Mingguan Baru</span>
            </button>
        </div>
    </div>

    <!-- Tests List Table -->
    <div class="rounded-2xl bg-white border-2 border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[11px] tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">No</th>
                        <th class="py-3.5 px-4">Nama Tes &amp; Kategori</th>
                        <th class="py-3.5 px-4">Periode Pengerjaan</th>
                        <th class="py-3.5 px-4">Durasi &amp; Min</th>
                        <th class="py-3.5 px-4">Partisipasi Sales</th>
                        <th class="py-3.5 px-4 text-center">Rata-rata</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tests as $idx => $t)
                        <tr class="hover:bg-blue-50/40 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-500">{{ $idx + 1 }}</td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $t->nama_tes }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">{{ $t->kategori }} • {{ $t->questions->count() }} Soal</div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-700 font-medium">
                                {{ $t->tanggal_mulai->format('d M') }} — {{ $t->tanggal_selesai->format('d M Y, H:i') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-slate-900">{{ $t->durasi_menit }} Menit</div>
                                <div class="text-[11px] text-slate-500">Min: {{ $t->nilai_minimum }}/100</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">
                                    {{ $t->completed_count }} <span class="text-slate-500 font-normal">/ {{ $t->total_sales }} Sales</span>
                                </div>
                                <div class="text-[10px] text-slate-500 mt-0.5">
                                    <span class="text-emerald-600 font-bold">{{ $t->passed_count }} Lulus</span> •
                                    <span class="text-rose-600 font-bold">{{ $t->failed_count }} Gagal</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="font-black text-sm {{ $t->avg_score >= $t->nilai_minimum ? 'text-emerald-600' : 'text-slate-700' }}">
                                    {{ $t->avg_score }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $t->status_badge_classes }}">
                                    {{ $t->status_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.weekly-tests.manage', $t->id) }}"
                                       class="rounded-lg px-3 py-1.5 text-xs font-bold text-white bg-[#1E3A8A] hover:bg-blue-900 transition">
                                        Kelola Soal &amp; Hasil
                                    </a>

                                    <form id="delete-form-{{ $t->id }}" action="{{ route('admin.weekly-tests.destroy', $t->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="openDeleteModal('{{ $t->id }}', '{{ addslashes($t->nama_tes) }}')"
                                                class="rounded-lg px-2.5 py-1.5 text-xs font-bold text-rose-600 bg-rose-50 border border-rose-200 hover:bg-rose-100 transition cursor-pointer"
                                                title="Hapus Tes">
                                            ✕
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-500 font-medium">
                                Belum ada tes mingguan yang dibuat. Klik tombol Buat Tes Mingguan Baru di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL CREATE WEEKLY TEST -->
    <div id="modalCreateTest" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 hidden">
        <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border-2 border-slate-200 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900">Buat Tes Online Mingguan Baru</h3>
                <button type="button" onclick="document.getElementById('modalCreateTest').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
            </div>

            <form action="{{ route('admin.weekly-tests.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Tes Mingguan <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_tes" required placeholder="Contoh: Tes Mingguan 03 — Product Knowledge & Handling"
                           class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori / Topik Tes</label>
                    <input type="text" name="kategori" value="Product Knowledge & Sales Skill" required
                           class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" name="tanggal_mulai" required value="{{ now()->format('Y-m-d\TH:i') }}"
                               class="w-full rounded-xl border-2 border-slate-300 p-2 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Selesai <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" name="tanggal_selesai" required value="{{ now()->addDays(7)->format('Y-m-d\TH:i') }}"
                               class="w-full rounded-xl border-2 border-slate-300 p-2 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Durasi Tes (Menit) <span class="text-rose-500">*</span></label>
                        <input type="number" name="durasi_menit" value="15" min="1" max="120" required
                               class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nilai Minimum Lulus (0-100) <span class="text-rose-500">*</span></label>
                        <input type="number" name="nilai_minimum" value="70" min="1" max="100" required
                               class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Singkat</label>
                    <textarea name="deskripsi" rows="2" required placeholder="Jelaskan cakupan materi yang diujikan..."
                              class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Publikasi</label>
                    <select name="status" class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                        <option value="published">Published (Aktif sesuai jadwal)</option>
                        <option value="draft">Draft (Disembunyikan)</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('modalCreateTest').classList.add('hidden')" class="rounded-xl px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit" class="rounded-xl px-5 py-2 text-xs font-bold text-white bg-[#1E3A8A] hover:bg-blue-900">
                        Simpan &amp; Input Soal
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- MODAL KONFIRMASI HAPUS TES MINGGUAN -->
<div id="customDeleteModal" class="hidden" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
    <!-- Overlay hitam transparan sekitar 35%, tanpa efek blur -->
    <div id="deleteModalBackdrop" 
         onclick="closeDeleteModal()" 
         class="fixed inset-0 bg-black/35 z-[1000] transition-opacity duration-200 opacity-0"></div>

    <!-- Panel Modal: Posisi tepat di tengah layar horizontal & vertikal, rounded 18px -->
    <div id="deleteModalPanel" 
         class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[1001] w-[calc(100%-32px)] max-w-md bg-white rounded-[18px] shadow-2xl border border-slate-100 p-5 sm:p-6 transition-all duration-200 opacity-0 scale-95 flex flex-col pointer-events-auto"
         style="border-radius: 18px; max-height: calc(100vh - 40px);">
        
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
            <!-- Ikon Peringatan Merah -->
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 border border-rose-100 text-rose-600">
                <svg class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>

            <!-- Pesan dan Detail -->
            <div class="flex-1 min-w-0 text-center sm:text-left">
                <h3 class="text-base sm:text-lg font-bold text-slate-900" id="deleteModalTitle">
                    Hapus Tes Mingguan?
                </h3>
                
                <div class="mt-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-left">
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Tes yang akan dihapus:</span>
                    <span class="text-xs sm:text-sm font-bold text-slate-900 break-words mt-0.5 block" id="deleteTestNameModal">-</span>
                </div>

                <p class="mt-3 text-xs sm:text-[13px] text-slate-600 leading-relaxed text-left">
                    Apakah Anda yakin ingin menghapus tes ini beserta seluruh riwayat nilai dan hasil pengerjaan sales? Tindakan ini tidak dapat dibatalkan.
                </p>

                <!-- Box Notifikasi Error Jika Proses Gagal -->
                <div id="deleteModalError" class="hidden mt-3 p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 font-medium text-left"></div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-6 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2.5 pt-4 border-t border-slate-100">
            <button type="button" 
                    id="btnCancelDelete" 
                    onclick="closeDeleteModal()" 
                    class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm px-5 py-2.5 border border-slate-300 transition active:scale-95 cursor-pointer shadow-sm">
                Batalkan
            </button>
            <button type="button" 
                    id="btnConfirmDelete" 
                    onclick="executeDelete()" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs sm:text-sm px-5 py-2.5 shadow-sm transition active:scale-95 cursor-pointer">
                Ya, Hapus Tes
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentDeleteId = null;
    let isDeleting = false;

    const deleteModal = document.getElementById('customDeleteModal');
    const deleteBackdrop = document.getElementById('deleteModalBackdrop');
    const deletePanel = document.getElementById('deleteModalPanel');
    const deleteNameEl = document.getElementById('deleteTestNameModal');
    const deleteErrorEl = document.getElementById('deleteModalError');
    const btnConfirm = document.getElementById('btnConfirmDelete');
    const btnCancel = document.getElementById('btnCancelDelete');

    function openDeleteModal(id, testName) {
        if (isDeleting) return;
        currentDeleteId = id;
        deleteNameEl.textContent = testName;
        deleteErrorEl.classList.add('hidden');
        deleteErrorEl.textContent = '';

        // Reset button states
        btnConfirm.disabled = false;
        btnCancel.disabled = false;
        btnConfirm.classList.remove('opacity-75', 'cursor-not-allowed');
        btnConfirm.innerHTML = 'Ya, Hapus Tes';

        deleteModal.classList.remove('hidden');

        // Trigger smooth transition
        requestAnimationFrame(() => {
            deleteBackdrop.classList.remove('opacity-0');
            deleteBackdrop.classList.add('opacity-100');
            deletePanel.classList.remove('opacity-0', 'scale-95');
            deletePanel.classList.add('opacity-100', 'scale-100');
        });
    }

    function closeDeleteModal() {
        if (isDeleting) return;

        deleteBackdrop.classList.remove('opacity-100');
        deleteBackdrop.classList.add('opacity-0');
        deletePanel.classList.remove('opacity-100', 'scale-100');
        deletePanel.classList.add('opacity-0', 'scale-95');

        setTimeout(() => {
            deleteModal.classList.add('hidden');
            currentDeleteId = null;
        }, 200);
    }

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !deleteModal.classList.contains('hidden') && !isDeleting) {
            closeDeleteModal();
        }
    });

    function executeDelete() {
        if (!currentDeleteId || isDeleting) return;

        const form = document.getElementById('delete-form-' + currentDeleteId);
        if (!form) return;

        if (!navigator.onLine) {
            deleteErrorEl.textContent = 'Tidak ada sambungan internet. Silakan periksa koneksi Anda.';
            deleteErrorEl.classList.remove('hidden');
            return;
        }

        // Kunci proses untuk mencegah penghapusan ganda
        isDeleting = true;
        btnConfirm.disabled = true;
        btnCancel.disabled = true;
        btnConfirm.classList.add('opacity-75', 'cursor-not-allowed');

        // Tampilkan animasi loading
        btnConfirm.innerHTML = `
            <svg class="animate-spin -ml-0.5 mr-1.5 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Menghapus...</span>
        `;

        // Jalankan pengiriman form penghapusan
        try {
            form.submit();
        } catch (err) {
            deleteErrorEl.textContent = 'Terjadi kesalahan sistem saat memproses penghapusan.';
            deleteErrorEl.classList.remove('hidden');
            btnConfirm.disabled = false;
            btnCancel.disabled = false;
            btnConfirm.classList.remove('opacity-75', 'cursor-not-allowed');
            btnConfirm.innerHTML = 'Ya, Hapus Tes';
            isDeleting = false;
        }
    }
</script>
@endpush
@endsection
