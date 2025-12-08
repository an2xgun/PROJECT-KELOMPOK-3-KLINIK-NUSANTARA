<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClinicDataSeeder extends Seeder
{
    /**
     * Seed database dengan data lengkap untuk setiap tabel
     */
    public function run(): void
    {
        // 1. Seed polikliniks (Poli Umum, Gigi, Kandungan)
        DB::table('polikliniks')->insertOrIgnore([
            ['id' => 1, 'name' => 'Poliklinik Umum', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Poliklinik Gigi', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Poliklinik Kandungan', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Seed dokters dengan referensi ke polikliniks
        DB::table('dokters')->insertOrIgnore([
            ['id' => 1, 'nama' => 'Dr. Nur Anggun', 'alamat' => 'Jln. Danau Toba No. 10', 'poliklinik_id' => 1, 'telepon' => '085377378111', 'jadwalpraktek' => 'Senin-Jumat', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama' => 'Dr. Nadhifa', 'alamat' => 'Jln. Merdeka No. 20', 'poliklinik_id' => 1, 'telepon' => '085377378122', 'jadwalpraktek' => 'Selasa-Sabtu', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama' => 'Dr. Bambang Sutopo', 'alamat' => 'Jln. Ahmad Yani No. 15', 'poliklinik_id' => 2, 'telepon' => '085377378123', 'jadwalpraktek' => 'Senin-Kamis', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama' => 'Dr. Sri Handayani', 'alamat' => 'Jln. Hayam Wuruk No. 5', 'poliklinik_id' => 3, 'telepon' => '085377378124', 'jadwalpraktek' => 'Senin-Jumat', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. Seed master_tindakan (tindakan medis)
        DB::table('master_tindakan')->insertOrIgnore([
            ['id' => 1, 'kode' => 'TND001', 'nama' => 'Konsultasi Umum', 'harga' => 50000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'kode' => 'TND002', 'nama' => 'Pemeriksaan Tekanan Darah', 'harga' => 15000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'kode' => 'TND003', 'nama' => 'Pemeriksaan Gigi Lengkap', 'harga' => 75000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'kode' => 'TND004', 'nama' => 'Scaling Gigi', 'harga' => 150000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'kode' => 'TND005', 'nama' => 'Pemeriksaan USG', 'harga' => 200000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'kode' => 'TND006', 'nama' => 'Tes Darah Lengkap', 'harga' => 150000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'kode' => 'TND007', 'nama' => 'Injeksi', 'harga' => 50000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'kode' => 'TND008', 'nama' => 'Vaksinasi', 'harga' => 100000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. Seed master_diagnosa
        DB::table('master_diagnosa')->insertOrIgnore([
            ['id' => 1, 'kode' => 'DGN001', 'nama' => 'Demam Biasa', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'kode' => 'DGN002', 'nama' => 'Influenza', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'kode' => 'DGN003', 'nama' => 'Sakit Kepala', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'kode' => 'DGN004', 'nama' => 'Caries Gigi', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'kode' => 'DGN005', 'nama' => 'Gingivitis', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'kode' => 'DGN006', 'nama' => 'Kehamilan Normal Trimester 1', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'kode' => 'DGN007', 'nama' => 'Hipertensi', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'kode' => 'DGN008', 'nama' => 'Diabetes Melitus Tipe 2', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 5. Seed jadwals (jadwal dokter per hari)
        DB::table('jadwals')->insertOrIgnore([
            ['id' => 1, 'jadwalpraktek' => '07:00-14:00', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'jadwalpraktek' => '09:00-16:00', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'jadwalpraktek' => '12:00-18:00', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'jadwalpraktek' => '14:00-20:00', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 6. Seed jadwal_polis (jadwal spesifik per poliklinik)
        DB::table('jadwal_polis')->insertOrIgnore([
            ['id' => 1, 'poliklinik_id' => 1, 'dokter_id' => 1, 'hari' => 'Senin', 'jam_mulai' => '08:00:00', 'jam_selesai' => '12:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'poliklinik_id' => 1, 'dokter_id' => 1, 'hari' => 'Rabu', 'jam_mulai' => '14:00:00', 'jam_selesai' => '17:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'poliklinik_id' => 1, 'dokter_id' => 2, 'hari' => 'Selasa', 'jam_mulai' => '09:00:00', 'jam_selesai' => '12:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'poliklinik_id' => 1, 'dokter_id' => 2, 'hari' => 'Kamis', 'jam_mulai' => '15:00:00', 'jam_selesai' => '18:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'poliklinik_id' => 2, 'dokter_id' => 3, 'hari' => 'Senin', 'jam_mulai' => '09:00:00', 'jam_selesai' => '12:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'poliklinik_id' => 2, 'dokter_id' => 3, 'hari' => 'Rabu', 'jam_mulai' => '14:00:00', 'jam_selesai' => '17:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'poliklinik_id' => 3, 'dokter_id' => 4, 'hari' => 'Selasa', 'jam_mulai' => '10:00:00', 'jam_selesai' => '13:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'poliklinik_id' => 3, 'dokter_id' => 4, 'hari' => 'Jumat', 'jam_mulai' => '15:00:00', 'jam_selesai' => '18:00:00', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 7. Seed pasiens
        DB::table('pasiens')->insertOrIgnore([
            ['id' => 1, 'no_rm' => '0001', 'kodepasien' => '121299', 'nama' => 'Supardi Ansyah', 'alamat' => 'Jln. Tawang Mangu No. 5', 'lahir' => '1999-12-12', 'nik' => '0730408574712012', 'kelamin' => 'Laki-laki', 'telepon' => '087646463722', 'agama' => 'Islam', 'pendidikan' => 'S1', 'pekerjaan' => 'Karyawan Swasta', 'golongan_darah' => 'O', 'jenis_pasien' => 'Umum', 'provinsi' => 'Jawa Timur', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'no_rm' => '0002', 'kodepasien' => '021589', 'nama' => 'Nurrahma Harris', 'alamat' => 'Jln. Sudirman No. 12', 'lahir' => '1989-02-15', 'nik' => '1234567890123456', 'kelamin' => 'Perempuan', 'telepon' => '081234567890', 'agama' => 'Islam', 'pendidikan' => 'S1', 'pekerjaan' => 'Guru', 'golongan_darah' => 'A', 'jenis_pasien' => 'BPJS', 'provinsi' => 'Jawa Timur', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'no_rm' => '0003', 'kodepasien' => '051690', 'nama' => 'Rina Kartikasari', 'alamat' => 'Jln. Ahmad Yani No. 25', 'lahir' => '1990-05-16', 'nik' => '9876543210987654', 'kelamin' => 'Perempuan', 'telepon' => '087987654321', 'agama' => 'Kristen', 'pendidikan' => 'D3', 'pekerjaan' => 'Perawat', 'golongan_darah' => 'B', 'jenis_pasien' => 'Asuransi', 'provinsi' => 'Jawa Timur', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'no_rm' => '0004', 'kodepasien' => '311289', 'nama' => 'Budi Santoso', 'alamat' => 'Jln. Hayam Wuruk No. 8', 'lahir' => '1989-03-31', 'nik' => '5555666677778888', 'kelamin' => 'Laki-laki', 'telepon' => '085555666677', 'agama' => 'Islam', 'pendidikan' => 'SMA', 'pekerjaan' => 'Sopir', 'golongan_darah' => 'AB', 'jenis_pasien' => 'Umum', 'provinsi' => 'Jawa Timur', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'no_rm' => '0005', 'kodepasien' => '211191', 'nama' => 'Siti Aminah', 'alamat' => 'Jln. Merdeka No. 30', 'lahir' => '1991-02-21', 'nik' => '1111222233334444', 'kelamin' => 'Perempuan', 'telepon' => '081234567899', 'agama' => 'Islam', 'pendidikan' => 'S1', 'pekerjaan' => 'Pedagang', 'golongan_darah' => 'O', 'jenis_pasien' => 'BPJS', 'provinsi' => 'Jawa Timur', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 8. Seed users (login accounts)
        DB::table('users')->insertOrIgnore([
            [
                'id' => 1,
                'name' => 'Admin Klinik',
                'email' => 'admin@clinic.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_superadmin' => 1,
                'is_admin' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'name' => 'Dr. Nur Anggun',
                'email' => 'dokter@clinic.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'dokter',
                'is_superadmin' => 0,
                'is_admin' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'name' => 'Petugas Pendaftaran',
                'email' => 'petugas@clinic.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'petugas_pendaftaran',
                'is_superadmin' => 0,
                'is_admin' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Removed perawat user as role 'perawat' is deprecated
            [
                'id' => 5,
                'name' => 'Kasir Klinik',
                'email' => 'kasir@clinic.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'kasir',
                'is_superadmin' => 0,
                'is_admin' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 6,
                'name' => 'Apoteker Klinik',
                'email' => 'apoteker@clinic.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'apoteker',
                'is_superadmin' => 0,
                'is_admin' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 9. Seed obat (medicines)
        DB::table('obats')->insertOrIgnore([
            ['id' => 1, 'nama' => 'Paracetamol 500mg', 'stok' => 100, 'harga' => 5000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama' => 'Amoxicillin 500mg', 'stok' => 50, 'harga' => 15000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama' => 'Ibuprofen 200mg', 'stok' => 75, 'harga' => 8000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama' => 'Omeprazole 20mg', 'stok' => 30, 'harga' => 12000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama' => 'Metformin 500mg', 'stok' => 40, 'harga' => 8000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nama' => 'Vitamin C 500mg', 'stok' => 200, 'harga' => 3000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'nama' => 'Antihistamin', 'stok' => 60, 'harga' => 10000, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'nama' => 'Cough Syrup', 'stok' => 45, 'harga' => 25000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 10. Seed no_rm_sequences
        DB::table('no_rm_sequences')->insertOrIgnore([
            ['id' => 1, 'next_no_rm' => 6, 'created_at' => now(), 'updated_at' => now()],
        ]);

        echo "\n✅ Seeding database selesai dengan data lengkap!\n";
        echo "📊 Data yang di-seed:\n";
        echo "   - 3 Poliklinik\n";
        echo "   - 4 Dokter\n";
        echo "   - 8 Tindakan Medis\n";
        echo "   - 8 Diagnosa\n";
        echo "   - 8 Jadwal Poliklinik\n";
        echo "   - 5 Pasien dengan No RM (0001-0005)\n";
        echo "   - 5 User Accounts (Admin, Dokter, Petugas, Kasir, Apoteker)\n";
        echo "   - 8 Obat-obatan dengan stok\n\n";
    }
}
