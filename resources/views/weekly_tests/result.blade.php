@extends('layouts.app')

@section('title', 'Hasil Tes: ' . $weeklyTest->nama_tes . ' — SIMAKIS')
@section('page-title', 'Hasil & Pembahasan Tes Mingguan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-16">

    <!-- Top Navigation Breadcrumb -->
    <div class="flex items-center justify-between">
        <a href="{{ route('weekly-tests.index') }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs transition-colors shadow-2xs">
            <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span>Daftar Tes Mingguan</span>
        </a>

        <span class="rounded-lg px-3 py-1 text-xs font-bold bg-blue-50 text-[#1E3A8A] border border-blue-200">
            {{ $weeklyTest->kategori }}
        </span>
    </div>

    <!-- Result Summary Banner Card -->
    @php
        $isPassed = $attempt->status_lulus;
        $bannerBg = $isPassed
            ? 'background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%);'
            : 'background: linear-gradient(135deg, #881337 0%, #9f1239 50%, #be123c 100%);';
        $bannerBorder = $isPassed ? 'border: 2px solid #10b981;' : 'border: 2px solid #f43f5e;';
    @endphp
    <div class="rounded-2xl p-6 sm:p-8 shadow-xl text-white" style="{{ $bannerBg }} {{ $bannerBorder }}">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 rounded-full px-3.5 py-1 text-xs font-bold"
                     style="{{ $isPassed ? 'background: rgba(16, 185, 129, 0.25); border: 1px solid #34d399; color: #a7f3d0;' : 'background: rgba(244, 63, 94, 0.25); border: 1px solid #fb7185; color: #fecdd3;' }}">
                    <span>{{ $isPassed ? '✓ LULUS EVALUASI' : '✕ BELUM LULUS EVALUASI' }}</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                    {{ $isPassed ? 'Selamat! Hasil Tes Anda Sangat Baik' : 'Tetap Semangat, Pelajari Kembali Materi' }}
                </h2>
                <p class="text-xs sm:text-sm max-w-xl leading-relaxed" style="color: {{ $isPassed ? '#d1fae5' : '#ffe4e6' }};">
                    {{ $weeklyTest->nama_tes }} • Batas nilai kelulusan minimal: <strong class="text-white">{{ $weeklyTest->nilai_minimum }}/100</strong>
                </p>
            </div>

            <!-- Score Big Box -->
            <div class="flex items-center gap-4 p-5 rounded-2xl shrink-0" style="background: rgba(0, 0, 0, 0.25); border: 1px solid rgba(255, 255, 255, 0.25);">
                <div class="text-center min-w-[90px]">
                    <div class="text-4xl sm:text-5xl font-black" style="color: {{ $isPassed ? '#6ee7b7' : '#fda4af' }};">
                        {{ $attempt->nilai }}
                    </div>
                    <div class="text-[11px] font-bold text-slate-200 mt-0.5">Skor Akhir / 100</div>
                </div>
            </div>
        </div>

        <!-- Breakdown Stats Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-6 mt-6" style="border-top: 1px solid rgba(255, 255, 255, 0.2);">
            <div class="rounded-xl p-3.5 text-center" style="background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.15);">
                <div class="text-xs font-medium text-slate-200">Jawaban Benar</div>
                <div class="text-lg font-black text-emerald-300 mt-0.5">{{ $attempt->jumlah_benar }} Soal</div>
            </div>
            <div class="rounded-xl p-3.5 text-center" style="background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.15);">
                <div class="text-xs font-medium text-slate-200">Jawaban Salah</div>
                <div class="text-lg font-black text-rose-300 mt-0.5">{{ $attempt->jumlah_salah }} Soal</div>
            </div>
            <div class="rounded-xl p-3.5 text-center" style="background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.15);">
                <div class="text-xs font-medium text-slate-200">Waktu Pengerjaan</div>
                <div class="text-lg font-black text-white mt-0.5">{{ $attempt->duration_spent }}</div>
            </div>
            <div class="rounded-xl p-3.5 text-center" style="background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.15);">
                <div class="text-xs font-medium text-slate-200">Waktu Kirim</div>
                <div class="text-xs font-bold text-white mt-1.5">{{ $attempt->waktu_selesai ? $attempt->waktu_selesai->format('d M Y, H:i') : '-' }}</div>
            </div>
        </div>
    </div>

    <!-- Questions Breakdown & Explanations -->
    <div class="space-y-4">
        <div class="border-b-2 border-slate-200 pb-3">
            <h3 class="text-base font-bold text-slate-900">
                Pembahasan &amp; Analisis Kunci Jawaban
            </h3>
            <p class="text-xs text-slate-500">Periksa evaluasi jawaban Anda untuk meningkatkan penguasaan materi selanjutnya.</p>
        </div>

        <div class="space-y-4">
            @foreach($weeklyTest->questions as $index => $question)
                @php
                    $ansRecord = $userAnswers->get($question->id);
                    $userAns = $ansRecord ? $ansRecord->jawaban_user : null;
                    $isCorrect = $ansRecord && $ansRecord->is_correct;
                @endphp
                <div class="rounded-2xl bg-white p-6 border-2 {{ $isCorrect ? 'border-emerald-200' : 'border-rose-200' }} shadow-sm space-y-4">
                    <!-- Question Title & Result Badge -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl {{ $isCorrect ? 'bg-emerald-600' : 'bg-rose-600' }} text-white font-bold text-xs shadow-sm">
                                {{ $index + 1 }}
                            </span>
                            <div class="pt-0.5">
                                <h4 class="text-sm font-bold text-slate-900 leading-snug">
                                    {{ $question->pertanyaan }}
                                </h4>
                            </div>
                        </div>

                        <span class="shrink-0 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $isCorrect ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ $isCorrect ? '✓ Benar' : '✕ Salah' }}
                        </span>
                    </div>

                    <!-- Choices Matrix -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pl-10 text-xs">
                        @foreach(['A' => $question->pilihan_a, 'B' => $question->pilihan_b, 'C' => $question->pilihan_c, 'D' => $question->pilihan_d] as $k => $text)
                            @php
                                $isThisCorrect = ($k === $question->jawaban_benar);
                                $isThisChosen = ($k === $userAns);
                            @endphp
                            <div class="flex items-center gap-2.5 p-3 rounded-xl border-2 {{ $isThisCorrect ? 'bg-emerald-50 border-emerald-500 text-emerald-950 font-bold' : ($isThisChosen ? 'bg-rose-50 border-rose-400 text-rose-950 font-semibold' : 'bg-slate-50 border-slate-200 text-slate-600') }}">
                                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-md font-bold text-[11px] {{ $isThisCorrect ? 'bg-emerald-600 text-white' : ($isThisChosen ? 'bg-rose-600 text-white' : 'bg-white border border-slate-300 text-slate-700') }}">
                                    {{ $k }}
                                </span>
                                <span class="leading-relaxed">{{ $text }}</span>
                                @if($isThisCorrect)
                                    <span class="ml-auto text-[10px] text-emerald-700 font-bold uppercase">(Kunci Benar)</span>
                                @elseif($isThisChosen)
                                    <span class="ml-auto text-[10px] text-rose-700 font-bold uppercase">(Pilihan Anda)</span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Explanation -->
                    @if(!empty($question->penjelasan))
                        <div class="ml-10 rounded-xl bg-blue-50/60 p-3.5 border border-blue-200 text-xs text-slate-700 space-y-1">
                            <div class="font-bold text-[#1E3A8A] flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                                </svg>
                                <span>Penjelasan Solusi:</span>
                            </div>
                            <p class="leading-relaxed text-slate-600">{{ $question->penjelasan }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Bottom Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-4">
        <a href="{{ route('weekly-tests.index') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-2.5 text-xs font-semibold text-slate-700 border border-slate-200 hover:bg-slate-50 transition shadow-2xs">
            <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span>Daftar Tes Mingguan</span>
        </a>

        <a href="{{ route('trainings.index') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#1E3A8A] px-6 py-3 text-xs font-bold text-white border-2 border-[#1E3A8A] shadow-md hover:bg-blue-900 transition">
            <span>Pelajari Materi Training Lainnya</span>
            &rarr;
        </a>
    </div>

</div>
@endsection
