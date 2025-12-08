<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftaran', 'jadwal_poli_id')) {
                $table->integer('jadwal_poli_id')->nullable();
            }
            if (!Schema::hasColumn('pendaftaran', 'keluhan')) {
                $table->text('keluhan')->nullable();
            }
            if (!Schema::hasColumn('pendaftaran', 'jenis_pembayaran')) {
                $table->string('jenis_pembayaran')->nullable();
            }
            if (!Schema::hasColumn('pendaftaran', 'tanggal_kunjungan')) {
                $table->date('tanggal_kunjungan')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            if (Schema::hasColumn('pendaftaran', 'jadwal_poli_id')) {
                $table->dropColumn('jadwal_poli_id');
            }
            if (Schema::hasColumn('pendaftaran', 'keluhan')) {
                $table->dropColumn('keluhan');
            }
            if (Schema::hasColumn('pendaftaran', 'jenis_pembayaran')) {
                $table->dropColumn('jenis_pembayaran');
            }
            if (Schema::hasColumn('pendaftaran', 'tanggal_kunjungan')) {
                $table->dropColumn('tanggal_kunjungan');
            }
        });
    }
};
