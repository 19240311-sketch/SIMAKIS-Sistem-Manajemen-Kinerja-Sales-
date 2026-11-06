<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modify peran column to VARCHAR(50) so it accepts 'supervisor', 'admin', 'sales'
        Schema::table('users', function (Blueprint $table) {
            $table->string('peran', 50)->default('sales')->change();
        });

        // Update existing 'admin' to 'supervisor'
        DB::table('users')->where('peran', 'admin')->update(['peran' => 'supervisor']);
    }

    public function down(): void
    {
        DB::table('users')->where('peran', 'supervisor')->update(['peran' => 'admin']);
        Schema::table('users', function (Blueprint $table) {
            $table->string('peran', 50)->default('sales')->change();
        });
    }
};
