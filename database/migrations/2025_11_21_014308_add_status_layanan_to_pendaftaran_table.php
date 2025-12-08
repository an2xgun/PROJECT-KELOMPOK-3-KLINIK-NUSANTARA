<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pendaftaran', function (Blueprint $table) {
            // Rename id_poli to poliklinik_id and make it a proper FK
            if (Schema::hasColumn('pendaftaran', 'id_poli')) {
                $table->dropColumn('id_poli');
            }
            if (!Schema::hasColumn('pendaftaran', 'poliklinik_id')) {
                $table->foreignId('poliklinik_id')->constrained('polikliniks')->onDelete('cascade');
            }
            // Rename id_pasien to pasien_id if it exists
            if (Schema::hasColumn('pendaftaran', 'id_pasien') && !Schema::hasColumn('pendaftaran', 'pasien_id')) {
                $table->renameColumn('id_pasien', 'pasien_id');
            }
            // Add constraint to pasien_id if not exists
            if (Schema::hasColumn('pendaftaran', 'pasien_id')) {
                try {
                    $table->foreign('pasien_id')->references('id')->on('pasiens')->onDelete('cascade');
                } catch (\Exception $e) {
                    // FK might already exist, skip
                }
            }
        });
    }

    public function down(): void {
        Schema::table('pendaftaran', function (Blueprint $table) {
            // Revert changes
            if (Schema::hasColumn('pendaftaran', 'poliklinik_id')) {
                $table->dropForeign(['poliklinik_id']);
                $table->dropColumn('poliklinik_id');
            }
            if (!Schema::hasColumn('pendaftaran', 'id_poli')) {
                $table->integer('id_poli')->nullable();
            }
        });
    }
};
?>