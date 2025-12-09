<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\JadwalPoli;

class PendaftaranTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data yang sudah ada
        $pasiens = Pasien::limit(3)->get();
        $jadwals = JadwalPoli::limit(2)->get();

        if ($pasiens->isEmpty() || $jadwals->isEmpty()) {
            $this->command->warn('❌ Data master tidak lengkap. Jalankan seeder lain terlebih dahulu.');
            return;
        }

        // Buat beberapa pendaftaran test
        $counter = 0;
        foreach ($pasiens as $pasien) {
            foreach ($jadwals as $jadwal) {
                $counter++;
                
                // Skip jika sudah dibuat
                if (Pendaftaran::where('pasien_id', $pasien->id)
                               ->where('jadwal_poli_id', $jadwal->id)
                               ->exists()) {
                    continue;
                }

                Pendaftaran::create([
                    'pasien_id' => $pasien->id,
                    'poliklinik_id' => $jadwal->poliklinik_id,
                    'jadwal_poli_id' => $jadwal->id,
                    'nomor_antrian' => str_pad($counter, 3, '0', STR_PAD_LEFT),
                    'keluhan' => 'Test - ' . ['Sakit perut', 'Sakit kepala', 'Gigi berlubang', 'Hamil'][rand(0, 3)],
                    'jenis_pembayaran' => ['Umum', 'BPJS', 'Asuransi'][rand(0, 2)],
                    'no_bpjs' => null,
                    'tanggal_kunjungan' => now()->format('Y-m-d'),
                    'status_layanan' => ['Menunggu', 'Sedang Dilayani'][rand(0, 1)],
                ]);

                $this->command->info("✅ Pendaftaran #{$counter} dibuat: {$pasien->nama}");
            }
        }

        $this->command->info("\n✅ Test seeder selesai! Total: {$counter} pendaftaran");
    }
}
