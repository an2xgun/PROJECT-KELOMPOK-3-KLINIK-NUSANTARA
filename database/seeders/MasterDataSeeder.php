<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Poliklinik;
use App\Models\Dokter;
use App\Models\JadwalPoli;
use App\Models\MasterTindakan;
use App\Models\MasterDiagnosa;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        Poliklinik::insert([
            ['kode'=>'A','nama'=>'Poli Umum','keterangan'=>'Poliklinik Umum'],
            ['kode'=>'G','nama'=>'Poli Gigi','keterangan'=>'Poli Kesehatan Gigi'],
            ['kode'=>'K','nama'=>'Poli Kandungan','keterangan'=>'Poli Kandungan'],
        ]);

        MasterTindakan::insert([
            ['kode'=>'T001','nama'=>'Konsultasi','harga'=>50000],
            ['kode'=>'T002','nama'=>'Scaling','harga'=>80000],
            ['kode'=>'T003','nama'=>'Suntik Vaksin','harga'=>70000],
        ]);

        MasterDiagnosa::insert([
            ['kode'=>'D001','nama'=>'ISPA'],
            ['kode'=>'D002','nama'=>'Karies Gigi'],
            ['kode'=>'D003','nama'=>'Kehamilan Trimester I'],
        ]);

        $d1 = Dokter::create(['nama'=>'Dr. Siti','spesialis'=>'Umum']);
        $d2 = Dokter::create(['nama'=>'Dr. Budi','spesialis'=>'Gigi']);
        $d3 = Dokter::create(['nama'=>'Dr. Mira','spesialis'=>'Obgyn']);

        $p1 = Poliklinik::where('kode','A')->first();
        $p2 = Poliklinik::where('kode','G')->first();
        $p3 = Poliklinik::where('kode','K')->first();

        JadwalPoli::create(['poliklinik_id'=>$p1->id,'dokter_id'=>$d1->id,'hari'=>'Senin','jam_mulai'=>'08:00:00','jam_selesai'=>'12:00:00']);
        JadwalPoli::create(['poliklinik_id'=>$p2->id,'dokter_id'=>$d2->id,'hari'=>'Selasa','jam_mulai'=>'09:00:00','jam_selesai'=>'13:00:00']);
        JadwalPoli::create(['poliklinik_id'=>$p3->id,'dokter_id'=>$d3->id,'hari'=>'Rabu','jam_mulai'=>'10:00:00','jam_selesai'=>'14:00:00']);
    }
}
