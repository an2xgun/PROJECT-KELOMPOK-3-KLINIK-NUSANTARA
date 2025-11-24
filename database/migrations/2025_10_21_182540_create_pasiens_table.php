<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasiensTable extends Migration
{
    public function up()
    {
        Schema::create('pasien', function (Blueprint $table) {
            $table->id();
            $table->string('no_rm')->unique();
            $table->string('no_rm_lama')->nullable();
            $table->string('nama');
            $table->string('nik')->nullable();
            $table->string('no_ihs')->nullable();

            // Data pribadi
            $table->string('agama')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('status_keluarga')->nullable();
            $table->date('tanggal_lahir');
            $table->integer('umur_tahun')->nullable();
            $table->integer('umur_bulan')->nullable();
            $table->integer('umur_hari')->nullable();
            $table->string('jenis_kelamin');

            $table->string('gol_darah')->nullable();
            $table->text('alamat')->nullable();
            $table->string('email')->nullable();
            $table->string('no_telp')->nullable();

            // Kolom kanan
            $table->string('pekerjaan')->nullable();
            $table->string('wilayah')->nullable();
            $table->string('desa')->nullable();
            $table->string('rujukan_dari')->nullable();
            $table->string('ket_rujukan')->nullable();
            $table->date('tanggal_kunjungan')->nullable();

            $table->string('tujuan')->nullable(); // Poli
            $table->string('jenis_kunjungan')->nullable();
            $table->string('jenis_pembayaran')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pasien');
    }
}
