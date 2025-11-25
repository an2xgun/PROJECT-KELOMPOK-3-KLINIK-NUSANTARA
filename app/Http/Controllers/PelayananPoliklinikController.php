<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien; // atau model antrian, sesuaikan

class PelayananPoliklinikController extends Controller
{
    public function poliUmum()
    {
        $pasien = Pasien::where('poli', 'umum')->get();
        return view('poliklinik.poli_umum', compact('pasien'));
    }

    public function poliGigi()
    {
        $pasien = Pasien::where('poli', 'gigi')->get();
        return view('poliklinik.poli_gigi', compact('pasien'));
    }

    public function poliKandungan()
    {
        $pasien = Pasien::where('poli', 'kandungan')->get();
        return view('poliklinik.poli_kandungan', compact('pasien'));
    }
}
