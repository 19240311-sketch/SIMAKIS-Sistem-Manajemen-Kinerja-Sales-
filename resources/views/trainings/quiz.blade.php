@extends('layouts.app')

@section('title', 'Kuis Evaluasi: ' . $training->nama_training . ' — SIMAKIS')
@section('page-title', 'Kuis Pemahaman Sales')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto pb-16">

    <!-- Top Navigation Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <a href="{{ route('trainings.show', $training->id) }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs transition-colors shadow-2xs">
            <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span>Tonton Ulang Video</span>
        </a>

        <div class="flex items-center gap-2">
            <span class="rounded-lg px-3 py-1 text-xs font-bold border {{ $training->category_color_classes }}">
                {{ $training->category_label }}
            </span>
            <span class="rounded-lg px-3 py-1 text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 flex items-center gap-1.5">
                <svg class="h-3.5 w-3.5 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <circle cx="12" cy="12" r="9" stroke-width="2"/>
                    <circle cx="12" cy="12" r="5" stroke-width="2"/>
                    <circle cx="12" cy="12" r="1.5" fill="currentColor"/>
                </svg>
                <span>Passing Grade: {{ $training->passing_grade ?: 80 }}%</span>
            </span>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- MODAL / ALERT HASIL KUIS JIKA BARU SAJA DIKUMPULKAN     --}}
    {{-- ======================================================== --}}
    @if(session('quiz_passed'))
        <div class="rounded-3xl p-6 sm:p-8 bg-gradient-to-br from-emerald-600 to-teal-700 text-white shadow-xl relative overflow-hidden animate-fadeIn">
            <div class="relative z-10 flex flex-col sm:flex-row items-center gap-6 text-center sm:text-left">
                <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center shrink-0 shadow-inner">
                    <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="space-y-1.5 flex-1">
                    <span class="inline-block px-3 py-0.5 rounded-full bg-white/20 text-xs font-bold uppercase tracking-wider text-emerald-100">
                        Evaluasi Selesai
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black">
                        SELAMAT! Anda Dinyatakan LULUS
                    </h2>
                    <p class="text-emerald-100 text-sm leading-relaxed max-w-xl">
                        Pemahaman Anda terhadap produk sepeda motor Yamaha ini telah teruji dengan baik. Status materi telah diperbarui menjadi <strong>Lulus ✓</strong>.
                    </p>
                    <div class="pt-2 flex flex-wrap items-center gap-4 text-xs">
                        <span class="bg-white text-emerald-800 font-extrabold px-3.5 py-1.5 rounded-xl shadow-sm text-sm">
                            Nilai: {{ session('score') }}%
                        </span>
                        <span class="text-emerald-100">
                            Benar: <strong>{{ session('correct_count') }}</strong> dari {{ session('total_questions') }} Soal
                        </span>
                        <span class="text-emerald-200">
                            Passing Grade: {{ session('passing_grade') }}%
                        </span>
                    </div>
                </div>
                <div class="shrink-0 pt-2 sm:pt-0">
                    <a href="{{ route('trainings.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3.5 text-sm font-extrabold text-emerald-800 shadow-md hover:bg-emerald-50 transition active:scale-95 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Selesai &amp; Kembali ke Katalog</span>
                    </a>
                </div>
            </div>
        </div>
    @elseif(session('quiz_failed'))
        <div class="rounded-3xl p-6 sm:p-8 bg-gradient-to-br from-amber-500 to-rose-600 text-white shadow-xl relative overflow-hidden animate-fadeIn">
            <div class="relative z-10 flex flex-col sm:flex-row items-center gap-6 text-center sm:text-left">
                <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center shrink-0 shadow-inner">
                    <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <div class="space-y-1.5 flex-1">
                    <span class="inline-block px-3 py-0.5 rounded-full bg-white/20 text-xs font-bold uppercase tracking-wider text-amber-100">
                        Hasil Evaluasi
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black">
                        Anda Belum Lulus
                    </h2>
                    <p class="text-amber-100 text-sm leading-relaxed max-w-xl">
                        Nilai Anda belum mencapai passing grade <strong>{{ session('passing_grade') }}%</strong>. Jangan berkecil hati, Anda dapat mengulang kuis kembali untuk menyelesaikan modul ini.
                    </p>
                    <div class="pt-2 flex flex-wrap items-center gap-4 text-xs">
                        <span class="bg-white text-rose-700 font-extrabold px-3.5 py-1.5 rounded-xl shadow-sm text-sm">
                            Nilai Anda: {{ session('score') }}%
                        </span>
                        <span class="text-amber-100">
                            Benar: <strong>{{ session('correct_count') }}</strong> dari {{ session('total_questions') }} Soal
                        </span>
                        <span class="text-amber-200">
                            Target Lulus: {{ session('passing_grade') }}%
                        </span>
                    </div>
                </div>
                <div class="shrink-0 pt-2 sm:pt-0">
                    <a href="#quizFormSection"
                       onclick="document.getElementById('quizFormSection').scrollIntoView({behavior: 'smooth'})"
                       class="inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3.5 text-sm font-extrabold text-slate-900 shadow-md hover:bg-slate-50 transition active:scale-95 cursor-pointer">
                        <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Ulangi Kuis Sekarang</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Header Banner: Video Selesai! Uji Pemahaman --}}
    <div class="rounded-2xl p-6 sm:p-7 bg-white border-2 border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-5">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                <span>✓ Video Selesai 100%</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900">
                Video selesai! Sekarang uji pemahaman Anda.
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 max-w-xl">
                Jawablah pertanyaan pilihan ganda berikut untuk memastikan Anda menguasai selling point produk <strong>{{ $training->nama_training }}</strong>.
            </p>
        </div>

        <div class="rounded-xl bg-slate-50 p-4 border border-slate-200 text-center shrink-0 min-w-[140px]">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Passing Grade</span>
            <span class="text-2xl font-black text-[#0A4DF3]">{{ $training->passing_grade ?: 80 }}%</span>
            <span class="text-[10px] text-slate-500 block">Total: {{ $training->quizzes->count() }} Soal</span>
        </div>
    </div>

    <!-- Interactive Stepper Quiz Form Section -->
    <div id="quizFormSection" class="rounded-3xl bg-white p-6 sm:p-8 border-2 border-slate-200 shadow-md space-y-6">
        
        @php
            $quizzes = $training->quizzes;
            $totalQ = $quizzes->count();
        @endphp

        <!-- Quiz Progress Bar & Question Counter -->
        <div class="space-y-2 border-b border-slate-100 pb-5">
            <div class="flex items-center justify-between text-xs font-bold">
                <span id="questionCounterText" class="text-slate-700">
                    Soal 1 dari {{ $totalQ }}
                </span>
                <span id="progressTextPercentage" class="text-[#0A4DF3] font-mono">
                    {{ round((1 / max(1, $totalQ)) * 100) }}%
                </span>
            </div>
            
            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden border border-slate-200">
                <div id="stepperProgressBar"
                     class="h-full rounded-full bg-[#0A4DF3] transition-all duration-300"
                     style="width: {{ round((1 / max(1, $totalQ)) * 100) }}%;"></div>
            </div>
        </div>

        <form id="quizForm" action="{{ route('trainings.quiz.submit', $training->id) }}" method="POST">
            @csrf

            <!-- Question Cards (Rendered with interactive Step Nav) -->
            @foreach($quizzes as $idx => $quiz)
                @php
                    $stepIndex = $idx + 1;
                    $rawChoices = is_array($quiz->pilihan_jawaban) ? $quiz->pilihan_jawaban : json_decode($quiz->pilihan_jawaban, true) ?? [];
                    
                    $mappedChoices = [];
                    foreach ($rawChoices as $c) {
                        if (is_array($c) && isset($c['id']) && isset($c['text'])) {
                            $mappedChoices[] = $c;
                        } else {
                            $mappedChoices[] = ['id' => $c, 'text' => $c]; // Backward compatibility
                        }
                    }
                    
                    shuffle($mappedChoices);
                    $letters = ['A', 'B', 'C', 'D', 'E'];
                @endphp
                <div class="question-step-card space-y-5 {{ $idx === 0 ? '' : 'hidden' }}" id="step-card-{{ $stepIndex }}" data-step="{{ $stepIndex }}">
                    
                    {{-- Question Number & Text --}}
                    <div class="space-y-2">
                        <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100">
                            Pertanyaan #{{ $stepIndex }}
                        </span>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 leading-relaxed pt-1">
                            {{ $quiz->pertanyaan }}
                        </h3>
                    </div>

                    {{-- Options A, B, C, D --}}
                    <div class="space-y-3 pt-2">
                        @foreach($mappedChoices as $cIdx => $choiceObj)
                            @php
                                $letter = $letters[$cIdx] ?? chr(65 + $cIdx);
                                $cleanText = preg_replace('/^[A-D]\.\s*/i', '', $choiceObj['text']);
                            @endphp
                            <label class="flex items-start gap-3.5 p-4 rounded-2xl border-2 border-slate-200 hover:border-[#0A4DF3]/60 hover:bg-blue-50/40 cursor-pointer transition has-[:checked]:border-[#0A4DF3] has-[:checked]:bg-blue-50/80 shadow-sm group">
                                <input type="radio"
                                       name="answers[{{ $quiz->id }}]"
                                       value="{{ $choiceObj['id'] }}"
                                       required
                                       onchange="handleOptionSelected({{ $stepIndex }})"
                                       class="mt-1 h-4 w-4 text-[#0A4DF3] border-slate-300 focus:ring-[#0A4DF3] cursor-pointer">
                                
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-lg bg-slate-100 group-has-[:checked]:bg-[#0A4DF3] group-has-[:checked]:text-white text-slate-700 text-xs font-black flex items-center justify-center shrink-0 border border-slate-300 group-has-[:checked]:border-[#0A4DF3] transition-colors">
                                        {{ $letter }}
                                    </span>
                                    <span class="text-sm font-medium text-slate-800 group-has-[:checked]:text-slate-900 leading-snug">
                                        {{ $cleanText }}
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- Stepper Control Navigation Buttons --}}
            <div class="pt-6 border-t border-slate-100 flex items-center justify-between gap-4">
                
                <button type="button"
                        id="btnPrevQuestion"
                        onclick="goToPrevStep()"
                        class="hidden px-5 py-2.5 rounded-xl border-2 border-slate-300 text-slate-700 font-bold text-xs hover:bg-slate-100 transition active:scale-95 cursor-pointer">
                    ← Soal Sebelumnya
                </button>

                <div class="ml-auto flex items-center gap-3">
                    <button type="button"
                            id="btnNextQuestion"
                            onclick="goToNextStep()"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#0A4DF3] text-white font-bold text-xs hover:bg-blue-700 transition shadow-sm active:scale-95 cursor-pointer">
                        <span>Soal Berikutnya</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>

                    <button type="submit"
                            id="btnSubmitQuiz"
                            class="hidden inline-flex items-center gap-2 px-7 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-md active:scale-95 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Kumpulkan Jawaban</span>
                    </button>
                </div>

            </div>
        </form>

    </div>

