<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rekam_diagnosa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rekam_id');
            $table->unsignedBigInteger('diagnosa_id');
            $table->timestamps();

            $table->foreign('rekam_id')->references('id')->on('rekams')->onDelete('cascade');
            $table->foreign('diagnosa_id')->references('id')->on('master_diagnosa')->onDelete('cascade');
            $table->unique(['rekam_id','diagnosa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekam_diagnosa');
    }
};
