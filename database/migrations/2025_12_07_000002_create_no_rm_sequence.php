<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration creates a separate sequence table for no_rm
     * and updates the pasiens table to use it properly
     */
    public function up(): void
    {
        // Create a sequence table for no_rm tracking
        Schema::create('no_rm_sequences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('next_no_rm')->default(1);
            $table->timestamps();
        });

        // Initialize with 1
        DB::table('no_rm_sequences')->insert([
            'next_no_rm' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Update no_rm column to be unique
        if (Schema::hasTable('pasiens')) {
            Schema::table('pasiens', function (Blueprint $table) {
                // Drop the old no_rm column if it exists and recreate it
                if (Schema::hasColumn('pasiens', 'no_rm')) {
                    $table->dropColumn('no_rm');
                }
            });

            Schema::table('pasiens', function (Blueprint $table) {
                $table->string('no_rm')->nullable()->unique()->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('no_rm_sequences');

        if (Schema::hasTable('pasiens')) {
            Schema::table('pasiens', function (Blueprint $table) {
                if (Schema::hasColumn('pasiens', 'no_rm')) {
                    $table->dropColumn('no_rm');
                }
            });
        }
    }
};
