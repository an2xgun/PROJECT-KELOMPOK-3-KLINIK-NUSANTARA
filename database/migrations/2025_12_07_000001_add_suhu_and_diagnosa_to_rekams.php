<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('rekams', function (Blueprint $table) {
            if (!Schema::hasColumn('rekams', 'suhu')) {
                $table->string('suhu')->nullable()->after('berat');
            }
            if (!Schema::hasColumn('rekams', 'diagnosa_primer')) {
                $table->unsignedBigInteger('diagnosa_primer')->nullable()->after('diagnosa');
            }
            if (!Schema::hasColumn('rekams', 'diagnosa_sekunder')) {
                $table->unsignedBigInteger('diagnosa_sekunder')->nullable()->after('diagnosa_primer');
            }
        });
    }

    public function down()
    {
        Schema::table('rekams', function (Blueprint $table) {
            if (Schema::hasColumn('rekams', 'suhu')) $table->dropColumn('suhu');
            if (Schema::hasColumn('rekams', 'diagnosa_primer')) $table->dropColumn('diagnosa_primer');
            if (Schema::hasColumn('rekams', 'diagnosa_sekunder')) $table->dropColumn('diagnosa_sekunder');
        });
    }
};
