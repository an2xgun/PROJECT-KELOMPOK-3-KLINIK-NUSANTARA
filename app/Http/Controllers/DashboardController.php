<?php

namespace App\Http\Controllers;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Poliklinik;


class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'title' => 'Dashboard',

            'total_pasien' => Pasien::count(),

            'menunggu' => Pendaftaran::where('status', 'Menunggu')->count(),
            'sedang_dilayani' => Pendaftaran::where('status', 'Sedang Dilayani')->count(),
            'selesai' => Pendaftaran::where('status', 'Selesai')->count(),

            'statistik_poli' => Poliklinik::withCount([
                'pendaftaran as total',/
                'pendaftaran as menunggu' => fn($q)=>$q->where('status','Menunggu'),
                'pendaftaran as sedang_dilayani' => fn($q)=>$q->where('status','Sedang Dilayani'),
                'pendaftaran as selesai' => fn($q)=>$q->where('status','Selesai'),            ])->get()
        ]);
    }
}
