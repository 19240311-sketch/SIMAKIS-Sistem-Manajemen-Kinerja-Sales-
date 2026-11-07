@extends('layouts.app')

@section('title', 'Input Data Prospek & Go To Deal — SIMAKIS Yamaha')
@section('page-title', 'Input Data Prospek & Go To Deal')

@section('content')
<div class="mx-auto max-w-3xl pb-12">

    <!-- Top Navigation & Stage Switcher Header Card -->
    <div class="mb-6 rounded-2xl bg-white p-5 shadow-sm border-2 border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('prospects.index') }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs transition-colors shadow-2xs"
                   title="Kembali ke Daftar Prospek">
                    <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    <span>Kembali</span>
                </a>
                <div>
                    <h2 class="text-heading-sm font-bold text-slate-900" id="headerTitle">
                        {{ old('tahap_data', $mode ?? 'prospek') === 'deal' ? 'Formulir Go To Deal' : 'Formulir Input Prospek' }}
                    </h2>
                    <p class="text-xs font-medium text-slate-500" id="headerSubtitle">
                        {{ old('tahap_data', $mode ?? 'prospek') === 'deal' ? 'Tahap transaksi & pelengkapan data pembelian unit' : 'Pencatatan calon konsumen dan minat unit sepeda motor' }}
                    </p>
                </div>
            </div>

            <!-- Two Distinct, High-Contrast Stage Buttons (Prospek vs Go To Deal) -->
            <div class="flex items-center gap-2.5 p-1.5 rounded-2xl bg-slate-100 border-2 border-slate-200">
                <button type="button" id="tabBtnProspek" onclick="switchFormTab('prospek')"
                        class="flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-bold transition shadow-sm {{ old('tahap_data', $mode ?? 'prospek') === 'prospek' ? 'bg-[#1E3A8A] text-white border-2 border-[#1E3A8A] shadow-md' : 'bg-white text-slate-700 border-2 border-slate-300 hover:border-blue-400 hover:bg-blue-50 hover:text-[#1E3A8A]' }}">
                    <svg class="h-4 w-4 shrink-0 {{ old('tahap_data', $mode ?? 'prospek') === 'prospek' ? 'text-white' : 'text-slate-600' }}" id="iconTabProspek" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Prospek</span>
                </button>

                <button type="button" id="tabBtnDeal" onclick="switchFormTab('deal')"
                        class="flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-bold transition shadow-sm {{ old('tahap_data', $mode ?? 'prospek') === 'deal' ? 'bg-[#1E3A8A] text-white border-2 border-[#1E3A8A] shadow-md' : 'bg-white text-slate-700 border-2 border-slate-300 hover:border-blue-400 hover:bg-blue-50 hover:text-[#1E3A8A]' }}">
                    <svg class="h-4 w-4 shrink-0 {{ old('tahap_data', $mode ?? 'prospek') === 'deal' ? 'text-white' : 'text-slate-600' }}" id="iconTabDeal" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Go To Deal</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- FORM 1: PROSPEK (Hanya data calon konsumen & minat unit motor)            -->
    <!-- ========================================================================= -->
    <div id="containerFormProspek" class="{{ old('tahap_data', $mode ?? 'prospek') === 'deal' ? 'hidden' : '' }}">
        <form id="formProspek" action="{{ route('prospects.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="tahap_data" value="prospek">

            <!-- Section A: Data Calon Konsumen -->
            <div class="rounded-2xl bg-white p-6 shadow-sm border-2 border-slate-200 space-y-4">
                <div class="flex items-center gap-3 border-b-2 border-slate-100 pb-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-[#1E3A8A] font-extrabold text-sm border-2 border-blue-200">
                        A
                    </div>
                    <div>
                        <h3 class="text-body-md font-bold text-[#1E3A8A]">Data Konsumen</h3>
                        <p class="text-xs text-slate-500">Informasi kontak dan domisili calon pembeli</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nama Konsumen -->
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Nama Konsumen <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="nama_konsumen" value="{{ old('nama_konsumen') }}" required
                               placeholder="Masukkan nama lengkap calon konsumen"
                               class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 focus:outline-none @error('nama_konsumen') border-red-500 @enderror">
                        @error('nama_konsumen')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No HP / WA -->
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            No. HP / WhatsApp <span class="text-red-600">*</span>
                        </label>
                        <input type="tel" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required
                               placeholder="Contoh: 08123456789"
                               class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 focus:outline-none @error('nomor_telepon') border-red-500 @enderror">
                        @error('nomor_telepon')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat Lengkap -->
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Alamat Lengkap <span class="text-red-600">*</span>
                        </label>
                        <textarea name="alamat" rows="2" required
                                  placeholder="Nama jalan, nomor rumah, RT/RW..."
                                  class="w-full rounded-xl border-2 border-slate-300 bg-white p-3.5 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 focus:outline-none @error('alamat') border-red-500 @enderror">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kelurahan -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Kelurahan
                        </label>
                        <input type="text" name="kelurahan" value="{{ old('kelurahan') }}"
                               placeholder="Kelurahan"
                               class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 focus:outline-none">
                    </div>

                    <!-- Kecamatan -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Kecamatan
                        </label>
                        <input type="text" name="kecamatan" value="{{ old('kecamatan') }}"
                               placeholder="Kecamatan"
                               class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 focus:outline-none">
                    </div>

                    <!-- Kota -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Kota / Kabupaten <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="kota" value="{{ old('kota', 'Jakarta Timur') }}" required
                               placeholder="Kota / Kabupaten"
                               class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 focus:outline-none">
                    </div>

                    <!-- Provinsi -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Provinsi <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="provinsi" value="{{ old('provinsi', 'DKI Jakarta') }}" required
                               placeholder="Provinsi"
                               class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 focus:outline-none">
                    </div>

                    <!-- Kode Pos -->
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Kode Pos
                        </label>
                        <input type="text" name="kode_pos" value="{{ old('kode_pos') }}"
                               placeholder="Kode Pos"
                               class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Section B: Data Motor & Minat (Relasi Dependent Tipe -> Warna -> Harga OTR -> Tenor -> Angsuran) -->
            <div class="rounded-2xl bg-white p-6 shadow-sm border-2 border-slate-200 space-y-4">
                <div class="flex items-center gap-3 border-b-2 border-slate-100 pb-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-[#1E3A8A] font-extrabold text-sm border-2 border-blue-200">
                        B
                    </div>
                    <div>
                        <h3 class="text-body-md font-bold text-[#1E3A8A]">Data Motor yang Diminati</h3>
                        <p class="text-xs text-slate-500">Pilihan tipe motor, warna yang tersedia, harga OTR, dan skema angsuran otomatis</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tipe Motor (Clean dropdown: NO OTR price inside label) -->
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Tipe Motor <span class="text-red-600">*</span>
                        </label>
                        <select name="sepeda_motor_id" id="selectMotorProspek" required onchange="handleMotorChange(this.value, 'prospek')"
                                class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 focus:outline-none @error('sepeda_motor_id') border-red-500 @enderror">
                            <option value="">-- Pilih Tipe Sepeda Motor Yamaha --</option>
                            @foreach($motorcycles as $moto)
                                <option value="{{ $moto->id }}" {{ old('sepeda_motor_id') == $moto->id ? 'selected' : '' }}>
                                    {{ $moto->full_display_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('sepeda_motor_id')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pilihan Warna Motor (Dependent Dropdown) -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Pilihan Warna Motor <span class="text-red-600">*</span>
                        </label>
                        <select name="warna_motor_diminati" id="selectColorProspek" required
                                class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 focus:outline-none @error('warna_motor_diminati') border-red-500 @enderror">
                            <option value="">Silakan pilih motor terlebih dahulu</option>
                        </select>
                        <p class="mt-1 text-[11px] text-slate-500" id="colorHintProspek">Pilihan warna menyesuaikan tipe motor yang dipilih.</p>
                        @error('warna_motor_diminati')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga OTR Otomatis (Read Only / Locked) -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Harga OTR (Otomatis)
                        </label>
                        <div class="relative">
                            <input type="text" id="displayOtrProspek" readonly
                                   placeholder="—"
                                   value="—"
                                   class="h-11 w-full rounded-xl border-2 border-slate-200 bg-slate-100 px-3.5 text-sm font-extrabold text-[#1E3A8A] focus:outline-none cursor-not-allowed">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-xs font-bold text-slate-500 gap-1">
                                <svg class="h-3.5 w-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                                <span>Terkunci</span>
                            </div>
                        </div>
                        <p class="mt-1 text-[11px] text-slate-500">Harga OTR diambil otomatis dari Master Data Motor.</p>
                    </div>

                    <!-- Tenor Angsuran (Dependent Dropdown from Master Data) -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Tenor Angsuran
                        </label>
                        <select name="tenor_bulan" id="selectTenorProspek" onchange="handleTenorChange(this.value, 'prospek')"
                                class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 focus:outline-none">
                            <option value="">Silakan pilih motor terlebih dahulu</option>
                        </select>
                        <p class="mt-1 text-[11px] text-slate-500">Pilihan tenor resmi yang tersedia untuk tipe motor ini.</p>
                    </div>

                    <!-- Angsuran Otomatis (Read Only / Locked) -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Angsuran (Otomatis)
                        </label>
                        <div class="relative">
                            <input type="text" id="displayAngsuranProspek" readonly
                                   placeholder="—"
                                   value="—"
                                   class="h-11 w-full rounded-xl border-2 border-slate-200 bg-slate-100 px-3.5 text-sm font-extrabold text-[#1E3A8A] focus:outline-none cursor-not-allowed">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-xs font-bold text-slate-500 gap-1">
                                <svg class="h-3.5 w-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                                <span>Terkunci</span>
                            </div>
                        </div>
                        <p class="mt-1 text-[11px] text-slate-500">Data harga dan angsuran diambil otomatis dari Master Data Motor.</p>
                    </div>
                </div>
            </div>

            <!-- Section C: Sumber & Status Prospek -->
            <div class="rounded-2xl bg-white p-6 shadow-sm border-2 border-slate-200 space-y-4">
                <div class="flex items-center gap-3 border-b-2 border-slate-100 pb-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-[#1E3A8A] font-extrabold text-sm border-2 border-blue-200">
                        C
                    </div>
                    <div>
                        <h3 class="text-body-md font-bold text-[#1E3A8A]">Sumber & Status Prospek</h3>
                        <p class="text-xs text-slate-500">Klasifikasi asal prospek dan status pemantauan awal</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Sumber Prospek -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Sumber Prospek <span class="text-red-600">*</span>
                        </label>
                        <select name="sumber_prospek_id" required
                                class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 focus:outline-none">
                            <option value="">-- Pilih Sumber Prospek --</option>
                            @foreach($sources as $src)
                                <option value="{{ $src->id }}" {{ old('sumber_prospek_id') == $src->id ? 'selected' : '' }}>
                                    {{ $src->nama_sumber }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Prospek -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Status Prospek <span class="text-red-600">*</span>
                        </label>
                        <select name="status_prospek_id" required
                                class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 focus:outline-none">
                            @foreach($statuses->where('kode', '!=', 'deal') as $st)
                                <option value="{{ $st->id }}" {{ old('status_prospek_id', $defaultStatus->id) == $st->id ? 'selected' : '' }}>
                                    {{ $st->nama_status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Penugasan Sales (Jika Admin) -->
                    @if(auth()->user()->isAdmin())
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-body-sm font-bold text-slate-900">
                                Tugaskan ke Sales <span class="text-red-600">*</span>
                            </label>
                            <select name="user_id" required
                                    class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 focus:outline-none">
                                <option value="">-- Pilih Sales Penanggung Jawab --</option>
                                @foreach($salesUsers as $sales)
                                    <option value="{{ $sales->id }}" {{ old('user_id') == $sales->id ? 'selected' : '' }}>
                                        {{ $sales->nama }} ({{ $sales->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Section D: Follow Up & Catatan -->
            <div class="rounded-2xl bg-white p-6 shadow-sm border-2 border-slate-200 space-y-4">
                <div class="flex items-center gap-3 border-b-2 border-slate-100 pb-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-[#1E3A8A] font-extrabold text-sm border-2 border-blue-200">
                        D
                    </div>
                    <div>
                        <h3 class="text-body-md font-bold text-[#1E3A8A]">Rencana Follow Up & Catatan</h3>
                        <p class="text-xs text-slate-500">Agenda tindak lanjut berikutnya dan catatan kebutuhan calon konsumen</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Tanggal Follow Up Selanjutnya -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Tanggal Follow Up Selanjutnya <span class="text-red-600">*</span>
                        </label>
                        <input type="date" name="tanggal_follow_up_selanjutnya" required
                               value="{{ old('tanggal_follow_up_selanjutnya', now()->addDays(2)->format('Y-m-d')) }}"
                               min="{{ now()->format('Y-m-d') }}"
                               class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 focus:outline-none">
                    </div>

                    <!-- Catatan / Keterangan -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Catatan / Keterangan Kebutuhan
                        </label>
                        <textarea name="catatan" rows="3"
                                  placeholder="Tuliskan respon awal konsumen, rencana DP, atau waktu luang untuk dihubungi..."
                                  class="w-full rounded-xl border-2 border-slate-300 bg-white p-3.5 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:border-[#1E3A8A] focus:ring-2 focus:ring-blue-100 focus:outline-none">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Bottom Submit & Cancel Action -->
            <div class="pt-4 flex flex-col-reverse sm:flex-row items-center gap-3">
                <a href="{{ route('prospects.index') }}"
                   class="w-full sm:w-auto px-6 py-3.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-sm font-bold text-center transition shadow-2xs">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 w-full flex items-center justify-center gap-2 rounded-xl bg-[#0A4DF3] py-3.5 px-6 text-sm font-bold text-white shadow-md hover:bg-blue-700 active:scale-[0.99] transition cursor-pointer">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Data Prospek</span>
                </button>
            </div>
        </form>
    </div>

    <!-- ========================================================================= -->
    <!-- FORM 2: GO TO DEAL (Fokus ke transaksi pembelian & data tanpa ketik ulang) -->
    <!-- ========================================================================= -->
    <div id="containerFormDeal" class="{{ old('tahap_data', $mode ?? 'prospek') === 'deal' ? '' : 'hidden' }}">
        <form id="formDeal" action="{{ route('prospects.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="tahap_data" value="deal">

            <!-- Card Pilihan: Ambil dari Prospek yang Sudah Ada (No Re-typing!) -->
            <div class="rounded-2xl bg-blue-50/90 p-5 border-2 border-blue-300 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-[#1E3A8A] font-bold text-sm sm:text-base">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Pilih Data Prospek yang Sudah Ada (Otomatis)</span>
                    </div>
                    <span class="rounded-full bg-blue-700 px-3 py-1 text-xs font-bold text-white shadow-sm">Bebas Ketik Ulang</span>
                </div>
                <p class="text-xs font-medium text-blue-900">
                    Pilih nama calon konsumen di bawah ini untuk memuat seluruh data diri dan motor yang diminati secara instan.
                </p>

                <select name="prospect_id" id="selectExistingProspect" onchange="loadProspectData(this.value)"
                        class="h-12 w-full rounded-xl border-2 border-blue-400 bg-white px-3.5 text-sm font-bold text-[#1E3A8A] focus:border-[#1E3A8A] focus:outline-none">
                    <option value="">-- Pilih Calon Konsumen Prospek --</option>
                    @foreach($pendingProspects as $item)
                        <option value="{{ $item->id }}" {{ (old('prospect_id', $selectedProspect->id ?? null) == $item->id) ? 'selected' : '' }}>
                            {{ $item->nama_konsumen }} ({{ $item->nomor_telepon }}) — Minat: {{ $item->motorcycle->full_display_name ?? 'Unit Yamaha' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Section A: Data Konsumen & Identitas Lengkap -->
            <div class="rounded-2xl bg-white p-6 shadow-sm border-2 border-slate-200 space-y-4">
                <div class="flex items-center gap-3 border-b-2 border-slate-100 pb-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-[#1E3A8A] font-extrabold text-sm border-2 border-blue-200">
                        A
                    </div>
                    <div>
                        <h3 class="text-body-md font-bold text-[#1E3A8A]">Data Konsumen & Identitas</h3>
                        <p class="text-xs text-slate-500">Data identitas lengkap untuk proses transaksi dan faktur</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nama Konsumen -->
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Nama Konsumen <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="nama_konsumen" id="dealInputNama" value="{{ old('nama_konsumen', $selectedProspect->nama_konsumen ?? '') }}" required
                               placeholder="Nama lengkap sesuai KTP"
                               class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>

                    <!-- No. HP -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            No. HP / WhatsApp <span class="text-red-600">*</span>
                        </label>
                        <input type="tel" name="nomor_telepon" id="dealInputHp" value="{{ old('nomor_telepon', $selectedProspect->nomor_telepon ?? '') }}" required
                               placeholder="Nomor telepon aktif"
                               class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>

                    <!-- Nomor KTP -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Nomor KTP (16 Digit) <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="nomor_ktp" id="dealInputKtp" value="{{ old('nomor_ktp', $selectedProspect->nomor_ktp ?? '') }}" required maxlength="16"
                               placeholder="Nomor Induk Kependudukan (NIK)"
                               class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Tanggal Lahir
                        </label>
                        <input type="date" name="tanggal_lahir" id="dealInputTglLahir" value="{{ old('tanggal_lahir', optional($selectedProspect->tanggal_lahir ?? null)->format('Y-m-d')) }}"
                               class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>

                    <!-- Pekerjaan -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Pekerjaan / Profesi
                        </label>
                        <select name="pekerjaan" id="dealInputPekerjaan" class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                            <option value="">-- Pilih Pekerjaan --</option>
                            <option value="Karyawan Swasta" {{ old('pekerjaan', $selectedProspect->pekerjaan ?? '') == 'Karyawan Swasta' ? 'selected' : '' }}>Karyawan Swasta</option>
                            <option value="Pegawai Negeri (PNS/BUMN)" {{ old('pekerjaan', $selectedProspect->pekerjaan ?? '') == 'Pegawai Negeri (PNS/BUMN)' ? 'selected' : '' }}>Pegawai Negeri (PNS/BUMN)</option>
                            <option value="Wiraswasta / Pengusaha" {{ old('pekerjaan', $selectedProspect->pekerjaan ?? '') == 'Wiraswasta / Pengusaha' ? 'selected' : '' }}>Wiraswasta / Pengusaha</option>
                            <option value="Profesional (Dokter/Advokat)" {{ old('pekerjaan', $selectedProspect->pekerjaan ?? '') == 'Profesional (Dokter/Advokat)' ? 'selected' : '' }}>Profesional (Dokter/Advokat)</option>
                            <option value="Pelajar / Mahasiswa" {{ old('pekerjaan', $selectedProspect->pekerjaan ?? '') == 'Pelajar / Mahasiswa' ? 'selected' : '' }}>Pelajar / Mahasiswa</option>
                            <option value="Ibu Rumah Tangga" {{ old('pekerjaan', $selectedProspect->pekerjaan ?? '') == 'Ibu Rumah Tangga' ? 'selected' : '' }}>Ibu Rumah Tangga</option>
                            <option value="Lainnya" {{ old('pekerjaan', $selectedProspect->pekerjaan ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <!-- Alamat Lengkap -->
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Alamat Sesuai KTP <span class="text-red-600">*</span>
                        </label>
                        <textarea name="alamat" id="dealInputAlamat" rows="2" required
                                  placeholder="Alamat domisili lengkap konsumen"
                                  class="w-full rounded-xl border-2 border-slate-300 bg-white p-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">{{ old('alamat', $selectedProspect->alamat ?? '') }}</textarea>
                    </div>

                    <!-- Kelurahan -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">Kelurahan</label>
                        <input type="text" name="kelurahan" id="dealInputKelurahan" value="{{ old('kelurahan', $selectedProspect->kelurahan ?? '') }}"
                               placeholder="Kelurahan"
                               class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>

                    <!-- Kecamatan -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">Kecamatan</label>
                        <input type="text" name="kecamatan" id="dealInputKecamatan" value="{{ old('kecamatan', $selectedProspect->kecamatan ?? '') }}"
                               placeholder="Kecamatan"
                               class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>

                    <!-- Kota -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">Kota / Kabupaten <span class="text-red-600">*</span></label>
                        <input type="text" name="kota" id="dealInputKota" value="{{ old('kota', $selectedProspect->kota ?? 'Jakarta Timur') }}" required
                               placeholder="Kota / Kabupaten"
                               class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>

                    <!-- Provinsi -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">Provinsi <span class="text-red-600">*</span></label>
                        <input type="text" name="provinsi" id="dealInputProvinsi" value="{{ old('provinsi', $selectedProspect->provinsi ?? 'DKI Jakarta') }}" required
                               placeholder="Provinsi"
                               class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>

                    <!-- Kode Pos -->
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">Kode Pos</label>
                        <input type="text" name="kode_pos" id="dealInputKodePos" value="{{ old('kode_pos', $selectedProspect->kode_pos ?? '') }}"
                               placeholder="Kode Pos"
                               class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Section B: Detail Sepeda Motor yang Dibeli -->
            <div class="rounded-2xl bg-white p-6 shadow-sm border-2 border-slate-200 space-y-4">
                <div class="flex items-center gap-3 border-b-2 border-slate-100 pb-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-[#1E3A8A] font-extrabold text-sm border-2 border-blue-200">
                        B
                    </div>
                    <div>
                        <h3 class="text-body-md font-bold text-[#1E3A8A]">Detail Motor yang Dibeli</h3>
                        <p class="text-xs text-slate-500">Pilihan unit final, warna yang tersedia, dan harga OTR resmi</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tipe Motor -->
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Tipe Motor <span class="text-red-600">*</span>
                        </label>
                        <select name="sepeda_motor_id" id="selectMotorDeal" required onchange="handleMotorChange(this.value, 'deal')"
                                class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                            <option value="">-- Pilih Tipe Sepeda Motor Yamaha --</option>
                            @foreach($motorcycles as $moto)
                                <option value="{{ $moto->id }}" {{ (old('sepeda_motor_id', $selectedProspect->sepeda_motor_id ?? null) == $moto->id) ? 'selected' : '' }}>
                                    {{ $moto->full_display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Warna Motor (Dependent Dropdown) -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Warna Motor Terpilih <span class="text-red-600">*</span>
                        </label>
                        <select name="warna_motor_diminati" id="selectColorDeal" required
                                class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                            <option value="">Silakan pilih motor terlebih dahulu</option>
                        </select>
                        <p class="mt-1 text-xs text-slate-500" id="colorHintDeal">Warna hanya menampilkan varian resmi motor ini.</p>
                    </div>

                    <!-- Harga OTR Otomatis -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Harga OTR (Otomatis)
                        </label>
                        <div class="relative">
                            <input type="text" id="displayOtrDeal" readonly
                                   placeholder="—"
                                   value="—"
                                   class="h-11 w-full rounded-xl border-2 border-slate-200 bg-slate-100 px-3.5 text-sm font-extrabold text-[#1E3A8A] focus:outline-none cursor-not-allowed">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-xs font-bold text-slate-500 gap-1">
                                <svg class="h-3.5 w-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                                <span>Terkunci</span>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">Harga OTR diambil otomatis dari master data.</p>
                    </div>
                </div>
            </div>

            <!-- Section C: Detail Skema Pembelian -->
            <div class="rounded-2xl bg-white p-6 shadow-sm border-2 border-slate-200 space-y-4">
                <div class="flex items-center gap-3 border-b-2 border-slate-100 pb-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-[#1E3A8A] font-extrabold text-sm border-2 border-blue-200">
                        C
                    </div>
                    <div>
                        <h3 class="text-body-md font-bold text-[#1E3A8A]">Detail Pembelian</h3>
                        <p class="text-xs text-slate-500">Skema transaksi tunai atau kredit pembiayaan</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Skema Pembelian (Cash vs Kredit) -->
                    <div>
                        <label class="mb-2 block text-body-sm font-bold text-slate-900">
                            Skema Pembelian <span class="text-red-600">*</span>
                        </label>
                        <input type="hidden" name="skema_pembelian" id="inputSkemaPembelian" value="{{ old('skema_pembelian', 'Cash') }}">
                        <div class="grid grid-cols-2 gap-3 max-w-md">
                            <button type="button" id="btnSkemaCash" onclick="setSkemaPembelian('Cash')"
                                    class="flex items-center justify-center gap-2 rounded-xl py-3 px-4 font-bold text-sm transition border-2 {{ old('skema_pembelian', 'Cash') === 'Cash' ? 'bg-[#1E3A8A] text-white border-[#1E3A8A] shadow-md' : 'bg-white text-slate-700 border-slate-300 hover:border-blue-400 hover:bg-blue-50 hover:text-[#1E3A8A] shadow-sm' }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>Cash / Tunai</span>
                            </button>
                            <button type="button" id="btnSkemaKredit" onclick="setSkemaPembelian('Kredit')"
                                    class="flex items-center justify-center gap-2 rounded-xl py-3 px-4 font-bold text-sm transition border-2 {{ old('skema_pembelian') === 'Kredit' ? 'bg-[#1E3A8A] text-white border-[#1E3A8A] shadow-md' : 'bg-white text-slate-700 border-slate-300 hover:border-blue-400 hover:bg-blue-50 hover:text-[#1E3A8A] shadow-sm' }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                <span>Kredit / Cicilan</span>
                            </button>
                        </div>
                    </div>

                    <!-- Fields Khusus Kredit (DP, Tenor, Angsuran Otomatis dari Master) -->
                    <div id="containerCreditFields" class="{{ old('skema_pembelian') === 'Kredit' ? '' : 'hidden' }} grid grid-cols-1 md:grid-cols-3 gap-4 pt-3 border-t-2 border-slate-100">
                        <!-- Uang Muka / DP -->
                        <div>
                            <label class="mb-1 block text-body-sm font-bold text-slate-900">
                                Uang Muka / DP (Rp) <span class="text-red-600">*</span>
                            </label>
                            <input type="number" name="dp" id="inputDp" value="{{ old('dp') }}"
                                   placeholder="Contoh: 3500000" min="0"
                                   class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                        </div>

                        <!-- Tenor Bulan -->
                        <div>
                            <label class="mb-1 block text-body-sm font-bold text-slate-900">
                                Tenor Angsuran <span class="text-red-600">*</span>
                            </label>
                            <select name="tenor_bulan" id="selectTenorDeal" onchange="handleTenorChange(this.value, 'deal')"
                                    class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                                <option value="">Silakan pilih motor terlebih dahulu</option>
                            </select>
                        </div>

                        <!-- Angsuran Otomatis -->
                        <div>
                            <label class="mb-1 block text-body-sm font-bold text-slate-900">
                                Angsuran / Bulan (Otomatis)
                            </label>
                            <div class="relative">
                                <input type="text" id="displayAngsuranDeal" readonly
                                       placeholder="—"
                                       value="—"
                                       class="h-11 w-full rounded-xl border-2 border-slate-200 bg-slate-100 px-3.5 text-sm font-extrabold text-[#1E3A8A] focus:outline-none cursor-not-allowed">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-xs font-bold text-slate-500 gap-1">
                                    <svg class="h-3.5 w-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                    </svg>
                                    <span>Terkunci</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section D: Informasi Pembayaran & Leasing -->
            <div class="rounded-2xl bg-white p-6 shadow-sm border-2 border-slate-200 space-y-4">
                <div class="flex items-center gap-3 border-b-2 border-slate-100 pb-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-[#1E3A8A] font-extrabold text-sm border-2 border-blue-200">
                        D
                    </div>
                    <div>
                        <h3 class="text-body-md font-bold text-[#1E3A8A]">Informasi Pembayaran & Lembaga Pembiayaan</h3>
                        <p class="text-xs text-slate-500">Lembaga pembiayaan resmi leasing dan metode pembayaran konsumen</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Leasing -->
                    <div id="wrapperLeasing">
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Lembaga Pembiayaan / Leasing <span id="starLeasing" class="{{ old('skema_pembelian') === 'Kredit' ? '' : 'hidden' }} text-red-600">*</span>
                        </label>
                        <select name="leasing" id="selectLeasing" class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                            <option value="">-- Pilih Lembaga Pembiayaan --</option>
                            <option value="BAF (Bussan Auto Finance)" {{ old('leasing') == 'BAF (Bussan Auto Finance)' ? 'selected' : '' }}>BAF (Bussan Auto Finance)</option>
                            <option value="Adira Finance" {{ old('leasing') == 'Adira Finance' ? 'selected' : '' }}>Adira Finance</option>
                            <option value="OTO Multiartha" {{ old('leasing') == 'OTO Multiartha' ? 'selected' : '' }}>OTO Multiartha</option>
                            <option value="MUF (Mandiri Utama Finance)" {{ old('leasing') == 'MUF (Mandiri Utama Finance)' ? 'selected' : '' }}>MUF (Mandiri Utama Finance)</option>
                            <option value="WOM Finance" {{ old('leasing') == 'WOM Finance' ? 'selected' : '' }}>WOM Finance</option>
                            <option value="Mega Central Finance (MCF)" {{ old('leasing') == 'Mega Central Finance (MCF)' ? 'selected' : '' }}>Mega Central Finance (MCF)</option>
                            <option value="Cash Dealer / Tunai Langsung" {{ old('leasing', 'Cash Dealer / Tunai Langsung') == 'Cash Dealer / Tunai Langsung' ? 'selected' : '' }}>Cash Dealer / Tunai Langsung</option>
                        </select>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Metode Pembayaran <span class="text-red-600">*</span>
                        </label>
                        <select name="metode_pembayaran" required class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            <option value="Transfer Bank BCA" {{ old('metode_pembayaran') == 'Transfer Bank BCA' ? 'selected' : '' }}>Transfer Bank BCA</option>
                            <option value="Transfer Bank Mandiri" {{ old('metode_pembayaran') == 'Transfer Bank Mandiri' ? 'selected' : '' }}>Transfer Bank Mandiri</option>
                            <option value="Transfer Bank BRI" {{ old('metode_pembayaran') == 'Transfer Bank BRI' ? 'selected' : '' }}>Transfer Bank BRI</option>
                            <option value="Transfer Bank BNI" {{ old('metode_pembayaran') == 'Transfer Bank BNI' ? 'selected' : '' }}>Transfer Bank BNI</option>
                            <option value="Kasir Tunai di Dealer" {{ old('metode_pembayaran', 'Kasir Tunai di Dealer') == 'Kasir Tunai di Dealer' ? 'selected' : '' }}>Kasir Tunai di Dealer</option>
                            <option value="Virtual Account / QRIS Dealer" {{ old('metode_pembayaran') == 'Virtual Account / QRIS Dealer' ? 'selected' : '' }}>Virtual Account / QRIS Dealer</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section E: Konfirmasi Go To Deal -->
            <div class="rounded-2xl bg-white p-6 shadow-sm border-2 border-slate-200 space-y-4">
                <div class="flex items-center gap-3 border-b-2 border-slate-100 pb-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-[#1E3A8A] font-extrabold text-sm border-2 border-blue-200">
                        E
                    </div>
                    <div>
                        <h3 class="text-body-md font-bold text-[#1E3A8A]">Konfirmasi Go To Deal</h3>
                        <p class="text-xs text-slate-500">Tanggal transaksi closing dan catatan perjanjian serah terima</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Tanggal Deal -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Tanggal Go To Deal <span class="text-red-600">*</span>
                        </label>
                        <input type="date" name="tanggal_deal" required
                               value="{{ old('tanggal_deal', now()->format('Y-m-d')) }}"
                               class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>

                    <!-- Catatan Transaksi -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Catatan Transaksi / Pengiriman
                        </label>
                        <textarea name="catatan" rows="3"
                                  placeholder="Catatan STNK, estimasi pengiriman unit, bonus aksesoris/helm..."
                                  class="w-full rounded-xl border-2 border-slate-300 bg-white p-3.5 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:border-[#1E3A8A] focus:outline-none">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Bottom Submit & Cancel Action Go To Deal -->
            <div class="pt-4 flex flex-col-reverse sm:flex-row items-center gap-3">
                <a href="{{ route('prospects.index') }}"
                   class="w-full sm:w-auto px-6 py-3.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-sm font-bold text-center transition shadow-2xs">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 w-full flex items-center justify-center gap-2 rounded-xl bg-emerald-600 py-3.5 px-6 text-sm font-bold text-white shadow-md hover:bg-emerald-700 active:scale-[0.99] transition cursor-pointer">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Simpan Transaksi Go To Deal</span>
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Master Data Injected as JSON for Instant Client-Side Reactivity -->
<script>
    const motorcyclesMaster = @json($motorcycles);
    const pendingProspectsMaster = @json($pendingProspects);

    // Initial selected values if old input exists
    const initialMotoIdProspek = "{{ old('sepeda_motor_id', '') }}";
    const initialColorProspek = "{{ old('warna_motor_diminati', '') }}";
    const initialTenorProspek = "{{ old('tenor_bulan', '') }}";

    const initialMotoIdDeal = "{{ old('sepeda_motor_id', $selectedProspect->sepeda_motor_id ?? '') }}";
    const initialColorDeal = "{{ old('warna_motor_diminati', $selectedProspect->warna_motor_diminati ?? '') }}";
    const initialTenorDeal = "{{ old('tenor_bulan', $selectedProspect->tenor_bulan ?? '') }}";

    let currentMode = "{{ old('tahap_data', $mode ?? 'prospek') }}";

    // Format number to IDR currency
    function formatRupiah(amount) {
        if (!amount || isNaN(amount) || amount <= 0) return '—';
        return 'Rp ' + Number(amount).toLocaleString('id-ID');
    }

    // Switch between PROSPEK and GO TO DEAL form tabs with crystal-clear high contrast classes
    function switchFormTab(targetTab) {
        currentMode = targetTab;
        const titleEl = document.getElementById('headerTitle');
        const subtitleEl = document.getElementById('headerSubtitle');
        const btnProspek = document.getElementById('tabBtnProspek');
        const btnDeal = document.getElementById('tabBtnDeal');
        const iconProspek = document.getElementById('iconTabProspek');
        const iconDeal = document.getElementById('iconTabDeal');
        const containerProspek = document.getElementById('containerFormProspek');
        const containerDeal = document.getElementById('containerFormDeal');

        const activeClasses = 'flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-bold transition shadow-md bg-[#1E3A8A] text-white border-2 border-[#1E3A8A]';
        const inactiveClasses = 'flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-bold transition shadow-sm bg-white text-slate-700 border-2 border-slate-300 hover:border-blue-400 hover:bg-blue-50 hover:text-[#1E3A8A]';

        if (targetTab === 'deal') {
            titleEl.textContent = 'Formulir Go To Deal';
            subtitleEl.textContent = 'Tahap transaksi & pelengkapan data pembelian unit';
            btnDeal.className = activeClasses;
            btnProspek.className = inactiveClasses;
            if (iconDeal) iconDeal.className = 'h-4 w-4 shrink-0 text-white';
            if (iconProspek) iconProspek.className = 'h-4 w-4 shrink-0 text-slate-600';

            containerDeal.classList.remove('hidden');
            containerProspek.classList.add('hidden');
        } else {
            titleEl.textContent = 'Formulir Input Prospek';
            subtitleEl.textContent = 'Pencatatan calon konsumen dan minat unit sepeda motor';
            btnProspek.className = activeClasses;
            btnDeal.className = inactiveClasses;
            if (iconProspek) iconProspek.className = 'h-4 w-4 shrink-0 text-white';
            if (iconDeal) iconDeal.className = 'h-4 w-4 shrink-0 text-slate-600';

            containerProspek.classList.remove('hidden');
            containerDeal.classList.add('hidden');
        }
    }

    // Handle Dependent Motorcycle -> Colors, OTR Price, and Tenors
    function handleMotorChange(motorId, mode, preselectedColor = '', preselectedTenor = '') {
        const selectColor = document.getElementById(mode === 'deal' ? 'selectColorDeal' : 'selectColorProspek');
        const displayOtr = document.getElementById(mode === 'deal' ? 'displayOtrDeal' : 'displayOtrProspek');
        const selectTenor = document.getElementById(mode === 'deal' ? 'selectTenorDeal' : 'selectTenorProspek');
        const displayAngsuran = document.getElementById(mode === 'deal' ? 'displayAngsuranDeal' : 'displayAngsuranProspek');

        selectColor.innerHTML = '';
        selectTenor.innerHTML = '';

        if (!motorId) {
            displayOtr.value = '—';
            if (displayAngsuran) displayAngsuran.value = '—';
            selectColor.innerHTML = '<option value="">Silakan pilih motor terlebih dahulu</option>';
            selectTenor.innerHTML = '<option value="">Silakan pilih motor terlebih dahulu</option>';
            return;
        }

        const selectedMoto = motorcyclesMaster.find(m => m.id == motorId);
        if (selectedMoto) {
            // Update OTR price automatically from master
            displayOtr.value = (selectedMoto.harga_otr && selectedMoto.harga_otr > 0)
                ? formatRupiah(selectedMoto.harga_otr)
                : 'Data harga belum tersedia';

            // Populate dependent colors
            selectColor.innerHTML = '<option value="">-- Pilih Warna Motor --</option>';
            if (selectedMoto.colors && selectedMoto.colors.length > 0) {
                selectedMoto.colors.forEach(col => {
                    const opt = document.createElement('option');
                    opt.value = col.nama_warna;
                    opt.textContent = col.nama_warna;
                    if (preselectedColor && preselectedColor.toUpperCase() === col.nama_warna.toUpperCase()) {
                        opt.selected = true;
                    }
                    selectColor.appendChild(opt);
                });
            } else {
                const defaultColors = ['Hitam', 'Putih', 'Merah', 'Biru', 'Silver'];
                defaultColors.forEach(col => {
                    const opt = document.createElement('option');
                    opt.value = col;
                    opt.textContent = col;
                    if (preselectedColor && preselectedColor.toUpperCase() === col.toUpperCase()) {
                        opt.selected = true;
                    }
                    selectColor.appendChild(opt);
                });
            }

            // Populate dependent tenors from master data
            selectTenor.innerHTML = '<option value="">-- Pilih Tenor Angsuran --</option>';
            if (selectedMoto.installments && selectedMoto.installments.length > 0) {
                selectedMoto.installments.forEach(ins => {
                    const opt = document.createElement('option');
                    opt.value = ins.tenor_bulan;
                    opt.textContent = ins.tenor_bulan + ' Bulan';
                    if (preselectedTenor && String(preselectedTenor) === String(ins.tenor_bulan)) {
                        opt.selected = true;
                    }
                    selectTenor.appendChild(opt);
                });
            } else {
                [11, 23, 35, 47].forEach(t => {
                    const opt = document.createElement('option');
                    opt.value = t;
                    opt.textContent = t + ' Bulan';
                    if (preselectedTenor && String(preselectedTenor) === String(t)) {
                        opt.selected = true;
                    }
                    selectTenor.appendChild(opt);
                });
            }

            // Update installment if tenor is already selected
            const activeTenor = selectTenor.value || preselectedTenor;
            handleTenorChange(activeTenor, mode);
        }
    }

    // Handle Dependent Tenor -> Angsuran Otomatis from Master Data Motor
    function handleTenorChange(tenor, mode) {
        const selectMotor = document.getElementById(mode === 'deal' ? 'selectMotorDeal' : 'selectMotorProspek');
        const displayAngsuran = document.getElementById(mode === 'deal' ? 'displayAngsuranDeal' : 'displayAngsuranProspek');

        if (!displayAngsuran) return;

        if (!tenor || !selectMotor.value) {
            displayAngsuran.value = '—';
            return;
        }

        const selectedMoto = motorcyclesMaster.find(m => m.id == selectMotor.value);
        if (selectedMoto && selectedMoto.installments && selectedMoto.installments.length > 0) {
            const ins = selectedMoto.installments.find(i => String(i.tenor_bulan) === String(tenor));
            if (ins && ins.nominal_angsuran > 0) {
                displayAngsuran.value = formatRupiah(ins.nominal_angsuran) + ' / bulan';
                return;
            }
        }

        // Fallback calculation if master installment not set
        if (selectedMoto && selectedMoto.harga_otr > 0 && tenor > 0) {
            const est = Math.round((selectedMoto.harga_otr * 1.15) / tenor);
            displayAngsuran.value = formatRupiah(est) + ' / bulan';
        } else {
            displayAngsuran.value = '—';
        }
    }

    // Load existing prospect data into Go To Deal form without retyping
    function loadProspectData(prospectId) {
        if (!prospectId) return;

        const p = pendingProspectsMaster.find(item => item.id == prospectId);
        if (!p) return;

        // Auto-populate Consumer Info
        document.getElementById('dealInputNama').value = p.nama_konsumen || p.name || '';
        document.getElementById('dealInputHp').value = p.nomor_telepon || p.phone || '';
        document.getElementById('dealInputAlamat').value = p.alamat || p.address || '';
        document.getElementById('dealInputKelurahan').value = p.kelurahan || '';
        document.getElementById('dealInputKecamatan').value = p.kecamatan || '';
        document.getElementById('dealInputKota').value = p.kota || 'Jakarta Timur';
        document.getElementById('dealInputProvinsi').value = p.provinsi || 'DKI Jakarta';
        document.getElementById('dealInputKodePos').value = p.kode_pos || '';
        document.getElementById('dealInputKtp').value = p.nomor_ktp || '';

        // Auto-populate Motor Info
        const selectMotorDeal = document.getElementById('selectMotorDeal');
        if (p.sepeda_motor_id) {
            selectMotorDeal.value = p.sepeda_motor_id;
            handleMotorChange(p.sepeda_motor_id, 'deal', p.warna_motor_diminati || '', p.tenor_bulan || '');
        }
    }

    // Set Skema Pembelian (Cash vs Kredit)
    function setSkemaPembelian(skema) {
        document.getElementById('inputSkemaPembelian').value = skema;
        const btnCash = document.getElementById('btnSkemaCash');
        const btnKredit = document.getElementById('btnSkemaKredit');
        const creditFields = document.getElementById('containerCreditFields');
        const starLeasing = document.getElementById('starLeasing');
        const selectLeasing = document.getElementById('selectLeasing');
        const inputDp = document.getElementById('inputDp');
        const selectTenor = document.getElementById('selectTenorDeal');

        if (skema === 'Kredit') {
            btnKredit.className = 'flex items-center justify-center gap-2 rounded-xl py-3 px-4 font-bold text-sm transition border-2 bg-[#1E3A8A] text-white border-[#1E3A8A] shadow-md';
            btnCash.className = 'flex items-center justify-center gap-2 rounded-xl py-3 px-4 font-bold text-sm transition border-2 bg-white text-slate-700 border-slate-300 hover:border-blue-400 hover:bg-blue-50 hover:text-[#1E3A8A] shadow-sm';
            creditFields.classList.remove('hidden');
            starLeasing.classList.remove('hidden');
            inputDp.required = true;
            selectTenor.required = true;
            if (selectLeasing.value === 'Cash Dealer / Tunai Langsung') {
                selectLeasing.value = 'BAF (Bussan Auto Finance)';
            }
        } else {
            btnCash.className = 'flex items-center justify-center gap-2 rounded-xl py-3 px-4 font-bold text-sm transition border-2 bg-[#1E3A8A] text-white border-[#1E3A8A] shadow-md';
            btnKredit.className = 'flex items-center justify-center gap-2 rounded-xl py-3 px-4 font-bold text-sm transition border-2 bg-white text-slate-700 border-slate-300 hover:border-blue-400 hover:bg-blue-50 hover:text-[#1E3A8A] shadow-sm';
            creditFields.classList.add('hidden');
            starLeasing.classList.add('hidden');
            inputDp.required = false;
            selectTenor.required = false;
            selectLeasing.value = 'Cash Dealer / Tunai Langsung';
        }
    }

    // Initialize dependent dropdowns on DOM ready
    document.addEventListener('DOMContentLoaded', () => {
        if (initialMotoIdProspek) {
            handleMotorChange(initialMotoIdProspek, 'prospek', initialColorProspek, initialTenorProspek);
        }
        if (initialMotoIdDeal) {
            handleMotorChange(initialMotoIdDeal, 'deal', initialColorDeal, initialTenorDeal);
        }
    });
</script>
@endsection
