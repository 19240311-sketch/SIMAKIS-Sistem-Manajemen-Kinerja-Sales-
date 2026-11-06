@extends('layouts.app')

@section('title', $meeting->judul_rapat)

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('meetings.index') }}" class="h-8 w-8 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2 mb-0.5">
                        @php
                            $statusColors = [
                                'dijadwalkan' => 'bg-blue-100 text-blue-700',
                                'berlangsung' => 'bg-emerald-100 text-emerald-700',
                                'selesai'     => 'bg-slate-100 text-slate-600',
                                'dibatalkan'  => 'bg-red-100 text-red-600',
                            ];
                            $statusLabels = [
                                'dijadwalkan' => 'Dijadwalkan',
                                'berlangsung' => 'Berlangsung',
                                'selesai'     => 'Selesai',
                                'dibatalkan'  => 'Dibatalkan',
                            ];
                            $metodeColors = ['online' => 'bg-cyan-100 text-cyan-700', 'offline' => 'bg-orange-100 text-orange-700', 'hybrid' => 'bg-purple-100 text-purple-700'];
                            $metodeLabels = ['online' => '📹 Online', 'offline' => '📍 Offline', 'hybrid' => '🔀 Hybrid'];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $statusColors[$meeting->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $statusLabels[$meeting->status] ?? $meeting->status }}
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $metodeColors[$meeting->metode] ?? '' }}">
                            {{ $metodeLabels[$meeting->metode] ?? $meeting->metode }}
                        </span>
                    </div>
                    <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ $meeting->judul_rapat }}</h1>
                </div>
            </div>
            @if(auth()->user()->isSupervisor())
                <a href="{{ route('meetings.edit', $meeting) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-amber-300 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-bold transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                    </svg>
                    Edit Rapat
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 text-xs font-medium rounded-xl px-4 py-3">
            <svg class="h-4 w-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-5">
            <!-- Detail Rapat -->
            <div class="bg-white rounded-2xl border border-hairline shadow-sm p-5">
                <h2 class="font-bold text-sm text-slate-800 mb-4">Detail Rapat</h2>
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 font-medium block mb-0.5">Tanggal</span>
                        <span class="text-slate-700 font-semibold">{{ \Carbon\Carbon::parse($meeting->tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium block mb-0.5">Waktu</span>
                        <span class="text-slate-700 font-semibold">{{ \Carbon\Carbon::parse($meeting->waktu_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($meeting->waktu_selesai)->format('H:i') }} WIB</span>
                    </div>
                    @if($meeting->link_zoom)
                        <div class="col-span-2">
                            <span class="text-slate-400 font-medium block mb-0.5">Link Online Meeting</span>
                            <a href="{{ $meeting->link_zoom }}" target="_blank" class="text-[#0A4DF3] font-semibold hover:underline break-all">{{ $meeting->link_zoom }}</a>
                        </div>
                    @endif
                    @if($meeting->nama_tempat)
                        <div>
                            <span class="text-slate-400 font-medium block mb-0.5">Tempat</span>
                            <span class="text-slate-700 font-semibold">{{ $meeting->nama_tempat }}</span>
                        </div>
                    @endif
                    @if($meeting->alamat_lokasi)
                        <div>
                            <span class="text-slate-400 font-medium block mb-0.5">Alamat</span>
                            <span class="text-slate-700">{{ $meeting->alamat_lokasi }}</span>
                        </div>
                    @endif
                    <div>
                        <span class="text-slate-400 font-medium block mb-0.5">Dijadwalkan oleh</span>
                        <span class="text-slate-700 font-semibold">{{ $meeting->creator->nama ?? '-' }}</span>
                    </div>
                </div>

                @if($meeting->agenda)
                    <div class="mt-4 pt-4 border-t border-hairline">
                        <span class="text-xs text-slate-400 font-medium block mb-2">Agenda Rapat</span>
                        <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-line">{{ $meeting->agenda }}</p>
                    </div>
                @endif

                @if($meeting->catatan_peserta)
                    <div class="mt-4 bg-blue-50 rounded-xl p-3">
                        <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wide block mb-1">Catatan untuk Peserta</span>
                        <p class="text-xs text-blue-700 leading-relaxed">{{ $meeting->catatan_peserta }}</p>
                    </div>
                @endif
            </div>

            <!-- Konfirmasi Kehadiran (untuk sales) -->
            @if($myAttendance && auth()->user()->peran === 'sales')
                <div class="bg-white rounded-2xl border border-hairline shadow-sm p-5">
                    <h2 class="font-bold text-sm text-slate-800 mb-3">Konfirmasi Kehadiran Saya</h2>
                    @php
                        $konfColors = ['hadir_online' => 'text-cyan-600', 'hadir_offline' => 'text-emerald-600', 'izin' => 'text-amber-600', 'tidak_hadir' => 'text-red-600', 'belum_konfirmasi' => 'text-slate-400'];
                        $konfLabels = ['hadir_online' => 'Hadir Online', 'hadir_offline' => 'Hadir Offline', 'izin' => 'Izin', 'tidak_hadir' => 'Tidak Hadir', 'belum_konfirmasi' => 'Belum Dikonfirmasi'];
                    @endphp
                    <p class="text-xs mb-3">
                        Status saat ini:
                        <span class="font-bold {{ $konfColors[$myAttendance->konfirmasi_kehadiran] ?? 'text-slate-500' }}">
                            {{ $konfLabels[$myAttendance->konfirmasi_kehadiran] ?? $myAttendance->konfirmasi_kehadiran }}
                        </span>
                    </p>
                    @if(in_array($meeting->status, ['dijadwalkan', 'berlangsung']))
                        <form method="POST" action="{{ route('meetings.attendance', $meeting) }}" class="space-y-3">
                            @csrf
                            <div class="grid grid-cols-2 gap-2">
                                @foreach(['hadir_online' => 'Hadir Online', 'hadir_offline' => 'Hadir Offline', 'izin' => 'Izin', 'tidak_hadir' => 'Tidak Hadir'] as $val => $label)
                                    <label class="flex items-center gap-2 p-2.5 rounded-lg border cursor-pointer hover:bg-slate-50 text-xs {{ $myAttendance->konfirmasi_kehadiran == $val ? 'border-violet-400 bg-violet-50' : 'border-slate-200' }}">
                                        <input type="radio" name="konfirmasi_kehadiran" value="{{ $val }}" {{ $myAttendance->konfirmasi_kehadiran == $val ? 'checked' : '' }} class="accent-violet-600">
                                        <span class="font-medium text-slate-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <textarea name="catatan_kehadiran" rows="2" placeholder="Catatan kehadiran (opsional)..."
                                      class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-violet-400 focus:outline-none resize-none">{{ $myAttendance->catatan_kehadiran }}</textarea>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold transition">Kirim Konfirmasi</button>
                        </form>
                    @endif
                </div>
            @endif

            <!-- Notulen & Evaluasi (Supervisor) -->
            @if(auth()->user()->isSupervisor() && in_array($meeting->status, ['berlangsung', 'selesai']))
                <div class="bg-white rounded-2xl border border-hairline shadow-sm p-5">
                    <h2 class="font-bold text-sm text-slate-800 mb-4">Notulen & Evaluasi Rapat</h2>
                    <form method="POST" action="{{ route('meetings.minutes', $meeting) }}" class="space-y-4">
                        @csrf @method('PUT')
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Notulen Rapat</label>
                            <textarea name="notulen" rows="5" placeholder="Tulis ringkasan notulen rapat..."
                                      class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-xs focus:border-violet-400 focus:outline-none resize-none">{{ $meeting->notulen }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Hasil Evaluasi</label>
                            <textarea name="hasil_evaluasi" rows="3" placeholder="Evaluasi jalannya rapat dan pencapaian agenda..."
                                      class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-xs focus:border-violet-400 focus:outline-none resize-none">{{ $meeting->hasil_evaluasi }}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Rencana Tindak Lanjut</label>
                                <textarea name="rencana_tindak_lanjut" rows="3" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-xs focus:border-violet-400 focus:outline-none resize-none">{{ $meeting->rencana_tindak_lanjut }}</textarea>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Penanggung Jawab</label>
                                    <input type="text" name="penanggung_jawab_tindak_lanjut" value="{{ $meeting->penanggung_jawab_tindak_lanjut }}"
                                           class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-violet-400 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Batas Waktu</label>
                                    <input type="date" name="batas_waktu_tindak_lanjut" value="{{ $meeting->batas_waktu_tindak_lanjut }}"
                                           class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-violet-400 focus:outline-none">
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Tindak Lanjut</label>
                            <select name="status_tindak_lanjut" class="w-full h-10 rounded-xl border border-slate-200 px-3 text-xs focus:border-violet-400 focus:outline-none">
                                <option value="belum_selesai" {{ $meeting->status_tindak_lanjut == 'belum_selesai' ? 'selected' : '' }}>Belum Selesai</option>
                                <option value="dalam_proses" {{ $meeting->status_tindak_lanjut == 'dalam_proses' ? 'selected' : '' }}>Dalam Proses</option>
                                <option value="selesai" {{ $meeting->status_tindak_lanjut == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <!-- Presensi -->
                        <div>
                            <h3 class="text-xs font-bold text-slate-700 mb-2">Presensi Kehadiran Peserta</h3>
                            <div class="space-y-2">
                                @foreach($meeting->attendances as $att)
                                    <div class="flex items-center justify-between p-2.5 rounded-lg border border-slate-200">
                                        <div class="flex items-center gap-2">
                                            <div class="h-6 w-6 rounded-full bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center text-white text-[9px] font-bold">
                                                {{ strtoupper(substr($att->user->nama ?? '-', 0, 2)) }}
                                            </div>
                                            <span class="text-xs font-medium text-slate-700">{{ $att->user->nama ?? '-' }}</span>
                                            <span class="text-[10px] text-slate-400">konfirmasi: {{ $att->konfirmasi_kehadiran }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input type="hidden" name="attendances[{{ $loop->index }}][id]" value="{{ $att->id }}">
                                            <select name="attendances[{{ $loop->index }}][status_kehadiran]"
                                                    class="h-7 rounded-lg border border-slate-200 px-2 text-[11px] focus:border-violet-400 focus:outline-none">
                                                <option value="belum_dicatat" {{ $att->status_kehadiran == 'belum_dicatat' ? 'selected' : '' }}>Belum Dicatat</option>
                                                <option value="hadir" {{ $att->status_kehadiran == 'hadir' ? 'selected' : '' }}>Hadir ✓</option>
                                                <option value="tidak_hadir" {{ $att->status_kehadiran == 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                                                <option value="izin" {{ $att->status_kehadiran == 'izin' ? 'selected' : '' }}>Izin</option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold transition">
                                Simpan Notulen & Presensi
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Show existing notulen for non-supervisors -->
            @if(!auth()->user()->isSupervisor() && $meeting->notulen)
                <div class="bg-white rounded-2xl border border-hairline shadow-sm p-5 space-y-4">
                    <h2 class="font-bold text-sm text-slate-800">Notulen Rapat</h2>
                    <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-line">{{ $meeting->notulen }}</p>
                    @if($meeting->hasil_evaluasi)
                        <div class="pt-3 border-t border-hairline">
                            <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Hasil Evaluasi</span>
                            <p class="text-xs text-slate-700 leading-relaxed">{{ $meeting->hasil_evaluasi }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar: Peserta -->
        <div class="space-y-5">
            <div class="bg-white rounded-2xl border border-hairline shadow-sm p-5">
                <h2 class="font-bold text-sm text-slate-800 mb-3">Peserta Rapat ({{ $meeting->attendances->count() }})</h2>
                <div class="space-y-2">
                    @foreach($meeting->attendances as $att)
                        @php
                            $konfColors2 = [
                                'hadir_online' => 'bg-cyan-100 text-cyan-700',
                                'hadir_offline' => 'bg-emerald-100 text-emerald-700',
                                'izin' => 'bg-amber-100 text-amber-700',
                                'tidak_hadir' => 'bg-red-100 text-red-600',
                                'belum_konfirmasi' => 'bg-slate-100 text-slate-400',
                            ];
                            $hadirColors = [
                                'belum_dicatat' => 'bg-slate-100 text-slate-400',
                                'hadir'         => 'bg-emerald-100 text-emerald-700',
                                'tidak_hadir'   => 'bg-red-100 text-red-600',
                                'izin'          => 'bg-amber-100 text-amber-700',
                            ];
                        @endphp
                        <div class="flex items-center justify-between py-1.5">
                            <div class="flex items-center gap-2">
                                <div class="h-7 w-7 rounded-full bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center text-white text-[9px] font-bold shrink-0">
                                    {{ strtoupper(substr($att->user->nama ?? '-', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-slate-700">{{ $att->user->nama ?? '-' }}</div>
                                    <span class="text-[9px] px-1.5 py-0.5 rounded-full font-bold {{ $konfColors2[$att->konfirmasi_kehadiran] ?? '' }}">
                                        {{ str_replace('_', ' ', $att->konfirmasi_kehadiran) }}
                                    </span>
                                </div>
                            </div>
                            <span class="text-[9px] px-1.5 py-0.5 rounded-full font-bold {{ $hadirColors[$att->status_kehadiran] ?? '' }}">
                                {{ str_replace('_', ' ', $att->status_kehadiran) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
