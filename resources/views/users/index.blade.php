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

            <div class="flex flex-wrap items-center gap-2.5">
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
     MODAL: TAMBAH PENGGUNA
================================================================ --}}
<div id="createUserModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Tambah Pengguna Baru</h3>
                <p class="text-xs text-slate-400 mt-0.5">Daftarkan akun pengguna SIMAKIS Yamaha JG Purwakarta</p>
            </div>
            <button type="button" onclick="closeCreateUserModal()" class="text-slate-400 hover:text-slate-600 p-1">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
            </button>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label for="name" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                       placeholder="Contoh: Rian Pratama"
                       class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="email" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Alamat Email (Login) <span class="text-rose-500">*</span></label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                       placeholder="rian@yamaha.co.id"
                       class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="password" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Kata Sandi <span class="text-rose-500">*</span></label>
                <input id="password" name="password" type="password" required
                       placeholder="Minimal 6 karakter"
                       class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="role" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Peran (Role) <span class="text-rose-500">*</span></label>
                    <select id="role" name="role" required
                            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="sales">Sales</option>
                        <option value="supervisor">Supervisor</option>
                        @if(Auth::user()->isSuperAdmin())
                            <option value="super_admin">Super Admin</option>
                        @endif
                    </select>
                </div>
                <div>
                    <label for="cabang" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Cabang Dealer</label>
                    <input id="cabang" name="cabang" type="text" value="Yamaha JG Purwakarta"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeCreateUserModal()"
                        class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition-colors">
                    Daftarkan Akun
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================================================================
     MODAL: UBAH PENGGUNA
================================================================ --}}
<div id="editUserModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Ubah Data Pengguna</h3>
                <p class="text-xs text-slate-400 mt-0.5">Perbarui profil, hak akses, atau status akun</p>
            </div>
            <button type="button" onclick="closeEditUserModal()" class="text-slate-400 hover:text-slate-600 p-1">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
            </button>
        </div>

        <form id="editUserForm" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_user_name" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input id="edit_user_name" name="name" type="text" required
                       class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="edit_user_email" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                <input id="edit_user_email" name="email" type="email" required
                       class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="edit_user_password" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Ganti Kata Sandi (Opsional)</label>
                <input id="edit_user_password" name="password" type="password"
                       placeholder="Kosongkan jika sandi tidak diubah"
                       class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit_user_role" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Peran (Role) <span class="text-rose-500">*</span></label>
                    <select id="edit_user_role" name="role" required
                            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="sales">Sales</option>
                        <option value="supervisor">Supervisor</option>
                        @if(Auth::user()->isSuperAdmin())
                            <option value="super_admin">Super Admin</option>
                        @endif
                    </select>
                </div>

                <div>
                    <label for="edit_user_is_active" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status Akun</label>
                    <select id="edit_user_is_active" name="is_active"
                            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="1">Aktif (Dapat Login)</option>
                        <option value="0">Nonaktif (Akses Dicegah)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit_user_cabang" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Cabang</label>
                    <input id="edit_user_cabang" name="cabang" type="text"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="edit_user_no_hp" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">No. WhatsApp/HP</label>
                    <input id="edit_user_no_hp" name="no_hp" type="text"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeEditUserModal()"
                        class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition-colors">
                    Simpan Perubahan
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
