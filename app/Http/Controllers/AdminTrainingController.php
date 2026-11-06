<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingMaterial;
use App\Models\TrainingQuiz;
use App\Models\User;
use App\Models\UserQuizResult;
use App\Models\UserTrainingProgress;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminTrainingController extends Controller
{
    /**
     * Admin index: List all trainings & sales completion overview.
     */
    public function index(Request $request): View
    {
        $activeTab = $request->query('tab', 'modules'); // 'modules' or 'sales_report'
        $search = $request->query('q', '');
        $kategoriFilter = $request->query('kategori', '');

        $trainingsQuery = Training::withCount(['materials', 'quizzes', 'userProgresses'])
            ->orderBy('urutan', 'asc');

        if (!empty($search)) {
            $trainingsQuery->where(function ($q) use ($search) {
                $q->where('nama_training', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if (!empty($kategoriFilter)) {
            $trainingsQuery->where('kategori', $kategoriFilter);
        }

        $trainings = $trainingsQuery->get();

        // Sales Report Data
        $salesUsers = User::where('peran', 'sales')
            ->with(['trainingProgresses.training', 'quizResults'])
            ->get();

        $salesProgressData = $salesUsers->map(function ($sales) use ($trainings) {
            $completedCount = $sales->trainingProgresses->where('status_lulus', true)->count();
            $inProgressCount = $sales->trainingProgresses->where('video_progress', '>', 0)->where('status_lulus', false)->count();
            $totalTrainings = max(1, $trainings->count());
            $completionRate = round(($completedCount / $totalTrainings) * 100);

            $latestQuiz = $sales->quizResults->sortByDesc('waktu_selesai')->first();
            $avgQuizScore = $sales->quizResults->count() > 0 ? round($sales->quizResults->avg('nilai')) : 0;

            return [
                'user' => $sales,
                'completed_count' => $completedCount,
                'in_progress_count' => $inProgressCount,
                'completion_rate' => $completionRate,
                'latest_quiz' => $latestQuiz,
                'avg_quiz_score' => $avgQuizScore,
                'progresses' => $sales->trainingProgresses->keyBy('training_id'),
            ];
        });

        $totalTrainingsCount = Training::count();
        $totalQuizzesCount = TrainingQuiz::count();
        $totalCompletions = UserTrainingProgress::where('status_lulus', true)->orWhere('status', 'selesai')->count();

        return view('admin.trainings.index', compact(
            'trainings',
            'salesProgressData',
            'activeTab',
            'search',
            'kategoriFilter',
            'totalTrainingsCount',
            'totalQuizzesCount',
            'totalCompletions'
        ));
    }

    /**
     * Show form to create new video & quiz training module.
     */
    public function create(): View
    {
        return view('admin.trainings.create');
    }

    /**
     * Store new training module with YouTube video & quizzes.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_training' => 'required|string|max:255',
            'kategori' => 'required|string|in:product_knowledge,sales_skill,product_update,training,lainnya',
            'deskripsi' => 'required|string',
            'youtube_url' => 'required|string|max:255',
            'durasi_video' => 'nullable|string|max:50',
            'jumlah_soal' => 'nullable|integer|min:1|max:20',
            'passing_grade' => 'required|integer|min:10|max:100',
            'status' => 'required|in:published,draft',
            'is_active' => 'nullable|boolean',
            'quizzes' => 'nullable|array',
            'quizzes.*.pertanyaan' => 'required|string',
            'quizzes.*.pilihan_a' => 'required|string',
            'quizzes.*.pilihan_b' => 'required|string',
            'quizzes.*.pilihan_c' => 'required|string',
            'quizzes.*.pilihan_d' => 'required|string',
            'quizzes.*.jawaban_benar' => 'required|in:A,B,C,D',
            'quizzes.*.penjelasan' => 'nullable|string',
        ]);

        $videoId = Training::extractYouTubeId($validated['youtube_url']);
        if (!$videoId) {
            return back()->withInput()->withErrors(['youtube_url' => 'Link YouTube tidak valid. Harap masukkan tautan video YouTube yang benar.']);
        }

        $maxUrutan = Training::max('urutan') ?? 0;

        $training = Training::create([
            'nama_training' => $validated['nama_training'],
            'kategori' => $validated['kategori'],
            'deskripsi' => $validated['deskripsi'],
            'youtube_url' => $validated['youtube_url'],
            'youtube_video_id' => $videoId,
            'thumbnail_url' => "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg",
            'durasi_video' => $validated['durasi_video'] ?: 'Otomatis',
            'jumlah_soal' => $validated['jumlah_soal'] ?: (isset($validated['quizzes']) ? count($validated['quizzes']) : 5),
            'passing_grade' => $validated['passing_grade'] ?? 80,
            'estimasi_waktu' => $validated['durasi_video'] ?: '15 Menit',
            'status' => $validated['status'],
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
            'urutan' => $maxUrutan + 1,
            'slug' => Str::slug($validated['nama_training']) . '-' . Str::random(4),
        ]);

        // Save Quizzes if provided
        if (!empty($validated['quizzes'])) {
            $order = 1;
            foreach ($validated['quizzes'] as $qData) {
                $optAId = (string) \Illuminate\Support\Str::uuid();
                $optBId = (string) \Illuminate\Support\Str::uuid();
                $optCId = (string) \Illuminate\Support\Str::uuid();
                $optDId = (string) \Illuminate\Support\Str::uuid();

                $choices = [
                    ['id' => $optAId, 'text' => $qData['pilihan_a']],
                    ['id' => $optBId, 'text' => $qData['pilihan_b']],
                    ['id' => $optCId, 'text' => $qData['pilihan_c']],
                    ['id' => $optDId, 'text' => $qData['pilihan_d']],
                ];

                $correctAnswer = match ($qData['jawaban_benar']) {
                    'A' => $optAId,
                    'B' => $optBId,
                    'C' => $optCId,
                    'D' => $optDId,
                    default => $optAId,
                };

                TrainingQuiz::create([
                    'training_id' => $training->id,
                    'pertanyaan' => $qData['pertanyaan'],
                    'pilihan_jawaban' => $choices,
                    'jawaban_benar' => $correctAnswer,
                    'penjelasan' => $qData['penjelasan'] ?? null,
                    'urutan' => $order++,
                ]);
            }

            // Sync jumlah_soal with actual questions count
            $training->update(['jumlah_soal' => count($validated['quizzes'])]);
        }

        ActivityLogService::log(
            'tambah',
            'pelatihan',
            "Menambahkan materi pelatihan '{$training->nama_training}'",
            $training->id,
            ['nama' => $training->nama_training, 'kategori' => $training->kategori, 'status' => $training->status]
        );

        return redirect()->route('admin.trainings.index')
            ->with('success', 'Materi pembelajaran "' . $training->nama_training . '" berhasil disimpan!');
    }

    /**
     * Show Edit form for training module.
     */
    public function edit(Training $training): View
    {
        $training->load('quizzes');
        return view('admin.trainings.edit', compact('training'));
    }

    /**
     * Update training module & synchronize quizzes.
     */
    public function update(Request $request, Training $training): RedirectResponse
    {
        $validated = $request->validate([
            'nama_training' => 'required|string|max:255',
            'kategori' => 'required|string|in:product_knowledge,sales_skill,product_update,training,lainnya',
            'deskripsi' => 'required|string',
            'youtube_url' => 'required|string|max:255',
            'durasi_video' => 'nullable|string|max:50',
            'jumlah_soal' => 'nullable|integer|min:1|max:20',
            'passing_grade' => 'required|integer|min:10|max:100',
            'status' => 'required|in:published,draft',
            'is_active' => 'nullable|boolean',
            'quizzes' => 'nullable|array',
            'quizzes.*.pertanyaan' => 'required|string',
            'quizzes.*.pilihan_a' => 'required|string',
            'quizzes.*.pilihan_b' => 'required|string',
            'quizzes.*.pilihan_c' => 'required|string',
            'quizzes.*.pilihan_d' => 'required|string',
            'quizzes.*.jawaban_benar' => 'required|in:A,B,C,D',
            'quizzes.*.penjelasan' => 'nullable|string',
        ]);

        $videoId = Training::extractYouTubeId($validated['youtube_url']);
        if (!$videoId) {
            return back()->withInput()->withErrors(['youtube_url' => 'Link YouTube tidak valid. Harap masukkan tautan video YouTube yang benar.']);
        }

        $training->update([
            'nama_training' => $validated['nama_training'],
            'kategori' => $validated['kategori'],
            'deskripsi' => $validated['deskripsi'],
            'youtube_url' => $validated['youtube_url'],
            'youtube_video_id' => $videoId,
            'thumbnail_url' => "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg",
            'durasi_video' => $validated['durasi_video'] ?: ($training->durasi_video ?: 'Otomatis'),
            'jumlah_soal' => $validated['jumlah_soal'] ?: (isset($validated['quizzes']) ? count($validated['quizzes']) : 5),
            'passing_grade' => $validated['passing_grade'] ?? 80,
            'status' => $validated['status'],
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        // If quizzes are submitted, replace existing ones
        if (isset($validated['quizzes'])) {
            $training->quizzes()->delete();

            $order = 1;
            foreach ($validated['quizzes'] as $qData) {
                $optAId = (string) \Illuminate\Support\Str::uuid();
                $optBId = (string) \Illuminate\Support\Str::uuid();
                $optCId = (string) \Illuminate\Support\Str::uuid();
                $optDId = (string) \Illuminate\Support\Str::uuid();

                $choices = [
                    ['id' => $optAId, 'text' => $qData['pilihan_a']],
                    ['id' => $optBId, 'text' => $qData['pilihan_b']],
                    ['id' => $optCId, 'text' => $qData['pilihan_c']],
                    ['id' => $optDId, 'text' => $qData['pilihan_d']],
                ];

                $correctAnswer = match ($qData['jawaban_benar']) {
                    'A' => $optAId,
                    'B' => $optBId,
                    'C' => $optCId,
                    'D' => $optDId,
                    default => $optAId,
                };

                TrainingQuiz::create([
                    'training_id' => $training->id,
                    'pertanyaan' => $qData['pertanyaan'],
                    'pilihan_jawaban' => $choices,
                    'jawaban_benar' => $correctAnswer,
                    'penjelasan' => $qData['penjelasan'] ?? null,
                    'urutan' => $order++,
                ]);
            }

            $training->update(['jumlah_soal' => count($validated['quizzes'])]);
        }

        ActivityLogService::log(
            'ubah',
            'pelatihan',
            "Memperbarui materi pelatihan '{$training->nama_training}'",
            $training->id,
            ['nama' => $training->nama_training, 'kategori' => $training->kategori, 'status' => $training->status]
        );

        return redirect()->route('admin.trainings.index')
            ->with('success', 'Materi pembelajaran "' . $training->nama_training . '" berhasil diperbarui!');
    }

    /**
     * Show Manage page (Materials & Quizzes) for backward compatibility.
     */
    public function manage(Training $training): View
    {
        $training->load(['materials', 'quizzes']);
        return view('admin.trainings.manage', compact('training'));
    }

    /**
     * Toggle active/inactive status of training module.
     */
    public function toggleStatus(Training $training): RedirectResponse
    {
        $training->is_active = !$training->is_active;
        $training->save();

        ActivityLogService::log(
            'ubah',
            'pelatihan',
            "Mengubah status aktif materi pelatihan '{$training->nama_training}' menjadi " . ($training->is_active ? 'Aktif' : 'Nonaktif'),
            $training->id,
            ['is_active' => $training->is_active]
        );

        $statusText = $training->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Materi \"{$training->nama_training}\" berhasil {$statusText}.");
    }

    /**
     * Delete training module.
     */
    public function destroy(Training $training): RedirectResponse
    {
        $nama = $training->nama_training;
        $id = $training->id;
        $training->delete();

        ActivityLogService::log(
            'hapus',
            'pelatihan',
            "Menghapus materi pelatihan '{$nama}'",
            $id,
            ['nama' => $nama]
        );

        return redirect()->route('admin.trainings.index')
            ->with('success', "Materi pembelajaran \"{$nama}\" berhasil dihapus.");
    }


}
