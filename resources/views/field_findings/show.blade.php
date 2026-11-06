@extends('layouts.app')

@section('title', 'Detail Temuan Lapangan')

@section('content')
<div class="space-y-6 max-w-3xl mx-auto">
    <div class="bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('field-findings.index') }}" class="h-8 w-8 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Detail Temuan Lapangan</h1>
                    <p class="text-xs text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($fieldFinding->tanggal_temuan)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
                </div>
            </div>
            <a href="{{ route('field-findings.edit', $fieldFinding) }}"
               class="inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-amber-300 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-bold transition">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                </svg>
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <!-- Konsumen Info -->
        <div class="bg-white rounded-2xl border border-hairline shadow-sm p-5">
            <h2 class="font-bold text-sm text-slate-800 mb-4 flex items-center gap-2">
                <svg class="h-4 w-4 text-teal-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                Informasi Konsumen
            </h2>
            <dl class="space-y-3 text-xs">
                <div class="flex justify-between">
                    <dt class="text-slate-400 font-medium">Sales</dt>
                    <dd class="text-slate-700 font-semibold">{{ $fieldFinding->user->nama ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-400 font-medium">Nama Konsumen</dt>
                    <dd class="text-slate-700 font-semibold">{{ $fieldFinding->nama_konsumen ?? 'Anonim' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-400 font-medium">Kontak</dt>
                    <dd class="text-slate-700">{{ $fieldFinding->kontak_konsumen ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-400 font-medium">Status Minat</dt>
                    <dd>
                        @php $mc = ['tinggi' => 'bg-emerald-100 text-emerald-700', 'sedang' => 'bg-amber-100 text-amber-700', 'rendah' => 'bg-slate-100 text-slate-500']; @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $mc[$fieldFinding->status_minat] ?? '' }}">
                            {{ ucfirst($fieldFinding->status_minat) }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Motor Info -->
        <div class="bg-white rounded-2xl border border-hairline shadow-sm p-5">
            <h2 class="font-bold text-sm text-slate-800 mb-4 flex items-center gap-2">
                <svg class="h-4 w-4 text-teal-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                </svg>
                Motor yang Diminati
            </h2>
            <dl class="space-y-3 text-xs">
                <div class="flex justify-between">
                    <dt class="text-slate-400 font-medium">Model</dt>
                    <dd class="text-slate-700 font-semibold">{{ $fieldFinding->motorcycle->nama_model ?? 'Belum ditentukan' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-400 font-medium">Warna</dt>
                    <dd class="text-slate-700">{{ $fieldFinding->warna_motor ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-400 font-medium">Metode Bayar</dt>
                    <dd class="text-slate-700 font-medium">{{ ucwords(str_replace('_', ' ', $fieldFinding->metode_pembayaran)) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-400 font-medium">Anggaran</dt>
                    <dd class="text-slate-700">{{ $fieldFinding->kisaran_anggaran ? 'Rp ' . number_format($fieldFinding->kisaran_anggaran, 0, ',', '.') : '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Catatan & Tindak Lanjut -->
    <div class="bg-white rounded-2xl border border-hairline shadow-sm p-5 space-y-5">
        <h2 class="font-bold text-sm text-slate-800">Catatan & Tindak Lanjut</h2>

        <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide block mb-2">Catatan Komunikasi</span>
            <p class="text-xs text-slate-700 leading-relaxed bg-slate-50 p-3 rounded-xl">{{ $fieldFinding->catatan_komunikasi }}</p>
        </div>

        @if($fieldFinding->kendala)
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide block mb-2">Kendala / Hambatan</span>
                <p class="text-xs text-slate-700 leading-relaxed bg-red-50 p-3 rounded-xl">{{ $fieldFinding->kendala }}</p>
            </div>
        @endif

        <div class="grid grid-cols-2 gap-4">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide block mb-2">Rencana Tindak Lanjut</span>
                <p class="text-xs text-slate-700">{{ $fieldFinding->rencana_tindak_lanjut ?? '-' }}</p>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide block mb-2">Tanggal Tindak Lanjut</span>
                <p class="text-xs text-slate-700">
                    {{ $fieldFinding->tanggal_tindak_lanjut ? \Carbon\Carbon::parse($fieldFinding->tanggal_tindak_lanjut)->locale('id')->isoFormat('D MMMM YYYY') : '-' }}
                </p>
            </div>
        </div>

        <div class="pt-3 border-t border-hairline flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide block mb-1">Status Tindak Lanjut</span>
                @php
                    $statusColors = ['menunggu' => 'bg-amber-100 text-amber-700', 'sedang_diproses' => 'bg-blue-100 text-blue-700', 'selesai' => 'bg-emerald-100 text-emerald-700', 'dibatalkan' => 'bg-red-100 text-red-600'];
                    $statusLabels = ['menunggu' => 'Menunggu', 'sedang_diproses' => 'Sedang Diproses', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$fieldFinding->status_tindak_lanjut] ?? '' }}">
                    {{ $statusLabels[$fieldFinding->status_tindak_lanjut] ?? $fieldFinding->status_tindak_lanjut }}
                </span>
            </div>
            @if($fieldFinding->catatan_hasil_tindak_lanjut)
                <div class="text-xs text-slate-500 max-w-xs text-right">
                    <span class="text-[10px] font-bold text-slate-400 block">Hasil Tindak Lanjut:</span>
                    {{ $fieldFinding->catatan_hasil_tindak_lanjut }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
