@extends('layouts.app')

@section('title', 'Mengerjakan: ' . $weeklyTest->nama_tes . ' — SIMAKIS')
@section('page-title', 'Pengerjaan Tes Online Mingguan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-16">

    <!-- Sticky Exam Top Bar: Title & Live Countdown Timer -->
    <div class="sticky top-16 z-20 rounded-2xl bg-white/95 backdrop-blur-md p-4 sm:p-5 border-2 border-slate-200 shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="text-xs font-bold text-[#1E3A8A] uppercase tracking-wider">{{ $weeklyTest->kategori }}</div>
            <h1 class="text-base sm:text-lg font-black text-slate-900 leading-snug truncate max-w-lg">
                {{ $weeklyTest->nama_tes }}
            </h1>
        </div>

        <!-- Live Countdown Timer -->
        <div class="flex items-center gap-3 shrink-0 self-end sm:self-center">
            <div class="flex items-center gap-2 rounded-xl bg-rose-50 border-2 border-rose-300 px-4 py-2 text-rose-700 shadow-sm">
                <svg class="h-5 w-5 animate-pulse text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <div class="text-[10px] font-bold text-rose-500 uppercase leading-none">Sisa Waktu</div>
                    <div id="countdownDisplay" class="text-lg font-black tracking-wider leading-tight">--:--</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Number Palette Navigation -->
    <div class="rounded-2xl bg-white p-4 sm:p-5 border-2 border-slate-200 shadow-sm space-y-3">
        <div class="flex items-center justify-between text-xs font-bold">
            <span class="text-slate-800 flex items-center gap-2">
                <svg class="h-4 w-4 text-[#1E3A8A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                Navigasi Soal ({{ $weeklyTest->questions->count() }} Soal)
            </span>
            <div class="flex items-center gap-3 text-[11px] font-medium text-slate-500">
                <span class="flex items-center gap-1.5"><span class="h-3 w-3 rounded-md bg-blue-600 inline-block"></span> Sudah Dijawab</span>
                <span class="flex items-center gap-1.5"><span class="h-3 w-3 rounded-md bg-slate-100 border border-slate-300 inline-block"></span> Belum Dijawab</span>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @foreach($weeklyTest->questions as $qIdx => $question)
                @php
                    $isAnswered = isset($savedAnswers[$question->id]);
                @endphp
                <button type="button"
                        onclick="scrollToQuestion({{ $question->id }})"
                        id="navBtn_{{ $question->id }}"
                        class="flex h-9 w-9 items-center justify-center rounded-xl text-xs font-bold transition border-2 {{ $isAnswered ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-slate-100 text-slate-700 border-slate-200 hover:border-slate-400' }}">
                    {{ $qIdx + 1 }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Exam Form -->
    <form id="examForm" action="{{ route('weekly-tests.submit', $weeklyTest->id) }}" method="POST" class="space-y-6">
        @csrf

        @foreach($weeklyTest->questions as $index => $question)
            @php
                $selected = $savedAnswers[$question->id] ?? null;
            @endphp
            <div id="questionCard_{{ $question->id }}" class="rounded-2xl bg-white p-6 border-2 border-slate-200 shadow-sm space-y-4 scroll-mt-36">
                <!-- Question Header -->
                <div class="flex items-start gap-3">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-[#1E3A8A] text-white font-bold text-xs shadow-sm">
                        {{ $index + 1 }}
                    </span>
                    <div class="space-y-1 pt-0.5 flex-1">
                        <h3 class="text-sm font-bold text-slate-900 leading-snug">
                            {{ $question->pertanyaan }}
                        </h3>
                    </div>
                </div>

                <!-- Multiple Choices (A, B, C, D) -->
                <div class="space-y-2.5 pt-1 pl-10">
                    @foreach(['A' => $question->pilihan_a, 'B' => $question->pilihan_b, 'C' => $question->pilihan_c, 'D' => $question->pilihan_d] as $key => $optionText)
                        @php
                            $isSelected = $selected === $key;
                        @endphp
                        <label class="flex items-center gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition hover:border-blue-300 hover:bg-blue-50/30 {{ $isSelected ? 'bg-blue-50 border-[#1E3A8A] text-[#1E3A8A] font-bold shadow-sm' : 'bg-slate-50 border-slate-200 text-slate-700' }}"
                               id="label_{{ $question->id }}_{{ $key }}">
                            <input type="radio"
                                   name="answers[{{ $question->id }}]"
                                   value="{{ $key }}"
                                   {{ $isSelected ? 'checked' : '' }}
                                   onchange="handleAnswerSelect({{ $question->id }}, '{{ $key }}')"
                                   class="h-4 w-4 text-[#1E3A8A] focus:ring-[#1E3A8A] border-slate-300">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-white border border-slate-300 text-xs font-bold text-slate-700">
                                {{ $key }}
                            </span>
                            <span class="text-xs leading-relaxed font-medium">{{ $optionText }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach

        <!-- Submit Prompt & Button -->
        <div class="rounded-2xl bg-white p-6 border-2 border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-0.5">
                <div class="text-xs font-bold text-slate-900">Periksa Kembali Jawaban Anda</div>
                <div class="text-[11px] text-slate-500">Pastikan seluruh nomor soal telah terjawab sebelum mengirim hasil tes.</div>
            </div>

            <button type="button"
                    onclick="openSubmitModal()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-8 py-3.5 text-sm font-bold text-white border-2 border-emerald-600 shadow-md hover:bg-emerald-700 hover:shadow-lg transition active:scale-95 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>Kirim Jawaban Tes</span>
            </button>
        </div>
    </form>

    <!-- SUBMIT CONFIRMATION MODAL -->
    <div id="submitModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 hidden">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border-2 border-slate-200 space-y-4 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600 text-xl font-bold">
                ✓
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900">Konfirmasi Pengiriman Jawaban</h3>
                <p class="text-xs text-slate-500 mt-1">
                    Apakah Anda yakin ingin mengirim hasil tes ini? Setelah dikirim, jawaban Anda tidak dapat diubah kembali.
                </p>
            </div>

            <div class="flex items-center justify-center gap-3 pt-2">
                <button type="button" onclick="closeSubmitModal()"
                        class="rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 bg-slate-100 border border-slate-300 hover:bg-slate-200 transition">
                    Periksa Lagi
                </button>
                <button type="button" onclick="document.getElementById('examForm').submit()"
                        class="rounded-xl px-6 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md transition">
                    Ya, Kirim Sekarang
                </button>
            </div>
        </div>
    </div>

</div>

<!-- Countdown Timer & Interactivity Script -->
<script>
    let remainingSeconds = {{ $remainingSeconds }};
    const displayEl = document.getElementById('countdownDisplay');
    const examForm = document.getElementById('examForm');

    function updateTimer() {
        if (remainingSeconds <= 0) {
            displayEl.textContent = '00:00';
            alert('Waktu pengerjaan tes telah habis. Jawaban Anda akan otomatis dikirimkan.');
            examForm.submit();
            return;
        }

        const minutes = Math.floor(remainingSeconds / 60);
        const seconds = remainingSeconds % 60;
        displayEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        remainingSeconds--;
    }

    // Run timer every second
    updateTimer();
    const timerInterval = setInterval(updateTimer, 1000);

    // Scroll smoothly to question
    function scrollToQuestion(qId) {
        const card = document.getElementById('questionCard_' + qId);
        if (card) {
            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
            card.classList.add('ring-2', 'ring-[#1E3A8A]');
            setTimeout(() => card.classList.remove('ring-2', 'ring-[#1E3A8A]'), 1500);
        }
    }

    // Handle answer selection to color palette & radio labels
    function handleAnswerSelect(qId, key) {
        // Update Nav Palette button
        const navBtn = document.getElementById('navBtn_' + qId);
        if (navBtn) {
            navBtn.className = 'flex h-9 w-9 items-center justify-center rounded-xl text-xs font-bold transition border-2 bg-blue-600 text-white border-blue-600 shadow-sm';
        }

        // Reset all choice labels for this question
        ['A', 'B', 'C', 'D'].forEach(k => {
            const label = document.getElementById(`label_${qId}_${k}`);
            if (label) {
                if (k === key) {
                    label.className = 'flex items-center gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition bg-blue-50 border-[#1E3A8A] text-[#1E3A8A] font-bold shadow-sm';
                } else {
                    label.className = 'flex items-center gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition hover:border-blue-300 hover:bg-blue-50/30 bg-slate-50 border-slate-200 text-slate-700';
                }
            }
        });
    }

    function openSubmitModal() {
        document.getElementById('submitModal').classList.remove('hidden');
    }

    function closeSubmitModal() {
        document.getElementById('submitModal').classList.add('hidden');
    }
</script>
@endsection
