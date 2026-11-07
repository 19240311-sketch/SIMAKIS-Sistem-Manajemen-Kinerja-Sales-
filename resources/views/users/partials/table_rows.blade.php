@forelse($users as $u)
    <tr class="hover:bg-slate-50/70 transition-colors border-b border-slate-100 last:border-b-0">
        {{-- 1. Kolom Pengguna --}}
        <td class="py-3 px-4">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-full flex items-center justify-center font-extrabold text-xs shrink-0
                    @if($u->peran === 'super_admin') bg-purple-100 text-purple-800 ring-2 ring-purple-200
                    @elseif($u->peran === 'supervisor') bg-blue-100 text-blue-800 ring-2 ring-blue-200
                    @else bg-emerald-100 text-emerald-800 ring-2 ring-emerald-200 @endif">
                    {{ $u->avatar_initials }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="font-bold text-slate-900 flex items-center gap-1.5 flex-wrap">
                        <span class="truncate max-w-[180px] sm:max-w-[220px]" title="{{ $u->nama }}">{{ $u->nama }}</span>
                        @if($u->id === auth()->id())
                            <span class="px-1.5 py-0.2 rounded text-[10px] font-black bg-blue-600 text-white uppercase tracking-wider">Anda</span>
                        @endif
                    </div>
                    <div class="text-[11px] text-slate-400 mt-0.5 truncate max-w-[200px] sm:max-w-[260px]" title="{{ $u->email }} • {{ $u->cabang ?? 'Yamaha JG Purwakarta' }}">
                        <span>{{ $u->email }}</span>
                        @if($u->cabang)
                            <span class="text-slate-300 mx-1">&bull;</span>
                            <span class="text-slate-500">{{ $u->cabang }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </td>

        {{-- 2. Kolom Role Akses --}}
        <td class="py-3 px-3 whitespace-nowrap">
            @if($u->peran === 'super_admin')
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800 border border-purple-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-purple-600"></span>
                    Super Admin
                </span>
            @elseif($u->peran === 'supervisor' || $u->peran === 'admin')
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                    Supervisor
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                    Sales
                </span>
            @endif
        </td>

        {{-- 3. Kolom Prospek --}}
        <td class="py-3 px-2 text-center whitespace-nowrap">
            <span class="font-bold font-mono text-slate-800 text-xs px-2 py-0.5 rounded-md bg-slate-100">
                {{ number_format($u->prospects_count ?? 0) }}
            </span>
        </td>

        {{-- 4. Kolom Transaksi --}}
        <td class="py-3 px-2 text-center whitespace-nowrap">
            <span class="font-bold font-mono text-slate-800 text-xs px-2 py-0.5 rounded-md bg-slate-100">
                {{ number_format($u->sales_transactions_count ?? 0) }}
            </span>
        </td>

        {{-- 5. Kolom Status Akun --}}
        <td class="py-3 px-3 text-center whitespace-nowrap">
            @if($u->status_aktif)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Aktif
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                    Nonaktif
                </span>
            @endif
        </td>

        {{-- 6. Kolom Aksi & Kontrol --}}
        <td class="py-3 px-4 text-right whitespace-nowrap">
            <div class="inline-flex items-center justify-end gap-1.5">
                {{-- Tombol Ubah Data --}}
                @if(Auth::user()->isSuperAdmin() || ($u->peran !== 'super_admin' && !Auth::user()->isSales()))
                    <button type="button"
                            onclick="openEditUserModal({{ $u->id }}, '{{ addslashes($u->nama) }}', '{{ addslashes($u->email) }}', '{{ $u->peran }}', {{ $u->status_aktif ? 1 : 0 }}, '{{ addslashes($u->cabang ?? '') }}', '{{ addslashes($u->no_hp ?? '') }}')"
                            title="Ubah data akun {{ $u->nama }}"
                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-2xs transition-colors border border-blue-200 shadow-2xs">
                        <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                        <span>Ubah</span>
                    </button>
                @endif

                {{-- Tombol Toggle Status (Aktif / Nonaktif) --}}
                @if(auth()->id() !== $u->id && (Auth::user()->isSuperAdmin() || $u->peran !== 'super_admin'))
                    <form action="{{ route('users.update', $u->id) }}" method="POST" class="inline-block m-0"
                          onsubmit="return confirm('Apakah Anda yakin ingin {{ $u->status_aktif ? 'menonaktifkan' : 'mengaktifkan' }} akun {{ $u->nama }}?');">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="name" value="{{ $u->nama }}">
                        <input type="hidden" name="email" value="{{ $u->email }}">
                        <input type="hidden" name="role" value="{{ $u->peran }}">
                        <input type="hidden" name="is_active" value="{{ $u->status_aktif ? 0 : 1 }}">
                        <button type="submit"
                                title="{{ $u->status_aktif ? 'Nonaktifkan akun ini' : 'Aktifkan kembali akun ini' }}"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg font-bold text-2xs transition-colors border shadow-2xs {{ $u->status_aktif ? 'bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-700 border-slate-200' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border-emerald-200' }}">
                            @if($u->status_aktif)
                                <svg class="h-3 w-3 shrink-0 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                                <span>Nonaktifkan</span>
                            @else
                                <svg class="h-3 w-3 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span>Aktifkan</span>
                            @endif
                        </button>
                    </form>
                @endif

                {{-- Tombol Hapus (Super Admin Only) --}}
                @if(Auth::user()->isSuperAdmin() && auth()->id() !== $u->id)
                    @php
                        $hasHistory = (($u->prospects_count ?? 0) > 0 || ($u->sales_transactions_count ?? 0) > 0);
                    @endphp
                    @if($hasHistory)
                        <button type="button"
                                title="Akun ini tidak dapat dihapus karena memiliki riwayat operasional ({{ $u->prospects_count }} prospek, {{ $u->sales_transactions_count }} transaksi). Silakan gunakan tombol Nonaktifkan."
                                onclick="alert('Akun {{ addslashes($u->nama) }} tidak dapat dihapus dari database karena memiliki riwayat operasional ({{ $u->prospects_count }} prospek, {{ $u->sales_transactions_count }} transaksi). Silakan gunakan tombol Nonaktifkan agar data historis tetap aman.');"
                                class="inline-flex items-center gap-1 px-2 py-1.5 rounded-lg bg-slate-100 text-slate-400 cursor-not-allowed font-medium text-2xs border border-slate-200">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                            <span>Terkunci</span>
                        </button>
                    @else
                        <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="inline-block m-0"
                              onsubmit="return confirm('PENTING: Anda yakin ingin menghapus permanen akun {{ $u->nama }}? Tindakan ini tidak dapat dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    title="Hapus permanen akun ini"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-2xs transition-colors border border-rose-200 shadow-2xs">
                                <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                <span>Hapus</span>
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="p-12 text-center">
            <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                <div class="h-12 w-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Tidak Ada Akun Ditemukan</h3>
                <p class="text-xs text-slate-400 mt-1">
                    Tidak ditemukan akun pengguna yang cocok dengan kriteria pencarian atau filter yang dipilih.
                </p>
                <button type="button" onclick="resetUserFilters()"
                        class="mt-4 px-3.5 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs transition-colors inline-flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    <span>Reset Pencarian &amp; Filter</span>
                </button>
            </div>
        </td>
    </tr>
@endforelse

