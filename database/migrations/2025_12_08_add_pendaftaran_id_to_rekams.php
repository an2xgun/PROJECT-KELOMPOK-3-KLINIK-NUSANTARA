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
        Schema::table('rekams', function (Blueprint $table) {
            if (!Schema::hasColumn('rekams', 'pendaftaran_id')) {
                $table->unsignedBigInteger('pendaftaran_id')->nullable()->after('id');
                $table->foreign('pendaftaran_id')->references('id')->on('pendaftaran')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekams', function (Blueprint $table) {
            if (Schema::hasColumn('rekams', 'pendaftaran_id')) {
                $table->dropForeign(['pendaftaran_id']);
                $table->dropColumn('pendaftaran_id');
            }
        });
    }
};
