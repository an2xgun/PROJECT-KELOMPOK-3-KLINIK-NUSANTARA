<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // Copy dokter id from rekams to prescriptions where missing
        // Use affectingStatement to get number of affected rows
        $count = DB::affectingStatement(
            "UPDATE prescriptions p JOIN rekams r ON p.rekam_id = r.id SET p.dokter_id = r.id_dokter WHERE p.dokter_id IS NULL AND r.id_dokter IS NOT NULL"
        );

        // Optionally log output to the migration output
        echo "Backfilled prescriptions.dokter_id for {$count} rows\n";
    }

    public function down(): void {
        // No-op: do not revert data back to null
    }
};
