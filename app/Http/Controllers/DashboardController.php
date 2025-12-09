<?php

namespace App\Http\Controllers;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Poliklinik;
use App\Models\Rekam;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        $data = [
            'title' => 'Dashboard',
            'total_pasien' => Pasien::count(),
            'menunggu' => Pendaftaran::where('status_layanan', 'Menunggu')->count(),
            'sedang_dilayani' => Pendaftaran::where('status_layanan', 'Sedang Dilayani')->count(),
            'selesai' => Pendaftaran::where('status_layanan', 'Selesai')->count(),
            'statistik_poli' => Poliklinik::with('pendaftaran')->get()->map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'total' => $p->pendaftaran->count(),
                    'menunggu' => $p->pendaftaran->where('status_layanan', 'Menunggu')->count(),
                    'sedang_dilayani' => $p->pendaftaran->where('status_layanan', 'Sedang Dilayani')->count(),
                    'selesai' => $p->pendaftaran->where('status_layanan', 'Selesai')->count(),
                ];
            }),
            'total_rekam' => Rekam::count(),
            'total_invoice' => Invoice::count(),
        ];

        // Role-specific data
        if ($user->role === 'dokter') {
            $data['rekam_hari_ini'] = Rekam::whereDate('created_at', today())->count();
        } elseif ($user->role === 'apoteker') {
            $data['resep_pending'] = \App\Models\Prescription::where('status', 'pending')->count();
        } elseif ($user->role === 'kasir') {
            $data['invoice_pending'] = Invoice::where('status', 'pending')->count();
        } elseif ($user->role === 'petugas_pendaftaran') {
            $data['pendaftaran_hari_ini'] = Pendaftaran::whereDate('created_at', today())->count();
        }

        return view('dashboard', $data);
    }
}
