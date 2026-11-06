@extends('layouts.app')

@section('title', 'Kelola Modul: ' . $training->nama_training . ' — SIMAKIS Admin')
@section('page-title', 'Kelola Modul Training')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 pb-12">

    <!-- Breadcrumb -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.trainings.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-[#1E3A8A] transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Manajemen Training</span>
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('trainings.show', $training->id) }}" target="_blank" class="rounded-xl px-3 py-1.5 text-xs font-bold text-[#1E3A8A] bg-blue-50 border border-blue-200 hover:bg-blue-100 transition">
                Pratinjau Modul (Sales View) ↗
            </a>
        </div>
    </div>

    <!-- Section 1: Edit Training Info -->
    <div class="rounded-2xl bg-white p-6 border-2 border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-900">Informasi Dasar Modul</h3>
            <span class="rounded-lg px-2.5 py-0.5 text-xs font-bold border {{ $training->category_color_classes }}">
                {{ $training->category_label }}
            </span>
        </div>

        <form action="{{ route('admin.trainings.update', $training->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Modul Training</label>
                    <input type="text" name="nama_training" value="{{ old('nama_training', $training->nama_training) }}" required
                           class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                        <select name="kategori" class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                            <option value="product_knowledge" {{ $training->kategori === 'product_knowledge' ? 'selected' : '' }}>Product Knowledge</option>
                            <option value="sales_skill" {{ $training->kategori === 'sales_skill' ? 'selected' : '' }}>Sales Skill</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                        <select name="status" class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                            <option value="published" {{ $training->status === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ $training->status === 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Modul</label>
                <textarea name="deskripsi" rows="2" required class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">{{ old('deskripsi', $training->deskripsi) }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="rounded-xl px-5 py-2 text-xs font-bold text-white bg-[#1E3A8A] hover:bg-blue-900 shadow-sm transition">
                    Simpan Perubahan Info Modul
                </button>
            </div>
        </form>
    </div>

    <!-- Section 2: Manage Materials -->
    <div class="rounded-2xl bg-white p-6 border-2 border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Daftar Materi Modul ({{ $training->materials->count() }} Topik)</h3>
                <p class="text-[11px] text-slate-500">Materi yang akan dipelajari berurutan oleh Sales</p>
            </div>
            <button type="button" onclick="document.getElementById('modalAddMaterial').classList.remove('hidden')" class="btn-primary">
                + Tambah Materi
            </button>
        </div>

        <div class="space-y-3">
            @forelse($training->materials as $idx => $m)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 rounded-xl border-2 border-slate-200 bg-slate-50 hover:bg-white transition">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#1E3A8A] text-white font-bold text-[10px]">
                                {{ $idx + 1 }}
                            </span>
                            <h4 class="text-xs font-bold text-slate-900">{{ $m->judul_materi }}</h4>
                        </div>
                        <p class="text-[11px] text-slate-500 line-clamp-2 pl-7">{{ $m->isi_materi }}</p>
                    </div>

                    <div class="flex items-center justify-end gap-2 shrink-0">
                        <form action="{{ route('admin.trainings.materials.destroy', [$training->id, $m->id]) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg px-2.5 py-1 text-xs font-bold text-rose-600 bg-rose-50 border border-rose-200 hover:bg-rose-100">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-xs text-slate-500 font-medium">
                    Belum ada materi untuk modul ini. Klik tombol Tambah Materi di atas.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Section 3: Manage Quiz Questions -->
    <div class="rounded-2xl bg-white p-6 border-2 border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Soal Quiz Evaluasi ({{ $training->quizzes->count() }} Soal)</h3>
                <p class="text-[11px] text-slate-500">Pertanyaan pilihan ganda untuk menguji pemahaman materi sales</p>
            </div>
            <button type="button" onclick="document.getElementById('modalAddQuiz').classList.remove('hidden')" class="btn-success">
                + Tambah Soal Quiz
            </button>
        </div>

        <div class="space-y-3">
            @forelse($training->quizzes as $qIdx => $quiz)
                <div class="p-4 rounded-xl border-2 border-slate-200 bg-slate-50 space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-2">
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-md bg-emerald-600 text-white font-bold text-[10px]">
                                {{ $qIdx + 1 }}
                            </span>
                            <div class="font-bold text-xs text-slate-900">{{ $quiz->pertanyaan }}</div>
                        </div>

                        <form action="{{ route('admin.trainings.quizzes.destroy', [$training->id, $quiz->id]) }}" method="POST" onsubmit="return confirm('Hapus soal kuis ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg px-2 py-1 text-[11px] font-bold text-rose-600 bg-rose-50 border border-rose-200 hover:bg-rose-100">
                                Hapus
                            </button>
                        </form>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pl-7 text-[11px]">
                        @foreach($quiz->pilihan_jawaban as $opt)
                            <div class="p-2 rounded-lg border {{ $opt === $quiz->jawaban_benar ? 'bg-emerald-50 border-emerald-400 text-emerald-950 font-bold' : 'bg-white border-slate-200 text-slate-700' }}">
                                {{ $opt }} @if($opt === $quiz->jawaban_benar) <span class="text-emerald-700">(Jawaban Benar)</span> @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-xs text-slate-500 font-medium">
                    Belum ada soal kuis pada modul ini. Klik tombol Tambah Soal Quiz di atas.
                </div>
            @endforelse
        </div>
    </div>

    <!-- MODAL ADD MATERIAL -->
    <div id="modalAddMaterial" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 hidden">
        <div class="w-full max-w-2xl rounded-2xl bg-white p-6 shadow-2xl border-2 border-slate-200 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900">Tambah Materi Baru</h3>
                <button type="button" onclick="document.getElementById('modalAddMaterial').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
            </div>

            <form action="{{ route('admin.trainings.materials.store', $training->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Materi <span class="text-rose-500">*</span></label>
                    <input type="text" name="judul_materi" required placeholder="Contoh: Fitur YECVT & Riding Mode"
                           class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Isi Materi Lengkap <span class="text-rose-500">*</span></label>
                    <textarea name="isi_materi" rows="10" required placeholder="Tuliskan materi penjelasan, spesifikasi, dan poin penting untuk sales..."
                              class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('modalAddMaterial').classList.add('hidden')" class="rounded-xl px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                    <button type="submit" class="rounded-xl px-5 py-2 text-xs font-bold text-white bg-[#1E3A8A] hover:bg-blue-900">Simpan Materi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL ADD QUIZ -->
    <div id="modalAddQuiz" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 hidden">
        <div class="w-full max-w-xl rounded-2xl bg-white p-6 shadow-2xl border-2 border-slate-200 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900">Tambah Soal Kuis Pilihan Ganda</h3>
                <button type="button" onclick="document.getElementById('modalAddQuiz').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
            </div>

            <form action="{{ route('admin.trainings.quizzes.store', $training->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pertanyaan <span class="text-rose-500">*</span></label>
                    <textarea name="pertanyaan" rows="2" required placeholder="Tuliskan pertanyaan kuis..."
                              class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none"></textarea>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700">Pilihan Jawaban (A, B, C, D) <span class="text-rose-500">*</span></label>
                    <input type="text" name="pilihan_a" required placeholder="Pilihan A" class="w-full rounded-xl border-2 border-slate-300 p-2 text-xs font-medium">
                    <input type="text" name="pilihan_b" required placeholder="Pilihan B" class="w-full rounded-xl border-2 border-slate-300 p-2 text-xs font-medium">
                    <input type="text" name="pilihan_c" required placeholder="Pilihan C" class="w-full rounded-xl border-2 border-slate-300 p-2 text-xs font-medium">
                    <input type="text" name="pilihan_d" required placeholder="Pilihan D" class="w-full rounded-xl border-2 border-slate-300 p-2 text-xs font-medium">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kunci Jawaban Benar <span class="text-rose-500">*</span></label>
                        <select name="jawaban_benar" required class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-bold text-emerald-700 focus:border-[#1E3A8A] focus:outline-none">
                            <option value="A">Pilihan A</option>
                            <option value="B">Pilihan B</option>
                            <option value="C">Pilihan C</option>
                            <option value="D">Pilihan D</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Penjelasan Singkat (Opsional)</label>
                        <input type="text" name="penjelasan" placeholder="Alasan jawaban benar..." class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('modalAddQuiz').classList.add('hidden')" class="rounded-xl px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                    <button type="submit" class="rounded-xl px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700">Simpan Soal Quiz</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