</div>

<script>
    let currentStep = 1;
    const totalSteps = {{ $totalQ }};

    function updateStepUI() {
        // Show only active step card
        document.querySelectorAll('.question-step-card').forEach((card, idx) => {
            const stepNum = idx + 1;
            if (stepNum === currentStep) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });

        // Update Counter & Progress Bar
        const counterText = document.getElementById('questionCounterText');
        const percentageText = document.getElementById('progressTextPercentage');
        const progressBar = document.getElementById('stepperProgressBar');

        if (counterText) counterText.innerText = `Soal ${currentStep} dari ${totalSteps}`;
        const pct = Math.round((currentStep / totalSteps) * 100);
        if (percentageText) percentageText.innerText = `${pct}%`;
        if (progressBar) progressBar.style.width = `${pct}%`;

        // Toggle Prev button visibility
        const prevBtn = document.getElementById('btnPrevQuestion');
        if (prevBtn) {
            if (currentStep > 1) {
                prevBtn.classList.remove('hidden');
            } else {
                prevBtn.classList.add('hidden');
            }
        }

        // Toggle Next & Submit buttons
        const nextBtn = document.getElementById('btnNextQuestion');
        const submitBtn = document.getElementById('btnSubmitQuiz');

        if (currentStep >= totalSteps) {
            if (nextBtn) nextBtn.classList.add('hidden');
            if (submitBtn) submitBtn.classList.remove('hidden');
        } else {
            if (nextBtn) nextBtn.classList.remove('hidden');
            if (submitBtn) submitBtn.classList.add('hidden');
        }
    }

    function goToNextStep() {
        if (currentStep < totalSteps) {
            currentStep++;
            updateStepUI();
            window.scrollTo({ top: document.getElementById('quizFormSection').offsetTop - 20, behavior: 'smooth' });
        }
    }

    function goToPrevStep() {
        if (currentStep > 1) {
            currentStep--;
            updateStepUI();
            window.scrollTo({ top: document.getElementById('quizFormSection').offsetTop - 20, behavior: 'smooth' });
        }
    }

    function handleOptionSelected(stepNum) {
        // Optional quick tactile feedback
    }

    // Initialize UI on load
    document.addEventListener('DOMContentLoaded', () => {
        updateStepUI();
    });
</script>
@endsection
