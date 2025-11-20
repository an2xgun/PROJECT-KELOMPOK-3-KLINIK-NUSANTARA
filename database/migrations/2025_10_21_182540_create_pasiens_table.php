<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasiens', function (Blueprint $table) {
    $table->id();
    $table->string('nik')->unique();
    $table->string('no_rm')->unique();
    $table->string('nama');
    $table->text('alamat');
    $table->string('jenis_kelamin');
    $table->date('tanggal_lahir');
    $table->string('no_telepon');
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('pasiens');
    }
};
