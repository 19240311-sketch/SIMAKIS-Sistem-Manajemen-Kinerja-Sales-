@extends('layouts.app')

@section('title', 'Tes Online Mingguan — SIMAKIS Yamaha')
@section('page-title', 'Tes Online Mingguan')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto pb-12">

    <!-- Header Banner / Intro -->
    <div class="rounded-2xl bg-gradient-to-r from-blue-950 via-[#1E3A8A] to-indigo-900 p-6 sm:p-8 text-white shadow-md">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold backdrop-blur-sm">
                    <svg class="h-3.5 w-3.5 text-blue-200" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Evaluasi Rutin Sales Consultant</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Tes Online Mingguan
                </h2>
                <p class="text-blue-100 text-sm max-w-2xl font-normal leading-relaxed">
                    Uji dan asah wawasan produk motor Yamaha serta skill penjualan Anda secara berkala setiap minggu untuk memastikan kualitas layanan terbaik bagi konsumen.
                </p>
            </div>

            <!-- Summary Stats -->
            <div class="grid grid-cols-3 gap-3 shrink-0 bg-white/10 p-3.5 rounded-xl backdrop-blur-md border border-white/20">
                <div class="text-center px-2">
                    <div class="text-2xl font-black text-amber-300">{{ $totalAvailable }}</div>
                    <div class="text-[11px] font-medium text-blue-200">Tes Aktif</div>
                </div>
                <div class="text-center px-2 border-x border-white/20">
                    <div class="text-2xl font-black text-white">{{ $totalCompleted }}</div>
                    <div class="text-[11px] font-medium text-blue-200">Diselesaikan</div>
                </div>
                <div class="text-center px-2">
                    <div class="text-2xl font-black text-emerald-300">{{ $avgScore }}</div>
                    <div class="text-[11px] font-medium text-blue-200">Avg Nilai</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- SECTION 1: TES AKTIF & TERSEDIA            -->
    <!-- ========================================== -->
    <div class="space-y-4">
        <div class="flex items-center justify-between border-b-2 border-slate-200 pb-3">
            <div>
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="inline-block h-3 w-3 rounded-full bg-emerald-500 animate-pulse"></span>
                    Tes Mingguan Sedang Berjalan (Tersedia)
                </h3>
                <p class="text-xs text-slate-500">Kerjakan tes berikut sebelum batas akhir periode pengerjaan ditutup.</p>
            </div>
            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full">
                {{ $activeTests->count() }} Tes Terbuka
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @forelse($activeTests as $test)
                @php
                    $attempt = $test->user_attempt;
                    $isPending = !$attempt || $attempt->status === 'sedang_mengerjakan';
                    $totalQuestions = $test->questions->count();
                @endphp
                <div class="flex flex-col justify-between rounded-2xl bg-white p-6 border-2 border-blue-200 shadow-sm hover:border-[#1E3A8A] hover:shadow-md transition">
                    <div class="space-y-4">
                        <!-- Top Info -->
                        <div class="flex items-center justify-between gap-2">
                            <span class="rounded-lg px-2.5 py-1 text-xs font-bold bg-blue-50 text-[#1E3A8A] border border-blue-200">
                                {{ $test->kategori }}
                            </span>
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                ● Aktif Dibuka
                            </span>
                        </div>

                        <!-- Test Title & Description -->
                        <div>
                            <h4 class="text-base font-bold text-slate-900 leading-snug">
                                {{ $test->nama_tes }}
                            </h4>
                            <p class="text-xs text-slate-500 mt-1.5 line-clamp-2 leading-relaxed">
                                {{ $test->deskripsi }}
                            </p>
                        </div>

                        <!-- Test Specs Box -->
                        <div class="grid grid-cols-3 gap-2 rounded-xl bg-slate-50 p-3 border border-slate-200 text-center">
                            <div>
                                <div class="text-[11px] text-slate-500 font-medium">Jumlah Soal</div>
                                <div class="text-xs font-bold text-slate-900 mt-0.5">{{ $totalQuestions }} Soal</div>
                            </div>
                            <div class="border-x border-slate-200">
                                <div class="text-[11px] text-slate-500 font-medium">Durasi Waktu</div>
                                <div class="text-xs font-bold text-slate-900 mt-0.5">{{ $test->durasi_menit }} Menit</div>
                            </div>
                            <div>
                                <div class="text-[11px] text-slate-500 font-medium">Batas Lulus</div>
                                <div class="text-xs font-bold text-emerald-600 mt-0.5">&ge; {{ $test->nilai_minimum }}/100</div>
                            </div>
                        </div>

                        <!-- Period Dates -->
                        <div class="flex items-center gap-2 text-xs text-slate-600 font-medium">
                            <svg class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Periode: <strong>{{ $test->tanggal_mulai->format('d M') }} — {{ $test->tanggal_selesai->format('d M Y, H:i') }}</strong></span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-6 mt-auto">
                        <a href="{{ route('weekly-tests.take', $test->id) }}"
                           class="w-full flex items-center justify-center gap-2 rounded-xl py-3 text-sm font-bold text-white bg-[#1E3A8A] border-2 border-[#1E3A8A] hover:bg-blue-900 hover:shadow-md transition active:scale-95 cursor-pointer">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $attempt ? 'Lanjutkan Pengerjaan Tes' : 'Mulai Kerjakan Tes' }}</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-2xl bg-white p-8 text-center border-2 border-slate-200">
                    <p class="text-sm font-semibold text-slate-700">Tidak ada tes mingguan baru yang sedang terbuka saat ini.</p>
                    <p class="text-xs text-slate-500 mt-1">Nantikan jadwal tes mingguan berikutnya yang disiapkan oleh Dealer.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- ========================================== -->
    <!-- SECTION 2: RIWAYAT TES SAYA                -->
    <!-- ========================================== -->
    <div class="space-y-4">
        <div class="flex items-center justify-between border-b-2 border-slate-200 pb-3">
            <div>
                <h3 class="text-lg font-bold text-slate-900">
                    Riwayat Tes Mingguan
                </h3>
                <p class="text-xs text-slate-500">Daftar evaluasi tes yang telah selesai Anda ikuti maupun yang telah berakhir</p>
            </div>
            <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-3 py-1 rounded-full border border-slate-200">
                {{ $historyTests->count() }} Riwayat Tes
            </span>
        </div>

        <div class="rounded-2xl bg-white border-2 border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[11px] tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4">Nama Tes Mingguan</th>
                            <th class="py-3.5 px-4">Periode</th>
                            <th class="py-3.5 px-4 text-center">Nilai</th>
                            <th class="py-3.5 px-4 text-center">Benar / Total</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($historyTests as $test)
                            @php
                                $attempt = $test->user_attempt;
                                $isFinished = $attempt && in_array($attempt->status, ['selesai', 'waktu_habis']);
                            @endphp
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $test->nama_tes }}</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ $test->kategori }} • {{ $test->questions->count() }} Soal</div>
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 font-medium">
                                    {{ $test->tanggal_mulai->format('d M') }} — {{ $test->tanggal_selesai->format('d M Y') }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($isFinished)
                                        <span class="text-sm font-black {{ $attempt->status_lulus ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $attempt->nilai }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">/100</span>
                                    @else
                                        <span class="text-slate-400 font-semibold">-</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold text-slate-700">
                                    @if($isFinished)
                                        {{ $attempt->jumlah_benar }} / {{ $attempt->total_soal }}
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($isFinished)
                                        <span class="rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $attempt->status_badge_classes }}">
                                            {{ $attempt->status_label }}
                                        </span>
                                    @elseif($test->isExpired())
                                        <span class="rounded-full px-2.5 py-0.5 text-[11px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                            Tidak Mengerjakan
                                        </span>
                                    @else
                                        <span class="rounded-full px-2.5 py-0.5 text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            Belum Mengerjakan
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    @if($isFinished)
                                        <a href="{{ route('weekly-tests.result', $test->id) }}"
                                           class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-bold text-[#1E3A8A] bg-blue-50 border border-blue-200 hover:bg-blue-100 transition">
                                            <span>Lihat Hasil & Pembahasan</span>
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @elseif($test->isActive())
                                        <a href="{{ route('weekly-tests.take', $test->id) }}"
                                           class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-bold text-white bg-[#1E3A8A] hover:bg-blue-900 transition">
                                            <span>Mulai Tes</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400 text-xs font-medium">Periode Ditutup</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-500 font-medium">
                                    Belum ada riwayat tes mingguan sebelumnya.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
