@extends('layouts.app')

@section('title', 'Tambah Data Sales Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                Tambah Data Sales Baru
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Pendaftaran akun personil tim sales baru di dealer Yamaha JG Purwakarta.
            </p>
        </div>
        <a href="{{ route('sales.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-hairline shadow-sm p-6 sm:p-8">
        <form action="{{ route('sales.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="nama" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Nama Lengkap Sales <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                       placeholder="Contoh: Muhammad Rizky"
                       class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs focus:border-[#0A4DF3] focus:outline-none">
                @error('nama') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Alamat Email (Username Login) <span class="text-rose-500">*</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       placeholder="sales@yamaha.co.id"
                       class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs focus:border-[#0A4DF3] focus:outline-none">
                @error('email') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="no_hp" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Nomor HP / WhatsApp
                    </label>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}"
                           placeholder="0812xxxxxxxx"
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs focus:border-[#0A4DF3] focus:outline-none">
                    @error('no_hp') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="cabang" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Cabang Dealer
                    </label>
                    <input type="text" id="cabang" name="cabang" value="{{ old('cabang', 'Yamaha JG Purwakarta') }}"
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs focus:border-[#0A4DF3] focus:outline-none">
                    @error('cabang') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Kata Sandi Akun <span class="text-rose-500">*</span>
                </label>
                <input type="password" id="password" name="password" required minlength="6"
                       placeholder="Minimal 6 karakter"
                       class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs focus:border-[#0A4DF3] focus:outline-none">
                @error('password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('sales.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0A4DF3] hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition">
                    Simpan Sales Baru
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
