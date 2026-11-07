<?php

namespace App\Services;

use App\Models\Meeting;
use App\Models\MeetingAttendance;
use App\Models\Prospect;
use App\Models\RewardProgram;
use App\Models\SalesTarget;
use App\Models\SalesTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Get real-time system notifications for the current authenticated user.
     *
     * @param User|null $user
     * @return Collection
     */
    public static function getNotificationsFor(?User $user): Collection
    {
        if (!$user) {
            return collect();
        }

        $notifications = collect();
        $today = Carbon::today();

        try {
            // 1. NOTIFIKASI PROSPEK PERLU FOLLOW-UP (Jatuh tempo >= 3 hari tanpa aktivitas)
            $cutoffDate = Carbon::now()->subDays(3);
            $prospectQuery = Prospect::with(['motorcycle', 'status', 'latestActivity'])
                ->whereHas('status', function ($q) {
                    $q->whereNotIn('nama_status', ['Deal', 'Batal', 'Closed', 'Selesai']);
                });

            if ($user->isSales()) {
                $prospectQuery->where('user_id', $user->id);
            }

            // Batasi 50 prospek teratas untuk performa super cepat (< 5ms)
            $activeProspects = $prospectQuery->latest('updated_at')->take(50)->get();

            $overdueProspects = $activeProspects->filter(function ($p) use ($cutoffDate) {
                $lastDate = $p->latestActivity ? ($p->latestActivity->waktu_aktivitas ?? $p->latestActivity->activity_at) : $p->created_at;
                return $lastDate ? Carbon::parse($lastDate)->lte($cutoffDate) : false;
            });

            if ($overdueProspects->count() > 0) {
                $count = $overdueProspects->count();
                $firstName = $overdueProspects->first()->nama_konsumen ?? 'Konsumen';
                $motorName = $overdueProspects->first()->motorcycle?->nama_model ?? 'Yamaha';

                $message = $user->isSales()
                    ? "Ada {$count} prospek Anda yang belum dihubungi >= 3 hari (Termasuk: {$firstName} - {$motorName}). Segera follow-up!"
                    : "Terdapat {$count} prospek tim sales yang perlu tindak lanjut follow-up hari ini.";

                $targetUrl = route('prospects.index');

                $notifications->push([
                    'id' => 'overdue_prospects',
                    'type' => 'followup',
                    'level' => 'warning',
                    'icon' => 'clock',
                    'title' => 'Follow-up Jatuh Tempo',
                    'message' => $message,
                    'time' => 'Perlu tindakan',
                    'badge' => "{$count} Prospek",
                    'url' => $targetUrl,
                    'action_url' => $targetUrl,
                ]);
            }

            // 2. NOTIFIKASI AGENDA RAPAT TERDEKAT (Hari ini atau jadwal mendatang)
            $meetingQuery = Meeting::where('tanggal', '>=', $today->toDateString())
                ->whereIn('status', ['dijadwalkan', 'berlangsung'])
                ->orderBy('tanggal', 'asc')
                ->orderBy('waktu_mulai', 'asc');

            if ($user->isSales()) {
                $meetingQuery->whereHas('attendances', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }

            $upcomingMeetings = $meetingQuery->take(3)->get();

            foreach ($upcomingMeetings as $meeting) {
                $meetingDate = Carbon::parse($meeting->tanggal);
                $isToday = $meetingDate->isToday();
                $isTomorrow = $meetingDate->isTomorrow();
                $diffDays = $today->diffInDays($meetingDate, false);
                $jam = substr($meeting->waktu_mulai, 0, 5);
                $metode = ucfirst($meeting->metode ?? 'hybrid');

                if ($isToday) {
                    $title = '🚨 Rapat Hari Ini';
                    $badge = 'Hari Ini';
                    $level = 'warning';
                    $timeLabel = "Hari Ini, {$jam} WIB";
                } elseif ($isTomorrow) {
                    $title = '⏰ Rapat Besok';
                    $badge = 'Besok';
                    $level = 'warning';
                    $timeLabel = "Besok, {$jam} WIB";
                } elseif ($diffDays <= 7) {
                    $title = '📅 Rapat Minggu Ini';
                    $badge = "{$diffDays} Hari Lagi";
                    $level = 'info';
                    $timeLabel = $meetingDate->locale('id')->isoFormat('dddd, D MMMM') . " - {$jam} WIB";
                } else {
                    $title = '📅 Agenda Rapat Terjadwal';
                    $badge = $meetingDate->locale('id')->isoFormat('D MMM');
                    $level = 'info';
                    $timeLabel = $meetingDate->locale('id')->isoFormat('D MMMM Y') . " - {$jam} WIB";
                }

                $meetingUrl = route('meetings.show', $meeting->id);

                $notifications->push([
                    'id' => 'meeting_' . $meeting->id,
                    'type' => 'meeting',
                    'level' => $level,
                    'icon' => 'calendar',
                    'title' => $title,
                    'message' => "\"{$meeting->judul_rapat}\" ({$metode}) &bull; {$timeLabel}",
                    'time' => $timeLabel,
                    'badge' => $badge,
                    'url' => $meetingUrl,
                    'action_url' => $meetingUrl,
                ]);
            }

            // 3. NOTIFIKASI CAPAIAN TARGET & REWARD (Khusus Sales)
            if ($user->isSales()) {
                $activeReward = RewardProgram::where('is_active', true)->first();
                $targetReward = $activeReward ? $activeReward->target_minimal_unit : 15;

                $currentUnits = (int) SalesTransaction::valid()
                    ->where('user_id', $user->id)
                    ->whereMonth('tanggal_transaksi', $today->month)
                    ->whereYear('tanggal_transaksi', $today->year)
                    ->sum('jumlah_unit');

                $targetUrl = route('targets.index');

                if ($currentUnits >= $targetReward) {
                    $notifications->push([
                        'id' => 'reward_reached',
                        'type' => 'target',
                        'level' => 'success',
                        'icon' => 'trophy',
                        'title' => '🎉 Target Reward Tembus!',
                        'message' => "Realisasi Anda: {$currentUnits} unit (Target: {$targetReward} unit). Klaim reward Anda sekarang!",
                        'time' => 'Bulan Ini',
                        'badge' => 'Tembus Target',
                        'url' => $targetUrl,
                        'action_url' => $targetUrl,
                    ]);
                } elseif ($targetReward - $currentUnits <= 3 && $targetReward - $currentUnits > 0) {
                    $sisa = $targetReward - $currentUnits;
                    $notifications->push([
                        'id' => 'reward_near',
                        'type' => 'target',
                        'level' => 'info',
                        'icon' => 'trophy',
                        'title' => "⚡ Tinggal {$sisa} Unit Lagi Menuju Reward!",
                        'message' => "Penjualan saat ini: {$currentUnits} unit. Capai {$targetReward} unit untuk dapat reward bulan ini!",
                        'time' => 'Bulan Ini',
                        'badge' => "Kurang {$sisa} Unit",
                        'url' => $targetUrl,
                        'action_url' => $targetUrl,
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('NotificationService error: ' . $e->getMessage());
        }

        return $notifications;
    }
}
