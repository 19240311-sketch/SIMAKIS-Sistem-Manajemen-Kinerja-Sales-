@extends('layouts.app')

@section('title', 'Serah Terima Jabatan Supervisor — SIMAKIS Yamaha JG Purwakarta')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    {{-- ============================================================
         1. TOP HEADER & KONTROL
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-indigo-600"></span>
                        Transisi Operasional Sales
                    </span>
                    <span class="text-xs text-slate-400 font-medium">SIMAKIS &bull; Yamaha JG Purwakarta</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                    Serah Terima Jabatan Supervisor
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Dokumen resmi penyerahan tanggung jawab operasional dari Supervisor lama kepada Supervisor baru, mencakup rekap penjualan, target, prospek aktif, dan agenda rapat.
                </p>
            </div>

            @if(Auth::user()->isSuperAdmin())
            <div class="flex items-center gap-2.5">
                <a href="{{ route('supervisor.handovers.create') }}"
                   style="background-color: #4f46e5 !important; color: #ffffff !important;"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition-all active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Buat Dokumen Serah Terima</span>
                </a>
            </div>
            @endif
        </div>
    </div>

    {{-- ============================================================
         2. STATISTIK RINGKAS
         ============================================================ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4 flex items-center justify-between">
            <div>
                <span class="text-2xs font-bold text-slate-400 uppercase tracking-wider">Total Dokumen</span>
                <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total'] ?? 0 }}</div>
            </div>
            <div class="h-10 w-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4 flex items-center justify-between">
            <div>
                <span class="text-2xs font-bold text-slate-400 uppercase tracking-wider">Menunggu Serah Terima</span>
                <div class="text-2xl font-black text-blue-600 mt-1">{{ $stats['menunggu'] ?? 0 }}</div>
            </div>
            <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4 flex items-center justify-between">
            <div>
                <span class="text-2xs font-bold text-slate-400 uppercase tracking-wider">Dalam Proses</span>
                <div class="text-2xl font-black text-amber-600 mt-1">{{ $stats['dalam_proses'] ?? 0 }}</div>
            </div>
            <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4 flex items-center justify-between">
            <div>
                <span class="text-2xs font-bold text-slate-400 uppercase tracking-wider">Selesai / Tervalidasi</span>
                <div class="text-2xl font-black text-emerald-600 mt-1">{{ $stats['selesai'] ?? 0 }}</div>
            </div>
            <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- ============================================================
         3. FILTER STATUS DOKUMEN
         ============================================================ --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
        <a href="{{ route('supervisor.handovers.index') }}"
           style="{{ !request('status') ? 'background-color: #2563eb !important; color: #ffffff !important; border: 1px solid #2563eb !important;' : 'background-color: #ffffff !important; color: #334155 !important; border: 1px solid #cbd5e1 !important;' }}"
           class="px-4 py-2 rounded-xl font-bold transition-colors whitespace-nowrap shadow-xs">
            Semua Dokumen
        </a>
        <a href="{{ route('supervisor.handovers.index', ['status' => 'menunggu_serah_terima']) }}"
           style="{{ request('status') === 'menunggu_serah_terima' ? 'background-color: #2563eb !important; color: #ffffff !important; border: 1px solid #2563eb !important;' : 'background-color: #ffffff !important; color: #334155 !important; border: 1px solid #cbd5e1 !important;' }}"
           class="px-4 py-2 rounded-xl font-bold transition-colors whitespace-nowrap shadow-xs">
            Menunggu Serah Terima
        </a>
        <a href="{{ route('supervisor.handovers.index', ['status' => 'dalam_proses']) }}"
           style="{{ request('status') === 'dalam_proses' ? 'background-color: #d97706 !important; color: #ffffff !important; border: 1px solid #d97706 !important;' : 'background-color: #ffffff !important; color: #334155 !important; border: 1px solid #cbd5e1 !important;' }}"
           class="px-4 py-2 rounded-xl font-bold transition-colors whitespace-nowrap shadow-xs">
            Dalam Proses
        </a>
        <a href="{{ route('supervisor.handovers.index', ['status' => 'selesai']) }}"
           style="{{ request('status') === 'selesai' ? 'background-color: #059669 !important; color: #ffffff !important; border: 1px solid #059669 !important;' : 'background-color: #ffffff !important; color: #334155 !important; border: 1px solid #cbd5e1 !important;' }}"
           class="px-4 py-2 rounded-xl font-bold transition-colors whitespace-nowrap shadow-xs">
            Selesai
        </a>
    </div>

    {{-- ============================================================
         4. DAFTAR DOKUMEN SERAH TERIMA
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider text-2xs">
                        <th class="py-3 px-4 font-bold">No. Dokumen</th>
                        <th class="py-3 px-4 font-bold">Pihak Serah Terima</th>
                        <th class="py-3 px-4 font-bold">Tanggal Efektif</th>
                        <th class="py-3 px-4 font-bold">Status Berita Acara</th>
                        <th class="py-3 px-4 font-bold">Alasan Pergantian</th>
                        <th class="py-3 px-4 font-bold">Disahkan Oleh</th>
                        <th class="py-3 px-4 font-bold text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($handovers as $h)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-4 whitespace-nowrap font-mono font-bold text-indigo-700">
                                {{ $h->nomor_serah_terima }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="font-bold text-slate-700">{{ $h->supervisorLama?->nama ?? 'Supervisor Lama' }}</span>
                                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                    <span class="font-bold text-blue-600">{{ $h->supervisorBaru?->nama ?? 'Supervisor Baru' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-slate-700 font-semibold">
                                {{ $h->tanggal_serah_terima?->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-2xs font-bold border {{ $h->status_badge['bg'] }}">
                                    {{ $h->status_badge['label'] }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-slate-600 max-w-xs">
                                <div class="truncate" title="{{ $h->alasan_pergantian }}">
                                    {{ $h->alasan_pergantian }}
                                </div>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-slate-600">
                                <span class="font-semibold text-slate-800">{{ $h->superAdmin?->nama ?? 'Super Admin' }}</span>
                                <span class="block text-2xs text-purple-600 font-bold">Super Admin</span>
                            </td>
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <a href="{{ route('supervisor.handovers.show', $h->id) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs transition-colors">
                                    <span>Lihat Dokumen</span>
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <p class="text-sm font-semibold text-slate-600">Belum ada dokumen serah terima jabatan.</p>
                                @if(Auth::user()->isSuperAdmin())
                                    <a href="{{ route('supervisor.handovers.create') }}" class="inline-block mt-2 text-xs font-bold text-indigo-600 hover:underline">
                                        + Susun Dokumen Serah Terima Baru
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($handovers->hasPages())
            <div class="px-5 py-4 border-t border-slate-200">
                {{ $handovers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

