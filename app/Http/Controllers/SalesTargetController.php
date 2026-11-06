<?php

namespace App\Http\Controllers;

use App\Models\SalesTarget;
use App\Models\User;
use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SalesTargetController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = SalesTarget::with(['user', 'creator']);

        if (!$user->isSupervisor()) {
            $query->where('user_id', $user->id);
        } elseif ($request->filled('sales_id')) {
            $query->where('user_id', $request->input('sales_id'));
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->input('tahun'));
        } else {
            $query->where('tahun', date('Y'));
        }

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->input('bulan'));
        }

        if ($request->filled('periode_tipe')) {
            $query->where('periode_tipe', $request->input('periode_tipe'));
        }

        $targets = $query->orderBy('tahun', 'desc')
                         ->orderBy('bulan', 'desc')
                         ->orderBy('tanggal_mulai', 'desc')
                         ->paginate(15)
                         ->withQueryString();

        $salesList = User::where('peran', 'sales')->where('status_aktif', true)->orderBy('nama')->get();

        return view('targets.index', compact('targets', 'salesList'));
    }

    public function create(): View
    {
        $user = Auth::user();
        if (!$user->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang berhak mengatur target penjualan.');
        }

        $salesList = User::where('peran', 'sales')->where('status_aktif', true)->orderBy('nama')->get();
        return view('targets.create', compact('salesList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (!$user->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang berhak mengatur target penjualan.');
        }

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'periode_tipe' => ['required', 'in:bulanan,mingguan,khusus'],
            'tahun' => ['required', 'integer', 'min:2020', 'max:2035'],
            'bulan' => ['nullable', 'integer', 'min:1', 'max:12'],
            'minggu_ke' => ['nullable', 'integer', 'min:1', 'max:5'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'target_unit' => ['required', 'integer', 'min:1', 'max:1000'],
            'catatan' => ['nullable', 'string'],
        ]);

        $t = SalesTarget::create([
            'user_id' => $validated['user_id'],
            'periode_tipe' => $validated['periode_tipe'],
            'tahun' => $validated['tahun'],
            'bulan' => $validated['bulan'] ?? null,
            'minggu_ke' => $validated['minggu_ke'] ?? null,
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'target_unit' => $validated['target_unit'],
            'catatan' => $validated['catatan'] ?? null,
            'dibuat_oleh' => $user->id,
        ]);

        ActivityLogService::log(
            activityType: 'tambah',
            module: 'target',
            description: "Menetapkan target penjualan {$t->target_unit} unit untuk sales {$t->user?->nama} (Periode {$t->periode_label}).",
            record: $t
        );

        return redirect()->route('targets.index')
            ->with('success', 'Target penjualan berhasil ditetapkan!');
    }

    public function show(SalesTarget $target): RedirectResponse
    {
        return redirect()->route('targets.index');
    }

    public function edit(SalesTarget $target): View
    {
        $user = Auth::user();
        if (!$user->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang berhak mengubah target penjualan.');
        }

        $salesList = User::where('peran', 'sales')->where('status_aktif', true)->orderBy('nama')->get();
        return view('targets.edit', compact('target', 'salesList'));
    }

    public function update(Request $request, SalesTarget $target): RedirectResponse
    {
        $user = Auth::user();
        if (!$user->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang berhak mengubah target penjualan.');
        }

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'periode_tipe' => ['required', 'in:bulanan,mingguan,khusus'],
            'tahun' => ['required', 'integer', 'min:2020', 'max:2035'],
            'bulan' => ['nullable', 'integer', 'min:1', 'max:12'],
            'minggu_ke' => ['nullable', 'integer', 'min:1', 'max:5'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'target_unit' => ['required', 'integer', 'min:1', 'max:1000'],
            'catatan' => ['nullable', 'string'],
        ]);

        $beforeData = ['target_unit' => $target->target_unit];

        $target->update($validated);

        ActivityLogService::log(
            activityType: 'ubah',
            module: 'target',
            description: "Memperbarui target penjualan sales {$target->user?->nama} menjadi {$target->target_unit} unit.",
            record: $target,
            beforeData: $beforeData,
            afterData: ['target_unit' => $target->target_unit]
        );

        return redirect()->route('targets.index')
            ->with('success', 'Target penjualan berhasil diperbarui!');
    }

    public function destroy(SalesTarget $target): RedirectResponse
    {
        $user = Auth::user();
        if (!$user->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang berhak menghapus target penjualan.');
        }

        $target->delete();

        return redirect()->route('targets.index')
            ->with('success', 'Target penjualan berhasil dihapus.');
    }
}
