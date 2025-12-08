<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'no_bpjs')) {
                $table->string('no_bpjs')->nullable()->after('jenis_pembayaran');
            }
            if (!Schema::hasColumn('invoices', 'keterangan_pembayaran')) {
                $table->string('keterangan_pembayaran')->nullable()->after('no_bpjs');
            }
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'keterangan_pembayaran')) {
                $table->dropColumn('keterangan_pembayaran');
            }
            if (Schema::hasColumn('invoices', 'no_bpjs')) {
                $table->dropColumn('no_bpjs');
            }
        });
    }
};
