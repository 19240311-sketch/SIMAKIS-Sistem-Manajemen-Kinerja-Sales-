<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds an indexed is_demo flag to all relevant tables to cleanly separate demo/simulation data from real operational data.
     */
    public function up(): void
    {
        $tables = [
            'users',
            'prospek',
            'sales_transactions',
            'sales_targets',
            'reward_programs',
            'sales_rewards',
            'field_findings',
            'meetings',
            'meeting_attendances',
            'performance_evaluations',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'is_demo')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->boolean('is_demo')->default(false)->index();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users',
            'prospek',
            'sales_transactions',
            'sales_targets',
            'reward_programs',
            'sales_rewards',
            'field_findings',
            'meetings',
            'meeting_attendances',
            'performance_evaluations',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'is_demo')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropIndex(['is_demo']);
                    $table->dropColumn('is_demo');
                });
            }
        }
    }
};
