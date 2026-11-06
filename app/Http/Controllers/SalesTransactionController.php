<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;
use App\Models\Prospect;
use App\Models\SalesTransaction;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SalesTransactionController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = SalesTransaction::with(['sales', 'motorcycle', 'color', 'prospect']);

        // Non-supervisor can only see their own sales transactions
        if (!$user->isSupervisor()) {
            $query->where('user_id', $user->id);
        } elseif ($request->filled('sales_id')) {
            $query->where('user_id', $request->input('sales_id'));
        }

        if ($request->filled('status')) {
            $query->where('status_transaksi', $request->input('status'));
        }

        if ($request->filled('motorcycle_id')) {
            $query->where('sepeda_motor_id', $request->input('motorcycle_id'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_transaksi', [$request->input('start_date'), $request->input('end_date')]);
        } elseif ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal_transaksi', $request->input('bulan'))
                  ->whereYear('tanggal_transaksi', $request->input('tahun'));
        }

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('nomor_transaksi', 'like', "%{$q}%")
                    ->orWhere('nama_konsumen', 'like', "%{$q}%")
                    ->orWhere('nomor_telepon_konsumen', 'like', "%{$q}%");
            });
        }

        $transactions = $query->orderBy('tanggal_transaksi', 'desc')->paginate(15)->withQueryString();

        $motorcycles = Motorcycle::where('status_aktif', true)->orderBy('nama_model')->get();
        $salesList = User::where('peran', 'sales')->where('status_aktif', true)->orderBy('nama')->get();

        // Summary stats
        $statsQuery = clone $query;
        $totalUnits = (clone $statsQuery)->where('status_transaksi', 'valid')->sum('jumlah_unit');
        $totalAmount = (clone $statsQuery)->where('status_transaksi', 'valid')->sum('total_harga');

        return view('transactions.index', compact(
            'transactions',
            'motorcycles',
            'salesList',
            'totalUnits',
            'totalAmount'
        ));
    }

    public function create(): View
    {
        $user = Auth::user();
        $motorcycles = Motorcycle::with('colors')->where('status_aktif', true)->orderBy('nama_model')->get();
        $salesList = User::where('peran', 'sales')->where('status_aktif', true)->orderBy('nama')->get();
        
        $prospectsQuery = Prospect::with('motorcycle')
            ->whereNull('deleted_at')
            ->where(function ($q) {
                $q->where('tahap_data', 'deal')
                  ->orWhereHas('status', fn($sq) => $sq->where('kode', 'deal'));
            })
            ->whereDoesntHave('salesTransactions');

        if (!$user->isSupervisor()) {
            $prospectsQuery->where('user_id', $user->id);
        }
        $prospects = $prospectsQuery->orderBy('nama_konsumen')->get();

        // Default next transaction code
        $prefix = 'TRX-' . date('Ym') . '-';
        $lastTrx = SalesTransaction::where('nomor_transaksi', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();
        $seq = $lastTrx ? ((int) substr($lastTrx->nomor_transaksi, -4) + 1) : 1;
        $nomorTransaksi = $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);

        return view('transactions.create', compact('motorcycles', 'salesList', 'prospects', 'nomorTransaksi'));
    }

    public function store(Request $request): RedirectResponse
    {
        $currentUser = Auth::user();

        $rules = [
            'nomor_transaksi' => ['required', 'string', 'max:50', 'unique:sales_transactions,nomor_transaksi'],
            'sepeda_motor_id' => ['required', 'exists:sepeda_motor,id'],
            'warna_motor_id' => ['nullable', 'exists:warna_motor,id'],
            'nama_warna' => ['nullable', 'string', 'max:100'],
            'jumlah_unit' => ['required', 'integer', 'min:1', 'max:100'],
            'harga_satuan' => ['required', 'numeric', 'min:0'],
            'skema_pembelian' => ['required', 'in:cash,kredit'],
            'leasing' => ['nullable', 'string', 'max:100'],
            'nama_konsumen' => ['required', 'string', 'max:150'],
            'nomor_telepon_konsumen' => ['nullable', 'string', 'max:30'],
            'alamat_konsumen' => ['nullable', 'string'],
            'prospek_id' => ['nullable', 'exists:prospek,id'],
            'tanggal_transaksi' => ['required', 'date'],
            'status_transaksi' => ['required', 'in:valid,batal'],
            'catatan' => ['nullable', 'string'],
        ];

        if ($currentUser->isSupervisor()) {
            $rules['user_id'] = ['required', 'exists:users,id'];
        }

        $validated = $request->validate($rules);

        $salesId = $currentUser->isSupervisor() ? $validated['user_id'] : $currentUser->id;
        $totalHarga = $validated['jumlah_unit'] * $validated['harga_satuan'];

        $transaction = SalesTransaction::create([
            'nomor_transaksi' => $validated['nomor_transaksi'],
            'user_id' => $salesId,
            'sepeda_motor_id' => $validated['sepeda_motor_id'],
            'warna_motor_id' => $validated['warna_motor_id'] ?? null,
            'nama_warna' => $validated['nama_warna'] ?? null,
            'jumlah_unit' => $validated['jumlah_unit'],
            'harga_satuan' => $validated['harga_satuan'],
            'total_harga' => $totalHarga,
            'skema_pembelian' => $validated['skema_pembelian'],
            'leasing' => $validated['leasing'] ?? null,
            'nama_konsumen' => $validated['nama_konsumen'],
            'nomor_telepon_konsumen' => $validated['nomor_telepon_konsumen'] ?? null,
            'alamat_konsumen' => $validated['alamat_konsumen'] ?? null,
            'prospek_id' => $validated['prospek_id'] ?? null,
            'tanggal_transaksi' => $validated['tanggal_transaksi'],
            'status_transaksi' => $validated['status_transaksi'],
            'catatan' => $validated['catatan'] ?? null,
            'dibuat_oleh' => $currentUser->id,
        ]);

        ActivityLogService::log(
            activityType: 'tambah',
            module: 'transaksi',
            description: "Mencatat transaksi penjualan baru: {$transaction->nomor_transaksi} ({$transaction->jumlah_unit} unit - Konsumen {$transaction->nama_konsumen}).",
            record: $transaction,
            afterData: [
                'nomor_transaksi' => $transaction->nomor_transaksi,
                'konsumen' => $transaction->nama_konsumen,
                'unit' => $transaction->jumlah_unit,
                'status' => $transaction->status_transaksi
            ]
        );

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi penjualan ' . $transaction->nomor_transaksi . ' berhasil dicatat!');
    }

    public function show(SalesTransaction $transaction): View
    {
        $user = Auth::user();
        if (!$user->isSupervisor() && $transaction->user_id !== $user->id) {
            abort(403, 'Akses tidak diizinkan untuk transaksi sales lain.');
        }

        $transaction->load(['sales', 'motorcycle', 'color', 'prospect', 'creator']);
        return view('transactions.show', compact('transaction'));
    }

    public function edit(SalesTransaction $transaction): View
    {
        $user = Auth::user();
        if (!$user->isSupervisor() && $transaction->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses mengubah transaksi ini.');
        }

        $motorcycles = Motorcycle::with('colors')->where('status_aktif', true)->orderBy('nama_model')->get();
        $salesList = User::where('peran', 'sales')->where('status_aktif', true)->orderBy('nama')->get();

        $prospectsQuery = Prospect::with('motorcycle')
            ->whereNull('deleted_at')
            ->where(function ($q) {
                $q->where('tahap_data', 'deal')
                  ->orWhereHas('status', fn($sq) => $sq->where('kode', 'deal'));
            })
            ->where(function ($q) use ($transaction) {
                $q->whereDoesntHave('salesTransactions')
                  ->orWhere('id', $transaction->prospek_id);
            });

        if (!$user->isSupervisor()) {
            $prospectsQuery->where('user_id', $user->id);
        }
        $prospects = $prospectsQuery->orderBy('nama_konsumen')->get();

        return view('transactions.edit', compact('transaction', 'motorcycles', 'salesList', 'prospects'));
    }

    public function update(Request $request, SalesTransaction $transaction): RedirectResponse
    {
        $user = Auth::user();
        if (!$user->isSupervisor() && $transaction->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses mengubah transaksi ini.');
        }

        $rules = [
            'sepeda_motor_id' => ['required', 'exists:sepeda_motor,id'],
            'warna_motor_id' => ['nullable', 'exists:warna_motor,id'],
            'nama_warna' => ['nullable', 'string', 'max:100'],
            'jumlah_unit' => ['required', 'integer', 'min:1', 'max:100'],
            'harga_satuan' => ['required', 'numeric', 'min:0'],
            'skema_pembelian' => ['required', 'in:cash,kredit'],
            'leasing' => ['nullable', 'string', 'max:100'],
            'nama_konsumen' => ['required', 'string', 'max:150'],
            'nomor_telepon_konsumen' => ['nullable', 'string', 'max:30'],
            'alamat_konsumen' => ['nullable', 'string'],
            'prospek_id' => ['nullable', 'exists:prospek,id'],
            'tanggal_transaksi' => ['required', 'date'],
            'status_transaksi' => ['required', 'in:valid,batal'],
            'catatan' => ['nullable', 'string'],
        ];

        if ($user->isSupervisor()) {
            $rules['user_id'] = ['required', 'exists:users,id'];
        }

        $validated = $request->validate($rules);
        $totalHarga = $validated['jumlah_unit'] * $validated['harga_satuan'];

        $updateData = [
            'sepeda_motor_id' => $validated['sepeda_motor_id'],
            'warna_motor_id' => $validated['warna_motor_id'] ?? null,
            'nama_warna' => $validated['nama_warna'] ?? null,
            'jumlah_unit' => $validated['jumlah_unit'],
            'harga_satuan' => $validated['harga_satuan'],
            'total_harga' => $totalHarga,
            'skema_pembelian' => $validated['skema_pembelian'],
            'leasing' => $validated['leasing'] ?? null,
            'nama_konsumen' => $validated['nama_konsumen'],
            'nomor_telepon_konsumen' => $validated['nomor_telepon_konsumen'] ?? null,
            'alamat_konsumen' => $validated['alamat_konsumen'] ?? null,
            'prospek_id' => $validated['prospek_id'] ?? null,
            'tanggal_transaksi' => $validated['tanggal_transaksi'],
            'status_transaksi' => $validated['status_transaksi'],
            'catatan' => $validated['catatan'] ?? null,
        ];

        if ($user->isSupervisor() && isset($validated['user_id'])) {
            $updateData['user_id'] = $validated['user_id'];
        }

        $beforeData = [
            'status' => $transaction->status_transaksi,
            'unit' => $transaction->jumlah_unit,
            'total_harga' => $transaction->total_harga,
        ];

        $transaction->update($updateData);

        ActivityLogService::log(
            activityType: 'ubah',
            module: 'transaksi',
            description: "Memperbarui transaksi penjualan: {$transaction->nomor_transaksi} (Status: {$transaction->status_transaksi}).",
            record: $transaction,
            beforeData: $beforeData,
            afterData: [
                'status' => $transaction->status_transaksi,
                'unit' => $transaction->jumlah_unit,
                'total_harga' => $transaction->total_harga,
            ]
        );

        return redirect()->route('transactions.index')
            ->with('success', 'Data transaksi ' . $transaction->nomor_transaksi . ' berhasil diperbarui!');
    }

    public function destroy(SalesTransaction $transaction): RedirectResponse
    {
        $user = Auth::user();
        if (!$user->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang dapat menghapus transaksi.');
        }

        $nomor = $transaction->nomor_transaksi;

        ActivityLogService::log(
            activityType: 'hapus',
            module: 'transaksi',
            description: "Menghapus transaksi penjualan: {$nomor} (Konsumen: {$transaction->nama_konsumen}).",
            record: null,
            beforeData: ['nomor_transaksi' => $nomor, 'konsumen' => $transaction->nama_konsumen]
        );

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi ' . $nomor . ' berhasil dihapus.');
    }
}
