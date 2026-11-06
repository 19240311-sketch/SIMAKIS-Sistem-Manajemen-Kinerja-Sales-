@extends('layouts.app')

@section('title', 'Kelola Soal & Hasil: ' . $weeklyTest->nama_tes . ' — SIMAKIS Admin')
@section('page-title', 'Kelola Tes Mingguan')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 pb-12">

    <!-- Top Breadcrumb -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.weekly-tests.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-[#1E3A8A] transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Tes Mingguan</span>
        </a>

        <div class="flex items-center gap-2">
            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $weeklyTest->status_badge_classes }}">
                {{ $weeklyTest->status_label }}
            </span>
        </div>
    </div>

    <!-- Section 1: Edit Test Settings -->
    <div class="rounded-2xl bg-white p-6 border-2 border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-900">Pengaturan Jadwal &amp; Standar Kelulusan Tes</h3>
            <span class="text-xs font-bold text-[#1E3A8A] bg-blue-50 px-2.5 py-0.5 rounded-lg border border-blue-200">
                {{ $weeklyTest->kategori }}
            </span>
        </div>

        <form action="{{ route('admin.weekly-tests.update', $weeklyTest->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Tes Mingguan</label>
                    <input type="text" name="nama_tes" value="{{ old('nama_tes', $weeklyTest->nama_tes) }}" required
                           class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kategori / Topik</label>
                    <input type="text" name="kategori" value="{{ old('kategori', $weeklyTest->kategori) }}" required
                           class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai</label>
                    <input type="datetime-local" name="tanggal_mulai" required value="{{ old('tanggal_mulai', $weeklyTest->tanggal_mulai->format('Y-m-d\TH:i')) }}"
                           class="w-full rounded-xl border-2 border-slate-300 p-2 text-xs font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Selesai</label>
                    <input type="datetime-local" name="tanggal_selesai" required value="{{ old('tanggal_selesai', $weeklyTest->tanggal_selesai->format('Y-m-d\TH:i')) }}"
                           class="w-full rounded-xl border-2 border-slate-300 p-2 text-xs font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Durasi (Menit)</label>
                    <input type="number" name="durasi_menit" value="{{ old('durasi_menit', $weeklyTest->durasi_menit) }}" min="1" max="120" required
                           class="w-full rounded-xl border-2 border-slate-300 p-2 text-xs font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nilai Min. Lulus</label>
                    <input type="number" name="nilai_minimum" value="{{ old('nilai_minimum', $weeklyTest->nilai_minimum) }}" min="1" max="100" required
                           class="w-full rounded-xl border-2 border-slate-300 p-2 text-xs font-bold text-emerald-700">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Tes</label>
                <textarea name="deskripsi" rows="2" required class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">{{ old('deskripsi', $weeklyTest->deskripsi) }}</textarea>
            </div>

            <div class="flex items-center justify-between pt-2">
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-700">Status:</label>
                    <select name="status" class="rounded-xl border-2 border-slate-300 p-1.5 text-xs font-bold text-slate-800">
                        <option value="published" {{ $weeklyTest->status === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ $weeklyTest->status === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <button type="submit" class="rounded-xl px-5 py-2 text-xs font-bold text-white bg-[#1E3A8A] hover:bg-blue-900 shadow-sm transition">
                    Simpan Perubahan Pengaturan
                </button>
            </div>
        </form>
    </div>

    <!-- Section 2: Questions List & Management -->
    <div class="rounded-2xl bg-white p-6 border-2 border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Daftar Pertanyaan Pilihan Ganda ({{ $weeklyTest->questions->count() }} Soal)</h3>
                <p class="text-[11px] text-slate-500">Pertanyaan yang akan dikerjakan secara acak atau berurutan oleh Sales</p>
            </div>
            <button type="button" onclick="document.getElementById('modalAddQuestion').classList.remove('hidden')" class="btn-primary">
                + Tambah Soal
            </button>
        </div>

        <div class="space-y-3">
            @forelse($weeklyTest->questions as $qIdx => $q)
                <div class="p-4 rounded-xl border-2 border-slate-200 bg-slate-50 space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-2">
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-md bg-[#1E3A8A] text-white font-bold text-[10px]">
                                {{ $qIdx + 1 }}
                            </span>
                            <h4 class="font-bold text-xs text-slate-900">{{ $q->pertanyaan }}</h4>
                        </div>

                        <form action="{{ route('admin.weekly-tests.questions.destroy', [$weeklyTest->id, $q->id]) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg px-2 py-1 text-[11px] font-bold text-rose-600 bg-rose-50 border border-rose-200 hover:bg-rose-100">
                                Hapus
                            </button>
                        </form>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pl-7 text-[11px]">
                        @foreach(['A' => $q->pilihan_a, 'B' => $q->pilihan_b, 'C' => $q->pilihan_c, 'D' => $q->pilihan_d] as $k => $text)
                            <div class="p-2 rounded-lg border {{ $k === $q->jawaban_benar ? 'bg-emerald-50 border-emerald-400 text-emerald-950 font-bold' : 'bg-white border-slate-200 text-slate-700' }}">
                                <span class="font-bold">{{ $k }}.</span> {{ $text }}
                                @if($k === $q->jawaban_benar) <span class="text-emerald-700 font-bold text-[10px]">(Kunci Benar)</span> @endif
                            </div>
                        @endforeach
                    </div>

                    @if(!empty($q->penjelasan))
                        <div class="pl-7 text-[11px] text-slate-500">
                            <strong>Pembahasan:</strong> {{ $q->penjelasan }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-6 text-xs text-slate-500 font-medium">
                    Belum ada soal pada tes mingguan ini. Klik tombol + Tambah Soal di atas.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Section 3: Sales Monitoring Table -->
    <div class="rounded-2xl bg-white p-6 border-2 border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Monitoring Hasil Pengerjaan Tim Sales</h3>
                <p class="text-[11px] text-slate-500">Pantau status pengerjaan dan nilai evaluasi masing-masing sales</p>
            </div>
            <span class="text-xs font-bold text-slate-700 bg-slate-100 px-3 py-1 rounded-full border border-slate-200">
                Total: {{ count($salesMonitoring) }} Sales
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3 px-3">Nama Sales</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3 text-center">Nilai</th>
                        <th class="py-3 px-3 text-center">Benar / Total</th>
                        <th class="py-3 px-3">Durasi</th>
                        <th class="py-3 px-3">Waktu Selesai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($salesMonitoring as $item)
                        @php
                            $att = $item['attempt'];
                            $isDone = $att && in_array($att->status, ['selesai', 'waktu_habis']);
                        @endphp
                        <tr class="hover:bg-blue-50/40 transition">
                            <td class="py-3 px-3">
                                <div class="font-bold text-slate-900">{{ $item['user']->name }}</div>
                                <div class="text-[11px] text-slate-500">{{ $item['user']->email }}</div>
                            </td>
                            <td class="py-3 px-3">
                                <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold {{ $att ? $att->status_badge_classes : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                    {{ $item['status'] }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center">
                                @if($isDone)
                                    <span class="font-black text-sm {{ $att->status_lulus ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $att->nilai }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-center font-bold text-slate-700">
                                @if($isDone)
                                    {{ $att->jumlah_benar }} / {{ $att->total_soal }}
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-slate-600">
                                {{ $item['duration'] }}
                            </td>
                            <td class="py-3 px-3 text-slate-500">
                                {{ $item['submitted_at'] ? $item['submitted_at']->format('d M Y, H:i') : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL ADD QUESTION -->
    <div id="modalAddQuestion" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 hidden">
        <div class="w-full max-w-xl rounded-2xl bg-white p-6 shadow-2xl border-2 border-slate-200 space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900">Tambah Soal Pilihan Ganda</h3>
                <button type="button" onclick="document.getElementById('modalAddQuestion').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
            </div>

            <form action="{{ route('admin.weekly-tests.questions.store', $weeklyTest->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pertanyaan <span class="text-rose-500">*</span></label>
                    <textarea name="pertanyaan" rows="2" required placeholder="Tuliskan pertanyaan tes..."
                              class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none"></textarea>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-700">Pilihan Jawaban (A, B, C, D) <span class="text-rose-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-700">A</span>
                        <input type="text" name="pilihan_a" required placeholder="Teks pilihan A" class="w-full rounded-xl border-2 border-slate-300 p-2 text-xs font-medium">
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-700">B</span>
                        <input type="text" name="pilihan_b" required placeholder="Teks pilihan B" class="w-full rounded-xl border-2 border-slate-300 p-2 text-xs font-medium">
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-700">C</span>
                        <input type="text" name="pilihan_c" required placeholder="Teks pilihan C" class="w-full rounded-xl border-2 border-slate-300 p-2 text-xs font-medium">
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-700">D</span>
                        <input type="text" name="pilihan_d" required placeholder="Teks pilihan D" class="w-full rounded-xl border-2 border-slate-300 p-2 text-xs font-medium">
                    </div>
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
                        <label class="block text-xs font-bold text-slate-700 mb-1">Penjelasan / Pembahasan</label>
                        <input type="text" name="penjelasan" placeholder="Alasan jawaban benar..." class="w-full rounded-xl border-2 border-slate-300 p-2.5 text-xs font-medium">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('modalAddQuestion').classList.add('hidden')" class="rounded-xl px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200">Batal</button>
                    <button type="submit" class="rounded-xl px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700">Simpan Soal</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
