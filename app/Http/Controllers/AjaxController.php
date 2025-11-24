<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\JadwalPoli;

class AjaxController extends Controller
{
    public function jadwalByPoli($poliId)
    {
        $jadwals = JadwalPoli::with('dokter')->where('poliklinik_id',$poliId)->get();
        return response()->json($jadwals);
    }
}
