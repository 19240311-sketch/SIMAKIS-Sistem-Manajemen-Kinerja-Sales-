@extends('layouts.app')

@section('title', 'Edit Materi Pembelajaran: ' . $training->nama_training . ' — SIMAKIS Admin')
@section('page-title', 'Edit Materi Pembelajaran')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto pb-16">

    <!-- Header Breadcrumb -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.trainings.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-[#0A4DF3] transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Kelola Materi</span>
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('trainings.show', $training->id) }}" target="_blank" class="text-xs font-bold text-[#0A4DF3] hover:underline flex items-center gap-1">
                <span>Lihat di Sisi Sales</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>
    </div>

    <!-- Main Form Container -->
    <div class="rounded-3xl bg-white border-2 border-slate-200 shadow-md overflow-hidden">
        
        <!-- Form Header -->
        <div class="p-6 sm:p-8 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#0A4DF3] block mb-1">Perbarui Modul</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900">
                    EDIT MATERI PEMBELAJARAN
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Sesuaikan tautan video YouTube, passing grade, atau edit butir soal kuis evaluasi.
                </p>
            </div>
            
            <div class="shrink-0">
                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $training->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                    {{ $training->is_active ? 'Status: Aktif' : 'Status: Nonaktif' }}
                </span>
            </div>
        </div>

        @if($errors->any())
            <div class="m-6 sm:m-8 mb-0 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                <div class="font-bold flex items-center gap-1.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Terdapat beberapa kesalahan:</span>
                </div>
                <ul class="list-disc list-inside pl-1 text-[11px] text-rose-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.trainings.update', $training->id) }}" method="POST" id="formMateriEdit" class="p-6 sm:p-8 space-y-8">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="{{ $training->status }}">

            <!-- ============================================== -->
            <!-- 1. INFORMASI UTAMA MATERI                       -->
            <!-- ============================================== -->
            <div class="space-y-5">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-md bg-blue-100 text-[#0A4DF3] text-xs font-black flex items-center justify-center">1</span>
                    <span>Informasi Materi</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Judul Materi -->
                    <div class="md:col-span-2 space-y-1.5">
                        <label for="nama_training" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Judul Materi <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="nama_training"
                               id="nama_training"
                               required
                               value="{{ old('nama_training', $training->nama_training) }}"
                               class="w-full rounded-xl border-2 border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-900 focus:border-[#0A4DF3] focus:outline-none transition">
                    </div>

                    <!-- Kategori -->
                    <div class="space-y-1.5">
                        <label for="kategori" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Kategori <span class="text-rose-500">*</span>
                        </label>
                        <select name="kategori" id="kategori" required
                                class="w-full rounded-xl border-2 border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-900 focus:border-[#0A4DF3] focus:outline-none transition bg-white">
                            <option value="product_knowledge" {{ old('kategori', $training->kategori) === 'product_knowledge' ? 'selected' : '' }}>Product Knowledge</option>
                            <option value="sales_skill" {{ old('kategori', $training->kategori) === 'sales_skill' ? 'selected' : '' }}>Sales Skill</option>
                            <option value="product_update" {{ old('kategori', $training->kategori) === 'product_update' ? 'selected' : '' }}>Product Update</option>
                            <option value="training" {{ old('kategori', $training->kategori) === 'training' ? 'selected' : '' }}>Training</option>
                            <option value="lainnya" {{ old('kategori', $training->kategori) === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="space-y-1.5">
                        <label for="is_active" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Status <span class="text-rose-500">*</span>
                        </label>
                        <select name="is_active" id="is_active" required
                                class="w-full rounded-xl border-2 border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-900 focus:border-[#0A4DF3] focus:outline-none transition bg-white">
                            <option value="1" {{ old('is_active', $training->is_active ? '1' : '0') === '1' ? 'selected' : '' }}>Aktif (Tampil untuk Sales)</option>
                            <option value="0" {{ old('is_active', $training->is_active ? '1' : '0') === '0' ? 'selected' : '' }}>Nonaktif (Draft)</option>
                        </select>
                    </div>

                    <!-- Deskripsi Materi -->
                    <div class="md:col-span-2 space-y-1.5">
                        <label for="deskripsi" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Deskripsi Materi <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="deskripsi"
                                  id="deskripsi"
                                  rows="3"
                                  required
                                  class="w-full rounded-xl border-2 border-slate-200 p-4 text-sm font-medium text-slate-900 focus:border-[#0A4DF3] focus:outline-none transition leading-relaxed">{{ old('deskripsi', $training->deskripsi) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- 2. LINK VIDEO YOUTUBE & AUTO-DETECTION          -->
            <!-- ============================================== -->
            <div class="space-y-5 pt-6 border-t border-slate-100">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-md bg-blue-100 text-[#0A4DF3] text-xs font-black flex items-center justify-center">2</span>
                    <span>Link Video YouTube &amp; Durasi</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                    <!-- Input Link YouTube -->
                    <div class="md:col-span-8 space-y-1.5">
                        <label for="youtube_url" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Link Video YouTube <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="w-5 h-5 text-red-600 fill-current" viewBox="0 0 24 24">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                            </div>
                            <input type="text"
                                   name="youtube_url"
                                   id="youtube_url"
                                   required
                                   value="{{ old('youtube_url', $training->youtube_url) }}"
                                   oninput="detectYouTubeUrl(this.value)"
                                   class="w-full rounded-xl border-2 border-slate-200 pl-11 pr-4 py-2.5 text-sm font-semibold text-slate-900 focus:border-[#0A4DF3] focus:outline-none transition">
                        </div>
                        <p id="youtubeValidationMsg" class="text-[11px] font-semibold text-slate-500 mt-1">
                            Sistem otomatis membaca Video ID dari tautan YouTube.
                        </p>
                    </div>

                    <!-- Durasi Video -->
                    <div class="md:col-span-4 space-y-1.5">
                        <label for="durasi_video" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Durasi Video
                        </label>
                        <input type="text"
                               name="durasi_video"
                               id="durasi_video"
                               value="{{ old('durasi_video', $training->durasi_video ?: '05:00') }}"
                               class="w-full rounded-xl border-2 border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-900 focus:border-[#0A4DF3] focus:outline-none transition">
                    </div>

                    <!-- Video Preview Container -->
                    <div id="youtubePreviewContainer" class="{{ $training->youtube_video_id ? '' : 'hidden' }} md:col-span-12 rounded-2xl bg-slate-900 p-4 border border-slate-800">
                        <div class="flex items-center justify-between text-xs font-bold text-white mb-2">
                            <span class="flex items-center gap-1.5 text-emerald-400">
                                <span>✓ Video Terdeteksi</span>
                                <span id="detectedVideoId" class="text-slate-400 font-mono font-normal">({{ $training->youtube_video_id }})</span>
                            </span>
                            <span class="text-slate-400 text-[11px]">Preview YouTube Player</span>
                        </div>
                        <div class="relative w-full pb-[45%] max-w-xl mx-auto rounded-xl overflow-hidden bg-black">
                            <div id="previewIframe" class="absolute inset-0 w-full h-full"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- 3. PARAMETER KUIS & PASSING GRADE              -->
            <!-- ============================================== -->
            <div class="space-y-5 pt-6 border-t border-slate-100">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-md bg-blue-100 text-[#0A4DF3] text-xs font-black flex items-center justify-center">3</span>
                    <span>Parameter Kuis &amp; Passing Grade</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Target Jumlah Soal -->
                    <div class="space-y-1.5">
                        <label for="jumlah_soal_target" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Target Jumlah Soal
                        </label>
                        <select id="jumlah_soal_target" name="jumlah_soal"
                                class="w-full rounded-xl border-2 border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-900 focus:border-[#0A4DF3] focus:outline-none transition bg-white">
                            <option value="3" {{ old('jumlah_soal', $training->jumlah_soal) == 3 ? 'selected' : '' }}>3 Soal</option>
                            <option value="5" {{ old('jumlah_soal', $training->jumlah_soal) == 5 ? 'selected' : '' }}>5 Soal</option>
                            <option value="7" {{ old('jumlah_soal', $training->jumlah_soal) == 7 ? 'selected' : '' }}>7 Soal</option>
                            <option value="10" {{ old('jumlah_soal', $training->jumlah_soal) == 10 ? 'selected' : '' }}>10 Soal</option>
                        </select>
                    </div>

                    <!-- Passing Grade -->
                    <div class="space-y-1.5">
                        <label for="passing_grade" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Passing Grade (Standar Lulus) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number"
                                   name="passing_grade"
                                   id="passing_grade"
                                   required
                                   min="10"
                                   max="100"
                                   value="{{ old('passing_grade', $training->passing_grade ?: 80) }}"
                                   class="w-full rounded-xl border-2 border-slate-200 px-4 py-2.5 text-sm font-bold text-slate-900 focus:border-[#0A4DF3] focus:outline-none transition">
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-xs font-bold text-slate-400">
                                %
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- 4. SOAL KUIS & OPSI GENERATE SOAL AI          -->
            <!-- ============================================== -->
            <div id="sectionSoalKuis" class="space-y-5 pt-6 border-t border-slate-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                            <span class="w-5 h-5 rounded-md bg-blue-100 text-[#0A4DF3] text-xs font-black flex items-center justify-center">4</span>
                            <span>SOAL KUIS PEMAHAMAN</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Edit butir soal di bawah secara manual.
                        </p>
                    </div>
                </div>

                <!-- Container Butir-Butir Soal Kuis -->
                <div id="quizQuestionsContainer" class="space-y-6">
                    @foreach($training->quizzes as $idx => $quiz)
                        @php
                            $index = $idx + 1;
                            $choices = is_array($quiz->pilihan_jawaban) ? $quiz->pilihan_jawaban : json_decode($quiz->pilihan_jawaban, true) ?? [];
                            $optA = isset($choices[0]['text']) ? $choices[0]['text'] : ($choices[0] ?? '');
                            $optB = isset($choices[1]['text']) ? $choices[1]['text'] : ($choices[1] ?? '');
                            $optC = isset($choices[2]['text']) ? $choices[2]['text'] : ($choices[2] ?? '');
                            $optD = isset($choices[3]['text']) ? $choices[3]['text'] : ($choices[3] ?? '');
                            
                            $correctKey = 'A';
                            $correctId = trim((string)$quiz->jawaban_benar);
                            if ($correctId === (isset($choices[1]['id']) ? $choices[1]['id'] : $optB)) $correctKey = 'B';
                            elseif ($correctId === (isset($choices[2]['id']) ? $choices[2]['id'] : $optC)) $correctKey = 'C';
                            elseif ($correctId === (isset($choices[3]['id']) ? $choices[3]['id'] : $optD)) $correctKey = 'D';
                            elseif (in_array(strtoupper($correctId), ['A','B','C','D'])) {
                                $correctKey = strtoupper($correctId);
                            }
                        @endphp
                        <div class="question-item-card rounded-2xl p-5 border-2 border-slate-200 bg-white shadow-xs space-y-4 relative" id="qcard-{{ $index }}">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <span class="text-xs font-black text-slate-800 bg-slate-100 px-3 py-1 rounded-lg border border-slate-200">
                                    SOAL #{{ $index }}
                                </span>
                                <button type="button" onclick="removeQuestionCard('qcard-{{ $index }}')" class="text-xs font-bold text-rose-600 hover:text-rose-800 hover:bg-rose-50 px-2 py-1 rounded-lg transition">
                                    ✕ Hapus Soal
                                </button>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                    Pertanyaan <span class="text-rose-500">*</span>
                                </label>
                                <textarea name="quizzes[{{ $index }}][pertanyaan]" required rows="2" class="w-full rounded-xl border border-slate-200 p-3 text-xs font-semibold text-slate-900 focus:border-[#0A4DF3] focus:outline-none">{{ $quiz->pertanyaan }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                <div class="space-y-1">
                                    <label class="block text-[11px] font-bold text-slate-700">Pilihan A</label>
                                    <input type="text" name="quizzes[{{ $index }}][pilihan_a]" required value="{{ $optA }}" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-900 focus:border-[#0A4DF3] focus:outline-none">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-[11px] font-bold text-slate-700">Pilihan B</label>
                                    <input type="text" name="quizzes[{{ $index }}][pilihan_b]" required value="{{ $optB }}" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-900 focus:border-[#0A4DF3] focus:outline-none">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-[11px] font-bold text-slate-700">Pilihan C</label>
                                    <input type="text" name="quizzes[{{ $index }}][pilihan_c]" required value="{{ $optC }}" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-900 focus:border-[#0A4DF3] focus:outline-none">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-[11px] font-bold text-slate-700">Pilihan D</label>
                                    <input type="text" name="quizzes[{{ $index }}][pilihan_d]" required value="{{ $optD }}" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-900 focus:border-[#0A4DF3] focus:outline-none">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                                <div class="space-y-1">
                                    <label class="block text-[11px] font-bold text-emerald-700 uppercase">Jawaban Benar</label>
                                    <select name="quizzes[{{ $index }}][jawaban_benar]" required class="w-full rounded-lg border-2 border-emerald-300 bg-emerald-50 px-3 py-2 text-xs font-black text-emerald-900 focus:border-emerald-600 focus:outline-none">
                                        <option value="A" {{ $correctKey === 'A' ? 'selected' : '' }}>Pilihan A</option>
                                        <option value="B" {{ $correctKey === 'B' ? 'selected' : '' }}>Pilihan B</option>
                                        <option value="C" {{ $correctKey === 'C' ? 'selected' : '' }}>Pilihan C</option>
                                        <option value="D" {{ $correctKey === 'D' ? 'selected' : '' }}>Pilihan D</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-2 space-y-1">
                                    <label class="block text-[11px] font-bold text-slate-600 uppercase">Penjelasan / Pembahasan (Opsional)</label>
                                    <input type="text" name="quizzes[{{ $index }}][penjelasan]" value="{{ $quiz->penjelasan }}" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-normal text-slate-700 focus:border-[#0A4DF3] focus:outline-none">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Tombol Tambah Soal Manual -->
                <div class="pt-2">
                    <button type="button"
                            onclick="addNewEmptyQuestion()"
                            class="w-full py-3 rounded-2xl border-2 border-dashed border-slate-300 hover:border-[#0A4DF3] hover:bg-blue-50/40 text-slate-600 hover:text-[#0A4DF3] text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Tambah Butir Soal Kuis Baru</span>
                    </button>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- 5. TOMBOL AKSI SIMPAN / BATAL                  -->
            <!-- ============================================== -->
            <div class="pt-8 border-t-2 border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.trainings.index') }}"
                   class="px-6 py-3 rounded-xl border-2 border-slate-300 text-slate-700 font-bold text-sm hover:bg-slate-100 transition cursor-pointer">
                    Batal
                </a>

                <button type="submit"
                        class="px-8 py-3 rounded-xl bg-[#0A4DF3] hover:bg-blue-700 text-white font-extrabold text-sm transition shadow-md active:scale-95 cursor-pointer">
                    Simpan Perubahan Materi
                </button>
            </div>

        </form>

    </div>

</div>

<!-- JavaScript Engine untuk Form YouTube & AI Quiz Generation -->
<script>
    let questionCount = {{ $training->quizzes->count() }};

    function extractYouTubeId(url) {
        if (!url) return null;
        const regExp = /(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?|shorts)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i;
        const match = url.match(regExp);
        return (match && match[1]) ? match[1] : (url.length === 11 ? url : null);
    }

    // Load YouTube API
    let ytPlayer = null;
    let isApiReady = false;

    const tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    const firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    window.onYouTubeIframeAPIReady = function() {
        isApiReady = true;
        
        // Initialize existing video if any
        const initialUrl = document.getElementById('youtube_url').value;
        if(initialUrl) detectYouTubeUrl(initialUrl);
    };

    function detectYouTubeUrl(val) {
        const videoId = extractYouTubeId(val);
        const previewContainer = document.getElementById('youtubePreviewContainer');
        const msg = document.getElementById('youtubeValidationMsg');
        const detectedIdSpan = document.getElementById('detectedVideoId');

        if (videoId) {
            msg.innerHTML = '<span class="text-emerald-600 font-bold">✓ Link YouTube valid (ID: ' + videoId + ')</span>';
            detectedIdSpan.innerText = '(' + videoId + ')';
            previewContainer.classList.remove('hidden');

            if (isApiReady) {
                if (ytPlayer) {
                    ytPlayer.loadVideoById(videoId);
                } else {
                    ytPlayer = new YT.Player('previewIframe', {
                        videoId: videoId,
                        events: {
                            'onReady': onPlayerReady
                        }
                    });
                }
            }
        } else if (val.trim().length > 5) {
            msg.innerHTML = '<span class="text-rose-600 font-bold">⚠️ Link YouTube tidak valid.</span>';
            previewContainer.classList.add('hidden');
            if (ytPlayer) ytPlayer.stopVideo();
        } else {
            msg.innerHTML = 'Sistem otomatis membaca Video ID dari tautan YouTube.';
            previewContainer.classList.add('hidden');
            if (ytPlayer) ytPlayer.stopVideo();
        }
    }

    function onPlayerReady(event) {
        const duration = event.target.getDuration();
        if (duration > 0 && (!document.getElementById('durasi_video').value || document.getElementById('durasi_video').value === 'Otomatis')) {
            const m = Math.floor(duration / 60);
            const s = Math.floor(duration % 60);
            const formatted = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
            document.getElementById('durasi_video').value = formatted;
        }
    }

    function addNewEmptyQuestion(data = null) {
        questionCount++;
        const index = questionCount;
        const container = document.getElementById('quizQuestionsContainer');

        const q = data || {
            pertanyaan: '',
            pilihan_a: '',
            pilihan_b: '',
            pilihan_c: '',
            pilihan_d: '',
            jawaban_benar: 'A',
            penjelasan: ''
        };

        const cardHtml = `
            <div class="question-item-card rounded-2xl p-5 border-2 border-slate-200 bg-white shadow-xs space-y-4 relative" id="qcard-${index}">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <span class="text-xs font-black text-slate-800 bg-slate-100 px-3 py-1 rounded-lg border border-slate-200">
                        SOAL #${index}
                    </span>
                    <button type="button" onclick="removeQuestionCard('qcard-${index}')" class="text-xs font-bold text-rose-600 hover:text-rose-800 hover:bg-rose-50 px-2 py-1 rounded-lg transition">
                        ✕ Hapus Soal
                    </button>
                </div>

                <div class="space-y-1">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600">
                        Pertanyaan <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="quizzes[${index}][pertanyaan]" required rows="2" placeholder="Tuliskan butir pertanyaan..." class="w-full rounded-xl border border-slate-200 p-3 text-xs font-semibold text-slate-900 focus:border-[#0A4DF3] focus:outline-none">${q.pertanyaan}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-slate-700">Pilihan A</label>
                        <input type="text" name="quizzes[${index}][pilihan_a]" required value="${q.pilihan_a}" placeholder="Teks opsi A" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-900 focus:border-[#0A4DF3] focus:outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-slate-700">Pilihan B</label>
                        <input type="text" name="quizzes[${index}][pilihan_b]" required value="${q.pilihan_b}" placeholder="Teks opsi B" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-900 focus:border-[#0A4DF3] focus:outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-slate-700">Pilihan C</label>
                        <input type="text" name="quizzes[${index}][pilihan_c]" required value="${q.pilihan_c}" placeholder="Teks opsi C" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-900 focus:border-[#0A4DF3] focus:outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-slate-700">Pilihan D</label>
                        <input type="text" name="quizzes[${index}][pilihan_d]" required value="${q.pilihan_d}" placeholder="Teks opsi D" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-900 focus:border-[#0A4DF3] focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                    <div class="space-y-1">
                        <label class="block text-[11px] font-bold text-emerald-700 uppercase">Jawaban Benar</label>
                        <select name="quizzes[${index}][jawaban_benar]" required class="w-full rounded-lg border-2 border-emerald-300 bg-emerald-50 px-3 py-2 text-xs font-black text-emerald-900 focus:border-emerald-600 focus:outline-none">
                            <option value="A" ${q.jawaban_benar === 'A' ? 'selected' : ''}>Pilihan A</option>
                            <option value="B" ${q.jawaban_benar === 'B' ? 'selected' : ''}>Pilihan B</option>
                            <option value="C" ${q.jawaban_benar === 'C' ? 'selected' : ''}>Pilihan C</option>
                            <option value="D" ${q.jawaban_benar === 'D' ? 'selected' : ''}>Pilihan D</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2 space-y-1">
                        <label class="block text-[11px] font-bold text-slate-600 uppercase">Penjelasan / Pembahasan (Opsional)</label>
                        <input type="text" name="quizzes[${index}][penjelasan]" value="${q.penjelasan || ''}" placeholder="Alasan mengapa jawaban ini tepat..." class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-normal text-slate-700 focus:border-[#0A4DF3] focus:outline-none">
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', cardHtml);
    }

    function removeQuestionCard(cardId) {
        const card = document.getElementById(cardId);
        if (card) card.remove();
    }
</script>
@endsection
