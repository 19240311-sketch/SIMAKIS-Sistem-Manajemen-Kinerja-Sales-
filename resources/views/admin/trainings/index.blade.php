@extends('layouts.app')

@section('title', 'Kelola Materi Pembelajaran — SIMAKIS Admin')
@section('page-title', 'Kelola Materi Pembelajaran')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                Kelola Materi Pembelajaran (Video &amp; Kuis)
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Atur video pembelajaran YouTube, kuis evaluasi otomatis dengan AI, passing grade, serta pantau progres belajar sales dealer Yamaha.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('trainings.index') }}" class="btn-secondary" target="_blank">
                <svg class="h-4 w-4 text-[#0A4DF3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span>Lihat Tampilan Sales</span>
            </a>

            <a href="{{ route('admin.trainings.create') }}" class="btn-primary whitespace-nowrap">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Materi</span>
            </a>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-500">Total Materi Video</div>
            <div class="text-2xl font-black text-[#0A4DF3] mt-1">{{ $totalTrainingsCount }}</div>
        </div>
        <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-500">Materi Aktif</div>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $trainings->where('is_active', true)->count() }}</div>
        </div>
        <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-500">Total Soal Kuis</div>
            <div class="text-2xl font-black text-slate-800 mt-1">{{ $totalQuizzesCount }}</div>
        </div>
        <div class="rounded-2xl bg-white p-4 border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-500">Sales Lulus Modul</div>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $totalCompletions }} <span class="text-xs text-slate-500 font-semibold">kali</span></div>
        </div>
    </div>

    <!-- Tab Switcher (Daftar Materi vs Laporan Sales) -->
    <div class="flex items-center gap-2 border-b-2 border-slate-200 pb-2">
        <a href="{{ route('admin.trainings.index', ['tab' => 'modules']) }}"
           class="rounded-xl px-5 py-2.5 text-xs font-bold transition {{ $activeTab === 'modules' ? 'bg-[#0A4DF3] text-white border-2 border-[#0A4DF3] shadow-sm' : 'bg-white text-slate-700 border-2 border-slate-300 hover:bg-slate-50' }}">
            Daftar Materi Pembelajaran ({{ $trainings->count() }})
        </a>
        <a href="{{ route('admin.trainings.index', ['tab' => 'sales_report']) }}"
           class="rounded-xl px-5 py-2.5 text-xs font-bold transition {{ $activeTab === 'sales_report' ? 'bg-[#0A4DF3] text-white border-2 border-[#0A4DF3] shadow-sm' : 'bg-white text-slate-700 border-2 border-slate-300 hover:bg-slate-50' }}">
            Laporan Progres Belajar Sales ({{ $salesProgressData->count() }})
        </a>
    </div>

    @if($activeTab === 'modules')
        <!-- TAB 1: DAFTAR MATERI PEMBELAJARAN (VIDEO + KUIS) -->
        <div class="rounded-2xl bg-white border-2 border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[11px] tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4 min-w-[280px]">Materi</th>
                            <th class="py-3.5 px-4">Kategori</th>
                            <th class="py-3.5 px-4">Video</th>
                            <th class="py-3.5 px-4">Soal</th>
                            <th class="py-3.5 px-4">Passing Grade</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right min-w-[260px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($trainings as $idx => $t)
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                    {{ $idx + 1 }}
                                </td>
                                
                                {{-- Materi (Thumbnail + Judul + Deskripsi) --}}
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-14 h-9 rounded-lg bg-slate-900 shrink-0 overflow-hidden relative border border-slate-200 shadow-xs">
                                            <img src="{{ $t->effective_thumbnail }}" alt="{{ $t->nama_training }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-sm leading-snug">{{ $t->nama_training }}</div>
                                            <div class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $t->deskripsi }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Kategori --}}
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="rounded-lg px-2.5 py-1 text-[11px] font-bold border {{ $t->category_color_classes }}">
                                        {{ $t->category_label }}
                                    </span>
                                </td>

                                {{-- Video Status & Durasi --}}
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5 font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 w-fit">
                                        <svg class="w-3.5 h-3.5 text-red-600 fill-current" viewBox="0 0 24 24">
                                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                        </svg>
                                        <span>✓ Video</span>
                                    </div>
                                    <div class="text-[10px] text-slate-500 font-mono mt-0.5">
                                        {{ $t->durasi_video ?: '05:00' }}
                                    </div>
                                </td>

                                {{-- Jumlah Soal --}}
                                <td class="py-3.5 px-4 font-bold text-slate-800 whitespace-nowrap">
                                    {{ $t->quizzes_count }} Soal
                                </td>

                                {{-- Passing Grade --}}
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="font-extrabold text-[#0A4DF3] text-sm">
                                        {{ $t->passing_grade ?: 80 }}%
                                    </span>
                                </td>

                                {{-- Status Aktif / Nonaktif --}}
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($t->is_active)
                                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Aktif</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            <span>Nonaktif</span>
                                        </span>
                                    @endif
                                </td>

                                {{-- Aksi Buttons --}}
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        {{-- Edit --}}
                                        <a href="{{ route('admin.trainings.edit', $t->id) }}"
                                           class="rounded-lg px-2.5 py-1 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 transition border border-blue-200"
                                           title="Edit Materi & Soal">
                                            Edit
                                        </a>

                                        {{-- Kelola Soal --}}
                                        <a href="{{ route('admin.trainings.edit', $t->id) }}#sectionSoalKuis"
                                           class="rounded-lg px-2.5 py-1 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition border border-slate-300"
                                           title="Kelola Soal Kuis">
                                            Kelola Soal
                                        </a>

                                        {{-- Lihat --}}
                                        <a href="{{ route('trainings.show', $t->id) }}"
                                           target="_blank"
                                           class="rounded-lg px-2.5 py-1 text-xs font-bold text-slate-600 hover:text-[#0A4DF3] hover:bg-slate-100 transition"
                                           title="Lihat Tampilan Video & Kuis">
                                            Lihat
                                        </a>

                                        {{-- Toggle Aktif / Nonaktif --}}
                                        <form action="{{ route('admin.trainings.toggle', $t->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="rounded-lg px-2 py-1 text-xs font-semibold {{ $t->is_active ? 'text-amber-700 hover:bg-amber-50' : 'text-emerald-700 hover:bg-emerald-50' }} transition cursor-pointer"
                                                    title="{{ $t->is_active ? 'Nonaktifkan Materi' : 'Aktifkan Materi' }}">
                                                {{ $t->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        {{-- Hapus --}}
                                        <form id="delete-form-{{ $t->id }}" action="{{ route('admin.trainings.destroy', $t->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="openDeleteModal('{{ $t->id }}', '{{ addslashes($t->nama_training) }}')"
                                                    class="rounded-lg px-2 py-1 text-xs font-bold text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                                    title="Hapus Materi">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-500">
                                    Belum ada materi pembelajaran yang dibuat. Silakan klik tombol "Tambah Materi" di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @else
        <!-- TAB 2: LAPORAN KEMAJUAN BELAJAR SALES -->
        <div class="rounded-2xl bg-white border-2 border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[11px] tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 w-12 text-center">No</th>
                            <th class="py-3.5 px-4">Nama Sales Advisor</th>
                            <th class="py-3.5 px-4">Email</th>
                            <th class="py-3.5 px-4">Lulus Modul</th>
                            <th class="py-3.5 px-4">Sedang Belajar</th>
                            <th class="py-3.5 px-4">Tingkat Kelulusan</th>
                            <th class="py-3.5 px-4">Rata-rata Nilai</th>
                            <th class="py-3.5 px-4">Kuis Terakhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($salesProgressData as $sIdx => $item)
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="py-3.5 px-4 text-center font-bold text-slate-400">{{ $sIdx + 1 }}</td>
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    {{ $item['user']->name }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-600">{{ $item['user']->email }}</td>
                                <td class="py-3.5 px-4 font-bold text-emerald-600">
                                    {{ $item['completed_count'] }} Modul
                                </td>
                                <td class="py-3.5 px-4 font-bold text-amber-600">
                                    {{ $item['in_progress_count'] }} Modul
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 bg-slate-100 h-2 rounded-full overflow-hidden border border-slate-200">
                                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $item['completion_rate'] }}%"></div>
                                        </div>
                                        <span class="font-bold text-slate-700">{{ $item['completion_rate'] }}%</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-black text-slate-800">
                                    {{ $item['avg_quiz_score'] }}%
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($item['latest_quiz'])
                                        <span class="font-semibold {{ $item['latest_quiz']->status_lulus ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $item['latest_quiz']->nilai }}% ({{ $item['latest_quiz']->status_lulus ? 'Lulus' : 'Belum Lulus' }})
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic">Belum ada tes</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-500">
                                    Belum ada data sales advisor terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>

@push('scripts')
<!-- Custom Tailwind Delete Confirmation Modal -->
<div id="customDeleteModal" class="hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0 z-[1000] flex items-center justify-center" id="deleteModalBackdrop"></div>

    <!-- Modal panel -->
    <div id="deleteModalPanel" 
         class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[1001] w-[calc(100%-32px)] max-w-lg transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all duration-300 opacity-0 scale-95 border border-slate-200 flex flex-col"
         style="max-height: calc(100vh - 40px);">
        
        <div class="bg-white px-5 py-6 sm:p-7 overflow-y-auto flex-1">
            <div class="sm:flex sm:items-start gap-5">
                <div class="mx-auto flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-rose-100 sm:mx-0 sm:h-12 sm:w-12 border border-rose-200">
                    <svg class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="mt-4 text-center sm:ml-2 sm:mt-0 sm:text-left w-full">
                    <h3 class="text-xl font-black leading-6 text-slate-900" id="modal-title">Hapus Materi?</h3>
                    <div class="mt-3 space-y-3">
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Apakah Anda yakin ingin menghapus modul <strong class="text-slate-900" id="deleteModalTrainingName"></strong>?
                        </p>
                        <p class="text-[13px] text-rose-700 font-medium bg-rose-50 px-4 py-3 rounded-xl border border-rose-100/60 leading-relaxed">
                            Peringatan: Seluruh data kuis, pertanyaan, dan riwayat belajar sales terkait modul ini akan terhapus secara permanen!
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-slate-50 px-5 py-4 sm:flex sm:flex-row-reverse sm:px-7 border-t border-slate-100 gap-3 shrink-0">
            <button type="button" id="btnConfirmDelete" class="inline-flex w-full sm:w-auto justify-center items-center rounded-xl bg-rose-600 px-6 py-2.5 text-sm font-bold text-white shadow-md hover:bg-rose-500 transition active:scale-95 cursor-pointer">
                Ya, Hapus Permanen
            </button>
            <button type="button" onclick="closeDeleteModal()" class="mt-3 sm:mt-0 inline-flex w-full sm:w-auto justify-center items-center rounded-xl bg-white px-6 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 hover:text-slate-900 transition active:scale-95 cursor-pointer">
                Batalkan
            </button>
        </div>
    </div>
</div>

<script>
    let currentDeleteId = null;
    const modal = document.getElementById('customDeleteModal');
    const backdrop = document.getElementById('deleteModalBackdrop');
    const panel = document.getElementById('deleteModalPanel');

    function openDeleteModal(id, name) {
        currentDeleteId = id;
        document.getElementById('deleteModalTrainingName').innerText = name;
        
        // Show container
        modal.classList.remove('hidden');
        
        // Trigger animations
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            
            panel.classList.remove('opacity-0', 'scale-95');
            panel.classList.add('opacity-100', 'scale-100');
        }, 10);
    }

    function closeDeleteModal() {
        currentDeleteId = null;
        
        // Reverse animations
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        
        panel.classList.remove('opacity-100', 'scale-100');
        panel.classList.add('opacity-0', 'scale-95');
        
        // Hide container after animation completes
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    document.getElementById('btnConfirmDelete').addEventListener('click', function() {
        if (currentDeleteId) {
            // Disable button and show loading state
            this.disabled = true;
            this.innerHTML = '<span class="flex items-center gap-2"><svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Menghapus...</span>';
            
            document.getElementById('delete-form-' + currentDeleteId).submit();
        }
    });
</script>
@endpush
@endsection
