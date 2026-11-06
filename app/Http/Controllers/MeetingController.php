<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingAttendance;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MeetingController extends Controller
{
    public function index(Request $request): View
    {
        $currentUser = Auth::user();
        $query = Meeting::with(['creator', 'attendances.user']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('metode')) {
            $query->where('metode', $request->input('metode'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->input('start_date'), $request->input('end_date')]);
        }

        $meetings = $query->orderBy('tanggal', 'desc')
                          ->orderBy('waktu_mulai', 'desc')
                          ->paginate(10)
                          ->withQueryString();

        // Upcoming meetings count
        $upcomingCount = Meeting::where('tanggal', '>=', now()->toDateString())
            ->whereIn('status', ['dijadwalkan', 'berlangsung'])
            ->count();

        return view('meetings.index', compact('meetings', 'upcomingCount'));
    }

    public function create(): View
    {
        $user = Auth::user();
        if (!$user->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang dapat menjadwalkan rapat.');
        }

        $salesList = User::where('peran', 'sales')->where('status_aktif', true)->orderBy('nama')->get();
        return view('meetings.create', compact('salesList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $currentUser = Auth::user();
        if (!$currentUser->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang dapat menjadwalkan rapat.');
        }

        $validated = $request->validate([
            'judul_rapat' => ['required', 'string', 'max:200'],
            'tanggal' => ['required', 'date'],
            'waktu_mulai' => ['required'],
            'waktu_selesai' => ['required', 'after:waktu_mulai'],
            'agenda' => ['required', 'string'],
            'deskripsi' => ['nullable', 'string'],
            'metode' => ['required', 'in:online,offline,hybrid'],
            'link_zoom' => ['nullable', 'required_if:metode,online,hybrid', 'url', 'max:500'],
            'nama_tempat' => ['nullable', 'required_if:metode,offline,hybrid', 'string', 'max:150'],
            'alamat_lokasi' => ['nullable', 'required_if:metode,offline,hybrid', 'string'],
            'catatan_peserta' => ['nullable', 'string'],
            'peserta_ids' => ['required', 'array', 'min:1'],
            'peserta_ids.*' => ['exists:users,id'],
        ]);

        $meeting = Meeting::create([
            'judul_rapat' => $validated['judul_rapat'],
            'tanggal' => $validated['tanggal'],
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'agenda' => $validated['agenda'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'metode' => $validated['metode'],
            'link_zoom' => $validated['link_zoom'] ?? null,
            'nama_tempat' => $validated['nama_tempat'] ?? null,
            'alamat_lokasi' => $validated['alamat_lokasi'] ?? null,
            'catatan_peserta' => $validated['catatan_peserta'] ?? null,
            'status' => 'dijadwalkan',
            'dibuat_oleh' => $currentUser->id,
        ]);

        // Attach participants
        foreach ($validated['peserta_ids'] as $salesId) {
            MeetingAttendance::create([
                'meeting_id' => $meeting->id,
                'user_id' => $salesId,
                'status_undangan' => 'diundang',
                'konfirmasi_kehadiran' => 'belum_konfirmasi',
                'status_kehadiran' => 'belum_dicatat',
            ]);
        }

        ActivityLogService::log(
            activityType: 'tambah',
            module: 'rapat',
            description: "Menjadwalkan rapat mingguan '{$meeting->judul_rapat}'",
            record: $meeting,
            afterData: [
                'judul' => $meeting->judul_rapat,
                'tanggal' => $meeting->tanggal,
                'waktu' => "{$meeting->waktu_mulai} - {$meeting->waktu_selesai}",
                'metode' => $meeting->metode,
                'peserta_count' => count($validated['peserta_ids']),
            ]
        );

        return redirect()->route('meetings.index')
            ->with('success', 'Jadwal rapat mingguan berhasil dibuat dan disiarkan kepada peserta!');
    }

    public function show(Meeting $meeting): View
    {
        $meeting->load(['creator', 'attendances.user']);
        $currentUser = Auth::user();

        // Current user attendance record
        $myAttendance = $meeting->attendances->firstWhere('user_id', $currentUser->id);

        return view('meetings.show', compact('meeting', 'myAttendance'));
    }

    public function edit(Meeting $meeting): View
    {
        $user = Auth::user();
        if (!$user->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang dapat mengubah rapat.');
        }

        $meeting->load('attendances');
        $salesList = User::where('peran', 'sales')->where('status_aktif', true)->orderBy('nama')->get();
        $selectedSalesIds = $meeting->attendances->pluck('user_id')->toArray();

        return view('meetings.edit', compact('meeting', 'salesList', 'selectedSalesIds'));
    }

    public function update(Request $request, Meeting $meeting): RedirectResponse
    {
        $currentUser = Auth::user();
        if (!$currentUser->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang dapat mengubah rapat.');
        }

        $validated = $request->validate([
            'judul_rapat' => ['required', 'string', 'max:200'],
            'tanggal' => ['required', 'date'],
            'waktu_mulai' => ['required'],
            'waktu_selesai' => ['required'],
            'agenda' => ['required', 'string'],
            'deskripsi' => ['nullable', 'string'],
            'metode' => ['required', 'in:online,offline,hybrid'],
            'link_zoom' => ['nullable', 'required_if:metode,online,hybrid', 'url', 'max:500'],
            'nama_tempat' => ['nullable', 'required_if:metode,offline,hybrid', 'string', 'max:150'],
            'alamat_lokasi' => ['nullable', 'required_if:metode,offline,hybrid', 'string'],
            'catatan_peserta' => ['nullable', 'string'],
            'status' => ['required', 'in:dijadwalkan,berlangsung,selesai,dibatalkan'],
            'peserta_ids' => ['required', 'array', 'min:1'],
            'peserta_ids.*' => ['exists:users,id'],
        ]);

        $meeting->update([
            'judul_rapat' => $validated['judul_rapat'],
            'tanggal' => $validated['tanggal'],
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'agenda' => $validated['agenda'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'metode' => $validated['metode'],
            'link_zoom' => $validated['link_zoom'] ?? null,
            'nama_tempat' => $validated['nama_tempat'] ?? null,
            'alamat_lokasi' => $validated['alamat_lokasi'] ?? null,
            'catatan_peserta' => $validated['catatan_peserta'] ?? null,
            'status' => $validated['status'],
        ]);

        // Sync attendees
        $existingAttendees = $meeting->attendances->pluck('user_id')->toArray();
        $newAttendees = $validated['peserta_ids'];

        // Remove unselected
        MeetingAttendance::where('meeting_id', $meeting->id)
            ->whereNotIn('user_id', $newAttendees)
            ->delete();

        // Add newly added
        foreach (array_diff($newAttendees, $existingAttendees) as $newUserId) {
            MeetingAttendance::create([
                'meeting_id' => $meeting->id,
                'user_id' => $newUserId,
                'status_undangan' => 'diundang',
                'konfirmasi_kehadiran' => 'belum_konfirmasi',
                'status_kehadiran' => 'belum_dicatat',
            ]);
        }

        ActivityLogService::log(
            activityType: 'ubah',
            module: 'rapat',
            description: "Memperbarui jadwal rapat mingguan '{$meeting->judul_rapat}'",
            record: $meeting,
            afterData: ['judul' => $meeting->judul_rapat, 'status' => $meeting->status, 'tanggal' => $meeting->tanggal]
        );

        return redirect()->route('meetings.show', $meeting)
            ->with('success', 'Jadwal dan informasi rapat berhasil diperbarui!');
    }

    public function confirmAttendance(Request $request, Meeting $meeting): RedirectResponse
    {
        $currentUser = Auth::user();
        $attendance = MeetingAttendance::where('meeting_id', $meeting->id)
            ->where('user_id', $currentUser->id)
            ->firstOrFail();

        $validated = $request->validate([
            'konfirmasi_kehadiran' => ['required', 'in:hadir_online,hadir_offline,izin,tidak_hadir'],
            'catatan_kehadiran' => ['nullable', 'string', 'max:500'],
        ]);

        $attendance->update([
            'konfirmasi_kehadiran' => $validated['konfirmasi_kehadiran'],
            'catatan_kehadiran' => $validated['catatan_kehadiran'] ?? null,
            'waktu_konfirmasi' => now(),
        ]);

        ActivityLogService::log(
            activityType: 'ubah',
            module: 'rapat',
            description: "Mengonfirmasi kehadiran rapat '{$meeting->judul_rapat}': " . str_replace('_', ' ', $validated['konfirmasi_kehadiran']),
            record: $meeting,
            afterData: ['status' => $validated['konfirmasi_kehadiran']]
        );

        return back()->with('success', 'Konfirmasi kehadiran Anda berhasil dicatat!');
    }

    public function updateMinutes(Request $request, Meeting $meeting): RedirectResponse
    {
        $currentUser = Auth::user();
        if (!$currentUser->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang dapat mengisi notulen dan evaluasi rapat.');
        }

        $validated = $request->validate([
            'notulen' => ['nullable', 'string'],
            'hasil_evaluasi' => ['nullable', 'string'],
            'rencana_tindak_lanjut' => ['nullable', 'string'],
            'penanggung_jawab_tindak_lanjut' => ['nullable', 'string', 'max:150'],
            'batas_waktu_tindak_lanjut' => ['nullable', 'date'],
            'status_tindak_lanjut' => ['required', 'in:belum_selesai,dalam_proses,selesai'],
            'attendances' => ['nullable', 'array'],
            'attendances.*.id' => ['required', 'exists:meeting_attendances,id'],
            'attendances.*.status_kehadiran' => ['required', 'in:belum_dicatat,hadir,tidak_hadir,izin'],
        ]);

        $meeting->update([
            'notulen' => $validated['notulen'] ?? null,
            'hasil_evaluasi' => $validated['hasil_evaluasi'] ?? null,
            'rencana_tindak_lanjut' => $validated['rencana_tindak_lanjut'] ?? null,
            'penanggung_jawab_tindak_lanjut' => $validated['penanggung_jawab_tindak_lanjut'] ?? null,
            'batas_waktu_tindak_lanjut' => $validated['batas_waktu_tindak_lanjut'] ?? null,
            'status_tindak_lanjut' => $validated['status_tindak_lanjut'],
        ]);

        // Update attendance status by supervisor
        if (!empty($validated['attendances'])) {
            foreach ($validated['attendances'] as $att) {
                MeetingAttendance::where('id', $att['id'])->update([
                    'status_kehadiran' => $att['status_kehadiran'],
                ]);
            }
        }

        ActivityLogService::log(
            activityType: 'ubah',
            module: 'rapat',
            description: "Memperbarui notulen, evaluasi & presensi rapat '{$meeting->judul_rapat}'",
            record: $meeting,
            afterData: ['status_tindak_lanjut' => $meeting->status_tindak_lanjut]
        );

        return back()->with('success', 'Notulen, tindak lanjut, dan presensi rapat berhasil disimpan!');
    }

    public function destroy(Meeting $meeting): RedirectResponse
    {
        $currentUser = Auth::user();
        if (!$currentUser->isSupervisor()) {
            abort(403, 'Hanya Supervisor yang dapat membatalkan rapat.');
        }

        $judul = $meeting->judul_rapat;
        $id = $meeting->id;
        $meeting->delete();

        ActivityLogService::log(
            activityType: 'hapus',
            module: 'rapat',
            description: "Membatalkan/menghapus jadwal rapat mingguan '{$judul}'",
            beforeData: ['judul' => $judul, 'id' => $id]
        );

        return redirect()->route('meetings.index')
            ->with('success', 'Rapat berhasil dibatalkan dan dihapus.');
    }
}
