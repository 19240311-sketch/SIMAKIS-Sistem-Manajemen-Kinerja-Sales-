<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;
use App\Models\Prospect;
use App\Models\ProspectActivity;
use App\Models\ProspectStatus;
use App\Models\Source;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProspectController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = Prospect::with(['user', 'motorcycle', 'source', 'status', 'latestActivity']);

        // Scope to sales user if not admin
        if ($user->isSales()) {
            $query->where('user_id', $user->id);
        } elseif ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Tab filter: 'prospect' (Belum Deal) vs 'deal' (Sudah Deal)
        $tab = $request->get('tab', 'all');
        if ($tab === 'prospect') {
            $query->whereHas('status', fn($q) => $q->where('kode', '!=', 'deal')->where('status_akhir', false));
        } elseif ($tab === 'deal') {
            $query->where(function ($q) {
                $q->where('tahap_data', 'deal')
                  ->orWhereHas('status', fn($sq) => $sq->where('kode', 'deal'));
            });
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_konsumen', 'like', "%{$search}%")
                  ->orWhere('nomor_telepon', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nomor_ktp', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status_id')) {
            $query->where('status_prospek_id', $request->status_id);
        }

        // Source filter
        if ($request->filled('source_id')) {
            $query->where('sumber_prospek_id', $request->source_id);
        }

        // Motorcycle filter
        if ($request->filled('motorcycle_id')) {
            $query->where('sepeda_motor_id', $request->motorcycle_id);
        }

        // Needs follow up filter (overdue >= 3 days)
        if ($request->boolean('needs_follow_up')) {
            $query->whereHas('status', function ($q) {
                $q->where('status_akhir', false);
            })->where(function ($q) {
                $q->whereDoesntHave('activities', function ($actQ) {
                    $actQ->where('waktu_aktivitas', '>=', now()->subDays(3));
                })->where('created_at', '<=', now()->subDays(3));
            });
        }

        // Counts for tabs
        $baseCountQuery = Prospect::query();
        if ($user->isSales()) {
            $baseCountQuery->where('user_id', $user->id);
        }
        $countAll = (clone $baseCountQuery)->count();
        $countProspect = (clone $baseCountQuery)->whereHas('status', fn($q) => $q->where('kode', '!=', 'deal')->where('status_akhir', false))->count();
        $countDeal = (clone $baseCountQuery)->where(function ($q) {
            $q->where('tahap_data', 'deal')
              ->orWhereHas('status', fn($sq) => $sq->where('kode', 'deal'));
        })->count();

        $prospects = $query->orderBy('updated_at', 'desc')->paginate(10)->withQueryString();

        $statuses = ProspectStatus::orderBy('urutan')->get();
        $sources = Source::where('status_aktif', true)->get();
        $motorcycles = Motorcycle::where('status_aktif', true)->get();
        $salesUsers = User::where('peran', 'sales')->where('status_aktif', true)->get();

        return view('prospects.index', compact(
            'prospects',
            'statuses',
            'sources',
            'motorcycles',
            'salesUsers',
            'tab',
            'countAll',
            'countProspect',
            'countDeal'
        ));
    }

    public function create(Request $request): View
    {
        $user = Auth::user();
        $motorcycles = Motorcycle::with(['colors', 'installments'])->where('status_aktif', true)->orderBy('nama_model')->orderBy('varian')->get();
        $sources = Source::where('status_aktif', true)->orderBy('nama_sumber')->get();
        $statuses = ProspectStatus::orderBy('urutan')->get();
        $salesUsers = User::where('peran', 'sales')->where('status_aktif', true)->get();

        $mode = $request->get('mode', 'prospek');
        $selectedProspect = null;

        if ($request->filled('prospect_id')) {
            $selectedProspect = Prospect::with(['motorcycle.colors', 'motorcycle.installments', 'source', 'status'])->find($request->prospect_id);
            if ($selectedProspect) {
                $mode = 'deal';
            }
        }

        // List of existing non-deal prospects for dropdown auto-population in Go To Deal mode
        $pendingProspects = Prospect::with(['motorcycle.colors', 'motorcycle.installments', 'source', 'status'])
            ->where(function ($q) {
                $q->where('tahap_data', 'prospek')
                  ->orWhereNull('tahap_data');
            })
            ->whereHas('status', fn($sq) => $sq->where('status_akhir', false))
            ->when($user->isSales(), fn($q) => $q->where('user_id', $user->id))
            ->orderBy('nama_konsumen')
            ->get();

        $dealStatus = ProspectStatus::where('kode', 'deal')->first() ?? $statuses->last();
        $defaultStatus = $statuses->first();

        return view('prospects.create', compact(
            'motorcycles',
            'sources',
            'statuses',
            'salesUsers',
            'defaultStatus',
            'dealStatus',
            'mode',
            'pendingProspects',
            'selectedProspect'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $tahapData = $request->input('tahap_data', 'prospek');

        if ($tahapData === 'deal') {
            // ==========================================
            // ALUR 2: GO TO DEAL
            // ==========================================
            $validated = $request->validate([
                'prospect_id' => ['nullable', 'exists:prospek,id'],
                'nama_konsumen' => ['required', 'string', 'max:100'],
                'nomor_telepon' => ['required', 'string', 'max:20'],
                'alamat' => ['required', 'string'],
                'kelurahan' => ['nullable', 'string', 'max:100'],
                'kecamatan' => ['nullable', 'string', 'max:100'],
                'kota' => ['required', 'string', 'max:100'],
                'provinsi' => ['required', 'string', 'max:100'],
                'kode_pos' => ['nullable', 'string', 'max:10'],

                'nomor_ktp' => ['required', 'string', 'max:30'],
                'tanggal_lahir' => ['nullable', 'date'],
                'pekerjaan' => ['nullable', 'string', 'max:100'],

                'sepeda_motor_id' => ['required', 'exists:sepeda_motor,id'],
                'warna_motor_diminati' => ['required', 'string', 'max:50'],

                'skema_pembelian' => ['required', 'in:Cash,Kredit'],
                'dp' => [$request->skema_pembelian === 'Kredit' ? 'required' : 'nullable', 'numeric', 'min:0'],
                'tenor_bulan' => [$request->skema_pembelian === 'Kredit' ? 'required' : 'nullable', 'integer'],
                'angsuran_per_bulan' => ['nullable', 'numeric', 'min:0'],
                'leasing' => [$request->skema_pembelian === 'Kredit' ? 'required' : 'nullable', 'string', 'max:100'],
                'metode_pembayaran' => ['required', 'string', 'max:100'],
                'tanggal_deal' => ['required', 'date'],
                'catatan' => ['nullable', 'string'],

                'sumber_prospek_id' => ['nullable', 'exists:sumber_prospek,id'],
                'user_id' => [$user->isAdmin() ? 'required' : 'nullable', 'exists:users,id'],
            ], [
                'nama_konsumen.required' => 'Nama konsumen wajib diisi.',
                'nomor_telepon.required' => 'Nomor telepon konsumen wajib diisi.',
                'alamat.required' => 'Alamat lengkap konsumen wajib diisi.',
                'nomor_ktp.required' => 'Nomor KTP wajib diisi untuk transaksi Go To Deal.',
                'sepeda_motor_id.required' => 'Model motor wajib dipilih.',
                'warna_motor_diminati.required' => 'Warna motor wajib dipilih sesuai ketersediaan.',
                'skema_pembelian.required' => 'Skema pembelian (Cash/Kredit) wajib dipilih.',
                'dp.required' => 'Uang muka (DP) wajib diisi untuk skema kredit.',
                'tenor_bulan.required' => 'Tenor angsuran wajib dipilih untuk skema kredit.',
                'leasing.required' => 'Lembaga pembiayaan (Leasing) wajib dipilih.',
                'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
                'tanggal_deal.required' => 'Tanggal Go To Deal wajib diisi.',
            ]);

            $motorcycle = Motorcycle::with(['colors', 'installments'])->findOrFail($validated['sepeda_motor_id']);
            $hargaOtr = $motorcycle->harga_otr;
            $dealStatus = ProspectStatus::where('kode', 'deal')->first() ?? ProspectStatus::where('status_akhir', true)->first();
            $assignedUserId = $user->isAdmin() ? ($validated['user_id'] ?? $user->id) : $user->id;

            $tenorBulan = ($validated['skema_pembelian'] === 'Kredit') ? ($validated['tenor_bulan'] ?? null) : null;
            $angsuranPerBulan = null;
            if ($tenorBulan) {
                $ins = $motorcycle->installments->where('tenor_bulan', (int)$tenorBulan)->first();
                $angsuranPerBulan = $ins ? $ins->nominal_angsuran : ($validated['angsuran_per_bulan'] ?? null);
            }

            $dealData = [
                'nama_konsumen' => $validated['nama_konsumen'],
                'nomor_telepon' => $validated['nomor_telepon'],
                'alamat' => $validated['alamat'],
                'kelurahan' => $validated['kelurahan'] ?? null,
                'kecamatan' => $validated['kecamatan'] ?? null,
                'kota' => $validated['kota'],
                'provinsi' => $validated['provinsi'],
                'kode_pos' => $validated['kode_pos'] ?? null,
                'sepeda_motor_id' => $validated['sepeda_motor_id'],
                'warna_motor_diminati' => $validated['warna_motor_diminati'],
                'harga_otr' => $hargaOtr,
                'tahap_data' => 'deal',
                'status_prospek_id' => $dealStatus->id,
                'nomor_ktp' => $validated['nomor_ktp'],
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'pekerjaan' => $validated['pekerjaan'] ?? null,
                'skema_pembelian' => $validated['skema_pembelian'],
                'dp' => $validated['dp'] ?? null,
                'tenor_bulan' => $tenorBulan,
                'angsuran_per_bulan' => $angsuranPerBulan,
                'leasing' => $validated['leasing'] ?? null,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'tanggal_deal' => $validated['tanggal_deal'],
                'catatan' => $validated['catatan'] ?? null,
            ];

            if ($request->filled('prospect_id')) {
                // UPDATE EXISTING PROSPECT (No re-typing, seamless deal conversion!)
                $prospect = Prospect::findOrFail($request->prospect_id);
                $prospect->update($dealData);

                ProspectActivity::create([
                    'prospek_id' => $prospect->id,
                    'user_id' => $user->id,
                    'status_prospek_id' => $dealStatus->id,
                    'jenis_aktivitas' => 'visit',
                    'waktu_aktivitas' => now(),
                    'catatan' => 'Prospek telah berhasil dikonversi ke tahap GO TO DEAL (Closing). Pembelian ' . $motorcycle->full_display_name . ' (' . $validated['warna_motor_diminati'] . ') via ' . $validated['skema_pembelian'] . ($validated['leasing'] ? ' (' . $validated['leasing'] . ')' : '') . '.',
                ]);

                ActivityLogService::log(
                    activityType: 'status_change',
                    module: 'prospek',
                    description: "Konversi prospek {$prospect->nama_konsumen} ke tahap Go To Deal ({$motorcycle->nama_model}).",
                    record: $prospect,
                    afterData: ['status' => 'deal', 'nama_konsumen' => $prospect->nama_konsumen, 'motor' => $motorcycle->nama_model]
                );

                return redirect()->route('prospects.show', $prospect->id)
                    ->with('success', 'Selamat! Prospek ' . $prospect->nama_konsumen . ' berhasil dikonversi ke GO TO DEAL!');
            } else {
                // Direct Deal without prior prospect
                $defaultSource = Source::first();
                $dealData['sumber_prospek_id'] = $validated['sumber_prospek_id'] ?? ($defaultSource ? $defaultSource->id : 1);
                $dealData['user_id'] = $assignedUserId;

                $prospect = Prospect::create($dealData);

                ProspectActivity::create([
                    'prospek_id' => $prospect->id,
                    'user_id' => $user->id,
                    'status_prospek_id' => $dealStatus->id,
                    'jenis_aktivitas' => 'visit',
                    'waktu_aktivitas' => now(),
                    'catatan' => 'Pendaftaran transaksi langsung Go To Deal untuk unit ' . $motorcycle->full_display_name . ' (' . $validated['warna_motor_diminati'] . ').',
                ]);

                ActivityLogService::log(
                    activityType: 'tambah',
                    module: 'prospek',
                    description: "Pendaftaran transaksi langsung Go To Deal konsumen {$prospect->nama_konsumen} ({$motorcycle->nama_model}).",
                    record: $prospect,
                    afterData: ['status' => 'deal', 'nama_konsumen' => $prospect->nama_konsumen, 'motor' => $motorcycle->nama_model]
                );

                return redirect()->route('prospects.show', $prospect->id)
                    ->with('success', 'Data transaksi Go To Deal berhasil disimpan!');
            }
        } else {
            // ==========================================
            // ALUR 1: INPUT PROSPEK BARU
            // ==========================================
            $validated = $request->validate([
                'nama_konsumen' => ['required', 'string', 'max:100'],
                'nomor_telepon' => ['required', 'string', 'max:20'],
                'alamat' => ['required', 'string'],
                'kelurahan' => ['nullable', 'string', 'max:100'],
                'kecamatan' => ['nullable', 'string', 'max:100'],
                'kota' => ['required', 'string', 'max:100'],
                'provinsi' => ['required', 'string', 'max:100'],
                'kode_pos' => ['nullable', 'string', 'max:10'],

                'sepeda_motor_id' => ['required', 'exists:sepeda_motor,id'],
                'warna_motor_diminati' => ['required', 'string', 'max:50'],
                'tenor_bulan' => ['nullable', 'integer'],

                'sumber_prospek_id' => ['required', 'exists:sumber_prospek,id'],
                'status_prospek_id' => ['required', 'exists:status_prospek,id'],
                'tanggal_follow_up_selanjutnya' => ['required', 'date'],
                'catatan' => ['nullable', 'string'],
                'user_id' => [$user->isAdmin() ? 'required' : 'nullable', 'exists:users,id'],
            ], [
                'nama_konsumen.required' => 'Nama calon konsumen wajib diisi.',
                'nomor_telepon.required' => 'Nomor HP / WhatsApp calon konsumen wajib diisi.',
                'alamat.required' => 'Alamat calon konsumen wajib diisi.',
                'kota.required' => 'Kota / Kabupaten wajib diisi.',
                'provinsi.required' => 'Provinsi wajib diisi.',
                'sepeda_motor_id.required' => 'Tipe motor yang diminati wajib dipilih.',
                'warna_motor_diminati.required' => 'Pilihan warna motor wajib dipilih.',
                'sumber_prospek_id.required' => 'Sumber asal prospek wajib dipilih.',
                'status_prospek_id.required' => 'Status awal prospek wajib dipilih.',
                'tanggal_follow_up_selanjutnya.required' => 'Tanggal rencana follow up selanjutnya wajib diisi.',
            ]);

            $motorcycle = Motorcycle::with(['colors', 'installments'])->findOrFail($validated['sepeda_motor_id']);
            $hargaOtr = $motorcycle->harga_otr;
            $tenorBulan = $validated['tenor_bulan'] ?? null;
            $angsuranPerBulan = null;
            if ($tenorBulan) {
                $ins = $motorcycle->installments->where('tenor_bulan', (int)$tenorBulan)->first();
                $angsuranPerBulan = $ins ? $ins->nominal_angsuran : null;
            }

            $assignedUserId = $user->isAdmin() ? ($validated['user_id'] ?? $user->id) : $user->id;

            $prospect = Prospect::create([
                'user_id' => $assignedUserId,
                'sepeda_motor_id' => $validated['sepeda_motor_id'],
                'sumber_prospek_id' => $validated['sumber_prospek_id'],
                'status_prospek_id' => $validated['status_prospek_id'],
                'nama_konsumen' => $validated['nama_konsumen'],
                'nomor_telepon' => $validated['nomor_telepon'],
                'alamat' => $validated['alamat'],
                'kelurahan' => $validated['kelurahan'] ?? null,
                'kecamatan' => $validated['kecamatan'] ?? null,
                'kota' => $validated['kota'],
                'provinsi' => $validated['provinsi'],
                'kode_pos' => $validated['kode_pos'] ?? null,
                'warna_motor_diminati' => $validated['warna_motor_diminati'],
                'harga_otr' => $hargaOtr,
                'tenor_bulan' => $tenorBulan,
                'angsuran_per_bulan' => $angsuranPerBulan,
                'tanggal_follow_up_selanjutnya' => $validated['tanggal_follow_up_selanjutnya'],
                'catatan' => $validated['catatan'] ?? null,
                'tahap_data' => 'prospek',
            ]);

            ActivityLogService::log(
                activityType: 'tambah',
                module: 'prospek',
                description: "Menambahkan prospek baru: {$prospect->nama_konsumen} ({$motorcycle->nama_model}) oleh {$user->nama}.",
                record: $prospect,
                afterData: ['nama_konsumen' => $prospect->nama_konsumen, 'motor' => $motorcycle->nama_model]
            );

            return redirect()->route('prospects.show', $prospect->id)
                ->with('success', 'Data prospek konsumen berhasil ditambahkan dan masuk ke sistem monitoring!');
        }
    }

    public function show(Prospect $prospect): View
    {
        $user = Auth::user();

        // Authorization check: Sales can only view their own prospect
        if ($user->isSales() && $prospect->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data prospek ini.');
        }

        $prospect->load(['user', 'motorcycle.colors', 'motorcycle.installments', 'source', 'status', 'activities.user', 'activities.status']);

        $statuses = ProspectStatus::orderBy('urutan')->get();

        return view('prospects.show', compact('prospect', 'statuses'));
    }

    public function edit(Prospect $prospect): View
    {
        $user = Auth::user();

        if ($user->isSales() && $prospect->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah data prospek ini.');
        }

        $motorcycles = Motorcycle::with(['colors', 'installments'])->where('status_aktif', true)->orderBy('nama_model')->orderBy('varian')->get();
        $sources = Source::where('status_aktif', true)->orderBy('nama_sumber')->get();
        $statuses = ProspectStatus::orderBy('urutan')->get();
        $salesUsers = User::where('peran', 'sales')->where('status_aktif', true)->get();

        return view('prospects.edit', compact('prospect', 'motorcycles', 'sources', 'statuses', 'salesUsers'));
    }

    public function update(Request $request, Prospect $prospect): RedirectResponse
    {
        $user = Auth::user();

        if ($user->isSales() && $prospect->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah data prospek ini.');
        }

        $tahapData = $request->input('tahap_data', $prospect->tahap_data ?? 'prospek');

        if ($tahapData === 'deal') {
            $validated = $request->validate([
                'nama_konsumen' => ['required', 'string', 'max:100'],
                'nomor_telepon' => ['required', 'string', 'max:20'],
                'alamat' => ['required', 'string'],
                'kelurahan' => ['nullable', 'string', 'max:100'],
                'kecamatan' => ['nullable', 'string', 'max:100'],
                'kota' => ['required', 'string', 'max:100'],
                'provinsi' => ['required', 'string', 'max:100'],
                'kode_pos' => ['nullable', 'string', 'max:10'],

                'nomor_ktp' => ['required', 'string', 'max:30'],
                'tanggal_lahir' => ['nullable', 'date'],
                'pekerjaan' => ['nullable', 'string', 'max:100'],

                'sepeda_motor_id' => ['required', 'exists:sepeda_motor,id'],
                'warna_motor_diminati' => ['required', 'string', 'max:50'],

                'skema_pembelian' => ['required', 'in:Cash,Kredit'],
                'dp' => [$request->skema_pembelian === 'Kredit' ? 'required' : 'nullable', 'numeric', 'min:0'],
                'tenor_bulan' => [$request->skema_pembelian === 'Kredit' ? 'required' : 'nullable', 'integer'],
                'angsuran_per_bulan' => ['nullable', 'numeric', 'min:0'],
                'leasing' => [$request->skema_pembelian === 'Kredit' ? 'required' : 'nullable', 'string', 'max:100'],
                'metode_pembayaran' => ['required', 'string', 'max:100'],
                'tanggal_deal' => ['required', 'date'],
                'catatan' => ['nullable', 'string'],

                'sumber_prospek_id' => ['nullable', 'exists:sumber_prospek,id'],
                'user_id' => [$user->isAdmin() ? 'required' : 'nullable', 'exists:users,id'],
            ]);

            $motorcycle = Motorcycle::with(['colors', 'installments'])->findOrFail($validated['sepeda_motor_id']);
            $dealStatus = ProspectStatus::where('kode', 'deal')->first() ?? ProspectStatus::where('status_akhir', true)->first();

            $tenorBulan = ($validated['skema_pembelian'] === 'Kredit') ? ($validated['tenor_bulan'] ?? null) : null;
            $angsuranPerBulan = null;
            if ($tenorBulan) {
                $ins = $motorcycle->installments->where('tenor_bulan', (int)$tenorBulan)->first();
                $angsuranPerBulan = $ins ? $ins->nominal_angsuran : ($validated['angsuran_per_bulan'] ?? null);
            }

            $updateData = [
                'nama_konsumen' => $validated['nama_konsumen'],
                'nomor_telepon' => $validated['nomor_telepon'],
                'alamat' => $validated['alamat'],
                'kelurahan' => $validated['kelurahan'] ?? null,
                'kecamatan' => $validated['kecamatan'] ?? null,
                'kota' => $validated['kota'],
                'provinsi' => $validated['provinsi'],
                'kode_pos' => $validated['kode_pos'] ?? null,
                'sepeda_motor_id' => $validated['sepeda_motor_id'],
                'warna_motor_diminati' => $validated['warna_motor_diminati'],
                'harga_otr' => $motorcycle->harga_otr,
                'tahap_data' => 'deal',
                'status_prospek_id' => $dealStatus ? $dealStatus->id : $prospect->status_prospek_id,
                'nomor_ktp' => $validated['nomor_ktp'],
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'pekerjaan' => $validated['pekerjaan'] ?? null,
                'skema_pembelian' => $validated['skema_pembelian'],
                'dp' => $validated['dp'] ?? null,
                'tenor_bulan' => $tenorBulan,
                'angsuran_per_bulan' => $angsuranPerBulan,
                'leasing' => $validated['leasing'] ?? null,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'tanggal_deal' => $validated['tanggal_deal'],
                'catatan' => $validated['catatan'] ?? null,
            ];

            if ($user->isAdmin() && isset($validated['user_id'])) {
                $updateData['user_id'] = $validated['user_id'];
            }

            $prospect->update($updateData);

            return redirect()->route('prospects.show', $prospect->id)
                ->with('success', 'Data transaksi Go To Deal berhasil diperbarui!');
        } else {
            $validated = $request->validate([
                'nama_konsumen' => ['required', 'string', 'max:100'],
                'nomor_telepon' => ['required', 'string', 'max:20'],
                'alamat' => ['required', 'string'],
                'kelurahan' => ['nullable', 'string', 'max:100'],
                'kecamatan' => ['nullable', 'string', 'max:100'],
                'kota' => ['required', 'string', 'max:100'],
                'provinsi' => ['required', 'string', 'max:100'],
                'kode_pos' => ['nullable', 'string', 'max:10'],

                'sepeda_motor_id' => ['required', 'exists:sepeda_motor,id'],
                'warna_motor_diminati' => ['required', 'string', 'max:50'],
                'tenor_bulan' => ['nullable', 'integer'],

                'sumber_prospek_id' => ['required', 'exists:sumber_prospek,id'],
                'status_prospek_id' => ['required', 'exists:status_prospek,id'],
                'tanggal_follow_up_selanjutnya' => ['required', 'date'],
                'catatan' => ['nullable', 'string'],
                'user_id' => [$user->isAdmin() ? 'required' : 'nullable', 'exists:users,id'],
            ]);

            $motorcycle = Motorcycle::with(['colors', 'installments'])->findOrFail($validated['sepeda_motor_id']);

            $tenorBulan = $validated['tenor_bulan'] ?? null;
            $angsuranPerBulan = null;
            if ($tenorBulan) {
                $ins = $motorcycle->installments->where('tenor_bulan', (int)$tenorBulan)->first();
                $angsuranPerBulan = $ins ? $ins->nominal_angsuran : null;
            }

            $updateData = [
                'nama_konsumen' => $validated['nama_konsumen'],
                'nomor_telepon' => $validated['nomor_telepon'],
                'alamat' => $validated['alamat'],
                'kelurahan' => $validated['kelurahan'] ?? null,
                'kecamatan' => $validated['kecamatan'] ?? null,
                'kota' => $validated['kota'],
                'provinsi' => $validated['provinsi'],
                'kode_pos' => $validated['kode_pos'] ?? null,
                'sepeda_motor_id' => $validated['sepeda_motor_id'],
                'warna_motor_diminati' => $validated['warna_motor_diminati'],
                'harga_otr' => $motorcycle->harga_otr,
                'tenor_bulan' => $tenorBulan,
                'angsuran_per_bulan' => $angsuranPerBulan,
                'sumber_prospek_id' => $validated['sumber_prospek_id'],
                'status_prospek_id' => $validated['status_prospek_id'],
                'tanggal_follow_up_selanjutnya' => $validated['tanggal_follow_up_selanjutnya'],
                'catatan' => $validated['catatan'] ?? null,
                'tahap_data' => 'prospek',
            ];

            if ($user->isAdmin() && isset($validated['user_id'])) {
                $updateData['user_id'] = $validated['user_id'];
            }

            $prospect->update($updateData);

            return redirect()->route('prospects.show', $prospect->id)
                ->with('success', 'Data prospek konsumen berhasil diperbarui!');
        }
    }

    public function destroy(Prospect $prospect): RedirectResponse
    {
        $user = Auth::user();

        if ($user->isSales() && $prospect->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus prospek ini.');
        }

        $prospect->delete();

        return redirect()->route('prospects.index')
            ->with('info', 'Data prospek konsumen berhasil dihapus.');
    }

    public function export(Request $request): StreamedResponse
    {
        $user = Auth::user();
        $query = Prospect::with(['user', 'motorcycle', 'source', 'status', 'latestActivity']);

        // Scope to sales user if not admin
        if ($user->isSales()) {
            $query->where('user_id', $user->id);
        } elseif ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_konsumen', 'like', "%{$search}%")
                  ->orWhere('nomor_telepon', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status_id')) {
            $query->where('status_prospek_id', $request->status_id);
        }

        // Source filter
        if ($request->filled('source_id')) {
            $query->where('sumber_prospek_id', $request->source_id);
        }

        // Motorcycle filter
        if ($request->filled('motorcycle_id')) {
            $query->where('sepeda_motor_id', $request->motorcycle_id);
        }

        // Needs follow up filter (overdue >= 3 days)
        if ($request->boolean('needs_follow_up')) {
            $query->whereHas('status', function ($q) {
                $q->where('status_akhir', false);
            })->where(function ($q) {
                $q->whereDoesntHave('activities', function ($actQ) {
                    $actQ->where('waktu_aktivitas', '>=', now()->subDays(3));
                })->where('created_at', '<=', now()->subDays(3));
            });
        }

        $prospects = $query->orderBy('created_at', 'desc')->get();
        $fileName = 'SIMPRO_Data_Prospek_' . date('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($prospects) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM for Microsoft Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header Row
            fputcsv($handle, [
                'ID',
                'Nama Konsumen',
                'Nomor Telepon/WA',
                'Email',
                'Motor Diminati',
                'Sumber Prospek',
                'Status Prospek',
                'Sales Penanggung Jawab',
                'Alamat',
                'Catatan Kebutuhan',
                'Aktivitas Terakhir',
                'Tanggal Terdaftar',
            ]);

            foreach ($prospects as $p) {
                $lastActivityText = $p->latestActivity
                    ? strtoupper($p->latestActivity->type) . ' (' . $p->latestActivity->activity_at->format('d/m/Y H:i') . ')'
                    : 'Belum Ada';

                fputcsv($handle, [
                    $p->id,
                    $p->name,
                    $p->phone,
                    $p->email ?? '-',
                    $p->motorcycle->name ?? '-',
                    $p->source->name ?? '-',
                    $p->status->name ?? '-',
                    $p->user->name ?? '-',
                    $p->address ?? '-',
                    $p->notes ?? '-',
                    $lastActivityText,
                    $p->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }
}
