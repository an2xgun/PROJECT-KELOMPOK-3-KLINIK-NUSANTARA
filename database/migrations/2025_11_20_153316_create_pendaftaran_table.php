<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasiens')->cascadeOnDelete();
            $table->foreignId('poliklinik_id')->constrained('polikliniks')->cascadeOnDelete();
            $table->foreignId('jadwal_poli_id')->nullable()->constrained('jadwal_polis')->nullOnDelete();
            $table->foreignId('tindakan_id')->nullable()->constrained('master_tindakan')->nullOnDelete();
            $table->foreignId('diagnosa_id')->nullable()->constrained('master_diagnosa')->nullOnDelete();
            $table->date('tanggal_kunjungan')->nullable();
            $table->enum('status_kunjungan',['Sehat','Sakit'])->default('Sakit');
            $table->string('jenis_pembayaran')->nullable();
            $table->string('no_antrian')->nullable(); // format A-001 / G-001 / K-001
            $table->enum('status_antrian',['Sedang Antri','Telah Dilayani'])->default('Sedang Antri');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->enum('status_layanan', ['Menunggu','Dalam Antrian','Sedang Dilayani','Selesai','Batal'])
      ->default('Menunggu');

        });
    }
    public function down(): void {
        Schema::dropIfExists('pendaftaran');
    }
};
