@extends('layouts.app')

@section('title', 'Transaksi Penjualan Motor')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-hairline shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-blue-100 text-[#0A4DF3]">
                    SIMAKIS
                </span>
                <span class="text-xs text-slate-400 font-medium">Realisasi Penjualan Unit</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-1">
                Transaksi Penjualan Motor
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Pencatatan resmi transaksi penjualan unit sepeda motor dealer Yamaha JG Purwakarta.
            </p>
        </div>

        <div>
            <a href="{{ route('transactions.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A4DF3] hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Catat Transaksi Baru</span>
            </a>
        </div>
    </div>

    <!-- Summary Statistics Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-hairline shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Unit Terjual</span>
            <div class="text-2xl font-extrabold text-emerald-600">{{ $totalUnits }} <span class="text-xs font-semibold text-slate-500">Unit</span></div>
            <p class="text-[10px] text-slate-400 mt-0.5">Berdasarkan filter aktif</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-hairline shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Nilai Transaksi</span>
            <div class="text-xl font-extrabold text-slate-900 truncate">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
            <p class="text-[10px] text-slate-400 mt-0.5">Penjualan valid terverifikasi</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-hairline shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Entri Transaksi</span>
            <div class="text-2xl font-extrabold text-[#0A4DF3]">{{ $transactions->total() }} <span class="text-xs font-semibold text-slate-500">Data</span></div>
            <p class="text-[10px] text-slate-400 mt-0.5">Termasuk cash dan kredit</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-hairline shadow-sm flex flex-col justify-center">
            <a href="{{ route('reports.sales') }}" class="inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span>Lihat Laporan Penjualan</span>
            </a>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <form method="GET" action="{{ route('transactions.index') }}" class="bg-white p-4 rounded-2xl border border-hairline shadow-sm flex flex-wrap items-center justify-between gap-3 text-xs">
        <div class="flex flex-wrap items-center gap-2 flex-1">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="No. Transaksi / Konsumen..."
                   class="h-9 rounded-xl border border-slate-200 px-3 text-xs placeholder:text-slate-400 focus:border-[#0A4DF3] focus:outline-none min-w-[180px]">

            @if(auth()->user()->isSupervisor())
                <select name="sales_id" class="h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:outline-none">
                    <option value="">Semua Sales</option>
                    @foreach($salesList as $s)
                        <option value="{{ $s->id }}" {{ request('sales_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                    @endforeach
                </select>
            @endif

            <select name="motorcycle_id" class="h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:outline-none">
                <option value="">Semua Model Motor</option>
                @foreach($motorcycles as $m)
                    <option value="{{ $m->id }}" {{ request('motorcycle_id') == $m->id ? 'selected' : '' }}>{{ $m->nama_model }}</option>
                @endforeach
            </select>

            <select name="status" class="h-9 rounded-xl border border-slate-200 px-3 text-xs font-medium focus:outline-none">
                <option value="">Semua Status</option>
                <option value="valid" {{ request('status') === 'valid' ? 'selected' : '' }}>Valid</option>
                <option value="batal" {{ request('status') === 'batal' ? 'selected' : '' }}>Batal</option>
            </select>

            <div class="flex items-center gap-1.5 text-slate-500">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="h-9 rounded-xl border border-slate-200 px-2 text-xs focus:outline-none">
                <span>-</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="h-9 rounded-xl border border-slate-200 px-2 text-xs focus:outline-none">
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="h-9 px-4 rounded-xl bg-[#0A4DF3] text-white font-bold hover:bg-blue-700 transition">
                Filter
            </button>
            <a href="{{ route('transactions.index') }}" class="h-9 px-3 rounded-xl border border-slate-200 hover:bg-slate-50 flex items-center text-slate-600 font-semibold transition">
                Reset
            </a>
        </div>
    </form>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl border border-hairline shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                        <th class="py-3 px-4">No. Transaksi</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Sales Penanggung Jawab</th>
                        <th class="py-3 px-4">Konsumen</th>
                        <th class="py-3 px-4">Model &amp; Varian Motor</th>
                        <th class="py-3 px-4 text-center">Unit</th>
                        <th class="py-3 px-4 text-right">Total Nilai</th>
                        <th class="py-3 px-4 text-center">Metode</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-blue-50/40 transition">
                            <td class="py-3 px-4 font-mono font-bold text-[#0A4DF3]">
                                <a href="{{ route('transactions.show', $trx->id) }}" class="hover:underline">
                                    {{ $trx->nomor_transaksi }}
                                </a>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                {{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y') }}
                            </td>
                            <td class="py-3 px-4 font-semibold text-slate-900">
                                {{ $trx->sales->nama ?? '-' }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900">{{ $trx->nama_konsumen }}</div>
                                <div class="text-[10px] text-slate-400">{{ $trx->nomor_telepon_konsumen ?: '-' }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-semibold text-slate-800">{{ $trx->motorcycle->nama_model ?? '-' }}</div>
                                <div class="text-[10px] text-slate-500">{{ $trx->nama_warna ?: 'Warna Standar' }}</div>
                            </td>
                            <td class="py-3 px-4 text-center font-extrabold text-emerald-600 text-sm">
                                {{ $trx->jumlah_unit }}
                            </td>
                            <td class="py-3 px-4 text-right font-medium">
                                Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase border {{ $trx->skema_pembelian === 'kredit' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-blue-50 text-[#0A4DF3] border-blue-200' }}">
                                    {{ $trx->skema_pembelian }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($trx->status_transaksi === 'valid')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Valid
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Batal
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('transactions.show', $trx->id) }}"
                                       class="p-1 rounded-lg hover:bg-blue-50 text-slate-500 hover:text-[#0A4DF3] transition"
                                       title="Lihat Detail">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>

                                    <a href="{{ route('transactions.edit', $trx->id) }}"
                                       class="p-1 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-slate-800 transition"
                                       title="Ubah Transaksi">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </a>

                                    @if(auth()->user()->isSupervisor())
                                        <form action="{{ route('transactions.destroy', $trx->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Hapus data transaksi {{ $trx->nomor_transaksi }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded-lg hover:bg-rose-50 text-slate-500 hover:text-rose-600 transition" title="Hapus Transaksi">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-10 text-center text-slate-400 italic">
                                Belum ada transaksi penjualan yang tercatat pada kriteria ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
