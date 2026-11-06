<?php

namespace App\Http\Controllers;

use App\Models\FieldFinding;
use App\Models\Motorcycle;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FieldFindingController extends Controller
{
    public function index(Request $request): View
    {
        $currentUser = Auth::user();
        $query = FieldFinding::with(['user', 'motorcycle']);

        if (!$currentUser->isSupervisor()) {
            $query->where('user_id', $currentUser->id);
        } elseif ($request->filled('sales_id')) {
            $query->where('user_id', $request->input('sales_id'));
        }

        if ($request->filled('status')) {
            $query->where('status_tindak_lanjut', $request->input('status'));
        }

        if ($request->filled('status_minat')) {
            $query->where('status_minat', $request->input('status_minat'));
        }

        if ($request->filled('motorcycle_id')) {
            $query->where('sepeda_motor_id', $request->input('motorcycle_id'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_temuan', [$request->input('start_date'), $request->input('end_date')]);
        }

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_konsumen', 'like', "%{$q}%")
                    ->orWhere('kontak_konsumen', 'like', "%{$q}%")
                    ->orWhere('warna_motor', 'like', "%{$q}%")
                    ->orWhere('kendala', 'like', "%{$q}%")
                    ->orWhere('catatan_komunikasi', 'like', "%{$q}%");
            });
        }

        // Special filter: only pending
        if ($request->boolean('unresolved_only')) {
            $query->whereIn('status_tindak_lanjut', ['menunggu', 'sedang_diproses']);
        }

        $findings = $query->orderBy('tanggal_temuan', 'desc')->paginate(15)->withQueryString();

        $motorcycles = Motorcycle::where('status_aktif', true)->orderBy('nama_model')->get();
        $salesList = User::where('peran', 'sales')->where('status_aktif', true)->orderBy('nama')->get();

        // Pending count
        $unresolvedCount = FieldFinding::whereIn('status_tindak_lanjut', ['menunggu', 'sedang_diproses'])
            ->when(!$currentUser->isSupervisor(), fn($q) => $q->where('user_id', $currentUser->id))
            ->count();

        return view('field_findings.index', compact('findings', 'motorcycles', 'salesList', 'unresolvedCount'));
    }

    public function create(): View
    {
        $user = Auth::user();
        $motorcycles = Motorcycle::with('colors')->where('status_aktif', true)->orderBy('nama_model')->get();
        $salesList = User::where('peran', 'sales')->where('status_aktif', true)->orderBy('nama')->get();

        $prospects = Prospect::select('id', 'nama_konsumen', 'nomor_telepon', 'user_id')
            ->when(!$user->isSupervisor(), fn($q) => $q->where('user_id', $user->id))
            ->orderBy('nama_konsumen')
            ->get();

        return view('field_findings.create', compact('motorcycles', 'salesList', 'prospects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $currentUser = Auth::user();

        $rules = [
            'tanggal_temuan' => ['required', 'date'],
            'nama_konsumen' => ['nullable', 'string', 'max:150'],
            'kontak_konsumen' => ['nullable', 'string', 'max:50'],
            'sepeda_motor_id' => ['nullable', 'exists:sepeda_motor,id'],
            'warna_motor' => ['nullable', 'string', 'max:100'],
            'kisaran_anggaran' => ['nullable', 'numeric', 'min:0'],
            'metode_pembayaran' => ['required', 'in:cash,kredit,belum_pasti'],
            'status_minat' => ['required', 'in:rendah,sedang,tinggi'],
            'kendala' => ['nullable', 'string'],
            'catatan_komunikasi' => ['required', 'string'],
            'rencana_tindak_lanjut' => ['nullable', 'string'],
            'tanggal_tindak_lanjut' => ['nullable', 'date'],
            'status_tindak_lanjut' => ['required', 'in:menunggu,sedang_diproses,selesai,dibatalkan'],
        ];

        if ($currentUser->isSupervisor()) {
            $rules['user_id'] = ['required', 'exists:users,id'];
        }

        $validated = $request->validate($rules);
        $salesId = $currentUser->isSupervisor() ? $validated['user_id'] : $currentUser->id;

        FieldFinding::create([
            'user_id' => $salesId,
            'tanggal_temuan' => $validated['tanggal_temuan'],
            'nama_konsumen' => $validated['nama_konsumen'] ?? null,
            'kontak_konsumen' => $validated['kontak_konsumen'] ?? null,
            'sepeda_motor_id' => $validated['sepeda_motor_id'] ?? null,
            'warna_motor' => $validated['warna_motor'] ?? null,
            'kisaran_anggaran' => $validated['kisaran_anggaran'] ?? null,
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status_minat' => $validated['status_minat'],
            'kendala' => $validated['kendala'] ?? null,
            'catatan_komunikasi' => $validated['catatan_komunikasi'],
            'rencana_tindak_lanjut' => $validated['rencana_tindak_lanjut'] ?? null,
            'tanggal_tindak_lanjut' => $validated['tanggal_tindak_lanjut'] ?? null,
            'status_tindak_lanjut' => $validated['status_tindak_lanjut'],
        ]);

        return redirect()->route('field-findings.index')
            ->with('success', 'Catatan temuan lapangan berhasil disimpan!');
    }

    public function show(FieldFinding $fieldFinding): View
    {
        $currentUser = Auth::user();
        if (!$currentUser->isSupervisor() && $fieldFinding->user_id !== $currentUser->id) {
            abort(403, 'Akses terbatas untuk temuan sales lain.');
        }

        $fieldFinding->load(['user', 'motorcycle']);
        return view('field_findings.show', compact('fieldFinding'));
    }

    public function edit(FieldFinding $fieldFinding): View
    {
        $currentUser = Auth::user();
        if (!$currentUser->isSupervisor() && $fieldFinding->user_id !== $currentUser->id) {
            abort(403, 'Anda tidak berwenang mengubah temuan ini.');
        }

        $motorcycles = Motorcycle::with('colors')->where('status_aktif', true)->orderBy('nama_model')->get();
        $salesList = User::where('peran', 'sales')->where('status_aktif', true)->orderBy('nama')->get();

        $prospects = Prospect::select('id', 'nama_konsumen', 'nomor_telepon', 'user_id')
            ->when(!$currentUser->isSupervisor(), fn($q) => $q->where('user_id', $currentUser->id))
            ->orderBy('nama_konsumen')
            ->get();

        return view('field_findings.edit', compact('fieldFinding', 'motorcycles', 'salesList', 'prospects'));
    }

    public function update(Request $request, FieldFinding $fieldFinding): RedirectResponse
    {
        $currentUser = Auth::user();
        if (!$currentUser->isSupervisor() && $fieldFinding->user_id !== $currentUser->id) {
            abort(403, 'Anda tidak berwenang mengubah temuan ini.');
        }

        $rules = [
            'tanggal_temuan' => ['required', 'date'],
            'nama_konsumen' => ['nullable', 'string', 'max:150'],
            'kontak_konsumen' => ['nullable', 'string', 'max:50'],
            'sepeda_motor_id' => ['nullable', 'exists:sepeda_motor,id'],
            'warna_motor' => ['nullable', 'string', 'max:100'],
            'kisaran_anggaran' => ['nullable', 'numeric', 'min:0'],
            'metode_pembayaran' => ['required', 'in:cash,kredit,belum_pasti'],
            'status_minat' => ['required', 'in:rendah,sedang,tinggi'],
            'kendala' => ['nullable', 'string'],
            'catatan_komunikasi' => ['required', 'string'],
            'rencana_tindak_lanjut' => ['nullable', 'string'],
            'tanggal_tindak_lanjut' => ['nullable', 'date'],
            'status_tindak_lanjut' => ['required', 'in:menunggu,sedang_diproses,selesai,dibatalkan'],
            'catatan_hasil_tindak_lanjut' => ['nullable', 'string'],
        ];

        if ($currentUser->isSupervisor()) {
            $rules['user_id'] = ['required', 'exists:users,id'];
        }

        $validated = $request->validate($rules);

        $updateData = [
            'tanggal_temuan' => $validated['tanggal_temuan'],
            'nama_konsumen' => $validated['nama_konsumen'] ?? null,
            'kontak_konsumen' => $validated['kontak_konsumen'] ?? null,
            'sepeda_motor_id' => $validated['sepeda_motor_id'] ?? null,
            'warna_motor' => $validated['warna_motor'] ?? null,
            'kisaran_anggaran' => $validated['kisaran_anggaran'] ?? null,
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status_minat' => $validated['status_minat'],
            'kendala' => $validated['kendala'] ?? null,
            'catatan_komunikasi' => $validated['catatan_komunikasi'],
            'rencana_tindak_lanjut' => $validated['rencana_tindak_lanjut'] ?? null,
            'tanggal_tindak_lanjut' => $validated['tanggal_tindak_lanjut'] ?? null,
            'status_tindak_lanjut' => $validated['status_tindak_lanjut'],
            'catatan_hasil_tindak_lanjut' => $validated['catatan_hasil_tindak_lanjut'] ?? null,
        ];

        if ($currentUser->isSupervisor() && isset($validated['user_id'])) {
            $updateData['user_id'] = $validated['user_id'];
        }

        $fieldFinding->update($updateData);

        return redirect()->route('field-findings.index')
            ->with('success', 'Data temuan lapangan berhasil diperbarui!');
    }

    public function destroy(FieldFinding $fieldFinding): RedirectResponse
    {
        $currentUser = Auth::user();
        if (!$currentUser->isSupervisor() && $fieldFinding->user_id !== $currentUser->id) {
            abort(403, 'Anda tidak berwenang menghapus temuan ini.');
        }

        $fieldFinding->delete();

        return redirect()->route('field-findings.index')
            ->with('success', 'Catatan temuan lapangan berhasil dihapus.');
    }
}
