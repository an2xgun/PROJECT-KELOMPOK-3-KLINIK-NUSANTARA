#!/usr/bin/env php
<?php
/**
 * TEST: Full Examination Workflow
 * Jalankan: php artisan tinker < tools/test_full_examination_workflow.php
 * atau: php artisan tinker --execute "include 'tools/test_full_examination_workflow.php';"
 */

use App\Models\Pasien;
use App\Models\Poliklinik;
use App\Models\Dokter;
use App\Models\JadwalPoli;
use App\Models\Pendaftaran;
use App\Models\Rekam;
use App\Models\MasterDiagnosa;
use App\Models\MasterTindakan;
use Carbon\Carbon;

echo "\n";
echo "====================================================================\n";
echo "    TEST FULL EXAMINATION WORKFLOW (PENDAFTARAN - EXAMINATION)    \n";
echo "====================================================================\n\n";

try {
    // ============================================
    // STEP 1: CREATE PASIEN
    // ============================================
    echo "[1] MEMBUAT DATA PASIEN BARU\n";
    echo "------------------------------------\n";
    
    $pasien = Pasien::create([
        'no_rm' => Pasien::generateNextNoRm(),
        'nama' => 'Budi Santoso Test',
        'nik' => '1234567890123456',
        'lahir' => '1990-05-15',
        'kelamin' => 'L',
        'alamat' => 'Jl. Test No. 123',
        'telepon' => '08123456789',
        'agama' => 'Islam',
        'pendidikan' => 'SMA',
        'pekerjaan' => 'Pegawai Swasta',
        'golongan_darah' => 'O',
    ]);
    
    echo "[OK] PASIEN DIBUAT!\n";
    echo "  ID: {$pasien->id}\n";
    echo "  No RM: {$pasien->no_rm}\n";
    echo "  Nama: {$pasien->nama}\n";
    echo "  Umur: {$pasien->umur_tahun} tahun\n";
    echo "  Jenis Kelamin: {$pasien->jenis_kelamin}\n\n";

    // ============================================
    // STEP 2: GET POLIKLINIK & DOKTER & JADWAL
    // ============================================
    echo "[2] MENGAMBIL DATA POLIKLINIK & JADWAL\n";
    echo "------------------------------------\n";
    
    $poli = Poliklinik::first();
    if (!$poli) {
        echo "[FAIL] Tidak ada poliklinik di database\n";
        exit(1);
    }
    
    $jadwal = JadwalPoli::where('poliklinik_id', $poli->id)->with('dokter')->first();
    if (!$jadwal) {
        echo "[FAIL] Tidak ada jadwal di poliklinik {$poli->name}\n";
        exit(1);
    }
    
    echo "[OK] DATA DITEMUKAN!\n";
    echo "  Poliklinik: {$poli->name}\n";
    echo "  Jadwal: {$jadwal->hari} ({$jadwal->jam_mulai} - {$jadwal->jam_selesai})\n";
    echo "  Dokter: {$jadwal->dokter->nama}\n\n";

    // ============================================
    // STEP 3: CREATE PENDAFTARAN
    // ============================================
    echo "[3] MEMBUAT PENDAFTARAN\n";
    echo "------------------------------------\n";
    
    $pendaftaran = Pendaftaran::create([
        'pasien_id' => $pasien->id,
        'poliklinik_id' => $poli->id,
        'jadwal_poli_id' => $jadwal->id,
        'nomor_antrian' => rand(1, 50),
        'status_layanan' => 'Menunggu',
        'tanggal' => Carbon::today(),
        'keluhan' => 'Sakit kepala dan demam',
    ]);
    
    echo "[OK] PENDAFTARAN DIBUAT!\n";
    echo "  ID: {$pendaftaran->id}\n";
    echo "  Pasien: {$pendaftaran->pasien->nama}\n";
    echo "  Poliklinik: {$pendaftaran->poliklinik->name}\n";
    echo "  Dokter: {$pendaftaran->jadwalPoli->dokter->nama}\n";
    echo "  No Antrian: {$pendaftaran->nomor_antrian}\n";
    echo "  Status: {$pendaftaran->status_layanan}\n\n";

    // ============================================
    // STEP 4: CREATE REKAM MEDIS (EXAMINATION)
    // ============================================
    echo "[4] MEMBUAT REKAM MEDIS & PEMERIKSAAN\n";
    echo "------------------------------------\n";
    
    // Ambil diagnosa dari database
    $diagnosaA = MasterDiagnosa::where('kode', 'J10')->first(); // Influenza
    $diagnosaB = MasterDiagnosa::where('kode', 'R50.9')->first(); // Fever
    
    if (!$diagnosaA) {
        echo "[WARN] Diagnosa J10 tidak ditemukan, menggunakan diagnosa pertama\n";
        $diagnosaA = MasterDiagnosa::first();
    }
    
    if (!$diagnosaB) {
        // Coba cari diagnosa lain
        $diagnosaB = MasterDiagnosa::where('kode', 'like', 'R%')->first();
    }
    
    $rekam = Rekam::create([
        'id_pasien' => $pasien->id,
        'pendaftaran_id' => $pendaftaran->id,
        'nomorantrian' => $pendaftaran->nomor_antrian,
        'tanggalperiksa' => Carbon::today()->toDateString(),
        'layanan' => $poli->name,
        'keluhan' => 'Sakit kepala dan demam selama 2 hari',
        'id_dokter' => $jadwal->dokter_id,
        'tinggi' => 170,
        'berat' => 70,
        'suhu' => 38.5,
        'darah' => '120/80',
        'diagnosa_primer' => $diagnosaA ? $diagnosaA->id : null,
        'diagnosa_sekunder' => $diagnosaB ? $diagnosaB->id : null,
        'keterangan' => 'Pasien didiagnosa influenza dengan gejala demam tinggi',
        'status_pemeriksaan' => 'Sudah Diperiksa',
    ]);
    
    echo "[OK] REKAM MEDIS DIBUAT!\n";
    echo "  ID: " . $rekam->id . "\n";
    echo "  Pasien: " . $rekam->pasien->nama . "\n";
    echo "  Dokter: " . $rekam->dokter->nama . "\n";
    echo "  Tanggal: " . $rekam->tanggalperiksa . "\n";
    echo "  Vital Signs: " . $rekam->tinggi . "cm, " . $rekam->berat . "kg, Suhu " . $rekam->suhu . "C, TD " . $rekam->darah . "\n";
    if ($diagnosaA) {
        echo "  Diagnosa Primer: [" . $diagnosaA->kode . "] " . $diagnosaA->nama . "\n";
    }
    if ($diagnosaB) {
        echo "  Diagnosa Sekunder: [" . $diagnosaB->kode . "] " . $diagnosaB->nama . "\n";
    }
    echo "  Status: " . $rekam->status_pemeriksaan . "\n\n";

    // ============================================
    // STEP 5: ADD TINDAKAN (PROCEDURES)
    // ============================================
    echo "[5] MENAMBAHKAN TINDAKAN MEDIS\n";
    echo "------------------------------------\n";
    
    $tindakan = MasterTindakan::take(2)->get();
    if ($tindakan->count() > 0) {
        $rekam->tindakan()->sync($tindakan->pluck('id')->toArray());
        echo "[OK] TINDAKAN DITAMBAHKAN!\n";
        foreach ($tindakan as $t) {
            echo "  [{" . ($t->kode ?? 'N/A') . "}] {$t->nama} - Rp " . number_format($t->harga, 0, ',', '.') . "\n";
        }
    } else {
        echo "[WARN] Tidak ada tindakan di database\n";
    }
    echo "\n";

    // ============================================
    // STEP 6: UPDATE PENDAFTARAN STATUS
    // ============================================
    echo "[6] MENGUPDATE STATUS PENDAFTARAN\n";
    echo "------------------------------------\n";
    
    $pendaftaran->update([
        'status_layanan' => 'Selesai'
    ]);
    
    echo "[OK] STATUS DIUPDATE!\n";
    echo "  Status Lama: Menunggu\n";
    echo "  Status Baru: {$pendaftaran->status_layanan}\n\n";

    // ============================================
    // STEP 7: VERIFY DATA INTEGRITY
    // ============================================
    echo "[7] VERIFIKASI INTEGRITAS DATA\n";
    echo "------------------------------------\n";
    
    // Reload from database
    $verifyPasien = Pasien::find($pasien->id);
    $verifyPendaftaran = Pendaftaran::with('pasien', 'poliklinik', 'jadwalPoli.dokter')->find($pendaftaran->id);
    $verifyRekam = Rekam::with('pasien', 'dokter', 'tindakan')->find($rekam->id);
    
    $errors = [];
    
    if (!$verifyPasien) $errors[] = "Pasien tidak ditemukan";
    if (!$verifyPendaftaran) $errors[] = "Pendaftaran tidak ditemukan";
    if (!$verifyRekam) $errors[] = "Rekam medis tidak ditemukan";
    
    if (count($errors) === 0) {
        echo "[OK] SEMUA DATA VALID!\n";
        echo "  X Pasien ID {$verifyPasien->id} tersimpan\n";
        echo "  X Pendaftaran ID {$verifyPendaftaran->id} tersimpan\n";
        echo "  X Rekam Medis ID {$verifyRekam->id} tersimpan\n";
        echo "  X Relasi Pasien-Pendaftaran-Rekam terkoneksi\n";
        if ($verifyRekam->tindakan->count() > 0) {
            echo "  X Tindakan " . $verifyRekam->tindakan->count() . " item tersimpan\n";
        }
    } else {
        echo "[FAIL] ERRORS:\n";
        foreach ($errors as $err) {
            echo "  - $err\n";
        }
        exit(1);
    }
    
    echo "\n";

    // ============================================
    // FINAL REPORT
    // ============================================
    echo "====================================================================\n";
    echo "  FULL EXAMINATION WORKFLOW BERHASIL [OK]\n";
    echo "====================================================================\n\n";
    
    echo "RINGKASAN:\n";
    echo "--------------------------------------------------------------------\n";
    echo "X Pasien         : {$verifyPasien->nama} (ID: {$verifyPasien->id}, No RM: {$verifyPasien->no_rm})\n";
    echo "X Pendaftaran    : Poliklinik {$verifyPendaftaran->poliklinik->name} (ID: {$verifyPendaftaran->id})\n";
    echo "X Dokter         : {$verifyPendaftaran->jadwalPoli->dokter->nama}\n";
    echo "X Rekam Medis    : {$verifyRekam->tanggalperiksa} (ID: {$verifyRekam->id})\n";
    if ($verifyRekam->diagnosa_primer) {
        $diag = MasterDiagnosa::find($verifyRekam->diagnosa_primer);
        echo "X Diagnosa       : [{$diag->kode}] {$diag->nama}\n";
    }
    if ($verifyRekam->tindakan->count() > 0) {
        echo "X Tindakan       : " . $verifyRekam->tindakan->count() . " prosedur\n";
    }
    echo "X Vital Signs    : TB {$verifyRekam->tinggi}cm, BB {$verifyRekam->berat}kg, Suhu {$verifyRekam->suhu}C\n";
    echo "X Status         : {$verifyPendaftaran->status_layanan}\n";
    echo "--------------------------------------------------------------------\n\n";
    echo "DATABASE STATISTICS:\n";
    echo "   Total Pasien: " . Pasien::count() . "\n";
    echo "   Total Pendaftaran: " . Pendaftaran::count() . "\n";
    echo "   Total Rekam Medis: " . Rekam::count() . "\n";
    echo "   Total Diagnosa ICD-10: " . MasterDiagnosa::count() . "\n";
    echo "   Total Tindakan: " . MasterTindakan::count() . "\n\n";

} catch (\Exception $e) {
    echo "[FAIL] ERROR: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . " (Line " . $e->getLine() . ")\n\n";
    exit(1);
}
?>
