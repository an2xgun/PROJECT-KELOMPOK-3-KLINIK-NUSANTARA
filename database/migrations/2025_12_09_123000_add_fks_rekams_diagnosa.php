<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $fk1 = 'rekams_diagnosa_primer_foreign';
        $fk2 = 'rekams_diagnosa_sekunder_foreign';

        try {
            // Drop if existed from previous attempts
            DB::statement("ALTER TABLE `rekams` DROP FOREIGN KEY `{$fk1}`;");
        } catch (\Throwable $e) {}
        try {
            DB::statement("ALTER TABLE `rekams` DROP FOREIGN KEY `{$fk2}`;");
        } catch (\Throwable $e) {}

        // Add foreign keys to master_diagnosa (set null on delete)
        try {
            DB::statement("ALTER TABLE `rekams` ADD CONSTRAINT `{$fk1}` FOREIGN KEY (`diagnosa_primer`) REFERENCES `master_diagnosa`(`id`) ON DELETE SET NULL;");
        } catch (\Throwable $e) {}

        try {
            DB::statement("ALTER TABLE `rekams` ADD CONSTRAINT `{$fk2}` FOREIGN KEY (`diagnosa_sekunder`) REFERENCES `master_diagnosa`(`id`) ON DELETE SET NULL;");
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        $fk1 = 'rekams_diagnosa_primer_foreign';
        $fk2 = 'rekams_diagnosa_sekunder_foreign';

        try {
            DB::statement("ALTER TABLE `rekams` DROP FOREIGN KEY `{$fk1}`;");
        } catch (\Throwable $e) {}
        try {
            DB::statement("ALTER TABLE `rekams` DROP FOREIGN KEY `{$fk2}`;");
        } catch (\Throwable $e) {}
    }
};
