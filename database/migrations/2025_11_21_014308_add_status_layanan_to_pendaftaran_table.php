<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up() {
Schema::create('pendaftarans', function (Blueprint $table) {
$table->id();
$table->foreignId('pasien_id')->constrained()->onDelete('cascade');
$table->foreignId('poliklinik_id')->constrained()->onDelete('cascade');
$table->string('nomor_antrian'); // A-001, G-001, K-001
$table->enum('status', ['Menunggu','Dalam Antrian','Sedang Dilayani','Selesai','Batal'])->default('Menunggu');
$table->timestamps();
});
}
public function down() {
Schema::dropIfExists('pendaftarans');
}
};
?>