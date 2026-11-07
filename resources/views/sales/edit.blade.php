@extends('layouts.app')

@section('title', 'Ubah Data Sales: ' . $sale->nama)
@section('page-title', 'Edit Data Sales')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                Ubah Data Sales: {{ $sale->nama }}
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Perbarui informasi akun, kontak, atau status aktif personil sales.
            </p>
        </div>
        <a href="{{ route('sales.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs shadow-2xs transition">
            <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span>Kembali ke Data Sales</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-hairline shadow-sm p-6 sm:p-8">
        <form action="{{ route('sales.update', $sale->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="nama" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Nama Lengkap Sales <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $sale->nama) }}" required
                       class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs focus:border-[#0A4DF3] focus:outline-none">
                @error('nama') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Alamat Email (Username Login) <span class="text-rose-500">*</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email', $sale->email) }}" required
                       class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs focus:border-[#0A4DF3] focus:outline-none">
                @error('email') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="no_hp" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Nomor HP / WhatsApp
                    </label>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $sale->no_hp) }}"
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs focus:border-[#0A4DF3] focus:outline-none">
                    @error('no_hp') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="cabang" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Cabang Dealer
                    </label>
                    <input type="text" id="cabang" name="cabang" value="{{ old('cabang', $sale->cabang) }}"
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs focus:border-[#0A4DF3] focus:outline-none">
                    @error('cabang') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="status_aktif" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Status Akun <span class="text-rose-500">*</span>
                </label>
                <select id="status_aktif" name="status_aktif" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs font-semibold focus:border-[#0A4DF3] focus:outline-none">
                    <option value="1" {{ old('status_aktif', $sale->status_aktif) ? 'selected' : '' }}>Aktif (Bisa Login & Mengelola Data)</option>
                    <option value="0" {{ !old('status_aktif', $sale->status_aktif) ? 'selected' : '' }}>Nonaktif (Akun Diblokir)</option>
                </select>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Kata Sandi Baru (Kosongkan bila tidak ingin diubah)
                </label>
                <input type="password" id="password" name="password" minlength="6"
                       placeholder="Minimal 6 karakter"
                       class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs focus:border-[#0A4DF3] focus:outline-none">
                @error('password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('sales.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0A4DF3] hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
