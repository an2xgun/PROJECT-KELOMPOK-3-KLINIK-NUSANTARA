<?php
namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Poliklinik;
use App\Models\JadwalDokter;
use Illuminate\Http\Request;

class PemeriksaanController extends Controller
{
    public function show($pasien_id)
    {
        $pasien = Pasien::findOrFail($pasien_id);
        $poli = Poliklinik::all();
        $jadwal = JadwalDokter::with('dokter', 'poli')->get();

        return view('pemeriksaan.show', compact('pasien', 'poli', 'jadwal'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'pasien_id'        => 'required',
            'poli_id'          => 'required',
            'jadwal_id'        => 'required',
            'tgl_kunjungan'    => 'required',
            'jenis_pembayaran' => 'required',
        ]);

        // Simpan ke tabel pemeriksaan
        \App\Models\Pemeriksaan::create($r->all());

        return redirect()->route('pasien.index')->with('success', 'Pemeriksaan disimpan');
    }
}
