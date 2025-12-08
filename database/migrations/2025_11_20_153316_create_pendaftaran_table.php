<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pasien');
            $table->integer('id_poli');
            $table->string('nomor_antrian')->nullable();
            $table->string('status')->nullable();
            $table->string('status_layanan')->default('Menunggu');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pendaftaran');
    }
};
