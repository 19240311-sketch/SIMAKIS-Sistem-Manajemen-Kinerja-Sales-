<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use App\Models\ProspectActivity;
use App\Models\ProspectStatus;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProspectActivityController extends Controller
{
    public function store(Request $request, Prospect $prospect): RedirectResponse
    {
        $user = Auth::user();

        if ($user->isSales() && $prospect->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak untuk mencatat aktivitas pada prospek ini.');
        }

        $validated = $request->validate([
            'type' => ['required', 'in:phone,visit'],
            'activity_at' => ['required', 'date', 'before_or_equal:now'],
            'notes' => ['required', 'string', 'min:3'],
            'prospect_status_id' => ['nullable', 'exists:status_prospek,id'],
        ], [
            'activity_at.before_or_equal' => 'Waktu aktivitas tidak boleh melewati waktu saat ini.',
            'notes.required' => 'Catatan hasil follow-up wajib diisi.',
            'type.in' => 'Jenis aktivitas harus berupa Phone atau Visit.',
        ]);

        DB::transaction(function () use ($user, $prospect, $validated) {
            $newStatusId = $validated['prospect_status_id'] ?? null;

            // Create append-only history activity log
            ProspectActivity::create([
                'prospek_id' => $prospect->id,
                'user_id' => $user->id,
                'status_prospek_id' => $newStatusId ?: null,
                'jenis_aktivitas' => $validated['type'],
                'waktu_aktivitas' => $validated['activity_at'],
                'catatan' => $validated['notes'],
            ]);

            // Update current prospect status if status was changed during follow-up
            if ($newStatusId && (int)($prospect->status_prospek_id ?? $prospect->prospect_status_id) !== (int)$newStatusId) {
                $prospect->status_prospek_id = $newStatusId;
                $prospect->save();
            }

            ActivityLogService::log(
                activityType: 'tambah',
                module: 'prospek',
                description: "Mencatat aktivitas follow-up " . ucfirst($validated['type']) . " pada prospek {$prospect->nama_konsumen}.",
                record: $prospect,
                afterData: ['jenis_aktivitas' => $validated['type'], 'catatan' => $validated['notes']]
            );
        });

        $activityLabel = ucfirst($validated['type']);
        return redirect()->route('prospects.show', $prospect->id)
            ->with('success', "Aktivitas {$activityLabel} berhasil dicatat dan tersimpan!");
    }
}
