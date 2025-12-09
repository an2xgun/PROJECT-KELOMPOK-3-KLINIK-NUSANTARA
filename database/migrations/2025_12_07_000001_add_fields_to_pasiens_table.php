<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pasiens', function (Blueprint $table) {
            $table->string('no_rm')->nullable()->after('id');
            $table->string('golongan_darah')->nullable()->after('kelamin');
            $table->string('provinsi')->nullable()->after('alamat');
            $table->string('jenis_pasien')->default('Umum')->after('pekerjaan'); // Umum atau Asuransi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasiens', function (Blueprint $table) {
            $table->dropColumn(['no_rm', 'golongan_darah', 'provinsi', 'jenis_pasien']);
        });
    }
};
