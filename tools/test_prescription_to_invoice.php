<?php
// tools/test_prescription_to_invoice.php
// Skrip tinker untuk membuat pasien/rekam/resep, lalu memproses resep dan memastikan invoice dibuat.

use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Rekam;
use App\Models\Obat;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Invoice;
use App\Models\User;
use App\Http\Controllers\PrescriptionController;
use Illuminate\Http\Request;

echo "=== START TEST: Prescription -> Dispense -> Invoice ===\n";

// Mock user authentication sebagai apoteker
$user = User::where('role', 'apoteker')->first();

if (!$user) {
    // Fallback: ambil user pertama dan set rolenya
    $user = User::first();
    if ($user) {
        $user->update(['role' => 'apoteker']);
    }
}

if (!$user) {
    echo "ERROR: Tidak ada user tersedia untuk autentikasi\n";
    exit;
}

auth()->setUser($user);
echo "User login: {$user->name} (role: {$user->role})\n";

// 1) Buat atau ambil pasien
$pasien = Pasien::first() ?: Pasien::create([
    'nama' => 'Test Pasien',
    'tanggal_lahir' => '1990-01-01',
    'jenis_kelamin' => 'L'
]);

echo "Pasien ID: {$pasien->id}\n";

// 2) Buat pendaftaran minimal
$pendaftaran = Pendaftaran::create([
    'pasien_id' => $pasien->id,
    'tanggal' => date('Y-m-d'),
    'status_layanan' => 'Menunggu',
    'poliklinik_id' => 1,
    'dokter_id' => null
]);

echo "Pendaftaran ID: {$pendaftaran->id}\n";

// 3) Buat rekam
$rekam = Rekam::create([
    'id_pasien' => $pasien->id,
    'pendaftaran_id' => $pendaftaran->id,
    'nomorantrian' => 'TEST-' . date('YmdHis'),
    'layanan' => 'Pemeriksaan Umum',
    'keluhan' => 'Keluhan test',
    'id_dokter' => 1,
    'diagnosa' => null
]);

echo "Rekam ID: {$rekam->id}\n";

// 4) Pastikan ada obat dengan stok
$obat = Obat::first();
if (!$obat) {
    $obat = Obat::create([
        'nama' => 'Obat Test',
        'stok' => 100,
        'harga' => 5000
    ]);
    echo "Membuat obat test ID: {$obat->id}\n";
} else {
    echo "Obat tersedia, ID: {$obat->id}, stok: {$obat->stok}\n";
}

// 5) Buat prescription oleh dokter
$prescription = Prescription::create([
    'rekam_id' => $rekam->id,
    'dokter_id' => null,
    'status' => 'Pending'
]);

// 6) Tambah item resep
$item = PrescriptionItem::create([
    'prescription_id' => $prescription->id,
    'obat_id' => $obat->id,
    'jumlah' => 2,
    'dosis' => '1x2',
    'harga_satuan' => $obat->harga,
    'subtotal' => $obat->harga * 2
]);

echo "Prescription ID: {$prescription->id}, Item ID: {$item->id}\n";

// 7) Proses resep via controller (sebagai apoteker) — di tinker kita panggil method langsung
$controller = new PrescriptionController();

try {
    // panggil process (akan men-decrement stok, ubah status, dan membuat invoice)
    $resp = $controller->process($prescription->id, new Request());

    // Cek invoice terkait rekam
    $invoice = Invoice::where('rekam_id', $rekam->id)->orderBy('id', 'desc')->first();
    if ($invoice) {
        echo "Invoice dibuat: ID={$invoice->id}, total={$invoice->total}, status={$invoice->status}\n";
        echo "Invoice items:\n";
        foreach ($invoice->items as $invItem) {
            echo " - {$invItem->name}: {$invItem->amount}\n";
        }
    } else {
        echo "Gagal: Invoice tidak ditemukan untuk rekam_id={$rekam->id}\n";
    }

    // Cek stok obat
    $obat->refresh();
    echo "Stok obat sekarang: {$obat->stok}\n";

    echo "=== TEST FINISHED ===\n";
} catch (Exception $e) {
    echo "ERROR during processing: " . $e->getMessage() . "\n";
}
