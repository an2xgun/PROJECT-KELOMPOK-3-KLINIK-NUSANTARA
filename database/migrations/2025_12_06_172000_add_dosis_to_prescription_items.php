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
        Schema::table('prescription_items', function (Blueprint $table) {
            // Add dosis column if it doesn't exist
            if (!Schema::hasColumn('prescription_items', 'dosis')) {
                $table->string('dosis')->nullable()->after('obat_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescription_items', function (Blueprint $table) {
            if (Schema::hasColumn('prescription_items', 'dosis')) {
                $table->dropColumn('dosis');
            }
        });
    }
};
