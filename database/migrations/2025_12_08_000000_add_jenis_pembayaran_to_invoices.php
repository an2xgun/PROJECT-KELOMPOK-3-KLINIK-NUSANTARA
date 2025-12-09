<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('invoices') && !Schema::hasColumn('invoices', 'jenis_pembayaran')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->string('jenis_pembayaran')->nullable()->after('layanan');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('invoices') && Schema::hasColumn('invoices', 'jenis_pembayaran')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropColumn('jenis_pembayaran');
            });
        }
    }
};
