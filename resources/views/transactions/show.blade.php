@extends('layouts.app')

@section('title', 'Detail Transaksi ' . $transaction->nomor_transaksi)
@section('page-title', 'Detail Transaksi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-blue-100 text-[#0A4DF3]">
                    SIMAKIS
                </span>
                <span class="text-xs text-slate-400 font-medium">Detail Transaksi Penjualan</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-1 flex items-center gap-3">
                <span>{{ $transaction->nomor_transaksi }}</span>
                @if($transaction->status_transaksi === 'valid')
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Valid
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                        Batal
                    </span>
                @endif
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Tanggal: <strong class="text-slate-800">{{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->locale('id')->isoFormat('dddd, D MMMM Y') }}</strong>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('transactions.index') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-2xs transition">
                <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Kembali</span>
            </a>
            <a href="{{ route('transactions.edit', $transaction->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0A4DF3] hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                <span>Edit Transaksi</span>
            </a>
            @if(auth()->user()->isSupervisor())
            <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data transaksi ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold transition">
                    Hapus
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Left 2 Cols: Unit & Payment Details -->
        <div class="md:col-span-2 space-y-6">

            <!-- Card: Unit Sepeda Motor -->
            <div class="bg-white rounded-2xl border border-hairline shadow-sm p-6 space-y-4">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Unit Sepeda Motor Yamaha</h2>
                        <p class="text-[11px] text-slate-400">Rincian tipe model dan varian unit yang dibeli</p>
                    </div>
                    <span class="text-xs font-bold text-[#0A4DF3] bg-blue-50 px-2.5 py-1 rounded-lg">
                        {{ $transaction->jumlah_unit }} Unit
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Model Sepeda Motor</span>
                        <span class="text-base font-extrabold text-slate-900">{{ $transaction->motorcycle->nama_model ?? 'Model Tidak Ditemukan' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Varian Warna</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $transaction->nama_warna ?: ($transaction->color->nama_warna ?? 'Standard') }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Harga Satuan (Kesepakatan)</span>
                        <span class="text-sm font-bold text-slate-800">Rp {{ number_format($transaction->harga_satuan, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Harga OTR Resmi</span>
                        <span class="text-sm font-medium text-slate-500">Rp {{ number_format($transaction->motorcycle->harga_otr ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Big Total Bar -->
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Total Nilai Transaksi</span>
                        <span class="text-xs text-slate-500">{{ $transaction->jumlah_unit }} Unit x Rp {{ number_format($transaction->harga_satuan, 0, ',', '.') }}</span>
                    </div>
                    <div class="text-2xl font-black text-[#0A4DF3]">
                        Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Card: Pembeli & Konsumen -->
            <div class="bg-white rounded-2xl border border-hairline shadow-sm p-6 space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-sm font-bold text-slate-900">Identitas Konsumen &amp; Skema Pembayaran</h2>
                    <p class="text-[11px] text-slate-400">Data pembeli dan metode pelunasan unit</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Nama Konsumen</span>
                        <span class="text-sm font-bold text-slate-900">{{ $transaction->nama_konsumen }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Nomor Telepon / WA</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $transaction->nomor_telepon_konsumen ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Metode Pembayaran</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold {{ $transaction->skema_pembelian === 'cash' ? 'bg-emerald-50 text-emerald-700' : 'bg-indigo-50 text-indigo-700' }}">
                            {{ strtoupper($transaction->skema_pembelian ?? 'CASH') }}
                        </span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Perusahaan Leasing</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $transaction->leasing ?: '-' }}</span>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Alamat Konsumen</span>
                        <p class="text-slate-700 leading-relaxed">{{ $transaction->alamat_konsumen ?: '-' }}</p>
                    </div>
                </div>

                @if($transaction->prospect)
                <div class="mt-2 pt-3 border-t border-slate-100 flex items-center justify-between text-xs bg-blue-50/50 p-3 rounded-xl border border-blue-100">
                    <div>
                        <span class="text-[10px] font-bold text-blue-600 uppercase block">Terhubung dengan Prospek</span>
                        <span class="font-bold text-slate-800">{{ $transaction->prospect->nama_konsumen }}</span>
                    </div>
                    <a href="{{ route('prospects.show', $transaction->prospect_id) }}" class="text-xs font-bold text-[#0A4DF3] hover:underline">
                        Lihat Data Prospek &rarr;
                    </a>
                </div>
                @endif
            </div>

            @if($transaction->catatan)
            <!-- Card: Catatan -->
            <div class="bg-white rounded-2xl border border-hairline shadow-sm p-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Catatan Tambahan Transaksi</h3>
                <p class="text-xs text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100">
                    {{ $transaction->catatan }}
                </p>
            </div>
            @endif

        </div>

        <!-- Right 1 Col: Metadata & Sales Profile -->
        <div class="space-y-6">

            <!-- Card: Sales Penanggung Jawab -->
            <div class="bg-white rounded-2xl border border-hairline shadow-sm p-5 space-y-3">
                <span class="text-[10px] uppercase font-bold text-slate-400 block">Sales Penanggung Jawab</span>
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-sm shrink-0">
                        {{ strtoupper(substr($transaction->sales->nama ?? 'S', 0, 2)) }}
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">{{ $transaction->sales->nama ?? '-' }}</h4>
                        <span class="text-[11px] text-slate-400 block">Kode: {{ $transaction->sales->kode_sales ?? '-' }}</span>
                        <span class="text-[10px] text-slate-400 block">{{ $transaction->sales->email ?? '' }}</span>
                    </div>
                </div>
            </div>

            <!-- Card: Riwayat Entri & Waktu -->
            <div class="bg-white rounded-2xl border border-hairline shadow-sm p-5 space-y-3 text-xs">
                <span class="text-[10px] uppercase font-bold text-slate-400 block">Informasi Sistem</span>
                <div class="space-y-2 text-slate-600">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Dibuat Pada:</span>
                        <span class="font-medium text-slate-800">{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Terakhir Update:</span>
                        <span class="font-medium text-slate-800">{{ $transaction->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($transaction->creator)
                    <div class="flex justify-between">
                        <span class="text-slate-400">Diinput Oleh:</span>
                        <span class="font-medium text-slate-800">{{ $transaction->creator->nama }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Link Laporan Sales -->
            <div class="bg-slate-50 border border-slate-200/70 rounded-2xl p-4 text-xs space-y-2">
                <span class="font-bold text-slate-800 block">Laporan Kinerja Terkait</span>
                <p class="text-[11px] text-slate-500">Transaksi ini secara otomatis terhitung pada target penjualan dan evaluasi kinerja sales.</p>
                <a href="{{ route('reports.sales-person', ['sales_id' => $transaction->user_id, 'bulan' => \Carbon\Carbon::parse($transaction->tanggal_transaksi)->month, 'tahun' => \Carbon\Carbon::parse($transaction->tanggal_transaksi)->year]) }}"
                   class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0A4DF3] hover:underline pt-1">
                    <span>Lihat Rekap Sales Ini</span>
                    <span>&rarr;</span>
                </a>
            </div>

        </div>

    </div>

</div>
@endsection
