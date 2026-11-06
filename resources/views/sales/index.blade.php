@extends('layouts.app')

@section('title', 'Data Sales & Peringkat Penjualan')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-blue-100 text-[#0A4DF3]">
                    SIMAKIS
                </span>
                <span class="text-xs text-slate-400 font-medium">Dealer Yamaha JG Purwakarta</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-1">
                Data Sales &amp; Peringkat Penjualan
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Pengelolaan profil personil sales, kuota target, realisasi unit, dan peringkat kinerja otomatis.
            </p>
        </div>

        @if(auth()->user()->isSupervisor())
            <div>
                <a href="{{ route('sales.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A4DF3] hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Tambah Sales Baru</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Filters & Search Toolbar -->
    <form method="GET" action="{{ route('sales.index') }}" class="bg-white p-4 rounded-2xl border border-hairline shadow-sm flex flex-wrap items-center justify-between gap-3 text-xs">
        <div class="flex flex-wrap items-center gap-2 flex-1 min-w-[280px]">
            <!-- Search -->
            <div class="relative min-w-[200px]">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, email, no HP..."
                       class="h-9 w-full rounded-xl border border-slate-200 pl-8 pr-3 text-xs placeholder:text-slate-400 focus:border-[#0A4DF3] focus:outline-none">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5 text-slate-400">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
            </div>

            <!-- Bulan -->
            <select name="bulan" class="h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:border-[#0A4DF3] focus:outline-none">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                        Bulan {{ \Carbon\Carbon::create(2026, $m, 1)->locale('id')->isoFormat('MMMM') }}
                    </option>
                @endfor
            </select>

            <!-- Tahun -->
            <select name="tahun" class="h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:border-[#0A4DF3] focus:outline-none">
                @foreach(range(date('Y') - 1, date('Y') + 1) as $y)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                @endforeach
            </select>

            <!-- Status Aktif -->
            <select name="status_aktif" class="h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:border-[#0A4DF3] focus:outline-none">
                <option value="">Semua Status Akun</option>
                <option value="1" {{ $statusAktif === '1' ? 'selected' : '' }}>Aktif Saja</option>
                <option value="0" {{ $statusAktif === '0' ? 'selected' : '' }}>Nonaktif Saja</option>
            </select>

            <!-- Urutkan Penjualan -->
            <select name="sort" class="h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:border-[#0A4DF3] focus:outline-none">
                <option value="desc" {{ $sortOrder === 'desc' ? 'selected' : '' }}>Penjualan Tertinggi &rarr; Terendah</option>
                <option value="asc" {{ $sortOrder === 'asc' ? 'selected' : '' }}>Penjualan Terendah &rarr; Tertinggi</option>
            </select>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="h-9 px-4 rounded-xl bg-[#0A4DF3] text-white font-bold hover:bg-blue-700 transition">
                Terapkan Filter
            </button>
            <a href="{{ route('sales.index') }}" class="h-9 px-3 rounded-xl border border-slate-200 hover:bg-slate-50 flex items-center text-slate-600 font-semibold transition">
                Reset
            </a>
        </div>
    </form>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl border border-hairline shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                        <th class="py-3 px-4 text-center">Peringkat</th>
                        <th class="py-3 px-4">ID Sales</th>
                        <th class="py-3 px-4">Nama Lengkap</th>
                        <th class="py-3 px-4">Email / Akun</th>
                        <th class="py-3 px-4 text-center">Target (Unit)</th>
                        <th class="py-3 px-4 text-center">Terjual (Unit)</th>
                        <th class="py-3 px-4">Pencapaian (%)</th>
                        <th class="py-3 px-4 text-center">Status Kinerja</th>
                        <th class="py-3 px-4 text-center">Status Akun</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($finalSales as $row)
                        <tr class="hover:bg-blue-50/40 transition">
                            <!-- Peringkat -->
                            <td class="py-3.5 px-4 text-center">
                                @if($row['peringkat'] === 1)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-extrabold text-[10px] border border-amber-300">
                                        <svg class="h-3 w-3 text-amber-500 fill-amber-500 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        #1
                                    </span>
                                @elseif($row['peringkat'] === 2)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-extrabold text-[10px] border border-slate-300">
                                        <svg class="h-3 w-3 text-slate-400 fill-slate-400 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        #2
                                    </span>
                                @elseif($row['peringkat'] === 3)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 font-extrabold text-[10px] border border-orange-300">
                                        <svg class="h-3 w-3 text-orange-400 fill-orange-400 shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        #3
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-slate-100 text-slate-600 font-bold text-xs">#{{ $row['peringkat'] }}</span>
                                @endif
                            </td>

                            <!-- ID Sales -->
                            <td class="py-3.5 px-4 font-mono font-semibold text-slate-500">
                                SLS-{{ str_pad($row['user']->id, 3, '0', STR_PAD_LEFT) }}
                            </td>

                            <!-- Nama Lengkap -->
                            <td class="py-3.5 px-4">
                                <a href="{{ route('sales.show', $row['user']->id) }}" class="font-bold text-slate-900 hover:text-[#0A4DF3] transition flex items-center gap-2">
                                    <div class="h-6 w-6 rounded-full bg-blue-100 text-[#0A4DF3] font-bold text-[10px] flex items-center justify-center shrink-0">
                                        {{ $row['user']->avatar_initials }}
                                    </div>
                                    <span>{{ $row['user']->nama }}</span>
                                </a>
                            </td>

                            <!-- Email / Kontak -->
                            <td class="py-3.5 px-4 text-slate-600">
                                <div>{{ $row['user']->email }}</div>
                                <div class="text-[10px] text-slate-400">{{ $row['user']->no_hp ?: '-' }}</div>
                            </td>

                            <!-- Target Unit -->
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-700">
                                {{ $row['target_unit'] }} Unit
                            </td>

                            <!-- Unit Terjual -->
                            <td class="py-3.5 px-4 text-center font-extrabold text-emerald-600 text-sm">
                                {{ $row['unit_terjual'] }} Unit
                            </td>

                            <!-- Pencapaian (%) -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2 min-w-[100px]">
                                    <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-[#0A4DF3] h-1.5 rounded-full" style="width: {{ min(100, $row['persentase']) }}%"></div>
                                    </div>
                                    <span class="font-bold text-slate-800 text-[11px]">{{ $row['persentase'] }}%</span>
                                </div>
                            </td>

                            <!-- Status Kinerja -->
                            <td class="py-3.5 px-4 text-center">
                                @php
                                    $badgeStyle = match($row['status_kinerja']) {
                                        'Sangat Baik' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'Baik' => 'bg-blue-50 text-[#0A4DF3] border-blue-200',
                                        'Cukup' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        default => 'bg-rose-50 text-rose-700 border-rose-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold border {{ $badgeStyle }}">
                                    {{ $row['status_kinerja'] }}
                                </span>
                            </td>

                            <!-- Status Akun -->
                            <td class="py-3.5 px-4 text-center">
                                @if($row['user']->status_aktif)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('sales.show', $row['user']->id) }}"
                                       class="p-1 rounded-lg hover:bg-blue-50 text-slate-500 hover:text-[#0A4DF3] transition"
                                       title="Lihat Detail Kinerja">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>

                                    @if(auth()->user()->isSupervisor())
                                        <a href="{{ route('sales.edit', $row['user']->id) }}"
                                           class="p-1 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-slate-800 transition"
                                           title="Ubah Data Sales">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('sales.toggle', $row['user']->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin mengubah status aktif akun {{ $row['user']->nama }}?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="p-1 rounded-lg hover:bg-rose-50 text-slate-500 hover:text-rose-600 transition"
                                                    title="{{ $row['user']->status_aktif ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-10 text-center text-slate-400 italic">
                                Tidak ditemukan data sales sesuai kriteria pencarian atau filter yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
