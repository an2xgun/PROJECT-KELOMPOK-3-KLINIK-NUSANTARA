<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('rekam_tindakan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rekam_id');
            $table->unsignedBigInteger('master_tindakan_id');
            $table->timestamps();

            $table->foreign('rekam_id')->references('id')->on('rekams')->onDelete('cascade');
            $table->foreign('master_tindakan_id')->references('id')->on('master_tindakan')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rekam_tindakan');
    }
};
