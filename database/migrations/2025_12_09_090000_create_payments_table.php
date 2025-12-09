<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('user_id')->nullable(); // kasir pengguna
            $table->string('method')->nullable();
            $table->string('no_bpjs')->nullable();
            $table->text('note')->nullable();
            $table->decimal('amount', 14, 2)->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->index('invoice_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
