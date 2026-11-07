@extends('layouts.app')

@section('title', $training->nama_training . ' — Video Pembelajaran SIMAKIS')
@section('page-title', 'Ruang Belajar Video')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">

    <!-- Top Navigation Breadcrumb -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <a href="{{ route('trainings.index') }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs transition-colors shadow-2xs">
            <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span>Katalog Modul</span>
        </a>

        <div class="flex items-center gap-2">
            <span class="rounded-lg px-3 py-1 text-xs font-bold border {{ $training->category_color_classes }}">
                {{ $training->category_label }}
            </span>
            <span class="rounded-lg px-3 py-1 text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200 flex items-center gap-1">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Durasi: {{ $training->durasi_video ?: $training->estimasi_waktu }}
            </span>
        </div>
    </div>

    <!-- Main Learning Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- ========================================== -->
        <!-- KOLOM KIRI (VIDEO PLAYER & PROGRESS)       -->
        <!-- ========================================== -->
        <div class="lg:col-span-8 space-y-5">
            
            <!-- Video Player Box Container -->
            <div class="rounded-2xl bg-slate-900 border-2 border-slate-800 shadow-xl overflow-hidden relative">
                
                {{-- 16:9 Aspect Ratio Responsive Wrapper --}}
                <div class="relative w-full pb-[56.25%] bg-black">
                    <div id="youtubePlayer" class="absolute inset-0 w-full h-full"></div>
                </div>

                {{-- Warning anti-seek alert if user tries to jump forward --}}
                <div id="seekWarningAlert" class="hidden absolute top-4 left-1/2 -translate-x-1/2 z-30 bg-amber-500/95 backdrop-blur-md text-white text-xs font-bold px-4 py-2 rounded-xl shadow-lg border border-amber-300 items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>Mohon tonton video secara berurutan. Anda belum dapat melompati bagian video ini.</span>
                </div>
            </div>

            <!-- Video Progress Bar & Action Banner -->
            <div class="rounded-2xl bg-white p-5 sm:p-6 border-2 border-slate-200 shadow-sm space-y-4">
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full {{ ($progress->video_completed || $progress->video_progress >= 100) ? 'bg-emerald-500' : 'bg-[#0A4DF3] animate-pulse' }}"></span>
                            <h3 class="text-sm font-bold text-slate-800">
                                Status Menonton Video
                            </h3>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5" id="progressStatusText">
                            @if($progress->video_completed || $progress->video_progress >= 100)
                                <strong class="text-emerald-600">Video Selesai ✓</strong> Anda telah menyelesaikan 100% video.
                            @else
                                Selesaikan video hingga 100% untuk membuka kuis pemahaman.
                            @endif
                        </p>
                    </div>

                    {{-- Realtime Progress Label --}}
                    <div class="text-right">
                        <span id="progressPercentDisplay" class="text-lg font-black {{ ($progress->video_completed || $progress->video_progress >= 100) ? 'text-emerald-600' : 'text-[#0A4DF3]' }}">
                            {{ ($progress->video_completed || $progress->video_progress >= 100) ? 'Video Selesai ✓' : 'Progress Video: ' . ($progress->video_progress ?? 0) . '%' }}
                        </span>
                        <div class="text-[11px] text-slate-400 font-mono" id="videoTimerDisplay">
                            00:00 / 00:00
                        </div>
                    </div>
                </div>

                {{-- Interactive Visual Progress Bar --}}
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden border border-slate-200 p-0.5">
                    <div id="progressBarFill"
                         class="h-full rounded-full transition-all duration-300 {{ ($progress->video_completed || $progress->video_progress >= 100) ? 'bg-emerald-500' : 'bg-[#0A4DF3]' }}"
                         style="width: {{ max(2, $progress->video_progress ?? 0) }}%;"></div>
                </div>

                {{-- Action Button: Lanjut ke Kuis --}}
                <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-slate-100">
                    <div class="text-xs text-slate-500 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Tombol kuis aktif otomatis saat video selesai 100%.</span>
                    </div>

                    <a id="btnProceedQuiz"
                       href="{{ route('trainings.quiz', $training->id) }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl px-6 py-3 text-sm font-bold transition shadow-sm select-none {{ ($progress->video_completed || $progress->video_progress >= 100) ? 'bg-[#0A4DF3] text-white hover:bg-blue-700 cursor-pointer shadow-md' : 'bg-slate-200 text-slate-400 cursor-not-allowed pointer-events-none' }}">
                        <span>Lanjut ke Kuis</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Module Description & Content Section -->
            <div class="rounded-2xl bg-white p-6 border-2 border-slate-200 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-2 h-4 rounded-full bg-[#0A4DF3]"></span>
                    Ringkasan Materi Pembelajaran
                </h3>
                <div class="text-sm text-slate-700 leading-relaxed space-y-2">
                    <p>{{ $training->deskripsi }}</p>
                </div>
            </div>

        </div>

        <!-- ========================================== -->
        <!-- KOLOM KANAN (INFO KUIS & PERSYARATAN)       -->
        <!-- ========================================== -->
        <div class="lg:col-span-4 space-y-5">

            <!-- Card Info Modul -->
            <div class="rounded-2xl bg-white p-6 border-2 border-slate-200 shadow-sm space-y-5">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Judul Materi</span>
                    <h2 class="text-lg font-black text-slate-900 mt-0.5 leading-snug">
                        {{ $training->nama_training }}
                    </h2>
                </div>

                {{-- Requirements checklist --}}
                <div class="space-y-3 pt-2 border-t border-slate-100">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Alur Penyelesaian</h4>

                    <div class="flex items-start gap-3 text-xs">
                        <div class="w-5 h-5 rounded-full flex items-center justify-center shrink-0 mt-0.5 {{ ($progress->video_completed || $progress->video_progress >= 100) ? 'bg-emerald-100 text-emerald-700 font-bold' : 'bg-blue-100 text-[#0A4DF3] font-bold' }}">
                            1
                        </div>
                        <div>
                            <span class="font-bold text-slate-800">Tonton Video Resmi</span>
                            <p class="text-slate-500 text-[11px] mt-0.5">Tonton video hingga selesai tanpa melewati bagian video.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 text-xs">
                        <div class="w-5 h-5 rounded-full flex items-center justify-center shrink-0 mt-0.5 {{ $progress->status_lulus ? 'bg-emerald-100 text-emerald-700 font-bold' : 'bg-slate-100 text-slate-600 font-bold' }}">
                            2
                        </div>
                        <div>
                            <span class="font-bold text-slate-800">Kuis Evaluasi Pemahaman</span>
                            <p class="text-slate-500 text-[11px] mt-0.5">{{ $training->quizzes->count() }} soal pilihan ganda.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 text-xs">
                        <div class="w-5 h-5 rounded-full flex items-center justify-center shrink-0 mt-0.5 {{ $progress->status_lulus ? 'bg-emerald-100 text-emerald-700 font-bold' : 'bg-slate-100 text-slate-600 font-bold' }}">
                            3
                        </div>
                        <div>
                            <span class="font-bold text-slate-800">Capai Nilai Lulus ({{ $training->passing_grade ?: 80 }}%)</span>
                            <p class="text-slate-500 text-[11px] mt-0.5">Jika belum lulus, Anda dapat mengulang kuis kembali.</p>
                        </div>
                    </div>
                </div>

                {{-- Passing Grade Box --}}
                <div class="rounded-xl bg-blue-50/80 p-4 border border-blue-200 text-center space-y-1">
                    <span class="text-xs font-semibold text-blue-800">Standar Kelulusan (Passing Grade)</span>
                    <div class="text-3xl font-black text-[#0A4DF3]">
                        {{ $training->passing_grade ?: 80 }}%
                    </div>
                    <span class="text-[11px] text-blue-600 block">Minimal benar {{ ceil(($training->quizzes->count() * ($training->passing_grade ?: 80)) / 100) }} dari {{ $training->quizzes->count() }} soal</span>
                </div>

                {{-- Riwayat Kuis Terakhir jika ada --}}
                @if($latestQuiz)
                    <div class="rounded-xl p-3.5 border {{ $latestQuiz->status_lulus ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-amber-50 border-amber-200 text-amber-900' }} text-xs space-y-1">
                        <div class="flex items-center justify-between font-bold">
                            <span>Hasil Kuis Terakhir:</span>
                            <span>{{ $latestQuiz->nilai }}%</span>
                        </div>
                        <div class="text-[11px]">
                            Status: <strong>{{ $latestQuiz->status_lulus ? 'LULUS ✓' : 'Belum Lulus' }}</strong>
                            ({{ $latestQuiz->jumlah_benar }} dari {{ $latestQuiz->total_soal }} benar)
                        </div>
                        <div class="text-[10px] text-slate-500 pt-1">
                            Dikerjakan pada {{ $latestQuiz->waktu_selesai ? $latestQuiz->waktu_selesai->format('d M Y H:i') : '-' }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Tips Pembelajaran -->
            <div class="rounded-2xl bg-white p-5 border-2 border-slate-200 shadow-sm text-xs text-slate-600 space-y-2">
                <span class="font-bold text-slate-800 flex items-center gap-1.5">
                    <svg class="h-4 w-4 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                    </svg>
                    <span>Petunjuk Belajar Sales:</span>
                </span>
                <ul class="list-disc list-inside space-y-1 text-slate-500 leading-relaxed text-[11px]">
                    <li>Perhatikan fitur-fitur pembeda produk Yamaha.</li>
                    <li>Catat keunggulan Blue Core, konektivitas Y-Connect, dan garansi.</li>
                    <li>Gunakan poin-poin dalam video saat berhadapan dengan calon pembeli di dealer.</li>
                </ul>
            </div>

        </div>

    </div>

</div>

{{-- YouTube IFrame Player API Scripts --}}
<script>
    const YOUTUBE_VIDEO_ID = "{{ $training->youtube_video_id }}";
    const CSRF_TOKEN = "{{ csrf_token() }}";
    const PROGRESS_URL = "{{ route('trainings.video-progress', $training->id) }}";
    
    let isAlreadyCompleted = {{ ($progress->video_completed || $progress->video_progress >= 100) ? 'true' : 'false' }};
    let currentWatchedProgress = {{ (int) ($progress->video_progress ?? 0) }};
    
    let player;
    let pollInterval = null;
    let maxWatchedSeconds = 0;
    let lastReportedPercent = currentWatchedProgress;

    // Load YouTube IFrame Player API
    const tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    const firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    function onYouTubeIframeAPIReady() {
        player = new YT.Player('youtubePlayer', {
            videoId: YOUTUBE_VIDEO_ID,
            playerVars: {
                'playsinline': 1,
                'rel': 0,
                'modestbranding': 1,
                'controls': 1,
            },
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    }

    function onPlayerReady(event) {
        const duration = player.getDuration();
        if (duration > 0 && currentWatchedProgress > 0) {
            maxWatchedSeconds = (currentWatchedProgress / 100) * duration;
        }
        updateTimerDisplay(0, duration);
    }

    function onPlayerStateChange(event) {
        if (event.data === YT.PlayerState.PLAYING) {
            startProgressTracking();
        } else {
            stopProgressTracking();
        }

        // When video ends 100%
        if (event.data === YT.PlayerState.ENDED) {
            handleVideoFinished();
        }
    }

    function startProgressTracking() {
        if (pollInterval) clearInterval(pollInterval);
        pollInterval = setInterval(checkVideoProgress, 800);
    }

    function stopProgressTracking() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
    }

    function checkVideoProgress() {
        if (!player || typeof player.getCurrentTime !== 'function') return;

        const currentTime = player.getCurrentTime();
        const duration = player.getDuration();
        if (!duration || duration <= 0) return;

        // Anti-seek mechanism (only if not yet completed)
        if (!isAlreadyCompleted) {
            // If user jumped forward more than 3 seconds past maxWatchedSeconds
            if (currentTime > maxWatchedSeconds + 3) {
                player.seekTo(maxWatchedSeconds, true);
                showSeekWarning();
                return;
            }

            if (currentTime > maxWatchedSeconds) {
                maxWatchedSeconds = currentTime;
            }
        }

        // Calculate progress percentage
        const effectiveTime = isAlreadyCompleted ? duration : Math.max(currentTime, maxWatchedSeconds);
        const percent = Math.min(100, Math.round((effectiveTime / duration) * 100));

        updateProgressUI(percent);
        updateTimerDisplay(currentTime, duration);

        // Report progress to server every 5% increment
        if (percent >= lastReportedPercent + 5 || (percent >= 100 && !isAlreadyCompleted)) {
            lastReportedPercent = percent;
            sendProgressToServer(percent, percent >= 100, currentTime, duration);
        }

        if (percent >= 99 || currentTime >= duration - 1) {
            handleVideoFinished();
        }
    }

    function handleVideoFinished() {
        isAlreadyCompleted = true;
        updateProgressUI(100);
        sendProgressToServer(100, true, player.getDuration(), player.getDuration());
        enableQuizButton();
    }

    function updateProgressUI(percent) {
        const fill = document.getElementById('progressBarFill');
        const display = document.getElementById('progressPercentDisplay');
        const statusText = document.getElementById('progressStatusText');

        if (fill) fill.style.width = Math.max(2, percent) + '%';

        if (percent >= 100 || isAlreadyCompleted) {
            if (display) {
                display.innerText = 'Video Selesai ✓';
                display.className = 'text-lg font-black text-emerald-600';
            }
            if (statusText) {
                statusText.innerHTML = '<strong class="text-emerald-600">Video Selesai ✓</strong> Anda telah menyelesaikan 100% video.';
            }
            if (fill) {
                fill.className = 'h-full rounded-full transition-all duration-300 bg-emerald-500';
            }
        } else {
            if (display) {
                display.innerText = 'Progress Video: ' + percent + '%';
            }
        }
    }

    function updateTimerDisplay(current, duration) {
        const timerElem = document.getElementById('videoTimerDisplay');
        if (!timerElem) return;
        timerElem.innerText = formatTime(current) + ' / ' + formatTime(duration);
    }

    function formatTime(sec) {
        if (!sec || isNaN(sec)) return "00:00";
        sec = Math.round(sec);
        const m = Math.floor(sec / 60);
        const s = sec % 60;
        return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
    }

    function enableQuizButton() {
        const btn = document.getElementById('btnProceedQuiz');
        if (!btn) return;

        btn.classList.remove('bg-slate-200', 'text-slate-400', 'cursor-not-allowed', 'pointer-events-none');
        btn.classList.add('bg-[#0A4DF3]', 'text-white', 'hover:bg-blue-700', 'cursor-pointer', 'shadow-md');
    }

    function showSeekWarning() {
        const alertBox = document.getElementById('seekWarningAlert');
        if (!alertBox) return;
        alertBox.classList.remove('hidden');
        alertBox.classList.add('flex');
        setTimeout(() => {
            alertBox.classList.remove('flex');
            alertBox.classList.add('hidden');
        }, 3500);
    }

    function sendProgressToServer(percent, completed, currentTime, duration) {
        fetch(PROGRESS_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                progress: percent,
                completed: completed ? 1 : 0,
                current_time: currentTime,
                duration: duration
            })
        }).then(res => res.json()).then(data => {
            if (data.can_take_quiz) {
                enableQuizButton();
            }
        }).catch(err => console.error('Error tracking video progress:', err));
    }
</script>
@endsection
