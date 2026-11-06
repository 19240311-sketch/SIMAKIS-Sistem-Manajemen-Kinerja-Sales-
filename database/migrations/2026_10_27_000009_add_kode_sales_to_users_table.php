<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'kode_sales')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('kode_sales', 50)->nullable()->after('peran');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'kode_sales')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('kode_sales');
            });
        }
    }
};
