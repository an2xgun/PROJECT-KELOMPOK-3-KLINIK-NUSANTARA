<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id();
            $table->string('no_rm')->unique(); // auto generated
            $table->string('nama');
            $table->string('nik')->unique()->nullable();
            $table->string('agama')->nullable();
            $table->string('pendidikan')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin',['L','P'])->nullable();
            $table->text('alamat')->nullable();
            $table->string('kontak')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('pasiens');
    }
};
