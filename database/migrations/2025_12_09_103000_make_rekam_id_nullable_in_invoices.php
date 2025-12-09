<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Use raw statements to avoid requiring doctrine/dbal for simple alter
        // Drop existing foreign key if it exists, make column nullable, then re-add FK
        // Adjust the FK name conservatively to the Laravel convention
        $fkName = 'invoices_rekam_id_foreign';

        // Drop FK if exists
        try {
            DB::statement("ALTER TABLE `invoices` DROP FOREIGN KEY `{$fkName}`;");
        } catch (\Throwable $e) {
            // ignore if FK does not exist
        }

        // Make column nullable
        try {
            DB::statement("ALTER TABLE `invoices` MODIFY `rekam_id` BIGINT UNSIGNED NULL;");
        } catch (\Throwable $e) {
            // ignore failures; some environments may require doctrine/dbal
        }

        // Recreate foreign key with ON DELETE SET NULL
        try {
            DB::statement("ALTER TABLE `invoices` ADD CONSTRAINT `{$fkName}` FOREIGN KEY (`rekam_id`) REFERENCES `rekams`(`id`) ON DELETE SET NULL;");
        } catch (\Throwable $e) {
            // ignore if cannot create
        }
    }

    public function down(): void
    {
        $fkName = 'invoices_rekam_id_foreign';

        try {
            DB::statement("ALTER TABLE `invoices` DROP FOREIGN KEY `{$fkName}`;");
        } catch (\Throwable $e) {
            // ignore
        }

        // Make column NOT NULL again (set default 0 temporarily when nulls exist)
        try {
            DB::statement("UPDATE `invoices` SET `rekam_id` = 0 WHERE `rekam_id` IS NULL;");
        } catch (\Throwable $e) {
        }

        try {
            DB::statement("ALTER TABLE `invoices` MODIFY `rekam_id` BIGINT UNSIGNED NOT NULL;");
        } catch (\Throwable $e) {
        }

        // Re-add original FK with cascade
        try {
            DB::statement("ALTER TABLE `invoices` ADD CONSTRAINT `{$fkName}` FOREIGN KEY (`rekam_id`) REFERENCES `rekams`(`id`) ON DELETE CASCADE;");
        } catch (\Throwable $e) {
        }
    }
};
