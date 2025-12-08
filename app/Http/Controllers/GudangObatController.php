<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GudangObatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Halaman Apotik
    public function apotik()
    {
        return view('gudang_obat.apotik');
    }

    // Halaman Apotik Retail
    public function apotikRetail()
    {
        return view('gudang_obat.apotik_retail');
    }

    // Halaman Farmasi
    public function farmasi()
    {
        return view('gudang_obat.farmasi');
    }

    // Halaman Master Obat
    public function masterObat()
    {
        return view('gudang_obat.master_obat');
    }
}
