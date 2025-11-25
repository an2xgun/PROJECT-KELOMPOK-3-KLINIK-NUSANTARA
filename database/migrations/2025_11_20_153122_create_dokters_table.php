<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('dokters', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('spesialis'); // Umum, Gigi, Kandungan
            $table->string('no_izin')->nullable();
            $table->string('telepon')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('dokters');
    }
};
