<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('aktivitas_prospek') && !Schema::hasColumn('aktivitas_prospek', 'is_demo')) {
            Schema::table('aktivitas_prospek', function (Blueprint $table) {
                $table->boolean('is_demo')->default(false)->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('aktivitas_prospek') && Schema::hasColumn('aktivitas_prospek', 'is_demo')) {
            Schema::table('aktivitas_prospek', function (Blueprint $table) {
                $table->dropIndex(['is_demo']);
                $table->dropColumn('is_demo');
            });
        }
    }
};
