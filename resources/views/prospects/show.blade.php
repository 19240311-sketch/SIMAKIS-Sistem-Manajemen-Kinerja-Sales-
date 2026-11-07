@extends('layouts.app')

@section('title', 'Detail Prospek — ' . $prospect->name)
@section('page-title', 'Detail Prospek Konsumen')

@section('content')
<div class="space-y-6">

    <!-- Top Navigation Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('prospects.index') }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs transition-colors shadow-2xs">
            <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span>Kembali ke Daftar Prospek</span>
        </a>

        <div class="flex items-center gap-2 flex-wrap">
            @if($prospect->wa_number)
                <a href="{{ $prospect->wa_link }}" target="_blank"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition active:scale-95"
                   style="background-color: #059669 !important; color: #ffffff !important;">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824zm-3.423-14.416c-6.627 0-12 5.373-12 12 0 2.115.548 4.102 1.509 5.831l-1.602 5.855 6.001-1.574c1.668.908 3.57 1.428 5.592 1.428 6.627 0 12-5.373 12-12 0-6.627-5.373-12-12-12zm0 21.6c-1.802 0-3.488-.501-4.93-1.37l-.353-.213-3.669.962.979-3.577-.234-.372c-.958-1.523-1.493-3.313-1.493-5.23 0-5.293 4.307-9.6 9.6-9.6 5.293 0 9.6 4.307 9.6 9.6 0 5.293-4.307 9.6-9.6 9.6z"/>
                    </svg>
                    <span>Chat WhatsApp Konsumen</span>
                </a>
            @endif

            @if(($prospect->tahap_data ?? 'prospek') !== 'deal' && !($prospect->status->status_akhir ?? false))
                <a href="{{ route('prospects.create', ['mode' => 'deal', 'prospect_id' => $prospect->id]) }}" class="btn-success">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                    <span>Lanjut ke Go To Deal</span>
                </a>
            @endif

            <a href="{{ route('prospects.edit', $prospect->id) }}" class="btn-secondary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                <span>Ubah Data</span>
            </a>

            <form id="deleteProspectForm" action="{{ route('prospects.destroy', $prospect->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="button" onclick="openDeleteModal()" class="btn-danger" title="Hapus Data Prospek">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    <span>Hapus</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Follow-up Reminder Banner (if overdue) -->
    @if($prospect->needs_follow_up)
        <div class="rounded-xl border border-amber-300 bg-amber-50/95 p-4 sm:p-5 shadow-elev-1 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-500 text-white font-bold">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-body-sm font-bold text-amber-950">
                        Perhatian: Prospek ini belum menerima follow-up selama {{ $prospect->days_since_last_activity }} hari!
                    </h4>
                    <p class="text-caption text-amber-900 mt-0.5">
                        Segera hubungi calon konsumen melalui form di bawah untuk menjaga minat pembelian.
                    </p>
                </div>
            </div>
            <a href="#notes" class="hidden sm:inline-flex h-9 items-center rounded-pill bg-amber-600 px-4 text-button-md text-white hover:bg-amber-700 transition">
                Catat Sekarang &darr;
            </a>
        </div>
    @endif

    <!-- 1. Prospect Status Tracker Component -->
    <div class="rounded-xl bg-surface p-6 border border-hairline shadow-elev-1">
        <h3 class="text-caption uppercase tracking-wider font-semibold text-mute mb-2">Perkembangan Tahapan Prospek</h3>
        <x-status-tracker :currentStatus="$prospect->status" :statuses="$statuses" />
    </div>

    <!-- Main 2-Column Section -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        <!-- Left Column: Prospect Summary Card (Section 5.2) -->
        <div class="space-y-6 lg:col-span-1">
            <div class="rounded-xl bg-surface p-6 border border-hairline shadow-elev-1 space-y-5">
                <!-- Header / Initial Avatar -->
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand text-on-primary font-display font-extrabold text-lg shadow-elev-2">
                            {{ $prospect->initials }}
                        </div>
                        <div>
                            <h2 class="text-heading-md font-bold text-ink">{{ $prospect->name }}</h2>
                            <p class="text-caption text-mute">ID Prospek: #{{ $prospect->id }}</p>
                        </div>
                    </div>
                    <x-status-badge :status="$prospect->status" />
                </div>

                <!-- Details List -->
                <div class="space-y-3 pt-3 border-t border-hairline text-body-sm">
                    <!-- Phone / WA -->
                    <div class="flex items-center justify-between">
                        <span class="text-mute flex items-center gap-2">
                            <svg class="h-4 w-4 text-mute" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                            Telepon / WA
                        </span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-primary">{{ $prospect->phone }}</span>
                            @if($prospect->wa_number)
                                <a href="{{ $prospect->wa_link }}" target="_blank"
                                   class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white border border-emerald-300 text-2xs font-bold transition">
                                    <span>Buka WA &rarr;</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Email -->
                    @if($prospect->email)
                        <div class="flex items-center justify-between">
                            <span class="text-mute flex items-center gap-2">
                                <svg class="h-4 w-4 text-mute" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                                Email
                            </span>
                            <span class="font-medium text-ink">{{ $prospect->email }}</span>
                        </div>
                    @endif

                    <!-- Motor Diminati -->
                    <div class="flex items-start justify-between">
                        <span class="text-mute flex items-center gap-2">
                            <svg class="h-4 w-4 text-mute" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.25V4.5m0 0H9.75M14.25 7.5H9.75" />
                            </svg>
                            Motor Diminati
                        </span>
                        <div class="text-right">
                            <span class="font-bold text-slate-900 block">{{ $prospect->motorcycle->full_display_name ?? ($prospect->motorcycle->nama_model ?? '-') }}</span>
                            @if($prospect->warna_motor_diminati)
                                <span class="inline-block rounded bg-blue-50 px-2 py-0.5 text-xs font-bold text-[#1E3A8A] border border-blue-200 mt-0.5">
                                    {{ $prospect->warna_motor_diminati }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Harga OTR -->
                    <div class="flex items-center justify-between">
                        <span class="text-mute">Harga OTR</span>
                        <span class="font-bold text-[#1E3A8A] text-body-sm">
                            Rp {{ number_format($prospect->harga_otr ?? ($prospect->motorcycle->harga_otr ?? 0), 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- Tenor & Angsuran jika ada -->
                    @if($prospect->tenor_bulan)
                        <div class="flex items-center justify-between">
                            <span class="text-mute">Tenor Angsuran</span>
                            <span class="font-semibold text-slate-800 text-caption">{{ $prospect->tenor_bulan }} Bulan</span>
                        </div>
                    @endif

                    @if($prospect->angsuran_per_bulan)
                        <div class="flex items-center justify-between">
                            <span class="text-mute">Angsuran / Bulan</span>
                            <span class="font-bold text-emerald-700 text-caption">Rp {{ number_format($prospect->angsuran_per_bulan, 0, ',', '.') }} / bln</span>
                        </div>
                    @endif

                    @if($prospect->tanggal_follow_up_selanjutnya)
                        <div class="flex items-center justify-between">
                            <span class="text-mute">Next Follow-up</span>
                            <span class="font-semibold text-primary text-caption">{{ $prospect->tanggal_follow_up_selanjutnya->format('d M Y') }}</span>
                        </div>
                    @endif

                    <!-- Sumber -->
                    <div class="flex items-center justify-between">
                        <span class="text-mute flex items-center gap-2">
                            <svg class="h-4 w-4 text-mute" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.778.099-1.533.284-2.253" />
                            </svg>
                            Sumber Asal
                        </span>
                        <span class="rounded-pill bg-canvas-soft px-2.5 py-0.5 text-caption font-medium border border-hairline">
                            {{ $prospect->source->nama_sumber ?? $prospect->source->name ?? '-' }}
                        </span>
                    </div>

                    <!-- Sales in Charge -->
                    <div class="flex items-center justify-between">
                        <span class="text-mute flex items-center gap-2">
                            <svg class="h-4 w-4 text-mute" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            Sales PJ
                        </span>
                        <span class="font-medium text-ink">{{ $prospect->user->nama ?? $prospect->user->name ?? '-' }}</span>
                    </div>
                </div>

                <!-- Address & Area Box -->
                @if($prospect->alamat || $prospect->address)
                    <div class="p-3.5 rounded-xl bg-canvas-soft border border-hairline text-caption space-y-1">
                        <strong class="text-ink block font-semibold text-[#1E3A8A]">Alamat Domisili:</strong>
                        <p class="text-body font-medium">{{ $prospect->alamat ?? $prospect->address }}</p>
                        @if($prospect->kelurahan || $prospect->kecamatan_kota_provinsi)
                            <p class="text-mute text-xs">
                                {{ $prospect->kelurahan ? 'Kel. ' . $prospect->kelurahan . ', ' : '' }}
                                {{ $prospect->kecamatan_kota_provinsi ?? '' }}
                                {{ $prospect->kode_pos ? ' (Kode POS: ' . $prospect->kode_pos . ')' : '' }}
                            </p>
                        @endif
                    </div>
                @endif

                <!-- Informasi Pribadi & Pembayaran (Deal Details) -->
                @if($prospect->nomor_ktp || $prospect->metode_pembayaran || $prospect->pekerjaan || $prospect->skema_pembelian)
                    <div class="p-3.5 rounded-xl bg-blue-50/70 border border-blue-200 text-caption space-y-2">
                        <strong class="text-[#1E3A8A] block font-bold">Rincian Go To Deal & Pembayaran:</strong>
                        @if($prospect->nomor_ktp)
                            <div class="flex justify-between"><span class="text-mute">No. KTP:</span> <span class="font-semibold text-ink">{{ $prospect->nomor_ktp }}</span></div>
                        @endif
                        @if($prospect->pekerjaan)
                            <div class="flex justify-between"><span class="text-mute">Pekerjaan:</span> <span class="text-ink">{{ $prospect->pekerjaan }}</span></div>
                        @endif
                        @if($prospect->skema_pembelian)
                            <div class="flex justify-between"><span class="text-mute">Skema:</span> <span class="font-bold text-[#1E3A8A]">{{ $prospect->skema_pembelian }}</span></div>
                        @endif
                        @if($prospect->leasing)
                            <div class="flex justify-between"><span class="text-mute">Leasing:</span> <span class="font-semibold text-ink">{{ $prospect->leasing }}</span></div>
                        @endif
                        @if($prospect->dp)
                            <div class="flex justify-between"><span class="text-mute">DP (Uang Muka):</span> <span class="font-semibold text-[#1E3A8A]">Rp {{ number_format($prospect->dp, 0, ',', '.') }}</span></div>
                        @endif
                        @if($prospect->tenor_bulan)
                            <div class="flex justify-between"><span class="text-mute">Tenor:</span> <span class="font-semibold text-ink">{{ $prospect->tenor_bulan }} Bulan</span></div>
                        @endif
                        @if($prospect->angsuran_per_bulan)
                            <div class="flex justify-between"><span class="text-mute">Angsuran / Bln:</span> <span class="font-bold text-emerald-700">Rp {{ number_format($prospect->angsuran_per_bulan, 0, ',', '.') }}</span></div>
                        @endif
                        @if($prospect->metode_pembayaran)
                            <div class="flex justify-between"><span class="text-mute">Pembayaran:</span> <span class="font-bold text-success">{{ $prospect->metode_pembayaran }}</span></div>
                        @endif
                    </div>
                @endif

                <!-- Dokumen Foto yang Diunggah -->
                @if($prospect->foto_identitas || $prospect->foto_kendaraan || $prospect->foto_rumah || $prospect->foto_kk)
                    <div class="p-3.5 rounded-xl bg-canvas-soft border border-hairline text-caption space-y-2">
                        <strong class="text-[#1E3A8A] block font-bold">Dokumen Foto:</strong>
                        <div class="grid grid-cols-2 gap-2 pt-1">
                            @if($prospect->foto_identitas)
                                <a href="{{ asset($prospect->foto_identitas) }}" target="_blank" class="block rounded-lg overflow-hidden border border-hairline group">
                                    <img src="{{ asset($prospect->foto_identitas) }}" class="h-16 w-full object-cover group-hover:scale-105 transition">
                                    <span class="block text-center text-xs py-0.5 bg-surface text-mute">Foto KTP</span>
                                </a>
                            @endif
                            @if($prospect->foto_kendaraan)
                                <a href="{{ asset($prospect->foto_kendaraan) }}" target="_blank" class="block rounded-lg overflow-hidden border border-hairline group">
                                    <img src="{{ asset($prospect->foto_kendaraan) }}" class="h-16 w-full object-cover group-hover:scale-105 transition">
                                    <span class="block text-center text-xs py-0.5 bg-surface text-mute">Foto Motor</span>
                                </a>
                            @endif
                            @if($prospect->foto_rumah)
                                <a href="{{ asset($prospect->foto_rumah) }}" target="_blank" class="block rounded-lg overflow-hidden border border-hairline group">
                                    <img src="{{ asset($prospect->foto_rumah) }}" class="h-16 w-full object-cover group-hover:scale-105 transition">
                                    <span class="block text-center text-xs py-0.5 bg-surface text-mute">Foto Rumah</span>
                                </a>
                            @endif
                            @if($prospect->foto_kk)
                                <a href="{{ asset($prospect->foto_kk) }}" target="_blank" class="block rounded-lg overflow-hidden border border-hairline group">
                                    <img src="{{ asset($prospect->foto_kk) }}" class="h-16 w-full object-cover group-hover:scale-105 transition">
                                    <span class="block text-center text-xs py-0.5 bg-surface text-mute">Foto KK</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                @if($prospect->notes || $prospect->catatan)
                    <div class="p-3.5 rounded-xl bg-canvas-soft border border-hairline text-caption space-y-1">
                        <strong class="text-ink block font-semibold text-[#1E3A8A]">Catatan Kebutuhan:</strong>
                        <p class="text-body">{{ $prospect->catatan ?? $prospect->notes }}</p>
                    </div>
                @endif

                <div class="pt-2 text-caption text-mute text-right">
                    Terdaftar sejak {{ $prospect->created_at->format('d M Y') }}
                </div>
            </div>
        </div>

        <!-- Right Column: Input Activity Form & Timeline -->
        <div class="space-y-6 lg:col-span-2">

            <!-- 2. Form Input Aktivitas Follow-up (Section 5.3) -->
            <div class="rounded-xl bg-surface p-6 border border-hairline shadow-elev-1 space-y-4">
                <div class="flex items-center justify-between border-b border-hairline pb-4">
                    <div>
                        <h3 class="text-heading-md font-bold text-ink">Catat Aktivitas Follow-up</h3>
                        <p class="text-caption text-mute">Setiap follow-up akan tersimpan abadi sebagai riwayat kronologis prospek.</p>
                    </div>
                </div>

                <form action="{{ route('prospects.activities.store', $prospect->id) }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Activity Type Selection (Phone vs Visit) -->
                    <div>
                        <label class="mb-xs block text-body-sm font-medium text-ink">Jenis Follow-up <span class="text-error">*</span></label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center justify-center gap-2 rounded-lg border border-hairline-strong p-3 cursor-pointer hover:bg-canvas-soft has-[:checked]:border-primary has-[:checked]:bg-primary-soft has-[:checked]:text-primary-ink font-semibold transition">
                                <input type="radio" name="type" value="phone" checked class="hidden">
                                <svg class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                </svg>
                                <span>Phone (Telepon / WA)</span>
                            </label>

                            <label class="flex items-center justify-center gap-2 rounded-lg border border-hairline-strong p-3 cursor-pointer hover:bg-canvas-soft has-[:checked]:border-accent has-[:checked]:bg-cyan-50 has-[:checked]:text-accent-ink font-semibold transition">
                                <input type="radio" name="type" value="visit" class="hidden">
                                <svg class="h-5 w-5 text-accent" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                <span>Visit (Kunjungan)</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Activity Date & Time -->
                        <div>
                            <label for="activity_at" class="mb-xs block text-body-sm font-medium text-ink">
                                Tanggal &amp; Waktu Follow-up <span class="text-error">*</span>
                            </label>
                            <input id="activity_at" name="activity_at" type="datetime-local"
                                   value="{{ date('Y-m-d\TH:i') }}" required
                                   max="{{ date('Y-m-d\TH:i') }}"
                                   class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
                        </div>

                        <!-- Update Prospect Status Option -->
                        <div>
                            <label for="new_status_id" class="mb-xs block text-body-sm font-medium text-ink">
                                Perbarui Status Prospek (Opsional)
                            </label>
                            <select id="new_status_id" name="prospect_status_id"
                                    class="h-10 w-full rounded-md border border-hairline-strong bg-surface px-3 text-body-sm text-ink focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0">
                                <option value="">-- Tetap ({{ $prospect->status->name }}) --</option>
                                @foreach($statuses as $st)
                                    <option value="{{ $st->id }}" {{ $prospect->prospect_status_id == $st->id ? 'selected' : '' }}>
                                        Ubah ke: {{ $st->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Activity Notes -->
                    <div>
                        <label for="notes" class="mb-xs block text-body-sm font-medium text-ink">
                            Catatan Hasil Komunikasi / Kunjungan <span class="text-error">*</span>
                        </label>
                        <textarea id="notes" name="notes" rows="3" required
                                  placeholder="Tuliskan poin-poin hasil percakapan atau respon calon konsumen..."
                                  class="w-full rounded-md border border-hairline-strong bg-surface p-3 text-body-md text-ink placeholder:text-mute focus:border-primary focus:shadow-focus focus:outline-none focus:ring-0"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex h-10 items-center justify-center gap-2 rounded-pill bg-primary px-6 text-button-md text-on-primary transition hover:bg-primary-deep hover:shadow-elev-2 active:scale-[0.98]">
                            Simpan Aktivitas
                        </button>
                    </div>
                </form>
            </div>

            <!-- 3. Phone & Visit Activity Timeline (Section 5.4) -->
            <div class="rounded-xl bg-surface p-6 border border-hairline shadow-elev-1 space-y-6">
                <div class="border-b border-hairline pb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-heading-md font-bold text-ink">Riwayat Kronologis Aktivitas</h3>
                        <p class="text-caption text-mute">Total {{ $prospect->activities->count() }} aktivitas follow-up tercatat</p>
                    </div>
                </div>

                @if($prospect->activities->count() > 0)
                    <div class="relative pl-6 border-l-2 border-hairline space-y-8 my-4">
                        @foreach($prospect->activities as $activity)
                            <div class="relative group">
                                <!-- Node Icon on Timeline Line -->
                                <div class="absolute -left-[37px] flex h-8 w-8 items-center justify-center rounded-full bg-surface border-2 {{ $activity->type === 'phone' ? 'border-primary text-primary ring-4 ring-primary-soft' : 'border-accent text-accent-ink ring-4 ring-cyan-50' }}">
                                    @if($activity->type === 'phone')
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                        </svg>
                                    @else
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                        </svg>
                                    @endif
                                </div>

                                <!-- Activity Content Card -->
                                <div class="rounded-lg border border-hairline bg-canvas-soft p-4 transition hover:border-primary-softer hover:bg-surface hover:shadow-elev-2">
                                    <div class="flex items-center justify-between gap-2 border-b border-hairline pb-2.5 mb-2.5">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-ink uppercase text-caption tracking-wider {{ $activity->type === 'phone' ? 'text-primary' : 'text-accent-ink' }}">
                                                {{ $activity->type === 'phone' ? 'Follow-up Phone' : 'Follow-up Visit' }}
                                            </span>
                                            @if($activity->status)
                                                <span class="text-caption text-mute">&bull;</span>
                                                <span class="text-caption font-semibold text-success-ink">Status diubah &rarr; {{ $activity->status->name }}</span>
                                            @endif
                                        </div>
                                        <time class="text-caption text-mute" title="{{ $activity->activity_at->format('d M Y H:i') }}">
                                            {{ $activity->activity_at->format('d M Y, H:i') }} ({{ $activity->activity_at->diffForHumans() }})
                                        </time>
                                    </div>

                                    <!-- Notes Content -->
                                    <p class="text-body-sm text-ink whitespace-pre-line leading-relaxed">
                                        {{ $activity->notes }}
                                    </p>

                                    <!-- Author Footer -->
                                    <div class="mt-3 pt-2 border-t border-hairline flex items-center justify-between text-[11px] text-mute">
                                        <span>Dicatat oleh: <strong class="text-ink font-semibold">{{ $activity->user->name ?? 'Sales' }}</strong></span>
                                        <span>Tersimpan secara aman</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="p-8 text-center text-mute space-y-2">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-canvas-soft text-mute">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-heading-md font-semibold text-ink">Belum Ada Aktivitas Follow-up</p>
                        <p class="text-body-sm text-mute">Gunakan formulir di atas untuk mencatat panggilan Phone atau kunjungan Visit pertama ke konsumen.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI HAPUS DATA PROSPEK -->
<div id="customDeleteModal" class="hidden" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
    <!-- Overlay hitam transparan sekitar 35%, tanpa efek blur -->
    <div id="deleteModalBackdrop" 
         onclick="closeDeleteModal()" 
         class="fixed inset-0 bg-black/35 z-[1000] transition-opacity duration-200 opacity-0"></div>

    <!-- Panel Modal: Posisi tepat di tengah layar horizontal & vertikal, sudut membulat 16px -->
    <div id="deleteModalPanel" 
         class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[1001] w-[calc(100%-32px)] max-w-md bg-white rounded-[16px] shadow-2xl border border-slate-100 p-5 sm:p-6 transition-all duration-200 opacity-0 scale-95 flex flex-col pointer-events-auto"
         style="border-radius: 16px; max-height: calc(100vh - 40px);">
        
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
            <!-- Ikon Peringatan Merah -->
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 border border-rose-100 text-rose-600">
                <svg class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>

            <!-- Detail & Pesan Konfirmasi -->
            <div class="flex-1 min-w-0 text-center sm:text-left">
                <h3 class="text-base sm:text-lg font-bold text-slate-900" id="deleteModalTitle">
                    Hapus Data Prospek?
                </h3>
                
                <div class="mt-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-left">
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Prospek yang akan dihapus:</span>
                    <span class="text-xs sm:text-sm font-bold text-slate-900 break-words mt-0.5 block">{{ $prospect->name }}</span>
                    <span class="text-[11px] text-slate-500 block mt-0.5">ID: #{{ $prospect->id }} &bull; {{ $prospect->phone }}</span>
                </div>

                <p class="mt-3 text-xs sm:text-[13px] text-slate-600 leading-relaxed text-left">
                    Apakah Anda yakin ingin menghapus data prospek ini? Seluruh data dan riwayat aktivitas terkait mungkin ikut terhapus. Tindakan ini tidak dapat dibatalkan.
                </p>

                <!-- Box Pesan Error Jika Proses Gagal -->
                <div id="deleteModalError" class="hidden mt-3 p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 font-medium text-left"></div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-6 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2.5 pt-4 border-t border-slate-100">
            <button type="button" 
                    id="btnCancelDelete" 
                    onclick="closeDeleteModal()" 
                    class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm px-5 py-2.5 border border-slate-300 transition active:scale-95 cursor-pointer shadow-sm">
                Batalkan
            </button>
            <button type="button" 
                    id="btnConfirmDelete" 
                    onclick="executeDelete()" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs sm:text-sm px-5 py-2.5 shadow-sm transition active:scale-95 cursor-pointer">
                Ya, Hapus Prospek
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let isDeleting = false;

    const deleteModal = document.getElementById('customDeleteModal');
    const deleteBackdrop = document.getElementById('deleteModalBackdrop');
    const deletePanel = document.getElementById('deleteModalPanel');
    const deleteErrorEl = document.getElementById('deleteModalError');
    const btnConfirm = document.getElementById('btnConfirmDelete');
    const btnCancel = document.getElementById('btnCancelDelete');

    function openDeleteModal() {
        if (isDeleting) return;
        deleteErrorEl.classList.add('hidden');
        deleteErrorEl.textContent = '';

        // Reset state tombol
        btnConfirm.disabled = false;
        btnCancel.disabled = false;
        btnConfirm.classList.remove('opacity-75', 'cursor-not-allowed');
        btnConfirm.innerHTML = 'Ya, Hapus Prospek';

        deleteModal.classList.remove('hidden');

        requestAnimationFrame(() => {
            deleteBackdrop.classList.remove('opacity-0');
            deleteBackdrop.classList.add('opacity-100');
            deletePanel.classList.remove('opacity-0', 'scale-95');
            deletePanel.classList.add('opacity-100', 'scale-100');
        });
    }

    function closeDeleteModal() {
        if (isDeleting) return;

        deleteBackdrop.classList.remove('opacity-100');
        deleteBackdrop.classList.add('opacity-0');
        deletePanel.classList.remove('opacity-100', 'scale-100');
        deletePanel.classList.add('opacity-0', 'scale-95');

        setTimeout(() => {
            deleteModal.classList.add('hidden');
        }, 200);
    }

    // Tutup saat tombol Escape ditekan
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !deleteModal.classList.contains('hidden') && !isDeleting) {
            closeDeleteModal();
        }
    });

    function executeDelete() {
        if (isDeleting) return;

        const form = document.getElementById('deleteProspectForm');
        if (!form) return;

        if (!navigator.onLine) {
            deleteErrorEl.textContent = 'Tidak ada sambungan internet. Silakan periksa koneksi Anda.';
            deleteErrorEl.classList.remove('hidden');
            return;
        }

        // Kunci proses untuk mencegah penghapusan ganda
        isDeleting = true;
        btnConfirm.disabled = true;
        btnCancel.disabled = true;
        btnConfirm.classList.add('opacity-75', 'cursor-not-allowed');

        // Tampilkan indikator loading
        btnConfirm.innerHTML = `
            <svg class="animate-spin -ml-0.5 mr-1.5 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Menghapus...</span>
        `;

        try {
            form.submit();
        } catch (err) {
            deleteErrorEl.textContent = 'Terjadi kesalahan sistem saat memproses penghapusan.';
            deleteErrorEl.classList.remove('hidden');
            btnConfirm.disabled = false;
            btnCancel.disabled = false;
            btnConfirm.classList.remove('opacity-75', 'cursor-not-allowed');
            btnConfirm.innerHTML = 'Ya, Hapus Prospek';
            isDeleting = false;
        }
    }
</script>
@endpush
@endsection
