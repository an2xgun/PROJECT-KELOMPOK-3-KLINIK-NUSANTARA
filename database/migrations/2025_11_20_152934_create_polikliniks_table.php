<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up() {
Schema::create('polikliniks', function (Blueprint $table) {
$table->id();
$table->string('nama_poli');
$table->string('kode'); // A, G, K
$table->timestamps();
});
}
public function down() {
Schema::dropIfExists('polikliniks');
}
};
?>
