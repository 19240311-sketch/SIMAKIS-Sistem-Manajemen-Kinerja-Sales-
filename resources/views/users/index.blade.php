@extends('layouts.app')

@section('title', 'Kelola Pengguna Sistem — SIMAKIS Yamaha JG Purwakarta')
@section('page-title', 'Kelola Akun Pengguna')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    {{-- ================================================================
         HEADER & ACTION PANEL
    ================================================================ --}}
    <div class="rounded-2xl bg-white border border-slate-200 shadow-xs p-5 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                        Manajemen Hak Akses &amp; Otoritas
                    </span>
                    <span class="text-xs text-slate-400 font-medium">Dealer Resmi Yamaha JG Purwakarta</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                    Pengelolaan Akun Pengguna SIMAKIS
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Kelola akun Super Admin, Supervisor Operasional, dan Sales penanggung jawab prospek konsumen.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs transition-colors shadow-2xs">
                    <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    <span>Dashboard</span>
                </a>
                <button type="button" onclick="openCreateUserModal()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition-all active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Tambah Pengguna</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ================================================================
         USERS TABLE CARD
    ================================================================ --}}
    <div class="rounded-2xl bg-white border border-slate-200 shadow-xs overflow-hidden">

        {{-- ---- Card Header: Title + Counter ---- --}}
        <div class="px-5 pt-5 pb-4 border-b border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Daftar Akun Pengguna Terdaftar</h2>
                    <p class="text-xs text-slate-400 mt-0.5" id="usersCountText">
                        Total <span class="font-semibold text-slate-700" id="usersTotalBadge">{{ $totalAllUsers }}</span> akun dalam sistem
                    </p>
                </div>

                {{-- Summary teks saat filter aktif --}}
                <div id="usersSummaryBar" class="hidden text-xs text-slate-500 font-medium bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-xl">
                    <span id="usersSummaryText"></span>
                </div>
            </div>

            {{-- ---- Toolbar: Search + Filters + Per Page ---- --}}
            <div class="mt-4 flex flex-col sm:flex-row gap-2.5">

                {{-- Search Input --}}
                <div class="relative flex-1 min-w-0">
                    <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                    <input
                        id="userSearch"
                        type="text"
                        placeholder="Cari nama, email, atau role..."
                        class="w-full rounded-xl border border-slate-200 bg-white pl-9 pr-9 py-2 text-xs font-medium text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                    >
                    <button type="button" id="clearSearch"
                            class="hidden absolute inset-y-0 right-2.5 flex items-center text-slate-400 hover:text-slate-600 transition"
                            onclick="clearUserSearch()">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                        </svg>
                    </button>
                </div>

                {{-- Filter Role --}}
                <div class="shrink-0">
                    <select id="filterRole"
                            class="rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        <option value="">Semua Role</option>
                        <option value="super_admin">Super Admin</option>
                        <option value="supervisor">Supervisor</option>
                        <option value="sales">Sales</option>
                    </select>
                </div>

                {{-- Filter Status --}}
                <div class="shrink-0">
                    <select id="filterStatus"
                            class="rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                {{-- Per Page --}}
                <div class="shrink-0">
                    <select id="filterPerPage"
                            class="rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 / halaman</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 / halaman</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 / halaman</option>
                    </select>
                </div>

                {{-- Reset Button --}}
                <div class="shrink-0">
                    <button type="button" onclick="resetUserFilters()"
                            id="resetFiltersBtn"
                            class="hidden inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-semibold text-slate-600 transition">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        Reset
                    </button>
                </div>
            </div>
        </div>

        {{-- ---- Loading Indicator ---- --}}
        <div id="usersTableLoader" class="hidden px-5 py-3 bg-blue-50 border-b border-blue-100">
            <div class="flex items-center gap-2 text-xs text-blue-600 font-medium">
                <svg class="h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memuat data akun...
            </div>
        </div>

        {{-- ---- Table ---- --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" style="table-layout: fixed; min-width: 680px;">
                <colgroup>
                    <col style="width: 36%;">  {{-- Pengguna --}}
                    <col style="width: 15%;">  {{-- Role Akses --}}
                    <col style="width: 9%;">   {{-- Prospek --}}
                    <col style="width: 10%;">  {{-- Transaksi --}}
                    <col style="width: 11%;">  {{-- Status --}}
                    <col style="width: 19%;">  {{-- Aksi --}}
                </colgroup>
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider" style="font-size: 10px;">
                        <th class="py-3 px-4 font-bold">Pengguna</th>
                        <th class="py-3 px-3 font-bold">Role Akses</th>
                        <th class="py-3 px-2 font-bold text-center">Prospek</th>
                        <th class="py-3 px-2 font-bold text-center">Transaksi</th>
                        <th class="py-3 px-3 font-bold text-center">Status Akun</th>
                        <th class="py-3 px-4 font-bold text-right">Aksi &amp; Kontrol</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="usersTableBody">
                    @include('users.partials.table_rows', ['users' => $users, 'currentUser' => $currentUser])
                </tbody>
            </table>
        </div>

        {{-- ---- Pagination ---- --}}
        <div id="paginationContainer">
            @include('users.partials.pagination', ['users' => $users, 'perPage' => $perPage])
        </div>

    </div>{{-- /Users Table Card --}}
</div>{{-- /space-y-6 --}}

{{-- ================================================================
     MODAL: TAMBAH PENGGUNA (Rapi, Proporsional & Nyaman Dilihat)
================================================================ --}}
<div id="createUserModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden">
    <div class="w-full max-w-lg rounded-2xl bg-white p-5 sm:p-6 shadow-2xl border border-slate-200 animate-in fade-in zoom-in-95 duration-150">
        {{-- Modal Header --}}
        <div class="flex items-center justify-between border-b border-slate-100 pb-3.5 mb-4">
            <div class="flex items-center gap-2.5">
                <div class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold shrink-0 ring-1 ring-blue-100">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.765l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Tambah Pengguna Baru</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Daftarkan akun pengguna sistem SIMAKIS Yamaha JG Purwakarta</p>
                </div>
            </div>
            <button type="button" onclick="closeCreateUserModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition">
                <svg class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
            </button>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-3.5">
            @csrf
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                       placeholder="Contoh: Rian Pratama"
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition shadow-2xs">
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email (Login) <span class="text-rose-500">*</span></label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                       placeholder="rian@yamaha.co.id"
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition shadow-2xs">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi <span class="text-rose-500">*</span></label>
                <input id="password" name="password" type="password" required
                       placeholder="Minimal 6 karakter"
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition shadow-2xs">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="role" class="block text-xs font-semibold text-slate-700 mb-1">Peran (Role) <span class="text-rose-500">*</span></label>
                    <select id="role" name="role" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition shadow-2xs">
                        <option value="sales">Sales</option>
                        <option value="supervisor">Supervisor</option>
                        @if(Auth::user()->isSuperAdmin())
                            <option value="super_admin">Super Admin</option>
                        @endif
                    </select>
                </div>
                <div>
                    <label for="cabang" class="block text-xs font-semibold text-slate-700 mb-1">Cabang Dealer</label>
                    <input id="cabang" name="cabang" type="text" value="Yamaha JG Purwakarta"
                           class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition shadow-2xs">
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3.5 border-t border-slate-100">
                <button type="button" onclick="closeCreateUserModal()"
                        class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs transition shadow-2xs">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition active:scale-95 inline-flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Daftarkan Akun</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================================================================
     MODAL: UBAH PENGGUNA (Rapi, Proporsional & Nyaman Dilihat)
================================================================ --}}
<div id="editUserModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden">
    <div class="w-full max-w-lg rounded-2xl bg-white p-5 sm:p-6 shadow-2xl border border-slate-200 animate-in fade-in zoom-in-95 duration-150">
        {{-- Modal Header --}}
        <div class="flex items-center justify-between border-b border-slate-100 pb-3.5 mb-4">
            <div class="flex items-center gap-2.5">
                <div class="h-9 w-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold shrink-0 ring-1 ring-indigo-100">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Ubah Data Pengguna</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Perbarui profil pengguna, peran akses, dan status operasional</p>
                </div>
            </div>
            <button type="button" onclick="closeEditUserModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition">
                <svg class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
            </button>
        </div>

        <form id="editUserForm" method="POST" class="space-y-3.5">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_user_name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input id="edit_user_name" name="name" type="text" required
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition shadow-2xs">
            </div>

            <div>
                <label for="edit_user_email" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email (Login) <span class="text-rose-500">*</span></label>
                <input id="edit_user_email" name="email" type="email" required
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition shadow-2xs">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="edit_user_password" class="text-xs font-semibold text-slate-700">Ganti Kata Sandi</label>
                    <span class="text-[11px] text-slate-400 font-normal">Opsional</span>
                </div>
                <input id="edit_user_password" name="password" type="password"
                       placeholder="Kosongkan jika kata sandi tidak diubah"
                       class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition shadow-2xs">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="edit_user_role" class="block text-xs font-semibold text-slate-700 mb-1">Peran (Role) <span class="text-rose-500">*</span></label>
                    <select id="edit_user_role" name="role" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition shadow-2xs">
                        <option value="sales">Sales</option>
                        <option value="supervisor">Supervisor</option>
                        @if(Auth::user()->isSuperAdmin())
                            <option value="super_admin">Super Admin</option>
                        @endif
                    </select>
                </div>

                <div>
                    <label for="edit_user_is_active" class="block text-xs font-semibold text-slate-700 mb-1">Status Akun</label>
                    <select id="edit_user_is_active" name="is_active"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition shadow-2xs">
                        <option value="1">Aktif (Dapat Login)</option>
                        <option value="0">Nonaktif (Akses Dicegah)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="edit_user_cabang" class="block text-xs font-semibold text-slate-700 mb-1">Cabang Dealer</label>
                    <input id="edit_user_cabang" name="cabang" type="text"
                           class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition shadow-2xs">
                </div>
                <div>
                    <label for="edit_user_no_hp" class="block text-xs font-semibold text-slate-700 mb-1">No. WhatsApp / HP</label>
                    <input id="edit_user_no_hp" name="no_hp" type="text"
                           placeholder="Contoh: 08123456789"
                           class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition shadow-2xs">
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3.5 border-t border-slate-100">
                <button type="button" onclick="closeEditUserModal()"
                        class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs transition shadow-2xs">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition active:scale-95 inline-flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================================================================
     JAVASCRIPT: Live Search, Filter, Pagination
