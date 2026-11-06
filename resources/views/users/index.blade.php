@extends('layouts.app')

@section('title', 'Kelola Pengguna Sistem — SIMAKIS Yamaha JG Purwakarta')
@section('page-title', 'Kelola Akun Pengguna')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Header & Action Panel -->
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
                @if(Auth::user()->isSuperAdmin())
                <a href="{{ route('supervisor.history') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs border border-indigo-200 shadow-xs transition-all active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
                    </svg>
                    <span>Pergantian Supervisor</span>
                </a>
                @endif

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

    <!-- Users Table Card -->
    <div class="rounded-2xl bg-white border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900">Daftar Akun Pengguna Terdaftar</h2>
                <p class="text-xs text-slate-400">Total {{ $users->count() }} akun dalam sistem</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider text-2xs">
                        <th class="py-3 px-4 font-bold">Pengguna</th>
                        <th class="py-3 px-4 font-bold">Role Akses</th>
                        <th class="py-3 px-4 font-bold text-center">Prospek</th>
                        <th class="py-3 px-4 font-bold text-center">Transaksi</th>
                        <th class="py-3 px-4 font-bold">Status Akun</th>
                        <th class="py-3 px-4 font-bold text-right">Aksi &amp; Kontrol</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full flex items-center justify-center font-extrabold text-xs
                                        @if($u->peran === 'super_admin') bg-purple-100 text-purple-800 ring-2 ring-purple-200
                                        @elseif($u->peran === 'supervisor') bg-blue-100 text-blue-800 ring-2 ring-blue-200
                                        @else bg-emerald-100 text-emerald-800 ring-2 ring-emerald-200 @endif">
                                        {{ $u->avatar_initials }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 flex items-center gap-1.5">
                                            <span>{{ $u->nama }}</span>
                                            @if($u->id === auth()->id())
                                                <span class="px-1.5 py-0.2 rounded text-3xs font-black bg-blue-600 text-white uppercase">Anda</span>
                                            @endif
                                        </div>
                                        <div class="text-2xs text-slate-400 mt-0.5">
                                            {{ $u->email }} &bull; {{ $u->cabang ?? 'Purwakarta' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($u->peran === 'super_admin')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-2xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                                        Super Admin
                                    </span>
                                @elseif($u->peran === 'supervisor' || $u->peran === 'admin')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-2xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                                        Supervisor
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-2xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                        Sales
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold font-mono text-slate-800">
                                {{ $u->prospects_count }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold font-mono text-slate-800">
                                {{ $u->sales_transactions_count }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($u->status_aktif)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-2xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-2xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap space-x-2">
                                {{-- Tombol Ubah --}}
                                @if(Auth::user()->isSuperAdmin() || ($u->peran !== 'super_admin' && !Auth::user()->isSales()))
                                    <button type="button"
                                            onclick="openEditUserModal({{ $u->id }}, '{{ addslashes($u->nama) }}', '{{ addslashes($u->email) }}', '{{ $u->peran }}', {{ $u->status_aktif ? 1 : 0 }}, '{{ addslashes($u->cabang ?? '') }}', '{{ addslashes($u->no_hp ?? '') }}')"
                                            class="px-2.5 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-2xs transition-colors">
                                        Ubah Data
                                    </button>
                                @endif

                                {{-- Toggle Aktif / Nonaktif --}}
                                @if(auth()->id() !== $u->id)
                                    <form action="{{ route('users.update', $u->id) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('Apakah Anda yakin ingin {{ $u->status_aktif ? 'menonaktifkan' : 'mengaktifkan' }} akun {{ $u->nama }}?');">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="name" value="{{ $u->nama }}">
                                        <input type="hidden" name="email" value="{{ $u->email }}">
                                        <input type="hidden" name="role" value="{{ $u->peran }}">
                                        <input type="hidden" name="is_active" value="{{ $u->status_aktif ? 0 : 1 }}">
                                        <button type="submit"
                                                class="px-2.5 py-1.5 rounded-lg font-bold text-2xs transition-colors {{ $u->status_aktif ? 'bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-700' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700' }}">
                                            {{ $u->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @endif

                                {{-- Hapus Aman (Super Admin Only) --}}
                                @if(Auth::user()->isSuperAdmin() && auth()->id() !== $u->id)
                                    @php
                                        $hasHistory = ($u->prospects_count > 0 || $u->sales_transactions_count > 0 || $u->supervisor_histories_count > 0);
                                    @endphp
                                    @if($hasHistory)
                                        <button type="button"
                                                title="Akun ini tidak dapat dihapus karena memiliki riwayat operasional (transaksi/prospek/jabatan). Silakan nonaktifkan akun."
                                                onclick="alert('Akun {{ addslashes($u->nama) }} tidak dapat dihapus dari database karena memiliki riwayat operasional ({{ $u->prospects_count }} prospek, {{ $u->sales_transactions_count }} transaksi). Silakan gunakan tombol Nonaktifkan agar data historis tetap aman.');"
                                                class="px-2 py-1.5 rounded-lg bg-slate-100 text-slate-400 cursor-not-allowed font-medium text-2xs">
                                            Terkunci
                                        </button>
                                    @else
                                        <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('PENTING: Anda yakin ingin menghapus permanen akun {{ $u->nama }}? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-2.5 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-2xs transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Belum ada data pengguna terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Pengguna -->
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
                <label for="name" class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                       placeholder="Contoh: Rian Pratama"
                       class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="email" class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Alamat Email (Login) <span class="text-rose-500">*</span></label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                       placeholder="rian@yamaha.co.id"
                       class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="password" class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Kata Sandi <span class="text-rose-500">*</span></label>
                <input id="password" name="password" type="password" required
                       placeholder="Minimal 6 karakter"
                       class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="role" class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Peran (Role) <span class="text-rose-500">*</span></label>
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
                    <label for="cabang" class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Cabang Dealer</label>
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

<!-- Modal Ubah Pengguna -->
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
                <label for="edit_user_name" class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input id="edit_user_name" name="name" type="text" required
                       class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="edit_user_email" class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                <input id="edit_user_email" name="email" type="email" required
                       class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="edit_user_password" class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Ganti Kata Sandi (Opsional)</label>
                <input id="edit_user_password" name="password" type="password"
                       placeholder="Kosongkan jika sandi tidak diubah"
                       class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit_user_role" class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Peran (Role) <span class="text-rose-500">*</span></label>
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
                    <label for="edit_user_is_active" class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Status Akun</label>
                    <select id="edit_user_is_active" name="is_active"
                            class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="1">Aktif (Dapat Login)</option>
                        <option value="0">Nonaktif (Akses Dicegah)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit_user_cabang" class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">Cabang</label>
                    <input id="edit_user_cabang" name="cabang" type="text"
                           class="w-full rounded-xl border border-slate-200 px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="edit_user_no_hp" class="block text-2xs font-bold text-slate-500 uppercase tracking-wider mb-1">No. WhatsApp/HP</label>
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

<script>
    function openCreateUserModal() {
        document.getElementById('createUserModal').classList.remove('hidden');
    }

    function closeCreateUserModal() {
        document.getElementById('createUserModal').classList.add('hidden');
    }

    function openEditUserModal(id, name, email, role, isActive, cabang = '', noHp = '') {
        const modal = document.getElementById('editUserModal');
        const form = document.getElementById('editUserForm');
        form.action = `/users/${id}`;
        document.getElementById('edit_user_name').value = name;
        document.getElementById('edit_user_email').value = email;
        document.getElementById('edit_user_password').value = '';
        document.getElementById('edit_user_role').value = (role === 'admin' ? 'supervisor' : role);
        document.getElementById('edit_user_is_active').value = isActive;
        document.getElementById('edit_user_cabang').value = cabang;
        document.getElementById('edit_user_no_hp').value = noHp;
        modal.classList.remove('hidden');
    }

    function closeEditUserModal() {
        document.getElementById('editUserModal').classList.add('hidden');
    }
</script>
@endsection
