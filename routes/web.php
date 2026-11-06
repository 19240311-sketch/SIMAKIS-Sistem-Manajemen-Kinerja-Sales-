<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminTrainingController;
use App\Http\Controllers\AdminWeeklyTestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FieldFindingController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\MotorcycleController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProspectActivityController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\ProspectStatusController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\SalesDataController;
use App\Http\Controllers\SalesTargetController;
use App\Http\Controllers\SalesTransactionController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\SupervisorHandoverController;
use App\Http\Controllers\SupervisorManagementController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WeeklyTestController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SIMAKIS (Sistem Manajemen Kinerja Sales)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/home', function () {
    return redirect()->route('dashboard');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Logout Route (support POST & GET, bypass auth in case session already dead)
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {

    // 1. Dashboard SIMAKIS
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/demo/clean', [DashboardController::class, 'cleanDemoData'])->name('demo.clean');

    // AJAX: Notifikasi real-time (dipanggil client-side setelah halaman load, hindari timeout)
    Route::get('/api/notifications', [NotificationController::class, 'fetch'])->name('notifications.fetch');

    // 2. Data Sales & Peringkat Penjualan
    Route::resource('sales', SalesDataController::class)->names('sales');
    Route::patch('/sales/{sale}/toggle', [SalesDataController::class, 'toggleStatus'])->name('sales.toggle');

    // 3. Transaksi Penjualan Motor (Realisasi Unit)
    Route::resource('transactions', SalesTransactionController::class)->names('transactions');

    // 4. Pengelolaan Target Penjualan
    Route::resource('targets', SalesTargetController::class)->names('targets');

    // 5. Penilaian & Evaluasi Kinerja Sales
    Route::get('/performance', [PerformanceController::class, 'index'])->name('performance.index');
    Route::post('/performance/evaluate', [PerformanceController::class, 'storeEvaluation'])->name('performance.evaluate');
    Route::put('/performance/criteria', [PerformanceController::class, 'updateCriteria'])->name('performance.criteria.update');

    // 6. Program Reward & Apresiasi Sales
    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::get('/rewards/program/create', [RewardController::class, 'createProgram'])->name('rewards.programs.create');
    Route::post('/rewards/program', [RewardController::class, 'storeProgram'])->name('rewards.programs.store');
    Route::patch('/rewards/{reward}/verify', [RewardController::class, 'verify'])->name('rewards.verify');

    // 7. Temuan Lapangan Sales
    Route::resource('field-findings', FieldFindingController::class)->names('field-findings');

    // 8. Rapat Mingguan (Online / Offline / Hybrid)
    Route::resource('meetings', MeetingController::class)->names('meetings');
    Route::post('/meetings/{meeting}/attendance', [MeetingController::class, 'confirmAttendance'])->name('meetings.attendance');
    Route::put('/meetings/{meeting}/minutes', [MeetingController::class, 'updateMinutes'])->name('meetings.minutes');

    // 9. Laporan-Laporan & Ekspor Word (.docx)
    // 9.1 Laporan Penjualan
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/sales/word', [ReportController::class, 'exportSalesWord'])->name('reports.sales.word');

    // 9.2 Laporan Penjualan per Sales
    Route::get('/reports/sales-person', [ReportController::class, 'salesPerson'])->name('reports.sales-person');
    Route::get('/reports/sales-person/word', [ReportController::class, 'exportSalesPersonWord'])->name('reports.sales-person.word');

    // 9.3 Laporan Kinerja Sales
    Route::get('/reports/performance', [ReportController::class, 'performance'])->name('reports.performance');
    Route::get('/reports/performance/word', [ReportController::class, 'exportPerformanceWord'])->name('reports.performance.word');

    // 10. Fitur Lama yang Dipertahankan: Prospects Management
    Route::get('/prospects/export', [ProspectController::class, 'export'])->name('prospects.export');
    Route::resource('prospects', ProspectController::class);
    Route::post('/prospects/{prospect}/activities', [ProspectActivityController::class, 'store'])->name('prospects.activities.store');

    // 11. Fitur Lama: Training & Knowledge Sales
    Route::get('/trainings', [TrainingController::class, 'index'])->name('trainings.index');
    Route::get('/trainings/{training}', [TrainingController::class, 'show'])->name('trainings.show');
    Route::post('/trainings/{training}/video-progress', [TrainingController::class, 'updateVideoProgress'])->name('trainings.video-progress');
    Route::post('/trainings/{training}/materials/{material}/complete', [TrainingController::class, 'completeMaterial'])->name('trainings.materials.complete');
    Route::get('/trainings/{training}/quiz', [TrainingController::class, 'quiz'])->name('trainings.quiz');
    Route::post('/trainings/{training}/quiz', [TrainingController::class, 'submitQuiz'])->name('trainings.quiz.submit');

    // 12. Fitur Lama: Tes Mingguan
    Route::get('/weekly-tests', [WeeklyTestController::class, 'index'])->name('weekly-tests.index');
    Route::get('/weekly-tests/{weeklyTest}/take', [WeeklyTestController::class, 'take'])->name('weekly-tests.take');
    Route::post('/weekly-tests/{weeklyTest}/submit', [WeeklyTestController::class, 'submit'])->name('weekly-tests.submit');
    Route::get('/weekly-tests/{weeklyTest}/result', [WeeklyTestController::class, 'result'])->name('weekly-tests.result');

    // 13. Profile & Account Settings
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // 13.1 Log Aktivitas Sistem (Scoped by role inside Controller)
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{id}', [ActivityLogController::class, 'show'])->name('activity-logs.show');

    // 13.2 Riwayat Jabatan Supervisor & Serah Terima (Supervisor & Super Admin)
    Route::get('/supervisor/history', [SupervisorManagementController::class, 'history'])->name('supervisor.history');
    Route::get('/supervisor/handovers', [SupervisorHandoverController::class, 'index'])->name('supervisor.handovers.index');
    Route::get('/supervisor/handovers/{id}', [SupervisorHandoverController::class, 'show'])->name('supervisor.handovers.show');
    Route::patch('/supervisor/handovers/{id}/status', [SupervisorHandoverController::class, 'updateStatus'])->name('supervisor.handovers.update-status');

    // 13.3 Super Admin Only Operations
    Route::middleware(SuperAdminMiddleware::class)->group(function () {
        Route::post('/supervisor/replace', [SupervisorManagementController::class, 'replace'])->name('supervisor.replace');
        Route::get('/supervisor/handovers-create', [SupervisorHandoverController::class, 'create'])->name('supervisor.handovers.create');
        Route::post('/supervisor/handovers', [SupervisorHandoverController::class, 'store'])->name('supervisor.handovers.store');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // 14. Supervisor / Master Data Management
    Route::middleware(AdminMiddleware::class)->group(function () {
        // Master Data
        Route::get('/master/motorcycles', [MotorcycleController::class, 'index'])->name('master.motorcycles.index');
        Route::post('/master/motorcycles', [MotorcycleController::class, 'store'])->name('master.motorcycles.store');
        Route::post('/master/motorcycles/import', [MotorcycleController::class, 'import'])->name('master.motorcycles.import');
        Route::get('/master/motorcycles/template', [MotorcycleController::class, 'downloadTemplate'])->name('master.motorcycles.template');
        Route::put('/master/motorcycles/{motorcycle}', [MotorcycleController::class, 'update'])->name('master.motorcycles.update');
        Route::post('/master/motorcycles/bulk-delete', [MotorcycleController::class, 'bulkDestroy'])->name('master.motorcycles.bulk-delete');
        Route::post('/master/motorcycles/delete-all', [MotorcycleController::class, 'deleteAll'])->name('master.motorcycles.delete-all');
        Route::delete('/master/motorcycles/{motorcycle}', [MotorcycleController::class, 'destroy'])->name('master.motorcycles.destroy');
        Route::patch('/master/motorcycles/{motorcycle}/toggle', [MotorcycleController::class, 'toggleStatus'])->name('master.motorcycles.toggle');

        Route::get('/master/sources', [SourceController::class, 'index'])->name('master.sources.index');
        Route::post('/master/sources', [SourceController::class, 'store'])->name('master.sources.store');
        Route::put('/master/sources/{source}', [SourceController::class, 'update'])->name('master.sources.update');
        Route::delete('/master/sources/{source}', [SourceController::class, 'destroy'])->name('master.sources.destroy');

        Route::get('/master/statuses', [ProspectStatusController::class, 'index'])->name('master.statuses.index');
        Route::post('/master/statuses', [ProspectStatusController::class, 'store'])->name('master.statuses.store');
        Route::put('/master/statuses/{status}', [ProspectStatusController::class, 'update'])->name('master.statuses.update');
        Route::delete('/master/statuses/{status}', [ProspectStatusController::class, 'destroy'])->name('master.statuses.destroy');

        // User Accounts
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

        // Modul Training Admin
        Route::get('/admin/trainings', [AdminTrainingController::class, 'index'])->name('admin.trainings.index');
        Route::get('/admin/trainings/create', [AdminTrainingController::class, 'create'])->name('admin.trainings.create');
        Route::post('/admin/trainings', [AdminTrainingController::class, 'store'])->name('admin.trainings.store');
        Route::get('/admin/trainings/{training}/edit', [AdminTrainingController::class, 'edit'])->name('admin.trainings.edit');
        Route::get('/admin/trainings/{training}/manage', [AdminTrainingController::class, 'manage'])->name('admin.trainings.manage');
        Route::put('/admin/trainings/{training}', [AdminTrainingController::class, 'update'])->name('admin.trainings.update');
        Route::patch('/admin/trainings/{training}/toggle', [AdminTrainingController::class, 'toggleStatus'])->name('admin.trainings.toggle');
        Route::delete('/admin/trainings/{training}', [AdminTrainingController::class, 'destroy'])->name('admin.trainings.destroy');
        Route::post('/admin/trainings/generate-ai-questions', [AdminTrainingController::class, 'generateAiQuestions'])->name('admin.trainings.generate-ai-questions');

        Route::post('/admin/trainings/{training}/materials', [AdminTrainingController::class, 'storeMaterial'])->name('admin.trainings.materials.store');
        Route::put('/admin/trainings/{training}/materials/{material}', [AdminTrainingController::class, 'updateMaterial'])->name('admin.trainings.materials.update');
        Route::delete('/admin/trainings/{training}/materials/{material}', [AdminTrainingController::class, 'destroyMaterial'])->name('admin.trainings.materials.destroy');

        Route::post('/admin/trainings/{training}/quizzes', [AdminTrainingController::class, 'storeQuiz'])->name('admin.trainings.quizzes.store');
        Route::delete('/admin/trainings/{training}/quizzes/{quiz}', [AdminTrainingController::class, 'destroyQuiz'])->name('admin.trainings.quizzes.destroy');

        // Modul Tes Mingguan Admin
        Route::get('/admin/weekly-tests', [AdminWeeklyTestController::class, 'index'])->name('admin.weekly-tests.index');
        Route::post('/admin/weekly-tests', [AdminWeeklyTestController::class, 'store'])->name('admin.weekly-tests.store');
        Route::get('/admin/weekly-tests/{weeklyTest}/manage', [AdminWeeklyTestController::class, 'manage'])->name('admin.weekly-tests.manage');
        Route::put('/admin/weekly-tests/{weeklyTest}', [AdminWeeklyTestController::class, 'update'])->name('admin.weekly-tests.update');
        Route::delete('/admin/weekly-tests/{weeklyTest}', [AdminWeeklyTestController::class, 'destroy'])->name('admin.weekly-tests.destroy');

        Route::post('/admin/weekly-tests/{weeklyTest}/questions', [AdminWeeklyTestController::class, 'storeQuestion'])->name('admin.weekly-tests.questions.store');
        Route::delete('/admin/weekly-tests/{weeklyTest}/questions/{question}', [AdminWeeklyTestController::class, 'destroyQuestion'])->name('admin.weekly-tests.questions.destroy');
    });
});
