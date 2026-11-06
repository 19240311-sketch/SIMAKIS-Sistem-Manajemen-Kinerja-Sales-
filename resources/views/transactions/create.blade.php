@extends('layouts.app')

@section('title', 'Catat Transaksi Penjualan Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                Catat Transaksi Penjualan Motor
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Input data penjualan unit yang valid untuk memperbarui realisasi target dan kinerja.
            </p>
        </div>
        <a href="{{ route('transactions.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-hairline shadow-sm p-6 sm:p-8">
        <form action="{{ route('transactions.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Section 1: Informasi Transaksi -->
            <div class="pb-3 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-900">1. Data Dasar Transaksi</h2>
                <p class="text-[11px] text-slate-500">Nomor faktur, sales penanggung jawab, dan tanggal penjualan.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="nomor_transaksi" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Nomor Transaksi <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="nomor_transaksi" name="nomor_transaksi" value="{{ old('nomor_transaksi', $nomorTransaksi) }}" required
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs font-mono font-bold text-[#0A4DF3] focus:border-[#0A4DF3] focus:outline-none">
                    @error('nomor_transaksi') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tanggal_transaksi" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Tanggal Transaksi <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" id="tanggal_transaksi" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" required
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs focus:border-[#0A4DF3] focus:outline-none">
                    @error('tanggal_transaksi') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status_transaksi" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Status Transaksi <span class="text-rose-500">*</span>
                    </label>
                    <select id="status_transaksi" name="status_transaksi" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs font-bold focus:border-[#0A4DF3] focus:outline-none">
                        <option value="valid" {{ old('status_transaksi') === 'valid' ? 'selected' : '' }}>Valid (Berhasil Deal)</option>
                        <option value="batal" {{ old('status_transaksi') === 'batal' ? 'selected' : '' }}>Batal (Dibatalkan)</option>
                    </select>
                </div>
            </div>

            @if(auth()->user()->isSupervisor())
                <div>
                    <label for="user_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Sales Penanggung Jawab <span class="text-rose-500">*</span>
                    </label>
                    <select id="user_id" name="user_id" required class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-[#0A4DF3] focus:outline-none">
                        <option value="">Pilih Sales yang Melakukan Penjualan</option>
                        @foreach($salesList as $s)
                            <option value="{{ $s->id }}" {{ old('user_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }} ({{ $s->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
            @endif

            <!-- Hubungkan dengan Data Deal Konsumen -->
            <div class="rounded-2xl border border-blue-200 bg-blue-50/70 p-4 space-y-2">
                <div class="flex items-center justify-between">
                    <label for="prospek_id" class="text-xs font-bold text-[#0A4DF3] flex items-center gap-2">
                        <svg class="h-4 w-4 text-[#0A4DF3]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        Ambil dari Data Deal Konsumen (Otomatis Isi Rincian Motor & Konsumen)
                    </label>
                    <span class="text-[10px] text-blue-600 font-semibold bg-blue-100 px-2 py-0.5 rounded-full">Opsional</span>
                </div>
                <select id="prospek_id" name="prospek_id" onchange="autoFillFromProspect(this)"
                        class="w-full h-10 px-3.5 rounded-xl border border-blue-200 bg-white text-xs font-semibold text-slate-800 focus:border-[#0A4DF3] focus:outline-none shadow-2xs">
                    <option value="">{{ $prospects->isNotEmpty() ? '-- Pilih konsumen yang sudah Deal untuk mengisi otomatis --' : '-- Tidak ada data deal baru yang belum dibuatkan transaksi --' }}</option>
                    @foreach($prospects as $p)
                        @php
                            $pPrice = $p->harga_otr ?: ($p->motorcycle?->harga_otr ?? 0);
                        @endphp
                        <option value="{{ $p->id }}"
                                data-name="{{ $p->nama_konsumen }}"
                                data-phone="{{ $p->nomor_telepon }}"
                                data-address="{{ $p->alamat }}"
                                data-motor-id="{{ $p->sepeda_motor_id }}"
                                data-color="{{ $p->warna_motor_diminati }}"
                                data-price="{{ $pPrice }}"
                                data-skema="{{ strtolower($p->skema_pembelian ?? '') }}"
                                data-leasing="{{ $p->leasing }}"
                                {{ old('prospek_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_konsumen }} &bull; {{ $p->motorcycle?->nama_model ?: 'Unit belum dipilih' }}{{ $p->warna_motor_diminati ? ' ('.$p->warna_motor_diminati.')' : '' }} ({{ $p->nomor_telepon ?: 'Tanpa nomor' }})
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-500">Hanya menampilkan konsumen berstatus Deal yang belum pernah dibuatkan transaksi penjualan.</p>
            </div>

            <!-- Section 2: Unit Motor & Harga -->
            <div class="pt-2 pb-3 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-900">2. Rincian Unit Sepeda Motor</h2>
                <p class="text-[11px] text-slate-500">Pilih model motor, warna, jumlah unit, dan harga kesepakatan.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="sepeda_motor_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Model Sepeda Motor <span class="text-rose-500">*</span>
                    </label>
                    <select id="sepeda_motor_id" name="sepeda_motor_id" required onchange="handleMotorcycleChange(this)"
                            class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-[#0A4DF3] focus:outline-none">
                        <option value="">-- Pilih Model Motor Yamaha --</option>
                        @foreach($motorcycles as $m)
                            <option value="{{ $m->id }}" data-price="{{ $m->harga_otr }}" {{ old('sepeda_motor_id') == $m->id ? 'selected' : '' }}>
                                {{ $m->nama_model }} (OTR: Rp {{ number_format($m->harga_otr, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                    @error('sepeda_motor_id') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="nama_warna" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Varian Warna Motor
                    </label>
                    <input type="text" id="nama_warna" name="nama_warna" value="{{ old('nama_warna') }}"
                           placeholder="Contoh: Matte Black, Metallic Cyan"
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs focus:border-[#0A4DF3] focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="jumlah_unit" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Jumlah Unit <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" id="jumlah_unit" name="jumlah_unit" value="{{ old('jumlah_unit', 1) }}" min="1" max="100" required
                           oninput="calculateTotal()"
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs font-extrabold focus:border-[#0A4DF3] focus:outline-none">
                    @error('jumlah_unit') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="harga_satuan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Harga Satuan (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" id="harga_satuan" name="harga_satuan" value="{{ old('harga_satuan', 0) }}" min="0" required
                           oninput="calculateTotal()"
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs font-semibold focus:border-[#0A4DF3] focus:outline-none">
                    @error('harga_satuan') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Total Penjualan
                    </label>
                    <div id="total_display" class="h-10 px-3.5 rounded-xl bg-slate-100 flex items-center font-extrabold text-[#0A4DF3] text-xs">
                        Rp 0
                    </div>
                </div>
            </div>

            <!-- Section 3: Skema Pembayaran & Konsumen -->
            <div class="pt-2 pb-3 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-900">3. Metode Pembayaran &amp; Data Konsumen</h2>
                <p class="text-[11px] text-slate-500">Skema cash/kredit dan identitas pembeli.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="skema_pembelian" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Skema Pembelian <span class="text-rose-500">*</span>
                    </label>
                    <select id="skema_pembelian" name="skema_pembelian" required onchange="toggleLeasing(this)"
                            class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs font-bold focus:border-[#0A4DF3] focus:outline-none">
                        <option value="cash" {{ old('skema_pembelian') === 'cash' ? 'selected' : '' }}>Tunai / Cash</option>
                        <option value="kredit" {{ old('skema_pembelian') === 'kredit' ? 'selected' : '' }}>Kredit Leasing</option>
                    </select>
                </div>

                <div id="leasing_container">
                    <label for="leasing" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Perusahaan Leasing
                    </label>
                    <input type="text" id="leasing" name="leasing" value="{{ old('leasing') }}"
                           placeholder="Contoh: BAF, Adira Finance, OTO"
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs focus:border-[#0A4DF3] focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="nama_konsumen" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Nama Lengkap Konsumen <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="nama_konsumen" name="nama_konsumen" value="{{ old('nama_konsumen') }}" required
                           placeholder="Nama pembeli unit"
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs focus:border-[#0A4DF3] focus:outline-none">
                    @error('nama_konsumen') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="nomor_telepon_konsumen" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Nomor Telepon / WhatsApp Konsumen
                    </label>
                    <input type="text" id="nomor_telepon_konsumen" name="nomor_telepon_konsumen" value="{{ old('nomor_telepon_konsumen') }}"
                           placeholder="08xxxxxxxx"
                           class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs focus:border-[#0A4DF3] focus:outline-none">
                </div>
            </div>

            <div>
                <label for="alamat_konsumen" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Alamat Lengkap Konsumen
                </label>
                <textarea id="alamat_konsumen" name="alamat_konsumen" rows="2"
                          placeholder="Alamat domisili atau pengiriman unit..."
                          class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:border-[#0A4DF3] focus:outline-none">{{ old('alamat_konsumen') }}</textarea>
            </div>

            <div>
                <label for="catatan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Catatan Tambahan Transaksi
                </label>
                <textarea id="catatan" name="catatan" rows="2"
                          placeholder="Keterangan nomor rangka/mesin, bonus helm/jaket, dll..."
                          class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:border-[#0A4DF3] focus:outline-none">{{ old('catatan') }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('transactions.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0A4DF3] hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition">
                    Simpan Transaksi Penjualan
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    function handleMotorcycleChange(select) {
        const option = select.options[select.selectedIndex];
        const price = option.getAttribute('data-price');
        if (price) {
            document.getElementById('harga_satuan').value = price;
            calculateTotal();
        }
    }

    function calculateTotal() {
        const units = parseInt(document.getElementById('jumlah_unit').value) || 0;
        const price = parseInt(document.getElementById('harga_satuan').value) || 0;
        const total = units * price;
        document.getElementById('total_display').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    function toggleLeasing(select) {
        const container = document.getElementById('leasing_container');
        if (select.value === 'cash') {
            container.classList.add('opacity-40');
        } else {
            container.classList.remove('opacity-40');
        }
    }

    function autoFillFromProspect(select) {
        const opt = select.options[select.selectedIndex];
        if (opt && opt.value) {
            // 1. Data Konsumen
            document.getElementById('nama_konsumen').value = opt.getAttribute('data-name') || '';
            document.getElementById('nomor_telepon_konsumen').value = opt.getAttribute('data-phone') || '';
            document.getElementById('alamat_konsumen').value = opt.getAttribute('data-address') || '';

            // 2. Rincian Unit Sepeda Motor (Bagian 2)
            const motorId = opt.getAttribute('data-motor-id');
            const motorSelect = document.getElementById('sepeda_motor_id');
            if (motorId && motorSelect) {
                motorSelect.value = motorId;
            }

            const color = opt.getAttribute('data-color');
            if (color) {
                document.getElementById('nama_warna').value = color;
            }

            const price = opt.getAttribute('data-price');
            if (price && parseInt(price) > 0) {
                document.getElementById('harga_satuan').value = price;
            } else if (motorSelect && motorSelect.selectedIndex >= 0) {
                const motorOpt = motorSelect.options[motorSelect.selectedIndex];
                const motorPrice = motorOpt ? motorOpt.getAttribute('data-price') : 0;
                if (motorPrice) {
                    document.getElementById('harga_satuan').value = motorPrice;
                }
            }
            calculateTotal();

            // 3. Skema Pembelian & Leasing (Bagian 3)
            const skema = opt.getAttribute('data-skema') || '';
            const skemaSelect = document.getElementById('skema_pembelian');
            if (skema && skemaSelect) {
                if (skema.includes('kredit')) {
                    skemaSelect.value = 'kredit';
                } else if (skema.includes('cash') || skema.includes('tunai')) {
                    skemaSelect.value = 'cash';
                }
                toggleLeasing(skemaSelect);
            }

            const leasing = opt.getAttribute('data-leasing');
            if (leasing) {
                document.getElementById('leasing').value = leasing;
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        calculateTotal();
    });
</script>
@endsection
