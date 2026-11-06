@extends('layouts.app')

@section('title', 'Profil Saya — SIMAKIS Yamaha JG Purwakarta')
@section('page-title', 'Profil Saya')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 pb-16">

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="rounded-2xl bg-emerald-50 border-2 border-emerald-300 p-4 text-emerald-900 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-emerald-950">Berhasil!</h4>
                    <p class="text-xs text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 p-1">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-2xl bg-rose-50 border-2 border-rose-300 p-4 text-rose-900 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-rose-600 text-white font-bold">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-rose-950">Perhatian: Terjadi Kesalahan</h4>
                    <ul class="text-xs text-rose-800 mt-1 list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Profile Header Hero Card -->
    <div class="rounded-2xl bg-gradient-to-r from-blue-950 via-[#1E3A8A] to-indigo-900 p-6 sm:p-8 text-white shadow-md border border-blue-900"
         style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #172554 100%);">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
            <!-- Avatar Display -->
            <div class="relative shrink-0">
                @if($user->foto_url)
                    <img src="{{ $user->foto_url }}" alt="{{ $user->name }}"
                         class="h-24 w-24 sm:h-28 sm:w-28 rounded-2xl object-cover border-4 border-white/30 shadow-xl">
                @else
                    <div class="flex h-24 w-24 sm:h-28 sm:w-28 items-center justify-center rounded-2xl bg-white/15 text-white font-black text-3xl sm:text-4xl border-4 border-white/30 backdrop-blur-md shadow-xl tracking-wider">
                        {{ $user->avatar_initials }}
                    </div>
                @endif
                <span class="absolute -bottom-1 -right-1 flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white border-2 border-slate-900 shadow" title="Akun Aktif">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
            </div>

            <!-- Profile Info & Meta -->
            <div class="text-center sm:text-left flex-1 space-y-2">
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                    <span class="rounded-full px-3 py-0.5 text-xs font-bold uppercase tracking-wider {{ $user->isAdmin() ? 'bg-amber-400/25 text-amber-300 border border-amber-300/40' : 'bg-blue-400/25 text-blue-200 border border-blue-300/40' }}">
                        {{ $user->role }}
                    </span>
                    <span class="rounded-full px-3 py-0.5 text-xs font-semibold bg-white/15 text-slate-200 border border-white/20">
                        {{ $user->cabang ?? 'Yamaha JG Purwakarta' }}
                    </span>
                </div>

                <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    {{ $user->name }}
                </h2>

                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 text-xs text-blue-100 pt-1">
                    <div class="flex items-center gap-1.5">
                        <svg class="h-4 w-4 text-blue-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $user->email }}</span>
                    </div>
                    <span class="hidden sm:inline text-white/30">&bull;</span>
                    <div class="flex items-center gap-1.5">
                        <svg class="h-4 w-4 text-blue-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>{{ $user->no_hp ? $user->no_hp : 'No. HP belum diatur' }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Action Jump Button -->
            <div class="shrink-0 flex sm:flex-col gap-2">
                <a href="#form-edit-profil"
                   class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-xs font-bold text-[#1E3A8A] shadow-md hover:bg-blue-50 transition">
                    <svg class="h-4 w-4 text-[#1E3A8A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Edit Profil</span>
                </a>
                <a href="#form-keamanan"
                   class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2.5 text-xs font-bold text-white border border-white/25 backdrop-blur-sm hover:bg-white/25 transition">
                    <svg class="h-4 w-4 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span>Ubah Password</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content 2-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- ========================================== -->
        <!-- LEFT COLUMN: AKTIVITAS & TRAINING SUMMARY  -->
        <!-- ========================================== -->
        <div class="lg:col-span-1 space-y-6">

            <!-- Card 1: Informasi Akun Ringkas -->
            <div class="rounded-2xl bg-white p-6 border-2 border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-200">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-[#1E3A8A]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Informasi Akun</h3>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-500 font-medium">Nama Lengkap</span>
                        <div class="font-bold text-slate-900 mt-0.5">{{ $user->name }}</div>
                    </div>
                    <div>
                        <span class="text-slate-500 font-medium">Email / Username</span>
                        <div class="font-bold text-slate-900 mt-0.5">{{ $user->email }}</div>
                    </div>
                    <div>
                        <span class="text-slate-500 font-medium">Nomor Handphone</span>
                        <div class="font-bold text-slate-900 mt-0.5">{{ $user->no_hp ? $user->no_hp : '—' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-500 font-medium">Peran / Role</span>
                        <div class="font-bold text-slate-900 mt-0.5 capitalize">{{ $user->role }}</div>
                    </div>
                    <div>
                        <span class="text-slate-500 font-medium">Dealer / Cabang</span>
                        <div class="font-bold text-slate-900 mt-0.5">{{ $user->cabang ?? 'Yamaha JG Purwakarta' }}</div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Ringkasan Aktivitas Sales -->
            <div class="rounded-2xl bg-white p-6 border-2 border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100 text-indigo-800">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">Aktivitas Saya</h3>
                    </div>
                    <span class="text-[11px] font-bold text-slate-500">Prospek &amp; Deal</span>
                </div>

                <div class="grid grid-cols-3 gap-2.5 text-center">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                        <div class="text-[11px] text-slate-500 font-medium">Total Prospek</div>
                        <div class="text-lg font-black text-slate-900 mt-0.5">{{ $totalProspects }}</div>
                    </div>
                    <div class="p-3 rounded-xl bg-blue-50 border border-blue-200">
                        <div class="text-[11px] text-blue-700 font-medium">Go To Deal</div>
                        <div class="text-lg font-black text-[#1E3A8A] mt-0.5">{{ $goToDealCount }}</div>
                    </div>
                    <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200">
                        <div class="text-[11px] text-emerald-700 font-medium">Beli / Deal</div>
                        <div class="text-lg font-black text-emerald-700 mt-0.5">{{ $beliCount }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100 text-xs">
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50">
                        <span class="text-slate-600 font-medium">Follow Up Phone</span>
                        <strong class="text-slate-900 font-bold">{{ $phoneCount }}x</strong>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50">
                        <span class="text-slate-600 font-medium">Follow Up Visit</span>
                        <strong class="text-slate-900 font-bold">{{ $visitCount }}x</strong>
                    </div>
                </div>
            </div>

            <!-- Card 3: Ringkasan Training & Tes Mingguan -->
            <div class="rounded-2xl bg-white p-6 border-2 border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-teal-100 text-teal-800">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">Training &amp; Tes</h3>
                    </div>
                </div>

                @if($totalTrainingCount > 0 || $weeklyTestDoneCount > 0)
                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200">
                            <div>
                                <div class="font-bold text-slate-900">Training Selesai</div>
                                <div class="text-[11px] text-slate-500">Materi &amp; Quiz Modul</div>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-black text-primary">{{ $completedTrainingsCount }}</span>
                                <span class="text-slate-400 font-semibold">/ {{ $totalTrainingCount }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200">
                            <div>
                                <div class="font-bold text-slate-900">Tes Mingguan</div>
                                <div class="text-[11px] text-slate-500">Total Pengerjaan</div>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-black text-[#1E3A8A]">{{ $weeklyTestDoneCount }}</span>
                                <span class="text-[11px] text-slate-500 font-medium">tes</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50/70 border border-emerald-200">
                            <div>
                                <div class="font-bold text-emerald-950">Rata-rata Nilai Tes</div>
                                <div class="text-[11px] text-emerald-800">Evaluasi Kemampuan</div>
                            </div>
                            <div class="text-right">
                                <span class="text-base font-black text-emerald-700">{{ $avgWeeklyScore ?? '-' }}</span>
                                <span class="text-[10px] text-emerald-600 font-semibold">/ 100</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-6 text-center text-slate-500 text-xs bg-slate-50 rounded-xl border border-slate-200">
                        Belum ada aktivitas training.
                    </div>
                @endif
            </div>

        </div>

        <!-- ========================================== -->
        <!-- RIGHT COLUMN: EDIT PROFIL & UBAH PASSWORD  -->
        <!-- ========================================== -->
        <div class="lg:col-span-2 space-y-6">

            <!-- SECTION 1: EDIT PROFIL -->
            <div id="form-edit-profil" class="rounded-2xl bg-white p-6 sm:p-8 border-2 border-slate-200 shadow-sm space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <svg class="h-5 w-5 text-[#1E3A8A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            <span>Edit Profil Pengguna</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Perbarui nama, nomor telepon, dan foto profil akun SIMAKIS Anda.</p>
                    </div>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- Foto Profil Upload Field -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider">Foto Profil</label>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="shrink-0">
                                @if($user->foto_url)
                                    <img src="{{ $user->foto_url }}" alt="{{ $user->name }}" class="h-16 w-16 rounded-xl object-cover border-2 border-blue-200 shadow-sm">
                                @else
                                    <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-blue-100 text-[#1E3A8A] font-bold text-xl border-2 border-blue-200">
                                        {{ $user->avatar_initials }}
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 space-y-1">
                                <input type="file" name="foto_profil" accept="image/png, image/jpeg, image/jpg"
                                       class="block w-full text-xs text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#1E3A8A] hover:file:bg-blue-100 file:cursor-pointer border border-slate-300 rounded-xl bg-white p-1.5 focus:outline-none">
                                <p class="text-[11px] text-slate-500">Mendukung format JPG, JPEG, PNG (Maksimal ukuran 2MB).</p>
                            </div>
                        </div>
                    </div>

                    <!-- Input Nama Lengkap -->
                    <div class="space-y-1.5">
                        <label for="nama" class="block text-xs font-bold text-slate-800 uppercase tracking-wider">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required
                               class="w-full rounded-xl border-2 border-slate-200 px-3.5 py-2.5 text-xs text-slate-900 font-semibold focus:border-[#1E3A8A] focus:ring-0 transition">
                    </div>

                    <!-- Input Email & No HP Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="email" class="block text-xs font-bold text-slate-800 uppercase tracking-wider">
                                Alamat Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full rounded-xl border-2 border-slate-200 px-3.5 py-2.5 text-xs text-slate-900 font-semibold focus:border-[#1E3A8A] focus:ring-0 transition">
                        </div>

                        <div class="space-y-1.5">
                            <label for="no_hp" class="block text-xs font-bold text-slate-800 uppercase tracking-wider">
                                Nomor Handphone / WhatsApp
                            </label>
                            <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" placeholder="Contoh: 081234567890"
                                   class="w-full rounded-xl border-2 border-slate-200 px-3.5 py-2.5 text-xs text-slate-900 font-semibold focus:border-[#1E3A8A] focus:ring-0 transition">
                        </div>
                    </div>

                    <!-- Readonly Role & Dealer Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Peran Akun</label>
                            <div class="w-full rounded-xl bg-slate-100 border border-slate-200 px-3.5 py-2.5 text-xs font-bold text-slate-700 capitalize flex items-center justify-between">
                                <span>{{ $user->role }}</span>
                                <span class="text-[10px] text-slate-400 uppercase font-medium">Dikelola Admin</span>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Dealer / Cabang</label>
                            <div class="w-full rounded-xl bg-slate-100 border border-slate-200 px-3.5 py-2.5 text-xs font-bold text-slate-700 flex items-center justify-between">
                                <span>{{ $user->cabang ?? 'Yamaha JG Purwakarta' }}</span>
                                <span class="text-[10px] text-slate-400 uppercase font-medium">Dikelola Admin</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-[#1E3A8A] px-6 py-2.5 text-xs font-bold text-white border-2 border-[#1E3A8A] shadow-md hover:bg-blue-900 hover:shadow-lg transition active:scale-95 cursor-pointer">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Perubahan Profil</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- SECTION 2: KEAMANAN AKUN (UBAH PASSWORD) -->
            <div id="form-keamanan" class="rounded-2xl bg-white p-6 sm:p-8 border-2 border-slate-200 shadow-sm space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span>Keamanan Akun &amp; Kata Sandi</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Pastikan akun Anda menggunakan kata sandi yang aman dan tidak dibagikan kepada orang lain.</p>
                    </div>
                </div>

                <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Password Saat Ini -->
                    <div class="space-y-1.5">
                        <label for="current_password" class="block text-xs font-bold text-slate-800 uppercase tracking-wider">
                            Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" id="current_password" name="current_password" required
                               placeholder="Masukkan kata sandi yang Anda gunakan saat ini"
                               class="w-full rounded-xl border-2 border-slate-200 px-3.5 py-2.5 text-xs text-slate-900 font-semibold focus:border-[#1E3A8A] focus:ring-0 transition">
                    </div>

                    <!-- Password Baru & Konfirmasi Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="password" class="block text-xs font-bold text-slate-800 uppercase tracking-wider">
                                Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" id="password" name="password" required minlength="8"
                                   placeholder="Minimal 8 karakter"
                                   class="w-full rounded-xl border-2 border-slate-200 px-3.5 py-2.5 text-xs text-slate-900 font-semibold focus:border-[#1E3A8A] focus:ring-0 transition">
                        </div>

                        <div class="space-y-1.5">
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-800 uppercase tracking-wider">
                                Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8"
                                   placeholder="Ulangi kata sandi baru"
                                   class="w-full rounded-xl border-2 border-slate-200 px-3.5 py-2.5 text-xs text-slate-900 font-semibold focus:border-[#1E3A8A] focus:ring-0 transition">
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-6 py-2.5 text-xs font-bold text-white border-2 border-slate-900 shadow-md hover:bg-slate-800 hover:shadow-lg transition active:scale-95 cursor-pointer">
                            <svg class="h-4 w-4 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            <span>Perbarui Kata Sandi</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>

</div>
@endsection
