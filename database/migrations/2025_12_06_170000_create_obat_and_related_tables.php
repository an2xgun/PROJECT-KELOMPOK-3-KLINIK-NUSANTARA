<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('jenis', function (Blueprint $table) {
            $table->id();
            $table->string('jenisobat');
            $table->timestamps();
        });

        Schema::create('obats', function (Blueprint $table) {
            $table->id();
            $table->string('kodeobat')->nullable();
            $table->integer('stok')->nullable();
            $table->integer('id_jenis')->nullable();
            $table->string('nama');
            $table->string('dosis')->nullable();
            $table->string('harga')->nullable();
            $table->date('expired')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });

        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('kodepegawai')->nullable();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('kelamin');
            $table->string('telepon')->nullable();
            $table->string('agama');
            $table->string('jabatan')->nullable();
            $table->timestamps();
        });

        Schema::create('rekams', function (Blueprint $table) {
            $table->id();
            $table->integer('laporan')->default(0);
            $table->integer('id_pasien');
            $table->string('nomorantrian');
            $table->date('tanggalperiksa')->nullable();
            $table->string('layanan');
            $table->string('keluhan');
            $table->integer('id_dokter');
            $table->string('diagnosa')->nullable();
            $table->integer('id_obat')->nullable();
            $table->string('jumlahobat')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('rawat')->nullable();
            $table->string('lamabaru')->nullable();
            $table->string('darah')->nullable();
            $table->string('tinggi')->nullable();
            $table->string('berat')->nullable();
            $table->string('pinggang')->nullable();
            $table->dateTime('jadwal_kedatangan')->nullable();
            $table->dateTime('jadwal_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('rekams');
        Schema::dropIfExists('pegawais');
        Schema::dropIfExists('obats');
        Schema::dropIfExists('jenis');
    }
};
