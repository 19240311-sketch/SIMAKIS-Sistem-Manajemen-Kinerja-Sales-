<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Permanently drops supervisor_handovers and supervisor_histories tables.
     */
    public function up(): void
    {
        Schema::dropIfExists('supervisor_handovers');
        Schema::dropIfExists('supervisor_histories');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tables are permanently removed per SIMAKIS system update.
    }
};

