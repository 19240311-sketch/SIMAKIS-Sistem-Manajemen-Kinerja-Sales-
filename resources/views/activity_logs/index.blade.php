@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem — SIMAKIS Yamaha JG Purwakarta')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    {{-- ============================================================
         1. TOP HEADER & DESKRIPSI
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                        Audit &amp; Security Trail
                    </span>
                    <span class="text-xs text-slate-400 font-medium">SIMAKIS &bull; Yamaha JG Purwakarta</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                    Log Aktivitas Sistem
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    @if(Auth::user()->isSupervisor())
                        Seluruh rekaman jejak audit sistem: login/logout, manipulasi data transaksi, prospek, pengguna, serta pergantian wewenang pimpinan.
                    @else
                        Riwayat aktivitas kerja pribadi Anda yang tercatat secara aman dalam sistem.
                    @endif
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('activity-logs.index') }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    <span>Reset Filter</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ============================================================
         2. STATISTIK RINGKAS
         ============================================================ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4 flex items-center justify-between">
            <div>
                <span class="text-2xs font-bold text-slate-400 uppercase tracking-wider">Total Catatan Log</span>
                <div class="text-2xl font-black text-slate-900 mt-1">{{ number_format($stats['total'] ?? 0) }}</div>
            </div>
            <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4 flex items-center justify-between">
            <div>
                <span class="text-2xs font-bold text-slate-400 uppercase tracking-wider">Aktivitas Hari Ini</span>
                <div class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($stats['hari_ini'] ?? 0) }}</div>
            </div>
            <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4 flex items-center justify-between">
            <div>
                <span class="text-2xs font-bold text-slate-400 uppercase tracking-wider">Operasional Sales</span>
                <div class="text-2xl font-black text-indigo-600 mt-1">{{ number_format($stats['operasional'] ?? 0) }}</div>
            </div>
            <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6H2.25m0 0H3m0 0h1.5a.75.75 0 01.75.75V9m-3-3v3m0 0h1.5a.75.75 0 01.75.75v3m-3-3v3m0 0h1.5a.75.75 0 01.75.75v3m-3-3v3m0 0h1.5a.75.75 0 01.75.75v3" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4 flex items-center justify-between">
            <div>
                <span class="text-2xs font-bold text-slate-400 uppercase tracking-wider">Aktivitas Autentikasi</span>
                <div class="text-2xl font-black text-purple-600 mt-1">{{ number_format($stats['auth'] ?? 0) }}</div>
            </div>
            <div class="h-10 w-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- ============================================================
         3. FORM FILTER & PENCARIAN
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
        <form method="GET" action="{{ route('activity-logs.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">

                {{-- Keyword Search --}}
                <div class="lg:col-span-1">
                    <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari deskripsi, nama..."
                           class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Filter Pengguna (Super Admin & Supervisor) --}}
                @if(Auth::user()->isSupervisor())
                <div>
                    <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Pengguna</label>
                    <select name="user_id" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Pengguna</option>
                        @foreach($usersList as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->nama }} ({{ $u->role_label }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Filter Role (Super Admin & Supervisor) --}}
                @if(Auth::user()->isSupervisor())
                <div>
                    <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Role Pengguna</label>
                    <select name="role" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Role</option>
                        <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="supervisor" {{ request('role') === 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                        <option value="sales" {{ request('role') === 'sales' ? 'selected' : '' }}>Sales</option>
                    </select>
                </div>
                @endif

                {{-- Filter Modul --}}
                <div>
                    <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Modul Sistem</label>
                    <select name="modul" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Modul</option>
                        @foreach($modulesList as $key => $label)
                            <option value="{{ $key }}" {{ request('modul') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Jenis Aktivitas --}}
                <div>
                    <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Jenis Aktivitas</label>
                    <select name="jenis_aktivitas" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Jenis</option>
                        @foreach($activityTypes as $typeKey => $typeLabel)
                            <option value="{{ $typeKey }}" {{ request('jenis_aktivitas') === $typeKey ? 'selected' : '' }}>{{ $typeLabel }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Rentang Tanggal Mulai --}}
                <div>
                    <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Rentang Tanggal Selesai --}}
                <div>
                    <label class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="submit"
                        class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition-colors flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <span>Terapkan Filter</span>
                </button>
            </div>
        </form>
    </div>

    {{-- ============================================================
         4. TABEL LOG AKTIVITAS
         ============================================================ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider text-2xs">
                        <th class="py-3 px-4 font-bold w-12 text-center">No.</th>
                        <th class="py-3 px-4 font-bold">Pengguna</th>
                        <th class="py-3 px-4 font-bold">Role</th>
                        <th class="py-3 px-4 font-bold">Aktivitas</th>
                        <th class="py-3 px-4 font-bold">Modul</th>
                        <th class="py-3 px-4 font-bold">Deskripsi Perubahan</th>
                        <th class="py-3 px-4 font-bold whitespace-nowrap">Tanggal &amp; Waktu</th>
                        <th class="py-3 px-4 font-bold text-center w-20">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $index => $log)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-4 text-center font-medium text-slate-400">
                                {{ $logs->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap font-bold text-slate-900">
                                <div class="flex items-center gap-2">
                                    <div class="h-7 w-7 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-extrabold text-2xs">
                                        {{ strtoupper(substr($log->user_nama ?? 'S', 0, 2)) }}
                                    </div>
                                    <span>{{ $log->user_nama ?? 'Sistem' }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-2xs font-bold
                                    @if($log->user_peran === 'super_admin') bg-purple-100 text-purple-800 border border-purple-200
                                    @elseif($log->user_peran === 'supervisor') bg-blue-100 text-blue-800 border border-blue-200
                                    @elseif($log->user_peran === 'sales') bg-emerald-100 text-emerald-800 border border-emerald-200
                                    @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $log->user_peran ?? 'guest')) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-md text-2xs font-bold
                                    @if(in_array($log->jenis_aktivitas, ['login', 'login_failed'])) bg-amber-50 text-amber-700 border border-amber-200
                                    @elseif($log->jenis_aktivitas === 'tambah') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif($log->jenis_aktivitas === 'ubah') bg-blue-50 text-blue-700 border border-blue-200
                                    @elseif($log->jenis_aktivitas === 'hapus') bg-rose-50 text-rose-700 border border-rose-200
                                    @elseif($log->jenis_aktivitas === 'ganti_jabatan') bg-purple-50 text-purple-700 border border-purple-200
                                    @else bg-slate-50 text-slate-700 border border-slate-200 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $log->jenis_aktivitas)) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap font-semibold text-slate-700">
                                {{ ucfirst($log->modul) }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 max-w-sm">
                                <div class="line-clamp-2" title="{{ $log->deskripsi }}">
                                    {{ $log->deskripsi }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-500">
                                <div class="font-medium text-slate-800">{{ $log->created_at?->translatedFormat('d M Y') }}</div>
                                <div class="text-2xs text-slate-400">{{ $log->created_at?->format('H:i:s') }} WIB</div>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <button type="button"
                                        onclick="showLogDetail({{ json_encode($log) }})"
                                        class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-700 font-bold text-2xs transition-colors">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <svg class="h-10 w-10 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                <p class="text-sm font-semibold text-slate-600">Tidak ada riwayat aktivitas yang sesuai filter.</p>
                                <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau tanggal yang dipilih.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-5 py-4 border-t border-slate-200">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</div>

{{-- ============================================================
     5. MODAL DETAIL LOG AKTIVITAS (JSON DIFF & METADATA)
     ============================================================ --}}
<div id="logDetailModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Detail Rekaman Log Aktivitas</h3>
                <p id="modalLogId" class="text-2xs text-slate-400 mt-0.5">ID Log: -</p>
            </div>
            <button type="button" onclick="closeLogModal()" class="text-slate-400 hover:text-slate-600 p-1">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="space-y-4 mt-4 text-xs">
            <!-- Metadata Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                <div>
                    <span class="text-2xs text-slate-400 font-bold uppercase">Pengguna</span>
                    <p id="modalUser" class="font-bold text-slate-800 mt-0.5">-</p>
                </div>
                <div>
                    <span class="text-2xs text-slate-400 font-bold uppercase">Role</span>
                    <p id="modalRole" class="font-bold text-slate-800 mt-0.5">-</p>
                </div>
                <div>
                    <span class="text-2xs text-slate-400 font-bold uppercase">Waktu Aktivitas</span>
                    <p id="modalTime" class="font-bold text-slate-800 mt-0.5">-</p>
                </div>
                <div>
                    <span class="text-2xs text-slate-400 font-bold uppercase">Modul</span>
                    <p id="modalModule" class="font-bold text-slate-800 mt-0.5">-</p>
                </div>
                <div>
                    <span class="text-2xs text-slate-400 font-bold uppercase">Jenis Aksi</span>
                    <p id="modalType" class="font-bold text-slate-800 mt-0.5">-</p>
                </div>
                <div>
                    <span class="text-2xs text-slate-400 font-bold uppercase">IP Address</span>
                    <p id="modalIp" class="font-bold text-slate-800 mt-0.5">-</p>
                </div>
            </div>

            <!-- Deskripsi Lengkap -->
            <div>
                <span class="text-2xs text-slate-400 font-bold uppercase">Deskripsi Operasi</span>
                <p id="modalDescription" class="mt-1 p-3 bg-slate-50 rounded-xl border border-slate-100 text-slate-800 leading-relaxed font-medium">
                    -
                </p>
            </div>

            <!-- Data Diff (Sebelum vs Sesudah) jika ada -->
            <div id="modalDiffContainer" class="space-y-3 hidden">
                <span class="text-2xs text-slate-400 font-bold uppercase">Perubahan Data (Snapshot Diff)</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <div class="text-2xs font-bold text-rose-700 bg-rose-50 px-2 py-1 rounded-t-lg border border-b-0 border-rose-200">
                            Data Sebelum Perubahan:
                        </div>
                        <pre id="modalBeforeData" class="p-3 bg-slate-900 text-rose-300 font-mono text-2xs rounded-b-lg overflow-x-auto max-h-48"></pre>
                    </div>
                    <div>
                        <div class="text-2xs font-bold text-emerald-700 bg-emerald-50 px-2 py-1 rounded-t-lg border border-b-0 border-emerald-200">
                            Data Sesudah Perubahan:
                        </div>
                        <pre id="modalAfterData" class="p-3 bg-slate-900 text-emerald-300 font-mono text-2xs rounded-b-lg overflow-x-auto max-h-48"></pre>
                    </div>
                </div>
            </div>

            <!-- User Agent -->
            <div>
                <span class="text-2xs text-slate-400 font-bold uppercase">Informasi Browser / User Agent</span>
                <p id="modalUserAgent" class="mt-1 font-mono text-2xs text-slate-500 break-all p-2 bg-slate-50 rounded-lg">
                    -
                </p>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end">
            <button type="button" onclick="closeLogModal()"
                    class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function showLogDetail(log) {
        document.getElementById('modalLogId').innerText = 'ID Log #' + log.id;
        document.getElementById('modalUser').innerText = log.user_nama || 'Sistem / Anonim';
        document.getElementById('modalRole').innerText = (log.user_peran || '-').toUpperCase();
        document.getElementById('modalTime').innerText = log.created_at || '-';
        document.getElementById('modalModule').innerText = (log.modul || '-').toUpperCase();
        document.getElementById('modalType').innerText = (log.jenis_aktivitas || '-').toUpperCase();
        document.getElementById('modalIp').innerText = log.ip_address || '-';
        document.getElementById('modalDescription').innerText = log.deskripsi || '-';
        document.getElementById('modalUserAgent').innerText = log.user_agent || '-';

        const diffContainer = document.getElementById('modalDiffContainer');
        const beforeDataEl = document.getElementById('modalBeforeData');
        const afterDataEl = document.getElementById('modalAfterData');

        if (log.data_sebelum || log.data_sesudah) {
            diffContainer.classList.remove('hidden');
            beforeDataEl.innerText = log.data_sebelum ? JSON.stringify(log.data_sebelum, null, 2) : '(Kosong)';
            afterDataEl.innerText = log.data_sesudah ? JSON.stringify(log.data_sesudah, null, 2) : '(Kosong)';
        } else {
            diffContainer.classList.add('hidden');
        }

        document.getElementById('logDetailModal').classList.remove('hidden');
    }

    function closeLogModal() {
        document.getElementById('logDetailModal').classList.add('hidden');
    }
</script>
@endsection

