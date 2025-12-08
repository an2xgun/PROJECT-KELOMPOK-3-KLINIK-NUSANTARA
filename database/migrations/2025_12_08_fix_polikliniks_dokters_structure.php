<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Perbaikan struktur: 
     * - Rename 'polis' ke 'polikliniks' (jika belum ada)
     * - Fix foreign keys di dokters dan jadwal_polis
     */
    public function up(): void
    {
        // Jika tabel 'polis' ada, rename ke 'polikliniks'
        if (Schema::hasTable('polis') && !Schema::hasTable('polikliniks')) {
            Schema::rename('polis', 'polikliniks');
        }

        // Buat polikliniks jika belum ada
        if (!Schema::hasTable('polikliniks')) {
            Schema::create('polikliniks', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }

        // Fix dokters table - tambah foreign key ke polikliniks
        if (Schema::hasTable('dokters')) {
            if (!Schema::hasColumn('dokters', 'poliklinik_id')) {
                Schema::table('dokters', function (Blueprint $table) {
                    // Jika id_poli masih ada, gunakan sebagai poliklinik_id
                    if (Schema::hasColumn('dokters', 'id_poli')) {
                        $table->renameColumn('id_poli', 'poliklinik_id');
                    } else {
                        $table->foreignId('poliklinik_id')->nullable()->constrained('polikliniks')->nullOnDelete();
                    }
                });
            }
        }
    }

    public function down(): void
    {
        // Rollback: rename kembali polikliniks ke polis
        if (Schema::hasTable('polikliniks')) {
            Schema::rename('polikliniks', 'polis');
        }
    }
};
