<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('rekams', function (Blueprint $table) {
            if (!Schema::hasColumn('rekams', 'status_pemeriksaan')) {
                $table->string('status_pemeriksaan')->nullable()->default('Belum Diperiksa')->after('keterangan');
            }
            if (!Schema::hasColumn('rekams', 'catatan_status')) {
                $table->text('catatan_status')->nullable()->after('status_pemeriksaan');
            }
        });
    }

    public function down(): void {
        Schema::table('rekams', function (Blueprint $table) {
            if (Schema::hasColumn('rekams', 'status_pemeriksaan')) {
                $table->dropColumn('status_pemeriksaan');
            }
            if (Schema::hasColumn('rekams', 'catatan_status')) {
                $table->dropColumn('catatan_status');
            }
        });
    }
};
