@extends('layouts.app')

@section('title', 'Ubah Data Prospek — ' . ($prospect->nama_konsumen ?? $prospect->name))
@section('page-title', 'Ubah Data Prospek')

@section('content')
<div class="mx-auto max-w-3xl pb-12">

    <!-- Top Navigation Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl bg-white p-5 shadow-sm border-2 border-slate-200">
        <div class="flex items-center gap-3">
            <a href="{{ route('prospects.show', $prospect->id) }}" class="flex h-11 w-11 items-center justify-center rounded-xl border-2 border-slate-300 bg-white text-slate-700 hover:bg-blue-50 hover:text-[#1E3A8A] hover:border-blue-400 shadow-sm transition" title="Kembali ke Detail">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-heading-sm font-bold text-slate-900" id="headerTitle">
                        {{ old('tahap_data', $prospect->tahap_data ?? 'prospek') === 'deal' ? 'Ubah Transaksi Go To Deal' : 'Ubah Data Prospek' }}
                    </h2>
                    <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-bold text-[#1E3A8A] border border-blue-200">
                        #{{ $prospect->id }}
                    </span>
                </div>
                <p class="text-xs font-medium text-slate-500" id="headerSubtitle">
                    {{ old('tahap_data', $prospect->tahap_data ?? 'prospek') === 'deal' ? 'Perbarui rincian transaksi pembelian & pembiayaan konsumen' : 'Perbarui informasi calon konsumen & minat unit motor' }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 p-1.5 rounded-2xl bg-slate-100 border-2 border-slate-200">
            <button type="button" id="tabBtnProspek" onclick="switchFormTab('prospek')"
                    class="flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-bold transition shadow-sm {{ old('tahap_data', $prospect->tahap_data ?? 'prospek') === 'prospek' ? 'bg-[#1E3A8A] text-white border-2 border-[#1E3A8A]' : 'bg-white text-slate-700 border-2 border-slate-300 hover:border-blue-400 hover:bg-blue-50 hover:text-[#1E3A8A]' }}">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Prospek</span>
            </button>
            <button type="button" id="tabBtnDeal" onclick="switchFormTab('deal')"
                    class="flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-bold transition shadow-sm {{ old('tahap_data', $prospect->tahap_data ?? 'prospek') === 'deal' ? 'bg-[#1E3A8A] text-white border-2 border-[#1E3A8A]' : 'bg-white text-slate-700 border-2 border-slate-300 hover:border-blue-400 hover:bg-blue-50 hover:text-[#1E3A8A]' }}">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Go To Deal</span>
            </button>
        </div>
    </div>

    <!-- Main Edit Form -->
    <form id="formEditProspect" action="{{ route('prospects.update', $prospect->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Hidden Input for Active Mode (prospek / deal) -->
        <input type="hidden" name="tahap_data" id="inputTahapData" value="{{ old('tahap_data', $prospect->tahap_data ?? 'prospek') }}">

        <!-- ========================================================================= -->
        <!-- SECTION 1: DATA KONSUMEN & IDENTITAS                                      -->
        <!-- ========================================================================= -->
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
                    <input type="text" name="nama_konsumen" value="{{ old('nama_konsumen', $prospect->nama_konsumen ?? $prospect->name) }}" required
                           placeholder="Masukkan nama lengkap calon konsumen"
                           class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:border-[#1E3A8A] focus:outline-none @error('nama_konsumen') border-red-500 @enderror">
                    @error('nama_konsumen')
                        <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- No HP / WA -->
                <div>
                    <label class="mb-1 block text-body-sm font-bold text-slate-900">
                        No. HP / WhatsApp <span class="text-red-600">*</span>
                    </label>
                    <input type="tel" name="nomor_telepon" value="{{ old('nomor_telepon', $prospect->nomor_telepon ?? $prospect->phone) }}" required
                           placeholder="Contoh: 08123456789"
                           class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:border-[#1E3A8A] focus:outline-none @error('nomor_telepon') border-red-500 @enderror">
                    @error('nomor_telepon')
                        <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor KTP (Hanya terlihat/wajib saat Go To Deal) -->
                <div id="wrapperKtp" class="{{ old('tahap_data', $prospect->tahap_data ?? 'prospek') === 'deal' ? '' : 'hidden' }}">
                    <label class="mb-1 block text-body-sm font-bold text-slate-900">
                        Nomor KTP (16 Digit) <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="nomor_ktp" id="inputKtp" value="{{ old('nomor_ktp', $prospect->nomor_ktp) }}" maxlength="16"
                           placeholder="Nomor Induk Kependudukan (NIK)"
                           class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>

                <!-- Tanggal Lahir (Hanya terlihat saat Go To Deal) -->
                <div id="wrapperTglLahir" class="{{ old('tahap_data', $prospect->tahap_data ?? 'prospek') === 'deal' ? '' : 'hidden' }}">
                    <label class="mb-1 block text-body-sm font-bold text-slate-900">
                        Tanggal Lahir
                    </label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', optional($prospect->tanggal_lahir)->format('Y-m-d')) }}"
                           class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>

                <!-- Pekerjaan (Hanya terlihat saat Go To Deal) -->
                <div id="wrapperPekerjaan" class="{{ old('tahap_data', $prospect->tahap_data ?? 'prospek') === 'deal' ? '' : 'hidden' }}">
                    <label class="mb-1 block text-body-sm font-bold text-slate-900">
                        Pekerjaan / Profesi
                    </label>
                    <select name="pekerjaan" class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                        <option value="">-- Pilih Pekerjaan --</option>
                        <option value="Karyawan Swasta" {{ old('pekerjaan', $prospect->pekerjaan) == 'Karyawan Swasta' ? 'selected' : '' }}>Karyawan Swasta</option>
                        <option value="Pegawai Negeri (PNS/BUMN)" {{ old('pekerjaan', $prospect->pekerjaan) == 'Pegawai Negeri (PNS/BUMN)' ? 'selected' : '' }}>Pegawai Negeri (PNS/BUMN)</option>
                        <option value="Wiraswasta / Pengusaha" {{ old('pekerjaan', $prospect->pekerjaan) == 'Wiraswasta / Pengusaha' ? 'selected' : '' }}>Wiraswasta / Pengusaha</option>
                        <option value="Profesional (Dokter/Advokat)" {{ old('pekerjaan', $prospect->pekerjaan) == 'Profesional (Dokter/Advokat)' ? 'selected' : '' }}>Profesional (Dokter/Advokat)</option>
                        <option value="Pelajar / Mahasiswa" {{ old('pekerjaan', $prospect->pekerjaan) == 'Pelajar / Mahasiswa' ? 'selected' : '' }}>Pelajar / Mahasiswa</option>
                        <option value="Ibu Rumah Tangga" {{ old('pekerjaan', $prospect->pekerjaan) == 'Ibu Rumah Tangga' ? 'selected' : '' }}>Ibu Rumah Tangga</option>
                        <option value="Lainnya" {{ old('pekerjaan', $prospect->pekerjaan) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <!-- Alamat Lengkap -->
                <div class="md:col-span-2">
                    <label class="mb-1 block text-body-sm font-bold text-slate-900">
                        Alamat Lengkap <span class="text-red-600">*</span>
                    </label>
                    <textarea name="alamat" rows="2" required
                              placeholder="Nama jalan, nomor rumah, RT/RW..."
                              class="w-full rounded-xl border-2 border-slate-300 bg-white p-3.5 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:border-[#1E3A8A] focus:outline-none @error('alamat') border-red-500 @enderror">{{ old('alamat', $prospect->alamat ?? $prospect->address) }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kelurahan -->
                <div>
                    <label class="mb-1 block text-body-sm font-bold text-slate-900">Kelurahan</label>
                    <input type="text" name="kelurahan" value="{{ old('kelurahan', $prospect->kelurahan) }}"
                           placeholder="Kelurahan"
                           class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>

                <!-- Kecamatan -->
                <div>
                    <label class="mb-1 block text-body-sm font-bold text-slate-900">Kecamatan</label>
                    <input type="text" name="kecamatan" value="{{ old('kecamatan', $prospect->kecamatan) }}"
                           placeholder="Kecamatan"
                           class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>

                <!-- Kota -->
                <div>
                    <label class="mb-1 block text-body-sm font-bold text-slate-900">Kota / Kabupaten <span class="text-red-600">*</span></label>
                    <input type="text" name="kota" value="{{ old('kota', $prospect->kota ?? 'Jakarta Timur') }}" required
                           placeholder="Kota / Kabupaten"
                           class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>

                <!-- Provinsi -->
                <div>
                    <label class="mb-1 block text-body-sm font-bold text-slate-900">Provinsi <span class="text-red-600">*</span></label>
                    <input type="text" name="provinsi" value="{{ old('provinsi', $prospect->provinsi ?? 'DKI Jakarta') }}" required
                           placeholder="Provinsi"
                           class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>

                <!-- Kode Pos -->
                <div class="md:col-span-2">
                    <label class="mb-1 block text-body-sm font-bold text-slate-900">Kode Pos</label>
                    <input type="text" name="kode_pos" value="{{ old('kode_pos', $prospect->kode_pos) }}"
                           placeholder="Kode Pos"
                           class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- SECTION 2: DATA MOTOR (CLEAN DROPDOWN, WARNA, OTR, TENOR, ANGSURAN)       -->
        <!-- ========================================================================= -->
        <div class="rounded-2xl bg-white p-6 shadow-sm border-2 border-slate-200 space-y-4">
            <div class="flex items-center gap-3 border-b-2 border-slate-100 pb-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-[#1E3A8A] font-extrabold text-sm border-2 border-blue-200">
                    B
                </div>
                <div>
                    <h3 class="text-body-md font-bold text-[#1E3A8A]">Data Motor yang Diminati</h3>
                    <p class="text-xs text-slate-500">Pilihan tipe motor, warna, harga OTR, dan skema angsuran otomatis</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Tipe Motor (Clean dropdown: NO OTR price in label) -->
                <div class="md:col-span-2">
                    <label class="mb-1 block text-body-sm font-bold text-slate-900">
                        Tipe Motor <span class="text-red-600">*</span>
                    </label>
                    <select name="sepeda_motor_id" id="selectMotorEdit" required onchange="handleMotorChange(this.value)"
                            class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none @error('sepeda_motor_id') border-red-500 @enderror">
                        <option value="">-- Pilih Tipe Sepeda Motor Yamaha --</option>
                        @foreach($motorcycles as $moto)
                            <option value="{{ $moto->id }}" {{ (old('sepeda_motor_id', $prospect->sepeda_motor_id) == $moto->id) ? 'selected' : '' }}>
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
                    <select name="warna_motor_diminati" id="selectColorEdit" required
                            class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none @error('warna_motor_diminati') border-red-500 @enderror">
                        <option value="">Silakan pilih motor terlebih dahulu</option>
                    </select>
                    <p class="mt-1 text-[11px] text-slate-500" id="colorHint">Warna menyesuaikan tipe motor yang dipilih.</p>
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
                        <input type="text" id="displayOtrEdit" readonly
                               value="—"
                               placeholder="—"
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

                <!-- Tenor Angsuran (Hanya aktif untuk mode prospek atau kredit) -->
                <div>
                    <label class="mb-1 block text-body-sm font-bold text-slate-900">
                        Tenor Angsuran
                    </label>
                    <select name="tenor_bulan" id="selectTenorEdit" onchange="handleTenorChange(this.value)"
                            class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                        <option value="">Silakan pilih motor terlebih dahulu</option>
                    </select>
                    <p class="mt-1 text-[11px] text-slate-500">Pilihan tenor resmi yang tersedia dari master data.</p>
                </div>

                <!-- Angsuran Otomatis (Read Only / Locked) -->
                <div>
                    <label class="mb-1 block text-body-sm font-bold text-slate-900">
                        Angsuran (Otomatis)
                    </label>
                    <div class="relative">
                        <input type="text" id="displayAngsuranEdit" readonly
                               value="—"
                               placeholder="—"
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

        <!-- ========================================================================= -->
        <!-- SECTION 3: SUMBER & STATUS PROSPEK (Hanya untuk Mode Prospek)             -->
        <!-- ========================================================================= -->
        <div id="sectionSumberStatus" class="{{ old('tahap_data', $prospect->tahap_data ?? 'prospek') === 'deal' ? 'hidden' : '' }} rounded-2xl bg-white p-6 shadow-sm border-2 border-slate-200 space-y-4">
            <div class="flex items-center gap-3 border-b-2 border-slate-100 pb-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-[#1E3A8A] font-extrabold text-sm border-2 border-blue-200">
                    C
                </div>
                <div>
                    <h3 class="text-body-md font-bold text-[#1E3A8A]">Sumber & Status Prospek</h3>
                    <p class="text-xs text-slate-500">Klasifikasi asal prospek dan status pemantauan</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Sumber Prospek -->
                <div>
                    <label class="mb-1 block text-body-sm font-bold text-slate-900">
                        Sumber Prospek <span class="text-red-600">*</span>
                    </label>
                    <select name="sumber_prospek_id" id="selectSumberProspek"
                            class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                        @foreach($sources as $src)
                            <option value="{{ $src->id }}" {{ old('sumber_prospek_id', $prospect->sumber_prospek_id) == $src->id ? 'selected' : '' }}>
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
                    <select name="status_prospek_id" id="selectStatusProspek"
                            class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                        @foreach($statuses as $st)
                            <option value="{{ $st->id }}" {{ old('status_prospek_id', $prospect->status_prospek_id) == $st->id ? 'selected' : '' }}>
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
                        <select name="user_id"
                                class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                            @foreach($salesUsers as $sales)
                                <option value="{{ $sales->id }}" {{ old('user_id', $prospect->user_id) == $sales->id ? 'selected' : '' }}>
                                    {{ $sales->nama }} ({{ $sales->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- SECTION 4: DETAIL TRANSAKSI GO TO DEAL (Hanya untuk Mode Deal)            -->
        <!-- ========================================================================= -->
        <div id="sectionDetailDeal" class="{{ old('tahap_data', $prospect->tahap_data ?? 'prospek') === 'deal' ? '' : 'hidden' }} space-y-6">
            <!-- Skema Pembelian Card -->
            <div class="rounded-2xl bg-white p-6 shadow-sm border-2 border-slate-200 space-y-4">
                <div class="flex items-center gap-3 border-b-2 border-slate-100 pb-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-[#1E3A8A] font-extrabold text-sm border-2 border-blue-200">
                        C
                    </div>
                    <div>
                        <h3 class="text-body-md font-bold text-[#1E3A8A]">Detail Pembelian Go To Deal</h3>
                        <p class="text-xs text-slate-500">Skema transaksi tunai atau kredit pembiayaan</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Skema Pembelian -->
                    <div>
                        <label class="mb-2 block text-body-sm font-bold text-slate-900">
                            Skema Pembelian <span class="text-red-600">*</span>
                        </label>
                        <input type="hidden" name="skema_pembelian" id="inputSkemaPembelian" value="{{ old('skema_pembelian', $prospect->skema_pembelian ?? 'Cash') }}">
                        <div class="grid grid-cols-2 gap-3 max-w-md">
                            <button type="button" id="btnSkemaCash" onclick="setSkemaPembelian('Cash')"
                                    class="flex items-center justify-center gap-2 rounded-xl py-3 px-4 font-bold text-sm transition border-2 {{ old('skema_pembelian', $prospect->skema_pembelian ?? 'Cash') === 'Cash' ? 'bg-[#1E3A8A] text-white border-[#1E3A8A] shadow-md' : 'bg-white text-slate-700 border-slate-300 hover:border-blue-400 hover:bg-blue-50 hover:text-[#1E3A8A] shadow-sm' }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>Cash / Tunai</span>
                            </button>
                            <button type="button" id="btnSkemaKredit" onclick="setSkemaPembelian('Kredit')"
                                    class="flex items-center justify-center gap-2 rounded-xl py-3 px-4 font-bold text-sm transition border-2 {{ old('skema_pembelian', $prospect->skema_pembelian ?? 'Cash') === 'Kredit' ? 'bg-[#1E3A8A] text-white border-[#1E3A8A] shadow-md' : 'bg-white text-slate-700 border-slate-300 hover:border-blue-400 hover:bg-blue-50 hover:text-[#1E3A8A] shadow-sm' }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                <span>Kredit / Cicilan</span>
                            </button>
                        </div>
                    </div>

                    <!-- Fields Khusus Kredit -->
                    <div id="containerCreditFields" class="{{ old('skema_pembelian', $prospect->skema_pembelian ?? 'Cash') === 'Kredit' ? '' : 'hidden' }} grid grid-cols-1 md:grid-cols-2 gap-4 pt-3 border-t-2 border-slate-100">
                        <!-- DP -->
                        <div>
                            <label class="mb-1 block text-body-sm font-bold text-slate-900">
                                Uang Muka / DP (Rp) <span class="text-red-600">*</span>
                            </label>
                            <input type="number" name="dp" id="inputDp" value="{{ old('dp', $prospect->dp) }}"
                                   placeholder="Contoh: 3500000" min="0"
                                   class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Pembayaran & Leasing Card -->
            <div class="rounded-2xl bg-white p-6 shadow-sm border-2 border-slate-200 space-y-4">
                <div class="flex items-center gap-3 border-b-2 border-slate-100 pb-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-[#1E3A8A] font-extrabold text-sm border-2 border-blue-200">
                        D
                    </div>
                    <div>
                        <h3 class="text-body-md font-bold text-[#1E3A8A]">Informasi Pembayaran & Leasing</h3>
                        <p class="text-xs text-slate-500">Lembaga pembiayaan resmi leasing dan metode pembayaran konsumen</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Leasing -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Lembaga Pembiayaan / Leasing <span id="starLeasing" class="{{ old('skema_pembelian', $prospect->skema_pembelian ?? 'Cash') === 'Kredit' ? '' : 'hidden' }} text-red-600">*</span>
                        </label>
                        <select name="leasing" id="selectLeasing" class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                            <option value="">-- Pilih Lembaga Pembiayaan --</option>
                            <option value="BAF (Bussan Auto Finance)" {{ old('leasing', $prospect->leasing) == 'BAF (Bussan Auto Finance)' ? 'selected' : '' }}>BAF (Bussan Auto Finance)</option>
                            <option value="Adira Finance" {{ old('leasing', $prospect->leasing) == 'Adira Finance' ? 'selected' : '' }}>Adira Finance</option>
                            <option value="OTO Multiartha" {{ old('leasing', $prospect->leasing) == 'OTO Multiartha' ? 'selected' : '' }}>OTO Multiartha</option>
                            <option value="MUF (Mandiri Utama Finance)" {{ old('leasing', $prospect->leasing) == 'MUF (Mandiri Utama Finance)' ? 'selected' : '' }}>MUF (Mandiri Utama Finance)</option>
                            <option value="WOM Finance" {{ old('leasing', $prospect->leasing) == 'WOM Finance' ? 'selected' : '' }}>WOM Finance</option>
                            <option value="Mega Central Finance (MCF)" {{ old('leasing', $prospect->leasing) == 'Mega Central Finance (MCF)' ? 'selected' : '' }}>Mega Central Finance (MCF)</option>
                            <option value="Cash Dealer / Tunai Langsung" {{ old('leasing', $prospect->leasing ?? 'Cash Dealer / Tunai Langsung') == 'Cash Dealer / Tunai Langsung' ? 'selected' : '' }}>Cash Dealer / Tunai Langsung</option>
                        </select>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Metode Pembayaran <span class="text-red-600">*</span>
                        </label>
                        <select name="metode_pembayaran" class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-semibold text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            <option value="Transfer Bank BCA" {{ old('metode_pembayaran', $prospect->metode_pembayaran) == 'Transfer Bank BCA' ? 'selected' : '' }}>Transfer Bank BCA</option>
                            <option value="Transfer Bank Mandiri" {{ old('metode_pembayaran', $prospect->metode_pembayaran) == 'Transfer Bank Mandiri' ? 'selected' : '' }}>Transfer Bank Mandiri</option>
                            <option value="Transfer Bank BRI" {{ old('metode_pembayaran', $prospect->metode_pembayaran) == 'Transfer Bank BRI' ? 'selected' : '' }}>Transfer Bank BRI</option>
                            <option value="Transfer Bank BNI" {{ old('metode_pembayaran', $prospect->metode_pembayaran) == 'Transfer Bank BNI' ? 'selected' : '' }}>Transfer Bank BNI</option>
                            <option value="Kasir Tunai di Dealer" {{ old('metode_pembayaran', $prospect->metode_pembayaran ?? 'Kasir Tunai di Dealer') == 'Kasir Tunai di Dealer' ? 'selected' : '' }}>Kasir Tunai di Dealer</option>
                            <option value="Virtual Account / QRIS Dealer" {{ old('metode_pembayaran', $prospect->metode_pembayaran) == 'Virtual Account / QRIS Dealer' ? 'selected' : '' }}>Virtual Account / QRIS Dealer</option>
                        </select>
                    </div>

                    <!-- Tanggal Deal -->
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-body-sm font-bold text-slate-900">
                            Tanggal Go To Deal <span class="text-red-600">*</span>
                        </label>
                        <input type="date" name="tanggal_deal"
                               value="{{ old('tanggal_deal', optional($prospect->tanggal_deal)->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                               class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- SECTION 5: FOLLOW UP & CATATAN                                            -->
        <!-- ========================================================================= -->
        <div class="rounded-2xl bg-white p-6 shadow-sm border-2 border-slate-200 space-y-4">
            <div class="flex items-center gap-3 border-b-2 border-slate-100 pb-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-[#1E3A8A] font-extrabold text-sm border-2 border-blue-200">
                    {{ old('tahap_data', $prospect->tahap_data ?? 'prospek') === 'deal' ? 'E' : 'D' }}
                </div>
                <div>
                    <h3 class="text-body-md font-bold text-[#1E3A8A]">Follow Up & Catatan</h3>
                    <p class="text-xs text-slate-500">Agenda tindak lanjut atau catatan perjanjian transaksi</p>
                </div>
            </div>

            <div class="space-y-4">
                <!-- Tanggal Follow Up (Hanya jika tahap prospek) -->
                <div id="wrapperFollowUpDate" class="{{ old('tahap_data', $prospect->tahap_data ?? 'prospek') === 'deal' ? 'hidden' : '' }}">
                    <label class="mb-1 block text-body-sm font-bold text-slate-900">
                        Tanggal Follow Up Selanjutnya <span class="text-red-600">*</span>
                    </label>
                    <input type="date" name="tanggal_follow_up_selanjutnya"
                           value="{{ old('tanggal_follow_up_selanjutnya', optional($prospect->tanggal_follow_up_selanjutnya)->format('Y-m-d') ?? now()->addDays(2)->format('Y-m-d')) }}"
                           class="h-11 w-full rounded-xl border-2 border-slate-300 bg-white px-3.5 text-sm font-medium text-slate-900 focus:border-[#1E3A8A] focus:outline-none">
                </div>

                <!-- Catatan -->
                <div>
                    <label class="mb-1 block text-body-sm font-bold text-slate-900">
                        Catatan / Keterangan
                    </label>
                    <textarea name="catatan" rows="3"
                              placeholder="Tuliskan catatan follow-up, rincian negosiasi, atau keterangan pengiriman unit..."
                              class="w-full rounded-xl border-2 border-slate-300 bg-white p-3.5 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:border-[#1E3A8A] focus:outline-none">{{ old('catatan', $prospect->catatan ?? $prospect->notes) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Prominent Bottom Submit Button -->
        <div class="pt-4">
            <button type="submit" id="btnSubmitMain"
                    class="w-full flex items-center justify-center gap-2 rounded-2xl bg-[#1E3A8A] py-4 text-base font-bold text-white shadow-md hover:bg-blue-900 active:scale-[0.99] border-2 border-[#1E3A8A] transition cursor-pointer">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span id="txtBtnSubmitMain">Simpan Perubahan Data</span>
            </button>
        </div>
    </form>
</div>

<!-- Master Data Injected as JSON for Instant Client-Side Reactivity -->
<script>
    const motorcyclesMaster = @json($motorcycles);
    const initialMotoId = "{{ old('sepeda_motor_id', $prospect->sepeda_motor_id) }}";
    const initialColor = "{{ old('warna_motor_diminati', $prospect->warna_motor_diminati) }}";
    const initialTenor = "{{ old('tenor_bulan', $prospect->tenor_bulan) }}";

    // Format number to IDR currency
    function formatRupiah(amount) {
        if (!amount || isNaN(amount) || amount <= 0) return '—';
        return 'Rp ' + Number(amount).toLocaleString('id-ID');
    }

    // Switch between PROSPEK and GO TO DEAL form tabs in edit mode
    function switchFormTab(targetTab) {
        document.getElementById('inputTahapData').value = targetTab;
        const titleEl = document.getElementById('headerTitle');
        const subtitleEl = document.getElementById('headerSubtitle');
        const btnProspek = document.getElementById('tabBtnProspek');
        const btnDeal = document.getElementById('tabBtnDeal');
        const wrapperKtp = document.getElementById('wrapperKtp');
        const wrapperTglLahir = document.getElementById('wrapperTglLahir');
        const wrapperPekerjaan = document.getElementById('wrapperPekerjaan');
        const sectionSumberStatus = document.getElementById('sectionSumberStatus');
        const sectionDetailDeal = document.getElementById('sectionDetailDeal');
        const wrapperFollowUpDate = document.getElementById('wrapperFollowUpDate');
        const inputKtp = document.getElementById('inputKtp');
        const btnSubmitMain = document.getElementById('btnSubmitMain');

        const activeClasses = 'flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-bold transition shadow-sm bg-[#1E3A8A] text-white border-2 border-[#1E3A8A]';
        const inactiveClasses = 'flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-bold transition shadow-sm bg-white text-slate-700 border-2 border-slate-300 hover:border-blue-400 hover:bg-blue-50 hover:text-[#1E3A8A]';

        if (targetTab === 'deal') {
            titleEl.textContent = 'Ubah Transaksi Go To Deal';
            subtitleEl.textContent = 'Perbarui rincian transaksi pembelian & pembiayaan konsumen';
            btnDeal.className = activeClasses;
            btnProspek.className = inactiveClasses;

            wrapperKtp.classList.remove('hidden');
            wrapperTglLahir.classList.remove('hidden');
            wrapperPekerjaan.classList.remove('hidden');
            sectionSumberStatus.classList.add('hidden');
            sectionDetailDeal.classList.remove('hidden');
            wrapperFollowUpDate.classList.add('hidden');
            if (inputKtp) inputKtp.required = true;

            btnSubmitMain.className = 'w-full flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 py-4 text-base font-bold text-white shadow-md hover:bg-emerald-700 active:scale-[0.99] border-2 border-emerald-600 transition cursor-pointer';
        } else {
            titleEl.textContent = 'Ubah Data Prospek';
            subtitleEl.textContent = 'Perbarui informasi calon konsumen & minat unit motor';
            btnProspek.className = activeClasses;
            btnDeal.className = inactiveClasses;

            wrapperKtp.classList.add('hidden');
            wrapperTglLahir.classList.add('hidden');
            wrapperPekerjaan.classList.add('hidden');
            sectionSumberStatus.classList.remove('hidden');
            sectionDetailDeal.classList.add('hidden');
            wrapperFollowUpDate.classList.remove('hidden');
            if (inputKtp) inputKtp.required = false;

            btnSubmitMain.className = 'w-full flex items-center justify-center gap-2 rounded-2xl bg-[#1E3A8A] py-4 text-base font-bold text-white shadow-md hover:bg-blue-900 active:scale-[0.99] border-2 border-[#1E3A8A] transition cursor-pointer';
        }
    }

    // Handle Dependent Motorcycle -> Colors, OTR Price & Tenors
    function handleMotorChange(motorId, preselectedColor = '', preselectedTenor = '') {
        const selectColor = document.getElementById('selectColorEdit');
        const displayOtr = document.getElementById('displayOtrEdit');
        const selectTenor = document.getElementById('selectTenorEdit');
        const displayAngsuran = document.getElementById('displayAngsuranEdit');

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

            const activeTenor = selectTenor.value || preselectedTenor;
            handleTenorChange(activeTenor);
        }
    }

    // Handle Dependent Tenor -> Angsuran Otomatis from Master Data Motor
    function handleTenorChange(tenor) {
        const selectMotor = document.getElementById('selectMotorEdit');
        const displayAngsuran = document.getElementById('displayAngsuranEdit');

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

        if (selectedMoto && selectedMoto.harga_otr > 0 && tenor > 0) {
            const est = Math.round((selectedMoto.harga_otr * 1.15) / tenor);
            displayAngsuran.value = formatRupiah(est) + ' / bulan';
        } else {
            displayAngsuran.value = '—';
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

        if (skema === 'Kredit') {
            btnKredit.className = 'flex items-center justify-center gap-2 rounded-xl py-3 px-4 font-bold text-sm transition border-2 bg-[#1E3A8A] text-white border-[#1E3A8A] shadow-md';
            btnCash.className = 'flex items-center justify-center gap-2 rounded-xl py-3 px-4 font-bold text-sm transition border-2 bg-white text-slate-700 border-slate-300 hover:border-blue-400 hover:bg-blue-50 hover:text-[#1E3A8A] shadow-sm';
            creditFields.classList.remove('hidden');
            starLeasing.classList.remove('hidden');
            inputDp.required = true;
            if (selectLeasing.value === 'Cash Dealer / Tunai Langsung') {
                selectLeasing.value = 'BAF (Bussan Auto Finance)';
            }
        } else {
            btnCash.className = 'flex items-center justify-center gap-2 rounded-xl py-3 px-4 font-bold text-sm transition border-2 bg-[#1E3A8A] text-white border-[#1E3A8A] shadow-md';
            btnKredit.className = 'flex items-center justify-center gap-2 rounded-xl py-3 px-4 font-bold text-sm transition border-2 bg-white text-slate-700 border-slate-300 hover:border-blue-400 hover:bg-blue-50 hover:text-[#1E3A8A] shadow-sm';
            creditFields.classList.add('hidden');
            starLeasing.classList.add('hidden');
            inputDp.required = false;
            selectLeasing.value = 'Cash Dealer / Tunai Langsung';
        }
    }

    // Initialize dependent dropdowns on DOM ready
    document.addEventListener('DOMContentLoaded', () => {
        if (initialMotoId) {
            handleMotorChange(initialMotoId, initialColor, initialTenor);
        }
    });
</script>
@endsection
