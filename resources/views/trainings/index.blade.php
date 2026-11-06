@extends('layouts.app')

@section('title', 'Product Knowledge & Training Sales — SIMAKIS Yamaha')
@section('page-title', 'Product Knowledge & Training Sales')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto pb-12">

    <!-- Header Banner / Intro -->
    <div class="rounded-2xl p-6 sm:p-8 text-white shadow-md relative overflow-hidden"
         style="background: linear-gradient(135deg, #183A60 0%, #1e4673 50%, #0A4DF3 100%);">
        
        {{-- Subtle background glowing circles --}}
        <div class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full bg-white/10 filter blur-2xl pointer-events-none"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold backdrop-blur-sm border border-white/20">
                    <svg class="h-3.5 w-3.5 text-blue-200" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    <span>Sistem Pembelajaran Video &amp; Kuis Interaktif</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight" style="color: #ffffff !important;">
                    Product Knowledge &amp; Training Sales
                </h2>
                <p class="text-blue-100 text-sm max-w-2xl font-normal leading-relaxed">
                    Tonton video pembelajaran resmi hingga 100% dan uji pemahaman Anda melalui kuis evaluasi untuk menguasai selling point sepeda motor Yamaha JG Motor.
                </p>
            </div>

            <!-- Mini Sales Training Summary Metrics -->
            <div class="grid grid-cols-3 gap-3 shrink-0 bg-white/10 p-3.5 rounded-xl backdrop-blur-md border border-white/20">
                <div class="text-center px-2">
                    <div class="text-2xl font-black text-white">{{ $totalCompleted }}</div>
                    <div class="text-[11px] font-medium text-blue-200">Lulus ✓</div>
                </div>
                <div class="text-center px-2 border-x border-white/20">
                    <div class="text-2xl font-black text-amber-300">{{ $totalInProgress }}</div>
                    <div class="text-[11px] font-medium text-blue-200">Belajar</div>
                </div>
                <div class="text-center px-2">
                    <div class="text-2xl font-black text-emerald-300">{{ $avgScore }}%</div>
                    <div class="text-[11px] font-medium text-blue-200">Rata-rata</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- SECTION 1: KURSUS SAYA                    -->
    <!-- ========================================== -->
    @if($myCourses->isNotEmpty())
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-blue-100 text-[#0A4DF3] text-xs font-black">▶</span>
                        Modul yang Sedang &amp; Telah Dipelajari
                    </h3>
                    <p class="text-xs text-slate-500">Lanjutkan materi video dan pantau riwayat kelulusan kuis Anda</p>
                </div>
                <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-3 py-1 rounded-full border border-slate-200">
                    {{ $myCourses->count() }} Modul Aktif
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($myCourses as $course)
                    @php
                        $prog = $course->progress;
                        $vidPct = $prog->video_progress ?? 0;
                        $isPassed = (bool) ($prog->status_lulus ?? false);
                        $vidDone = (bool) ($prog->video_completed ?? false) || ($vidPct >= 100);
                        $quizCount = $course->quizzes->count();
                        $passingGrade = $course->passing_grade ?? 80;
                    @endphp
                    <div class="flex flex-col justify-between rounded-2xl bg-white border-2 border-slate-200 shadow-sm hover:border-[#0A4DF3]/60 hover:shadow-md transition group overflow-hidden">
                        
                        {{-- Video Thumbnail Header --}}
                        <div class="relative w-full h-40 bg-slate-900 overflow-hidden">
                            <img src="{{ $course->effective_thumbnail }}" alt="{{ $course->nama_training }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                            
                            {{-- Category Badge --}}
                            <div class="absolute top-3 left-3">
                                <span class="rounded-lg px-2.5 py-1 text-[11px] font-bold border backdrop-blur-md bg-white/90 text-slate-800 shadow-sm">
                                    {{ $course->category_label }}
                                </span>
                            </div>

                            {{-- Status Badge --}}
                            <div class="absolute top-3 right-3">
                                @if($isPassed)
                                    <span class="rounded-full px-2.5 py-0.5 text-xs font-bold bg-emerald-500 text-white shadow-sm flex items-center gap-1">
                                        ✓ Lulus ({{ $prog->nilai_kuis }}%)
                                    </span>
                                @elseif($prog && $prog->nilai_kuis !== null && !$isPassed)
                                    <span class="rounded-full px-2.5 py-0.5 text-xs font-bold bg-rose-500 text-white shadow-sm flex items-center gap-1">
                                        Belum Lulus ({{ $prog->nilai_kuis }}%)
                                    </span>
                                @elseif($vidDone)
                                    <span class="rounded-full px-2.5 py-0.5 text-xs font-bold bg-blue-500 text-white shadow-sm flex items-center gap-1">
                                        Video Selesai ✓
                                    </span>
                                @elseif($vidPct > 0)
                                    <span class="rounded-full px-2.5 py-0.5 text-xs font-bold bg-amber-500 text-white shadow-sm flex items-center gap-1">
                                        ▶ Video {{ $vidPct }}%
                                    </span>
                                @else
                                    <span class="rounded-full px-2.5 py-0.5 text-xs font-bold bg-slate-700 text-slate-200 shadow-sm flex items-center gap-1">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                        Belum Mulai
                                    </span>
                                @endif
                            </div>

                            {{-- Duration Pill --}}
                            <div class="absolute bottom-2.5 left-3 text-[11px] font-semibold text-white/90 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-red-500 fill-current" viewBox="0 0 24 24">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                                <span>Durasi: {{ $course->durasi_video ?: $course->estimasi_waktu }}</span>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-5 flex flex-col justify-between flex-1 space-y-3">
                            <div>
                                <h4 class="text-base font-bold text-slate-900 group-hover:text-[#0A4DF3] transition line-clamp-1">
                                    {{ $course->nama_training }}
                                </h4>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                                    {{ $course->deskripsi }}
                                </p>
                            </div>

                            {{-- Progress Bars --}}
                            <div class="space-y-1.5 pt-1">
                                <div class="flex items-center justify-between text-xs font-bold">
                                    <span class="text-slate-600">Progress Video:</span>
                                    <span class="{{ $vidDone ? 'text-blue-600' : 'text-slate-700' }}">
                                        {{ $vidDone ? 'Video Selesai ✓ (100%)' : $vidPct . '%' }}
                                    </span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-slate-100 overflow-hidden border border-slate-200">
                                    <div class="h-full rounded-full transition-all duration-500 {{ $vidDone ? 'bg-blue-600' : 'bg-[#0A4DF3]' }}" style="width: {{ $vidPct }}%"></div>
                                </div>

                                <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1">
                                    <span>Passing Grade: <strong>{{ $passingGrade }}%</strong></span>
                                    <span>Kuis: <strong>{{ $quizCount }} Soal</strong></span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="pt-3 mt-auto">
                                @if($isPassed)
                                    <a href="{{ route('trainings.show', $course->id) }}"
                                       class="w-full flex items-center justify-center gap-2 rounded-xl py-2.5 text-xs font-bold transition shadow-sm bg-emerald-50 text-emerald-700 border-2 border-emerald-300 hover:bg-emerald-100">
                                        <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>✓ Materi Selesai</span>
                                    </a>
                                @elseif($prog && $prog->nilai_kuis !== null && !$isPassed)
                                    <a href="{{ route('trainings.quiz', $course->id) }}"
                                       class="w-full flex items-center justify-center gap-2 rounded-xl py-2.5 text-xs font-bold transition shadow-sm bg-amber-500 text-white border-2 border-amber-600 hover:bg-amber-600">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        <span>Ulangi Kuis (Nilai: {{ $prog->nilai_kuis }}%)</span>
                                    </a>
                                @elseif($vidDone)
                                    <a href="{{ route('trainings.quiz', $course->id) }}"
                                       class="w-full flex items-center justify-center gap-2 rounded-xl py-2.5 text-xs font-bold transition shadow-sm bg-[#0A4DF3] text-white border-2 border-[#0A4DF3] hover:bg-blue-700">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Lanjut ke Kuis</span>
                                    </a>
                                @else
                                    <a href="{{ route('trainings.show', $course->id) }}"
                                       class="w-full flex items-center justify-center gap-2 rounded-xl py-2.5 text-xs font-bold transition shadow-sm bg-[#183A60] text-white border-2 border-[#183A60] hover:bg-[#214A75]">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        </svg>
                                        <span>Mulai Pelajari Modul</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- ========================================== -->
    <!-- SECTION 2: KATALOG SEMUA TRAINING          -->
    <!-- ========================================== -->
    <div class="space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b-2 border-slate-200 pb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900">
                    Katalog Materi Pembelajaran Sales
                </h3>
                <p class="text-xs text-slate-500">Pilih modul untuk menonton video pembelajaran dan selesaikan kuis pemahaman</p>
            </div>

            <!-- Search Form -->
            <form action="{{ route('trainings.index') }}" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="kategori" value="{{ $tab }}">
                <div class="relative">
                    <input type="text" name="q" value="{{ $search }}"
                           placeholder="Cari materi atau topik..."
                           class="w-48 sm:w-64 rounded-xl border-2 border-slate-300 bg-white py-2 pl-9 pr-3 text-xs font-medium text-slate-800 placeholder:text-slate-400 focus:border-[#0A4DF3] focus:outline-none">
                    <svg class="absolute left-3 top-2.5 h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                @if(!empty($search))
                    <a href="{{ route('trainings.index', ['kategori' => $tab]) }}" class="rounded-xl border border-slate-300 p-2 text-slate-500 hover:bg-slate-100" title="Reset">
                        ✕
                    </a>
                @endif
            </form>
        </div>

        <!-- Filter Segmented Tabs -->
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('trainings.index', ['kategori' => 'all', 'q' => $search]) }}"
               class="rounded-xl px-4 py-2 text-xs font-bold transition border-2 {{ $tab === 'all' ? 'bg-[#183A60] text-white border-[#183A60] shadow-sm' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}">
                Semua Materi
            </a>
            <a href="{{ route('trainings.index', ['kategori' => 'product_knowledge', 'q' => $search]) }}"
               class="flex items-center gap-1.5 rounded-xl px-4 py-2 text-xs font-bold transition border-2 {{ $tab === 'product_knowledge' ? 'bg-[#0A4DF3] text-white border-[#0A4DF3] shadow-sm' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}">
                <span>Product Knowledge</span>
            </a>
            <a href="{{ route('trainings.index', ['kategori' => 'sales_skill', 'q' => $search]) }}"
               class="flex items-center gap-1.5 rounded-xl px-4 py-2 text-xs font-bold transition border-2 {{ $tab === 'sales_skill' ? 'bg-[#0A4DF3] text-white border-[#0A4DF3] shadow-sm' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}">
                <span>Sales Skill</span>
            </a>
            <a href="{{ route('trainings.index', ['kategori' => 'product_update', 'q' => $search]) }}"
               class="flex items-center gap-1.5 rounded-xl px-4 py-2 text-xs font-bold transition border-2 {{ $tab === 'product_update' ? 'bg-[#0A4DF3] text-white border-[#0A4DF3] shadow-sm' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}">
                <span>Product Update</span>
            </a>
            <a href="{{ route('trainings.index', ['kategori' => 'training', 'q' => $search]) }}"
               class="flex items-center gap-1.5 rounded-xl px-4 py-2 text-xs font-bold transition border-2 {{ $tab === 'training' ? 'bg-[#0A4DF3] text-white border-[#0A4DF3] shadow-sm' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}">
                <span>Training</span>
            </a>
        </div>

        <!-- Training Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($allTrainings as $training)
                @php
                    $prog = $training->progress;
                    $vidPct = $prog->video_progress ?? 0;
                    $isPassed = (bool) ($prog->status_lulus ?? false);
                    $vidDone = (bool) ($prog->video_completed ?? false) || ($vidPct >= 100);
                    $quizCount = $training->quizzes->count();
                    $passingGrade = $training->passing_grade ?? 80;
                @endphp
                <div class="flex flex-col justify-between rounded-2xl bg-white border-2 border-slate-200 shadow-sm hover:border-[#0A4DF3]/60 hover:shadow-md transition group overflow-hidden">
                    
                    {{-- Thumbnail & Video Badge --}}
                    <div class="relative w-full h-44 bg-slate-900 overflow-hidden">
                        <img src="{{ $training->effective_thumbnail }}" alt="{{ $training->nama_training }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent"></div>

                        {{-- Category Badge --}}
                        <div class="absolute top-3 left-3">
                            <span class="rounded-lg px-2.5 py-1 text-xs font-bold border backdrop-blur-md bg-white/90 text-slate-900 shadow-sm">
                                {{ $training->category_label }}
                            </span>
                        </div>

                        {{-- Status Badge --}}
                        <div class="absolute top-3 right-3">
                            @if($isPassed)
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-bold bg-emerald-500 text-white shadow-sm flex items-center gap-1">
                                    ✓ Lulus ({{ $prog->nilai_kuis }}%)
                                </span>
                            @elseif($prog && $prog->nilai_kuis !== null && !$isPassed)
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-bold bg-rose-500 text-white shadow-sm flex items-center gap-1">
                                    Belum Lulus ({{ $prog->nilai_kuis }}%)
                                </span>
                            @elseif($vidDone)
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-bold bg-blue-500 text-white shadow-sm flex items-center gap-1">
                                    Video Selesai ✓
                                </span>
                            @elseif($vidPct > 0)
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-bold bg-amber-500 text-white shadow-sm flex items-center gap-1">
                                    ▶ Sedang Dipelajari
                                </span>
                            @else
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-bold bg-slate-700 text-slate-200 shadow-sm flex items-center gap-1">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                    Belum Dimulai
                                </span>
                            @endif
                        </div>

                        {{-- Video Tag & Duration --}}
                        <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-xs text-white">
                            <div class="flex items-center gap-1.5 font-semibold">
                                <span class="h-2 w-2 rounded-full bg-red-500 animate-pulse"></span>
                                <span>YouTube Video</span>
                            </div>
                            <span class="bg-black/60 px-2 py-0.5 rounded text-[11px] font-mono flex items-center gap-1">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $training->durasi_video ?: $training->estimasi_waktu }}
                            </span>
                        </div>
                    </div>

                    {{-- Card Content --}}
                    <div class="p-6 flex flex-col justify-between flex-1 space-y-4">
                        <div class="space-y-2">
                            <h4 class="text-base font-bold text-slate-900 group-hover:text-[#0A4DF3] transition leading-snug line-clamp-2">
                                {{ $training->nama_training }}
                            </h4>
                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                {{ $training->deskripsi }}
                            </p>
                        </div>

                        <!-- Specs / Outline Details -->
                        <div class="rounded-xl bg-slate-50 p-3.5 border border-slate-200 space-y-1.5">
                            <div class="flex items-center justify-between text-xs text-slate-600">
                                <span class="font-medium">Passing Grade Kuis:</span>
                                <span class="font-bold text-slate-900">{{ $passingGrade }}%</span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-slate-600">
                                <span class="font-medium">Jumlah Evaluasi:</span>
                                <span class="font-bold text-slate-900">{{ $quizCount }} Soal Pilihan Ganda</span>
                            </div>
                            @if($training->latest_quiz)
                                <div class="flex items-center justify-between text-xs pt-1.5 border-t border-slate-200">
                                    <span class="font-medium text-slate-600">Hasil Terakhir:</span>
                                    <span class="font-bold {{ $training->latest_quiz->status_lulus ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $training->latest_quiz->nilai }}% ({{ $training->latest_quiz->status_lulus ? 'Lulus' : 'Belum Lulus' }})
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Progress indicator if started -->
                        @if($prog)
                            <div class="space-y-1 pt-1">
                                <div class="flex items-center justify-between text-xs font-semibold text-slate-600">
                                    <span>Progress Video:</span>
                                    <span class="{{ $vidDone ? 'text-blue-700' : 'text-slate-800' }}">{{ $vidPct }}%</span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-slate-100 overflow-hidden border border-slate-200">
                                    <div class="h-full rounded-full transition-all duration-300 {{ $vidDone ? 'bg-blue-600' : 'bg-[#0A4DF3]' }}" style="width: {{ $vidPct }}%"></div>
                                </div>
                            </div>
                        @endif

                        <!-- Action Button -->
                        <div class="pt-2 mt-auto">
                            @if($isPassed)
                                <a href="{{ route('trainings.show', $training->id) }}"
                                   class="w-full flex items-center justify-center gap-2 rounded-xl py-3 text-xs font-bold transition shadow-sm bg-emerald-50 text-emerald-700 border-2 border-emerald-300 hover:bg-emerald-100">
                                    <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>✓ Materi Selesai</span>
                                </a>
                            @elseif($prog && $prog->nilai_kuis !== null && !$isPassed)
                                <a href="{{ route('trainings.quiz', $training->id) }}"
                                   class="w-full flex items-center justify-center gap-2 rounded-xl py-3 text-xs font-bold transition shadow-sm bg-amber-500 text-white border-2 border-amber-600 hover:bg-amber-600">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <span>Ulangi Kuis</span>
                                </a>
                            @elseif($vidDone)
                                <a href="{{ route('trainings.quiz', $training->id) }}"
                                   class="w-full flex items-center justify-center gap-2 rounded-xl py-3 text-xs font-bold transition shadow-sm bg-[#0A4DF3] text-white border-2 border-[#0A4DF3] hover:bg-blue-700 hover:shadow-md">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Lanjut ke Kuis</span>
                                </a>
                            @else
                                <a href="{{ route('trainings.show', $training->id) }}"
                                   class="w-full flex items-center justify-center gap-2 rounded-xl py-3 text-xs font-bold transition shadow-sm bg-[#183A60] text-white border-2 border-[#183A60] hover:bg-[#214A75] hover:shadow-md">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    </svg>
                                    <span>Mulai Pelajari Modul</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-2xl bg-white p-12 text-center border-2 border-dashed border-slate-200">
                    <p class="text-sm font-semibold text-slate-600">Tidak ada modul pembelajaran yang cocok dengan filter pencarian.</p>
                    <a href="{{ route('trainings.index') }}" class="mt-3 inline-block text-xs font-bold text-[#0A4DF3] hover:underline">
                        Tampilkan Semua Modul
                    </a>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