================================================================ --}}
<script>
(function () {
    'use strict';

    /* ---- State ---- */
    let _searchTimer = null;
    let _currentPage = 1;
    let _isFetching  = false;

    /* ---- DOM Refs (resolved after DOMContentLoaded) ---- */
    let elSearch, elRole, elStatus, elPerPage, elBody,
        elPagination, elLoader, elSummaryBar, elSummaryText,
        elTotalBadge, elClearBtn, elResetBtn;

    /* ---- Bootstrap ---- */
    document.addEventListener('DOMContentLoaded', function () {
        elSearch      = document.getElementById('userSearch');
        elRole        = document.getElementById('filterRole');
        elStatus      = document.getElementById('filterStatus');
        elPerPage     = document.getElementById('filterPerPage');
        elBody        = document.getElementById('usersTableBody');
        elPagination  = document.getElementById('paginationContainer');
        elLoader      = document.getElementById('usersTableLoader');
        elSummaryBar  = document.getElementById('usersSummaryBar');
        elSummaryText = document.getElementById('usersSummaryText');
        elTotalBadge  = document.getElementById('usersTotalBadge');
        elClearBtn    = document.getElementById('clearSearch');
        elResetBtn    = document.getElementById('resetFiltersBtn');

        /* -- Search: debounce 300ms -- */
        elSearch.addEventListener('input', function () {
            elClearBtn.classList.toggle('hidden', !this.value.trim());
            syncResetBtn();
            clearTimeout(_searchTimer);
            _searchTimer = setTimeout(function () {
                _currentPage = 1;
                fetchUsers(1);
            }, 300);
        });

        /* -- Dropdowns: instant -- */
        [elRole, elStatus, elPerPage].forEach(function (el) {
            el.addEventListener('change', function () {
                syncResetBtn();
                _currentPage = 1;
                fetchUsers(1);
            });
        });
    });

    /* ---- Helpers ---- */
    function syncResetBtn() {
        var active = elSearch.value.trim() || elRole.value || elStatus.value || elPerPage.value !== '10';
        elResetBtn.classList.toggle('hidden', !active);
    }

    function getParams(page) {
        var p = new URLSearchParams();
        if (elSearch.value.trim())  p.set('search',   elSearch.value.trim());
        if (elRole.value)           p.set('role',      elRole.value);
        if (elStatus.value)         p.set('status',    elStatus.value);
        if (elPerPage.value)        p.set('per_page',  elPerPage.value);
        p.set('page', page || _currentPage);
        return p;
    }

    /* ---- Core Fetch ---- */
    function fetchUsers(page) {
        if (_isFetching) return;
        _isFetching = true;

        page = page || _currentPage;
        var params = getParams(page);

        /* Show loader */
        if (elLoader) elLoader.classList.remove('hidden');

        /* Update browser URL bar without reload */
        try {
            window.history.replaceState(null, '', '?' + params.toString());
        } catch(e) {}

        fetch('/users?' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(function (res) {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(function (data) {
            /* Inject rows */
            elBody.innerHTML = data.html || '';

            /* Inject pagination */
            elPagination.innerHTML = data.pagination_html || '';

            /* Update summary */
            if (data.total > 0) {
                elSummaryText.textContent = data.summary_text || '';
                elSummaryBar.classList.remove('hidden');
            } else {
                elSummaryBar.classList.add('hidden');
            }

            /* Update total badge */
            if (elTotalBadge && typeof data.total_all !== 'undefined') {
                elTotalBadge.textContent = data.total_all;
            }

            _currentPage = data.current_page || page;
        })
        .catch(function (err) {
            console.error('Fetch users failed:', err);
            elBody.innerHTML = '<tr><td colspan="6" class="p-8 text-center text-rose-500 text-xs font-medium">Gagal memuat data. Silakan coba lagi.</td></tr>';
        })
        .finally(function () {
            _isFetching = false;
            if (elLoader) elLoader.classList.add('hidden');
        });
    }

    /* ---- Exposed Globals ---- */
    window.fetchUsers = fetchUsers;

    window.goToUserPage = function (page) {
        _currentPage = page;
        fetchUsers(page);
        /* Scroll table into view */
        var card = elBody ? elBody.closest('.rounded-2xl') : null;
        if (card) card.scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    window.resetUserFilters = function () {
        elSearch.value  = '';
        elRole.value    = '';
        elStatus.value  = '';
        elPerPage.value = '10';
        elClearBtn.classList.add('hidden');
        elResetBtn.classList.add('hidden');
        elSummaryBar.classList.add('hidden');
        _currentPage = 1;
        fetchUsers(1);
    };

    window.clearUserSearch = function () {
        elSearch.value = '';
        elClearBtn.classList.add('hidden');
        syncResetBtn();
        _currentPage = 1;
        fetchUsers(1);
    };

    /* ---- Modal Functions ---- */
    window.openCreateUserModal = function () {
        document.getElementById('createUserModal').classList.remove('hidden');
    };
    window.closeCreateUserModal = function () {
        document.getElementById('createUserModal').classList.add('hidden');
    };

    window.openEditUserModal = function (id, name, email, role, isActive, cabang, noHp) {
        cabang = cabang || '';
        noHp   = noHp   || '';
        var modal = document.getElementById('editUserModal');
        var form  = document.getElementById('editUserForm');
        form.action = '/users/' + id;
        document.getElementById('edit_user_name').value      = name;
        document.getElementById('edit_user_email').value     = email;
        document.getElementById('edit_user_password').value  = '';
        document.getElementById('edit_user_role').value      = (role === 'admin' ? 'supervisor' : role);
        document.getElementById('edit_user_is_active').value = isActive;
        document.getElementById('edit_user_cabang').value    = cabang;
        document.getElementById('edit_user_no_hp').value     = noHp;
        modal.classList.remove('hidden');
    };
    window.closeEditUserModal = function () {
        document.getElementById('editUserModal').classList.add('hidden');
    };

}());
</script>
@endsection
